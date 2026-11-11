<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Force HTTPS khi chạy production hoặc APP_URL là https
        if (config('app.env') === 'production' || str_starts_with(config('app.url', ''), 'https://')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Truyền danh sách danh mục chỉ vào các views của storefront
        View::composer(
            ['layouts.storefront', 'home', 'products.*', 'cart.*', 'checkout.*'],
            function ($view) {
                try {
                    $view->with('allCategories', Category::all());
                } catch (\Exception $e) {
                    $view->with('allCategories', collect());
                }
            }
        );
    }
}
