<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftSchedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'shift_id',
        'work_date',
        'note',
        'status',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'work_date' => 'date',
    ];

    /**
     * Status labels in Vietnamese.
     */
    const STATUS_LABELS = [
        'scheduled'  => 'Đã lên lịch',
        'confirmed'  => 'Đã xác nhận',
        'absent'     => 'Vắng mặt',
        'completed'  => 'Hoàn thành',
    ];

    /**
     * Get the employee (user) for this schedule.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the shift definition for this schedule.
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    /**
     * Scope: filter by employee.
     */
    public function scopeForEmployee($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: filter by year and month.
     */
    public function scopeForMonth($query, int $year, int $month)
    {
        return $query->whereYear('work_date', $year)
                     ->whereMonth('work_date', $month);
    }

    /**
     * Scope: filter by store (via user.store_id).
     */
    public function scopeForStore($query, int $storeId)
    {
        return $query->whereHas('user', function ($q) use ($storeId) {
            $q->where('store_id', $storeId);
        });
    }

    /**
     * Get status label in Vietnamese.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    /**
     * Get status badge color class.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'scheduled'  => 'bg-blue-100 text-blue-700',
            'confirmed'  => 'bg-green-100 text-green-700',
            'absent'     => 'bg-red-100 text-red-700',
            'completed'  => 'bg-gray-100 text-gray-700',
            default      => 'bg-gray-100 text-gray-700',
        };
    }
}
