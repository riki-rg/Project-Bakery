<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ReviewController;

Route::prefix('v1')->group(function () {

    // ── Public — Produk & Kategori ────────────────────────────
    Route::get('/products',              [ProductController::class, 'index']);
    Route::get('/products/{idOrSlug}',   [ProductController::class, 'show']);
    Route::get('/categories',            [ProductController::class, 'categories']);
    Route::get('/products/{id}/reviews', [ProductController::class, 'reviews']);
    Route::get('/promotions',            [\App\Http\Controllers\Api\PromoController::class, 'index']);

    // ── Auth Pelanggan (Sanctum) ───────────────────────────────
    Route::prefix('auth')->group(function () {
        Route::post('/register',       [AuthController::class, 'register']);
        Route::post('/login',          [AuthController::class, 'login']);
        Route::post('/forgot-password',[AuthController::class, 'forgotPassword']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me',      [AuthController::class, 'me']);
        });
    });

    // ── Cart (guest + authenticated) ───────────────────────────
    Route::prefix('cart')->group(function () {
        Route::get('/',              [CartController::class, 'index']);
        Route::post('/items',        [CartController::class, 'addItem']);
        Route::put('/items/{id}',    [CartController::class, 'updateItem']);
        Route::delete('/items/{id}', [CartController::class, 'removeItem']);
        Route::delete('/',           [CartController::class, 'clear']);
        Route::post('/validate',     [CartController::class, 'validate']);
        Route::post('/voucher',      [CartController::class, 'applyVoucher']);
    });

    // ── Midtrans Webhook (public, dari server Midtrans) ─────────
    Route::post('/payments/webhook', [PaymentController::class, 'webhook']);

    // ── Authenticated routes ───────────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {

        // Profil pelanggan
        Route::prefix('customer')->group(function () {
            Route::get('/profile',                    [CustomerController::class, 'profile']);
            Route::put('/profile',                    [CustomerController::class, 'updateProfile']);
            Route::put('/password',                   [CustomerController::class, 'updatePassword']);
            Route::get('/addresses',                  [CustomerController::class, 'addresses']);
            Route::post('/addresses',                 [CustomerController::class, 'storeAddress']);
            Route::put('/addresses/{id}',             [CustomerController::class, 'updateAddress']);
            Route::delete('/addresses/{id}',          [CustomerController::class, 'deleteAddress']);
            Route::patch('/addresses/{id}/default',   [CustomerController::class, 'setDefaultAddress']);
            Route::get('/wishlist',                   [CustomerController::class, 'wishlist']);
            Route::post('/wishlist/toggle',           [CustomerController::class, 'toggleWishlist']);
        });

        // Orders
        Route::prefix('orders')->group(function () {
            Route::get('/',            [OrderController::class, 'index']);
            Route::post('/',           [OrderController::class, 'store']);
            Route::get('/{id}',        [OrderController::class, 'show']);
            Route::post('/{id}/cancel',[OrderController::class, 'cancel']);
        });

        // Payments
        Route::prefix('payments')->group(function () {
            Route::post('/{orderId}/create', [PaymentController::class, 'create']);
            Route::get('/{orderId}/status',  [PaymentController::class, 'status']);
        });

        // Reviews
        Route::prefix('reviews')->group(function () {
            Route::get('/eligible', [ReviewController::class, 'eligible']);
            Route::post('/',        [ReviewController::class, 'store']);
            Route::put('/{id}',     [ReviewController::class, 'update']);
            Route::delete('/{id}',  [ReviewController::class, 'destroy']);
        });
    });
});
