<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PromotionController;

// Admin routes (owner only)
Route::middleware(['auth', 'active', 'owner'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('promotions', PromotionController::class);
    Route::post('promotions/{promotion}/toggle', [PromotionController::class, 'toggle'])->name('promotions.toggle');
});
