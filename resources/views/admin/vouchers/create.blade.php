@extends('admin.layouts.app')

@section('title', 'Tạo Voucher Mới')
@section('page-title', 'Tạo Voucher Mới')
@section('page-description', 'Điền thông tin để tạo mã giảm giá mới')

@section('content')
<div class="max-w-2xl">
    <div class="mb-4">
        <a href="{{ route('admin.vouchers.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Quay lại danh sách
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <form action="{{ route('admin.vouchers.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Code --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Mã Voucher <span class="text-red-500">*</span></label>
                <input type="text" name="code" value="{{ old('code') }}" placeholder="VD: SUMMER2026"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-mono font-bold uppercase tracking-widest focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all @error('code') border-red-400 @enderror">
                @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                <p class="text-gray-400 text-xs mt-1">Mã sẽ được tự động chuyển thành chữ hoa.</p>
            </div>

            {{-- Type + Value --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Loại Giảm Giá <span class="text-red-500">*</span></label>
                    <select name="type" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-red-500 outline-none @error('type') border-red-400 @enderror">
                        <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                        <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Số tiền cố định (đ)</option>
                    </select>
                    @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Giá Trị Giảm <span class="text-red-500">*</span></label>
                    <input type="number" name="value" value="{{ old('value') }}" min="0" step="0.01" placeholder="VD: 20"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all @error('value') border-red-400 @enderror">
                    @error('value') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Min Order + Usage Limit --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Đơn Hàng Tối Thiểu (đ)</label>
                    <input type="number" name="min_order_amount" value="{{ old('min_order_amount', 0) }}" min="0" step="1000"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all">
                    <p class="text-gray-400 text-xs mt-1">Nhập 0 nếu không giới hạn.</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Giới Hạn Sử Dụng</label>
                    <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" min="1" placeholder="Không giới hạn"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all">
                    <p class="text-gray-400 text-xs mt-1">Để trống nếu không giới hạn.</p>
                </div>
            </div>

            {{-- Expiry Date --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Ngày Hết Hạn</label>
                <input type="date" name="expires_at" value="{{ old('expires_at') }}"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all @error('expires_at') border-red-400 @enderror">
                @error('expires_at') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                <p class="text-gray-400 text-xs mt-1">Để trống nếu không giới hạn thời gian.</p>
            </div>

            {{-- Active Status --}}
            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-gray-100">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1" class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500" {{ old('is_active', true) ? 'checked' : '' }}>
                <label for="is_active" class="text-sm font-semibold text-gray-700 cursor-pointer">Kích hoạt voucher ngay</label>
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="flex items-center gap-2 bg-gray-800 text-white px-6 py-3 rounded-xl font-semibold text-sm hover:bg-gray-900 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Tạo Voucher
                </button>
                <a href="{{ route('admin.vouchers.index') }}" class="px-6 py-3 rounded-xl font-semibold text-sm text-gray-600 hover:bg-gray-100 transition-colors">Hủy bỏ</a>
            </div>
        </form>
    </div>
</div>
@endsection
