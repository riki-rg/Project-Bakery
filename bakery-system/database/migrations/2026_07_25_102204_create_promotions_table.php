<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed', 'shipping']);
            $table->unsignedBigInteger('value'); // percentage (0-100) or fixed amount in rupiah
            $table->unsignedBigInteger('max_discount')->nullable(); // max discount for percentage
            $table->unsignedBigInteger('min_spend')->default(0);
            $table->unsignedInteger('quota')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->unsignedInteger('usage_limit_per_customer')->default(1);
            $table->json('applicable_categories')->nullable(); // null = all categories
            $table->json('applicable_products')->nullable(); // null = all products
            $table->json('applicable_branches')->nullable(); // null = all branches
            $table->boolean('first_time_customer_only')->default(false);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['code', 'is_active']);
            $table->index(['starts_at', 'ends_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
