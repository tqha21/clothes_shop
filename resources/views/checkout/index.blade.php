@extends('layouts.storefront')

@section('title', 'Thanh Toán Đơn Hàng')

@section('content')
<!-- Checkout Progress Stepper -->
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="text-xs text-slate-500 mb-4 flex items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-red-600 transition">Trang chủ</a>
            <span>/</span>
            <a href="{{ route('cart.index') }}" class="hover:text-red-600 transition">Giỏ hàng</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">Thanh toán</span>
        </nav>

        <!-- Stepper -->
        <div class="flex items-center justify-center max-w-xl mx-auto">
            <!-- Step 1 -->
            <div class="flex items-center gap-2 text-emerald-600">
                <span class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-sm font-bold border-2 border-emerald-500">✓</span>
                <span class="text-xs sm:text-sm font-semibold hidden sm:inline">Giỏ hàng</span>
            </div>
            <div class="flex-1 h-0.5 bg-emerald-500 mx-2 sm:mx-4"></div>

            <!-- Step 2 -->
            <div class="flex items-center gap-2 text-indigo-600">
                <span class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold shadow-md shadow-indigo-500/30">2</span>
                <span class="text-xs sm:text-sm font-bold text-slate-900">Đặt hàng & Thanh toán</span>
            </div>
            <div class="flex-1 h-0.5 bg-slate-200 mx-2 sm:mx-4"></div>

            <!-- Step 3 -->
            <div class="flex items-center gap-2 text-slate-400">
                <span class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-sm font-bold border border-slate-300">3</span>
                <span class="text-xs sm:text-sm font-medium hidden sm:inline">Hoàn tất</span>
            </div>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 py-8">
    <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST">
        @csrf

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl shadow-sm">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span>Vui lòng kiểm tra lại thông tin:</span>
                </div>
                <ul class="list-disc list-inside text-sm space-y-1 pl-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- LEFT COLUMN: Thông tin giao hàng & Phương thức thanh toán (7 cols) -->
            <div class="lg:col-span-7 space-y-6">

                <!-- 1. THÔNG TIN GIAO HÀNG -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">1</span>
                            THÔNG TIN GIAO HÀNG
                        </h2>
                        @guest
                        <a href="{{ route('login') }}" class="text-xs text-indigo-600 font-semibold hover:underline flex items-center gap-1">
                            <span>Đăng nhập</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        @endguest
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Họ và tên người nhận <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="customer_name" 
                                       class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition" 
                                       required 
                                       value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                                       placeholder="Ví dụ: Nguyễn Văn A">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Số điện thoại liên hệ <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="customer_phone" 
                                       class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition" 
                                       required 
                                       value="{{ old('customer_phone', auth()->user()->phone ?? '') }}"
                                       placeholder="Ví dụ: 0912345678">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                Email (để nhận hóa đơn & cập nhật lộ trình đơn hàng)
                            </label>
                            <input type="email" name="customer_email" 
                                   class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition" 
                                   value="{{ old('customer_email', auth()->user()->email ?? '') }}"
                                   placeholder="email@example.com">
                        </div>

                        <!-- Địa chỉ 3 cấp hành chính -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Tỉnh / Thành phố <span class="text-red-500">*</span>
                                </label>
                                <select name="province" id="province" class="w-full px-3 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition cursor-pointer" required>
                                    <option value="">Chọn Tỉnh/Thành</option>
                                </select>
                                <input type="hidden" name="province_name" id="province_name">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Quận / Huyện <span class="text-red-500">*</span>
                                </label>
                                <select name="district" id="district" class="w-full px-3 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition cursor-pointer" required>
                                    <option value="">Chọn Quận/Huyện</option>
                                </select>
                                <input type="hidden" name="district_name" id="district_name">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Phường / Xã <span class="text-red-500">*</span>
                                </label>
                                <select name="ward" id="ward" class="w-full px-3 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition cursor-pointer" required>
                                    <option value="">Chọn Phường/Xã</option>
                                </select>
                                <input type="hidden" name="ward_name" id="ward_name">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                Địa chỉ cụ thể (Số nhà, tên đường, toà nhà/chung cư) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="customer_address" 
                                   class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition" 
                                   required 
                                   value="{{ old('customer_address') }}"
                                   placeholder="Ví dụ: Số 25 ngõ 180 đường Nguyễn Trãi">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                Ghi chú đơn hàng (Tùy chọn)
                            </label>
                            <textarea name="notes" rows="2" 
                                      class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition"
                                      placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi giao 15 phút...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                                <!-- 2. HÌNH THỨC VẬN CHUYỂN (CHUẨN SHOPEE) -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center font-bold text-sm">2</span>
                            <div>
                                <h2 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                                    HÌNH THỨC VẬN CHUYỂN
                                    <span class="text-[10px] font-bold bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full uppercase tracking-wider flex items-center gap-1">
                                        <svg class="w-3 h-3 text-orange-600" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7h-3v7h.05a2.5 2.5 0 014.9 0H17a1 1 0 001-1v-4.382a1 1 0 00-.293-.707l-2.618-2.618A1 1 0 0014.382 7H14z"/></svg>
                                        SPX Express
                                    </span>
                                </h2>
                                <p class="text-xs text-slate-500 mt-0.5">Lựa chọn đơn vị vận chuyển đối tác uy tín, bảo đảm thời gian giao hàng</p>
                            </div>
                        </div>
                        <span class="hidden sm:flex text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Đồng kiểm khi nhận
                        </span>
                    </div>

                    <div class="space-y-3" id="shipping-options-list">
                        
                        <!-- 1. VẬN CHUYỂN NHANH (TIÊU CHUẨN) -->
                        @php
                            $isFreeShip = ($subtotal >= 500000);
                            $standardFee = $isFreeShip ? 0 : 25000;
                        @endphp
                        <label class="shipping-option block p-4 rounded-2xl border-2 border-orange-500 bg-orange-50/20 cursor-pointer transition-all hover:border-orange-500" id="ship-opt-standard">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start gap-3">
                                    <input type="radio" name="shipping_method" value="standard" checked 
                                           class="mt-1 w-4 h-4 text-orange-600 focus:ring-orange-500 accent-orange-600"
                                           onchange="selectShipping('standard', {{ $standardFee }})">
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="font-bold text-slate-900 text-sm sm:text-base">Nhanh (Tiêu chuẩn)</span>
                                            <span class="bg-orange-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Shopee Xpress / GHN</span>
                                            @if($isFreeShip)
                                            <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full">Freeship Đơn &ge; 500k</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-600 mt-1 flex items-center gap-1.5">
                                            <span class="text-slate-400">Thời gian nhận hàng:</span>
                                            <strong class="text-slate-800 font-semibold">Nhận hàng vào {{ now()->addDays(2)->format('d/m') }} - {{ now()->addDays(3)->format('d/m') }}</strong>
                                        </p>
                                        <p class="text-[11px] text-slate-400 mt-0.5">Vận chuyển qua Shopee Xpress, Giao Hàng Nhanh (GHN), phát tận tay người nhận</p>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    @if($isFreeShip)
                                    <span class="text-xs text-slate-400 line-through block">25.000đ</span>
                                    <span class="text-sm sm:text-base font-bold text-emerald-600 font-mono">MIỄN PHÍ</span>
                                    @else
                                    <span class="text-sm sm:text-base font-bold text-slate-900 font-mono">25.000đ</span>
                                    @endif
                                </div>
                            </div>
                        </label>

                        <!-- 2. VẬN CHUYỂN HỎA TỐC -->
                        <label class="shipping-option block p-4 rounded-2xl border-2 border-slate-200 bg-white cursor-pointer transition-all hover:border-orange-300" id="ship-opt-express">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start gap-3">
                                    <input type="radio" name="shipping_method" value="express" 
                                           class="mt-1 w-4 h-4 text-orange-600 focus:ring-orange-500 accent-orange-600"
                                           onchange="selectShipping('express', 45000)">
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="font-bold text-slate-900 text-sm sm:text-base">Hỏa Tốc (Instant 2 - 4h)</span>
                                            <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase flex items-center gap-0.5">
                                                <span>⚡</span> Giao Ngay
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-600 mt-1 flex items-center gap-1.5">
                                            <span class="text-slate-400">Thời gian nhận hàng:</span>
                                            <strong class="text-red-600 font-semibold">Hôm nay (từ {{ now()->addHours(2)->format('H:i') }} - {{ now()->addHours(4)->format('H:i') }})</strong>
                                        </p>
                                        <p class="text-[11px] text-slate-400 mt-0.5">Vận chuyển qua Shopee Xpress Instant / GrabExpress hỏa tốc nội thành</p>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <span class="text-sm sm:text-base font-bold text-slate-900 font-mono">45.000đ</span>
                                </div>
                            </div>
                        </label>

                        <!-- 3. VẬN CHUYỂN TIẾT KIỆM -->
                        <label class="shipping-option block p-4 rounded-2xl border-2 border-slate-200 bg-white cursor-pointer transition-all hover:border-orange-300" id="ship-opt-economy">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start gap-3">
                                    <input type="radio" name="shipping_method" value="economy" 
                                           class="mt-1 w-4 h-4 text-orange-600 focus:ring-orange-500 accent-orange-600"
                                           onchange="selectShipping('economy', 15000)">
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="font-bold text-slate-900 text-sm sm:text-base">Tiết Kiệm</span>
                                            <span class="bg-blue-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Viettel Post / VNPost</span>
                                        </div>
                                        <p class="text-xs text-slate-600 mt-1 flex items-center gap-1.5">
                                            <span class="text-slate-400">Thời gian nhận hàng:</span>
                                            <strong class="text-slate-800 font-semibold">Nhận hàng vào {{ now()->addDays(3)->format('d/m') }} - {{ now()->addDays(5)->format('d/m') }}</strong>
                                        </p>
                                        <p class="text-[11px] text-slate-400 mt-0.5">Cước phí tiết kiệm nhất cho các đơn ngoại tỉnh và vùng xa</p>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <span class="text-sm sm:text-base font-bold text-slate-900 font-mono">15.000đ</span>
                                </div>
                            </div>
                        </label>

                    </div>

                    <!-- Dải cam thông báo Đồng Kiểm Shopee -->
                    <div class="mt-4 p-3 bg-gradient-to-r from-orange-50 to-amber-50 rounded-xl border border-orange-200/80 flex items-center gap-2.5 text-xs text-orange-950">
                        <div class="w-8 h-8 rounded-lg bg-orange-500 text-white flex items-center justify-center flex-shrink-0 font-bold text-sm">
                            📦
                        </div>
                        <div class="flex-1">
                            <span class="font-bold text-orange-800">Quyền lợi Shopee Đồng Kiểm:</span>
                            <span class="text-orange-900/90 ml-1">Được kiểm tra ngoại quan kiện hàng cùng shipper trước khi nhận và thanh toán. Đổi trả miễn phí 7 ngày nếu không vừa size.</span>
                        </div>
                    </div>
                </div>

                <!-- 3. PHƯƠNG THỨC THANH TOÁN (VIETQR / VNPAY / MOMO / COD) -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">3</span>
                            PHƯƠNG THỨC THANH TOÁN
                        </h2>
                        <span class="text-xs text-slate-500 flex items-center gap-1">
                            <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                            Bảo mật 256-bit
                        </span>
                    </div>

                    <div class="space-y-3.5">
                        
                        <!-- 1. CHUYỂN KHOẢN NGÂN HÀNG VIETQR -->
                        <div class="payment-card rounded-2xl border-2 border-slate-200 transition-all duration-200 overflow-hidden" id="card-BANK">
                            <label class="flex items-center p-4 cursor-pointer">
                                <input type="radio" name="payment_method" value="BANK" checked class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 accent-indigo-600" onchange="selectPayment('BANK')">
                                <div class="ml-3 flex items-center justify-between flex-1 gap-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center p-1.5 border border-red-100 flex-shrink-0">
                                            <span class="font-extrabold text-xs text-red-600 tracking-tighter">TCB</span>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-slate-900 text-sm sm:text-base">Chuyển khoản VietQR 24/7</span>
                                                <span class="bg-red-100 text-red-700 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Napas 247</span>
                                            </div>
                                            <p class="text-xs text-slate-500 mt-0.5">Quét mã bằng bất kỳ App ngân hàng nào (Techcombank, Vietcombank, MB, BIDV...)</p>
                                        </div>
                                    </div>
                                    <span class="hidden sm:inline-block text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">Khuyên dùng</span>
                                </div>
                            </label>

                            <!-- VietQR Details Panel -->
                            <div id="panel-BANK" class="border-t border-slate-200 bg-gradient-to-br from-slate-50 to-indigo-50/30 p-5 space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                        Mã VietQR Thanh Toán Động
                                    </span>
                                    <span class="text-[11px] text-slate-500">Tự điền STK & số tiền</span>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-5 items-center bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm">
                                    <div class="relative group">
                                        @php $initShipFee = ($subtotal >= 500000) ? 0 : 25000; @endphp
                                        <img id="vietqr-preview-img" src="https://img.vietqr.io/image/TCB-202108059999-compact2.png?amount={{ $total + $initShipFee }}&addInfo=Thanh toan don hang&accountName=TA QUANG HA" 
                                             alt="Mã VietQR Techcombank" 
                                             class="w-44 h-44 object-contain rounded-xl border border-slate-200 p-1 bg-white shadow-sm">
                                        <div class="text-center mt-1 text-[11px] text-slate-400">Quét bằng App Ngân Hàng</div>
                                    </div>

                                    <div class="flex-1 space-y-2.5 text-xs text-slate-600 w-full">
                                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                            <span class="text-slate-400">Ngân hàng:</span>
                                            <strong class="text-slate-900 font-bold">Techcombank (TCB)</strong>
                                        </div>
                                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                            <span class="text-slate-400">Số tài khoản:</span>
                                            <div class="flex items-center gap-2">
                                                <strong class="font-mono text-sm text-red-600 font-bold" id="bank-acc-num">202108059999</strong>
                                                <button type="button" onclick="copyText('202108059999', 'Số tài khoản')" class="text-indigo-600 hover:text-indigo-800 text-[11px] font-semibold underline">Sao chép</button>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                            <span class="text-slate-400">Chủ tài khoản:</span>
                                            <strong class="text-slate-900 font-bold uppercase">TẠ QUANG HÀ</strong>
                                        </div>
                                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                            <span class="text-slate-400">Số tiền:</span>
                                            <div class="flex items-center gap-2">
                                                <strong id="bank-transfer-amount" class="text-sm text-indigo-600 font-bold">{{ number_format($total + $initShipFee) }}đ</strong>
                                                <button type="button" onclick="copyText('{{ $total }}', 'Số tiền')" class="text-indigo-600 hover:text-indigo-800 text-[11px] font-semibold underline">Sao chép</button>
                                            </div>
                                        </div>
                                        <div class="p-2 bg-amber-50 rounded-lg border border-amber-200/60 text-[11.5px] text-amber-800">
                                            💡 <em>Sau khi bấm đặt hàng, hệ thống sẽ cấp mã đơn và mã QR kèm mã đơn để quý khách lưu lại.</em>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. CỔNG THANH TOÁN VNPAY -->
                        <div class="payment-card rounded-2xl border-2 border-slate-200 transition-all duration-200 overflow-hidden" id="card-VNPAY">
                            <label class="flex items-center p-4 cursor-pointer">
                                <input type="radio" name="payment_method" value="VNPAY" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 accent-indigo-600" onchange="selectPayment('VNPAY')">
                                <div class="ml-3 flex items-center justify-between flex-1 gap-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center p-1 border border-blue-100 flex-shrink-0">
                                            <span class="font-extrabold text-[11px] text-blue-600 tracking-tighter">VNPAY</span>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-slate-900 text-sm sm:text-base">Cổng thanh toán VNPAY</span>
                                                <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">ATM / QR / Visa</span>
                                            </div>
                                            <p class="text-xs text-slate-500 mt-0.5">Thanh toán qua VNPAY-QR, Thẻ ATM nội địa, Thẻ quốc tế Visa/MasterCard</p>
                                        </div>
                                    </div>
                                    <span class="hidden sm:inline-block text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg">Cổng thanh toán</span>
                                </div>
                            </label>

                            <div id="panel-VNPAY" class="hidden border-t border-slate-200 bg-blue-50/30 p-4 text-xs text-slate-600 space-y-2">
                                <div class="flex items-center gap-2 text-blue-800 font-semibold">
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    Chuyển hướng đến Cổng VNPAY an toàn
                                </div>
                                <p class="text-slate-600 leading-relaxed">
                                    Sau khi nhấn <strong>"Thanh toán đơn hàng"</strong>, bạn sẽ được chuyển đến Cổng thanh toán VNPAY chính thức để quét mã QR qua ứng dụng ngân hàng hoặc thanh toán thẻ ngân hàng 100% an toàn.
                                </p>
                            </div>
                        </div>

                        <!-- 3. VÍ ĐIỆN TỬ MOMO -->
                        <div class="payment-card rounded-2xl border-2 border-slate-200 transition-all duration-200 overflow-hidden" id="card-EWALLET">
                            <label class="flex items-center p-4 cursor-pointer">
                                <input type="radio" name="payment_method" value="EWALLET" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 accent-indigo-600" onchange="selectPayment('EWALLET')">
                                <div class="ml-3 flex items-center justify-between flex-1 gap-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-pink-50 flex items-center justify-center p-1 border border-pink-100 flex-shrink-0">
                                            <span class="font-extrabold text-xs text-pink-600">MoMo</span>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-slate-900 text-sm sm:text-base">Ví điện tử MoMo</span>
                                                <span class="bg-pink-100 text-pink-700 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Ví MoMo</span>
                                            </div>
                                            <p class="text-xs text-slate-500 mt-0.5">Chuyển tiền nhanh qua App MoMo quét mã QR ví</p>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <div id="panel-EWALLET" class="hidden border-t border-slate-200 bg-pink-50/30 p-4 text-xs text-slate-600 space-y-2">
                                <div class="flex flex-col sm:flex-row gap-4 items-center bg-white p-3.5 rounded-xl border border-pink-100">
                                    <div class="w-12 h-12 rounded-xl bg-pink-600 text-white font-bold flex items-center justify-center text-lg flex-shrink-0">
                                        M
                                    </div>
                                    <div class="flex-1 space-y-1">
                                        <p class="font-bold text-slate-900">Ví MoMo: <span class="font-mono text-pink-600 text-sm">0383390611</span></p>
                                        <p class="text-slate-600">Chủ ví: <strong class="uppercase">TẠ QUANG HÀ</strong></p>
                                        <p class="text-[11px] text-slate-400">Nội dung chuyển: Mã đơn hàng (được hiển thị sau khi đặt hàng)</p>
                                    </div>
                                    <button type="button" onclick="copyText('0383390611', 'Số MoMo')" class="text-xs font-semibold px-3 py-1.5 bg-pink-50 text-pink-700 rounded-lg hover:bg-pink-100 transition">Sao chép SĐT</button>
                                </div>
                            </div>
                        </div>

                        <!-- 4. THANH TOÁN KHI NHẬN HÀNG (COD) -->
                        <div class="payment-card rounded-2xl border-2 border-slate-200 transition-all duration-200 overflow-hidden" id="card-COD">
                            <label class="flex items-center p-4 cursor-pointer">
                                <input type="radio" name="payment_method" value="COD" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 accent-indigo-600" onchange="selectPayment('COD')">
                                <div class="ml-3 flex items-center justify-between flex-1 gap-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center p-1.5 border border-emerald-100 flex-shrink-0">
                                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-slate-900 text-sm sm:text-base">Thanh toán khi nhận hàng (COD)</span>
                                                <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Kiểm hàng</span>
                                            </div>
                                            <p class="text-xs text-slate-500 mt-0.5">Nhận hàng, kiểm tra sản phẩm ưng ý rồi mới trả tiền cho shipper</p>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <div id="panel-COD" class="hidden border-t border-slate-200 bg-emerald-50/30 p-4 text-xs text-slate-600 space-y-1">
                                <p class="text-emerald-800 font-semibold flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    Được đồng kiểm cùng nhân viên giao hàng
                                </p>
                                <p class="text-slate-500">Quý khách vui lòng chuẩn bị số tiền tương ứng khi shipper liên hệ giao hàng.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- CAM KẾT CỬA HÀNG -->
                <div class="grid grid-cols-3 gap-3 text-center">
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/60">
                        <span class="text-xl">🔄</span>
                        <h4 class="text-xs font-bold text-slate-900 mt-1">Đổi trả 7 ngày</h4>
                        <p class="text-[11px] text-slate-500">Đổi size tận nơi miễn phí</p>
                    </div>
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/60">
                        <span class="text-xl">📦</span>
                        <h4 class="text-xs font-bold text-slate-900 mt-1">Đồng kiểm khi nhận</h4>
                        <p class="text-[11px] text-slate-500">Xem hàng trước khi trả tiền</p>
                    </div>
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/60">
                        <span class="text-xl">🛡️</span>
                        <h4 class="text-xs font-bold text-slate-900 mt-1">100% Chính hãng</h4>
                        <p class="text-[11px] text-slate-500">Bảo hành đường may chất vải</p>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Đơn hàng & Tóm tắt thanh toán (5 cols) -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sticky top-24 space-y-5">
                    
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <span>🛒 ĐƠN HÀNG CỦA BẠN</span>
                            <span class="bg-slate-100 text-slate-600 text-xs px-2 py-0.5 rounded-full font-semibold">{{ count($cart) }} món</span>
                        </h2>
                        <a href="{{ route('cart.index') }}" class="text-xs text-indigo-600 font-semibold hover:underline">Sửa giỏ hàng</a>
                    </div>

                    <!-- Danh sách sản phẩm trong giỏ -->
                    <div class="space-y-3 max-h-64 overflow-y-auto pr-1 divide-y divide-slate-100">
                        @foreach($cart as $id => $item)
                        <div class="flex gap-3 pt-3 first:pt-0">
                            <div class="relative w-16 h-16 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0 border border-slate-200">
                                <img src="{{ asset('storage/' . $item['image']) }}" 
                                     alt="{{ $item['name'] }}" 
                                     class="w-full h-full object-cover">
                                <span class="absolute top-0.5 right-0.5 w-5 h-5 bg-indigo-600 text-white rounded-full flex items-center justify-center text-[10px] font-bold shadow-sm">
                                    {{ $item['quantity'] }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs sm:text-sm font-semibold text-slate-900 truncate" title="{{ $item['name'] }}">{{ $item['name'] }}</h4>
                                <div class="flex items-center gap-2 text-[11px] text-slate-500 mt-0.5">
                                    @if(isset($item['size']))<span class="bg-slate-100 px-1.5 py-0.5 rounded">Size: {{ $item['size'] }}</span>@endif
                                    @if(isset($item['color']))<span class="bg-slate-100 px-1.5 py-0.5 rounded">Màu: {{ $item['color'] }}</span>@endif
                                </div>
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-xs text-slate-400">SL: x{{ $item['quantity'] }}</span>
                                    <span class="text-xs font-bold text-slate-900">{{ number_format($item['price'] * $item['quantity']) }}đ</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- KHỐI VOUCHER / MÃ GIẢM GIÁ -->
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-dashed border-slate-300 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                Mã Giảm Giá / Voucher
                            </span>
                            @if(session('coupon_code'))
                            <span class="text-[11px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">
                                {{ session('coupon_code') }} (-{{ number_format(session('discount', 0)) }}đ)
                            </span>
                            @endif
                        </div>

                        <!-- Ô nhập mã -->
                        <div class="flex gap-2">
                            <input type="text" id="coupon_code_input" 
                                   class="flex-1 px-3 py-2 text-xs uppercase font-mono tracking-wider bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600" 
                                   placeholder="Nhập mã ưu đãi..."
                                   value="{{ session('coupon_code') }}">
                            <button type="button" onclick="submitCoupon()" class="px-3.5 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition">
                                Áp dụng
                            </button>
                        </div>

                        @if(session('coupon_success'))
                        <p class="text-xs text-emerald-600 font-medium">✓ {{ session('coupon_success') }}</p>
                        @endif
                        @if(session('coupon_error'))
                        <p class="text-xs text-red-600 font-medium">✕ {{ session('coupon_error') }}</p>
                        @endif

                        <!-- Danh sách voucher gợi ý/đã lưu -->
                        @if(isset($savedCoupons) && $savedCoupons->count() > 0)
                        <div class="pt-2 border-t border-slate-200">
                            <p class="text-[11px] font-semibold text-slate-600 mb-1.5">Voucher của bạn:</p>
                            <div class="space-y-1.5 max-h-36 overflow-y-auto pr-1">
                                @foreach($savedCoupons as $sc)
                                    @php $c = $sc->coupon; @endphp
                                    <div class="flex items-center justify-between p-2 bg-white rounded-lg border border-slate-200 text-xs hover:border-indigo-400 transition cursor-pointer" onclick="applyVoucher('{{ $c->code }}')">
                                        <div class="flex items-center gap-2">
                                            <span class="w-8 h-8 rounded bg-red-100 text-red-600 font-bold flex items-center justify-center text-[11px]">
                                                {{ $c->type === 'percentage' ? '-'.$c->value.'%' : '-'.($c->value/1000).'k' }}
                                            </span>
                                            <div>
                                                <p class="font-bold text-slate-800 font-mono">{{ $c->code }}</p>
                                                <p class="text-[10px] text-slate-500">{{ $c->discountLabel }}</p>
                                            </div>
                                        </div>
                                        <span class="text-[11px] text-indigo-600 font-bold">Dùng</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- BẢNG TÍNH TIỀN CHI TIẾT -->
                    <div class="space-y-2.5 pt-2 border-t border-slate-100 text-xs sm:text-sm">
                        <div class="flex justify-between text-slate-600">
                            <span>Tạm tính hàng</span>
                            <span class="font-medium text-slate-900">{{ number_format($subtotal) }}đ</span>
                        </div>

                        @if(isset($membershipDiscount) && $membershipDiscount > 0)
                        <div class="flex justify-between text-emerald-600 font-medium">
                            <span>Ưu đãi thành viên ({{ auth()->user()->membership_tier_label ?? 'VIP' }})</span>
                            <span>-{{ number_format($membershipDiscount) }}đ</span>
                        </div>
                        @endif

                        @if(session('discount', 0) > 0)
                        <div class="flex justify-between text-emerald-600 font-medium">
                            <span>Giảm giá voucher ({{ session('coupon_code') }})</span>
                            <span>-{{ number_format(session('discount', 0)) }}đ</span>
                        </div>
                        @endif

                        @php $stdFee = ($subtotal >= 500000) ? 0 : 25000; @endphp
                        <div class="flex justify-between text-slate-600">
                            <span class="flex items-center gap-1">
                                Phí vận chuyển
                                <span id="summary-shipping-carrier" class="text-[10px] bg-orange-100 text-orange-700 px-1.5 py-0.5 rounded font-semibold">SPX Express</span>
                            </span>
                            <span id="summary-shipping-fee" class="font-semibold {{ $stdFee == 0 ? "text-emerald-600" : "text-slate-900 font-mono" }}">
                                {{ $stdFee == 0 ? "MIỄN PHÍ" : number_format($stdFee) . "đ" }}
                            </span>
                        </div>

                        <div class="pt-3 border-t border-slate-200 flex justify-between items-baseline">
                            <div>
                                <span class="text-sm font-bold text-slate-900 block">Tổng thanh toán:</span>
                                <span class="text-[11px] text-slate-400 font-normal">(Đã bao gồm VAT & phí giao)</span>
                            </div>
                            <span class="text-xl sm:text-2xl font-black text-red-600 tracking-tight" id="final-total-display">
                                {{ number_format($total + $stdFee) }}đ
                            </span>
                        </div>
                    </div>

                    <!-- NÚT XÁC NHẬN ĐẶT HÀNG -->
                    <div class="pt-2">
                        <button type="submit" id="btn-submit-order" class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-red-600 via-rose-600 to-indigo-600 text-white font-bold text-base hover:opacity-95 shadow-xl shadow-red-500/20 active:scale-[0.99] transition-all flex items-center justify-center gap-2 group">
                            <svg class="w-5 h-5 text-white/90 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span id="submit-text">XÁC NHẬN ĐẶT HÀNG</span>
                        </button>
                    </div>

                    <div class="text-center pt-1">
                        <p class="text-[11px] text-slate-400">
                            Bằng việc nhấn đặt hàng, bạn đồng ý với các 
                            <a href="#" class="underline hover:text-slate-600">Điều khoản & Chính sách</a> của HTD Store.
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </form>
</div>

<script>
// Chuyển đổi phương thức thanh toán & styling card
function selectPayment(method) {
    const cards = ['BANK', 'VNPAY', 'EWALLET', 'COD'];
    cards.forEach(m => {
        const card = document.getElementById('card-' + m);
        const panel = document.getElementById('panel-' + m);
        if (m === method) {
            if (card) {
                card.classList.remove('border-slate-200');
                card.classList.add('border-indigo-600', 'bg-indigo-50/10', 'ring-2', 'ring-indigo-600/20');
            }
            if (panel) panel.classList.remove('hidden');
        } else {
            if (card) {
                card.classList.add('border-slate-200');
                card.classList.remove('border-indigo-600', 'bg-indigo-50/10', 'ring-2', 'ring-indigo-600/20');
            }
            if (panel) panel.classList.add('hidden');
        }
    });

    const submitText = document.getElementById('submit-text');
    if (method === 'VNPAY') {
        submitText.textContent = 'THANH TOÁN QUA VNPAY';
    } else {
        submitText.textContent = 'XÁC NHẬN ĐẶT HÀNG';
    }
}

// Khởi tạo active method
document.addEventListener('DOMContentLoaded', () => {
    const checked = document.querySelector('input[name="payment_method"]:checked')?.value || 'BANK';
    selectPayment(checked);
});

// Tiện ích sao chép nhanh
function copyText(text, label) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('Đã sao chép ' + label + ': ' + text, 'success');
    }).catch(() => {
        showToast('Sao chép thất bại', 'error');
    });
}

