<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'image_path',
        'current_stock',
        'low_stock_threshold',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'current_stock' => 'integer',
        'low_stock_threshold' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the category that owns the product
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get stock movements
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Get published reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class)->where('status', 'published');
    }

    /**
     * Get wishlists
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Avg rating appended attribute
     */
    public function getAvgRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    protected $appends = ['avg_rating', 'reviews_count'];

    /**
     * Check if stock is low
     */
    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->low_stock_threshold;
    }

    /**
     * Check if stock is out
     */
    public function isOutOfStock(): bool
    {
        return $this->current_stock <= 0;
    }
}
