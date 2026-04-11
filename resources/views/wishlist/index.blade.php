@extends('layouts.storefront')

@section('title', 'Sản phẩm yêu thích')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-100">
        <h1 class="text-2xl font-bold text-gray-900">Danh Sách Yêu Thích</h1>
        <p class="text-sm text-gray-500 mt-1">Lưu trữ những sản phẩm bạn quan tâm để mua sắm sau</p>
    </div>

    <div class="p-8">
        @if($wishlistItems->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($wishlistItems as $item)
                    <div class="group relative bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300">
                        <!-- Product Image -->
                        <div class="aspect-[3/4] overflow-hidden relative bg-gray-50">
                            @php
                                $imgUrl = str_starts_with($item->product->image, 'dummy_url|') 
                                    ? str_replace('dummy_url|', '', $item->product->image) 
                                    : asset('storage/' . $item->product->image);
                            @endphp
                            <img src="{{ $imgUrl }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            
                            <!-- Quick View Overlay -->
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                                <a href="{{ route('products.show', $item->product->slug) }}" class="p-3 bg-white text-gray-900 rounded-full hover:bg-red-600 hover:text-white transition-all transform translate-y-4 group-hover:translate-y-0 duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <form action="{{ route('wishlist.remove', $item->product) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-3 bg-white text-red-600 rounded-full hover:bg-red-600 hover:text-white transition-all transform translate-y-4 group-hover:translate-y-0 duration-300 delay-75">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="p-4">
                            <h3 class="font-bold text-gray-900 text-sm line-clamp-1 mb-2">
                                <a href="{{ route('products.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                            </h3>
                            <div class="flex items-center justify-between">
                                <span class="text-red-600 font-bold">{{ number_format($item->product->price) }}đ</span>
                                <button onclick="addToCartDirectly({{ $item->product->id }})" class="text-xs font-bold text-gray-400 hover:text-red-600 transition-colors uppercase tracking-wider">
                                    + Giỏ hàng
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                    <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Danh sách trống</h3>
                <p class="text-gray-500 mb-8">Bạn chưa lưu sản phẩm nào vào danh sách yêu thích.</p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center px-8 py-3 bg-red-600 text-white rounded-xl font-bold text-sm tracking-wide hover:bg-red-700 transition-all shadow-md active:scale-95">
                    Khám phá sản phẩm
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function addToCartDirectly(productId) {
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', 1);
    
    fetch('{{ route('cart.add') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            if(window.updateCartBadge) updateCartBadge(data.cartCount);
        } else {
            showToast(data.message || 'Có lỗi xảy ra!', 'error');
        }
    })
    .catch(() => showToast('Có lỗi xảy ra!', 'error'));
}
</script>
@endsection
