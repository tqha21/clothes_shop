@extends('admin.layouts.app')

@section('title', 'Khách Hàng')
@section('page-title', 'Sửa Thông Tin Khách Hàng')
@section('page-description', 'Cập nhật thông tin cho khách hàng: ' . $customer->name)

@section('content')
<div class="max-w-4xl space-y-8">
    <form method="POST" action="{{ route('admin.customers.update', $customer) }}" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Subscriber Identity Block -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group">
            <div class="bg-gray-50 px-8 py-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-800 flex items-center gap-3">
                        Thông Tin Cơ Bản
                    </h3>
                    <p class="text-[11px] text-gray-500 font-medium mt-1">Tên, email và số điện thoại</p>
                </div>
                <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-red-500 border border-red-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
            </div>
            
            <div class="p-8">
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide">Họ và Tên <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $customer->name) }}" required
                                   class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all outline-none"
                                   placeholder="Nhập họ và tên...">
                            @error('name')
                                <p class="text-xs font-semibold text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide">Địa Chỉ Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $customer->email) }}" required
                                   class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all outline-none"
                                   placeholder="email@vidu.com">
                            @error('email')
                                <p class="text-xs font-semibold text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide">Số Điện Thoại</label>
                        <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all outline-none"
                               placeholder="09xx xxx xxx">
                        @error('phone')
                            <p class="text-xs font-semibold text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-blue-50 p-6 rounded-xl border border-blue-100 flex items-start gap-4">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-blue-800 uppercase tracking-wide mb-1">Lưu Ý Về Bảo Mật</p>
                            <p class="text-[12px] text-blue-600/80 font-medium leading-relaxed">Mật khẩu của khách hàng được quản lý độc lập bởi chính họ vì lý do bảo mật. Quản trị viên không thể trực tiếp đổi mật khẩu cho khách hàng.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Engagement Statistics (Read-only) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group">
            <div class="bg-gray-50 px-8 py-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-800 flex items-center gap-3">
                        Thống Kê Tương Tác
                    </h3>
                    <p class="text-[11px] text-gray-500 font-medium mt-1">Đơn hàng và tổng chi tiêu</p>
                </div>
                <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-gray-600 border border-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-6 bg-gray-50 rounded-xl border border-gray-100 flex flex-col items-center text-center">
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-3">Tổng Đơn Hàng</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $customer->orders()->count() }}</p>
                    </div>

                    <div class="p-6 bg-red-50 rounded-xl border border-red-100 flex flex-col items-center text-center">
                        <p class="text-[10px] font-semibold text-red-500 uppercase tracking-widest mb-3">Tổng Chi Tiêu</p>
                        <p class="text-xl font-bold text-red-700">{{ number_format($customer->orders()->sum('total')) }}đ</p>
                    </div>

                    <div class="p-6 bg-gray-50 rounded-xl border border-gray-100 flex flex-col items-center text-center">
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-3">Ngày Đăng Ký</p>
                        <p class="text-sm font-bold text-gray-900 mb-0.5">{{ $customer->created_at->format('d/m/Y') }}</p>
                        <p class="text-[10px] text-gray-400 font-medium">Hệ thống ghi nhận</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Command Bar -->
        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-red-600 text-white px-8 py-3.5 rounded-xl font-bold text-sm uppercase tracking-wide hover:bg-red-700 transition-all shadow-sm active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Cập Nhật Thông Tin
            </button>
            <a href="{{ route('admin.customers.index') }}" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-white text-gray-700 px-8 py-3.5 rounded-xl font-bold text-sm uppercase tracking-wide hover:bg-gray-50 transition-all border border-gray-200 active:scale-95">
                Hủy Bỏ
            </a>
        </div>
    </form>
</div>
@endsection
