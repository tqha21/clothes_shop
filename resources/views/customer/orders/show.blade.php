@extends('layouts.storefront')

@section('title', 'Theo Dõi Đơn Hàng #HTD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT))

@section('content')
<div class="bg-slate-50 min-h-screen py-8">
    <div class="max-w-5xl mx-auto px-4">

        <!-- Breadcrumb & Top Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <nav class="text-xs text-slate-500 flex items-center gap-2">
                <a href="{{ route('home') }}" class="hover:text-red-600 transition">Trang chủ</a>
                <span>/</span>
                <a href="{{ route('customer.orders.index') }}" class="hover:text-red-600 transition">Đơn hàng của tôi</a>
                <span>/</span>
                <span class="text-slate-900 font-semibold">#HTD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
            </nav>

            <div class="flex items-center gap-2">
                <a href="{{ route('customer.orders.index') }}" class="px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 transition flex items-center gap-1">
                    ← Danh sách đơn hàng
                </a>
                <a href="{{ route('orders.invoice.print', ['order' => $order->id, 'autoprint' => 1]) }}" target="_blank" class="px-4 py-1.5 bg-gradient-to-r from-red-600 to-indigo-600 text-white rounded-xl text-xs font-bold shadow-xs hover:opacity-95 transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    In Hóa Đơn
                </a>
            </div>
        </div>

        <!-- THÔNG TIN VẬN CHUYỂN & LỘ TRÌNH CHUẨN SHOPEE EXPRESS -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <!-- Header Vận chuyển phong cách Shopee -->
            <div class="bg-gradient-to-r from-orange-500 via-orange-600 to-amber-500 p-5 sm:p-7 text-white">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-2xl shadow-inner border border-white/30 flex-shrink-0">
                            🚚
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h2 class="text-base sm:text-xl font-bold text-white tracking-wide">
                                    {{ $order->shipping_carrier ?? 'SPX Express (Shopee Xpress)' }}
                                </h2>
                                <span class="bg-white text-orange-600 text-[10px] font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                                    {{ $order->shipping_method_name }}
                                </span>
                            </div>
                            <p class="text-xs text-orange-100 mt-1 flex items-center gap-2">
                                <span>Mã vận đơn:</span>
                                <strong class="font-mono text-sm tracking-wider text-white bg-black/25 px-2 py-0.5 rounded-lg border border-white/20">
                                    {{ $order->formatted_tracking_code }}
                                </strong>
                                <button type="button" onclick="navigator.clipboard.writeText('{{ $order->formatted_tracking_code }}').then(() => alert('Đã sao chép mã vận đơn!'))" class="text-[11px] underline text-orange-100 hover:text-white transition">
                                    Sao chép
                                </button>
                            </p>
                        </div>
                    </div>

                    <div class="sm:text-right bg-white/10 sm:bg-transparent p-3 sm:p-0 rounded-2xl backdrop-blur-xs sm:backdrop-blur-none border border-white/15 sm:border-0">
                        <span class="text-xs text-orange-100 block">Thời gian giao dự kiến:</span>
                        <strong class="text-sm sm:text-lg font-bold text-white block">
                            {{ $order->estimated_delivery ?? 'Nhận hàng vào 2-3 ngày tới' }}
                        </strong>
                    </div>
                </div>

                <!-- Badge quyền lợi Đồng kiểm -->
                <div class="mt-4 pt-3 border-t border-white/20 flex items-center justify-between text-xs text-orange-100 flex-wrap gap-2">
                    <div class="flex items-center gap-1.5">
                        <span class="w-4 h-4 rounded-full bg-emerald-400 text-emerald-950 flex items-center justify-center text-[10px] font-black">✓</span>
                        <span><strong>Được đồng kiểm:</strong> Kiểm tra ngoại quan sản phẩm cùng bưu tá trước khi nhận hàng</span>
                    </div>
                    <span class="bg-black/20 text-white text-[11px] px-2.5 py-1 rounded-lg border border-white/20">
                        Đổi trả 7 ngày miễn phí
                    </span>
                </div>
            </div>

            <!-- Lộ trình vận chuyển chi tiết theo trạm (Shopee Tracking Timeline) -->
            <div class="p-6 sm:p-8">
                <h3 class="text-sm font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Lộ Trình Vận Chuyển Chi Tiết
                </h3>

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

        <!-- Chi tiết sản phẩm & Thông tin nhận hàng (2 Cột) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Cột trái: Sản phẩm (8 cols) -->
            <div class="lg:col-span-8 bg-white rounded-3xl p-6 shadow-sm border border-slate-200">
                <h3 class="text-sm font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center justify-between">
                    <span>📦 Sản Phẩm Trong Đơn Hàng ({{ $order->items->count() }})</span>
                    <span class="text-xs text-slate-400">Tổng SL: {{ $order->items->sum('quantity') }}</span>
                </h3>

                <div class="divide-y divide-slate-100">
                    @foreach($order->items as $item)
                    <div class="flex items-center gap-4 py-4 first:pt-3 last:pb-0">
                        <div class="w-16 h-16 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200">
                            @if($item->product && $item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-xs text-slate-400">Ảnh</div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-slate-900 line-clamp-1">{{ $item->product->name ?? 'Sản phẩm' }}</h4>
                            <div class="flex items-center gap-2 text-xs text-slate-500 mt-1">
                                @if($item->size)<span class="bg-slate-100 px-2 py-0.5 rounded text-[11px] font-medium">Size: {{ $item->size }}</span>@endif
                                @if($item->color)<span class="bg-slate-100 px-2 py-0.5 rounded text-[11px] font-medium">Màu: {{ $item->color }}</span>@endif
                                <span>SL: x{{ $item->quantity }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-slate-900">{{ number_format($item->price * $item->quantity) }}đ</span>
                            <span class="text-[11px] text-slate-400 block">{{ number_format($item->price) }}đ / món</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Cột phải: Thông tin giao nhận & Tổng tiền (4 cols) -->
            <div class="lg:col-span-4 bg-white rounded-3xl p-6 shadow-sm border border-slate-200 space-y-4">
                <h3 class="text-sm font-bold text-slate-900 pb-3 border-b border-slate-100">
                    Thông Tin Nhận Hàng
                </h3>

                <div class="space-y-3 text-xs sm:text-[13px] text-slate-700">
                    <div>
                        <span class="text-slate-400 text-xs block">Người nhận:</span>
                        <strong class="text-slate-900 font-semibold">{{ $order->customer_name }}</strong>
                    </div>

                    <div>
                        <span class="text-slate-400 text-xs block">Số điện thoại:</span>
                        <strong class="text-slate-900 font-mono">{{ $order->customer_phone }}</strong>
                    </div>

                    <div>
                        <span class="text-slate-400 text-xs block">Địa chỉ nhận hàng:</span>
                        <p class="text-slate-800 leading-relaxed">{{ $order->customer_address }}</p>
                    </div>

                    <div>
                        <span class="text-slate-400 text-xs block">Hình thức vận chuyển:</span>
                        <span class="bg-orange-100 text-orange-700 text-[11px] font-bold px-2 py-0.5 rounded-md uppercase">
                            {{ $order->shipping_carrier ?? 'SPX Express' }}
                        </span>
                        <span class="text-xs text-slate-500 ml-1">({{ $order->shipping_method_name }})</span>
                    </div>

                    <div>
                        <span class="text-slate-400 text-xs block">Phí vận chuyển:</span>
                        <strong class="{{ $order->shipping_fee > 0 ? 'text-slate-900' : 'text-emerald-600' }}">
                            {{ $order->shipping_fee > 0 ? number_format($order->shipping_fee) . 'đ' : 'MIỄN PHÍ' }}
                        </strong>
                    </div>

                    <div>
                        <span class="text-slate-400 text-xs block">Phương thức thanh toán:</span>
                        <strong class="text-slate-900 uppercase">
                            @if($order->payment_method === 'BANK')
                                Chuyển khoản VietQR
                            @elseif($order->payment_method === 'VNPAY')
                                Cổng VNPAY
                            @elseif($order->payment_method === 'EWALLET')
                                Ví MoMo
                            @else
                                Tiền mặt (COD)
                            @endif
                        </strong>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex justify-between items-baseline">
                        <span class="font-bold text-slate-900">Tổng thanh toán:</span>
                        <span class="text-xl font-black text-red-600 font-mono">{{ number_format($order->total) }}đ</span>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="{{ route('orders.invoice.print', ['order' => $order->id, 'autoprint' => 1]) }}" target="_blank" class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        In Hóa Đơn Bán Hàng
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection

