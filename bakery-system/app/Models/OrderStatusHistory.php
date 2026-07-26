<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'from_status', 'to_status', 'actor_type', 'actor_id', 'note'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
