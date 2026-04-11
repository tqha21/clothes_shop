<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSavedCoupon extends Model
{
    protected $fillable = ['user_id', 'coupon_id', 'saved_at', 'is_used'];

    protected $casts = [
        'saved_at' => 'datetime',
        'is_used'  => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
