<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public const METHODS = [
        'cash' => 'Tiền mặt',
        'card' => 'Thẻ ngân hàng (POS)',
        'bank_transfer' => 'Chuyển khoản / QR',
        'ewallet' => 'Ví điện tử',
        'cod' => 'COD',
        'vnpay' => 'VNPAY',
        'bank' => 'Chuyển khoản',
    ];

    protected $fillable = [
        'order_id', 'shift_session_id', 'store_id', 'type', 'method', 'amount',
        'received_amount', 'change_amount', 'status', 'reference', 'note',
        'processed_by', 'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'received_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function shiftSession()
    {
        return $this->belongsTo(ShiftSession::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function getMethodLabelAttribute(): string
    {
        return self::METHODS[$this->method] ?? strtoupper($this->method);
    }
}

