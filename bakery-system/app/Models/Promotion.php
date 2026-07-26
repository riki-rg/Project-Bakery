<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'max_discount',
        'min_spend',
        'quota',
        'used_count',
        'usage_limit_per_customer',
        'applicable_categories',
        'applicable_products',
        'applicable_branches',
        'first_time_customer_only',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected $casts = [
        'value'                      => 'integer',
        'max_discount'               => 'integer',
        'min_spend'                  => 'integer',
        'quota'                      => 'integer',
        'used_count'                 => 'integer',
        'usage_limit_per_customer'   => 'integer',
        'applicable_categories'      => 'array',
        'applicable_products'        => 'array',
        'applicable_branches'        => 'array',
        'first_time_customer_only'   => 'boolean',
        'is_active'                  => 'boolean',
        'starts_at'                  => 'datetime',
        'ends_at'                    => 'datetime',
    ];

    public function isValid(): bool
    {
        if (! $this->is_active) return false;

        if ($this->starts_at && now()->lt($this->starts_at)) return false;

        if ($this->ends_at && now()->gt($this->ends_at)) return false;

        if ($this->quota !== null && $this->used_count >= $this->quota) return false;

        return true;
    }

    public function calculateDiscount(int $subtotal): int
    {
        if ($subtotal < $this->min_spend) return 0;

        if ($this->type === 'percentage') {
            $discount = (int) round($subtotal * ($this->value / 100));
            if ($this->max_discount) {
                $discount = min($discount, $this->max_discount);
            }
            return $discount;
        }

        if ($this->type === 'fixed') {
            return min($this->value, $subtotal);
        }

        return 0;
    }
}
