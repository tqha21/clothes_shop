<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'type', 'value', 'min_order_amount',
        'usage_limit', 'used_count', 'expires_at', 'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active'  => 'boolean',
    ];

    /** Các user đã lưu coupon này */
    public function savedByUsers()
    {
        return $this->hasMany(UserSavedCoupon::class);
    }

    /** Kiểm tra user đã lưu coupon này chưa */
    public function isSavedBy($userId)
    {
        return $this->savedByUsers()->where('user_id', $userId)->exists();
    }

    /** Label hiển thị giá trị giảm */
    public function getDiscountLabelAttribute()
    {
        if ($this->type === 'percentage') {
            return "Giảm {$this->value}%";
        }
        return 'Giảm ' . number_format($this->value) . 'đ';
    }

    /** Check if coupon is valid */
    public function isValid($orderAmount = 0)
    {
        if (!$this->is_active) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        if ($orderAmount < $this->min_order_amount) return false;
        return true;
    }

    /** Calculate discount amount */
    public function calculateDiscount($orderAmount)
    {
        if ($this->type === 'percentage') {
            return ($orderAmount * $this->value) / 100;
        }
        return min($this->value, $orderAmount);
    }
}
