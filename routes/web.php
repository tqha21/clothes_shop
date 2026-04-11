<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\StoresController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\InventoryController as AdminInventoryController;
use App\Http\Controllers\Admin\VoucherController as AdminVoucherController;
use App\Http\Controllers\Admin\WishlistController as AdminWishlistController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Storefront Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/san-pham', [ProductController::class, 'index'])->name('products.index');
Route::get('/san-pham/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/san-pham/{product}/review', [ReviewController::class, 'store'])->name('products.reviews.store');

// Blog/Posts Routes
Route::get('/tin-tuc', [PostController::class, 'index'])->name('posts.index');
Route::get('/tin-tuc/{slug}', [PostController::class, 'show'])->name('posts.show');

// Promotions, Stores, About & Contact
Route::view('/uu-dai', 'promotions.index')->name('promotions.index');
Route::get('/he-thong-cua-hang', [StoresController::class, 'index'])->name('stores.index');
Route::view('/gioi-thieu', 'about')->name('about');
Route::view('/lien-he', 'contact')->name('contact');

// Cart Routes
Route::get('/gio-hang', [CartController::class, 'index'])->name('cart.index');
Route::post('/gio-hang/them', [CartController::class, 'add'])->name('cart.add');
Route::post('/gio-hang/cap-nhat', [CartController::class, 'update'])->name('cart.update');
Route::delete('/gio-hang/xoa/{id}', [CartController::class, 'remove'])->name('cart.remove');


// Checkout Routes (Require Authentication)
Route::middleware('auth')->group(function () {
    Route::get('/thanh-toan', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/thanh-toan', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/thanh-toan/vnpay-return', [CheckoutController::class, 'vnpayReturn'])->name('checkout.vnpay.return');
    Route::get('/dat-hang-thanh-cong/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
});


// Voucher/Coupon Wallet Routes (Protected)
Route::middleware('auth')->group(function () {
    Route::get('/vi-voucher', [VoucherController::class, 'index'])->name('voucher.index');
    Route::post('/vi-voucher/luu', [VoucherController::class, 'save'])->name('voucher.save');
    Route::post('/vi-voucher/bo-luu', [VoucherController::class, 'remove'])->name('voucher.remove');
    
    // Wishlist Routes
    Route::get('/yeu-thich', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/yeu-thich/them/{product}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/yeu-thich/xoa/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');
});


// Authentication Routes
Auth::routes();

// Social Login Routes
Route::get('/auth/{provider}/redirect', [\App\Http\Controllers\Auth\SocialLoginController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [\App\Http\Controllers\Auth\SocialLoginController::class, 'callback'])->name('social.callback');

// Admin Routes (Protected by auth and admin middleware)
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Category Management
    Route::resource('categories', AdminCategoryController::class);
    
    // Product Management
    Route::post('products/{product}/quick-edit', [AdminProductController::class, 'quickEdit'])->name('products.quick-edit');
    Route::resource('products', AdminProductController::class);
    
    // Order Management
    Route::get('orders/export', [AdminOrderController::class, 'exportCsv'])->name('orders.export');
    Route::get('orders/{order}/print', [AdminOrderController::class, 'print'])->name('orders.print');
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update']);
    
    // Employee Management
    Route::resource('employees', App\Http\Controllers\Admin\EmployeeController::class);
    
    // Customer Management
    Route::resource('customers', App\Http\Controllers\Admin\CustomerController::class);

    // Inventory Management
    Route::get('inventory', [AdminInventoryController::class, 'index'])->name('inventory.index');
    Route::get('inventory/history', [AdminInventoryController::class, 'history'])->name('inventory.history');
    Route::post('inventory/update', [AdminInventoryController::class, 'update'])->name('inventory.update');

    // Voucher (Coupon) Management
    Route::get('vouchers', [AdminVoucherController::class, 'index'])->name('vouchers.index');
    Route::middleware('admin.only')->group(function () {
        Route::get('vouchers/create', [AdminVoucherController::class, 'create'])->name('vouchers.create');
        Route::post('vouchers', [AdminVoucherController::class, 'store'])->name('vouchers.store');
        Route::get('vouchers/{voucher}/edit', [AdminVoucherController::class, 'edit'])->name('vouchers.edit');
        Route::put('vouchers/{voucher}', [AdminVoucherController::class, 'update'])->name('vouchers.update');
        Route::delete('vouchers/{voucher}', [AdminVoucherController::class, 'destroy'])->name('vouchers.destroy');
    });

    // Wishlist (Favorites) Management
    Route::get('wishlist', [AdminWishlistController::class, 'index'])->name('wishlist.index');
    Route::delete('wishlist/{wishlist}', [AdminWishlistController::class, 'destroy'])->name('wishlist.destroy');
});
