<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'product_id', 'product_name', 'quantity', 'refunded_quantity',
        'price', 'original_price', 'size', 'color',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'refunded_quantity' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getRefundableQuantityAttribute(): int
    {
        return max(0, (int) $this->quantity - (int) $this->refunded_quantity);
    }

    public function getLineTotalAttribute(): float
    {
        return (float) $this->price * (int) $this->quantity;
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->product_name ?: ($this->product->name ?? 'Sản phẩm #' . $this->product_id);
    }
}
