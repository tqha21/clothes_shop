@extends('admin.layouts.app')

@section('title', 'Sản Phẩm')
@section('page-title', 'Thêm Sản Phẩm Mới')
@section('page-description', 'Thêm sản phẩm mới vào kho hàng')

@section('content')
<div class="max-w-5xl">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <!-- Manifest Identity Block -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group">
            <div class="bg-gray-800 p-6 flex items-center justify-between border-b border-white/10 relative overflow-hidden">
                <div class="absolute inset-0 bg-red-500/10 translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                <div class="relative z-10">
                    <h3 class="text-xs font-semibold text-white uppercase tracking-widest mb-1">Thông Tin Cơ Bản</h3>
                    <p class="text-xs text-gray-400 font-medium">Tên gọi và phân loại sản phẩm</p>
                </div>
                <div class="relative z-10 w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center text-white shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Product Title -->
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest">Tên Sản Phẩm <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-900 focus:ring-2 focus:ring-red-500 focus:bg-white focus:border-red-500 transition-all placeholder:text-gray-400 outline-none"
                               placeholder="VD: Áo Thun Nam">
                        @error('name')
                            <p class="text-[11px] font-semibold text-red-600 mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category Vector -->
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest">Danh Mục <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="category_id" required
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-900 focus:ring-2 focus:ring-red-500 focus:bg-white focus:border-red-500 transition-all appearance-none cursor-pointer outline-none">
                                <option value="">Chọn danh mục</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        @error('category_id')
                            <p class="text-[11px] font-semibold text-red-600 mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Asset Vector Block -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-full group">
                    <div class="bg-gray-800 p-6 flex items-center justify-between border-b border-white/10 relative overflow-hidden">
                        <div class="absolute inset-0 bg-red-500/10 -translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                        <h3 class="text-xs font-semibold text-white uppercase tracking-widest relative z-10">Hình Ảnh</h3>
                        <div class="relative z-10 w-8 h-8 bg-gray-700 rounded-lg flex items-center justify-center text-white border border-white/10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                    
                    <div class="p-8">
                        <label class="flex flex-col items-center justify-center w-full h-80 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer bg-gray-50/50 hover:bg-white hover:border-red-400 transition-all relative overflow-hidden group/upload">
                            <div class="flex flex-col items-center justify-center text-center px-4 relative z-10">
                                <div class="w-16 h-16 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-center mb-4 group-hover/upload:scale-105 transition-transform">
                                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                </div>
                                <p class="text-sm font-bold text-gray-800 mb-2 mt-1">Tải ảnh lên</p>
                                <p class="text-xs text-gray-500 font-medium tracking-tight">Kéo thả hoặc nhấn</p>
                            </div>
                            <input type="file" name="image" class="hidden" accept="image/*" required onchange="previewImage(event)">
                            
                            <div id="imagePreview" class="absolute inset-0 hidden bg-white">
                                <img id="preview" class="w-full h-full object-cover" alt="Preview">
                                <div class="absolute inset-0 bg-gray-900/50 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                    <p class="text-white text-sm font-bold">Nhấn để đổi ảnh</p>
                                </div>
                            </div>
                        </label>
                        @error('image')
                            <p class="text-[11px] font-semibold text-red-600 mt-3 text-center">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Financial Intelligence Block -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group">
                    <div class="bg-gray-800 p-6 flex items-center justify-between border-b border-white/10 relative overflow-hidden">
                        <div class="absolute inset-0 bg-red-500/10 translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                        <div class="relative z-10">
                            <h3 class="text-xs font-semibold text-white uppercase tracking-widest mb-1">Giá & Tồn Kho</h3>
                            <p class="text-xs text-gray-300 font-medium">Thiết lập giá bán và số lượng</p>
                        </div>
                        <div class="relative z-10 w-10 h-10 bg-gray-700 rounded-xl flex items-center justify-center text-white border border-white/10">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>

                    <div class="p-8 space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest">Giá Gốc (VNĐ) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="number" name="price" value="{{ old('price') }}" required min="0" step="1000"
                                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-900 focus:ring-2 focus:ring-red-500 focus:bg-white focus:border-red-500 transition-all outline-none"
                                           placeholder="0">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-semibold text-gray-400">VNĐ</span>
                                </div>
                                @error('price')
                                    <p class="text-[11px] font-semibold text-red-600 mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest">Giá Khuyến Mãi (VNĐ)</label>
                                <div class="relative">
                                    <input type="number" name="sale_price" value="{{ old('sale_price') }}" min="0" step="1000"
                                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-red-600 focus:ring-2 focus:ring-red-500 focus:bg-white focus:border-red-500 transition-all outline-none"
                                           placeholder="0">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-semibold text-gray-400">VNĐ</span>
                                </div>
                                @error('sale_price')
                                    <p class="text-[11px] font-semibold text-red-600 mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest">Tồn Kho (Sản Phẩm) <span class="text-red-500">*</span></label>
                            <div class="relative md:max-w-xs">
                                <input type="number" name="stock" value="{{ old('stock', 0) }}" required min="0"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-900 focus:ring-2 focus:ring-red-500 focus:bg-white focus:border-red-500 transition-all outline-none"
                                       placeholder="Số lượng">
                            </div>
                            @error('stock')
                                <p class="text-[11px] font-semibold text-red-600 mt-1.5 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                </div>

                <!-- Variants Block -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group">
                    <div class="bg-gray-800 p-6 flex items-center justify-between border-b border-white/10 relative overflow-hidden">
                        <div class="absolute inset-0 bg-red-500/10 -translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                        <div class="relative z-10">
                            <h3 class="text-xs font-semibold text-white uppercase tracking-widest mb-1">Màu Sắc & Kích Thước</h3>
                            <p class="text-xs text-gray-300 font-medium">Chọn các biến thể của sản phẩm (nếu có)</p>
                        </div>
                        <div class="relative z-10 w-10 h-10 bg-gray-700 rounded-xl flex items-center justify-center text-white border border-white/10">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                        </div>
                    </div>

                    <div class="p-8 space-y-8">
                        <!-- Colors -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-4">Màu sắc khả dụng</label>
                            
                            <div id="colors-hidden-container">
                                @foreach(old('colors', []) as $oldColor)
                                    <input type="hidden" name="colors[]" value="{{ $oldColor }}">
                                @endforeach
                            </div>

                            <div class="flex flex-wrap gap-4">
                                @php
                                    $colors = [
                                        '#000000' => 'Đen', '#ffffff' => 'Trắng', '#ef4444' => 'Đỏ', 
                                        '#3b82f6' => 'Xanh dương', '#eab308' => 'Vàng', '#22c55e' => 'Xanh lá', 
                                        '#6b7280' => 'Xám', '#ec4899' => 'Hồng', '#a855f7' => 'Tím', 
                                        '#f97316' => 'Cam', '#78350f' => 'Nâu',
                                        '#f5f5dc' => 'Be', '#1e3a8a' => 'Xanh than', '#9ca3af' => 'Ghi'
                                    ];
                                @endphp
                                @foreach($colors as $hex => $label)
                                    <label class="inline-flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" value="{{ $label }}" data-label="{{ $label }}" {{ in_array($label, old('colors', [])) ? 'checked' : '' }} onchange="toggleColor(this, '{{ $label }}')" class="color-checkbox w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-4 h-4 rounded-full border border-gray-200 shadow-sm" style="background-color: {{ $hex }}"></span>
                                            <span class="text-sm font-medium text-gray-700 group-hover:text-red-600 transition-colors">{{ $label }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Sizes -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-4">Kích thước khả dụng</label>
                            <div class="flex flex-wrap gap-3">
                                @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL'] as $size)
                                    <label class="cursor-pointer">
                                        <input type="checkbox" name="sizes[]" value="{{ $size }}" {{ in_array($size, old('sizes', [])) ? 'checked' : '' }} class="peer sr-only">
                                        <div class="w-10 h-10 flex items-center justify-center rounded-lg border-2 border-gray-200 text-gray-600 font-bold peer-checked:border-red-600 peer-checked:text-red-600 peer-checked:bg-red-50 hover:border-gray-300 transition-all">
                                            {{ $size }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Extended Description -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group">
                    <div class="bg-gray-800 p-6 flex items-center justify-between border-b border-white/10 relative overflow-hidden">
                        <div class="absolute inset-0 bg-red-500/10 -translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                        <div class="relative z-10">
                            <h3 class="text-xs font-semibold text-white uppercase tracking-widest mb-1">Mô Tả Sản Phẩm</h3>
                            <p class="text-xs text-gray-300 font-medium">Nội dung chi tiết</p>
                        </div>
                        <div class="relative z-10 w-10 h-10 bg-gray-700 rounded-xl flex items-center justify-center text-white border border-white/10">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                        </div>
                    </div>

                    <div class="p-8">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-3">Văn bản mô tả <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="5" required
                                  class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium text-gray-900 focus:ring-2 focus:ring-red-500 focus:bg-white focus:border-red-500 outline-none transition-all placeholder:text-gray-400"
                                  placeholder="Mô tả chi tiết sản phẩm... Mở rộng khả năng thuyết phục khách hàng.">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-[11px] font-semibold text-red-600 mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Command Execution Bar -->
        <div class="flex items-center gap-4 pt-6">
            <button type="submit" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-gray-800 text-white px-8 py-3.5 rounded-xl font-bold text-sm hover:bg-gray-900 transition-all shadow-sm active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Lưu Sản Phẩm
            </button>
            <a href="{{ route('admin.products.index') }}" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-white text-gray-600 px-8 py-3.5 rounded-xl font-bold text-sm hover:bg-gray-50 transition-all border border-gray-200 active:scale-95">
                Hủy Bỏ
            </a>
        </div>
    </form>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}

function toggleColor(checkbox, colorLabel) {
    const container = document.getElementById('colors-hidden-container');
    if (checkbox.checked) {
        if (!container.querySelector(`input[value="${colorLabel}"]`)) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'colors[]';
            input.value = colorLabel;
            container.appendChild(input);
        }
    } else {
        const inputs = Array.from(container.querySelectorAll('input'));
        const target = inputs.find(i => i.value === colorLabel);
        if (target) target.remove();
    }
}
</script>
@endsection
