<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderManagementController extends Controller
{
    /**
     * Display orders list (owner/manager only)
     */
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'items', 'payment'])
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Search by order number, customer name, or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->paginate(20);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show order detail
     */
    public function show($id)
    {
        $order = Order::with(['customer', 'items.product', 'payment', 'statusHistories'])
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending_payment,payment_verified,processing,ready_pickup,delivering,completed,cancelled',
            'note' => 'nullable|string|max:500',
        ]);

        $order = Order::findOrFail($id);
        $fromStatus = $order->status;

        DB::transaction(function () use ($order, $validated, $fromStatus, $request) {
            $order->status = $validated['status'];
            
            // Update payment status based on order status
            if ($validated['status'] === 'payment_verified') {
                $order->payment_status = Order::PAYMENT_PAID;
            }
            
            $order->save();

            // Record status history
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'from_status' => $fromStatus,
                'to_status' => $validated['status'],
                'actor_type' => 'staff',
                'actor_id' => auth()->id(),
                'note' => $validated['note'] ?? 'Status diperbarui oleh ' . auth()->user()->name,
            ]);

            // If cancelled, restore stock
            if ($validated['status'] === Order::STATUS_CANCELLED) {
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        Product::find($item->product_id)?->increment('current_stock', $item->quantity);
                    }
                }
            }
        });

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }

    /**
     * Get order statistics for dashboard
     */
    public function statistics()
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();

        $stats = [
            'today' => [
                'orders' => Order::whereDate('created_at', $today)->count(),
                'revenue' => Order::whereDate('created_at', $today)
                    ->where('status', Order::STATUS_COMPLETED)
                    ->sum('grand_total'),
                'pending' => Order::whereDate('created_at', $today)
                    ->where('status', Order::STATUS_PENDING_PAYMENT)
                    ->count(),
            ],
            'month' => [
                'orders' => Order::whereDate('created_at', '>=', $thisMonth)->count(),
                'revenue' => Order::whereDate('created_at', '>=', $thisMonth)
                    ->where('status', Order::STATUS_COMPLETED)
                    ->sum('grand_total'),
                'completed' => Order::whereDate('created_at', '>=', $thisMonth)
                    ->where('status', Order::STATUS_COMPLETED)
                    ->count(),
            ],
            'by_status' => Order::select('status', DB::raw('count(*) as count'))
                ->whereDate('created_at', '>=', $thisMonth)
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status'),
        ];

        return $stats;
    }
}
