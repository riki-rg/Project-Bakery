<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'provider',
        'provider_order_id',
        'method',
        'amount',
        'status',
        'snap_token',
        'redirect_url',
        'expires_at',
        'paid_at',
        'metadata',
    ];

    protected $hidden = ['metadata']; // jangan expose raw payload Midtrans ke client

    protected $casts = [
        'amount'     => 'integer',
        'expires_at' => 'datetime',
        'paid_at'    => 'datetime',
        'metadata'   => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
