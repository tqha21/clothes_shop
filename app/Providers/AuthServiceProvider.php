<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Order::class => \App\Policies\OrderPolicy::class,
        \App\Models\ShiftSession::class => \App\Policies\ShiftSessionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Đăng ký mỗi quyền trong ma trận thành một Gate: can:pos.sell, can:inventory.adjust ...
        foreach (array_keys(config('permissions.roles', [])) as $permission) {
            Gate::define($permission, function (User $user) use ($permission) {
                return $user->hasPermission($permission);
            });
        }

        // Quyền thao tác trên một cửa hàng cụ thể (BR-03)
        Gate::define('store.access', function (User $user, $storeId) {
            return $user->isActive() && $user->isEmployee() && $user->canAccessStore($storeId);
        });
    }
}
