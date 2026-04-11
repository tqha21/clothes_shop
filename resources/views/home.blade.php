@extends('layouts.storefront')

@section('title', 'Trang Chủ')

@section('content')
<!-- Promotional Banners -->
<div class="container mx-auto px-4 py-6">
    <div class="grid grid-cols-3 gap-4">
        <!-- Main Banner (Left - 2/3 width) -->
        <div class="col-span-2">
            <div class="relative rounded-lg overflow-hidden shadow-lg group cursor-pointer h-[400px]">
                <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1200&h=600&fit=crop" 
                     alt="Tết Âm Giá Ẻm" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-r from-red-900/60 to-transparent flex items-center">
                    <div class="p-12 text-white">
                        <div class="text-yellow-400 text-sm font-bold mb-2">TẾT ÂM GIÁ ẺM</div>
                        <h2 class="text-5xl font-bold mb-4">QUÀ TỚ<br>TRAO THÊM</h2>
                        <p class="text-lg mb-6">Quà tặng Lì xì thưởng cho mùa Tết ấm êm</p>
                        <a href="{{ route('products.index') }}" class="inline-block bg-white text-red-600 px-8 py-3 rounded font-bold hover:bg-yellow-400 hover:text-red-700 transition-colors">
                            XEM NGAY
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side Banners (1/3 width) -->
        <div class="col-span-1 flex flex-col gap-4">
            <!-- Top Banner -->
            <div class="relative rounded-lg overflow-hidden shadow-lg group cursor-pointer h-[192px]">
                <img src="https://images.unsplash.com/photo-1607345366928-199ea26cfe3e?w=600&h=300&fit=crop" 
                     alt="Quà Tớ Trao Thêm" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end">
                    <div class="p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2">QUÀ TỚ<br>TRAO THÊM</h3>
                        <a href="{{ route('products.index', ['sort' => 'best_selling']) }}" class="text-sm bg-white text-red-600 px-4 py-2 rounded inline-block hover:bg-yellow-400 transition-colors">
                            Xem ngay
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bottom Banner -->
            <div class="relative rounded-lg overflow-hidden shadow-lg group cursor-pointer h-[192px]">
                <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=600&h=300&fit=crop" 
                     alt="Lễ Hội" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-pink-900/60 to-transparent flex items-end">
                    <div class="p-6 text-white">
                        <div class="text-yellow-300 text-xs mb-1">Bộ Sưu Tập Mới</div>
                        <h3 class="text-2xl font-bold mb-2">LỄ HỘI</h3>
                        <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="text-sm bg-white text-pink-600 px-4 py-2 rounded inline-block hover:bg-yellow-400 transition-colors">
                            Khám phá
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="border-b border-gray-200 mb-0"></div>

