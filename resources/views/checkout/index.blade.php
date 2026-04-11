@extends('layouts.storefront')

@section('title', 'Thanh Toán')

@section('content')
<!-- Breadcrumb -->
<div class="bg-gray-50 py-4">
    <div class="container mx-auto px-4">
        <nav class="text-sm">
            <ol class="flex items-center gap-2">
                <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-red-600">Trang chủ</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 font-medium">Thanh toán</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">THÔNG TIN ĐẶT HÀNG</h1>
    
    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-3 gap-8">
            <!-- Left Column - Customer Info & Payment -->
            <div class="col-span-2 space-y-6">
                <!-- Customer Information -->
                <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b border-gray-200">THÔNG TIN GIAO HÀNG</h2>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nhập vào Họ Tên</label>
                            <input type="text" name="customer_name" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded focus:border-red-600 focus:outline-none" 
                                   required 
                                   value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                                   placeholder="Nhập họ và tên">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nhập vào Số điện thoại</label>
                            <input type="tel" name="customer_phone" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded focus:border-red-600 focus:outline-none" 
                                   required 
                                   value="{{ old('customer_phone') }}"
                                   placeholder="Nhập số điện thoại">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nhập vào Email</label>
                        <input type="email" name="customer_email" 
                               class="w-full px-4 py-2 border border-gray-300 rounded focus:border-red-600 focus:outline-none" 
                               value="{{ old('customer_email', auth()->user()->email ?? '') }}"
                               placeholder="Nhập email">
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Chọn Tỉnh</label>
                            <select name="province" id="province" class="w-full px-4 py-2 border border-gray-300 rounded focus:border-red-600 focus:outline-none" required>
                                <option value="">Chọn tỉnh/thành phố</option>
                            </select>
                            <input type="hidden" name="province_name" id="province_name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Chọn Quận</label>
                            <select name="district" id="district" class="w-full px-4 py-2 border border-gray-300 rounded focus:border-red-600 focus:outline-none" required>
                                <option value="">Chọn quận/huyện</option>
                            </select>
                            <input type="hidden" name="district_name" id="district_name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Chọn Phường</label>
                            <select name="ward" id="ward" class="w-full px-4 py-2 border border-gray-300 rounded focus:border-red-600 focus:outline-none" required>
                                <option value="">Chọn phường/xã</option>
                            </select>
                            <input type="hidden" name="ward_name" id="ward_name">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ (số nhà, tên đường, tòa...)</label>
                        <input type="text" name="customer_address" 
                               class="w-full px-4 py-2 border border-gray-300 rounded focus:border-red-600 focus:outline-none" 
                               required 
                               value="{{ old('customer_address') }}"
                               placeholder="Nhập địa chỉ chi tiết">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ghi chú</label>
                        <textarea name="notes" rows="3" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded focus:border-red-600 focus:outline-none"
                                  placeholder="Ghi chú thêm (tùy chọn)">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b border-gray-200">PHƯƠNG THỨC THANH TOÁN</h2>
                    
                    <div class="space-y-3">
                        <!-- COD -->
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded cursor-pointer hover:border-red-600 transition-colors">
                            <input type="radio" name="payment_method" value="COD" checked class="w-5 h-5 text-red-600 focus:ring-red-500" onchange="toggleBankInfo(false)">
                            <div class="ml-3 flex items-center gap-3 flex-1">
                                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span class="font-medium text-gray-900">Thanh toán khi nhận hàng (COD)</span>
                            </div>
                        </label>

                        <!-- MoMo / EWALLET -->
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded cursor-pointer hover:border-red-600 transition-colors">
                            <input type="radio" name="payment_method" value="EWALLET" class="w-5 h-5 text-red-600 focus:ring-red-500" onchange="toggleBankInfo(false)">
                            <div class="ml-3 flex items-center gap-3 flex-1">
                                <div class="w-8 h-8 bg-pink-600 rounded flex items-center justify-center text-white font-bold text-xs">M</div>
                                <span class="font-medium text-gray-900">Thanh toán qua Ví Momo</span>
                            </div>
                        </label>

                        <!-- VNPay -->
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded cursor-pointer hover:border-red-600 transition-colors">
                            <input type="radio" name="payment_method" value="VNPAY" class="w-5 h-5 text-red-600 focus:ring-red-500" onchange="toggleBankInfo(false)">
                            <div class="ml-3 flex items-center gap-3 flex-1">
                                <div class="w-8 h-8 bg-blue-600 rounded flex items-center justify-center text-white font-bold text-xs">VP</div>
                                <span class="font-medium text-gray-900">Thanh toán ví điện tử VNPAY</span>
                            </div>
                        </label>

                        <!-- Bank -->
                        <div class="border-2 border-gray-200 rounded hover:border-red-600 transition-colors">
                            <label class="flex items-center p-4 cursor-pointer w-full">
                                <input type="radio" name="payment_method" value="BANK" class="w-5 h-5 text-red-600 focus:ring-red-500" onchange="toggleBankInfo(true)">
                                <div class="ml-3 flex items-center gap-3 flex-1">
                                    <svg class="w-8 h-8 text-blue-700" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106zm14.146-14.42a3.35 3.35 0 0 0-.607-.541c-.013.076-.026.175-.041.254-.93 4.778-4.005 7.201-9.138 7.201h-2.19a.563.563 0 0 0-.556.479l-1.187 7.527h-.506l-.24 1.516a.56.56 0 0 0 .554.647h3.882c.46 0 .85-.334.922-.788.06-.26.76-4.852.76-4.852a.932.932 0 0 1 .922-.788h.58c3.76 0 6.705-1.528 7.565-5.946.36-1.847.174-3.388-.777-4.471z"/>
                                    </svg>
                                    <span class="font-medium text-gray-900">Chuyển khoản ngân hàng</span>
                                </div>
                            </label>

                            <!-- Bảng thông tin chuyển khoản ẩn/hiện -->
                            <div id="bank-transfer-info" class="hidden border-t border-gray-200 bg-gray-50 p-4 animate-fadeIn">
                                <p class="text-sm font-bold text-gray-800 mb-4 text-center text-red-600">Quét mã QR để thanh toán nhanh</p>
                                <div class="flex flex-col md:flex-row gap-6 items-center">
                                    <img src="https://img.vietqr.io/image/TCB-202108059999-compact2.png?amount={{ $total }}&addInfo=Thanh toan don hang&accountName=TA QUANG HA" alt="QR Code Bank" class="w-40 h-40 rounded-lg shadow border border-gray-200 bg-white">
                                    <div class="text-[13px] text-gray-700 space-y-2.5">
                                        <p><span class="text-gray-500 w-24 inline-block">Ngân hàng:</span> <strong class="uppercase text-gray-900">Techcombank</strong></p>
                                        <p><span class="text-gray-500 w-24 inline-block">Số tài khoản:</span> <strong class="font-mono text-base text-red-600 tracking-wider">202108059999</strong></p>
                                        <p><span class="text-gray-500 w-24 inline-block">Chủ tài khoản:</span> <strong class="uppercase">TẠ QUANG HÀ</strong></p>
                                        <p><span class="text-gray-500 w-24 inline-block">Số tiền:</span> <strong class="text-base text-gray-900">{{ number_format($total) }}đ</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-gray-50 rounded text-sm text-gray-600">
                        <p class="font-semibold mb-2">Chính sách hoàn hàng, đổi hàng:</p>
                        <p>Người trả lại sản phẩm sẽ xuất hiện ngay cả khi nhận hàng, những trường hợp cần trả hàng như: Sản phẩm bị lỗi, không đúng với mô tả, không đúng với yêu cầu của khách hàng, những trường hợp cần trả hàng khác. Trả hàng.</p>
                    </div>
                </div>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="col-span-1">
                <div class="bg-white rounded-lg shadow border border-gray-200 p-6 sticky top-24">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b border-gray-200">ĐƠN HÀNG</h2>
                    
                    <!-- Cart Items -->
                    <div class="space-y-4 mb-4 max-h-64 overflow-y-auto">
                        @foreach($cart as $id => $item)
                        <div class="flex gap-3 pb-4 border-b border-gray-100">
                            <div class="relative">
                                <img src="{{ asset('storage/' . $item['image']) }}" 
                                     alt="{{ $item['name'] }}" 
                                     class="w-16 h-16 object-cover rounded border border-gray-200">
                                <span class="absolute -top-2 -right-2 w-6 h-6 bg-red-600 text-white rounded-full flex items-center justify-center text-xs font-bold">
                                    {{ $item['quantity'] }}
                                </span>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900 line-clamp-2">{{ $item['name'] }}</h4>
                                @if(isset($item['size']) || isset($item['color']))
                                <p class="text-xs text-gray-500 mt-1">
                                    @if(isset($item['size']))Size: {{ $item['size'] }}@endif
                                    @if(isset($item['size']) && isset($item['color'])), @endif
                                    @if(isset($item['color']))Màu: {{ $item['color'] }}@endif
                                </p>
                                @endif
                                <p class="text-sm font-bold text-red-600 mt-1">{{ number_format($item['price']) }}đ</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- ===== VOUCHER / MÃ GIẢM GIÁ ===== --}}
                    <div class="mb-4 border border-dashed border-red-300 rounded-lg overflow-hidden">
                        {{-- Header --}}
                        <div class="bg-red-50 px-4 py-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                            <span class="text-sm font-bold text-red-700">Mã Giảm Giá / Voucher</span>
                            @if(session('coupon_code'))
                            <span class="ml-auto bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded-full">
                                Đang dùng: {{ session('coupon_code') }}
                            </span>
                            @endif
                        </div>

                        <div class="p-4 space-y-3">
                            {{-- Voucher đã lưu --}}
                            @auth
                            @if(isset($savedCoupons) && $savedCoupons->count() > 0)
                            {{-- Có voucher đã lưu --}}
                            <div>
                                <p class="text-xs font-semibold text-gray-700 mb-2">🎟️ Voucher của bạn ({{ $savedCoupons->count() }} voucher):</p>
                                <div class="space-y-2 max-h-44 overflow-y-auto pr-1">
                                    @foreach($savedCoupons as $savedC)
                                    @php $c = $savedC->coupon; @endphp
                                    <button type="button"
                                        onclick="applyVoucher('{{ $c->code }}')"
                                        class="w-full text-left flex items-center gap-3 p-2.5 rounded-lg border-2 transition-all
                                               {{ session('coupon_code') == $c->code
                                                    ? 'border-red-500 bg-red-50 shadow-sm'
                                                    : 'border-gray-200 hover:border-red-400 hover:bg-red-50' }}">
                                        <div class="w-12 h-12 rounded-lg flex flex-col items-center justify-center text-white flex-shrink-0
                                                    {{ session('coupon_code') == $c->code ? 'bg-red-600' : 'bg-gray-600' }}">
                                            @if($c->type === 'percentage')
                                            <span class="text-sm font-black leading-none">-{{ $c->value }}%</span>
                                            @else
                                            <span class="text-xs font-black leading-none">-{{ number_format($c->value/1000) }}K</span>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-sm text-gray-900 font-mono tracking-wide">{{ $c->code }}</p>
                                            <p class="text-xs text-gray-500">
                                                {{ $c->discountLabel }}
                                                @if($c->min_order_amount > 0) · Đơn từ {{ number_format($c->min_order_amount) }}đ @endif
                                            </p>
                                        </div>
                                        @if(session('coupon_code') == $c->code)
                                        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        @else
                                        <span class="text-xs text-red-600 font-medium flex-shrink-0">Chọn</span>
                                        @endif
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                            <div class="border-t border-dashed border-gray-200 pt-3">
                            @else
                            {{-- Chưa có voucher lưu --}}
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center gap-2">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs font-medium text-gray-600">Bạn chưa lưu voucher nào</p>
                                        <p class="text-xs text-gray-400">Lưu voucher để dùng nhanh khi mua hàng</p>
                                    </div>
                                </div>
                                <a href="{{ route('voucher.index') }}" target="_blank"
                                   class="flex-shrink-0 bg-red-600 text-white text-xs font-bold px-3 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                    Xem voucher
                                </a>
                            </div>
                            <div class="pt-1">
                            @endif
                            @else
                            {{-- Chưa đăng nhập --}}
                            <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-xs text-blue-700"><a href="{{ route('login') }}" class="font-bold underline">Đăng nhập</a> để dùng voucher đã lưu</p>
                            </div>
                            <div class="pt-1">
                            @endauth

                            {{-- Nhập mã thủ công -- luôn hiển thị --}}
                            <div class="flex gap-2">
                                <input type="text" id="coupon_code_input" name="coupon_code"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-red-600 focus:outline-none font-mono tracking-wide"
                                       placeholder="Nhập mã giảm giá..."
                                       value="{{ session('coupon_code') }}">
                                <button type="button" onclick="submitCoupon()"
                                    class="px-4 py-2 bg-red-600 text-white text-sm font-bold rounded-lg hover:bg-red-700 transition-colors whitespace-nowrap">
                                    Áp dụng
                                </button>
                            </div>

                            {{-- Messages --}}
                            @if(session('coupon_success'))
                            <div class="flex items-center gap-2 mt-2 text-sm text-green-700 bg-green-50 px-3 py-2 rounded-lg">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                {{ session('coupon_success') }}
                            </div>
                            @endif
                            @if(session('coupon_error'))
                            <div class="flex items-center gap-2 mt-2 text-sm text-red-700 bg-red-50 px-3 py-2 rounded-lg">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ session('coupon_error') }}
                            </div>
                            @endif

                            {{-- Đóng div inner mở ở trên --}}
                            @auth
                            </div>
                            @endauth
                        </div>
                    </div>

                    <!-- Price Summary -->
                    <div class="space-y-3 mb-4 pb-4 border-b border-gray-200">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tạm tính</span>
                            <span class="text-gray-900">{{ number_format($subtotal) }}đ</span>
                        </div>
                        @if(isset($membershipDiscount) && $membershipDiscount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">
                                Ưu đãi hạng <span class="font-bold text-red-600">{{ auth()->user()->membership_tier_label }}</span>
                            </span>
                            <span class="text-green-600 font-bold">-{{ number_format($membershipDiscount) }}đ</span>
                        </div>
                        @endif
                        @if(session('discount', 0) > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Mã giảm giá <span class="font-mono text-red-600 font-bold">{{ session('coupon_code') }}</span></span>
                            <span class="text-green-600 font-bold">-{{ number_format(session('discount', 0)) }}đ</span>
                        </div>
                        @endif
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Phí giao hàng</span>
                            <span class="text-green-600 font-medium">Miễn phí</span>
                        </div>
                        <div class="flex justify-between text-base font-bold pt-2 border-t border-gray-200">
                            <span class="text-gray-900">Tổng thanh toán</span>
                            <span class="text-red-600 text-xl">{{ number_format($total) }}đ</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-green-600 text-white py-4 rounded font-bold text-lg hover:bg-green-700 transition-colors">
                        THANH TOÁN ĐƠN HÀNG
                    </button>

                    <!-- Additional Info -->
                    <div class="mt-4 p-4 bg-blue-50 rounded">
                        <h4 class="font-semibold text-gray-900 mb-2 text-sm">Thông tin bổ sung</h4>
                        <p class="text-xs text-gray-600">Nếu bạn không hài lòng với sản phẩm chúng tôi sẽ hoàn tiền lại cho bạn. Tuy nhiên bạn phải trả lời phí vận chuyển. Tư vấn.</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
function toggleBankInfo(show) {
    const infoDiv = document.getElementById('bank-transfer-info');
    if (show) {
        infoDiv.classList.remove('hidden');
    } else {
        infoDiv.classList.add('hidden');
    }
}

// Điền mã voucher vào ô input và submit áp dụng
function applyVoucher(code) {
    document.getElementById('coupon_code_input').value = code;
    submitCoupon();
}

// Submit form áp dụng mã giảm giá qua GET request
function submitCoupon() {
    const code = document.getElementById('coupon_code_input').value.trim();
    if (!code) return;
    window.location.href = '{{ route("checkout.index") }}?apply_coupon=' + encodeURIComponent(code);
}

// Enter key trên input cũng kích hoạt áp dụng
document.getElementById('coupon_code_input')?.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); submitCoupon(); }
});

