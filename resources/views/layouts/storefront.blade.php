<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Trang Chủ') - HTD Store</title>
    <meta name="description" content="@yield('meta_description', 'HTD Store - Thời trang chất lượng cao cho nam, nữ và trẻ em.')"> 
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="HTD Store">
    <meta property="og:title" content="@yield('og_title', 'HTD Store - Thời trang chất lượng cao')">
    <meta property="og:description" content="@yield('og_description', 'HTD Store - Thời trang chất lượng cao cho nam, nữ và trẻ em.')">
    <meta property="og:image" content="@yield('og_image', asset('images/logo.png'))">
    @stack('meta')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-red: #DC2626;
            --primary-red-dark: #B91C1C;
            --primary-red-light: #EF4444;
            --secondary-gray: #F3F4F6;
            --text-dark: #1F2937;
            --border-gray: #E5E7EB;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            scroll-behavior: smooth;
        }
        
        .font-display {
            font-family: 'Playfair Display', serif;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .glass-dark {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Red Theme Button Styles */
        .btn-primary {
            background: var(--primary-red);
            color: white;
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover {
            background: var(--primary-red-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }
        
        .btn-secondary {
            background: white;
            color: var(--primary-red);
            border: 2px solid var(--primary-red);
            transition: all 0.2s ease;
        }
        
        .btn-secondary:hover {
            background: #FEE2E2;
            border-color: var(--primary-red-dark);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Top Bar -->
    <div class="bg-gray-100 border-b border-gray-200">
        <div class="container mx-auto px-4 py-2 text-sm text-gray-600">
            <div class="flex justify-between items-center">
                <span>HTD STORE - Thời trang chất lượng cao</span>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group flex-shrink-0">
                    <img src="{{ asset('images/logo.png') }}"
                         alt="HTD STORE"
                         class="h-20 w-auto object-contain group-hover:scale-105 transition-transform duration-300 drop-shadow-sm">
                    <div class="hidden sm:block leading-tight">
                        <div class="text-2xl font-black text-gray-900 tracking-tight leading-none">HTD <span class="text-red-600">STORE</span></div>
                        <div class="text-[11px] text-gray-400 uppercase tracking-[0.25em] mt-1">Thời trang chất lượng</div>
                    </div>
                </a>
                
                <!-- Search Bar -->
                <div class="flex-1 max-w-2xl mx-8">
                    <form action="{{ route('products.index') }}" method="GET" class="relative">
                        <input type="text" 
                               name="search" 
                               placeholder="Nhập từ khóa..." 
                               class="w-full px-4 py-3 pr-12 rounded-full border-2 border-gray-200 focus:border-red-600 focus:outline-none text-gray-700">
                        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-red-600 text-white px-6 py-2 rounded-full hover:bg-red-700 transition-colors font-medium">
                            Tìm kiếm
                        </button>
                    </form>
                </div>
                
                <!-- Right Actions -->
                <div class="flex items-center gap-6">
                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}" class="flex items-center gap-2 hover:text-red-600 transition-colors">
                        <div class="relative">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            @php $cartCount = session('cart') ? count(session('cart')) : 0; @endphp
                            <span id="cart-badge" class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full min-w-[20px] h-[20px] flex items-center justify-center font-bold {{ $cartCount > 0 ? '' : 'hidden' }}">{{ $cartCount }}</span>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Giỏ Hàng</span>
                    </a>
                    
                    <!-- Login/Account -->
                    <div class="relative" id="account-menu-wrapper">
                        <a href="{{ auth()->check() ? '#' : route('login') }}"
                           id="account-trigger"
                           class="flex items-center gap-2 hover:text-red-600 transition-colors">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">{{ auth()->check() ? auth()->user()->name : 'Đăng nhập' }}</span>
                        </a>
                        <!-- Dropdown Menu (position:fixed để thoát stacking context) -->
                        @auth
                        <div id="account-dropdown"
                             class="hidden w-56 bg-white rounded-lg shadow-2xl border border-gray-200 overflow-hidden"
                             style="position:fixed; z-index:9999;">
                            
                            <!-- Membership Tier Info -->
                            <div class="px-4 py-3 bg-red-50 border-b border-gray-100">
                                <p class="text-xs text-gray-500 mb-1">Hạng thành viên</p>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="font-bold text-red-700">{{ auth()->user()->membership_tier_label }}</span>
                                </div>
                                <p class="text-xs text-gray-600 mt-1 font-medium">{{ number_format(auth()->user()->points) }} Điểm tích lũy</p>
                                @if(auth()->user()->next_tier_progress)
                                    <p class="text-[11px] text-gray-500 mt-1">
                                        Cần <strong>{{ number_format(auth()->user()->next_tier_progress['points_needed']) }}</strong> điểm để lên hạng <strong>{{ auth()->user()->next_tier_progress['next_tier'] }}</strong>
                                    </p>
                                @endif
                            </div>

                            @if(auth()->user()->isEmployee())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-red-50 transition-all">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Quản Trị</span>
                                </a>
                            @endif
                            <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-red-50 transition-all">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span class="text-sm font-medium">Yêu Thích</span>
                            </a>
                            <a href="{{ route('voucher.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-red-50 transition-all">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                </svg>
                                <span class="text-sm font-medium">Ví Voucher 🎟️</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 px-4 py-3 w-full hover:bg-red-50 transition-all">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        <span class="text-sm font-medium">Đăng Xuất</span>
                                    </button>
                                </form>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Canifa-Style Mega Menu Navigation --}}
    <style>
        /* -------- Secondary Nav Bar -------- */
        .sec-nav { background: #fff; border-bottom: 1px solid #E5E7EB; position: relative; z-index: 500; }
        .sec-nav-inner { display: flex; align-items: center; justify-content: center; gap: 0; }
        .sec-nav-link {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 0 22px; height: 52px;
            font-size: 15px; font-weight: 600; color: #374151;
            white-space: nowrap; border-radius: 0; border: none; background: none;
            transition: color .2s;
            position: relative;
        }
        .sec-nav-link:hover, .sec-nav-link.active { color: #DC2626; }
        .sec-nav-link svg { flex-shrink: 0; }

        /* -------- Canifa Category Tab -------- */
        .cat-nav-item { position: relative; }
        .cat-nav-item > .sec-nav-link { cursor: pointer; }

        /* -------- Full-Width Mega Panel -------- */
        .canifa-mega {
            display: none;
            position: fixed;
            left: 0; right: 0;
            top: auto; /* set via JS */
            background: #fff;
            box-shadow: 0 16px 56px rgba(0,0,0,0.14);
            border-top: 3px solid #DC2626;
            z-index: 9000;
            padding: 32px 0 24px;
        }

        .canifa-mega.force-hide { 
            display: none !important; 
        }

        .cat-nav-item:hover .canifa-mega:not(.force-hide) { 
            display: block; 
        }

        .canifa-mega-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 0;
        }

        /* Column header */
        .cmega-col-header {
            display: flex; align-items: center; gap: 6px;
            font-size: 15px; font-weight: 800; color: #111827;
            letter-spacing: .04em; text-transform: uppercase;
            padding-bottom: 14px; margin-bottom: 4px;
            border-bottom: 2px solid #F3F4F6;
        }
        .cmega-col-header a {
            color: inherit; text-decoration: none;
            display: flex; align-items: center; gap: 6px;
        }
        .cmega-col-header a:hover { color: #DC2626; }
        .cmega-col-header .arrow-ext {
            font-size: 12px; opacity: .55;
        }

        /* Divider between columns */
        .cmega-col + .cmega-col {
            border-left: 1px solid #F3F4F6;
            padding-left: 28px;
            margin-left: 0;
        }
        .cmega-col {
            padding-right: 28px;
            max-height: 70vh;
            overflow-y: auto;
            scroll-behavior: smooth;
        }
        /* Custom scrollbar */
        .cmega-col::-webkit-scrollbar { width: 5px; }
        .cmega-col::-webkit-scrollbar-track { background: #F9FAFB; border-radius: 4px; }
        .cmega-col::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 4px; }
        .cmega-col::-webkit-scrollbar-thumb:hover { background: #DC2626; }

        /* Category row */
        .cmega-row {
            display: flex; align-items: center; gap: 12px;
            padding: 9px 8px 9px 0;
            border-bottom: 1px solid #F9FAFB;
            cursor: pointer;
            text-decoration: none;
            color: #374151;
            transition: background .15s;
            border-radius: 8px;
        }
        .cmega-row:hover { background: #FEF2F2; color: #DC2626; }
        .cmega-row:hover .cmega-thumb-wrap { border-color: #DC2626; }
        .cmega-row:hover .cmega-chevron { color: #DC2626; }

        .cmega-thumb-wrap {
            width: 48px; height: 48px; border-radius: 50%;
            overflow: hidden; flex-shrink: 0;
            border: 2px solid #E5E7EB;
            background: #F3F4F6;
            transition: border-color .15s;
            display: flex; align-items: center; justify-content: center;
        }
        .cmega-thumb-wrap img {
            width: 100%; height: 100%; object-fit: cover; display: block;
        }
        .cmega-thumb-placeholder {
            color: #9CA3AF;
        }

        .cmega-label { flex: 1; font-size: 13.5px; font-weight: 500; line-height: 1.3; }
        .cmega-chevron { color: #9CA3AF; flex-shrink: 0; transition: color .15s; }

        .hot-badge { display:inline-block; background:#DC2626; color:#fff; font-size:9px; font-weight:700; padding:1px 5px; border-radius:4px; margin-left:5px; vertical-align:middle; letter-spacing:.05em; }

        /* Subcategory list */
        .cmega-sub-list {
            display: none;
            padding-left: 60px;
            margin-top: 0;
            margin-bottom: 4px;
            background: #FEF2F2;
            border-radius: 0 0 8px 8px;
        }
        .cmega-sub-list.open { display: block; }
        .cmega-sub-item {
            display: block;
            padding: 6px 12px 6px 0;
            font-size: 12.5px;
            color: #6B7280;
            text-decoration: none;
            border-bottom: 1px solid #FEE2E2;
            transition: color .15s;
        }
        .cmega-sub-item:last-child { border-bottom: none; }
        .cmega-sub-item:hover { color: #DC2626; }
        .cmega-sub-item::before { content: '•'; margin-right: 6px; color: #DC2626; font-size: 10px; }

        /* Row wrapper (row + sub-list) */
        .cmega-row-wrap { border-radius: 8px; margin-bottom: 1px; overflow: hidden; }
        .cmega-row-wrap .cmega-row { border-radius: 0; margin-bottom: 0; }
        .cmega-row-wrap.open > .cmega-row { background: #FEF2F2; color: #DC2626; }
        .cmega-row-wrap.open > .cmega-row .cmega-chevron { color: #DC2626; transform: rotate(180deg); }
        .cmega-chevron { transition: transform .2s ease, color .15s; }

    </style>

    {{-- Mobile Hamburger Button (only shows on mobile) --}}
    {{-- Secondary nav: white pill-style tabs like Canifa --}}
    <div class="sec-nav">
        <div class="container mx-auto px-4">
            <div class="sec-nav-inner">

                {{-- Home --}}
                <a href="{{ route('home') }}" class="sec-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Trang Chủ
                </a>

                {{-- DANH MỤC mega tab --}}
                <div class="cat-nav-item">
                    <span class="sec-nav-link" style="cursor:pointer;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        Danh Mục
                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </span>

                    {{-- Canifa Mega Panel --}}
                    <div class="canifa-mega">
                        <div class="canifa-mega-inner">

                            {{-- ===== CỘT 1: NAM ===== --}}
                            <div class="cmega-col">
                                <div class="cmega-col-header">
                                    <a href="{{ route('products.index', ['gender'=>'nam']) }}">
                                        NAM
                                        <span class="arrow-ext">↗</span>
                                    </a>
                                </div>
                                @foreach($allCategories->filter(fn($c) => $c->parent_id === null && str_contains(strtolower($c->name), 'nam') && !str_contains(strtolower($c->name), 'bé')) as $cat)
                                @php $namSubs = $allCategories->filter(fn($s) => $s->parent_id === $cat->id && !str_contains(strtolower($s->name), 'jogger') && !str_contains(strtolower($s->name), 'áo len'))->take(5); @endphp
                                <div class="cmega-row-wrap {{ $namSubs->isNotEmpty() ? 'has-children' : '' }}">
                                    @if($namSubs->isNotEmpty())
                                    <div class="cmega-row" onclick="toggleSub(this)" style="cursor:pointer;">
                                    @else
                                    <a href="{{ route('products.index', ['category' => $cat->id]) }}" class="cmega-row">
                                    @endif
                                        <div class="cmega-thumb-wrap">
                                            @if($cat->image)
                                                @if(str_starts_with($cat->image, 'dummy_url|'))
                                                    <img src="{{ str_replace('dummy_url|', '', $cat->image) }}" alt="{{ $cat->name }}">
                                                @else
                                                    <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}">
                                                @endif
                                            @else
                                                <svg class="cmega-thumb-placeholder w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                            @endif
                                        </div>
                                        <span class="cmega-label">
                                            {{ $cat->name }}
                                            @if(str_contains($cat->name,'Khoác') || str_contains($cat->name,'Giữ Nhiệt'))<span class="hot-badge">HOT</span>@endif
                                        </span>
                                        @if($namSubs->isNotEmpty())
                                            <svg class="cmega-chevron w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                    <div class="cmega-sub-list">
                                        @foreach($namSubs as $sub)
                                            <a href="{{ route('products.index', ['category' => $sub->id]) }}" class="cmega-sub-item">{{ $sub->name }}</a>
                                        @endforeach
                                    </div>
                                        @else
                                            <svg class="cmega-chevron w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                        @endif
                                </div>
                                @endforeach
                                <a href="{{ route('products.index', ['gender'=>'nam']) }}" class="cmega-row" style="justify-content:center; color:#DC2626; font-weight:600; font-size:13px; border-bottom:none; margin-top:4px;">
                                    Xem tất cả Nam →
                                </a>
                            </div>

                            {{-- ===== CỘT 2: NỮ ===== --}}
                            <div class="cmega-col">
                                <div class="cmega-col-header">
                                    <a href="{{ route('products.index', ['gender'=>'nu']) }}">
                                        NỮ
                                        <span class="arrow-ext">↗</span>
                                    </a>
                                </div>
                                @foreach($allCategories->filter(fn($c) => $c->parent_id === null && (str_contains($c->name, 'Nữ') || str_contains($c->name, 'nữ')) && !str_contains(strtolower($c->name), 'bé')) as $cat)
                                @php $nuSubs = $allCategories->filter(fn($s) => $s->parent_id === $cat->id && !str_contains(strtolower($s->name), 'áo len'))->take(5); @endphp
                                <div class="cmega-row-wrap {{ $nuSubs->isNotEmpty() ? 'has-children' : '' }}">
                                    @if($nuSubs->isNotEmpty())
                                    <div class="cmega-row" onclick="toggleSub(this)" style="cursor:pointer;">
                                    @else
                                    <a href="{{ route('products.index', ['category' => $cat->id]) }}" class="cmega-row">
                                    @endif
                                        <div class="cmega-thumb-wrap">
                                            @if($cat->image)
                                                @if(str_starts_with($cat->image, 'dummy_url|'))
                                                    <img src="{{ str_replace('dummy_url|', '', $cat->image) }}" alt="{{ $cat->name }}">
                                                @else
                                                    <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}">
                                                @endif
                                            @else
                                                <svg class="cmega-thumb-placeholder w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                            @endif
                                        </div>
                                        <span class="cmega-label">
                                            {{ $cat->name }}
                                            @if(str_contains($cat->name,'Khoác') || str_contains($cat->name,'Giữ Nhiệt'))<span class="hot-badge">HOT</span>@endif
                                        </span>
                                        @if($nuSubs->isNotEmpty())
                                            <svg class="cmega-chevron w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                    <div class="cmega-sub-list">
                                        @foreach($nuSubs as $sub)
                                            <a href="{{ route('products.index', ['category' => $sub->id]) }}" class="cmega-sub-item">{{ $sub->name }}</a>
                                        @endforeach
                                    </div>
                                        @else
                                            <svg class="cmega-chevron w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                        @endif
                                </div>
                                @endforeach
                                <a href="{{ route('products.index', ['gender'=>'nu']) }}" class="cmega-row" style="justify-content:center; color:#DC2626; font-weight:600; font-size:13px; border-bottom:none; margin-top:4px;">
                                    Xem tất cả Nữ →
                                </a>
                            </div>

                            {{-- ===== CỘT 3: TRẺ EM (Bé Trai + Bé Gái) ===== --}}
                            <div class="cmega-col">
                                <div class="cmega-col-header">
                                    <a href="{{ route('products.index', ['gender'=>'tre-em']) }}">
                                        TRẺ EM
                                        <span class="arrow-ext">↗</span>
                                    </a>
                                </div>
                                @foreach($allCategories->filter(fn($c) => $c->parent_id === null && (str_contains(strtolower($c->name), 'trẻ em') || str_contains(strtolower($c->name), 'bé')) && !str_contains(strtolower($c->name), 'đồ mặc trong') && !str_contains(strtolower($c->name), 'sản phẩm khác')) as $cat)
                                @php $treSubs = $allCategories->filter(fn($s) => $s->parent_id === $cat->id)->take(5); @endphp
                                <div class="cmega-row-wrap {{ $treSubs->isNotEmpty() ? 'has-children' : '' }}">
                                    @if($treSubs->isNotEmpty())
                                    <div class="cmega-row" onclick="toggleSub(this)" style="cursor:pointer;">
                                    @else
                                    <a href="{{ route('products.index', ['category' => $cat->id]) }}" class="cmega-row">
                                    @endif
                                        <div class="cmega-thumb-wrap">
                                            @if($cat->image)
                                                @if(str_starts_with($cat->image, 'dummy_url|'))
                                                    <img src="{{ str_replace('dummy_url|', '', $cat->image) }}" alt="{{ $cat->name }}">
                                                @else
                                                    <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}">
                                                @endif
                                            @else
                                                <svg class="cmega-thumb-placeholder w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                            @endif
                                        </div>
                                        <span class="cmega-label">
                                            {{ $cat->name }}
                                            @if(str_contains($cat->name,'Khoác') || str_contains($cat->name,'Giữ Nhiệt'))<span class="hot-badge">HOT</span>@endif
                                        </span>
                                        @if($treSubs->isNotEmpty())
                                            <svg class="cmega-chevron w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                    <div class="cmega-sub-list">
                                        @foreach($treSubs as $sub)
                                            <a href="{{ route('products.index', ['category' => $sub->id]) }}" class="cmega-sub-item">{{ $sub->name }}</a>
                                        @endforeach
                                    </div>
                                        @else
                                            <svg class="cmega-chevron w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                        @endif
                                </div>
                                @endforeach
                                <a href="{{ route('products.index', ['gender'=>'tre-em']) }}" class="cmega-row" style="justify-content:center; color:#DC2626; font-weight:600; font-size:13px; border-bottom:none; margin-top:4px;">
                                    Xem tất cả Trẻ Em →
                                </a>
                            </div>

                        </div>{{-- /canifa-mega-inner --}}
                        
                        {{-- Nút đóng ở dưới cùng --}}
                        <div class="canifa-mega-footer" style="background: rgba(0,0,0,0.4); text-align: center; padding: 12px 0;">
                            <button id="close-mega-menu" class="btn bg-white text-gray-800 rounded-full px-6 py-2 shadow-sm font-medium hover:bg-gray-100 transition inline-flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Đóng
                            </button>
                        </div>
                    </div>{{-- /canifa-mega --}}
                </div>{{-- /cat-nav-item --}}

                {{-- Flash Sale --}}
                <a href="{{ route('products.index', ['sort'=>'sale']) }}" class="sec-nav-link">
                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                    Flash Sale
                </a>

                {{-- Sản phẩm mới --}}
                <a href="{{ route('products.index', ['sort'=>'newest']) }}" class="sec-nav-link">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Mới Về
                </a>

                {{-- Cửa hàng --}}
                <a href="{{ route('stores.index') }}" class="sec-nav-link">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Cửa Hàng
                </a>

                {{-- Tin tức --}}
                <a href="{{ route('posts.index') }}" class="sec-nav-link">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    Tin Tức
                </a>

            </div>
        </div>
    </div>{{-- /sec-nav --}}


    <!-- Flash Messages -->
    @if(session('success'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded shadow-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded shadow-md flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Hero Section (Full Width) -->
    @yield('hero')

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <!-- Newsletter Section (Above Footer) -->
    @if(!request()->routeIs('home'))
    <div class="bg-white py-8 border-t border-gray-200">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">ĐĂNG KÝ NHẬN THÔNG TIN</h3>
                        <p class="text-gray-600 text-sm">Để nhận những thông tin khuyến mãi, chất khởi hấp dẫn</p>
                    </div>
                </div>
                <form class="flex gap-2">
                    <input type="email" 
                           placeholder="Nhập vào số điện thoại" 
                           class="px-6 py-3 rounded border border-gray-300 w-80 focus:outline-none focus:border-red-600">
                    <button type="submit" class="bg-red-600 text-white px-8 py-3 rounded font-bold hover:bg-red-700 transition-colors">
                        ĐĂNG KÝ
                    </button>
                </form>
                <div class="flex gap-3">
                    <a href="#" class="w-10 h-10 bg-red-600 text-white rounded flex items-center justify-center hover:bg-red-700 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-red-600 text-white rounded flex items-center justify-center hover:bg-red-700 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-red-600 text-white rounded flex items-center justify-center hover:bg-red-700 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Footer -->
    <footer class="{{ request()->routeIs('home') ? 'bg-white' : 'bg-gray-100' }} border-t border-gray-200">
        <div class="container mx-auto px-4 py-12">
            <!-- Logo and Company Name -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-red-600 rounded flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-red-600">HTD STORE</div>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-gray-900">HTD STORE</h3>
            </div>

            <div class="grid grid-cols-4 gap-8 mb-8">
                <!-- Customer Support -->
                <div>
                    <h4 class="font-bold text-gray-900 mb-4 uppercase">Hỗ Trợ Khách Hàng</h4>
                    <ul class="space-y-2 text-gray-600 text-sm">
                        <li><a href="#" class="hover:text-red-600">• QUY ĐỊNH MUA HÀNG</a></li>
                        <li><a href="#" class="hover:text-red-600">• HƯỚNG DẪN THANH TOÁN</a></li>
                        <li><a href="#" class="hover:text-red-600">• HƯỚNG DẪN MUA HÀNG</a></li>
                    </ul>
                </div>

                <!-- About HTD Store -->
                <div>
                    <h4 class="font-bold text-gray-900 mb-4 uppercase">Về HTD Store</h4>
                    <ul class="space-y-2 text-gray-600 text-sm">
                        <li><a href="{{ route('about') }}" class="hover:text-red-600">• GIỚI THIỆU</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-red-600">• LIÊN HỆ</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-red-600">• VỀ CHÚNG TÔI</a></li>
                    </ul>
                </div>

                <!-- Policies -->
                <div>
                    <h4 class="font-bold text-gray-900 mb-4 uppercase">Chính Sách</h4>
                    <ul class="space-y-2 text-gray-600 text-sm">
                        <li><a href="#" class="hover:text-red-600">• CHÍNH SÁCH ĐỔI TRẢ</a></li>
                        <li><a href="#" class="hover:text-red-600">• CHÍNH SÁCH BẢO MẬT</a></li>
                        <li><a href="#" class="hover:text-red-600">• CHÍNH SÁCH VẬN CHUYỂN</a></li>
                        <li><a href="#" class="hover:text-red-600">• CHÍNH SÁCH GIAO HÀNG</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="font-bold text-gray-900 mb-4 uppercase">Thông Tin Liên Hệ</h4>
                    <ul class="space-y-2 text-gray-600 text-sm">
                        <li>Địa chỉ: 56 534 Đường Láng, Phường Yên Hoà, Quận Đống Đa, Hà Nội</li>
                        <li>Số điện thoại: 0123456789 (ZALO)</li>
                        <li>Email: kenda@gmail.com</li>

                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-gray-200 pt-6">
                <p class="text-center text-gray-600 text-sm">
                    © Copyright 2026, All Rights Reserved - HTD Store
                </p>
                <p class="text-center text-gray-500 text-xs mt-2">
                    Website demo chưa kinh doanh chính thức
                </p>
            </div>
        </div>
    </footer>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-24 right-4 z-[9999] flex flex-col gap-3"></div>

    <style>
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        .toast-enter { animation: slideInRight 0.35s ease-out forwards; }
        .toast-exit { animation: slideOutRight 0.3s ease-in forwards; }
    </style>

    <script>
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        const bgColor = type === 'success' ? 'bg-green-600' : 'bg-red-600';
        const icon = type === 'success'
            ? '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>'
            : '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';

        toast.className = `${bgColor} text-white px-5 py-3 rounded-lg shadow-xl flex items-center gap-3 min-w-[300px] toast-enter`;
        toast.innerHTML = `${icon}<span class="font-medium">${message}</span>`;
        container.appendChild(toast);

        setTimeout(() => {
            toast.classList.remove('toast-enter');
            toast.classList.add('toast-exit');
            toast.addEventListener('animationend', () => toast.remove());
        }, 2500);
    }

    function updateCartBadge(count) {
        const badge = document.getElementById('cart-badge');
        if (badge) {
            badge.textContent = count;
            badge.classList.toggle('hidden', count <= 0);
        }
    }

    function ajaxAddToCart(form) {
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalHTML = submitBtn.innerHTML;

        // Loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Đang thêm...';

        fetch(form.action, {
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
                updateCartBadge(data.cartCount);
            } else {
                showToast(data.message || 'Có lỗi xảy ra!', 'error');
            }
        })
        .catch(() => {
            showToast('Có lỗi xảy ra, vui lòng thử lại!', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHTML;
        });

        return false; // Prevent form submission
    }

    // ===== ACCOUNT DROPDOWN POSITIONING =====
    (function() {
        const wrapper = document.getElementById('account-menu-wrapper');
        const trigger = document.getElementById('account-trigger');
        const dropdown = document.getElementById('account-dropdown');
        if (!wrapper || !dropdown) return;

        let hideTimer = null;

        function showDropdown() {
            clearTimeout(hideTimer);
            const rect = trigger.getBoundingClientRect();
            dropdown.style.top  = (rect.bottom + 4) + 'px';
            dropdown.style.right = (window.innerWidth - rect.right) + 'px';
            dropdown.style.left  = 'auto';
            dropdown.classList.remove('hidden');
        }

        function scheduleHide() {
            hideTimer = setTimeout(() => dropdown.classList.add('hidden'), 150);
        }

        wrapper.addEventListener('mouseenter', showDropdown);
        wrapper.addEventListener('mouseleave', scheduleHide);
        dropdown.addEventListener('mouseenter', () => clearTimeout(hideTimer));
        dropdown.addEventListener('mouseleave', scheduleHide);
    })();

    // ===== CANIFA MEGA MENU: auto-position top (optimized) =====
    (function() {
        const catItem = document.querySelector('.cat-nav-item');
        const megaPanel = catItem ? catItem.querySelector('.canifa-mega') : null;
        const closeBtn = document.getElementById('close-mega-menu');
        if (!catItem || !megaPanel) return;

        let rafId = null;

        function updateTop() {
            const rect = catItem.getBoundingClientRect();
            megaPanel.style.top = rect.bottom + 'px';
        }

        function onScroll() {
            if (rafId) return;
            rafId = requestAnimationFrame(function() {
                updateTop();
                rafId = null;
            });
        }

        // Khóa scroll trang khi mega menu mở
        catItem.addEventListener('mouseenter', function() {
            updateTop();
            document.body.style.overflow = 'hidden';
            megaPanel.classList.remove('force-hide');
        });

        // Mở lại scroll khi rời mega menu
        catItem.addEventListener('mouseleave', function() {
            document.body.style.overflow = '';
            megaPanel.classList.remove('force-hide');
        });

        // Sự kiện click nút Đóng
        if (closeBtn) {
            closeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                megaPanel.classList.add('force-hide');
                document.body.style.overflow = '';
            });
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', updateTop);
        updateTop();
    })();



    // ===== SUBCATEGORY TOGGLE =====
    function toggleSub(rowEl) {
        const wrap = rowEl.closest('.cmega-row-wrap');
        const subList = wrap ? wrap.querySelector('.cmega-sub-list') : null;
        if (!wrap || !subList) return;
        const isOpen = wrap.classList.toggle('open');
        subList.classList.toggle('open', isOpen);
    }
    </script>
</body>
</html>
