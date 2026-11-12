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

    public function storeStocks()
    {
        return $this->hasMany(StoreStock::class);
    }

    /** Giá bán hiện hành (ưu tiên giá khuyến mãi hợp lệ) */
    public function getEffectivePriceAttribute(): float
    {
        $sale = (float) $this->sale_price;
        $price = (float) $this->price;
        return ($sale > 0 && $sale < $price) ? $sale : $price;
    }

    /** Mã SKU hiển thị (hệ thống chưa có cột SKU riêng) */
    public function getSkuAttribute(): string
    {
        return 'SP' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
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
