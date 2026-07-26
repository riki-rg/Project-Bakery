<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\OrderStatusHistory;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private MidtransService $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    /**
     * POST /api/v1/payments/{orderId}/create
     * Buat transaksi Midtrans Snap
     */
    public function create(Request $request, $orderId)
    {
        $order = $request->user()->orders()->findOrFail($orderId);

        if ($order->payment_status !== Order::PAYMENT_UNPAID) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan sudah dibayar atau tidak valid.',
            ], 422);
        }

        // Cek apakah sudah ada payment pending
        $existingPayment = $order->payment;
        if ($existingPayment && $existingPayment->status === 'pending' && $existingPayment->snap_token) {
            return response()->json([
                'success' => true,
                'data'    => [
                    'snap_token'   => $existingPayment->snap_token,
                    'redirect_url' => $existingPayment->redirect_url,
                    'order_id'     => $order->order_number,
                ],
            ]);
        }

        // Buat transaksi Midtrans
        $params = [
            'transaction_details' => [
                'order_id'     => $order->order_number,
                'gross_amount' => (int) $order->grand_total,
            ],
            'customer_details' => [
                'first_name' => $order->recipient_snapshot['name']  ?? 'Customer',
                'email'      => $order->recipient_snapshot['email'] ?? '',
                'phone'      => $order->recipient_snapshot['phone'] ?? '',
            ],
            'item_details' => $order->items->map(fn($item) => [
                'id'       => (string) $item->product_id,
                'price'    => (int) $item->unit_price,
                'quantity' => $item->quantity,
                'name'     => $item->product_snapshot['name'] ?? 'Product',
            ])->toArray(),
        ];

        try {
            $result = $this->midtrans->createSnapTransaction($params);

            // Simpan payment record
            $payment = Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'provider'          => 'midtrans',
                    'provider_order_id' => $order->order_number,
                    'amount'            => $order->grand_total,
                    'status'            => 'pending',
                    'snap_token'        => $result['token'],
                    'redirect_url'      => $result['redirect_url'],
                    'expires_at'        => now()->addHours(24),
                ]
            );

            return response()->json([
                'success' => true,
                'data'    => [
                    'snap_token'   => $result['token'],
                    'redirect_url' => $result['redirect_url'],
                    'order_id'     => $order->order_number,
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi pembayaran. Coba lagi.',
            ], 500);
        }
    }

    /**
     * POST /api/v1/payments/webhook
     * Handle webhook dari Midtrans (server-to-server)
     */
    public function webhook(Request $request)
    {
        $payload = $request->all();

        $orderId        = $payload['order_id']           ?? null;
        $statusCode     = $payload['status_code']        ?? null;
        $grossAmount    = $payload['gross_amount']       ?? null;
        $signatureKey   = $payload['signature_key']      ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $paymentType    = $payload['payment_type']       ?? null;

        // Verifikasi signature
        $serverKey = config('services.midtrans.server_key');
        $validSignature = $this->midtrans->verifySignature(
            $orderId,
            $statusCode,
            $grossAmount,
            $serverKey,
            $signatureKey
        );

        if (! $validSignature) {
            Log::warning('Midtrans webhook invalid signature', ['order_id' => $orderId]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Cari order
        $order = Order::where('order_number', $orderId)->first();
        if (! $order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        DB::transaction(function () use ($order, $payload, $transactionStatus, $paymentType) {
            $payment = Payment::where('order_id', $order->id)->first();

            $newPaymentStatus = match ($transactionStatus) {
                'capture', 'settlement' => 'paid',
                'pending'               => 'pending',
                'deny', 'cancel', 'expire', 'failure' => 'failed',
                'refund', 'partial_refund' => 'refunded',
                default                 => 'pending',
            };

            // Update payment
            if ($payment) {
                $payment->update([
                    'status'     => $newPaymentStatus,
                    'method'     => $paymentType,
                    'paid_at'    => $newPaymentStatus === 'paid' ? now() : null,
                    'metadata'   => $payload,
                ]);
            }

            // Update order status
            $newOrderStatus = match ($newPaymentStatus) {
                'paid'     => Order::STATUS_PAYMENT_VERIFIED,
                'failed'   => Order::STATUS_CANCELLED,
                'refunded' => Order::STATUS_REFUND,
                default    => $order->status,
            };

            $oldStatus = $order->status;

            if ($newOrderStatus !== $oldStatus) {
                $order->update([
                    'status'         => $newOrderStatus,
                    'payment_status' => $newPaymentStatus,
                ]);

                OrderStatusHistory::create([
                    'order_id'    => $order->id,
                    'from_status' => $oldStatus,
                    'to_status'   => $newOrderStatus,
                    'actor_type'  => 'system',
                    'note'        => "Pembayaran {$newPaymentStatus} via Midtrans",
                ]);
            }
        });

        return response()->json(['message' => 'OK']);
    }

    /**
     * GET /api/v1/payments/{orderId}/status
     * Polling status pembayaran dari React
     */
    public function status(Request $request, $orderId)
    {
        $order = $request->user()->orders()->with('payment')->findOrFail($orderId);

        return response()->json([
            'success' => true,
            'data'    => [
                'order_id'       => $order->order_number,
                'order_status'   => $order->status,
                'payment_status' => $order->payment_status,
                'payment'        => $order->payment ? [
                    'method'    => $order->payment->method,
                    'status'    => $order->payment->status,
                    'paid_at'   => $order->payment->paid_at,
                    'expires_at' => $order->payment->expires_at,
                ] : null,
            ],
        ]);
    }
}
