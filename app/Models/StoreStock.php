<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreStock extends Model
{
    protected $fillable = ['store_id', 'product_id', 'quantity', 'low_stock_threshold', 'last_counted_at'];

    protected $casts = [
        'quantity' => 'integer',
        'low_stock_threshold' => 'integer',
        'last_counted_at' => 'datetime',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getIsLowAttribute(): bool
    {
        return $this->quantity > 0 && $this->quantity <= $this->low_stock_threshold;
    }
}

