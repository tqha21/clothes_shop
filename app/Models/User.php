<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'store_id',
        'employee_code',
        'position',
        'department',
        'hire_date',
        'salary',
        'status',
        'points',
        'membership_tier',
        'provider',
        'provider_id',
        'provider_token',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'hire_date' => 'date',
    ];

    /**
     * Check if user is admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is employee.
     *
     * @return bool
     */
    public function isEmployee()
    {
        return in_array($this->role, ['admin', 'manager', 'staff']);
    }

    /** Tài khoản đang hoạt động (nếu status null/empty mặc định active) */
    public function isActive(): bool
    {
        return empty($this->status) || $this->status === 'active';
    }

    /**
     * Kiểm tra quyền nghiệp vụ theo ma trận config/permissions.php.
     * Admin luôn có toàn quyền.
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->isAdmin() || $this->role === 'admin') {
            return true;
        }
        if (!$this->isActive()) {
            return false;
        }
        $roles = config("permissions.roles.$permission", []);
        $currentRole = ($this->role === 'employee') ? 'staff' : $this->role;
        return in_array($currentRole, $roles, true);
    }

    /** Admin thấy toàn bộ cửa hàng; Manager/Staff chỉ cửa hàng được gán (BR-03) */
    public function canAccessStore($storeId): bool
    {
        if ($this->role === 'admin') {
            return true;
        }
        return $storeId !== null && (int) $this->store_id === (int) $storeId;
    }

    /** Danh sách store_id trong phạm vi quản lý (null = tất cả) */
    public function accessibleStoreIds(): ?array
    {
        if ($this->role === 'admin') {
            return null;
        }
        return $this->store_id ? [(int) $this->store_id] : [];
    }

    public function shiftSessions()
    {
        return $this->hasMany(ShiftSession::class);
    }

    public function currentShiftSession(): ?ShiftSession
    {
        return $this->shiftSessions()->where('status', 'open')->latest('opened_at')->first();
    }

    public function posOrders()
    {
        return $this->hasMany(Order::class, 'staff_id');
    }

    /**
     * Scope a query to only include employees.
     */
    public function scopeEmployees($query)
    {
        return $query->whereIn('role', ['admin', 'manager', 'staff']);
    }

    /**
     * Get formatted salary.
     */
    public function getFormattedSalaryAttribute()
    {
        return $this->salary ? number_format($this->salary, 0, ',', '.') . 'đ' : 'N/A';
    }

    /**
     * Get user avatar or default avatar.
     */
    public function getAvatarUrlAttribute()
    {
        return $this->avatar ?: 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=E11D48&color=fff';
    }


    /**
     * Get the store where the employee works.
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the user's orders.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the user's inventory logs.
     */
    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class);
    }


    /**
     * Get the user's shift schedules.
     */
    public function shiftSchedules()
    {
        return $this->hasMany(ShiftSchedule::class);
    }
    /**
     * Scope a query to only include customers (or users with orders).
     */
    public function scopeCustomers($query)
    {
        return $query->where(function ($q) {
            $q->where('role', 'customer')
              ->orWhereHas('orders');
        });
    }

    /**
     * Check if user is customer.
     *
     * @return bool
     */
    public function isCustomer()
    {
        return $this->role === 'customer' || $this->orders()->exists();
    }

    /**
     * Get discount percent based on membership tier.
     */
    public function getDiscountPercentAttribute()
    {
        return match ($this->membership_tier) {
            'diamond' => 15,
            'gold' => 10,
            'silver' => 5,
            default => 0,
        };
    }

    /**
     * Get the next tier and points needed.
     */
    public function getNextTierProgressAttribute()
    {
        // 5000: Silver, 15000: Gold, 30000: Diamond
        if ($this->points < 5000) {
            return ['next_tier' => 'Bạc (Silver)', 'points_needed' => 5000 - $this->points];
        } elseif ($this->points < 15000) {
            return ['next_tier' => 'Vàng (Gold)', 'points_needed' => 15000 - $this->points];
        } elseif ($this->points < 30000) {
            return ['next_tier' => 'Kim Cương (Diamond)', 'points_needed' => 30000 - $this->points];
        }
        return null; // Max tier reached
    }

    /**
     * Get membership tier label.
     */
    public function getMembershipTierLabelAttribute()
    {
        return match ($this->membership_tier) {
            'diamond' => 'Kim Cương',
            'gold' => 'Vàng',
            'silver' => 'Bạc',
            default => 'Đồng',
        };
    }

    /**
     * Update membership tier based on points.
     */
    public function updateMembershipTier()
    {
        $newTier = 'bronze';
        if ($this->points >= 30000) {
            $newTier = 'diamond';
        } elseif ($this->points >= 15000) {
            $newTier = 'gold';
        } elseif ($this->points >= 5000) {
            $newTier = 'silver';
        }

        if ($this->membership_tier !== $newTier) {
            $this->membership_tier = $newTier;
            $this->save();
        }
    }
}

