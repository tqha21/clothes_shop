@extends('layouts.storefront')

@section('title', 'Ví Voucher của tôi')

@section('content')
<div class="bg-gray-50 py-4">
    <div class="container mx-auto px-4">
        <nav class="text-sm">
            <ol class="flex items-center gap-2">
                <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-red-600">Trang chủ</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 font-medium">Ví Voucher</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container mx-auto px-4 py-8">

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-300 text-green-800 px-5 py-4 rounded-lg flex items-center gap-3">
        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-300 text-red-800 px-5 py-4 rounded-lg flex items-center gap-3">
        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    <div class="flex items-center gap-3 mb-8">
        <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Ví Voucher của tôi</h1>
            <p class="text-sm text-gray-500">Lưu và quản lý các mã giảm giá</p>
        </div>
    </div>

    {{-- ===== VOUCHER ĐÃ LƯU ===== --}}
    <section class="mb-10">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <span class="inline-block w-1 h-5 bg-red-600 rounded"></span>
            Voucher đã lưu
            <span class="bg-red-100 text-red-700 text-xs font-bold px-2 py-0.5 rounded-full ml-1">{{ $savedCoupons->count() }}</span>
        </h2>

        @if($savedCoupons->isEmpty())
        <div class="bg-white rounded-xl border border-dashed border-gray-300 p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
            </svg>
            <p class="text-gray-500 font-medium">Bạn chưa lưu voucher nào</p>
            <p class="text-gray-400 text-sm mt-1">Hãy lưu voucher bên dưới để dùng khi thanh toán!</p>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($savedCoupons as $saved)
            @php $coupon = $saved->coupon; @endphp
            <div class="relative bg-white rounded-xl border-2 {{ $saved->is_used ? 'border-gray-200 opacity-60' : 'border-red-200' }} overflow-hidden shadow-sm">
                {{-- Decorative perforated edge --}}
                <div class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-1/2 w-6 h-6 bg-gray-50 rounded-full border border-gray-200"></div>
                <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/2 w-6 h-6 bg-gray-50 rounded-full border border-gray-200"></div>

                <div class="flex">
                    {{-- Left: discount value --}}
                    <div class="w-24 bg-gradient-to-br {{ $saved->is_used ? 'from-gray-400 to-gray-500' : 'from-red-500 to-red-600' }} flex flex-col items-center justify-center p-3 text-white flex-shrink-0">
                        @if($coupon->type === 'percentage')
                        <span class="text-3xl font-black">{{ $coupon->value }}%</span>
                        <span class="text-xs opacity-80">GIẢM GIÁ</span>
                        @else
                        <span class="text-lg font-black leading-tight">{{ number_format($coupon->value) }}</span>
                        <span class="text-xs opacity-80">đ GIẢM</span>
                        @endif
                    </div>

                    {{-- Right: details --}}
                    <div class="flex-1 p-4 border-l-2 border-dashed border-gray-200">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-bold text-gray-900 font-mono tracking-wide text-sm">{{ $coupon->code }}</span>
                                    @if($saved->is_used)
                                    <span class="bg-gray-200 text-gray-500 text-xs px-2 py-0.5 rounded-full font-medium">Đã dùng</span>
                                    @endif
                                </div>
                                @if($coupon->min_order_amount > 0)
                                <p class="text-xs text-gray-500">Đơn tối thiểu {{ number_format($coupon->min_order_amount) }}đ</p>
                                @endif
                                @if($coupon->expires_at)
                                <p class="text-xs text-gray-500">HSD: {{ $coupon->expires_at->format('d/m/Y') }}</p>
                                @else
                                <p class="text-xs text-green-600 font-medium">Không giới hạn thời gian</p>
                                @endif
                            </div>
                        </div>

                        <div class="mt-3 flex items-center gap-2">
                            @if(!$saved->is_used)
                            <a href="{{ route('checkout.index') }}?use_coupon={{ $coupon->code }}"
                               class="flex-1 text-center bg-red-600 text-white text-xs font-bold py-1.5 px-3 rounded-lg hover:bg-red-700 transition-colors">
                                Dùng ngay
                            </a>
                            @endif
                            <form action="{{ route('voucher.remove') }}" method="POST">
                                @csrf
                                <input type="hidden" name="coupon_id" value="{{ $coupon->id }}">
                                <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors text-xs underline">
                                    Xóa
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </section>

    {{-- ===== VOUCHER CÓ SẴN ===== --}}
    <section>
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <span class="inline-block w-1 h-5 bg-red-600 rounded"></span>
            Voucher có sẵn cho bạn
        </h2>

        @if($allCoupons->isEmpty())
        <div class="bg-white rounded-xl border border-dashed border-gray-300 p-12 text-center">
            <p class="text-gray-500">Hiện chưa có voucher nào khả dụng. Hãy quay lại sau!</p>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($allCoupons as $coupon)
            @php $alreadySaved = in_array($coupon->id, $savedIds); @endphp
            <div class="relative bg-white rounded-xl border-2 {{ $alreadySaved ? 'border-green-200' : 'border-gray-200 hover:border-red-300' }} overflow-hidden shadow-sm transition-all">
                <div class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-1/2 w-6 h-6 bg-gray-50 rounded-full border border-gray-200"></div>
                <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/2 w-6 h-6 bg-gray-50 rounded-full border border-gray-200"></div>

                <div class="flex">
                    {{-- Left --}}
                    <div class="w-24 bg-gradient-to-br from-red-500 to-red-600 flex flex-col items-center justify-center p-3 text-white flex-shrink-0">
                        @if($coupon->type === 'percentage')
                        <span class="text-3xl font-black">{{ $coupon->value }}%</span>
                        <span class="text-xs opacity-80">GIẢM GIÁ</span>
                        @else
                        <span class="text-lg font-black leading-tight">{{ number_format($coupon->value) }}</span>
                        <span class="text-xs opacity-80">đ GIẢM</span>
                        @endif
                    </div>

                    {{-- Right --}}
                    <div class="flex-1 p-4 border-l-2 border-dashed border-gray-200">
                        <span class="font-bold text-gray-900 font-mono tracking-wide text-sm">{{ $coupon->code }}</span>
                        @if($coupon->min_order_amount > 0)
                        <p class="text-xs text-gray-500 mt-0.5">Đơn tối thiểu {{ number_format($coupon->min_order_amount) }}đ</p>
                        @endif
                        @if($coupon->expires_at)
                        <p class="text-xs text-gray-500">HSD: {{ $coupon->expires_at->format('d/m/Y') }}</p>
                        @else
                        <p class="text-xs text-green-600 font-medium">Không giới hạn</p>
                        @endif
                        @if($coupon->usage_limit)
                        <p class="text-xs text-gray-400">Còn {{ $coupon->usage_limit - $coupon->used_count }} lượt</p>
                        @endif

                        <div class="mt-3">
                            @if($alreadySaved)
                            <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-bold px-3 py-1.5 rounded-lg">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Đã lưu
                            </span>
                            @else
                            <form action="{{ route('voucher.save') }}" method="POST">
                                @csrf
                                <input type="hidden" name="coupon_id" value="{{ $coupon->id }}">
                                <button type="submit"
                                    class="w-full bg-red-600 text-white text-xs font-bold py-1.5 px-3 rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Lưu vào ví
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </section>
</div>
@endsection
