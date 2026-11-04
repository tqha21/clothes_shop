@extends('layouts.storefront')

@section('title', 'Đơn Hàng Của Tôi - HTD Store')

@section('content')
<div class="bg-slate-50 min-h-screen py-8">
    <div class="max-w-6xl mx-auto px-4">

        <!-- Breadcrumb -->
        <nav class="text-xs text-slate-500 mb-6 flex items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-red-600 transition">Trang chủ</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">Tài khoản</span>
            <span>/</span>
            <span class="text-slate-900 font-semibold">Đơn hàng của tôi</span>
        </nav>

        <!-- User Profile Quick Bar -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                @if(auth()->user()->avatar)
                    <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-14 h-14 rounded-2xl object-cover border-2 border-red-500 shadow-sm flex-shrink-0">
                @else
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-red-600 to-indigo-600 text-white font-black text-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="text-lg sm:text-xl font-bold text-slate-900">{{ auth()->user()->name }}</h1>
                        <span class="text-xs font-bold text-red-600 bg-red-50 px-2.5 py-0.5 rounded-full border border-red-200 flex items-center gap-1">
                            <span>⭐</span> {{ auth()->user()->membership_tier_label }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1 flex items-center gap-2">
                        <span>{{ auth()->user()->email }}</span>
                        <span>•</span>
                        <span>{{ number_format(auth()->user()->points) }} Điểm tích lũy</span>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto">
                <a href="{{ route('voucher.index') }}" class="flex-1 md:flex-none px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl text-center transition">
                    🎟️ Ví Voucher
                </a>
                <a href="{{ route('cart.index') }}" class="flex-1 md:flex-none px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl text-center transition">
                    🛒 Giỏ hàng
                </a>
            </div>
        </div>

        <!-- Filter Status Tabs (Phong cách Shopee) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-1.5 mb-6 overflow-x-auto">
            <div class="flex items-center gap-1 min-w-[500px]">
                <a href="{{ route('customer.orders.index', ['status' => 'all']) }}" 
                   class="flex-1 py-2.5 px-3 text-xs sm:text-sm font-semibold rounded-xl text-center transition-all flex items-center justify-center gap-1.5 {{ $status === 'all' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                    <span>Tất cả</span>
                    <span class="text-[11px] px-1.5 py-0.2 rounded-full {{ $status === 'all' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">{{ $counts['all'] }}</span>
                </a>

                <a href="{{ route('customer.orders.index', ['status' => 'pending']) }}" 
                   class="flex-1 py-2.5 px-3 text-xs sm:text-sm font-semibold rounded-xl text-center transition-all flex items-center justify-center gap-1.5 {{ $status === 'pending' ? 'bg-orange-500 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                    <span>Chờ xử lý</span>
                    <span class="text-[11px] px-1.5 py-0.2 rounded-full {{ $status === 'pending' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">{{ $counts['pending'] }}</span>
                </a>

                <a href="{{ route('customer.orders.index', ['status' => 'shipping']) }}" 
                   class="flex-1 py-2.5 px-3 text-xs sm:text-sm font-semibold rounded-xl text-center transition-all flex items-center justify-center gap-1.5 {{ $status === 'shipping' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                    <span>Đang vận chuyển</span>
                    <span class="text-[11px] px-1.5 py-0.2 rounded-full {{ $status === 'shipping' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">{{ $counts['shipping'] }}</span>
                </a>

                <a href="{{ route('customer.orders.index', ['status' => 'completed']) }}" 
                   class="flex-1 py-2.5 px-3 text-xs sm:text-sm font-semibold rounded-xl text-center transition-all flex items-center justify-center gap-1.5 {{ $status === 'completed' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                    <span>Đã giao thành công</span>
                    <span class="text-[11px] px-1.5 py-0.2 rounded-full {{ $status === 'completed' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">{{ $counts['completed'] }}</span>
                </a>

                <a href="{{ route('customer.orders.index', ['status' => 'cancelled']) }}" 
                   class="flex-1 py-2.5 px-3 text-xs sm:text-sm font-semibold rounded-xl text-center transition-all flex items-center justify-center gap-1.5 {{ $status === 'cancelled' ? 'bg-red-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                    <span>Đã hủy</span>
                    <span class="text-[11px] px-1.5 py-0.2 rounded-full {{ $status === 'cancelled' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">{{ $counts['cancelled'] }}</span>
                </a>
            </div>
        </div>

        <!-- Orders List -->
        @if($orders->count() > 0)
        <div class="space-y-4">
            @foreach($orders as $order)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden hover:border-slate-300 transition-all">
                
                <!-- Order Card Header -->
                <div class="p-4 sm:p-5 bg-slate-50/60 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-lg flex-shrink-0 font-bold">
                            🚚
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-bold text-slate-900 text-sm">#HTD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                                <span class="text-xs text-slate-400">•</span>
                                <span class="text-xs text-slate-500">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                <span class="bg-orange-100 text-orange-700 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">
                                    {{ $order->shipping_carrier ?? 'SPX Express' }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Mã vận đơn: <strong class="font-mono text-slate-700">{{ $order->formatted_tracking_code }}</strong>
                                @if($order->estimated_delivery)
                                <span class="hidden sm:inline text-slate-400"> | Dự kiến: <strong class="text-slate-700">{{ $order->estimated_delivery }}</strong></span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div class="flex items-center gap-2">
                        @if($order->status === 'pending')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Chờ đóng gói
                            </span>
                        @elseif($order->status === 'shipping')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-ping"></span>
                                Đang vận chuyển
                            </span>
                        @elseif($order->status === 'completed')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                Giao thành công
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                Đã hủy
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Order Items (Product Rows) -->
                <div class="p-4 sm:p-5 divide-y divide-slate-100">
                    @foreach($order->items as $item)
                    <div class="flex items-center gap-4 py-3 first:pt-0 last:pb-0">
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
                                <span>x{{ $item->quantity }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-slate-900">{{ number_format($item->price * $item->quantity) }}đ</span>
                            <span class="text-[11px] text-slate-400 block">{{ number_format($item->price) }}đ / món</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Order Card Footer & Actions -->
                <div class="p-4 sm:p-5 bg-slate-50/40 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="text-xs text-slate-600">
                        <span>Tổng tiền đơn hàng: </span>
                        <strong class="text-base sm:text-lg font-black text-red-600 font-mono">{{ number_format($order->total) }}đ</strong>
                        <span class="text-slate-400 ml-1">({{ $order->items->sum('quantity') }} sản phẩm)</span>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap">
                        <!-- Nút in hóa đơn -->
                        <a href="{{ route('orders.invoice.print', ['order' => $order->id, 'autoprint' => 1]) }}" target="_blank" class="px-3.5 py-2 bg-white hover:bg-slate-100 text-slate-700 text-xs font-semibold rounded-xl border border-slate-200 shadow-2xs transition flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            In Hóa Đơn
                        </a>

                        <!-- Nút theo dõi lộ trình vận chuyển -->
                        <a href="{{ route('customer.orders.show', $order->id) }}" class="px-4 py-2 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7h-3v7h.05a2.5 2.5 0 014.9 0H17a1 1 0 001-1v-4.382a1 1 0 00-.293-.707l-2.618-2.618A1 1 0 0014.382 7H14z"/></svg>
                            Theo Dõi Vận Chuyển
                        </a>
                    </div>
                </div>

            </div>
            @endforeach

            <!-- Pagination -->
            <div class="pt-4">
                {{ $orders->links() }}
            </div>
        </div>
        @else
        <!-- Empty State -->
        <div class="bg-white rounded-3xl p-12 text-center shadow-sm border border-slate-200/80">
            <div class="w-20 h-20 bg-slate-100 rounded-3xl flex items-center justify-center mx-auto mb-4 text-3xl">
                📦
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-1">Chưa có đơn hàng nào</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto mb-6">Bạn chưa có đơn hàng nào trong mục này. Hãy khám phá kho hàng thời trang chất lượng của HTD Store ngay nhé!</p>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-600 to-indigo-600 text-white font-bold text-xs sm:text-sm rounded-xl shadow-md hover:opacity-95 transition">
                <span>Khám Phá Sản Phẩm Ngay</span>
                <span>→</span>
            </a>
        </div>
        @endif

    </div>
</div>
@endsection

