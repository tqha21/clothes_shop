@extends('admin.layouts.app')

@section('title', 'Chỉnh Sửa Voucher')
@section('page-title', 'Chỉnh Sửa Voucher')
@section('page-description', 'Cập nhật thông tin mã giảm giá')

@section('content')
<div class="max-w-2xl">
    <div class="mb-4">
        <a href="{{ route('admin.vouchers.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Quay lại danh sách
        </a>
    </div>

    {{-- Usage Info Card --}}
    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6 flex items-center gap-3">
        <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-blue-800">Voucher <span class="font-mono tracking-wider">{{ $voucher->code }}</span></p>
            <p class="text-xs text-blue-600 mt-0.5">Đã sử dụng: <strong>{{ $voucher->used_count }}</strong> lần {{ $voucher->usage_limit ? '/ ' . $voucher->usage_limit . ' lần' : '' }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <form action="{{ route('admin.vouchers.update', $voucher) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Code --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Mã Voucher <span class="text-red-500">*</span></label>
                <input type="text" name="code" value="{{ old('code', $voucher->code) }}" placeholder="VD: SUMMER2026"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-mono font-bold uppercase tracking-widest focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all @error('code') border-red-400 @enderror">
                @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Type + Value --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Loại Giảm Giá <span class="text-red-500">*</span></label>
                    <select name="type" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-red-500 outline-none @error('type') border-red-400 @enderror">
                        <option value="percentage" {{ old('type', $voucher->type) == 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                        <option value="fixed" {{ old('type', $voucher->type) == 'fixed' ? 'selected' : '' }}>Số tiền cố định (đ)</option>
                    </select>
                    @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Giá Trị Giảm <span class="text-red-500">*</span></label>
                    <input type="number" name="value" value="{{ old('value', $voucher->value) }}" min="0" step="0.01"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all @error('value') border-red-400 @enderror">
                    @error('value') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Min Order + Usage Limit --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Đơn Hàng Tối Thiểu (đ)</label>
                    <input type="number" name="min_order_amount" value="{{ old('min_order_amount', $voucher->min_order_amount) }}" min="0" step="1000"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Giới Hạn Sử Dụng</label>
                    <input type="number" name="usage_limit" value="{{ old('usage_limit', $voucher->usage_limit) }}" min="{{ $voucher->used_count }}" placeholder="Không giới hạn"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all">
                    @if($voucher->used_count > 0)
                        <p class="text-gray-400 text-xs mt-1">Tối thiểu {{ $voucher->used_count }} (số đã dùng).</p>
                    @endif
                </div>
            </div>

            {{-- Expiry Date --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Ngày Hết Hạn</label>
                <input type="date" name="expires_at" value="{{ old('expires_at', $voucher->expires_at ? $voucher->expires_at->format('Y-m-d') : '') }}"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all @error('expires_at') border-red-400 @enderror">
                @error('expires_at') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Active Status --}}
            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-gray-100">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                    class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500"
                    {{ old('is_active', $voucher->is_active) ? 'checked' : '' }}>
                <label for="is_active" class="text-sm font-semibold text-gray-700 cursor-pointer">Kích hoạt voucher</label>
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="flex items-center gap-2 bg-gray-800 text-white px-6 py-3 rounded-xl font-semibold text-sm hover:bg-gray-900 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Lưu Thay Đổi
                </button>
                <a href="{{ route('admin.vouchers.index') }}" class="px-6 py-3 rounded-xl font-semibold text-sm text-gray-600 hover:bg-gray-100 transition-colors">Hủy bỏ</a>
            </div>
        </form>
    </div>
</div>
@endsection
