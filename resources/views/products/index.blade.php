@extends('layouts.storefront')

@section('title', 'Sản Phẩm')

@php
    $currentGender = request('gender', '');
    $genderLabel = [
        '' => 'Tất cả sản phẩm',
        'nam' => 'Sản phẩm Nam',
        'nu' => 'Sản phẩm Nữ',
        'be-gai' => 'Sản phẩm Bé Gái',
        'be-trai' => 'Sản phẩm Bé Trai',
    ];
    $pageTitle = $genderLabel[$currentGender] ?? 'Sản Phẩm';
@endphp

@section('content')
@if(request('sort') === 'sale')
{{-- High Energy Flash Sale Hero --}}
<div class="relative bg-gray-900 overflow-hidden mb-12">
    <!-- Animated background particles -->
    <div class="absolute inset-0 opacity-30 pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-full flash-particles"></div>
    </div>
    
    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-r from-red-600/80 via-red-900/90 to-black/80"></div>
    
    <div class="container mx-auto px-4 py-16 relative z-10 text-center">
        <div class="inline-block px-4 py-1 bg-yellow-400 text-red-700 font-black rounded-full text-sm mb-6 animate-bounce">
            HOT DEAL NOW
        </div>
        
        <h1 class="text-6xl md:text-8xl font-black text-white mb-6 tracking-tighter italic flaming-text uppercase">
            Flash <span class="text-yellow-400">Sale</span>
        </h1>
        
        <p class="text-xl md:text-2xl text-red-100 mb-10 font-bold tracking-wide uppercase opacity-90">
            Giảm giá cực sâu • Số lượng có hạn • Chỉ còn hôm nay
        </p>
        
        <!-- Countdown Timer -->
        <div class="flex justify-center items-center gap-4 md:gap-8 mb-12" id="flash-countdown">
            <div class="flex flex-col">
                <div class="bg-white text-gray-900 w-16 h-16 md:w-24 md:h-24 rounded-2xl flex items-center justify-center text-3xl md:text-5xl font-black shadow-2xl countdown-item" data-unit="hours">00</div>
                <span class="text-white text-xs md:text-sm font-bold mt-2 uppercase">Giờ</span>
            </div>
            <div class="text-white text-4xl font-black">:</div>
            <div class="flex flex-col">
                <div class="bg-white text-gray-900 w-16 h-16 md:w-24 md:h-24 rounded-2xl flex items-center justify-center text-3xl md:text-5xl font-black shadow-2xl countdown-item" data-unit="minutes">00</div>
                <span class="text-white text-xs md:text-sm font-bold mt-2 uppercase">Phút</span>
            </div>
            <div class="text-white text-4xl font-black">:</div>
            <div class="flex flex-col">
                <div class="bg-yellow-400 text-red-600 w-16 h-16 md:w-24 md:h-24 rounded-2xl flex items-center justify-center text-3xl md:text-5xl font-black shadow-2xl scale-110 countdown-item" data-unit="seconds">00</div>
                <span class="text-white text-xs md:text-sm font-bold mt-2 uppercase">Giây</span>
            </div>
        </div>
        
        <div class="flex flex-wrap justify-center gap-4">
            <div class="bg-black/40 backdrop-blur-md px-6 py-3 rounded-full border border-white/20 text-white flex items-center gap-3">
                <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"></path></svg>
                <span class="font-bold">Hơn 500+ mặt hàng giảm giá</span>
            </div>
        </div>
    </div>
    
    <!-- Wave Bottom -->
    <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none">
        <svg class="relative block w-full h-[40px]" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C58.47,101.48,124.64,115.68,187.52,104.91A1112.51,1112.51,0,0,0,321.39,56.44Z" class="fill-gray-50"></path>
        </svg>
    </div>
</div>

