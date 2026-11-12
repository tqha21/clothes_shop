<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const CHANNEL_ONLINE = 'online';
    public const CHANNEL_POS = 'pos';

    /** Trạng thái được ghi nhận doanh thu (BR-07) */
    public const REVENUE_STATUSES = ['completed', 'refunded'];

    protected $fillable = [
        'code', 'channel', 'store_id', 'staff_id', 'shift_session_id',
        'user_id', 'customer_name', 'customer_phone', 'customer_address',
        'subtotal', 'discount_amount', 'coupon_id',
        'total', 'refunded_amount', 'status', 'payment_method', 'payment_status',
        'stock_deducted', 'stock_restored_at', 'completed_at', 'cancelled_at', 'note',
        'shipping_method', 'shipping_carrier', 'shipping_fee', 'tracking_code', 'estimated_delivery'
    ];

    protected $casts = [
        'stock_deducted' => 'boolean',
        'stock_restored_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function shiftSession()
    {
        return $this->belongsTo(ShiftSession::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class);
    }

    public function scopePos($query)
    {
        return $query->where('channel', self::CHANNEL_POS);
    }

    public function scopeOnline($query)
    {
        return $query->where('channel', self::CHANNEL_ONLINE);
    }

    /** Đơn được ghi nhận doanh thu */
    public function scopeRevenueRecognized($query)
    {
        return $query->whereIn('status', self::REVENUE_STATUSES)->whereNotNull('completed_at');
    }

    public function isPos(): bool
    {
        return $this->channel === self::CHANNEL_POS;
    }

    /** Doanh thu thuần của đơn = tổng thanh toán - đã hoàn */
    public function getNetTotalAttribute(): float
    {
        return max(0, (float) $this->total - (float) $this->refunded_amount);
    }

    public function getDisplayCodeAttribute(): string
    {
        return $this->code ?: ('#' . $this->id);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Chờ xử lý',
            'shipping' => 'Đang giao',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
            'refunded' => 'Đã hoàn tiền',
            default => $this->status,
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'paid' => 'Đã thanh toán',
            'pending' => 'Chờ xác nhận',
            'failed' => 'Thất bại',
            'partially_refunded' => 'Hoàn một phần',
            'refunded' => 'Đã hoàn tiền',
            default => 'Chưa thanh toán',
        };
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
