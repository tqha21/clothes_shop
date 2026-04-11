@extends('admin.layouts.app')

@section('title', 'Khách Hàng')
@section('page-title', 'Quản Lý Khách Hàng')
@section('page-description', 'Xem danh sách và thông tin liên hệ của khách hàng')

@section('header-actions')
<a href="{{ route('admin.customers.create') }}" class="inline-flex items-center justify-center gap-2 bg-red-600 text-white px-6 py-3 rounded-xl font-bold text-sm uppercase tracking-wide hover:bg-red-700 transition-all shadow-sm active:scale-95">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
    Thêm Khách Hàng
</a>
@endsection

@section('content')
<!-- Search Bar -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 overflow-hidden relative group">
    <div class="absolute top-0 right-0 w-64 h-64 bg-red-50 rounded-full -mr-32 -mt-32 blur-3xl transition-all group-hover:bg-red-100"></div>
    
    <form method="GET" action="{{ route('admin.customers.index') }}" class="relative z-10 flex flex-col md:flex-row gap-4">
        <div class="flex-1 w-full relative">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Tìm kiếm theo tên, email hoặc số điện thoại..." 
                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-5 py-3.5 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all outline-none">
            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>
        
        <div class="flex gap-3 w-full md:w-auto">
            <button type="submit" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-red-600 text-white px-8 py-3.5 rounded-xl font-bold text-sm uppercase tracking-wide hover:bg-red-700 transition-all shadow-sm active:scale-95">
                Tìm Kiếm
            </button>
            
            @if(request('search'))
            <a href="{{ route('admin.customers.index') }}" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-white text-gray-700 px-8 py-3.5 rounded-xl font-bold text-sm uppercase tracking-wide hover:bg-gray-50 transition-all border border-gray-200 active:scale-95">
                Bỏ Lọc
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Customer list -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Khách Hàng</th>
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Thông Tin Liên Hệ</th>
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Đơn Hàng</th>
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tổng Chi Tiêu</th>
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Ngày Đăng Ký</th>
                    <th class="px-6 py-4 text-center text-[11px] font-bold text-gray-500 uppercase tracking-wider">Thao Tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($customers as $customer)
                <tr class="hover:bg-gray-50/50 transition-colors group">
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-red-600 text-sm font-bold border border-red-100 group-hover:bg-red-100 transition-colors">
                                {{ substr($customer->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">{{ $customer->name }}</p>
                                <p class="text-[11px] font-semibold text-gray-400 uppercase">Mã: #{{ $customer->id }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[13px] font-semibold text-gray-700">{{ $customer->email }}</span>
                            <span class="text-[11px] font-medium text-gray-500">{{ $customer->phone ?? 'Chưa cập nhật SĐT' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-semibold bg-blue-50 text-blue-600 border border-blue-100">
                             {{ $customer->orders_count }} Đơn Hàng
                        </span>
                    </td>
                    <td class="px-6 py-5">
                        <span class="text-[13px] font-bold text-gray-800">{{ number_format($customer->orders_sum_total ?? 0) }}đ</span>
                    </td>
                    <td class="px-6 py-5">
                        <span class="text-[12px] font-medium text-gray-600">{{ $customer->created_at->format('d/m/Y') }}</span>
                    </td>
                    <td class="px-6 py-5 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.customers.show', $customer) }}" class="p-2.5 bg-gray-50 text-gray-600 rounded-xl hover:bg-blue-50 shadow-sm hover:text-blue-600 transition-all border border-gray-100 hover:border-blue-100 active:scale-95" title="Xem chi tiết">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            <a href="{{ route('admin.customers.edit', $customer) }}" class="p-2.5 bg-gray-50 text-gray-600 rounded-xl hover:bg-yellow-50 shadow-sm hover:text-yellow-600 transition-all border border-gray-100 hover:border-yellow-100 active:scale-95" title="Sửa thông tin">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa khách hàng này? Đây là hành động không thể hoàn tác.')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2.5 bg-gray-50 text-gray-600 rounded-xl hover:bg-red-50 shadow-sm hover:text-red-600 transition-all border border-gray-100 hover:border-red-100 active:scale-95" title="Xóa khách hàng">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500 font-medium text-[13px]">Không tìm thấy khách hàng nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($customers->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
        <div class="text-sm text-gray-500">
            Hiển thị từ <span class="font-semibold text-gray-900">{{ $customers->firstItem() }}</span> đến <span class="font-semibold text-gray-900">{{ $customers->lastItem() }}</span> trong tổng số <span class="font-semibold text-gray-900">{{ $customers->total() }}</span> kết quả
        </div>
        <div class="flex gap-2">
            @if ($customers->onFirstPage())
                <span class="px-3 py-1.5 rounded-lg border border-gray-200 text-sm font-medium text-gray-400 bg-gray-50 cursor-not-allowed">Trang Trước</span>
            @else
                <a href="{{ $customers->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-gray-200 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:text-gray-900 transition-colors">Trang Trước</a>
            @endif

            @if ($customers->hasMorePages())
                <a href="{{ $customers->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-gray-200 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:text-gray-900 transition-colors">Trang Sau</a>
            @else
                <span class="px-3 py-1.5 rounded-lg border border-gray-200 text-sm font-medium text-gray-400 bg-gray-50 cursor-not-allowed">Trang Sau</span>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
