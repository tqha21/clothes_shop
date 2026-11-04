@extends('layouts.storefront')

@section('title', 'Đặt Hàng Thành Công #' . str_pad($order->id, 6, '0', STR_PAD_LEFT))

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10 print:p-0">

    <!-- Print-only Brand Header -->
    <div class="hidden print:block mb-6 border-b pb-4">
        <h1 class="text-2xl font-bold">HTD STORE - HÓA ĐƠN BÁN HÀNG</h1>
        <p class="text-xs text-gray-500">Mã đơn hàng: #HTD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }} | Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <!-- Success Hero Banner (Screen only) -->
    <div class="print:hidden relative overflow-hidden bg-gradient-to-r from-emerald-600 via-teal-600 to-indigo-600 rounded-3xl shadow-xl shadow-emerald-500/10 p-8 sm:p-10 text-center text-white mb-8">
        <!-- Glow accents -->
        <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10">
            <div class="w-20 h-20 bg-white text-emerald-600 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-black/10">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            
            <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight mb-2">ĐẶT HÀNG THÀNH CÔNG!</h1>
            <p class="text-emerald-100 text-sm sm:text-base max-w-xl mx-auto">
                Cảm ơn <strong>{{ $order->customer_name }}</strong> đã tin tưởng mua sắm tại HTD Store. Đơn hàng của bạn đã được tiếp nhận và đang được xử lý.
            </p>

            <!-- Order Badge -->
            <div class="mt-6 inline-flex flex-wrap items-center justify-center gap-3 bg-white/15 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/20">
                <span class="text-xs uppercase tracking-wider text-emerald-100 font-semibold">Mã đơn hàng:</span>
                <span class="text-xl sm:text-2xl font-black font-mono tracking-wider">#HTD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>
    </div>

    <!-- KHỐI HƯỚNG DẪN THANH TOÁN (BANK / MOMO / VNPAY / COD) -->
    @if($order->payment_method === 'BANK')
    <div class="print:border print:border-slate-300 bg-white rounded-3xl shadow-lg shadow-slate-200/50 border-2 border-indigo-500/80 p-6 sm:p-8 mb-8 relative overflow-hidden">
        <div class="flex flex-col sm:flex-row items-center justify-between pb-4 mb-6 border-b border-slate-100 gap-3">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center border border-indigo-100">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 flex items-center gap-2">
                        Quét Mã VietQR Chuyển Khoản
                        <span class="bg-red-100 text-red-700 text-xs font-bold px-2.5 py-0.5 rounded-full">Napas 247</span>
                    </h2>
                    <p class="text-xs text-slate-500">Mã QR đã bao gồm chính xác số tiền & nội dung đơn hàng</p>
                </div>
            </div>
            <span class="text-xs font-semibold px-3 py-1.5 rounded-xl bg-amber-50 text-amber-700 border border-amber-200 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                Chờ chuyển khoản
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
            <!-- QR Image Box -->
            <div class="md:col-span-5 flex flex-col items-center text-center">
                <div class="p-3 bg-gradient-to-b from-indigo-50 to-white rounded-2xl border border-slate-200 shadow-inner">
                    @php
                        $qrUrl = "https://img.vietqr.io/image/TCB-202108059999-compact2.png?amount={$order->total}&addInfo=HTD{$order->id}&accountName=TA%20QUANG%20HA";
                    @endphp
                    <img src="{{ $qrUrl }}" 
                         alt="Mã QR VietQR Đơn Hàng #HTD-{{ $order->id }}" 
                         class="w-56 h-56 object-contain rounded-xl bg-white shadow-sm"
                         id="qr-image">
                </div>
                <div class="mt-3 flex items-center gap-2">
                    <a href="{{ $qrUrl }}" download="VietQR_HTD_{{ $order->id }}.png" target="_blank" class="text-xs font-semibold px-3 py-1.5 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Tải mã QR
                    </a>
                    <span class="text-[11px] text-slate-400">Hỗ trợ mọi App Ngân Hàng</span>
                </div>
            </div>

            <!-- Transfer Info Box -->
            <div class="md:col-span-7 space-y-3.5">
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 space-y-3 text-xs sm:text-sm">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-200/80">
                        <span class="text-slate-500">Ngân hàng:</span>
                        <strong class="text-slate-900 font-bold uppercase">Techcombank (TCB)</strong>
                    </div>

                    <div class="flex items-center justify-between pb-2 border-b border-slate-200/80">
                        <span class="text-slate-500">Chủ tài khoản:</span>
                        <strong class="text-slate-900 font-bold uppercase">TẠ QUANG HÀ</strong>
                    </div>

                    <div class="flex items-center justify-between pb-2 border-b border-slate-200/80">
                        <span class="text-slate-500">Số tài khoản:</span>
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-base font-bold text-red-600">202108059999</span>
                            <button type="button" onclick="copyText('202108059999', 'Số tài khoản')" class="px-2 py-1 bg-white border border-slate-200 rounded text-xs font-semibold text-indigo-600 hover:bg-indigo-50 transition shadow-2xs">Sao chép</button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pb-2 border-b border-slate-200/80">
                        <span class="text-slate-500">Số tiền cần thanh toán:</span>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-base text-indigo-700">{{ number_format($order->total) }}đ</span>
                            <button type="button" onclick="copyText('{{ $order->total }}', 'Số tiền')" class="px-2 py-1 bg-white border border-slate-200 rounded text-xs font-semibold text-indigo-600 hover:bg-indigo-50 transition shadow-2xs">Sao chép</button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Nội dung chuyển khoản:</span>
                        <div class="flex items-center gap-2">
                            <span class="font-mono font-bold text-slate-900 bg-yellow-100 text-yellow-900 px-2.5 py-1 rounded-lg">HTD{{ $order->id }}</span>
                            <button type="button" onclick="copyText('HTD{{ $order->id }}', 'Nội dung chuyển khoản')" class="px-2 py-1 bg-white border border-slate-200 rounded text-xs font-semibold text-indigo-600 hover:bg-indigo-50 transition shadow-2xs">Sao chép</button>
                        </div>
                    </div>
                </div>

                <div id="transfer-confirm-box" class="pt-2">
                    <button type="button" onclick="confirmTransferPaid()" id="btn-paid-confirm" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-sm shadow-md transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Tôi Đã Chuyển Khoản Xong
                    </button>
                </div>
            </div>
        </div>
    </div>
    @elseif($order->payment_method === 'EWALLET')
    <div class="bg-white rounded-3xl shadow-lg border-2 border-pink-400 p-6 sm:p-8 mb-8">
        <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-pink-600 text-white font-bold flex items-center justify-center text-lg">M</div>
            <div>
                <h3 class="font-bold text-slate-900 text-lg">Thanh toán qua Ví MoMo</h3>
                <p class="text-xs text-slate-500">Chuyển tiền qua App MoMo tới số điện thoại</p>
            </div>
        </div>
        <div class="p-4 bg-pink-50/50 rounded-2xl border border-pink-100 space-y-2 text-sm">
            <p>Số MoMo: <strong class="font-mono text-pink-600 text-base">0383390611</strong> (TẠ QUANG HÀ)</p>
            <p>Số tiền: <strong class="text-indigo-600">{{ number_format($order->total) }}đ</strong></p>
            <p>Lời nhắn: <strong class="font-mono bg-white px-2 py-0.5 rounded border border-pink-200">HTD{{ $order->id }}</strong></p>
        </div>
    </div>
    @elseif($order->payment_method === 'VNPAY')
    <div class="bg-white rounded-3xl shadow-lg border-2 border-blue-400 p-6 sm:p-8 mb-8">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 font-bold flex items-center justify-center text-sm border border-blue-100">VNPAY</div>
            <div>
                <h3 class="font-bold text-slate-900 text-lg">Thanh toán Cổng VNPAY</h3>
                <p class="text-xs text-slate-500">Đơn hàng được bảo đảm qua Cổng VNPAY</p>
            </div>
        </div>
    </div>
    @else
    <div class="bg-white rounded-3xl shadow-lg border-2 border-emerald-400 p-6 sm:p-8 mb-8">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-900 text-lg">Thanh toán khi nhận hàng (COD)</h3>
                <p class="text-xs text-slate-500">Bạn sẽ thanh toán số tiền <strong>{{ number_format($order->total) }}đ</strong> trực tiếp cho shipper khi nhận và kiểm tra hàng.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- CHI TIẾT ĐƠN HÀNG & LỘ TRÌNH -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Cột trái: Sản phẩm & Thông tin người nhận (8 cols) -->
        <div class="lg:col-span-8 space-y-6">

            <!-- Danh sách sản phẩm -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-base font-bold text-slate-900 pb-3 mb-4 border-b border-slate-100 flex items-center justify-between">
                    <span>📦 Sản Phẩm Đã Đặt ({{ $order->items->count() }})</span>
                    <span class="text-xs font-normal text-slate-400">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</span>
                </h3>

                <div class="divide-y divide-slate-100">
                    @foreach($order->items as $item)
                    <div class="flex gap-4 py-3.5 first:pt-0 last:pb-0 items-center">
                        <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0 border border-slate-200">
                            <img src="{{ asset('storage/' . ($item->product->image ?? '')) }}" 
                                 alt="{{ $item->product->name ?? 'Sản phẩm' }}" 
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-slate-900 line-clamp-1">{{ $item->product->name ?? 'Sản phẩm' }}</h4>
                            <div class="flex items-center gap-2 text-xs text-slate-500 mt-0.5">
                                @if($item->size)<span class="bg-slate-100 px-1.5 py-0.5 rounded text-[11px]">Size: {{ $item->size }}</span>@endif
                                @if($item->color)<span class="bg-slate-100 px-1.5 py-0.5 rounded text-[11px]">Màu: {{ $item->color }}</span>@endif
                                <span>SL: x{{ $item->quantity }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-slate-900">{{ number_format($item->price * $item->quantity) }}đ</p>
                            <p class="text-[11px] text-slate-400">{{ number_format($item->price) }}đ / món</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- THÔNG TIN VẬN CHUYỂN & LỘ TRÌNH CHUẨN SHOPEE EXPRESS -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <!-- Header Vận chuyển phong cách Shopee -->
                <div class="bg-gradient-to-r from-orange-500 via-orange-600 to-amber-500 p-5 sm:p-6 text-white">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-2xl shadow-inner border border-white/30 flex-shrink-0">
                                🚚
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-base sm:text-lg font-bold text-white tracking-wide">
                                        {{ $order->shipping_carrier ?? 'SPX Express (Shopee Xpress)' }}
                                    </h3>
                                    <span class="bg-white text-orange-600 text-[10px] font-extrabold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                        {{ $order->shipping_method_name }}
                                    </span>
                                </div>
                                <p class="text-xs text-orange-100 mt-0.5 flex items-center gap-2">
                                    <span>Mã vận đơn:</span>
                                    <strong class="font-mono text-sm tracking-wider text-white bg-black/20 px-2 py-0.5 rounded-lg border border-white/20">
                                        {{ $order->formatted_tracking_code }}
                                    </strong>
                                    <button type="button" onclick="copyText('{{ $order->formatted_tracking_code }}', 'Mã vận đơn')" class="text-[11px] underline text-orange-100 hover:text-white transition">
                                        Sao chép
                                    </button>
                                </p>
                            </div>
                        </div>

                        <div class="sm:text-right bg-white/10 sm:bg-transparent p-2.5 sm:p-0 rounded-xl backdrop-blur-xs sm:backdrop-blur-none border border-white/15 sm:border-0">
                            <span class="text-xs text-orange-100 block">Thời gian giao dự kiến:</span>
                            <strong class="text-sm sm:text-base font-bold text-white block">
                                {{ $order->estimated_delivery ?? 'Nhận hàng vào 2-3 ngày tới' }}
                            </strong>
                        </div>
                    </div>

                    <!-- Badge quyền lợi Đồng kiểm -->
                    <div class="mt-4 pt-3 border-t border-white/20 flex items-center justify-between text-xs text-orange-100 flex-wrap gap-2">
                        <div class="flex items-center gap-1.5">
                            <span class="w-4 h-4 rounded-full bg-emerald-400 text-emerald-950 flex items-center justify-center text-[10px] font-black">✓</span>
                            <span><strong>Được đồng kiểm:</strong> Kiểm tra ngoại quan và mẫu mã cùng bưu tá trước khi nhận hàng</span>
                        </div>
                        <span class="bg-black/20 text-white text-[11px] px-2.5 py-1 rounded-lg border border-white/20">
                            Đổi trả 7 ngày miễn phí
                        </span>
                    </div>
                </div>

                <!-- Lộ trình vận chuyển chi tiết theo trạm (Shopee Tracking Timeline) -->
                <div class="p-6">
                    <h4 class="text-sm font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Lộ Trình Vận Chuyển Chi Tiết
                    </h4>

                    @php
                        $isCompleted = ($order->status === 'completed');
                        $isShipping = in_array($order->status, ['shipping', 'completed']);
                        $isPending = ($order->status === 'pending');
                    @endphp

                    <div class="relative pl-6 space-y-7 before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">

                        <!-- Trạm 1: Đặt hàng thành công -->
                        <div class="relative group">
                            <div class="absolute -left-6 top-1 w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold shadow-md shadow-emerald-500/30 ring-4 ring-white">
                                ✓
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                                <h5 class="text-xs sm:text-sm font-bold text-slate-900 flex items-center gap-2">
                                    Đơn hàng đã được đặt thành công
                                    <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">Hoàn tất</span>
                                </h5>
                                <span class="text-xs font-mono text-slate-400">{{ $order->created_at->format('H:i d/m/Y') }}</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">Đơn hàng #HTD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }} đã được tiếp nhận vào hệ thống kho vận.</p>
                        </div>

                        <!-- Trạm 2: Đang chuẩn bị hàng -->
                        <div class="relative group">
                            <div class="absolute -left-6 top-1 w-5 h-5 rounded-full {{ $isPending ? 'bg-orange-500 text-white animate-pulse ring-4 ring-orange-100' : 'bg-emerald-500 text-white ring-4 ring-white' }} flex items-center justify-center text-[10px] font-bold shadow-xs">
                                {{ $isPending ? '●' : '✓' }}
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                                <h5 class="text-xs sm:text-sm font-bold {{ $isPending ? 'text-orange-600' : 'text-slate-900' }} flex items-center gap-2">
                                    Người bán đang chuẩn bị đơn hàng
                                    @if($isPending)
                                    <span class="text-[10px] font-bold text-orange-700 bg-orange-100 px-2 py-0.5 rounded-full">Hiện tại</span>
                                    @else
                                    <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">Hoàn tất</span>
                                    @endif
                                </h5>
                                <span class="text-xs font-mono text-slate-400">{{ $order->created_at->addMinutes(20)->format('H:i d/m/Y') }}</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">Tổng kho HTD Store đang kiểm tra chất lượng, đóng gói tiêu chuẩn và dán mã vận đơn <strong>{{ $order->formatted_tracking_code }}</strong>.</p>
                        </div>

                        <!-- Trạm 3: Đơn vị vận chuyển đã lấy hàng -->
                        <div class="relative group">
                            <div class="absolute -left-6 top-1 w-5 h-5 rounded-full {{ $isShipping ? 'bg-emerald-500 text-white ring-4 ring-white' : 'bg-slate-200 text-slate-400' }} flex items-center justify-center text-[10px] font-bold">
                                {{ $isShipping ? '✓' : '3' }}
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                                <h5 class="text-xs sm:text-sm font-bold {{ $isShipping ? 'text-slate-900' : 'text-slate-500' }}">
                                    Đơn vị vận chuyển đã lấy hàng & nhập kho phân loại
                                </h5>
                                <span class="text-xs font-mono text-slate-400">
                                    {{ $isShipping ? $order->created_at->addHours(2)->format('H:i d/m/Y') : 'Dự kiến hôm nay' }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">Bưu tá {{ $order->shipping_carrier }} đã nhận kiện hàng. Hàng đã qua máy quét tại Kho Phân Loại Trung Tâm SPX Hub.</p>
                        </div>

                        <!-- Trạm 4: Đang trung chuyển liên tỉnh -->
                        <div class="relative group">
                            <div class="absolute -left-6 top-1 w-5 h-5 rounded-full {{ $isShipping ? 'bg-orange-500 text-white animate-pulse ring-4 ring-orange-100' : 'bg-slate-200 text-slate-400' }} flex items-center justify-center text-[10px] font-bold">
                                {{ $isShipping && !$isCompleted ? '●' : ($isCompleted ? '✓' : '4') }}
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                                <h5 class="text-xs sm:text-sm font-bold {{ $isShipping ? 'text-slate-900' : 'text-slate-500' }} flex items-center gap-2">
                                    Đang trung chuyển đến bưu cục phát khu vực của bạn
                                    @if($isShipping && !$isCompleted)
                                    <span class="text-[10px] font-bold text-orange-700 bg-orange-100 px-2 py-0.5 rounded-full">Đang giao</span>
                                    @endif
                                </h5>
                                <span class="text-xs font-mono text-slate-400">
                                    {{ $isShipping ? $order->created_at->addHours(12)->format('H:i d/m/Y') : 'Dự kiến' }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">Kiện hàng đang được điều phối trên tuyến trung chuyển hỏa tốc đến điểm giao nhận gần nhất.</p>
                        </div>

                        <!-- Trạm 5: Giao hàng thành công -->
                        <div class="relative group">
                            <div class="absolute -left-6 top-1 w-5 h-5 rounded-full {{ $isCompleted ? 'bg-emerald-500 text-white ring-4 ring-white' : 'bg-slate-200 text-slate-400' }} flex items-center justify-center text-[10px] font-bold">
                                {{ $isCompleted ? '✓' : '5' }}
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                                <h5 class="text-xs sm:text-sm font-bold {{ $isCompleted ? 'text-emerald-700' : 'text-slate-500' }}">
                                    Giao hàng thành công & Hoàn tất đồng kiểm
                                </h5>
                                <span class="text-xs font-mono text-slate-400">
                                    {{ $isCompleted ? $order->updated_at->format('H:i d/m/Y') : ($order->estimated_delivery ?? 'Dự kiến') }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">
                                @if($isCompleted)
                                Người nhận đã đồng kiểm tra kiện hàng và hoàn tất nhận bưu phẩm.
                                @else
                                Bưu tá sẽ liên hệ số điện thoại <strong>{{ $order->customer_phone }}</strong> trước khi giao. Vui lòng giữ liên lạc.
                                @endif
                            </p>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!-- Cột phải: Thông tin giao hàng & Tổng tiền (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-4">
                <h3 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100">Thông Tin Giao Nhận</h3>

                <div class="space-y-3 text-xs sm:text-sm">
                    <div>
                        <span class="text-slate-400 text-xs block">Người nhận:</span>
                        <strong class="text-slate-900 font-semibold">{{ $order->customer_name }}</strong>
                    </div>

                    <div>
                        <span class="text-slate-400 text-xs block">Số điện thoại:</span>
                        <strong class="text-slate-900 font-semibold">{{ $order->customer_phone }}</strong>
                    </div>

                    <div>
                        <span class="text-slate-400 text-xs block">Địa chỉ giao:</span>
                        <p class="text-slate-800 leading-relaxed">{{ $order->customer_address }}</p>
                    </div>

                    <div>
                        <span class="text-slate-400 text-xs block">Vận chuyển Shopee:</span>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="bg-orange-100 text-orange-700 text-[11px] font-bold px-2 py-0.5 rounded-md uppercase">
                                {{ $order->shipping_carrier ?? "SPX Express" }}
                            </span>
                            <span class="text-xs text-slate-600 font-medium">({{ $order->shipping_method_name }})</span>
                        </div>
                    </div>

                    <div>
                        <span class="text-slate-400 text-xs block">Mã vận đơn:</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="font-mono text-xs font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                {{ $order->formatted_tracking_code }}
                            </span>
                            <button type="button" onclick="copyText('{{ $order->formatted_tracking_code }}', 'Mã vận đơn')" class="text-xs text-orange-600 hover:text-orange-700 font-semibold underline">Sao chép</button>
                        </div>
                    </div>

                    <div>
                        <span class="text-slate-400 text-xs block">Phí vận chuyển:</span>
                        <span class="font-bold text-xs {{ $order->shipping_fee > 0 ? "text-slate-900" : "text-emerald-600" }}">
                            {{ $order->shipping_fee > 0 ? number_format($order->shipping_fee) . "đ" : "MIỄN PHÍ" }}
                        </span>
                    </div>

                    <div>
                        <span class="text-slate-400 text-xs block">Phương thức thanh toán:</span>
                        <span class="font-semibold text-slate-900">
                            @if($order->payment_method === 'BANK')
                                Chuyển khoản VietQR
                            @elseif($order->payment_method === 'VNPAY')
                                Cổng thanh toán VNPAY
                            @elseif($order->payment_method === 'EWALLET')
                                Ví điện tử MoMo
                            @else
                                Tiền mặt (COD)
                            @endif
                        </span>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex justify-between items-baseline">
                        <span class="font-bold text-slate-900">Tổng thanh toán:</span>
                        <span class="text-xl font-black text-red-600">{{ number_format($order->total) }}đ</span>
                    </div>
                </div>

                <!-- Action Buttons (Screen only) -->
                <div class="print:hidden pt-4 space-y-2.5">
                    <a href="{{ route('orders.invoice.print', ['order' => $order->id, 'autoprint' => 1]) }}" target="_blank" class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs sm:text-sm transition flex items-center justify-center gap-2 shadow-sm">
                        <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        In Hóa Đơn Chuẩn (Khổ A4)
                    </a>

                    <a href="{{ route('customer.orders.index') }}" class="w-full py-2.5 bg-orange-50 hover:bg-orange-100 text-orange-700 font-bold rounded-xl text-xs transition flex items-center justify-center gap-1.5 border border-orange-200">
                        📦 Quản Lý & Theo Dõi Tất Cả Đơn Hàng
                    </a>

                    <a href="{{ route('home') }}" class="block w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs text-center transition">
                        Về Trang Chủ Tiếp Tục Mua Sắm
                    </a>
                </div>
            </div>

            <!-- Hotline hỗ trợ -->
            <div class="bg-indigo-50/50 rounded-2xl border border-indigo-100 p-4 text-xs text-slate-600 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                <div>
                    <p class="font-bold text-slate-900">Cần hỗ trợ đơn hàng?</p>
                    <p class="text-slate-500">Hotline: <strong class="text-indigo-600 font-mono">0383.390.611</strong> (8:00 - 22:00)</p>
                </div>
            </div>

        </div>

    </div>

</div>

<script>
function copyText(text, label) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('Đã sao chép ' + label + ': ' + text, 'success');
    }).catch(() => {
        showToast('Sao chép thất bại', 'error');
    });
}

function confirmTransferPaid() {
    const btn = document.getElementById('btn-paid-confirm');
    btn.innerHTML = '✓ Đã ghi nhận chuyển khoản!';
    btn.classList.remove('bg-emerald-600', 'hover:bg-emerald-700');
    btn.classList.add('bg-slate-800', 'cursor-default');
    btn.disabled = true;
    showToast('Cảm ơn bạn! Đơn hàng sẽ được nhân viên đối soát và chuẩn bị ngay.', 'success');
}
</script>
@endsection
