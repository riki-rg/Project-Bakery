<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::orderByDesc('created_at')->paginate(10);
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('admin.promotions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'                     => 'required|string|max:50|unique:promotions,code',
            'name'                     => 'required|string|max:255',
            'description'              => 'nullable|string',
            'type'                     => 'required|in:percentage,fixed,shipping',
            'value'                    => 'required|integer|min:0',
            'max_discount'             => 'nullable|integer|min:0',
            'min_spend'                => 'nullable|integer|min:0',
            'quota'                    => 'nullable|integer|min:1',
            'usage_limit_per_customer' => 'nullable|integer|min:1',
            'first_time_customer_only' => 'boolean',
            'starts_at'                => 'nullable|date',
            'ends_at'                  => 'nullable|date|after_or_equal:starts_at',
            'is_active'                => 'boolean',
        ]);

        Promotion::create($validated);

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Promo berhasil dibuat.');
    }

    public function edit(Promotion $promotion)
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'code'                     => 'required|string|max:50|unique:promotions,code,' . $promotion->id,
            'name'                     => 'required|string|max:255',
            'description'              => 'nullable|string',
            'type'                     => 'required|in:percentage,fixed,shipping',
            'value'                    => 'required|integer|min:0',
            'max_discount'             => 'nullable|integer|min:0',
            'min_spend'                => 'nullable|integer|min:0',
            'quota'                    => 'nullable|integer|min:1',
            'usage_limit_per_customer' => 'nullable|integer|min:1',
            'first_time_customer_only' => 'boolean',
            'starts_at'                => 'nullable|date',
            'ends_at'                  => 'nullable|date|after_or_equal:starts_at',
            'is_active'                => 'boolean',
        ]);

        $promotion->update($validated);

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Promo berhasil dihapus.');
    }

    public function toggle(Promotion $promotion)
    {
        $promotion->update(['is_active' => ! $promotion->is_active]);

        $status = $promotion->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Promo berhasil {$status}.");
    }
}
