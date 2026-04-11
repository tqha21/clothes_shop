@extends('admin.layouts.app')

@section('title', 'Sản Phẩm')
@section('page-title', 'Danh Sách Sản Phẩm')
@section('page-description', 'Quản lý kho hàng và thông tin sản phẩm')

@section('content')
<!-- Header Actions with Advanced Filters -->
<form action="{{ route('admin.products.index') }}" method="GET" class="mb-8 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between">
    <div class="flex flex-wrap items-center gap-4 w-full md:w-auto">
        <!-- Search -->
        <div class="relative group flex-1 md:w-64">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm sản phẩm..." class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all">
            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>

        <!-- Category Filter -->
        <select name="category_id" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-red-500 outline-none">
            <option value="">Tất cả danh mục</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>

        <!-- Stock Status Filter -->
        <select name="stock_status" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-red-500 outline-none">
            <option value="">Trạng thái Kho</option>
            <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>Còn nhiều (>20)</option>
            <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Sắp hết (1-20)</option>
            <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng (0)</option>
        </select>

        <!-- Price Range Filter -->
        <select name="price_range" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-red-500 outline-none">
            <option value="">Khoảng giá</option>
            <option value="under_500" {{ request('price_range') == 'under_500' ? 'selected' : '' }}>Dưới 500k</option>
            <option value="500_to_1m" {{ request('price_range') == '500_to_1m' ? 'selected' : '' }}>Từ 500k - 1 Triệu</option>
            <option value="over_1m" {{ request('price_range') == 'over_1m' ? 'selected' : '' }}>Trên 1 Triệu</option>
        </select>

        <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-4 rounded-xl text-sm transition-colors">Lọc</button>
        <a href="{{ route('admin.products.index') }}" class="text-xs text-red-600 font-medium hover:underline">Xóa lọc</a>
    </div>
    
    <a href="{{ route('admin.products.create') }}" class="group flex items-center gap-2 bg-gray-800 text-white px-5 py-2.5 rounded-xl font-semibold text-xs py-3 uppercase tracking-wider hover:bg-gray-900 transition-all shadow-sm flex-shrink-0">
        <div class="w-5 h-5 bg-white/10 rounded-md flex items-center justify-center group-hover:bg-white/20">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        </div>
        Thêm Mới
    </a>
</form>

