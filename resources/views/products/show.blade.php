@extends('layouts.storefront')

@section('title', $product->name)

@push('meta')
@php
    $productImg = str_starts_with($product->image, 'dummy_url|')
        ? str_replace('dummy_url|', '', $product->image)
        : asset('storage/' . $product->image);
    $productDesc = \Illuminate\Support\Str::limit(strip_tags($product->description ?? ''), 160);
@endphp
<meta property="og:type" content="product">
<meta property="og:title" content="{{ $product->name }} - HTD Store">
<meta property="og:description" content="{{ $productDesc }}">
<meta property="og:image" content="{{ $productImg }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta name="description" content="{{ $productDesc }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $product->name }} - HTD Store">
<meta name="twitter:description" content="{{ $productDesc }}">
<meta name="twitter:image" content="{{ $productImg }}">
@endpush

@section('content')
<!-- Breadcrumb -->
<div class="bg-gray-50 py-4">
    <div class="container mx-auto px-4">
        <nav class="text-sm">
            <ol class="flex items-center gap-2">
                <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-red-600">Trang chủ</a></li>
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('products.index') }}" class="text-gray-600 hover:text-red-600">Sản phẩm</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 font-medium">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <!-- Product Image & Thumbnails -->
        <div class="flex gap-4 items-start sticky top-24">
            <!-- Main Image (Bên phải) -->
            <div class="flex-1 bg-gray-50 rounded-2xl overflow-hidden relative" id="mainImageWrap"
                 style="cursor: zoom-in;"
                 onmouseenter="startZoom(event, this)" onmousemove="moveZoom(event, this)" onmouseleave="stopZoom(this)">
                @if(str_starts_with($product->image, 'dummy_url|'))
                    <img id="mainProductImage" src="{{ str_replace('dummy_url|', '', $product->image) }}"
                @else
                    <img id="mainProductImage" src="{{ asset('storage/' . $product->image) }}"
                @endif
                     alt="{{ $product->name }}"
                     class="w-full h-auto object-cover transition-transform duration-300"
                     style="transform-origin: 0 0;">
                <!-- Zoom lens hint -->
                <div class="absolute bottom-3 right-3 bg-black/50 text-white text-xs px-2 py-1 rounded-full flex items-center gap-1 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity" id="zoomHint">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                    Phóng to
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div>
            <div class="bg-white rounded-lg shadow border border-gray-200 p-8">
                <div class="text-sm text-red-600 font-medium mb-2">{{ $product->category->name }}</div>
                <h1 class="text-3xl font-bold mb-4 text-gray-900">{{ $product->name }}</h1>
                
                <!-- Rating & Reviews Hidden -->
                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-200">
                    <span class="text-gray-600 text-sm">Đã bán {{ $product->sold_count ?? 0 }}</span>
                </div>
                
                <!-- Price -->
                <div class="bg-gray-50 p-6 rounded-lg mb-6">
                    @if($product->sale_price)
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-gray-400 line-through text-xl">{{ number_format($product->price) }}đ</span>
                            <span class="bg-red-600 text-white px-2 py-1 rounded text-xs font-bold">
                                -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                            </span>
                        </div>
                        <div class="text-red-600 font-bold text-4xl">{{ number_format($product->sale_price) }}đ</div>
                    @else
                        <div class="text-red-600 font-bold text-4xl">{{ number_format($product->price) }}đ</div>
                    @endif
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <h3 class="font-bold text-gray-900 mb-2">Mô tả sản phẩm</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                </div>

                <!-- Stock Status -->
                <div class="mb-6">
                    @if($product->stock > 0)
                        <div class="flex items-center gap-2 text-green-600">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold">Còn hàng ({{ $product->stock }} sản phẩm)</span>
                        </div>
                    @else
                        <div class="flex items-center gap-2 text-red-600">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold">Hết hàng</span>
                        </div>
                    @endif
                </div>

                @if($product->stock > 0)
                <!-- Add to Cart Form -->
                <form action="{{ route('cart.add') }}" method="POST" onsubmit="return ajaxAddToCart(this)">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <!-- Variants Selection -->
                    <div class="mb-6 space-y-6">
                        @if($product->colors && count($product->colors) > 0)
                        @php
                            $uniqueColors = array_values(array_unique($product->colors));
                            $colorMap = [
                                'Đen' => '#111111', 'Trắng' => '#ffffff', 'Đỏ' => '#ef4444', 
                                'Xanh dương' => '#3b82f6', 'Vàng' => '#eab308', 'Xanh lá' => '#22c55e', 
                                'Xám' => '#6b7280', 'Hồng' => '#ec4899', 'Tím' => '#a855f7', 
                                'Cam' => '#f97316', 'Nâu' => '#78350f', 'Be' => '#d2b48c', 
                                'Xanh Navy' => '#1e3a8a', 'Xanh than' => '#1c2d4a', 'Ghi' => '#9ca3af',
                                'Xanh Jean Nhạt' => '#5b85b8', 'Xanh Jean Đậm' => '#1a3a5c',
                                'Xám Khói' => '#8a8f9c', 'Xanh Rêu' => '#4a7c5e', 'Kem' => '#f5e6c8',
                                'Xám Đậm' => '#374151', 'Xám Nhạt' => '#d1d5db', 'Xanh Lá' => '#16a34a',
                            ];
                            $firstColor = $uniqueColors[0] ?? '';
                            $firstColorName = preg_match('/^#[a-f0-9]{6}$/i', $firstColor) ? 'Màu 1' : $firstColor;
                        @endphp
                        <div>
                            <label class="block font-bold text-gray-900 mb-3">Màu sắc <span class="text-sm font-bold text-red-600 ml-2" id="selectedColorName">{{ $firstColorName }}</span></label>
                            <div class="flex flex-wrap gap-3">
                                @php
                                    // Build image array for JS
                                    $allImgsForJs = [];
                                    $allImgsForJs[] = str_starts_with($product->image, 'dummy_url|') ? str_replace('dummy_url|', '', $product->image) : asset('storage/' . $product->image);
                                    if (is_array($product->images)) {
                                        foreach($product->images as $img) {
                                            $allImgsForJs[] = str_starts_with($img, 'dummy_url|') ? str_replace('dummy_url|', '', $img) : asset('storage/' . $img);
                                        }
                                    }
                                @endphp
                                @foreach($uniqueColors as $index => $color)
                                @php
                                    $hex = $colorMap[$color] ?? (preg_match('/^#[a-f0-9]{6}$/i', $color) ? $color : '#cccccc');
                                    $colorName = preg_match('/^#[a-f0-9]{6}$/i', $color) ? 'Màu ' . ($index + 1) : $color;
                                    
                                    // Bất kể số lượng ảnh, luôn gán theo vị trí màu (index)
                                    $imgCount = count($allImgsForJs);
                                    $imgForColor = $index < $imgCount ? $allImgsForJs[$index] : ($imgCount > 0 ? $allImgsForJs[0] : '');
                                    
                                    // Truyền thẳng $index vào JS để thumbnail đổi tương ứng
                                    $onchangeJs = "changeColorImage('" . addslashes($imgForColor) . "', '" . addslashes($colorName) . "', " . $index . ")";
                                @endphp
                                <label class="cursor-pointer" title="{{ $colorName }}">
                                    <input type="radio" name="color" value="{{ $color }}" class="peer sr-only" required {{ $index == 0 ? 'checked' : '' }}
                                           onchange="{{ $onchangeJs }}">
                                    <!-- Vòng viền ngoài sẽ hiện khi checked -->
                                    <div class="w-11 h-11 rounded-full border-2 border-transparent peer-checked:border-red-600 peer-checked:shadow-lg p-[3px] transition-all flex items-center justify-center hover:scale-110">
                                       <!-- Hình tròn màu bên trong -->
                                       <div class="w-full h-full rounded-full border border-gray-200 shadow-sm" style="background-color: {{ $hex }}"></div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @php
                            $catName = mb_strtolower($product->category->name, 'UTF-8');
                            $isNoSizeCat = str_contains($catName, 'mũ') || str_contains($catName, 'thắt lưng') || str_contains($catName, 'phụ kiện') || str_contains($catName, 'ví');
                        @endphp

                        @if(!$isNoSizeCat && $product->sizes && count($product->sizes) > 0)
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <label class="font-bold text-gray-900">Kích thước</label>
                                <button type="button" onclick="toggleSizeGuide()" class="text-sm text-blue-600 hover:underline">Hướng dẫn chọn size</button>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                @foreach($product->sizes as $index => $size)
                                <label class="cursor-pointer">
                                    <input type="radio" name="size" value="{{ $size }}" class="peer sr-only" required {{ $index == 0 ? 'checked' : '' }}>
                                    <div class="w-12 h-12 flex items-center justify-center border border-gray-300 rounded-full peer-checked:border-blue-600 peer-checked:text-blue-600 peer-checked:font-bold hover:border-gray-400 text-gray-700 transition-all bg-white">
                                        {{ $size }}
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-4 mb-6">
                        <label class="font-bold text-gray-900">Số lượng</label>
                        <div class="flex items-center border-2 border-gray-300 rounded overflow-hidden">
                            <button type="button" onclick="decrementQuantity()" class="w-10 h-10 flex items-center justify-center bg-gray-100 hover:bg-gray-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-16 h-10 text-center border-0 focus:ring-0 font-bold">
                            <button type="button" onclick="incrementQuantity()" class="w-10 h-10 flex items-center justify-center bg-gray-100 hover:bg-gray-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 bg-red-600 text-white px-6 py-4 rounded hover:bg-red-700 font-bold text-lg transition-all flex items-center justify-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            THÊM VÀO GIỎ
                        </button>
                        <button type="button" onclick="addToWishlist({{ $product->id }})" class="w-12 h-12 border-2 border-red-600 text-red-600 rounded hover:bg-red-50 transition-colors flex items-center justify-center" id="wishlist-btn">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="wishlist-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
                @endif

                <!-- Return & Shipping Info -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-5 rounded-2xl border border-gray-100">
                    <!-- Return 30 Days -->
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-gray-900 leading-tight">Đổi trả trong 30 ngày</div>
                            <div class="text-[11px] text-gray-500 leading-tight">kể từ ngày mua hàng</div>
                        </div>
                    </div>

                    <!-- Free Return -->
                    <div class="flex items-center gap-3 md:border-l md:border-gray-200 md:pl-4">
                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-gray-900 leading-tight">Miễn phí 100%</div>
                            <div class="text-[11px] text-gray-500 leading-tight">đổi hàng</div>
                        </div>
                    </div>

                    <!-- Free Shipping -->
                    <div class="flex items-center gap-3 md:border-l md:border-gray-200 md:pl-4">
                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 011 1v2.5a1.5 1.5 0 01-3 0V17a1 1 0 011-1h2zm7-2v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2a1 1 0 011-1h2a1 1 0 011 1zm-3-3V7a1 1 0 00-1-1h-2a1 1 0 00-1 1v4h4z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-gray-900 leading-tight">Miễn phí vận chuyển</div>
                            <div class="text-[11px] text-gray-500 leading-tight">theo chính sách giao hàng</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Details & Reviews Tabs -->
    <div class="mt-16 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex border-b border-gray-100">
            <button onclick="showTab('details')" id="tab-details" class="tab-button active px-8 py-5 text-sm font-bold uppercase tracking-wider text-red-600 border-b-2 border-red-600 transition-all">
                Thông tin chi tiết
            </button>
            <button onclick="showTab('reviews')" id="tab-reviews" class="tab-button px-8 py-5 text-sm font-bold uppercase tracking-wider text-gray-500 hover:text-red-600 transition-all relative">
                Đánh giá 
                @if($product->reviews->count() > 0)
                    <span class="ml-2 bg-red-100 text-red-600 px-2 py-0.5 rounded-full text-[10px]">{{ $product->reviews->count() }}</span>
                @endif
            </button>
        </div>

        <div class="p-8">
            <!-- Details Tab -->
            <div id="content-details" class="tab-content">
                <div class="prose max-w-none text-gray-700 leading-relaxed">
                    {!! $product->content !!}
                </div>
            </div>

            <!-- Reviews Tab -->
            <div id="content-reviews" class="tab-content hidden">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <!-- Rating Summary -->
                    <div class="lg:col-span-1">
                        <h4 class="text-xl font-bold text-gray-900 mb-6">Tổng quan đánh giá</h4>
                        @php
                            $avgRating = $product->reviews->avg('rating') ?? 0;
                            $totalReviews = $product->reviews->count();
                        @endphp
                        <div class="flex items-end gap-4 mb-4">
                            <span class="text-6xl font-black text-gray-900">{{ number_format($avgRating, 1) }}</span>
                            <div class="mb-2">
                                <div class="flex text-yellow-400 mb-1">
                                    @for($i=1; $i<=5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= round($avgRating) ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                                <p class="text-sm text-gray-500 font-medium">{{ $totalReviews }} nhận xét</p>
                            </div>
                        </div>

                        @auth
                            <button onclick="toggleReviewForm()" class="w-full mt-6 py-3 bg-gray-900 text-white rounded-xl font-bold text-sm tracking-wide hover:bg-red-600 transition-all active:scale-95">
                                Viết đánh giá của bạn
                            </button>
                        @else
                            <div class="mt-6 p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                                <p class="text-sm text-gray-500 mb-3">Bạn cần đăng nhập để đánh giá</p>
                                <a href="{{ route('login') }}" class="text-sm font-bold text-red-600 hover:underline">Đăng nhập ngay</a>
                            </div>
                        @endauth
                    </div>

                    <!-- Reviews List -->
                    <div class="lg:col-span-2">
                        <!-- Submission Form -->
                        @auth
                        <div id="reviewForm" class="{{ $errors->any() ? '' : 'hidden' }} mb-12 bg-gray-50 p-6 rounded-2xl border border-gray-100 animate-fadeIn">
                            <h5 class="font-bold text-gray-900 mb-6">Đánh giá sản phẩm này</h5>
                            
                            @if(session('success'))
                                <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-xl text-sm font-medium">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-xl text-sm font-medium">
                                    <ul class="list-disc pl-5">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('products.reviews.store', $product) }}" method="POST">
                                @csrf
                                <div class="mb-6">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Số sao của bạn</label>
                                    <div class="flex gap-2 text-gray-300">
                                        @for($i=1; $i<=5; $i++)
                                        <label class="cursor-pointer group">
                                            <input type="radio" name="rating" value="{{ $i }}" class="peer sr-only" required {{ $i == 5 ? 'checked' : '' }}>
                                            <svg class="w-8 h-8 peer-checked:text-yellow-400 group-hover:text-yellow-300 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </label>
                                        @endfor
                                    </div>
                                </div>
                                <div class="mb-6">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Nhận xét của bạn</label>
                                    <textarea name="comment" rows="4" required class="w-full bg-white border border-gray-200 rounded-xl px-5 py-3 text-sm focus:ring-2 focus:ring-red-500 outline-none" placeholder="Chia sẻ cảm nhận của bạn về sản phẩm..."></textarea>
                                </div>
                                <button type="submit" class="bg-red-600 text-white px-8 py-3 rounded-xl font-bold text-sm tracking-wide hover:bg-red-700 transition-all">
                                    Gửi đánh giá
                                </button>
                            </form>
                        </div>
                        @endauth

                        @if($product->reviews->count() > 0)
                            <div class="space-y-8 divide-y divide-gray-100">
                                @foreach($product->reviews as $review)
                                <div class="pt-8 first:pt-0">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center font-bold text-gray-600">
                                                {{ substr($review->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-900">{{ $review->user->name }}</p>
                                                <p class="text-[11px] text-gray-400 font-medium">{{ $review->created_at->format('d/m/Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex text-yellow-400">
                                            @for($i=1; $i<=5; $i++)
                                                <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed">{{ $review->comment }}</p>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                <p class="text-gray-500 font-medium">Chưa có đánh giá nào cho sản phẩm này.</p>
                                <p class="text-sm text-gray-400 mt-1">Hãy là người đầu tiên chia sẻ cảm nhận!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
    <div class="mt-24">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Sản phẩm liên quan</h2>
                <div class="w-20 h-1.5 bg-red-600 mt-4 rounded-full"></div>
            </div>
            <a href="{{ route('products.index', ['category' => $product->category_id]) }}" class="group flex items-center gap-2 text-sm font-bold text-gray-900 hover:text-red-600 transition-all">
                Xem tất cả 
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
            @foreach($relatedProducts as $relProduct)
                @include('components.product-card-swatch', ['product' => $relProduct])
            @endforeach
        </div>
    </div>
    @endif

</div>

<!-- Floating Chat Buttons -->
<div class="fixed bottom-8 right-8 z-50 flex flex-col gap-4">
    <!-- Messenger Button -->
    <a href="https://m.me/yourpage" target="_blank" class="w-14 h-14 bg-blue-600 text-white rounded-2xl flex items-center justify-center shadow-2xl shadow-blue-600/40 hover:scale-110 hover:-translate-y-1 transition-all group overflow-hidden relative">
        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
        <svg class="w-7 h-7 relative z-10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.145 2 11.258c0 2.908 1.457 5.518 3.732 7.231v3.511c0 .243.218.435.454.382l3.882-1.002a11.107 11.107 0 001.932.169c5.523 0 10-4.145 10-9.258C22 6.145 17.523 2 12 2zm1.061 12.639l-2.548-2.722-4.964 2.722 5.464-5.803 2.613 2.722 4.899-2.722-5.464 5.803z"/></svg>
    </a>
    
    <!-- Zalo Button -->
    <a href="https://zalo.me/0383390611" target="_blank" class="w-14 h-14 bg-[#0068ff] text-white rounded-2xl flex items-center justify-center shadow-2xl shadow-blue-600/30 hover:scale-110 hover:-translate-y-1 transition-all group overflow-hidden relative">
        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
        <span class="font-black text-xs relative z-10">ZALO</span>
    </a>

    <!-- Scroll Top Button -->
    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="w-14 h-14 bg-white text-gray-900 rounded-2xl flex items-center justify-center shadow-xl border border-gray-100 hover:bg-gray-50 transition-all opacity-0 pointer-events-none translate-y-10" id="scrollTopBtn">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
    </button>
</div>

<script>
// ===== Đổi ảnh theo màu sắc =====
function changeColorImage(imgSrc, colorName, imgIndex) {
    const mainImg = document.getElementById('mainProductImage');
    // Fade out → change → fade in
    mainImg.style.opacity = '0';
    mainImg.style.transition = 'opacity 0.2s';
    setTimeout(() => {
        mainImg.src = imgSrc;
        mainImg.style.opacity = '1';
    }, 200);

    // Cập nhật tên màu
    document.getElementById('selectedColorName').innerText = colorName;
}

function changeMainImage(src) {
    const mainImg = document.getElementById('mainProductImage');
    mainImg.style.opacity = '0';
    mainImg.style.transition = 'opacity 0.2s';
    setTimeout(() => { mainImg.src = src; mainImg.style.opacity = '1'; }, 200);
}

// ===== Zoom ảnh khi hover =====
let zooming = false;
function startZoom(e, wrap) {
    zooming = true;
    const img = wrap.querySelector('img');
    img.style.transition = 'transform 0.1s';
    document.getElementById('zoomHint').style.opacity = '1';
    moveZoom(e, wrap);
}
function moveZoom(e, wrap) {
    if (!zooming) return;
    const img = wrap.querySelector('img');
    const rect = wrap.getBoundingClientRect();
    const x = ((e.clientX - rect.left) / rect.width) * 100;
    const y = ((e.clientY - rect.top) / rect.height) * 100;
    const scale = 2.2;
    img.style.transformOrigin = `${x}% ${y}%`;
    img.style.transform = `scale(${scale})`;
}
function stopZoom(wrap) {
    zooming = false;
    const img = wrap.querySelector('img');
    img.style.transform = 'scale(1)';
    img.style.transition = 'transform 0.3s';
    document.getElementById('zoomHint').style.opacity = '0';
}

function incrementQuantity() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.max);
    const current = parseInt(input.value);
    if (current < max) {
        input.value = current + 1;
    }
}

function decrementQuantity() {
    const input = document.getElementById('quantity');
    const min = parseInt(input.min);
    const current = parseInt(input.value);
    if (current > min) {
        input.value = current - 1;
    }
}

function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active', 'border-red-600', 'text-red-600');
    });
    
    // Show selected tab
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Add active class to selected button
    const activeBtn = document.getElementById('tab-' + tabName);
    activeBtn.classList.add('active', 'border-red-600', 'text-red-600');
}

function toggleReviewForm() {
    const form = document.getElementById('reviewForm');
    form.classList.toggle('hidden');
}

function toggleSizeGuide() {
    const modal = document.getElementById('sizeGuideModal');
    if(modal.classList.contains('hidden')) {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('.transform').classList.remove('scale-95');
        }, 10);
    } else {
        modal.classList.add('opacity-0');
        modal.querySelector('.transform').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
}

