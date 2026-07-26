<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // ORD-2026-XXXXX
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->json('guest_contact')->nullable(); // {name, email, phone} untuk tamu
            $table->string('fulfillment_type'); // delivery | pickup
            $table->string('status')->default('pending_payment');
            // Status: pending_payment | payment_verified | processing | ready_pickup | delivering | completed | cancelled | refund
            $table->string('payment_status')->default('unpaid');
            // unpaid | paid | pending | failed | expired | refunded

            // Snapshots (tidak berubah saat data master berubah)
            $table->json('recipient_snapshot');  // {name, email, phone, notes}
            $table->json('address_snapshot')->nullable(); // full alamat untuk delivery
            $table->json('branch_snapshot')->nullable(); // data cabang untuk pickup

            $table->timestamp('scheduled_at')->nullable(); // jadwal pickup/delivery

            // Biaya (integer rupiah, hindari floating point)
            $table->unsignedBigInteger('subtotal');
            $table->unsignedBigInteger('discount')->default(0);
            $table->unsignedBigInteger('delivery_fee')->default(0);
            $table->unsignedBigInteger('service_fee')->default(0);
            $table->unsignedBigInteger('tax')->default(0);
            $table->unsignedBigInteger('grand_total');

            $table->string('voucher_code')->nullable();
            $table->text('notes')->nullable();
            $table->string('idempotency_key')->unique()->nullable(); // cegah order ganda
            $table->timestamps();

            $table->index('status');
            $table->index('customer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
