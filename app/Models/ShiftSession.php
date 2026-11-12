<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftSession extends Model
{
    protected $fillable = [
        'code', 'user_id', 'store_id', 'shift_schedule_id', 'opened_at', 'closed_at',
        'opening_cash', 'cash_sales', 'cash_refunds', 'cash_in', 'cash_out', 'non_cash_sales',
        'expected_cash', 'counted_cash', 'difference', 'orders_count', 'status',
        'open_note', 'close_note', 'handover_to', 'closed_by',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'opening_cash' => 'float',
        'cash_sales' => 'float',
        'cash_refunds' => 'float',
        'cash_in' => 'float',
        'cash_out' => 'float',
        'non_cash_sales' => 'float',
        'expected_cash' => 'float',
        'counted_cash' => 'float',
        'difference' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function schedule()
    {
        return $this->belongsTo(ShiftSchedule::class, 'shift_schedule_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function cashMovements()
    {
        return $this->hasMany(CashMovement::class);
    }

    public function handoverUser()
    {
        return $this->belongsTo(User::class, 'handover_to');
    }

    public function closer()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    /** Tiền mặt dự kiến hiện tại trong két */
    public function getCurrentExpectedCashAttribute(): float
    {
        return round($this->opening_cash + $this->cash_sales - $this->cash_refunds + $this->cash_in - $this->cash_out, 2);
    }
}

