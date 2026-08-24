@extends('admin.layouts.app')

@section('title', 'Thêm Nhân Viên')
@section('page-title', 'Thêm Mới Nhân Sự')
@section('page-description', 'Tạo hồ sơ và cấp quyền truy cập cho nhân viên mới')

@section('content')
<div class="max-w-4xl">
    <form method="POST" action="{{ route('admin.employees.store') }}" class="space-y-8">
        @csrf

        <!-- Personnel Identity Block -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group">
            <div class="bg-gray-50 px-8 py-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-800 flex items-center gap-3">
                        Hồ Sơ Cá Nhân
                    </h3>
                    <p class="text-[11px] text-gray-500 font-medium mt-1">Thông tin cơ bản và thông tin đăng nhập</p>
                </div>
                <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-red-500 border border-red-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide">Họ và Tên <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all outline-none"
                               placeholder="Nhập họ và tên...">
                        @error('name')
                            <p class="text-xs font-semibold text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide">Địa Chỉ Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all outline-none"
                               placeholder="email@congty.com">
                        @error('email')
                            <p class="text-xs font-semibold text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide">Số Điện Thoại</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all outline-none"
                               placeholder="09xx xxx xxx">
                        @error('phone')
                            <p class="text-xs font-semibold text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide">Mật Khẩu <span class="text-red-500">*</span></label>
                            <input type="password" name="password" required
                                   class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all outline-none">
                            @error('password')
                                <p class="text-xs font-semibold text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide">Xác Nhận Mật Khẩu <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all outline-none">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Professional Protocol Block -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group">
            <div class="bg-gray-50 px-8 py-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-800 flex items-center gap-3">
                        Thông Tin Công Việc
                    </h3>
                    <p class="text-[11px] text-gray-500 font-medium mt-1">Vị trí, phòng ban và lương</p>
                </div>
                <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-gray-700 border border-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide">Mã Nhân Viên <span class="text-red-500">*</span></label>
                        <input type="text" name="employee_code" value="{{ old('employee_code') }}" required
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all outline-none uppercase"
                               placeholder="VD: NV001">
                        @error('employee_code')
                            <p class="text-xs font-semibold text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide">Chức Danh <span class="text-red-500">*</span></label>
                        <input type="text" name="position" value="{{ old('position') }}" required
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all outline-none"
                               placeholder="VD: Nhân viên bán hàng">
                        @error('position')
                            <p class="text-xs font-semibold text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide">Phòng Ban <span class="text-red-500">*</span></label>
                        <input type="text" name="department" value="{{ old('department') }}" required
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all outline-none"
                               placeholder="VD: Phòng Kinh Doanh">
                        @error('department')
                            <p class="text-xs font-semibold text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide">Ngày Bắt Đầu <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="date" name="hire_date" value="{{ old('hire_date') }}" required
                                   class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all outline-none">
                        </div>
                        @error('hire_date')
                            <p class="text-xs font-semibold text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide">Mức Lương Cán Bản (VNĐ) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="number" name="salary" value="{{ old('salary') }}" required min="0" step="1000"
                                   class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 pr-12 text-sm font-bold text-gray-800 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all text-right outline-none"
                                   placeholder="0">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-semibold text-gray-400">VNĐ</span>
                        </div>
                        @error('salary')
                            <p class="text-xs font-semibold text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide">Trạng Thái <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="status" required
                                    class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all appearance-none cursor-pointer outline-none">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Đang Làm Việc</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Đã Nghỉ Việc</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        @error('status')
                            <p class="text-xs font-semibold text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide">Phân Quyền (Vai Trò) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="role" required
                                    class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all appearance-none cursor-pointer outline-none">
                                <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Nhân Viên (Staff)</option>
                                <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Quản Lý (Manager)</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Quản Trị Viên (Admin)</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        @error('role')
                            <p class="text-xs font-semibold text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide">Cửa Hàng Làm Việc</label>
                        <div class="relative">
                            <select name="store_id"
                                    class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all appearance-none cursor-pointer outline-none">
                                <option value="">-- Chưa Phân Cửa Hàng --</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>
                                        {{ $store->name }} ({{ $store->city }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        @error('store_id')
                            <p class="text-xs font-semibold text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-red-600 text-white px-8 py-3.5 rounded-xl font-bold text-sm uppercase tracking-wide hover:bg-red-700 transition-all shadow-sm active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Lưu Nhân Viên
            </button>
            <a href="{{ route('admin.employees.index') }}" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-white text-gray-700 px-8 py-3.5 rounded-xl font-bold text-sm uppercase tracking-wide hover:bg-gray-50 transition-all border border-gray-200 active:scale-95">
                Hủy Bỏ
            </a>
        </div>
    </form>
</div>
@endsection
