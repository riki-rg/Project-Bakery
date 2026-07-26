<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    /**
     * GET /api/v1/promotions
     * Menampilkan promo aktif untuk banner storefront
     */
    public function index()
    {
        $promotions = Promotion::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('quota')->orWhereColumn('used_count', '<', 'quota');
            })
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $promotions,
        ]);
    }
}
