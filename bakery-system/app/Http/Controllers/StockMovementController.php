<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::with(['product', 'user']);

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('date_from')) {
            $query->whereDate('occurred_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('occurred_at', '<=', $request->date_to);
        }

        $movements = $query->orderBy('occurred_at', 'desc')->paginate(20);
        $products = Product::where('is_active', true)->get();

        return view('stock-movements.index', compact('movements', 'products'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->get();
        return view('stock-movements.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'type' => ['required', 'in:in,out,adjustment'],
            'quantity' => ['required', 'integer', 'min:1'],
            'note' => ['nullable', 'string'],
        ]);

        try {
            DB::beginTransaction();

            $product = Product::lockForUpdate()->findOrFail($request->product_id);
            $stockBefore = $product->current_stock;

            // Calculate new stock based on type
            if ($request->type === 'in') {
                $stockAfter = $stockBefore + $request->quantity;
            } elseif ($request->type === 'out') {
                if ($stockBefore < $request->quantity) {
                    return back()->withErrors(['quantity' => 'Insufficient stock. Available: ' . $stockBefore])->withInput();
                }
                $stockAfter = $stockBefore - $request->quantity;
            } else { // adjustment
                $stockAfter = $request->quantity;
            }

            // Update product stock
            $product->update(['current_stock' => $stockAfter]);

            // Create stock movement record
            StockMovement::create([
                'product_id' => $request->product_id,
                'user_id' => auth()->id(),
                'type' => $request->type,
                'quantity' => $request->quantity,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'note' => $request->note,
                'occurred_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('stock-movements.index')->with('success', 'Stock movement recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to record stock movement.'])->withInput();
        }
    }
}
