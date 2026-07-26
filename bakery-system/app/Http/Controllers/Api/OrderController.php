<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * GET /api/v1/orders
     */
    public function index(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->with(['items', 'payment'])
            ->latest()
            ->paginate(10);

        return response()->json(['success' => true, 'data' => $orders]);
    }

    /**
     * GET /api/v1/orders/{id}
     */
    public function show(Request $request, $id)
    {
        $order = $request->user()
            ->orders()
            ->with(['items', 'statusHistories', 'payment'])
            ->findOrFail($id);

        return response()->json(['success' => true, 'data' => $order]);
    }

    /**
     * POST /api/v1/orders
     * Buat order dari cart
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idempotency_key'        => 'required|string|max:100',
            'fulfillment_type'       => 'required|in:delivery,pickup',
            'recipient_name'         => 'required|string',
            'recipient_email'        => 'required|email',
            'recipient_phone'        => 'required|string',
            'notes'                  => 'nullable|string',
            'address_line'           => 'required_if:fulfillment_type,delivery|nullable|string',
            'city'                   => 'nullable|string',
            'postal_code'            => 'nullable|string',
            'latitude'               => 'nullable|numeric',
            'longitude'              => 'nullable|numeric',
            'courier_note'           => 'nullable|string',
            'voucher_code'           => 'nullable|string',
            'payment_method'         => 'required|in:midtrans,cod',
            'scheduled_at'           => 'nullable|date|after:now',
            'agree_terms'            => 'required|accepted',
        ]);

        // Cegah order ganda dengan idempotency key
        $existing = Order::where('idempotency_key', $validated['idempotency_key'])->first();
        if ($existing) {
            return response()->json(['success' => true, 'data' => $existing->load(['items', 'payment'])]);
        }

        $customer = $request->user();

        // Ambil cart
        $cart = Cart::where('customer_id', $customer->id)
            ->with('items.product')
            ->firstOrFail();

        if ($cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang kosong.',
            ], 422);
        }

        // Validasi stok ulang (server-side)
        $stockErrors = [];
        foreach ($cart->items as $item) {
            if (! $item->product || ! $item->product->is_active) {
                $stockErrors[] = "{$item->product?->name} tidak tersedia.";
            } elseif ($item->product->current_stock < $item->quantity) {
                $stockErrors[] = "Stok {$item->product->name} hanya tersisa {$item->product->current_stock}.";
            }
        }

        if (! empty($stockErrors)) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi stok gagal.',
                'errors'  => $stockErrors,
            ], 422);
        }

        // Hitung harga
        $subtotal    = $cart->subtotal; // dalam rupiah (int)
        $discount    = 0;
        $deliveryFee = $validated['fulfillment_type'] === 'delivery' ? 15000 : 0;
        $grandTotal  = $subtotal - $discount + $deliveryFee;

        // Diskon voucher (stub)
        if (! empty($validated['voucher_code']) && strtoupper($validated['voucher_code']) === 'PERTAMA') {
            $discount   = min((int) round($subtotal * 0.1), 15000);
            $grandTotal = $subtotal - $discount + $deliveryFee;
        }

        DB::beginTransaction();

        try {
            // Buat Order
            $order = Order::create([
                'order_number'       => Order::generateOrderNumber(),
                'customer_id'        => $customer->id,
                'fulfillment_type'   => $validated['fulfillment_type'],
                'status'             => Order::STATUS_PENDING_PAYMENT,
                'payment_status'     => Order::PAYMENT_UNPAID,
                'recipient_snapshot' => [
                    'name'  => $validated['recipient_name'],
                    'email' => $validated['recipient_email'],
                    'phone' => $validated['recipient_phone'],
                    'notes' => $validated['notes'] ?? null,
                ],
                'address_snapshot' => $validated['fulfillment_type'] === 'delivery' ? [
                    'address_line' => $validated['address_line'],
                    'city'         => $validated['city'] ?? null,
                    'postal_code'  => $validated['postal_code'] ?? null,
                    'latitude'     => $validated['latitude'] ?? null,
                    'longitude'    => $validated['longitude'] ?? null,
                    'courier_note' => $validated['courier_note'] ?? null,
                ] : null,
                'scheduled_at'    => $validated['scheduled_at'] ?? null,
                'subtotal'        => $subtotal,
                'discount'        => $discount,
                'delivery_fee'    => $deliveryFee,
                'service_fee'     => 0,
                'tax'             => 0,
                'grand_total'     => $grandTotal,
                'voucher_code'    => $validated['voucher_code'] ?? null,
                'notes'           => $validated['notes'] ?? null,
                'idempotency_key' => $validated['idempotency_key'],
            ]);

            // Buat OrderItems & kurangi stok
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id'         => $order->id,
                    'product_id'       => $cartItem->product_id,
                    'product_snapshot' => [
                        'id'         => $cartItem->product->id,
                        'name'       => $cartItem->product->name,
                        'slug'       => $cartItem->product->slug,
                        'image_path' => $cartItem->product->image_path,
                        'category'   => $cartItem->product->category?->name,
                    ],
                    'quantity'   => $cartItem->quantity,
                    'unit_price' => (int) ($cartItem->price_snapshot * 100) / 100,
                    'subtotal'   => (int) ($cartItem->price_snapshot * $cartItem->quantity),
                ]);

                // Kurangi stok produk
                $cartItem->product->decrement('current_stock', $cartItem->quantity);
            }

            // Catat history status
            OrderStatusHistory::create([
                'order_id'   => $order->id,
                'from_status' => null,
                'to_status'  => Order::STATUS_PENDING_PAYMENT,
                'actor_type' => 'system',
                'note'       => 'Pesanan dibuat.',
            ]);

            // Buat Payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'provider' => $validated['payment_method'] === 'cod' ? 'cod' : 'midtrans',
                'amount'   => $grandTotal,
                'status'   => 'pending',
            ]);

            // Kosongkan cart
            $cart->items()->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'data'    => $order->load(['items', 'payment']),
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan. Silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * POST /api/v1/orders/{id}/cancel
     */
    public function cancel(Request $request, $id)
    {
        $order = $request->user()->orders()->findOrFail($id);

        if (! $order->canBeCancelled()) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak dapat dibatalkan pada status ini.',
            ], 422);
        }

        $request->validate(['reason' => 'nullable|string|max:500']);

        DB::transaction(function () use ($order, $request) {
            $fromStatus    = $order->status;
            $order->status = Order::STATUS_CANCELLED;
            $order->save();

            // Kembalikan stok
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    Product::find($item->product_id)?->increment('current_stock', $item->quantity);
                }
            }

            OrderStatusHistory::create([
                'order_id'   => $order->id,
                'from_status' => $fromStatus,
                'to_status'  => Order::STATUS_CANCELLED,
                'actor_type' => 'customer',
                'actor_id'   => $request->user()->id,
                'note'       => $request->reason ?? 'Dibatalkan oleh pelanggan.',
            ]);
        });

        return response()->json(['success' => true, 'message' => 'Pesanan berhasil dibatalkan.']);
    }

    /**
     * GET /api/v1/payments/{orderId}/status
     * React polling status pembayaran — TIDAK dari callback browser
     */
    public function paymentStatus(Request $request, $orderId)
    {
        $order = $request->user()
            ->orders()
            ->with('payment')
            ->findOrFail($orderId);

        return response()->json([
            'success' => true,
            'data'    => [
                'order_status'   => $order->status,
                'payment_status' => $order->payment_status,
                'payment'        => $order->payment,
            ],
        ]);
    }
}
