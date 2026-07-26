<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_code',
        'phone',
        'address',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'date',
    ];

    /**
     * Get the user that owns the profile
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
