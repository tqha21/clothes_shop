@extends('admin.layouts.app')

@section('title', 'Sản Phẩm')
@section('page-title', 'Cập Nhật Sản Phẩm')
@section('page-description', 'Chỉnh sửa thông tin sản phẩm: ' . $product->name)

@section('content')
<div class="max-w-5xl">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')
        
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
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-900 focus:ring-2 focus:ring-red-500 focus:bg-white focus:border-red-500 transition-all outline-none"
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
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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

        <!-- Asset Deployment Block -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group">
            <div class="bg-gray-800 p-6 flex items-center justify-between border-b border-white/10 relative overflow-hidden">
                <div class="absolute inset-0 bg-red-500/10 -translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                <div class="relative z-10">
                    <h3 class="text-xs font-semibold text-white uppercase tracking-widest mb-1">Hình Ảnh</h3>
                    <p class="text-xs text-gray-400 font-medium">Hình ảnh đại diện hiện tại và tải lên mới</p>
                </div>
                <div class="relative z-10 w-10 h-10 bg-gray-700 rounded-xl flex items-center justify-center text-white border border-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            
            <div class="p-8 flex flex-col md:flex-row gap-8">
                @if($product->image)
                <div class="w-full md:w-64 space-y-3">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest">Ảnh Hiện Tại</label>
                    <div class="relative w-full aspect-square bg-gray-100 rounded-2xl overflow-hidden group/asset border border-gray-200 shadow-sm">
                        @if(str_starts_with($product->image, 'dummy_url|'))
                            <img src="{{ str_replace('dummy_url|', '', $product->image) }}" class="w-full h-full object-cover group-hover/asset:scale-105 transition-transform duration-500" alt="{{ $product->name }}">
                        @else
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover/asset:scale-105 transition-transform duration-500" alt="{{ $product->name }}">
                        @endif
                        <div class="absolute top-3 right-3 bg-red-600 text-white px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm">
                            Đang Dùng
                        </div>
                    </div>
                </div>
                @endif

                <div class="flex-1 space-y-3">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest">Tải Lên Ảnh Mới</label>
                    <div class="relative">
                        <label class="flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer bg-gray-50/50 hover:bg-white hover:border-red-400 transition-all group/upload overflow-hidden">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-10 relative z-10">
                                <div class="w-16 h-16 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-center mb-4 group-hover/upload:scale-105 transition-transform">
                                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                </div>
                                <p class="mb-2 text-sm font-bold text-gray-800">Cập nhật hình ảnh</p>
                                <p class="text-xs text-gray-500 font-medium mb-3">Kéo thả hoặc nhấn (Tùy chọn)</p>
                                <span class="px-5 py-2 bg-gray-800 text-white text-xs font-semibold rounded-lg shadow-sm">Chọn Tệp Mới</span>
                            </div>
                            <input type="file" name="image" class="hidden" accept="image/*" onchange="previewImage(event)">
                            
                            <div id="imagePreview" class="absolute inset-0 hidden bg-white">
                                <img id="preview" class="w-full h-full object-cover" alt="Preview">
                                <div class="absolute inset-0 bg-gray-900/40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                    <p class="text-white text-sm font-bold">Nhấn để đổi ảnh</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                @error('image')
                    <p class="text-[11px] font-semibold text-red-600 mt-2 ml-2">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Phân khu ảnh phụ -->
            <div class="w-full mt-6 pt-6 border-t border-gray-100 space-y-4">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest">Ảnh phụ (Nhiều ảnh để thể hiện màu sắc)</label>
                
                @if($product->images && count($product->images) > 0)
                <div class="flex gap-4 mb-4">
                    @foreach($product->images as $img)
                        <div class="relative w-24 h-24 rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                            <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
                <p class="text-xs text-orange-600 font-medium">Lưu ý: Nếu tải ảnh mới lên, toàn bộ ảnh phụ cũ sẽ bị thay thế.</p>
                @endif

                <label class="relative flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer bg-gray-50/50 hover:bg-white hover:border-blue-400 transition-all group/upload overflow-hidden">
                    <div class="flex flex-col items-center justify-center text-center px-10 relative z-10">
                        <div class="w-10 h-10 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-center mb-2 group-hover/upload:scale-105 transition-transform">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        </div>
                        <p class="mb-1 text-sm font-bold text-gray-800">{{ ($product->images && count($product->images) > 0) ? 'Thay đổi tất cả ảnh phụ' : 'Chọn nhiều ảnh phụ' }}</p>
                        <p class="text-xs text-gray-500 font-medium">Giữ Ctrl hoặc kéo thả nhiều ảnh</p>
                    </div>
                    <input type="file" name="images[]" multiple class="hidden" accept="image/*">
                </label>
                @error('images.*')
                    <p class="text-[11px] font-semibold text-red-600 mt-2 ml-2">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Financial Intelligence Block -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group">
            <div class="bg-gray-800 p-6 flex items-center justify-between border-b border-white/10 relative overflow-hidden">
                <div class="absolute inset-0 bg-red-500/10 translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                <div class="relative z-10">
                    <h3 class="text-xs font-semibold text-white uppercase tracking-widest mb-1">Giá & Tồn Kho</h3>
                    <p class="text-xs text-gray-400 font-medium">Cập nhật giá bán và số lượng</p>
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
                            <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0" step="1000"
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
                            <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" min="0" step="1000"
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
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required min="0"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-900 focus:ring-2 focus:ring-red-500 focus:bg-white focus:border-red-500 transition-all outline-none"
                               placeholder="Số lượng">
                    </div>
                    @error('stock')
                        <p class="text-[11px] font-semibold text-red-600 mt-1.5 ml-1">{{ $message }}</p>
                    @enderror
                </div>
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
                    <div class="flex items-center justify-between mb-4">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest">Màu sắc khả dụng</label>
                        <span class="text-xs text-orange-500 font-medium">⚠ Màu chọn trước = ảnh đầu tiên</span>
                    </div>
                    @php
                        $productColors = is_array($product->colors) ? $product->colors : [];
                    @endphp

                    {{-- Hiển thị thứ tự màu hiện tại --}}
                    @if(count($productColors) > 0)
                    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-xl">
                        <p class="text-xs font-semibold text-blue-700 mb-2">Thứ tự màu hiện tại (màu 1 = ảnh chính):</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($productColors as $i => $col)
                                <span class="px-2 py-1 bg-white border border-blue-300 rounded-lg text-xs font-bold text-blue-800">{{ $i+1 }}. {{ $col }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Hidden inputs — được quản lý hoàn toàn bởi JS --}}
                    <div id="colors-hidden-container">
                        {{-- JS sẽ điền vào khi submit --}}
                    </div>

                    <div class="flex flex-wrap gap-4">
                        @php
                            $colors = [
                                '#111111' => 'Đen', '#ffffff' => 'Trắng', '#ef4444' => 'Đỏ',
                                '#3b82f6' => 'Xanh dương', '#eab308' => 'Vàng', '#22c55e' => 'Xanh lá',
                                '#6b7280' => 'Xám', '#ec4899' => 'Hồng', '#a855f7' => 'Tím',
                                '#f97316' => 'Cam', '#78350f' => 'Nâu',
                                '#d2b48c' => 'Be', '#1e3a8a' => 'Xanh Navy', '#1c2d4a' => 'Xanh than', '#9ca3af' => 'Ghi',
                                '#5b85b8' => 'Xanh Jean Nhạt', '#1a3a5c' => 'Xanh Jean Đậm',
                                '#8a8f9c' => 'Xám Khói', '#4a7c5e' => 'Xanh Rêu',
                            ];
                        @endphp
                        @foreach($colors as $hex => $label)
                            <label class="inline-flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" value="{{ $label }}" data-label="{{ $label }}"
                                       {{ in_array($label, $productColors) ? 'checked' : '' }}
                                       onchange="toggleColor(this, '{{ $label }}')"
                                       class="color-checkbox w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-4 h-4 rounded-full border border-gray-200 shadow-sm" style="background-color: {{ $hex }}"></span>
                                    <span class="text-sm font-medium text-gray-700 group-hover:text-red-600 transition-colors">{{ $label }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-400 mt-3">Thứ tự click = thứ tự hiển thị trên trang sản phẩm (màu 1 tương ứng ảnh chính, màu 2 ứng ảnh phụ 1, ...)</p>
                </div>

                <!-- Sizes -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-4">Kích thước khả dụng</label>
                    <div class="flex flex-wrap gap-3">
                        @php
                            $productSizes = is_array($product->sizes) ? $product->sizes : [];
                        @endphp
                        @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL'] as $size)
                            <label class="cursor-pointer">
                                <input type="checkbox" name="sizes[]" value="{{ $size }}" {{ in_array($size, old('sizes', $productSizes)) ? 'checked' : '' }} class="peer sr-only">
                                <div class="w-10 h-10 flex items-center justify-center rounded-lg border-2 border-gray-200 text-gray-600 font-bold peer-checked:border-red-600 peer-checked:text-red-600 peer-checked:bg-red-50 hover:border-gray-300 transition-all">
                                    {{ $size }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Narrative Protocol Block -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group">
            <div class="bg-gray-800 p-6 flex items-center justify-between border-b border-white/10 relative overflow-hidden">
                <div class="absolute inset-0 bg-red-500/10 -translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                <div class="relative z-10">
                    <h3 class="text-xs font-semibold text-white uppercase tracking-widest mb-1">Mô Tả Sản Phẩm</h3>
                    <p class="text-xs text-gray-400 font-medium">Nội dung chi tiết hiện tại</p>
                </div>
                <div class="relative z-10 w-10 h-10 bg-gray-700 rounded-xl flex items-center justify-center text-white border border-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                </div>
            </div>
            
            <div class="p-8 space-y-8">
                <div class="space-y-3">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest">Mô Tả Ngắn</label>
                    <textarea name="description" rows="3"
                              class="w-full bg-gray-50 border border-gray-200 rounded-xl px-5 py-4 text-sm font-medium text-gray-900 focus:ring-2 focus:ring-red-500 focus:bg-white focus:border-red-500 transition-all resize-none outline-none"
                              placeholder="Mô tả tóm tắt sản phẩm...">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="space-y-3">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest">Chi Tiết Sản Phẩm</label>
                    <textarea name="content" rows="8"
                              class="w-full bg-gray-50 border border-gray-200 rounded-xl px-5 py-4 text-sm font-medium text-gray-900 focus:ring-2 focus:ring-red-500 focus:bg-white focus:border-red-500 transition-all outline-none"
                              placeholder="Chi tiết chất liệu, kích thước, và hướng dẫn sử dụng...">{{ old('content', $product->content) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Command Execution Bar -->
        <div class="flex items-center gap-4 pt-6">
            <button type="submit" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-gray-800 text-white px-8 py-3.5 rounded-xl font-bold text-sm hover:bg-gray-900 transition-all shadow-sm active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Cập Nhật Sản Phẩm
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

// Lưu thứ tự màu theo thứ tự click (không theo vị trí DOM)
let colorOrder = [];

// Chỉ giữ màu từ DB nếu màu đó có trong danh sách checkbox
(function initColors() {
    const allAvailable = Array.from(document.querySelectorAll('.color-checkbox')).map(el => el.value);
    const existing = @json($productColors);
    // Lọc: chỉ giữ màu vừa có trong DB vừa có trong picker
    colorOrder = existing.filter(c => allAvailable.includes(c));
    rebuildHiddenInputs();
})();

function toggleColor(checkbox, colorLabel) {
    if (checkbox.checked) {
        if (!colorOrder.includes(colorLabel)) {
            colorOrder.push(colorLabel);
        }
    } else {
        colorOrder = colorOrder.filter(c => c !== colorLabel);
    }
    rebuildHiddenInputs();
}

function rebuildHiddenInputs() {
    const container = document.getElementById('colors-hidden-container');
    container.innerHTML = ''; // Xóa hết rồi tạo lại
    colorOrder.forEach(function(color) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'colors[]';
        input.value = color;
        container.appendChild(input);
    });
}
</script>
@endsection
