<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * GET /api/v1/products
     * Filter: category_id, category_slug, search, sort, in_stock
     */
    public function index(Request $request)
    {
        $query = Product::with('category')
            ->where('is_active', true);

        // Filter kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('category_slug')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category_slug));
        }

        // Pencarian
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter stok
        if ($request->boolean('in_stock')) {
            $query->where('current_stock', '>', 0);
        }

        // Filter harga
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        match ($request->get('sort', 'terbaru')) {
            'harga-asc'  => $query->orderBy('price', 'asc'),
            'harga-desc' => $query->orderBy('price', 'desc'),
            'nama'       => $query->orderBy('name', 'asc'),
            default      => $query->latest(),
        };

        $perPage  = min((int) $request->get('per_page', 15), 50);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $products,
        ]);
    }

    /**
     * GET /api/v1/products/{id}
     * Bisa juga pakai slug: /api/v1/products/{slug}
     */
    public function show($idOrSlug)
    {
        $product = Product::with(['category'])
            ->where('is_active', true)
            ->where(function ($q) use ($idOrSlug) {
                is_numeric($idOrSlug)
                    ? $q->where('id', $idOrSlug)
                    : $q->where('slug', $idOrSlug);
            })
            ->firstOrFail();

        // Ambil ulasan yang dipublikasikan
        $reviews = $product->reviews()
            ->with('customer:id,name')
            ->latest('published_at')
            ->take(10)
            ->get()
            ->map(fn($r) => [
                'id'         => $r->id,
                'rating'     => $r->rating,
                'content'    => $r->content,
                'created_at' => $r->created_at,
                'customer'   => ['name' => $r->customer?->name],
            ]);

        return response()->json([
            'success' => true,
            'data'    => array_merge($product->toArray(), [
                'reviews' => $reviews,
            ]),
        ]);
    }

    /**
     * GET /api/v1/categories
     */
    public function categories()
    {
        $categories = Category::where('is_active', true)
            ->withCount(['products' => fn($q) => $q->where('is_active', true)])
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $categories,
        ]);
    }

    /**
     * GET /api/v1/products/{id}/reviews
     */
    public function reviews(Request $request, $id)
    {
        $product = Product::where('is_active', true)->findOrFail($id);

        $reviews = $product->reviews()
            ->with('customer:id,name')
            ->latest('published_at')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data'    => $reviews,
        ]);
    }
}
