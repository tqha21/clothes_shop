@extends('admin.layouts.app')

@section('title', 'Đơn Hàng')
@section('page-title', 'Quản Lý Đơn Hàng')
@section('page-description', 'Theo dõi và xử lý giao dịch')

@section('content')
<!-- Filter Bar -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 overflow-hidden relative group">
    <div class="absolute top-0 right-0 w-64 h-64 bg-red-500/5 rounded-full -mr-32 -mt-32 blur-3xl transition-all group-hover:bg-red-500/10"></div>
    
    <form method="GET" class="relative z-10 flex flex-col md:flex-row gap-4 items-end">
        <div class="flex-1 w-full">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-2">Trạng Thái Đơn Hàng</label>
            <div class="relative">
                <select name="status" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-900 focus:ring-2 focus:ring-red-500 focus:bg-white focus:border-red-500 transition-all appearance-none cursor-pointer outline-none">
                    <option value="">Tất Cả Đơn Hàng</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ Duyệt</option>
                    <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Đang Giao</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Đã Hoàn Thành</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã Hủy</option>
                </select>
                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </div>
        
        <div class="flex gap-3 w-full md:w-auto">
            <button type="submit" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-gray-800 text-white px-6 py-3 rounded-xl font-semibold text-xs uppercase tracking-wider hover:bg-gray-900 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Lọc Đơn Hàng
            </button>
            
            @if(request('status'))
            <a href="{{ route('admin.orders.index') }}" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-red-50 text-red-600 px-6 py-3 rounded-xl font-semibold text-xs uppercase tracking-wider hover:bg-red-100 transition-all border border-red-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Hủy Lọc
            </a>
            @endif
            
            <a href="{{ route('admin.orders.export', request()->all()) }}" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-green-50 text-green-600 px-6 py-3 rounded-xl font-semibold text-xs uppercase tracking-wider hover:bg-green-100 transition-all border border-green-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Xuất Excel
            </a>
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Mã ĐH</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Khách Hàng</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Liên Hệ</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Tổng Tiền</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Trạng Thái</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Thời Gian</th>
                    <th class="px-6 py-4 text-center text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Thao Tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                    <td class="px-6 py-4">
                        <span class="font-bold text-gray-800 group-hover:text-red-500 transition-colors">#{{ $order->id }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-700 text-sm font-bold border border-gray-200">
                                {{ substr($order->customer_name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">{{ $order->customer_name }}</p>
                                <p class="text-[11px] text-gray-500">{{ $order->customer_email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-1.5 text-gray-700">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span class="text-xs font-semibold">{{ $order->customer_phone }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-gray-900">{{ number_format($order->total) }}đ</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($order->status == 'pending')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-semibold tracking-wide bg-yellow-50 text-yellow-600 border border-yellow-100">
                                <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5"></span>
                                Chờ Duyệt
                            </span>
                        @elseif($order->status == 'shipping')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-semibold tracking-wide bg-blue-50 text-blue-600 border border-blue-100">
                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-1.5"></span>
                                Đang Giao
                            </span>
                        @elseif($order->status == 'completed')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-semibold tracking-wide bg-green-50 text-green-600 border border-green-100">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                Hoàn Thành
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-semibold tracking-wide bg-red-50 text-red-600 border border-red-100">
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                                Đã Hủy
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold text-gray-800">{{ $order->created_at->format('d/m/Y') }}</span>
                            <span class="text-[11px] text-gray-500">{{ $order->created_at->format('H:i') }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 text-gray-700 text-[11px] font-semibold uppercase tracking-wider rounded-lg hover:bg-gray-100 transition-colors border border-gray-200">
                             <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Chi Tiết
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-12 text-center text-gray-500 font-medium text-sm">Chưa có đơn hàng nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($orders->hasPages())
<div class="mt-8 flex justify-center">
    {{ $orders->links() }}
</div>
@endif

<style>
    .pagination { @apply flex gap-2; }
    .page-item { @apply rounded-lg border border-gray-100 bg-white shadow-sm transition-all; }
    .page-link { @apply flex items-center justify-center w-9 h-9 text-xs font-semibold text-gray-700; }
    .page-item.active { @apply bg-red-600 border-red-600 shadow-md; }
    .page-item.active .page-link { @apply text-white; }
    .page-item:hover:not(.active) { @apply bg-gray-50 border-gray-200 transform -translate-y-0.5; }
</style>
@endsection

