<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quản Trị Hệ Thống') - Clothers Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Custom scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 8px; }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.2); }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="w-72 bg-gray-900 text-white flex-shrink-0 shadow-xl relative z-30 print:hidden">
            <!-- Brand Section -->
            <div class="p-6 border-b border-white/10 bg-gradient-to-r from-red-600/5 to-transparent">
                <a href="{{ route('admin.dashboard') }}" class="group block">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center shadow-lg shadow-red-600/20 group-hover:scale-105 transition-transform duration-300">
                            <span class="text-white font-bold text-xl">C</span>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold tracking-tight group-hover:text-red-400 transition-colors">CLOTHERS</h1>
                            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-widest mt-0.5">Quản Trị Viên</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Admin Profile Quick Access -->
            <div class="px-5 py-5 border-b border-white/5">
                <div class="bg-white/5 rounded-xl p-3 flex items-center gap-3 border border-white/5 hover:bg-white/10 transition-all cursor-pointer group">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full border border-red-500/30 p-0.5">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=E11D48&color=fff" class="w-full h-full rounded-full">
                        </div>
                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-gray-900 rounded-full"></span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-0.5">Xin chào</p>
                        <p class="text-sm font-semibold truncate text-white group-hover:text-red-400 transition-colors">{{ auth()->user()->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Enhanced Navigation -->
            <nav class="p-5 space-y-1.5 sidebar-scroll overflow-y-auto" style="max-height: calc(100vh - 260px);">
                <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-widest px-3 mb-3">Hệ Thống Lõi</p>
                
                @php
                    $navItems = [
                        ['route' => 'admin.dashboard', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z', 'label' => 'Bảng điều khiển', 'active' => 'admin.dashboard'],
                        ['route' => 'admin.categories.index', 'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16', 'label' => 'Danh mục', 'active' => 'admin.categories.*'],
                        ['route' => 'admin.products.index', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'label' => 'Sản phẩm', 'active' => 'admin.products.*'],
                        ['route' => 'admin.inventory.index', 'icon' => 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4a2 2 0 012-2m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4', 'label' => 'Quản lý kho', 'active' => 'admin.inventory.*'],
                        ['route' => 'admin.orders.index', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'label' => 'Đơn hàng', 'active' => 'admin.orders.*'],
                    ];
                @endphp

                @foreach($navItems as $item)
                    <a href="{{ route($item['route']) }}" 
                       class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs($item['active']) ? 'bg-red-600 text-white shadow-md shadow-red-600/20' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path>
                            </svg>
                        </div>
                        <span class="font-medium text-sm">{{ $item['label'] }}</span>
                    </a>
                @endforeach

                <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-widest px-3 pt-5 mb-3">Nhân Sự & Khách Hàng</p>

                @if(auth()->check() && auth()->user()->role === 'admin')
                <a href="{{ route('admin.employees.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.employees.*') ? 'bg-red-600 text-white shadow-md shadow-red-600/20' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="font-medium text-sm">Nhân viên</span>
                </a>
                @endif

                <a href="{{ route('admin.customers.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.customers.*') ? 'bg-red-600 text-white shadow-md shadow-red-600/20' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span class="font-medium text-sm">Khách hàng</span>
                </a>

                <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-widest px-3 pt-5 mb-3">Khuyến Mãi & Tương Tác</p>

                <a href="{{ route('admin.vouchers.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.vouchers.*') ? 'bg-red-600 text-white shadow-md shadow-red-600/20' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <span class="font-medium text-sm">Quản lý Voucher</span>
                </a>

                <a href="{{ route('admin.wishlist.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.wishlist.*') ? 'bg-red-600 text-white shadow-md shadow-red-600/20' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    <span class="font-medium text-sm">Sản phẩm Yêu thích</span>
                </a>

                <!-- Quick Actions Footer -->

                <div class="mt-8 p-3 bg-white/5 rounded-xl border border-white/5">
                    <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-between group px-2 py-1.5">
                        <span class="text-xs font-medium text-gray-300 group-hover:text-red-400 transition-colors uppercase tracking-wider">Xem trang chủ</span>
                        <svg class="w-4 h-4 text-gray-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </a>
                </div>
            </nav>

            <!-- Bottom Actions -->
            <div class="absolute bottom-0 left-0 right-0 p-4 bg-gray-900 border-t border-white/5">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full h-10 bg-white/5 hover:bg-red-600 group rounded-lg transition-all duration-200 flex items-center justify-center gap-2 border border-white/5 hover:border-red-500 text-gray-300 hover:text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span class="font-medium text-xs uppercase tracking-wider">Đăng xuất</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden bg-gray-50/50 print:bg-white">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200 px-8 h-20 flex items-center justify-between sticky top-0 z-20 print:hidden">
                <div class="flex items-center gap-4">
                    <div class="w-1 h-8 bg-red-600 rounded-full"></div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 tracking-tight">@yield('page-title', 'Bảng Điều Khiển')</h2>
                        <div class="text-xs text-gray-500 mt-0.5 font-medium">
                            @yield('page-description', 'Tổng quan hệ thống')
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    @php
                        $lowStockProducts = \App\Models\Product::where('stock', '<', 10)->where('stock', '>', 0)->get();
                        $outOfStockProducts = \App\Models\Product::where('stock', 0)->get();
                        $newOrders = \App\Models\Order::where('status', 'pending')->latest()->take(5)->get();
                        $notificationCount = $lowStockProducts->count() + $outOfStockProducts->count() + $newOrders->count();
                    @endphp

                    <!-- Notifications Dropdown -->
                    <div class="relative group">
                        <button class="relative p-2 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            @if($notificationCount > 0)
                                <span class="absolute top-1 right-1 flex h-4 w-4">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-4 w-4 bg-red-600 text-white text-[9px] font-bold items-center justify-center">{{ $notificationCount > 9 ? '9+' : $notificationCount }}</span>
                                </span>
                            @endif
                        </button>

                        <div class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-2xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top-right z-50">
                            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 rounded-t-xl">
                                <h3 class="text-sm font-bold text-gray-800">Thông báo</h3>
                                <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-md">{{ $notificationCount }} mới</span>
                            </div>
                            <div class="max-h-[320px] overflow-y-auto custom-scroll">
                                @if($notificationCount > 0)
                                    @foreach($newOrders as $order)
                                    <a href="{{ route('admin.orders.show', $order) }}" class="block p-4 border-b border-gray-50 hover:bg-gray-50 transition-colors">
                                        <div class="flex gap-3">
                                            <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Đơn hàng mới #{{ $order->id }}</p>
                                                <p class="text-[11px] text-gray-500 mt-0.5">{{ $order->customer_name }} - {{ number_format($order->total) }}đ</p>
                                                <p class="text-[10px] text-gray-400 mt-1">{{ $order->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </a>
                                    @endforeach

                                    @foreach($outOfStockProducts as $product)
                                    <a href="{{ route('admin.products.edit', $product) }}" class="block p-4 border-b border-gray-50 hover:bg-red-50/30 transition-colors">
                                        <div class="flex gap-3">
                                            <div class="w-8 h-8 rounded-full bg-red-50 text-red-600 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-red-600">Sản phẩm Hết Hàng!</p>
                                                <p class="text-[11px] text-gray-500 mt-0.5">{{ mb_strimwidth($product->name, 0, 40, '...') }}</p>
                                            </div>
                                        </div>
                                    </a>
                                    @endforeach

                                    @foreach($lowStockProducts as $product)
                                    <a href="{{ route('admin.products.edit', $product) }}" class="block p-4 border-b border-gray-50 hover:bg-yellow-50/30 transition-colors">
                                        <div class="flex gap-3">
                                            <div class="w-8 h-8 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-yellow-700">Sắp hết hàng (Còn {{ $product->stock }})</p>
                                                <p class="text-[11px] text-gray-500 mt-0.5">{{ mb_strimwidth($product->name, 0, 40, '...') }}</p>
                                            </div>
                                        </div>
                                    </a>
                                    @endforeach
                                @else
                                    <div class="p-6 text-center">
                                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-2">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        <p class="text-sm text-gray-500">Tuyệt vời! Không có cảnh báo mới.</p>
                                    </div>
                                @endif
                            </div>
                            <div class="p-2 border-t border-gray-100 text-center bg-gray-50 rounded-b-xl">
                                <a href="{{ route('admin.orders.index') }}" class="text-[11px] font-semibold text-gray-500 hover:text-red-600 transition-colors uppercase tracking-widest">Xem tất cả đơn hàng</a>
                            </div>
                        </div>
                    </div>

                    <div class="hidden lg:flex items-center gap-2 border-l border-gray-200 pl-6">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                        <span class="text-xs font-medium text-green-600">Hệ thống đang hoạt động</span>
                    </div>
                    
                    <div class="bg-gray-100 px-4 py-2 rounded-lg flex items-center gap-3">
                        <div class="text-right">
                             <p class="text-sm font-semibold text-gray-700 leading-none mb-0.5">{{ now()->format('d/m/Y') }}</p>
                             <p class="text-[11px] text-gray-500 font-medium">{{ now()->format('H:i') }}</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Scroll Area -->
            <main class="flex-1 overflow-y-auto p-8 custom-scroll">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="bg-white border-l-4 border-green-500 p-4 rounded-xl mb-6 shadow-sm flex items-center gap-4 animate-fadeIn">
                        <div class="w-10 h-10 bg-green-50 rounded-full flex items-center justify-center text-green-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-semibold text-green-600 uppercase tracking-wider mb-0.5">Thành công</p>
                            <p class="text-sm text-gray-700">{{ session('success') }}</p>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-white border-l-4 border-red-500 p-4 rounded-xl mb-6 shadow-sm flex items-center gap-4 animate-fadeIn">
                        <div class="w-10 h-10 bg-red-50 rounded-full flex items-center justify-center text-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-semibold text-red-600 uppercase tracking-wider mb-0.5">Lỗi</p>
                            <p class="text-sm text-gray-700">{{ session('error') }}</p>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                @endif

                <div class="content-body">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fadeIn { animation: fadeIn 0.3s ease-out forwards; }
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 8px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #D1D5DB; }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('form.btn-delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const message = this.getAttribute('data-message') || "Hành động này không thể hoàn tác!";
                Swal.fire({
                    title: 'Xác nhận yêu cầu',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#9ca3af',
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Hủy bỏ',
                    customClass: {
                        confirmButton: 'rounded-xl',
                        cancelButton: 'rounded-xl'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
    </script>
    @stack('scripts')
</body>
</html>
