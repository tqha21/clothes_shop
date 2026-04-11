@extends('layouts.storefront')

@section('title', 'Giỏ Hàng & Thanh Toán')

@section('content')
<!-- Breadcrumb -->
<div class="bg-gray-50 py-4">
    <div class="container mx-auto px-4">
        <nav class="text-sm">
            <ol class="flex items-center gap-2">
                <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-red-600">Trang chủ</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 font-medium">Giỏ hàng</li>
            </ol>
        </nav>
    </div>
</div>

@if(empty($cart))
    <!-- Empty Cart State -->
    <div class="container mx-auto px-4 py-16">
        <div class="bg-white rounded-lg shadow border border-gray-200 p-16 text-center">
            <div class="max-w-md mx-auto">
                <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-red-100 to-pink-100 rounded-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Giỏ Hàng Trống</h2>
                <p class="text-gray-600 mb-8 text-lg">Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
                <a href="{{ route('products.index') }}" class="inline-block bg-red-600 text-white px-8 py-4 rounded font-semibold text-lg hover:bg-red-700 transition-colors">
                    Khám Phá Sản Phẩm
                </a>
            </div>
        </div>
    </div>
@else
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-3 gap-8">
                <!-- Left Column - Cart Items & Customer Info -->
                <div class="col-span-2 space-y-6">
                    <!-- Cart Items -->
                    <div class="bg-white rounded-lg shadow border border-gray-200">
                        <div class="bg-red-600 p-4 text-white">
                            <h2 class="text-xl font-bold flex items-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Giỏ Hàng ({{ count($cart) }} sản phẩm)
                            </h2>
                        </div>
                        
                        <div class="divide-y divide-gray-200">
                            @foreach($cart as $id => $item)
                            <div class="p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex gap-4">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('storage/' . $item['image']) }}" 
                                             class="w-24 h-24 object-cover rounded border border-gray-200" 
                                             alt="{{ $item['name'] }}">
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-1">
                                        <h3 class="font-bold text-gray-900 mb-2">{{ $item['name'] }}</h3>
                                        
                                        @if(isset($item['size']) || isset($item['color']))
                                        <div class="flex gap-2 mb-2">
                                            @if(isset($item['size']))
                                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">Size: {{ $item['size'] }}</span>
                                            @endif
                                            @if(isset($item['color']))
                                            <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded">Màu: {{ $item['color'] }}</span>
                                            @endif
                                        </div>
                                        @endif
                                        
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-xl font-bold text-red-600">{{ number_format($item['price']) }}đ</p>
                                            </div>

                                            <!-- Quantity Control -->
                                            <div class="flex items-center gap-3">
                                                <form action="{{ route('cart.update') }}" method="POST" class="flex items-center border-2 border-gray-300 rounded">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $id }}">
                                                    <button type="button" onclick="this.nextElementSibling.stepDown(); this.form.submit();" class="w-8 h-8 flex items-center justify-center hover:bg-gray-100">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                        </svg>
                                                    </button>
                                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-12 h-8 text-center border-0 focus:ring-0 font-bold" readonly>
                                                    <button type="button" onclick="this.previousElementSibling.stepUp(); this.form.submit();" class="w-8 h-8 flex items-center justify-center hover:bg-gray-100">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                        </svg>
                                                    </button>
                                                </form>

                                                <div class="text-right">
                                                    <p class="text-lg font-bold text-gray-900">{{ number_format($item['price'] * $item['quantity']) }}đ</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Remove Button -->
                                    <div>
                                        <form action="{{ route('cart.remove', $id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:bg-red-50 p-2 rounded" onclick="return confirm('Xóa sản phẩm này?')">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                <!-- Right Column - Order Summary -->
                <div class="col-span-1">
                    <div class="bg-white rounded-lg shadow border border-gray-200 p-6 sticky top-24">
                        <h2 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b border-gray-200">TÓM TẮT ĐƠN HÀNG</h2>
                        
                        <!-- Summary -->
                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tạm tính:</span>
                                <span class="font-semibold text-gray-900">{{ number_format($total) }}đ</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Phí vận chuyển:</span>
                                <span class="font-semibold text-green-600">Miễn phí</span>
                            </div>
                            <div class="flex justify-between text-sm pb-3 border-b border-gray-200">
                                <span class="text-gray-600">Giảm giá:</span>
                                <span class="font-semibold text-red-600">-{{ session('discount', 0) }}đ</span>
                            </div>
                            <div class="flex justify-between items-center pt-2">
                                <span class="text-lg font-bold text-gray-900">Tổng cộng:</span>
                                <span class="text-3xl font-bold text-red-600">{{ number_format($total - session('discount', 0)) }}đ</span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        @auth
                            <a href="{{ route('checkout.index') }}" class="group w-full bg-gradient-to-r from-green-600 to-green-500 text-white py-4 rounded-lg font-bold text-lg hover:from-green-700 hover:to-green-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                TIẾN HÀNH THANH TOÁN
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="group w-full bg-gradient-to-r from-red-600 to-red-500 text-white py-4 rounded-lg font-bold text-lg hover:from-red-700 hover:to-red-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                ĐĂNG NHẬP ĐỂ THANH TOÁN
                            </a>
                        @endauth

                        <!-- Additional Info -->
                        <div class="mt-4 p-4 bg-blue-50 rounded text-xs text-gray-600">
                            <p class="font-semibold mb-1">Chính sách đổi trả:</p>
                            <p>Miễn phí đổi trả trong 30 ngày. Thanh toán an toàn.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