function addToWishlist(productId) {
    const url = `/yeu-thich/them/${productId}`;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if(response.status === 401) {
            window.location.href = '{{ route('login') }}';
            return;
        }
        return response.json();
    })
    .then(data => {
        if (data && data.success) {
            showToast(data.message, 'success');
            const icon = document.getElementById('wishlist-icon');
            icon.setAttribute('fill', 'currentColor');
            document.getElementById('wishlist-btn').classList.add('bg-red-50');
        }
    })
    .catch(() => showToast('Có lỗi xảy ra!', 'error'));
}

window.addEventListener('scroll', function() {
    const btn = document.getElementById('scrollTopBtn');
    if (window.pageYOffset > 500) {
        btn.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-10');
    } else {
        btn.classList.add('opacity-0', 'pointer-events-none', 'translate-y-10');
    }
});
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<!-- Size Guide Modal -->
<div id="sizeGuideModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden transform scale-95 transition-transform duration-300">
        <div class="bg-gray-900 p-4 flex items-center justify-between">
            <h3 class="text-white font-bold text-lg">Bảng Hướng Dẫn Chọn Size</h3>
            <button type="button" onclick="toggleSizeGuide()" class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto max-h-[70vh]">
            <p class="text-gray-600 mb-6 text-sm">Vui lòng tham khảo bảng kích thước dưới đây để chọn được sản phẩm phù hợp nhất.</p>
            
            @php
                $isPants = str_contains(mb_strtolower($product->category->name, 'UTF-8'), 'quần');
            @endphp
            
            @if($isPants)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 border border-gray-200">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 border-b">Size</th>
                            <th class="px-6 py-3 border-b">Chiều cao (cm)</th>
                            <th class="px-6 py-3 border-b">Cân nặng (kg)</th>
                            <th class="px-6 py-3 border-b">Vòng bụng (cm)</th>
                            <th class="px-6 py-3 border-b">Chiều dài quần (cm)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-gray-900 border-r">S</td>
                            <td class="px-6 py-4">160 - 165</td>
                            <td class="px-6 py-4">50 - 55</td>
                            <td class="px-6 py-4">74 - 76</td>
                            <td class="px-6 py-4">96</td>
                        </tr>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-gray-900 border-r">M</td>
                            <td class="px-6 py-4">165 - 170</td>
                            <td class="px-6 py-4">55 - 60</td>
                            <td class="px-6 py-4">76 - 79</td>
                            <td class="px-6 py-4">98</td>
                        </tr>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-gray-900 border-r">L</td>
                            <td class="px-6 py-4">170 - 175</td>
                            <td class="px-6 py-4">60 - 65</td>
                            <td class="px-6 py-4">79 - 81</td>
                            <td class="px-6 py-4">100</td>
                        </tr>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-gray-900 border-r">XL</td>
                            <td class="px-6 py-4">175 - 180</td>
                            <td class="px-6 py-4">65 - 70</td>
                            <td class="px-6 py-4">81 - 84</td>
                            <td class="px-6 py-4">102</td>
                        </tr>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-gray-900 border-r">XXL</td>
                            <td class="px-6 py-4">180 - 185</td>
                            <td class="px-6 py-4">70 - 75</td>
                            <td class="px-6 py-4">84 - 87</td>
                            <td class="px-6 py-4">104</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 border border-gray-200">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 border-b">Size</th>
                            <th class="px-6 py-3 border-b">Chiều cao (cm)</th>
                            <th class="px-6 py-3 border-b">Cân nặng (kg)</th>
                            <th class="px-6 py-3 border-b">Vòng ngực (cm)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-gray-900 border-r">S</td>
                            <td class="px-6 py-4">155 - 160</td>
                            <td class="px-6 py-4">45 - 50</td>
                            <td class="px-6 py-4">80 - 84</td>
                        </tr>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-gray-900 border-r">M</td>
                            <td class="px-6 py-4">160 - 165</td>
                            <td class="px-6 py-4">50 - 55</td>
                            <td class="px-6 py-4">84 - 88</td>
                        </tr>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-gray-900 border-r">L</td>
                            <td class="px-6 py-4">165 - 170</td>
                            <td class="px-6 py-4">55 - 60</td>
                            <td class="px-6 py-4">88 - 92</td>
                        </tr>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-gray-900 border-r">XL</td>
                            <td class="px-6 py-4">170 - 175</td>
                            <td class="px-6 py-4">60 - 65</td>
                            <td class="px-6 py-4">92 - 96</td>
                        </tr>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-gray-900 border-r">XXL</td>
                            <td class="px-6 py-4">175 - 180</td>
                            <td class="px-6 py-4">65 - 75</td>
                            <td class="px-6 py-4">96 - 100</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @endif
            <div class="mt-6 bg-red-50 text-red-800 p-4 rounded-lg text-sm">
                <strong>Lưu ý:</strong> Nếu số đo của bạn nằm giữa 2 size, chúng tôi khuyên bạn nên chọn size lớn hơn để có cảm giác mặc thoải mái nhất.
            </div>
        </div>
        <div class="bg-gray-50 p-4 border-t border-gray-200 flex justify-end">
            <button type="button" onclick="toggleSizeGuide()" class="bg-gray-200 text-gray-800 px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-300 transition-all">Đóng</button>
        </div>
    </div>
</div>
@endsection