document.addEventListener('DOMContentLoaded', function() {
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    
    // Fetch provinces
    fetch('https://esgoo.net/api-tinhthanh/1/0.htm')
        .then(response => response.json())
        .then(data => {
            if (data.error === 0) {
                let html = '<option value="">Chọn tỉnh/thành phố</option>';
                data.data.forEach(item => {
                    html += `<option value="${item.id}" data-name="${item.full_name}">${item.full_name}</option>`;
                });
                provinceSelect.innerHTML = html;
            }
        });

    provinceSelect.addEventListener('change', function() {
        const id = this.value;
        const name = this.options[this.selectedIndex]?.getAttribute('data-name') || '';
        document.getElementById('province_name').value = name;
        
        districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
        document.getElementById('district_name').value = '';
        document.getElementById('ward_name').value = '';
        
        if (id) {
            fetch(`https://esgoo.net/api-tinhthanh/2/${id}.htm`)
                .then(response => response.json())
                .then(data => {
                    if (data.error === 0) {
                        let html = '<option value="">Chọn quận/huyện</option>';
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
        
        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
        document.getElementById('ward_name').value = '';
        
        if (id) {
            fetch(`https://esgoo.net/api-tinhthanh/3/${id}.htm`)
                .then(response => response.json())
                .then(data => {
                    if (data.error === 0) {
                        let html = '<option value="">Chọn phường/xã</option>';
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
</script>
@endsection
