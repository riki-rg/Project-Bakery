<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'guest_contact',
        'fulfillment_type',
        'status',
        'payment_status',
        'recipient_snapshot',
        'address_snapshot',
        'branch_snapshot',
        'scheduled_at',
        'subtotal',
        'discount',
        'delivery_fee',
        'service_fee',
        'tax',
        'grand_total',
        'voucher_code',
        'notes',
        'idempotency_key',
    ];

    protected $casts = [
        'guest_contact'      => 'array',
        'recipient_snapshot' => 'array',
        'address_snapshot'   => 'array',
        'branch_snapshot'    => 'array',
        'scheduled_at'       => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING_PAYMENT  = 'pending_payment';
    const STATUS_PAYMENT_VERIFIED = 'payment_verified';
    const STATUS_PROCESSING       = 'processing';
    const STATUS_READY_PICKUP     = 'ready_pickup';
    const STATUS_DELIVERING       = 'delivering';
    const STATUS_COMPLETED        = 'completed';
    const STATUS_CANCELLED        = 'cancelled';
    const STATUS_REFUND           = 'refund';

    const PAYMENT_UNPAID   = 'unpaid';
    const PAYMENT_PAID     = 'paid';
    const PAYMENT_PENDING  = 'pending';
    const PAYMENT_FAILED   = 'failed';
    const PAYMENT_EXPIRED  = 'expired';
    const PAYMENT_REFUNDED = 'refunded';

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING_PAYMENT,
            self::STATUS_PAYMENT_VERIFIED,
        ]);
    }

    // Generate order number: ORD-20260725-00001
    public static function generateOrderNumber(): string
    {
        $date   = now()->format('Ymd');
        $latest = self::whereDate('created_at', today())->count() + 1;
        return 'ORD-' . $date . '-' . str_pad($latest, 5, '0', STR_PAD_LEFT);
    }
}