// Xử lý áp dụng voucher
function applyVoucher(code) {
    document.getElementById('coupon_code_input').value = code;
    submitCoupon();
}

function submitCoupon() {
    const code = document.getElementById('coupon_code_input').value.trim();
    if (!code) {
        showToast('Vui lòng nhập mã giảm giá', 'error');
        return;
    }
    window.location.href = '{{ route("checkout.index") }}?apply_coupon=' + encodeURIComponent(code);
}

document.getElementById('coupon_code_input')?.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        submitCoupon();
    }
});

// Tải danh sách Tỉnh/Thành -> Quận/Huyện -> Phường/Xã
document.addEventListener('DOMContentLoaded', function() {
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');

    fetch('https://esgoo.net/api-tinhthanh/1/0.htm')
        .then(res => res.json())
        .then(data => {
            if (data.error === 0) {
                let html = '<option value="">Chọn Tỉnh / Thành phố</option>';
                data.data.forEach(item => {
                    html += `<option value="${item.id}" data-name="${item.full_name}">${item.full_name}</option>`;
                });
                provinceSelect.innerHTML = html;
            }
        })
        .catch(err => {
            console.warn('API tỉnh thành mạng chậm, nạp danh sách dự phòng');
            provinceSelect.innerHTML = `
                <option value="">Chọn Tỉnh / Thành phố</option>
                <option value="01" data-name="Thành phố Hà Nội">Thành phố Hà Nội</option>
                <option value="79" data-name="Thành phố Hồ Chí Minh">Thành phố Hồ Chí Minh</option>
                <option value="48" data-name="Thành phố Đà Nẵng">Thành phố Đà Nẵng</option>
                <option value="31" data-name="Thành phố Hải Phòng">Thành phố Hải Phòng</option>
                <option value="92" data-name="Thành phố Cần Thơ">Thành phố Cần Thơ</option>
            `;
        });

    provinceSelect.addEventListener('change', function() {
        const id = this.value;
        const name = this.options[this.selectedIndex]?.getAttribute('data-name') || '';
        document.getElementById('province_name').value = name;
        
        districtSelect.innerHTML = '<option value="">Chọn Quận / Huyện</option>';
        wardSelect.innerHTML = '<option value="">Chọn Phường / Xã</option>';
        document.getElementById('district_name').value = '';
        document.getElementById('ward_name').value = '';

        if (id) {
            fetch(`https://esgoo.net/api-tinhthanh/2/${id}.htm`)
                .then(res => res.json())
                .then(data => {
                    if (data.error === 0) {
                        let html = '<option value="">Chọn Quận / Huyện</option>';
                        data.data.forEach(item => {
                            html += `<option value="${item.id}" data-name="${item.full_name}">${item.full_name}</option>`;
                        });
                        districtSelect.innerHTML = html;
                    }
                });
        }
    });

    districtSelect.addEventListener('change', function() {
        const id = this.value;
        const name = this.options[this.selectedIndex]?.getAttribute('data-name') || '';
        document.getElementById('district_name').value = name;
        
        wardSelect.innerHTML = '<option value="">Chọn Phường / Xã</option>';
        document.getElementById('ward_name').value = '';

        if (id) {
            fetch(`https://esgoo.net/api-tinhthanh/3/${id}.htm`)
                .then(res => res.json())
                .then(data => {
                    if (data.error === 0) {
                        let html = '<option value="">Chọn Phường / Xã</option>';
                        data.data.forEach(item => {
                            html += `<option value="${item.id}" data-name="${item.full_name}">${item.full_name}</option>`;
                        });
                        wardSelect.innerHTML = html;
                    }
                });
        }
    });

    wardSelect.addEventListener('change', function() {
        const name = this.options[this.selectedIndex]?.getAttribute('data-name') || '';
        document.getElementById('ward_name').value = name;
    });
});

    // Xử lý chọn hình thức vận chuyển Shopee
    let currentShippingFee = {{ ($subtotal >= 500000) ? 0 : 25000 }};
    const baseTotalAmount = {{ $total }};

    function selectShipping(method, fee) {
        currentShippingFee = fee;
        
        // Cập nhật viền lựa chọn
        document.querySelectorAll('.shipping-option').forEach(el => {
            el.classList.remove('border-orange-500', 'bg-orange-50/20');
            el.classList.add('border-slate-200', 'bg-white');
        });
        
        const selectedEl = document.getElementById('ship-opt-' + method);
        if (selectedEl) {
            selectedEl.classList.remove('border-slate-200', 'bg-white');
            selectedEl.classList.add('border-orange-500', 'bg-orange-50/20');
        }
        
        // Cập nhật nhãn đơn vị vận chuyển
        const carrierMap = {
            'standard': 'SPX Express (Nhanh)',
            'express': 'Shopee Xpress Hỏa Tốc',
            'economy': 'Viettel Post Tiết Kiệm'
        };
        const carrierBadge = document.getElementById('summary-shipping-carrier');
        if (carrierBadge) carrierBadge.textContent = carrierMap[method] || 'SPX Express';
        
        // Cập nhật phí vận chuyển tóm tắt
        const feeEl = document.getElementById('summary-shipping-fee');
        if (feeEl) {
            if (fee === 0) {
                feeEl.textContent = 'MIỄN PHÍ';
                feeEl.className = 'font-semibold text-emerald-600';
            } else {
                feeEl.textContent = fee.toLocaleString('vi-VN') + 'đ';
                feeEl.className = 'font-semibold text-slate-900 font-mono';
            }
        }
        
        // Cập nhật tổng thanh toán cuối cùng (cộng phí ship vào gốc!)
        const grandTotal = baseTotalAmount + fee;
        const formattedTotal = grandTotal.toLocaleString('vi-VN') + 'đ';

        const totalDisplay = document.getElementById('final-total-display') || document.getElementById('summary-total');
        if (totalDisplay) {
            totalDisplay.textContent = formattedTotal;
        }

        // Cập nhật số tiền trong mục VietQR
        const qrAmountEl = document.getElementById('bank-transfer-amount');
        if (qrAmountEl) {
            qrAmountEl.textContent = formattedTotal;
        }
        const qrImgEl = document.getElementById('vietqr-preview-img');
        if (qrImgEl) {
            qrImgEl.src = `https://img.vietqr.io/image/TCB-202108059999-compact2.png?amount=${grandTotal}&addInfo=Thanh toan don hang&accountName=TA QUANG HA`;
        }
    }
</script>
@endsection
