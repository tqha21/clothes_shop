@extends('admin.layouts.app')

@section('title', 'Thêm Khách Hàng')
@section('page-title', 'Thêm Khách Hàng Mới')
@section('page-description', 'Tạo tài khoản khách hàng mới trong hệ thống')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8">
            <form action="{{ route('admin.customers.store') }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Họ và Tên</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-5 py-3 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all outline-none @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Địa Chỉ Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-5 py-3 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all outline-none @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Số Điện Thoại</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-5 py-3 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all outline-none">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Mật Khẩu</label>
                            <input type="password" name="password" id="password" required
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-5 py-3 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all outline-none @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Xác Nhận Mật Khẩu</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-5 py-3 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all outline-none">
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex items-center gap-4">
                    <button type="submit" class="flex-1 bg-red-600 text-white px-8 py-3.5 rounded-xl font-bold text-sm uppercase tracking-wide hover:bg-red-700 transition-all shadow-sm active:scale-95">
                        Tạo Khách Hàng
                    </button>
                    <a href="{{ route('admin.customers.index') }}" class="px-8 py-3.5 bg-gray-100 text-gray-700 rounded-xl font-bold text-sm uppercase tracking-wide hover:bg-gray-200 transition-all text-center">
                        Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
