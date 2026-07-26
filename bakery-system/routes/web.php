<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\OrderManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Owner-only routes
    Route::middleware('owner')->group(function () {
        // Employee Management
        Route::resource('employees', EmployeeController::class)->except(['destroy']);
        Route::post('employees/{employee}/deactivate', [EmployeeController::class, 'deactivate'])->name('employees.deactivate');
        Route::post('employees/{employee}/activate', [EmployeeController::class, 'activate'])->name('employees.activate');

        // Category Management
        Route::resource('categories', CategoryController::class);

        // Product Management
        Route::resource('products', ProductController::class);

        // Order Management
        Route::get('orders', [OrderManagementController::class, 'index'])->name('orders.index');
        Route::get('orders/{id}', [OrderManagementController::class, 'show'])->name('orders.show');
        Route::put('orders/{id}/status', [OrderManagementController::class, 'updateStatus'])->name('orders.update-status');

        // Promotions Management
        Route::resource('admin/promotions', \App\Http\Controllers\Admin\PromotionController::class)->except(['show'])->names('admin.promotions');
        Route::post('admin/promotions/{promotion}/toggle', [\App\Http\Controllers\Admin\PromotionController::class, 'toggle'])->name('admin.promotions.toggle');

        // Attendance Correction
        Route::get('attendances/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendances.edit');
        Route::put('attendances/{attendance}', [AttendanceController::class, 'update'])->name('attendances.update');
    });

    // Routes for both Owner and Employee
    // Stock Management
    Route::get('stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');
    Route::get('stock-movements/create', [StockMovementController::class, 'create'])->name('stock-movements.create');
    Route::post('stock-movements', [StockMovementController::class, 'store'])->name('stock-movements.store');

    // Attendance
    Route::get('attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::post('attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check-in');
    Route::post('attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.check-out');
    Route::get('my-attendance', [AttendanceController::class, 'myAttendance'])->name('attendance.my');
});

require __DIR__.'/auth.php';
