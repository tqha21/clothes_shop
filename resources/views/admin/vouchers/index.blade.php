@extends('admin.layouts.app')

@section('title', 'Quản Lý Voucher')
@section('page-title', 'Quản Lý Voucher')
@section('page-description', 'Tạo, chỉnh sửa và quản lý mã giảm giá')

@section('content')
{{-- Header Actions --}}
<div class="mb-6 flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
    <form action="{{ route('admin.vouchers.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
        <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm mã voucher..."
                class="w-56 bg-white border border-gray-200 rounded-xl pl-9 pr-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <select name="status" class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-red-500 outline-none">
            <option value="">Tất cả trạng thái</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
        </select>
        <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-4 rounded-xl text-sm transition-colors">Lọc</button>
        @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('admin.vouchers.index') }}" class="text-xs text-red-600 font-medium hover:underline">Xóa lọc</a>
        @endif
    </form>

    @if(auth()->user()->isAdmin())
    <a href="{{ route('admin.vouchers.create') }}"
        class="group flex items-center gap-2 bg-gray-800 text-white px-5 py-2.5 rounded-xl font-semibold text-xs uppercase tracking-wider hover:bg-gray-900 transition-all shadow-sm flex-shrink-0">
        <div class="w-5 h-5 bg-white/10 rounded-md flex items-center justify-center group-hover:bg-white/20">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        </div>
        Tạo Voucher Mới
    </a>
    @endif
</div>

{{-- Stats Cards --}}
@php
    $totalActive = \App\Models\Coupon::where('is_active', true)->count();
    $totalExpired = \App\Models\Coupon::where('expires_at', '<', now())->count();
    $totalAll = \App\Models\Coupon::count();
@endphp
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $totalActive }}</p>
            <p class="text-xs text-gray-500 font-medium mt-0.5">Đang Hoạt Động</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $totalExpired }}</p>
            <p class="text-xs text-gray-500 font-medium mt-0.5">Đã Hết Hạn</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $totalAll }}</p>
            <p class="text-xs text-gray-500 font-medium mt-0.5">Tổng Voucher</p>
        </div>
    </div>
</div>

{{-- Voucher Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Mã Voucher</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Loại & Giá trị</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Đơn Tối Thiểu</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Đã Dùng / Giới Hạn</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Hạn Sử Dụng</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Trạng Thái</th>
                    @if(auth()->user()->isAdmin())
                    <th class="px-6 py-4 text-center text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Thao Tác</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($coupons as $coupon)
                @php
                    $isExpired = $coupon->expires_at && $coupon->expires_at->isPast();
                    $isFull = $coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit;
                @endphp
                <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 bg-gray-900 text-white text-xs font-mono font-bold px-3 py-1.5 rounded-lg tracking-widest">
                            <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                            {{ $coupon->code }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($coupon->type === 'percentage')
                            <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-700 text-xs font-bold px-2.5 py-1 rounded-lg">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M17 17h.01M7 17L17 7"/></svg>
                                Giảm {{ $coupon->value }}%
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg">
                                Giảm {{ number_format($coupon->value) }}đ
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-600 text-sm font-medium">
                        {{ $coupon->min_order_amount > 0 ? number_format($coupon->min_order_amount) . 'đ' : 'Không có' }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold {{ $isFull ? 'text-red-600' : 'text-gray-800' }}">
                                {{ $coupon->used_count }}
                            </span>
                            <span class="text-gray-400">/</span>
                            <span class="text-sm text-gray-500">{{ $coupon->usage_limit ?? '∞' }}</span>
                        </div>
                        @if($coupon->usage_limit)
                        <div class="w-24 h-1.5 bg-gray-100 rounded-full mt-1.5 overflow-hidden">
                            @php $pct = min(100, ($coupon->used_count / $coupon->usage_limit) * 100); @endphp
                            <div class="h-full rounded-full {{ $pct >= 100 ? 'bg-red-500' : ($pct >= 70 ? 'bg-yellow-500' : 'bg-green-500') }}" style="width: {{ $pct }}%"></div>
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($coupon->expires_at)
                            <span class="{{ $isExpired ? 'text-red-500 font-semibold' : 'text-gray-600' }}">
                                {{ $coupon->expires_at->format('d/m/Y') }}
                                @if($isExpired)
                                    <span class="block text-[10px] text-red-400">(Đã hết hạn)</span>
                                @else
                                    <span class="block text-[10px] text-gray-400">{{ $coupon->expires_at->diffForHumans() }}</span>
                                @endif
                            </span>
                        @else
                            <span class="text-gray-400 italic text-xs">Không giới hạn</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($coupon->is_active && !$isExpired && !$isFull)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-green-50 text-green-700">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>Hoạt động
                            </span>
                        @elseif(!$coupon->is_active)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-gray-100 text-gray-500">
                                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>Tạm dừng
                            </span>
                        @elseif($isExpired)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-red-50 text-red-600">
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>Hết hạn
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-yellow-50 text-yellow-700">
                                Đã dùng hết
                            </span>
                        @endif
                    </td>
                    @if(auth()->user()->isAdmin())
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.vouchers.edit', $coupon) }}"
                                class="w-8 h-8 bg-gray-100 hover:bg-blue-50 hover:text-blue-600 text-gray-600 rounded-lg flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </a>
                            <form action="{{ route('admin.vouchers.destroy', $coupon) }}" method="POST" class="inline btn-delete-form" data-message="Bạn có chắc chắn muốn xóa voucher '{{ $coupon->code }}'?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 bg-red-50 hover:bg-red-100 text-red-500 rounded-lg flex items-center justify-center transition-colors border border-red-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3 text-gray-400">
                            <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            <p class="font-medium text-sm">Chưa có voucher nào.</p>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.vouchers.create') }}" class="text-red-600 text-xs font-semibold hover:underline">Tạo voucher đầu tiên</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
@if($coupons->hasPages())
<div class="mt-6 flex justify-center">
    {{ $coupons->links() }}
</div>
@endif
@endsection