<!-- Modern Products Repository Table -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Hình Ảnh</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Tên Sản Phẩm</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Danh Mục</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Giá Bán</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Tồn Kho</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Đã Bán</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Trạng Thái</th>
                    <th class="px-6 py-4 text-center text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Thao Tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                    <td class="px-6 py-4">
                        <div class="w-16 h-16 rounded-lg overflow-hidden shadow-sm border border-gray-100 group-hover:border-red-400 transition-colors">
                            @if(str_starts_with($product->image, 'dummy_url|'))
                                <img src="{{ str_replace('dummy_url|', '', $product->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $product->name }}">
                            @else
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $product->name }}">
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="max-w-xs">
                            <p class="font-bold text-gray-800 mb-1 group-hover:text-red-500 transition-colors truncate">{{ $product->name }}</p>
                            <p class="text-[11px] text-gray-500 line-clamp-1">{{ $product->description }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        <span class="inline-flex px-2.5 py-1 rounded-md text-[10px] font-semibold tracking-wide bg-gray-100 text-gray-700">
                            {{ $product->category->name }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($product->sale_price)
                            <div class="flex flex-col">
                                <span class="text-[11px] font-medium line-through text-gray-400">{{ number_format($product->price) }}đ</span>
                                <input type="number" class="w-24 text-sm font-bold text-red-600 bg-transparent border border-transparent hover:border-gray-200 focus:border-red-500 focus:ring-0 rounded p-1 -ml-1 transition-colors quick-edit-input" data-id="{{ $product->id }}" data-field="sale_price" value="{{ $product->sale_price }}">
                            </div>
                        @else
                            <input type="number" class="w-24 text-sm font-bold text-gray-900 bg-transparent border border-transparent hover:border-gray-200 focus:border-red-500 focus:ring-0 rounded p-1 -ml-1 transition-colors quick-edit-input" data-id="{{ $product->id }}" data-field="price" value="{{ $product->price }}">
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-1 mt-1">
                            <input type="number" class="w-16 text-xs font-bold {{ $product->stock > 20 ? 'text-gray-800' : ($product->stock > 0 ? 'text-yellow-600' : 'text-red-500') }} bg-transparent border border-transparent hover:border-gray-200 focus:border-red-500 focus:ring-0 rounded p-1 text-center transition-colors quick-edit-input" data-id="{{ $product->id }}" data-field="stock" value="{{ $product->stock }}">
                            <span class="font-normal text-[11px] text-gray-500">sp</span>
                        </div>
                        @if($product->stock > 20)
                            <div class="w-16 h-1 mt-1 bg-gray-200 rounded-full overflow-hidden ml-1">
                                <div class="w-full h-full bg-green-500"></div>
                            </div>
                        @elseif($product->stock > 0)
                            <div class="w-16 h-1 mt-1 bg-gray-200 rounded-full overflow-hidden ml-1">
                                <div class="w-1/2 h-full bg-yellow-500"></div>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                         @if($product->stock > 0)
                            <span class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                <span class="text-[11px] font-semibold text-gray-700">Còn Hàng</span>
                            </span>
                        @else
                            <span class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                <span class="text-[11px] font-semibold text-gray-500">Ngừng Bán</span>
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}" class="w-8 h-8 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg flex items-center justify-center transition-colors inline-flex">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline btn-delete-form" data-message="Bạn có chắc chắn muốn xóa sản phẩm này?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 bg-red-50 hover:bg-red-100 text-red-500 rounded-lg flex items-center justify-center transition-colors inline-flex border border-red-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-6 py-12 text-center text-gray-400 font-medium text-sm">Chưa có sản phẩm nào trong kho.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($products->hasPages())
<div class="mt-8 flex justify-center">
    {{ $products->links() }}
</div>
@endif

<style>
    .pagination { @apply flex gap-2; }
    .page-item { @apply rounded-lg border border-gray-100 bg-white shadow-sm overflow-hidden transition-all; }
    .page-link { @apply flex items-center justify-center w-9 h-9 text-xs font-semibold text-gray-700; }
    .page-item.active { @apply bg-red-600 border-red-600 shadow-md; }
    .page-item.active .page-link { @apply text-white; }
    .page-item:hover:not(.active) { @apply bg-gray-50 border-gray-200 transform -translate-y-0.5; }
    
    /* Input arrows override */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.quick-edit-input');
    
    inputs.forEach(input => {
        let originalValue = input.value;
        
        const saveEdit = async (el) => {
            if(el.value === originalValue) return; // no change
            
            const field = el.dataset.field;
            const id = el.dataset.id;
            const value = el.value;
            
            try {
                // Add loading indicator class
                el.classList.add('opacity-50');
                
                const response = await fetch(`/admin/products/${id}/quick-edit`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        [field]: value
                    })
                });
                
                const result = await response.json();
                el.classList.remove('opacity-50');
                
                if(result.success) {
                    originalValue = value;
                    // Optional: show a small success toast
                    el.classList.add('bg-green-50', 'text-green-700');
                    setTimeout(() => el.classList.remove('bg-green-50', 'text-green-700'), 1000);
                } else {
                    alert('Lỗi cập nhật. Vui lòng thử lại.');
                    el.value = originalValue;
                }
            } catch (error) {
                console.error(error);
                el.classList.remove('opacity-50');
                el.value = originalValue;
            }
        };

        // Trigger on Blur (click outside)
        input.addEventListener('blur', function() {
            saveEdit(this);
        });

        // Trigger on Enter key
        input.addEventListener('keydown', function(e) {
            if(e.key === 'Enter') {
                e.preventDefault();
                this.blur(); // will trigger the blur event
            }
        });
    });
});
</script>
@endpush
@endsection
