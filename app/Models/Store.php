<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'address',
        'city',
        'district',
        'phone',
        'latitude',
        'longitude',
        'opening_hours',
        'status',
        'is_default_warehouse',
        'image_url'
    ];

    protected $casts = [
        'is_default_warehouse' => 'boolean',
    ];

    public function stocks()
    {
        return $this->hasMany(StoreStock::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function shiftSessions()
    {
        return $this->hasMany(ShiftSession::class);
    }

    /** Kho mặc định (nhận tồn cũ, ưu tiên xuất đơn online) */
    public static function defaultWarehouse(): ?self
    {
        return static::where('is_default_warehouse', true)->orderBy('id')->first()
            ?? static::where('status', 'active')->orderBy('id')->first();
    }

    /**
     * Scope to filter stores by search term
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Scope to filter stores by city
     */
    public function scopeCity($query, $city)
    {
        if ($city && $city !== 'Tất cả thành phố') {
            return $query->where('city', $city);
        }
        return $query;
    }

    /**
     * Scope to get only active stores
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get Google Maps direction URL
     */
    public function getDirectionUrlAttribute()
    {
        return "https://www.google.com/maps/dir/?api=1&destination={$this->latitude},{$this->longitude}";
    }

    /**
     * Check if store is currently open
     */
    public function getIsOpenAttribute()
    {
        // Simple check - in real app, you'd check against opening_hours
        return $this->status === 'active';
    }

    /**
     * Get the employees associated with the store.
     */
    public function employees()
    {
        return $this->hasMany(User::class, 'store_id')->whereIn('role', ['admin', 'manager', 'staff']);
    }
}
