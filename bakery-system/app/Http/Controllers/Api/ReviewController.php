<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * GET /api/v1/reviews/eligible
     * Item dari order selesai yang belum diulas
     */
    public function eligible(Request $request)
    {
        $customer = $request->user();

        $items = OrderItem::whereHas('order', fn($q) => $q
                ->where('customer_id', $customer->id)
                ->where('status', 'completed')
            )
            ->whereDoesntHave('review')
            ->with(['product:id,name,slug,image_path', 'order:id,order_number,created_at'])
            ->get();

        return response()->json(['success' => true, 'data' => $items]);
    }

    /**
     * POST /api/v1/reviews
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_item_id' => 'required|exists:order_items,id',
            'rating'        => 'required|integer|between:1,5',
            'content'       => 'nullable|string|max:1000',
        ]);

        $customer  = $request->user();
        $orderItem = OrderItem::whereHas('order', fn($q) => $q
                ->where('customer_id', $customer->id)
                ->where('status', 'completed')
            )
            ->findOrFail($validated['order_item_id']);

        // Cegah ulasan ganda
        if ($orderItem->review()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu sudah mengulas produk ini.',
            ], 422);
        }

        $review = Review::create([
            'customer_id'   => $customer->id,
            'order_item_id' => $orderItem->id,
            'product_id'    => $orderItem->product_id,
            'rating'        => $validated['rating'],
            'content'       => $validated['content'] ?? null,
            'status'        => 'pending', // moderasi dulu
        ]);

        return response()->json(['success' => true, 'data' => $review], 201);
    }

    /**
     * PUT /api/v1/reviews/{id}
     */
    public function update(Request $request, $id)
    {
        $review = Review::where('customer_id', $request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'rating'  => 'sometimes|integer|between:1,5',
            'content' => 'nullable|string|max:1000',
        ]);

        $review->update(array_merge($validated, ['status' => 'pending'])); // moderasi ulang

        return response()->json(['success' => true, 'data' => $review->fresh()]);
    }

    /**
     * DELETE /api/v1/reviews/{id}
     */
    public function destroy(Request $request, $id)
    {
        Review::where('customer_id', $request->user()->id)->findOrFail($id)->delete();

        return response()->json(['success' => true, 'message' => 'Ulasan dihapus.']);
    }
}
