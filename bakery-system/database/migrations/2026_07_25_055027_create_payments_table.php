<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('provider')->default('midtrans');
            $table->string('provider_order_id')->nullable()->unique(); // Midtrans order_id
            $table->string('method')->nullable(); // bank_transfer, gopay, qris, dll
            $table->unsignedBigInteger('amount');
            $table->string('status')->default('pending');
            // pending | paid | failed | expired | cancelled | refunded
            $table->text('snap_token')->nullable();   // Midtrans Snap token
            $table->text('redirect_url')->nullable(); // Midtrans payment URL
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('metadata')->nullable(); // raw response Midtrans
            $table->timestamps();

            $table->index(['order_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
