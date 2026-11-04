<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'customer_name', 'customer_phone', 'customer_address',
        'total', 'status', 'payment_method',
        'shipping_method', 'shipping_carrier', 'shipping_fee', 'tracking_code', 'estimated_delivery'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getShippingMethodNameAttribute()
    {
        return match($this->shipping_method) {
            'express' => 'Hỏa Tốc (Nhận trong ngày 2-4h)',
            'economy' => 'Tiết Kiệm (3-5 ngày)',
            default => 'Nhanh (Tiêu chuẩn 1-3 ngày)'
        };
    }

    public function getFormattedTrackingCodeAttribute()
    {
        if (!empty($this->tracking_code)) {
            return $this->tracking_code;
        }
        return 'SPX-VN' . str_pad($this->id, 6, '0', STR_PAD_LEFT) . '88';
    }
}