<style>
    .flaming-text {
        text-shadow: 0 0 10px rgba(255, 255, 255, 0.5), 0 0 20px rgba(255, 165, 0, 0.8), 0 0 30px rgba(255, 69, 0, 1), 0 0 40px rgba(255, 0, 0, 1);
        animation: text-pulse 2s infinite alternate;
    }
    
    @keyframes text-pulse {
        0% { transform: scale(1); text-shadow: 0 0 10px rgba(255, 165, 0, 0.5); }
        100% { transform: scale(1.02); text-shadow: 0 0 30px rgba(255, 69, 0, 1), 0 0 50px rgba(255, 0, 0, 1); }
    }
    
    .flash-particles {
        background-image: radial-gradient(circle, white 1px, transparent 1px);
        background-size: 50px 50px;
        animation: snow 20s linear infinite;
    }
    
    @keyframes snow {
        from { background-position: 0 0; }
        to { background-position: 500px 1000px; }
    }
    
    .countdown-item {
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    #flash-countdown .countdown-item:hover {
        transform: scale(1.1) rotate(2deg);
    }
</style>

<script>
    function updateFlashCountdown() {
        const now = new Date();
        const endOfDay = new Date();
        endOfDay.setHours(23, 59, 59, 999);
        
        const diff = endOfDay - now;
        
        if (diff <= 0) {
            document.querySelectorAll('.countdown-item').forEach(el => el.innerText = '00');
            return;
        }
        
        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
        
        document.querySelector('[data-unit="hours"]').innerText = String(hours).padStart(2, '0');
        document.querySelector('[data-unit="minutes"]').innerText = String(minutes).padStart(2, '0');
        document.querySelector('[data-unit="seconds"]').innerText = String(seconds).padStart(2, '0');
    }
    
    setInterval(updateFlashCountdown, 1000);
    updateFlashCountdown();
</script>
@endif

@if(request('sort') === 'newest')
{{-- Chic & Modern New Arrivals Hero --}}
<div class="relative bg-white overflow-hidden mb-12 border-b border-gray-100">
    <!-- Subtle background pattern -->
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none">
        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="currentColor" stroke-width="1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid)" />
        </svg>
    </div>
    
    <div class="container mx-auto px-4 py-20 relative z-10 text-center">
        <div class="flex flex-col items-center">
            <span class="text-red-600 font-bold tracking-[0.3em] uppercase text-[10px] mb-4 animate-fade-in-down">
                BST XUÂN HÈ 2026
            </span>
            
            <h1 class="font-display text-5xl md:text-7xl text-gray-900 mb-6 italic tracking-tight animate-float">
                New <span class="font-normal text-gray-400">Arrivals</span>
            </h1>
            
            <div class="w-20 h-px bg-gray-200 mb-8"></div>
            
            <p class="max-w-xl text-gray-500 text-lg leading-relaxed mb-10 font-light italic">
                Khám phá những thiết kế mới nhất, mang đậm hơi thở thời đại và sự tinh tế trong từng đường nét.
            </p>
            
            <div class="flex items-center gap-6 justify-center">
                <div class="flex -space-x-3 overflow-hidden">
                    <img class="inline-block h-12 w-12 rounded-full ring-4 ring-white object-cover" src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=100&h=100&fit=crop" alt="">
                    <img class="inline-block h-12 w-12 rounded-full ring-4 ring-white object-cover" src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=100&h=100&fit=crop" alt="">
                    <img class="inline-block h-12 w-12 rounded-full ring-4 ring-white object-cover" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&h=100&fit=crop" alt="">
                </div>
                <p class="text-sm font-medium text-gray-600">
                    <span class="text-gray-900 font-bold">1,000+</span> người đã trải nghiệm
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
    
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down {
        animation: fadeInDown 1s ease-out forwards;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
        opacity: 0;
    }
</style>
@endif

