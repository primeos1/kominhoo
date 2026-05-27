<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'brand', 'category', 'description',
        'price', 'original_price', 'stock', 'skin_types', 'images',
        'size', 'is_featured', 'is_active', 'rating', 'review_count',
        'concerns', 'routine_step', 'time_of_use', 'texture',
        'sensitivity', 'ingredients', 'badge', 'pro_tip', 'ingredient_info',
    ];

    protected $casts = [
        'skin_types'      => 'array',
        'images'          => 'array',
        'concerns'        => 'array',
        'ingredients'     => 'array',
        'ingredient_info' => 'array',
        'is_featured'     => 'boolean',
        'is_active'       => 'boolean',
        'price'           => 'decimal:2',
        'original_price'  => 'decimal:2',
        'rating'          => 'decimal:2',
    ];

    protected $appends = ['in_stock'];

    public function getInStockAttribute(): bool
    {
        return $this->stock > 0;
    }

    public function bundles()
    {
        return $this->belongsToMany(Bundle::class, 'bundle_product')->withPivot('quantity');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->morphMany(OrderItem::class, 'itemable');
    }
}