{{-- ========= FLASH SALE ========= --}}
<div class="bg-white py-8">

    <div class="container mx-auto px-4">
        <!-- Flash Sale Header -->
        <div class="bg-red-600 text-white px-6 py-4 rounded-t-lg flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"></path>
                </svg>
                <h2 class="text-3xl font-bold">FLASH SALE</h2>
            </div>
            <div class="text-sm">
                <span class="opacity-80">Kết thúc sau:</span>
                <span class="bg-white text-red-600 px-3 py-1 rounded ml-2 font-bold">23:59:59</span>
            </div>
        </div>

        <!-- Flash Sale Products -->
        <div class="border-x border-b border-gray-200 rounded-b-lg p-6">
            <div class="grid grid-cols-5 gap-4">
                @foreach($newArrivals->take(5) as $product)
                <div class="bg-white border border-gray-200 rounded-lg hover:shadow-lg transition-shadow group">
                    <div class="relative overflow-hidden rounded-t-lg">
                        <a href="{{ route('products.show', $product->slug) }}">
                            @if(str_starts_with($product->image, 'dummy_url|'))
                                <img src="{{ str_replace('dummy_url|', '', $product->image) }}" 
                            @else
                                <img src="{{ asset('storage/' . $product->image) }}" 
                            @endif
                                 alt="{{ $product->name }}" 
                                 class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500">
                        </a>
                        @if($product->sale_price)
                        <div class="absolute top-2 left-2 bg-red-600 text-white px-2 py-1 rounded text-xs font-bold">
                            -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                        </div>
                        @endif
                        {{-- Hover overlay --}}
                        <div class="absolute bottom-0 left-0 right-0 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out z-10">
                            <div class="flex items-center bg-white/95 backdrop-blur-sm border-t border-gray-200">
                                <form action="{{ route('cart.add') }}" method="POST" onsubmit="return ajaxAddToCart(this)" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 text-sm font-semibold text-gray-800 hover:bg-gray-100 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                        THÊM VÀO GIỎ
                                    </button>
                                </form>
                                <div class="w-px h-8 bg-gray-300"></div>
                                <a href="{{ route('products.show', $product->slug) }}" class="flex items-center justify-center px-4 py-3 text-gray-600 hover:text-red-600 hover:bg-gray-100 transition-colors" title="Xem sản phẩm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="p-3">
                        <a href="{{ route('products.show', $product->slug) }}" class="block">
                            <h3 class="text-sm font-medium text-gray-800 mb-2 line-clamp-2 hover:text-red-600 transition-colors">
                                {{ $product->name }}
                            </h3>
                        </a>
                        <div class="flex items-center gap-2 mb-2">
                            @if($product->sale_price)
                                <span class="text-red-600 font-bold text-lg">{{ number_format($product->sale_price) }}đ</span>
                                <span class="text-gray-400 line-through text-sm">{{ number_format($product->price) }}đ</span>
                            @else
                                <span class="text-red-600 font-bold text-lg">{{ number_format($product->price) }}đ</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-1 text-xs text-gray-500">
                            <span>Đã bán {{ $product->sold_count ?? rand(10, 100) }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>


<!-- Đã ẩn mục BẠN CẦN TÌM  -->
{{-- ========= SƠ MI NANO ========= --}}
@if(isset($somiProducts) && $somiProducts->count() > 0)
<div class="bg-gray-50 py-8 border-t border-gray-200">
    <div class="container mx-auto px-4">
        <!-- Banner ngang -->
        <div class="mb-6 rounded-lg overflow-hidden relative shadow-md">
            <!-- Nền màu hoặc ảnh banner (Ảnh Placeholder cho nhẹ) -->
            <img src="https://images.unsplash.com/photo-1598033129183-c4f50c736f10?w=1200&h=300&fit=crop&crop=center" class="w-full h-[200px] md:h-[250px] object-cover" alt="Sơ mi Nano">
            <div class="absolute inset-0 bg-gradient-to-r from-red-800/90 via-red-800/50 to-transparent flex items-center">
                <div class="p-8 md:px-16 text-white max-w-2xl">
                    <h2 class="text-4xl md:text-6xl font-black mb-2 uppercase tracking-tight text-white drop-shadow-lg">SƠ MI NANO</h2>
                    <p class="text-lg md:text-2xl font-medium mb-6 text-red-50">Chỉn chu lịch thiệp - Không lo gò bó</p>
                    <a href="{{ route('products.index', ['category' => $categorySomi->id ?? '']) }}" class="inline-flex items-center gap-2 bg-white text-red-600 px-6 py-2.5 rounded-full font-bold hover:bg-yellow-400 hover:text-red-700 transition-colors uppercase text-sm shadow">
                        Xem tất cả
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </div>
        <!-- Card grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($somiProducts as $product)
                <x-product-card-swatch :product="$product" badge="ÁO SƠ MI GIẢM 30%" />
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- ========= ÁO POLO ========= --}}
@if(isset($poloProducts) && $poloProducts->count() > 0)
<div class="bg-white py-8 border-t border-gray-200">
    <div class="container mx-auto px-4">
        <!-- Banner ngang -->
        <div class="mb-6 rounded-lg overflow-hidden relative shadow-md">
            <img src="{{ asset('storage/banners/polo_banner.png') }}" class="w-full h-[200px] md:h-[250px] object-cover" alt="Áo Polo">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/90 via-blue-900/50 to-transparent flex items-center">
                <div class="p-8 md:px-16 text-white max-w-2xl">
                    <h2 class="text-4xl md:text-6xl font-black mb-2 uppercase tracking-tight drop-shadow-lg">ÁO POLO DÁNG ĐẸP</h2>
                    <p class="text-lg md:text-2xl font-medium mb-6 text-blue-50">Gọn gàng lịch sự - Tự tin đón ngày mới</p>
                    <a href="{{ route('products.index', ['category' => $categoryPolo->id ?? '']) }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-2.5 rounded-full font-bold hover:bg-yellow-400 hover:text-blue-900 transition-colors uppercase text-sm shadow">
                        Khám phá
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </div>
        <!-- Card grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($poloProducts as $product)
                <x-product-card-swatch :product="$product" badge="ĐỘC QUYỀN ONLINE" />
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- ========= BST JEANS FLEX ========= --}}
@if(isset($jeansProducts) && $jeansProducts->count() > 0)
<div class="bg-gray-50 py-8 border-t border-gray-200">
    <div class="container mx-auto px-4">
        <!-- Banner ngang -->
        <div class="mb-6 rounded-lg overflow-hidden relative shadow-md">
            <!-- Nền màu hoặc ảnh banner (Ảnh Placeholder cho nhẹ) -->
            <img src="https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=1200&h=300&fit=crop&crop=center" class="w-full h-[200px] md:h-[250px] object-cover" alt="Jeans Flex">
            <div class="absolute inset-0 bg-gradient-to-r from-red-800/90 via-red-800/50 to-transparent flex items-center">
                <div class="p-8 md:px-16 text-white max-w-2xl">
                    <h2 class="text-4xl md:text-6xl font-black mb-2 uppercase tracking-tight text-white drop-shadow-lg">BST JEANS FLEX</h2>
                    <p class="text-lg md:text-2xl font-medium mb-6 text-red-50">Co giãn tối đa - Thoải mái vận động</p>
                    <a href="{{ route('products.index', ['category' => $categoryJeans->id ?? '']) }}" class="inline-flex items-center gap-2 bg-white text-red-600 px-6 py-2.5 rounded-full font-bold hover:bg-yellow-400 hover:text-red-700 transition-colors uppercase text-sm shadow">
                        Mua ngay
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </div>
        <!-- Card grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($jeansProducts as $product)
                <x-product-card-swatch :product="$product" badge="MỚI RA MẮT" />
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- ========= QUẦN ÂU THANH LỊCH ========= --}}
@if(isset($quanAuProducts) && $quanAuProducts->count() > 0)
<div class="bg-white py-8 border-t border-gray-200">
    <div class="container mx-auto px-4">
        <!-- Banner ngang -->
        <div class="mb-6 rounded-lg overflow-hidden relative shadow-md">
            <img src="{{ asset('storage/banners/quan_au_banner.png') }}" class="w-full h-[200px] md:h-[250px] object-cover" alt="Quần Âu">
            <div class="absolute inset-0 bg-gradient-to-r from-red-700/90 via-red-700/50 to-transparent flex items-center">
                <div class="p-8 md:px-16 text-white max-w-2xl">
                    <h2 class="text-4xl md:text-6xl font-black mb-2 uppercase tracking-tight drop-shadow-lg">QUẦN ÂU THANH LỊCH</h2>
                    <p class="text-lg md:text-2xl font-medium mb-6 text-red-50">Chuẩn form bền dáng - Tự tin sải bước</p>
                    <a href="{{ route('products.index', ['category' => $categoryQuanAu->id ?? '']) }}" class="inline-flex items-center gap-2 bg-red-600 text-white border border-white px-6 py-2.5 rounded-full font-bold hover:bg-white hover:text-red-700 transition-colors uppercase text-sm shadow">
                        Khám phá
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </div>
        <!-- Card grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($quanAuProducts as $product)
                <x-product-card-swatch :product="$product" badge="BÁN CHẠY" />
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- ========= ÁO THUN BASIC ========= --}}
@if(isset($aoThunProducts) && $aoThunProducts->count() > 0)
<div class="bg-gray-50 py-8 border-t border-gray-200">
    <div class="container mx-auto px-4">
        <!-- Banner ngang -->
        <div class="mb-6 rounded-lg overflow-hidden relative shadow-md">
            <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=1200&h=300&fit=crop&crop=center" class="w-full h-[200px] md:h-[250px] object-cover" alt="Áo thun Basic">
            <div class="absolute inset-0 bg-gradient-to-r from-red-600/90 via-red-600/50 to-transparent flex items-center">
                <div class="p-8 md:px-16 text-white max-w-2xl">
                    <h2 class="text-4xl md:text-6xl font-black mb-2 uppercase tracking-tight text-white drop-shadow-lg">ÁO THUN BASIC</h2>
                    <p class="text-lg md:text-2xl font-medium mb-6 text-red-50">Đa dạng linh hoạt - Không lo về giá</p>
                    <a href="{{ route('products.index', ['category' => $categoryAoThun->id ?? '']) }}" class="inline-flex items-center gap-2 bg-yellow-400 text-red-900 px-6 py-2.5 rounded-full font-bold hover:bg-white transition-colors uppercase text-sm shadow">
                        Mua ngay
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </div>
        <!-- Card grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($aoThunProducts as $product)
                <x-product-card-swatch :product="$product" badge="MUA NHIỀU GIẢM SÂU" />
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- ========= ÁO PHAO 4S ========= --}}
@if(isset($aoPhaoProducts) && $aoPhaoProducts->count() > 0)
<div class="bg-white py-8 border-t border-gray-200">
    <div class="container mx-auto px-4">
        <!-- Banner ngang -->
        <div class="mb-6 rounded-lg overflow-hidden relative shadow-md">
            <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?w=1200&h=300&fit=crop&crop=center" class="w-full h-[200px] md:h-[250px] object-cover" alt="Áo Phao">
            <div class="absolute inset-0 bg-gradient-to-r from-yellow-700/90 via-yellow-700/50 to-transparent flex items-center">
                <div class="p-8 md:px-16 text-white max-w-2xl">
                    <h2 class="text-4xl md:text-6xl font-black mb-2 uppercase tracking-tight drop-shadow-lg">ÁO PHAO 4S</h2>
                    <p class="text-lg md:text-2xl font-medium mb-6 text-yellow-50">Siêu ấm - Siêu nhẹ - Siêu linh hoạt</p>
                    <a href="{{ route('products.index', ['category' => $categoryAoPhao->id ?? '']) }}" class="inline-flex items-center gap-2 bg-yellow-500 text-yellow-950 px-6 py-2.5 rounded-full font-bold hover:bg-white hover:text-yellow-700 transition-colors uppercase text-sm shadow">
                        Sắm ngay
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </div>
        <!-- Card grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($aoPhaoProducts as $product)
                <x-product-card-swatch :product="$product" badge="-50%" />
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- ========= PHỤ KIỆN TRẺ EM ========= --}}
@if(isset($phuKienProducts) && $phuKienProducts->count() > 0)
<div class="bg-gray-50 py-8 border-t border-gray-200">
    <div class="container mx-auto px-4">
        <!-- Banner ngang -->
        <div class="mb-6 rounded-lg overflow-hidden relative shadow-md">
            <img src="https://images.unsplash.com/photo-1519689680058-324335c77eba?w=1200&h=300&fit=crop&crop=center" class="w-full h-[200px] md:h-[250px] object-cover" alt="Phụ Kiện Trẻ Em">
            <div class="absolute inset-0 bg-gradient-to-r from-pink-500/90 via-pink-400/50 to-transparent flex items-center">
                <div class="p-8 md:px-16 text-white max-w-2xl">
                    <h2 class="text-4xl md:text-6xl font-black mb-2 uppercase tracking-tight drop-shadow-lg">PHỤ KIỆN TRẺ EM</h2>
                    <p class="text-lg md:text-2xl font-medium mb-6 text-pink-50">Xinh xắn, Đáng yêu - An toàn cho bé</p>
                    <a href="{{ route('products.index', ['category' => $categoryPhuKien->id ?? '']) }}" class="inline-flex items-center gap-2 bg-white text-pink-600 px-6 py-2.5 rounded-full font-bold hover:bg-yellow-400 hover:text-pink-700 transition-colors uppercase text-sm shadow">
                        Mua sắm ngay
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </div>
        <!-- Card grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($phuKienProducts as $product)
                <x-product-card-swatch :product="$product" badge="NỔI BẬT" />
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- New Arrivals Section -->
<div class="container mx-auto px-4 py-12">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Hàng Mới Về</h2>
        <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="text-red-600 hover:text-red-700 font-medium flex items-center gap-2">
            Xem tất cả
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>

    <div class="grid grid-cols-5 gap-4">
        @foreach($newArrivals->skip(5)->take(10) as $product)
        <div class="bg-white border border-gray-200 rounded-lg hover:shadow-lg transition-shadow group">
            <div class="relative overflow-hidden rounded-t-lg">
                <a href="{{ route('products.show', $product->slug) }}">
                    @if(str_starts_with($product->image, 'dummy_url|'))
                        <img src="{{ str_replace('dummy_url|', '', $product->image) }}" 
                    @else
                        <img src="{{ asset('storage/' . $product->image) }}" 
                    @endif
                         alt="{{ $product->name }}" 
                         class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500">
                </a>
                @if($product->sale_price)
                <div class="absolute top-2 left-2 bg-red-600 text-white px-2 py-1 rounded text-xs font-bold">
                    -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                </div>
                @endif
                {{-- Hover overlay --}}
                <div class="absolute bottom-0 left-0 right-0 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out z-10">
                    <div class="flex items-center bg-white/95 backdrop-blur-sm border-t border-gray-200">
                        <form action="{{ route('cart.add') }}" method="POST" onsubmit="return ajaxAddToCart(this)" class="flex-1">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 text-sm font-semibold text-gray-800 hover:bg-gray-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                THÊM VÀO GIỎ
                            </button>
                        </form>
                        <div class="w-px h-8 bg-gray-300"></div>
                        <a href="{{ route('products.show', $product->slug) }}" class="flex items-center justify-center px-4 py-3 text-gray-600 hover:text-red-600 hover:bg-gray-100 transition-colors" title="Xem sản phẩm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="p-3">
                <a href="{{ route('products.show', $product->slug) }}" class="block">
                    <h3 class="text-sm font-medium text-gray-800 mb-2 line-clamp-2 hover:text-red-600 transition-colors">
                        {{ $product->name }}
                    </h3>
                </a>
                <div class="flex items-center gap-2">
                    @if($product->sale_price)
                        <span class="text-red-600 font-bold text-lg">{{ number_format($product->sale_price) }}đ</span>
                        <span class="text-gray-400 line-through text-sm">{{ number_format($product->price) }}đ</span>
                    @else
                        <span class="text-red-600 font-bold text-lg">{{ number_format($product->price) }}đ</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Fashion News Section -->
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-red-600 mb-2">TIN THỜI TRANG</h2>
            <p class="text-gray-600">Cập nhật xu hướng thời trang mới nhất</p>
        </div>

        <div class="grid grid-cols-5 gap-6">
            @php
            $fashionNews = \App\Models\Post::where('is_published', true)
                ->orderBy('published_at', 'desc')
                ->limit(5)
                ->get();
            @endphp

            @foreach($fashionNews as $news)
            <div class="bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition-shadow">
                <a href="{{ route('posts.show', $news->slug) }}">
                    <img src="{{ $news->image }}" alt="{{ $news->title }}" class="w-full h-48 object-cover hover:scale-105 transition-transform duration-300">
                </a>
                <div class="p-4">
                    <a href="{{ route('posts.show', $news->slug) }}">
                        <h3 class="font-bold text-sm mb-2 line-clamp-2 hover:text-red-600 transition-colors cursor-pointer">
                            {{ $news->title }}
                        </h3>
                    </a>
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span>{{ $news->published_at->format('d/m/Y') }}</span>
                        <a href="{{ route('posts.show', $news->slug) }}" class="text-red-600 hover:text-red-700 font-medium">XEM THÊM</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Newsletter Section -->