<!-- Breadcrumb -->
<div class="bg-gray-50 py-4">
    <div class="container mx-auto px-4">
        <nav class="text-sm">
            <ol class="flex items-center gap-2">
                <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-red-600">Trang chủ</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 font-medium">{{ $pageTitle }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    @if(request('sort') === 'newest')
    {{-- Editorial Lookbook: Horizontal Filter --}}
    <div class="mb-12">
        <div class="flex flex-wrap items-center justify-between gap-6 pb-6 border-b border-gray-100">
            <h2 class="text-2xl font-display italic text-gray-900">Mới nhất từ <span class="font-bold">HTD Store</span></h2>
            
            <div class="flex items-center gap-4">
                <form action="{{ route('products.index') }}" method="GET" class="flex items-center gap-3">
                    <input type="hidden" name="sort" value="newest">
                    @if(request('gender')) <input type="hidden" name="gender" value="{{ request('gender') }}"> @endif
                    
                    <select name="category" onchange="this.form.submit()" class="bg-gray-50 border-none text-sm font-medium rounded-full px-6 py-2 focus:ring-1 focus:ring-gray-200">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <div class="h-4 w-px bg-gray-200"></div>

                    <div class="flex items-center bg-gray-50 rounded-full px-4 py-1.5">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mr-2">Khoảng giá</span>
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Từ" class="w-20 bg-transparent border-none p-0 text-xs focus:ring-0">
                        <span class="mx-2 text-gray-300">-</span>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Đến" class="w-20 bg-transparent border-none p-0 text-xs focus:ring-0">
                        <button type="submit" class="ml-2 text-gray-400 hover:text-gray-900 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </form>

                @if(request()->hasAny(['category','min_price','max_price']))
                <a href="{{ route('products.index', ['sort' => 'newest', 'gender' => request('gender')]) }}" class="text-[10px] font-black text-red-600 hover:text-red-700 uppercase tracking-widest bg-red-50 px-4 py-2 rounded-full transition-all">
                    Xóa lọc
                </a>
                @endif
            </div>
        </div>
    </div>
    @endif

    <div class="flex gap-6 @if(request('sort') === 'newest') flex-col @endif">
        @if(request('sort') !== 'newest')
        <!-- Sidebar Filter -->
        <div class="w-72 flex-shrink-0">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 sticky top-24 overflow-hidden">

                <!-- Header -->
                <div class="bg-gradient-to-r from-gray-900 to-gray-800 px-5 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                        </div>
                        <span class="font-bold text-white text-sm tracking-wide">BỘ LỌC SẢN PHẨM</span>
                    </div>
                    @if(request()->hasAny(['category','min_price','max_price','sort']) || $currentGender)
                    <a href="{{ route('products.index') }}" class="text-[10px] font-bold text-red-400 hover:text-red-300 uppercase tracking-wider transition-colors">
                        Đặt lại
                    </a>
                    @endif
                </div>

                <form method="GET" id="filterForm" class="divide-y divide-gray-100">
                    {{-- Giữ gender param --}}
                    @if(request('gender'))
                    <input type="hidden" name="gender" value="{{ request('gender') }}">
                    @endif

                    {{-- ===== NHÓM ===== --}}
                    <div class="p-5">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Nhóm khách hàng</p>
                        <div class="grid grid-cols-2 gap-2">
                            @php
                            $groups = [
                                '' => ['label' => 'Tất cả', 'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'],
                                'nam' => ['label' => 'Nam', 'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>'],
                                'nu' => ['label' => 'Nữ', 'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"/></svg>'],
                                'be-gai' => ['label' => 'Bé Gái', 'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>'],
                                'be-trai' => ['label' => 'Bé Trai', 'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'],
                            ];
                            @endphp
                            @foreach($groups as $key => $group)
                            <a href="{{ $key ? route('products.index', ['gender'=>$key]) : route('products.index') }}"
                               class="flex flex-col items-center gap-1.5 px-3 py-3 rounded-xl text-xs font-semibold transition-all border-2
                                      {{ $currentGender === $key
                                         ? 'bg-red-600 text-white border-red-600 shadow-lg shadow-red-600/20'
                                         : 'bg-gray-50 text-gray-600 border-gray-100 hover:border-red-300 hover:bg-red-50 hover:text-red-600' }}">
                                {!! $group['icon'] !!}
                                {{ $group['label'] }}
                            </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- ===== DANH MỤC ===== --}}
                    <div class="p-5" x-data="{ open: true }">
                        <button type="button" @click="open = !open" class="w-full flex items-center justify-between mb-3 group">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Danh Mục</p>
                            <svg class="w-4 h-4 text-gray-400 transition-transform group-hover:text-red-500" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="space-y-1 max-h-56 overflow-y-auto pr-1" style="scrollbar-width: thin; scrollbar-color: #DC2626 #f3f4f6;">
                            <label class="flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer transition-all {{ !request('category') ? 'bg-red-50 border border-red-200' : 'hover:bg-gray-50' }}">
                                <input type="radio" name="category" value="" {{ !request('category') ? 'checked' : '' }} onchange="this.form.submit()" class="w-3.5 h-3.5 text-red-600 focus:ring-red-500 border-gray-300">
                                <span class="text-sm {{ !request('category') ? 'font-semibold text-red-700' : 'text-gray-700' }}">Tất cả danh mục</span>
                            </label>
                            @foreach($categories as $cat)
                            <label class="flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer transition-all {{ request('category') == $cat->id ? 'bg-red-50 border border-red-200' : 'hover:bg-gray-50' }}">
                                <input type="radio" name="category" value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'checked' : '' }} onchange="this.form.submit()" class="w-3.5 h-3.5 text-red-600 focus:ring-red-500 border-gray-300">
                                <span class="text-sm {{ request('category') == $cat->id ? 'font-semibold text-red-700' : 'text-gray-700' }}">{{ $cat->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- ===== KHOẢNG GIÁ ===== --}}
                    <div class="p-5">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Khoảng Giá</p>
                        {{-- Preset price ranges --}}
                        <div class="grid grid-cols-2 gap-1.5 mb-3">
                            @php
                            $priceRanges = [
                                ['label'=>'Dưới 200k','min'=>'','max'=>'200000'],
                                ['label'=>'200k-400k','min'=>'200000','max'=>'400000'],
                                ['label'=>'400k-700k','min'=>'400000','max'=>'700000'],
                                ['label'=>'Trên 700k','min'=>'700000','max'=>''],
                            ];
                            @endphp
                            @foreach($priceRanges as $range)
                            @php
                            $isActive = request('min_price') == $range['min'] && request('max_price') == $range['max'];
                            @endphp
                            <a href="{{ route('products.index', array_filter(array_merge(request()->query(), ['min_price'=>$range['min'],'max_price'=>$range['max']]), fn($v) => $v !== '')) }}"
                               class="text-center text-[11px] font-semibold px-2 py-2 rounded-lg border transition-all
                                      {{ $isActive ? 'bg-red-600 text-white border-red-600' : 'bg-gray-50 text-gray-600 border-gray-200 hover:border-red-300 hover:text-red-600' }}">
                                {{ $range['label'] }}
                            </a>
                            @endforeach
                        </div>
                        {{-- Custom range --}}
                        <div class="flex items-center gap-2 mb-2">
                            <div class="flex-1 relative">
                                <input type="number" name="min_price" placeholder="Từ" value="{{ request('min_price') }}"
                                       class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-red-500 focus:ring-2 focus:ring-red-100 outline-none transition-all bg-gray-50 focus:bg-white">
                            </div>
                            <div class="w-3 h-px bg-gray-300 flex-shrink-0"></div>
                            <div class="flex-1">
                                <input type="number" name="max_price" placeholder="Đến" value="{{ request('max_price') }}"
                                       class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-red-500 focus:ring-2 focus:ring-red-100 outline-none transition-all bg-gray-50 focus:bg-white">
                            </div>
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-gray-900 text-white text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-red-600 transition-all shadow-sm">
                            Áp dụng
                        </button>
                    </div>

                    {{-- ===== SẮP XẾP ===== --}}
                    <div class="p-5">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Sắp Xếp</p>
                        <div class="space-y-1.5">
                            @php
                            $sorts = [
                                '' => ['icon'=>'🕐','label'=>'Mặc định'],
                                'newest' => ['icon'=>'✨','label'=>'Mới nhất'],
                                'price_asc' => ['icon'=>'↑','label'=>'Giá thấp → cao'],
                                'price_desc' => ['icon'=>'↓','label'=>'Giá cao → thấp'],
                                'best_selling' => ['icon'=>'🔥','label'=>'Bán chạy nhất'],
                            ];
                            @endphp
                            @foreach($sorts as $val => $sort)
                            <label class="flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer transition-all {{ request('sort',$val===''?'':request('sort')) == $val ? 'bg-red-50 border border-red-200' : 'hover:bg-gray-50 border border-transparent' }}">
                                <input type="radio" name="sort" value="{{ $val }}" {{ (request('sort', '') === $val) ? 'checked' : '' }} onchange="this.form.submit()" class="w-3.5 h-3.5 text-red-600 focus:ring-red-500 border-gray-300">
                                <span class="text-sm {{ request('sort','') === $val ? 'font-semibold text-red-700' : 'text-gray-700' }}">
                                    <span class="mr-1">{{ $sort['icon'] }}</span> {{ $sort['label'] }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <script>
        // Alpine.js fallback if not loaded
        if (typeof Alpine === 'undefined') {
            document.querySelectorAll('[x-data]').forEach(el => {
                const btn = el.querySelector('button[\\@click]');
                const content = el.querySelector('[x-show]');
                if (btn && content) {
                    btn.addEventListener('click', () => {
                        content.style.display = content.style.display === 'none' ? '' : 'none';
                        const svg = btn.querySelector('svg');
                        if (svg) svg.style.transform = content.style.display === 'none' ? '' : 'rotate(180deg)';
                    });
                }
            });
        }
        </script>
        @endif

        <!-- Products Grid -->
        <div class="flex-1">
            @if(request('sort') !== 'newest')
            <!-- Results Header (Only for non-lookbook) -->
            <div class="bg-white rounded-lg shadow border border-gray-200 px-6 py-4 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        @if($currentGender)
                        <span class="inline-block bg-red-100 text-red-700 text-xs font-bold px-3 py-1 rounded-full mb-1">
                            {{ $pageTitle }}
                        </span><br>
                        @endif
                        <span class="text-gray-700">Hiển thị <span class="font-semibold">{{ $products->count() }}</span> trong <span class="font-semibold">{{ $products->total() }}</span> sản phẩm</span>
                    </div>
                    @if(request('search'))
                    <div class="text-gray-600">
                        Kết quả tìm kiếm cho: <span class="font-semibold text-red-600">"{{ request('search') }}"</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            @if($products->count() > 0)
            <!-- Products Grid -->
            <div class="grid @if(request('sort') === 'newest') grid-cols-1 md:grid-cols-4 gap-x-8 gap-y-16 @else grid-cols-1 md:grid-cols-3 gap-4 @endif">
                @foreach($products as $product)
                @php
                    $isLookbook = request('sort') === 'newest';
                    $isRecent = $product->created_at && $product->created_at->diffInDays(now()) <= 3;
                    // Simulate editorial featured items (e.g., every 7th item)
                    $isFeatured = $isLookbook && ($loop->iteration % 7 == 1);
                @endphp
                <div class="{{ $isLookbook ? 'group fade-in-up' : 'bg-white border border-gray-200 rounded-lg hover:shadow-lg transition-shadow group' }} {{ $isFeatured ? 'md:col-span-2 md:row-span-2' : '' }}" style="animation-delay: {{ $loop->index * 0.1 }}s">
                    <div class="relative overflow-hidden {{ $isLookbook ? 'rounded-sm shadow-sm' : '' }}">
                        <a href="{{ route('products.show', $product->slug) }}">
                            @if(str_starts_with($product->image, 'dummy_url|'))
                                <img src="{{ str_replace('dummy_url|', '', $product->image) }}" 
                            @else
                                <img src="{{ asset('storage/' . $product->image) }}" 
                            @endif
                                 alt="{{ $product->name }}" 
                                 class="w-full {{ $isFeatured ? 'h-[600px]' : ($isLookbook ? 'h-96' : 'h-72') }} object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                        </a>

                        @if($isLookbook)
                            {{-- Editorial Overlay for Lookbook --}}
                            <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500 z-30">
                                <a href="{{ route('products.show', $product->slug) }}" class="bg-white/90 backdrop-blur-md text-gray-900 px-6 py-2.5 text-xs font-bold tracking-widest uppercase hover:bg-gray-900 hover:text-white transition-all shadow-xl">
                                    Quick View
                                </a>
                            </div>

                            @if($isRecent)
                            <div class="absolute top-4 right-4 z-20">
                                <span class="bg-red-600 text-white text-[9px] font-black px-3 py-1 uppercase tracking-widest shadow-lg">New</span>
                            </div>
                            @endif
                        @else
                            {{-- Regular badges --}}
                            @if($product->sale_price && request('sort') !== 'newest')
                            <div class="absolute top-2 left-2 bg-red-600 text-white px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider z-20 shadow-lg animate-pulse">
                                SALE -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                            </div>
                            @endif
                            @if(request('sort') === 'sale')
                            <div class="absolute top-2 right-2 bg-yellow-400 text-red-700 px-2 py-1 rounded text-[10px] font-bold z-20 shadow-md">
                                💥 DEAL HỜI
                            </div>
                            @endif
                        @endif

                        @if($product->stock <= 0)
                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center z-10">
                            <span class="bg-red-600 text-white px-4 py-2 rounded font-bold">Hết hàng</span>
                        </div>
                        @elseif(!$isLookbook)
                        {{-- Hover overlay buttons for regular view --}}
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
                        @endif
                    </div>
                    <div class="{{ $isLookbook ? 'pt-6 pb-2 px-1' : 'p-4' }}">
                        @if($isLookbook)
                            <div class="flex justify-between items-start mb-2">
                                <a href="{{ route('products.show', $product->slug) }}" class="flex-1">
                                    <h3 class="font-display text-lg text-gray-900 group-hover:text-red-600 transition-colors tracking-tight">
                                        {{ $product->name }}
                                    </h3>
                                </a>
                                @if($isRecent)
                                    <span class="text-[10px] text-gray-400 font-light italic shrink-0 ml-4">New Season</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-3">
                                @if($product->sale_price)
                                    <span class="text-red-600 font-bold text-base">{{ number_format($product->sale_price) }}đ</span>
                                    <span class="text-gray-300 line-through text-xs">{{ number_format($product->price) }}đ</span>
                                @else
                                    <span class="text-gray-600 font-medium text-base">{{ number_format($product->price) }}đ</span>
                                @endif
                            </div>
                        @else
                            {{-- Regular Card Info --}}
                             @if(request('sort') === 'sale')
                                <div class="mt-4">
                                    <div class="flex justify-between items-center text-[10px] font-bold uppercase mb-1">
                                        <span class="text-red-600">🔥 Sắp hết hàng</span>
                                        <span class="text-gray-500">Đã bán {{ $product->sold_count ?? rand(40, 95) }}%</span>
                                    </div>
                                    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-red-500 to-orange-400 rounded-full" style="width: {{ $product->sold_count ?? rand(40, 95) }}%"></div>
                                    </div>
                                </div>
                            @elseif(request('sort') === 'newest')
                                {{-- No specific extra info needed for Lookbook here as it's handled above --}}
                            @else
                                <div class="flex items-center gap-1 text-xs text-yellow-500 mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($product->averageRating))
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endif
                                    @endfor
                                    <span class="text-gray-500 ml-1">({{ $product->reviewsCount }})</span>
                                </div>
                            @endif
                            
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
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->appends(request()->query())->links() }}
            </div>
            @else
            <!-- No Products Found -->
            <div class="bg-white rounded-lg shadow border border-gray-200 p-12 text-center">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Không tìm thấy sản phẩm</h3>
                <p class="text-gray-600 mb-6">Vui lòng thử lại với bộ lọc khác</p>
                <a href="{{ route('products.index') }}" class="inline-block bg-red-600 text-white px-6 py-3 rounded hover:bg-red-700 transition-colors font-medium">
                    Xem tất cả sản phẩm
                </a>
            </div>
            @endif
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
.font-display { font-family: 'Playfair Display', serif; }
</style>
@endsection
