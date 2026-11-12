<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashMovement extends Model
{
    public const TYPES = [
        'in' => 'Thu khác',
        'out' => 'Chi khác',
        'deposit' => 'Nộp tiền',
    ];

    protected $fillable = ['shift_session_id', 'user_id', 'type', 'amount', 'reason'];

    protected $casts = ['amount' => 'float'];

    public function shiftSession()
    {
        return $this->belongsTo(ShiftSession::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}