<div class="bg-red-600 py-12">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between max-w-4xl mx-auto">
            <div class="flex items-center gap-4 text-white">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <div>
                    <h3 class="text-2xl font-bold mb-1">ĐĂNG KÝ NHẬN THÔNG TIN</h3>
                    <p class="text-red-100">Để nhận những thông tin khuyến mãi, chất khởi hấp dẫn</p>
                </div>
            </div>
            <form class="flex gap-2">
                <input type="email" 
                       placeholder="hello.vip@.dien.thoai" 
                       class="px-6 py-3 rounded-full w-80 focus:outline-none focus:ring-2 focus:ring-white">
                <button type="submit" class="bg-white text-red-600 px-8 py-3 rounded-full font-bold hover:bg-yellow-400 hover:text-red-700 transition-colors flex items-center gap-2">
                    ĐĂNG KÝ
                </button>
            </form>
            <div class="flex gap-3">
                <a href="#" class="w-10 h-10 bg-white text-red-600 rounded-full flex items-center justify-center hover:bg-yellow-400 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                <a href="#" class="w-10 h-10 bg-white text-red-600 rounded-full flex items-center justify-center hover:bg-yellow-400 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                </a>
                <a href="#" class="w-10 h-10 bg-white text-red-600 rounded-full flex items-center justify-center hover:bg-yellow-400 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"/></svg>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
