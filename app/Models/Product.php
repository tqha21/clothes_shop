<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'price', 'sale_price', 
        'image', 'images', 'description', 'content', 'stock', 'sizes', 'colors',
        'view_count', 'sold_count'
    ];

    protected $casts = [
        'images' => 'array',
        'colors' => 'array',
        'sizes' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class);
    }


    /**
     * Scope for popular products (most viewed).
     */
    public function scopePopular($query)
    {
        return $query->orderBy('view_count', 'desc');
    }

    /**
     * Scope for new arrivals.
     */
    public function scopeNewArrivals($query)
    {
        return $query->latest();
    }

    /**
     * Scope for best sellers.
     */
    public function scopeBestSellers($query)
    {
        return $query->orderBy('sold_count', 'desc');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
