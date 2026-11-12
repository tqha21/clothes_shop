<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS Bán Hàng Tại Quầy - HTD Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scroll::-webkit-scrollbar-track { background: #f8fafc; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 h-screen flex flex-col overflow-hidden select-none">

    <!-- Top Navigation Bar -->
    <header class="bg-slate-900 text-white px-4 py-2.5 flex items-center justify-between border-b border-slate-800 shadow-md shrink-0">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 text-rose-500 font-extrabold text-lg tracking-tight hover:opacity-90">
                <span class="w-8 h-8 rounded-lg bg-rose-600 text-white flex items-center justify-center text-base font-black shadow-md shadow-rose-600/30">POS</span>
                <span class="text-white">HTD<span class="text-rose-500">STORE</span></span>
            </a>

            <!-- Store Selector -->
            <div class="flex items-center gap-2 bg-slate-800 px-3 py-1.5 rounded-lg border border-slate-700 text-xs">
                <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span class="text-slate-400">Cửa hàng:</span>
                @if(auth()->user()->role === 'admin')
                    <select id="storeSelect" class="bg-transparent text-white font-semibold outline-none cursor-pointer" onchange="changeStore(this.value)">
                        @foreach($stores as $st)
                            <option value="{{ $st->id }}" {{ $currentStore->id === $st->id ? 'selected' : '' }} class="bg-slate-800 text-white">{{ $st->name }} ({{ $st->city }})</option>
                        @endforeach
                    </select>
                @else
                    <span class="font-semibold text-white">{{ $currentStore->name ?? 'Cửa hàng chính' }}</span>
                @endif
            </div>

            <!-- Shift Status Badge -->
            @if($activeSession)
                <a href="{{ route('staff.shifts.index') }}" class="flex items-center gap-2 bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-emerald-500/25 transition">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Ca mở: {{ $activeSession->code }} (Đầu ca: {{ number_format($activeSession->opening_cash) }}đ)</span>
                </a>
            @else
                <a href="{{ route('staff.shifts.index') }}" class="flex items-center gap-2 bg-amber-500/15 border border-amber-500/30 text-amber-300 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-amber-500/25 transition">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    <span>Chưa mở ca - Bấm vào đây để mở ca</span>
                </a>
            @endif
        </div>

        <div class="flex items-center gap-3 text-xs">
            <a href="{{ route('staff.shifts.index') }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg transition border border-slate-700 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Quản lý Ca & Tiền
            </a>

            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'manager')
                <a href="{{ route('admin.reports.sales') }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg transition border border-slate-700 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Báo cáo Doanh số
                </a>
            @endif

            <div class="flex items-center gap-2 pl-3 border-l border-slate-700">
                <span class="w-7 h-7 rounded-full bg-rose-600 text-white font-bold flex items-center justify-center text-xs">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </span>
                <div>
                    <div class="font-bold text-white leading-tight">{{ auth()->user()->name }}</div>
                    <div class="text-[10px] text-slate-400 uppercase tracking-wider">{{ auth()->user()->role }}</div>
                </div>
            </div>

            <a href="{{ route('admin.dashboard') }}" class="p-1.5 hover:bg-slate-800 text-slate-400 hover:text-white rounded-lg transition" title="Về trang quản trị">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            </a>
        </div>
    </header>

    <!-- Main Workspace: Split 65% Catalog - 35% Cart -->
    <div class="flex-1 flex overflow-hidden">

        <!-- LEFT: Product Catalog & Barcode (65%) -->
        <div class="w-[62%] flex flex-col border-r border-slate-200 bg-white">
            <!-- Search & Filters -->
            <div class="p-3 border-b border-slate-200 bg-slate-50/70 space-y-2.5">
                <div class="flex items-center gap-2">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" id="searchInput" placeholder="Tìm tên sản phẩm hoặc mã SKU (Phím F2)..."
                               class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 outline-none shadow-sm transition">
                    </div>

                    <!-- Barcode Scan Trigger -->
                    <div class="relative w-56">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </span>
                        <input type="text" id="barcodeInput" placeholder="Quét Barcode / SKU (F4)..."
                               class="w-full pl-9 pr-3 py-2.5 bg-white border border-rose-300 focus:border-rose-600 focus:ring-2 focus:ring-rose-500/30 rounded-xl text-xs outline-none shadow-sm font-mono">
                    </div>
                </div>

                <!-- Category Chips -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 custom-scroll text-xs">
                    <button onclick="filterCategory('all')" class="cat-btn px-3 py-1.5 rounded-lg font-semibold bg-rose-600 text-white shadow-sm shrink-0" data-cat="all">Tất cả</button>
                    @foreach($categories as $cat)
                        <button onclick="filterCategory({{ $cat->id }})" class="cat-btn px-3 py-1.5 rounded-lg font-medium bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 shrink-0 transition" data-cat="{{ $cat->id }}">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Product Grid -->
            <div id="productGrid" class="flex-1 p-3.5 overflow-y-auto custom-scroll grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 content-start">
                <div class="col-span-full py-16 text-center text-slate-400">
                    <div class="animate-spin w-8 h-8 border-4 border-rose-500 border-t-transparent rounded-full mx-auto mb-3"></div>
                    Đang tải danh mục sản phẩm...
                </div>
            </div>
        </div>

        <!-- RIGHT: Current Cart & Payment Processing (38%) -->
        <div class="w-[38%] flex flex-col bg-slate-50 h-full shadow-lg">

            <!-- Customer Lookup Header -->
            <div class="p-3 bg-white border-b border-slate-200 shrink-0">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Khách Hàng
                    </span>
                    <button type="button" onclick="resetCustomer()" id="resetCustomerBtn" class="hidden text-xs text-rose-600 hover:underline">Hủy chọn</button>
                </div>
                <div class="flex items-center gap-2">
                    <div class="relative flex-1">
                        <input type="text" id="customerPhoneInput" placeholder="Nhập SĐT khách hàng thành viên..."
                               class="w-full pl-3 pr-20 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs outline-none focus:border-rose-500 focus:bg-white transition">
                        <button type="button" onclick="searchCustomer()" class="absolute right-1 top-1 bottom-1 px-3 bg-slate-800 hover:bg-slate-700 text-white rounded text-[11px] font-semibold transition">
                            Tìm
                        </button>
                    </div>
                </div>

                <!-- Customer Info Banner (Hidden by default) -->
                <div id="customerInfoBanner" class="hidden mt-2 p-2 bg-rose-50 border border-rose-200 rounded-lg flex items-center justify-between text-xs">
                    <div>
                        <div class="font-bold text-slate-800" id="custNameDisplay">Nguyễn Văn A</div>
                        <div class="text-[11px] text-slate-600 flex items-center gap-2">
                            <span>SĐT: <span id="custPhoneDisplay" class="font-mono">0987654321</span></span>
                            <span>•</span>
                            <span class="text-rose-600 font-semibold" id="custTierDisplay">Hạng Vàng</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span id="custDiscountBadge" class="bg-rose-600 text-white px-2 py-0.5 rounded text-[10px] font-bold">Giảm 10%</span>
                        <div class="text-[10px] text-slate-500 mt-0.5"><span id="custPointsDisplay">0</span> điểm</div>
                    </div>
                </div>
            </div>

            <!-- Cart Table Items -->
            <div class="flex-1 overflow-y-auto custom-scroll p-3 space-y-2" id="cartContainer">
                <!-- Cart items rendered here via JS -->
            </div>

            <!-- Bill Summary & Payment Section -->
            <div class="bg-white border-t border-slate-200 p-3.5 space-y-3 shrink-0 shadow-inner">
                <!-- Coupon Input -->
                <div class="flex items-center gap-2">
                    <input type="text" id="couponInput" placeholder="Mã giảm giá (nếu có)..." class="flex-1 uppercase px-3 py-1.5 text-xs border border-slate-300 rounded-lg outline-none font-mono focus:border-rose-500">
                    <button type="button" onclick="applyCoupon()" class="px-3 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-lg text-xs transition">Áp dụng</button>
                </div>

                <!-- Price Calculations -->
                <div class="space-y-1.5 text-xs text-slate-600 border-t border-slate-100 pt-2">
                    <div class="flex justify-between">
                        <span>Tổng tiền hàng (<span id="cartCount">0</span> món):</span>
                        <span class="font-bold text-slate-800" id="subtotalDisplay">0đ</span>
                    </div>
                    <div class="flex justify-between text-rose-600" id="discountRow" style="display:none;">
                        <span>Giảm giá / Chiết khấu:</span>
                        <span class="font-bold" id="discountDisplay">-0đ</span>
                    </div>
                    <div class="flex justify-between text-base font-extrabold text-slate-900 pt-1 border-t border-dashed border-slate-200">
                        <span>KHÁCH PHẢI TRẢ:</span>
                        <span class="text-xl text-rose-600" id="finalTotalDisplay">0đ</span>
                    </div>
                </div>

                <!-- Payment Method Buttons -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Phương thức thanh toán</label>
                    <div class="grid grid-cols-4 gap-1.5 text-xs font-semibold">
                        <button type="button" onclick="setPaymentMethod('cash')" class="pay-btn py-2 px-1 rounded-lg border text-center transition bg-rose-50 border-rose-500 text-rose-700 font-bold" data-method="cash">
                            💵 Tiền mặt
                        </button>
                        <button type="button" onclick="setPaymentMethod('bank_transfer')" class="pay-btn py-2 px-1 rounded-lg border text-center transition bg-white border-slate-200 text-slate-700 hover:bg-slate-50" data-method="bank_transfer">
                            📱 Chuyển QR
                        </button>
                        <button type="button" onclick="setPaymentMethod('card')" class="pay-btn py-2 px-1 rounded-lg border text-center transition bg-white border-slate-200 text-slate-700 hover:bg-slate-50" data-method="card">
                            💳 Thẻ POS
                        </button>
                        <button type="button" onclick="setPaymentMethod('ewallet')" class="pay-btn py-2 px-1 rounded-lg border text-center transition bg-white border-slate-200 text-slate-700 hover:bg-slate-50" data-method="ewallet">
                            📲 Ví ĐT
                        </button>
                    </div>
                </div>

                <!-- Cash Calculator (Visible only when method == cash) -->
                <div id="cashCalcSection" class="p-2.5 bg-slate-50 border border-slate-200 rounded-xl space-y-2 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="text-slate-600 font-medium whitespace-nowrap">Tiền khách đưa:</span>
                        <input type="number" id="receivedCashInput" oninput="calculateChange()" placeholder="0"
                               class="flex-1 px-3 py-1.5 bg-white border border-slate-300 rounded-lg text-sm font-bold text-slate-900 outline-none focus:border-rose-500 font-mono text-right">
                    </div>
                    <!-- Quick cash denomination chips -->
                    <div class="flex flex-wrap gap-1">
                        <button type="button" onclick="quickCash('exact')" class="px-2 py-1 bg-white border border-slate-200 rounded text-[11px] hover:bg-slate-100 font-medium">Đủ tiền</button>
                        <button type="button" onclick="quickCash(100000)" class="px-2 py-1 bg-white border border-slate-200 rounded text-[11px] hover:bg-slate-100 font-medium">100k</button>
                        <button type="button" onclick="quickCash(200000)" class="px-2 py-1 bg-white border border-slate-200 rounded text-[11px] hover:bg-slate-100 font-medium">200k</button>
                        <button type="button" onclick="quickCash(500000)" class="px-2 py-1 bg-white border border-slate-200 rounded text-[11px] hover:bg-slate-100 font-medium">500k</button>
                        <button type="button" onclick="quickCash(1000000)" class="px-2 py-1 bg-white border border-slate-200 rounded text-[11px] hover:bg-slate-100 font-medium">1.000k</button>
                    </div>
                    <div class="flex justify-between items-center pt-1 border-t border-slate-200 text-xs font-semibold">
                        <span>Tiền thối lại khách:</span>
                        <span id="changeDisplay" class="text-sm font-black text-emerald-600 font-mono">0đ</span>
                    </div>
                </div>

                <!-- Submit / Checkout Action Button -->
                <button type="button" id="checkoutBtn" onclick="submitPOSSale()"
                        class="w-full py-3.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-base shadow-lg shadow-rose-600/30 transition flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>THANH TOÁN & IN HÓA ĐƠN</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Variant Picker Modal (When clicking multi-size/multi-color product) -->
    <div id="variantModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-md w-full p-5 shadow-2xl space-y-4">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="font-bold text-base text-slate-800" id="modalProductName">Tên sản phẩm</h3>
                    <div class="text-xs text-rose-600 font-extrabold mt-0.5" id="modalProductPrice">0đ</div>
                </div>
                <button onclick="closeVariantModal()" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
            </div>

            <!-- Size Selector -->
            <div id="modalSizeGroup" class="space-y-1.5">
                <label class="text-xs font-bold text-slate-600">Chọn Kích thước (Size):</label>
                <div class="flex flex-wrap gap-2" id="modalSizeOptions"></div>
            </div>

            <!-- Color Selector -->
            <div id="modalColorGroup" class="space-y-1.5">
                <label class="text-xs font-bold text-slate-600">Chọn Màu sắc:</label>
                <div class="flex flex-wrap gap-2" id="modalColorOptions"></div>
            </div>

            <div class="flex justify-between items-center pt-3 border-t border-slate-100">
                <span class="text-xs text-slate-500">Tồn kho tại quầy: <strong id="modalStockDisplay" class="text-slate-800">0</strong></span>
                <button type="button" onclick="confirmAddVariant()" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-rose-600/20">
                    Thêm vào giỏ
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript Logic -->
    <script>
        const STORE_ID = {{ $currentStore->id }};
        let currentProducts = [];
        let cart = [];
        let selectedCustomer = null;
        let selectedPaymentMethod = 'cash';
        let appliedCouponCode = null;
        let couponDiscount = 0;
        let activeVariantProduct = null;
        let selectedSize = null;
        let selectedColor = null;

        // Init load
        document.addEventListener('DOMContentLoaded', () => {
            loadProducts();

            // Setup search debounce
            const searchInput = document.getElementById('searchInput');
            let debounceTimer;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    loadProducts(e.target.value);
                }, 300);
            });

            // Barcode enter key
            const barcodeInput = document.getElementById('barcodeInput');
            barcodeInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    handleBarcode(e.target.value.trim());
                    e.target.value = '';
                }
            });

            // Global Keyboard Shortcuts
            window.addEventListener('keydown', (e) => {
                if (e.key === 'F2') {
                    e.preventDefault();
                    document.getElementById('searchInput').focus();
                } else if (e.key === 'F4') {
                    e.preventDefault();
                    document.getElementById('barcodeInput').focus();
                } else if (e.key === 'F9') {
                    e.preventDefault();
                    submitPOSSale();
                }
            });
        });

        function changeStore(storeId) {
            window.location.href = "{{ route('pos.index') }}?store_id=" + storeId;
        }

        async function loadProducts(query = '', categoryId = 'all') {
            const grid = document.getElementById('productGrid');
            try {
                const res = await fetch(`{{ route('pos.products') }}?store_id=${STORE_ID}&q=${encodeURIComponent(query)}&category_id=${categoryId}`);
                const data = await res.json();
                if (data.success) {
                    currentProducts = data.products;
                    renderProductGrid(currentProducts);
                }
            } catch (err) {
                console.error(err);
                grid.innerHTML = '<div class="col-span-full py-10 text-center text-red-500">Lỗi nạp sản phẩm!</div>';
            }
        }

        function filterCategory(catId) {
            document.querySelectorAll('.cat-btn').forEach(btn => {
                btn.classList.remove('bg-rose-600', 'text-white', 'shadow-sm');
                btn.classList.add('bg-white', 'text-slate-700');
            });
            const activeBtn = document.querySelector(`.cat-btn[data-cat="${catId}"]`);
            if (activeBtn) {
                activeBtn.classList.remove('bg-white', 'text-slate-700');
                activeBtn.classList.add('bg-rose-600', 'text-white', 'shadow-sm');
            }
            loadProducts(document.getElementById('searchInput').value, catId);
        }

        function renderProductGrid(products) {
            const grid = document.getElementById('productGrid');
            if (!products || products.length === 0) {
                grid.innerHTML = '<div class="col-span-full py-16 text-center text-slate-400 font-medium">Không tìm thấy sản phẩm nào phù hợp.</div>';
                return;
            }

            grid.innerHTML = products.map(p => {
                const isOutOfStock = p.stock <= 0;
                const stockColor = p.stock > 10 ? 'bg-emerald-100 text-emerald-700' : (p.stock > 0 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700');
                const stockLabel = p.stock > 0 ? `Còn ${p.stock}` : 'Hết hàng';

                return `
                    <div onclick="onProductClick(${p.id})"
                         class="group bg-white rounded-xl border border-slate-200 hover:border-rose-400 p-2.5 flex flex-col justify-between transition cursor-pointer hover:shadow-md relative ${isOutOfStock ? 'opacity-50 pointer-events-none' : ''}">
                        <div>
                            <div class="w-full h-32 bg-slate-100 rounded-lg overflow-hidden mb-2 relative">
                                <img src="${p.image_url}" alt="${p.name}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                <span class="absolute top-1.5 right-1.5 px-2 py-0.5 rounded text-[10px] font-bold ${stockColor}">
                                    ${stockLabel}
                                </span>
                            </div>
                            <div class="text-[11px] text-slate-400 font-mono">${p.sku}</div>
                            <h4 class="text-xs font-semibold text-slate-800 line-clamp-2 leading-tight">${p.name}</h4>
                        </div>
                        <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs font-extrabold text-rose-600">${formatVND(p.effective_price)}</span>
                            <span class="w-6 h-6 rounded-lg bg-rose-50 text-rose-600 group-hover:bg-rose-600 group-hover:text-white flex items-center justify-center font-bold text-xs transition">+</span>
                        </div>
                    </div>
                `;
            }).join('');
        }

        async function handleBarcode(code) {
            if (!code) return;
            try {
                const res = await fetch(`{{ route('pos.barcode') }}?store_id=${STORE_ID}&code=${encodeURIComponent(code)}`);
                const data = await res.json();
                if (data.success && data.product) {
                    onProductClick(data.product.id, data.product);
                } else {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'Không tìm thấy sản phẩm!', timer: 2000, showConfirmButton: false });
                }
            } catch (err) {
                console.error(err);
            }
        }

        function onProductClick(productId, productObj = null) {
            const product = productObj || currentProducts.find(p => p.id === productId);
            if (!product) return;

            if (product.stock <= 0) {
                Swal.fire({ icon: 'warning', title: 'Hết hàng', text: 'Sản phẩm này đã hết hàng tại quầy.', timer: 2000, showConfirmButton: false });
                return;
            }

            const hasSizes = product.sizes && product.sizes.length > 0;
            const hasColors = product.colors && product.colors.length > 0;

            if (hasSizes || hasColors) {
                openVariantModal(product);
            } else {
                addToCart(product, null, null);
            }
        }

        function openVariantModal(product) {
            activeVariantProduct = product;
            document.getElementById('modalProductName').textContent = product.name;
            document.getElementById('modalProductPrice').textContent = formatVND(product.effective_price);
            document.getElementById('modalStockDisplay').textContent = product.stock;

            const sizeContainer = document.getElementById('modalSizeOptions');
            const sizeGroup = document.getElementById('modalSizeGroup');
            if (product.sizes && product.sizes.length > 0) {
                sizeGroup.classList.remove('hidden');
                selectedSize = product.sizes[0];
                sizeContainer.innerHTML = product.sizes.map((s, idx) => `
                    <button type="button" onclick="selectVariantSize('${s}')"
                            class="variant-size-btn px-3 py-1.5 rounded-lg border text-xs font-semibold ${idx === 0 ? 'bg-rose-600 text-white border-rose-600' : 'bg-white text-slate-700 border-slate-200'}">
                        ${s}
                    </button>
                `).join('');
            } else {
                sizeGroup.classList.add('hidden');
                selectedSize = null;
            }

            const colorContainer = document.getElementById('modalColorOptions');
            const colorGroup = document.getElementById('modalColorGroup');
            if (product.colors && product.colors.length > 0) {
                colorGroup.classList.remove('hidden');
                selectedColor = product.colors[0];
                colorContainer.innerHTML = product.colors.map((c, idx) => `
                    <button type="button" onclick="selectVariantColor('${c}')"
                            class="variant-color-btn px-3 py-1.5 rounded-lg border text-xs font-semibold ${idx === 0 ? 'bg-rose-600 text-white border-rose-600' : 'bg-white text-slate-700 border-slate-200'}">
                        ${c}
                    </button>
                `).join('');
            } else {
                colorGroup.classList.add('hidden');
                selectedColor = null;
            }

            document.getElementById('variantModal').classList.remove('hidden');
        }

        function selectVariantSize(size) {
            selectedSize = size;
            document.querySelectorAll('.variant-size-btn').forEach(btn => {
                if (btn.textContent.trim() === size) {
                    btn.className = 'variant-size-btn px-3 py-1.5 rounded-lg border text-xs font-semibold bg-rose-600 text-white border-rose-600';
                } else {
                    btn.className = 'variant-size-btn px-3 py-1.5 rounded-lg border text-xs font-semibold bg-white text-slate-700 border-slate-200';
                }
            });
        }

        function selectVariantColor(color) {
            selectedColor = color;
            document.querySelectorAll('.variant-color-btn').forEach(btn => {
                if (btn.textContent.trim() === color) {
                    btn.className = 'variant-color-btn px-3 py-1.5 rounded-lg border text-xs font-semibold bg-rose-600 text-white border-rose-600';
                } else {
                    btn.className = 'variant-color-btn px-3 py-1.5 rounded-lg border text-xs font-semibold bg-white text-slate-700 border-slate-200';
                }
            });
        }

        function closeVariantModal() {
            document.getElementById('variantModal').classList.add('hidden');
            activeVariantProduct = null;
        }

        function confirmAddVariant() {
            if (!activeVariantProduct) return;
            addToCart(activeVariantProduct, selectedSize, selectedColor);
            closeVariantModal();
        }

        function addToCart(product, size = null, color = null) {
            const key = `${product.id}_${size || ''}_${color || ''}`;
            const existing = cart.find(item => item.key === key);

            if (existing) {
                if (existing.quantity + 1 > product.stock) {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: `Chỉ còn ${product.stock} sản phẩm trong kho!`, timer: 2000, showConfirmButton: false });
                    return;
                }
                existing.quantity += 1;
            } else {
                cart.push({
                    key: key,
                    product_id: product.id,
                    name: product.name,
                    price: product.effective_price,
                    stock: product.stock,
                    size: size,
                    color: color,
                    quantity: 1,
                });
            }

            renderCart();
        }

        function updateCartQty(key, delta) {
            const item = cart.find(i => i.key === key);
            if (!item) return;

            const newQty = item.quantity + delta;
            if (newQty <= 0) {
                cart = cart.filter(i => i.key !== key);
            } else if (newQty > item.stock) {
                Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: `Tồn kho tối đa: ${item.stock}`, timer: 1500, showConfirmButton: false });
                return;
            } else {
                item.quantity = newQty;
            }
            renderCart();
        }

        function removeCartItem(key) {
            cart = cart.filter(i => i.key !== key);
            renderCart();
        }

        function renderCart() {
            const container = document.getElementById('cartContainer');
            if (cart.length === 0) {
                container.innerHTML = `
                    <div class="h-full flex flex-col items-center justify-center text-slate-400 py-12">
                        <svg class="w-12 h-12 mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <div class="text-xs font-semibold">Chưa có sản phẩm nào trong giỏ</div>
                        <div class="text-[11px]">Bấm vào sản phẩm bên trái hoặc quét barcode để thêm</div>
                    </div>
                `;
                updateSummary(0);
                return;
            }

            let subtotal = 0;
            let totalCount = 0;

            container.innerHTML = cart.map(item => {
                const lineTotal = item.price * item.quantity;
                subtotal += lineTotal;
                totalCount += item.quantity;

                return `
                    <div class="bg-white p-2.5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between gap-2">
                        <div class="flex-1 min-w-0">
                            <h5 class="text-xs font-semibold text-slate-800 truncate">${item.name}</h5>
                            <div class="text-[11px] text-slate-400 flex items-center gap-1.5 mt-0.5">
                                ${item.size ? `<span class="bg-slate-100 px-1.5 py-0.5 rounded text-slate-600">Size: ${item.size}</span>` : ''}
                                ${item.color ? `<span class="bg-slate-100 px-1.5 py-0.5 rounded text-slate-600">Màu: ${item.color}</span>` : ''}
                                <span>${formatVND(item.price)}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <button type="button" onclick="updateCartQty('${item.key}', -1)" class="w-6 h-6 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs flex items-center justify-center">-</button>
                            <span class="w-7 text-center font-bold text-xs">${item.quantity}</span>
                            <button type="button" onclick="updateCartQty('${item.key}', 1)" class="w-6 h-6 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs flex items-center justify-center">+</button>
                        </div>
                        <div class="text-right shrink-0 min-w-[70px]">
                            <div class="text-xs font-extrabold text-slate-800">${formatVND(lineTotal)}</div>
                            <button type="button" onclick="removeCartItem('${item.key}')" class="text-[10px] text-red-500 hover:underline">Xóa</button>
                        </div>
                    </div>
                `;
            }).join('');

            document.getElementById('cartCount').textContent = totalCount;
            updateSummary(subtotal);
        }

        function updateSummary(subtotal) {
            let discount = couponDiscount;
            if (selectedCustomer && selectedCustomer.discount_percent > 0) {
                discount += (subtotal * selectedCustomer.discount_percent) / 100;
            }

            const finalTotal = Math.max(0, subtotal - discount);

            document.getElementById('subtotalDisplay').textContent = formatVND(subtotal);
            if (discount > 0) {
                document.getElementById('discountRow').style.display = 'flex';
                document.getElementById('discountDisplay').textContent = `-${formatVND(discount)}`;
            } else {
                document.getElementById('discountRow').style.display = 'none';
            }
            document.getElementById('finalTotalDisplay').textContent = formatVND(finalTotal);

            calculateChange();
        }

        function setPaymentMethod(method) {
            selectedPaymentMethod = method;
            document.querySelectorAll('.pay-btn').forEach(btn => {
                if (btn.dataset.method === method) {
                    btn.className = 'pay-btn py-2 px-1 rounded-lg border text-center transition bg-rose-50 border-rose-500 text-rose-700 font-bold';
                } else {
                    btn.className = 'pay-btn py-2 px-1 rounded-lg border text-center transition bg-white border-slate-200 text-slate-700 hover:bg-slate-50';
                }
            });

            const cashSection = document.getElementById('cashCalcSection');
            if (method === 'cash') {
                cashSection.classList.remove('hidden');
                calculateChange();
            } else {
                cashSection.classList.add('hidden');
            }
        }

        function quickCash(amount) {
            const finalTotal = parseNumberFromVND(document.getElementById('finalTotalDisplay').textContent);
            const receivedInput = document.getElementById('receivedCashInput');
            if (amount === 'exact') {
                receivedInput.value = finalTotal;
            } else {
                receivedInput.value = amount;
            }
            calculateChange();
        }

        function calculateChange() {
            const finalTotal = parseNumberFromVND(document.getElementById('finalTotalDisplay').textContent);
            const received = parseFloat(document.getElementById('receivedCashInput').value) || 0;
            const change = Math.max(0, received - finalTotal);
            document.getElementById('changeDisplay').textContent = formatVND(change);
        }

        async function searchCustomer() {
            const phone = document.getElementById('customerPhoneInput').value.trim();
            if (!phone) return;

            try {
                const res = await fetch(`{{ route('pos.customer.search') }}?phone=${encodeURIComponent(phone)}`);
                const data = await res.json();
                if (data.success && data.customer) {
                    selectedCustomer = data.customer;
                    document.getElementById('customerInfoBanner').classList.remove('hidden');
                    document.getElementById('resetCustomerBtn').classList.remove('hidden');
                    document.getElementById('custNameDisplay').textContent = selectedCustomer.name;
                    document.getElementById('custPhoneDisplay').textContent = selectedCustomer.phone;
                    document.getElementById('custTierDisplay').textContent = selectedCustomer.tier;
                    document.getElementById('custPointsDisplay').textContent = selectedCustomer.points;
                    document.getElementById('custDiscountBadge').textContent = `Giảm ${selectedCustomer.discount_percent}%`;
                    renderCart();
                } else {
                    Swal.fire({ icon: 'info', title: 'Chưa có thông tin', text: 'Khách hàng chưa đăng ký thành viên.', timer: 2000, showConfirmButton: false });
                }
            } catch (err) {
                console.error(err);
            }
        }

        function resetCustomer() {
            selectedCustomer = null;
            document.getElementById('customerInfoBanner').classList.add('hidden');
            document.getElementById('resetCustomerBtn').classList.add('hidden');
            document.getElementById('customerPhoneInput').value = '';
            renderCart();
        }

        function applyCoupon() {
            const code = document.getElementById('couponInput').value.trim().toUpperCase();
            if (!code) return;
            // Simulated check or simple coupon handler
            appliedCouponCode = code;
            Swal.fire({ icon: 'success', title: 'Đã nhận mã giảm giá', text: code, timer: 1500, showConfirmButton: false });
        }

        async function submitPOSSale() {
            if (cart.length === 0) {
                Swal.fire({ icon: 'warning', title: 'Giỏ hàng trống', text: 'Vui lòng chọn ít nhất 1 sản phẩm để thanh toán.' });
                return;
            }

            const finalTotal = parseNumberFromVND(document.getElementById('finalTotalDisplay').textContent);
            let receivedAmount = finalTotal;

            if (selectedPaymentMethod === 'cash') {
                receivedAmount = parseFloat(document.getElementById('receivedCashInput').value) || 0;
                if (receivedAmount < finalTotal) {
                    Swal.fire({ icon: 'warning', title: 'Tiền khách đưa chưa đủ', text: `Cần thanh toán ${formatVND(finalTotal)} (Thiếu: ${formatVND(finalTotal - receivedAmount)})` });
                    return;
                }
            }

            const btn = document.getElementById('checkoutBtn');
            btn.disabled = true;
            btn.innerHTML = 'Đang xử lý đơn hàng...';

            const payload = {
                store_id: STORE_ID,
                items: cart.map(i => ({
                    product_id: i.product_id,
                    quantity: i.quantity,
                    size: i.size,
                    color: i.color,
                })),
                payment: {
                    method: selectedPaymentMethod,
                    received_amount: receivedAmount,
                },
                customer: selectedCustomer ? {
                    user_id: selectedCustomer.id,
                    name: selectedCustomer.name,
                    phone: selectedCustomer.phone,
                } : {
                    name: 'Khách lẻ tại quầy',
                },
                coupon_code: appliedCouponCode,
            };

            try {
                const res = await fetch(`{{ route('pos.sales.store') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();
                if (data.success) {
                    // Reset cart
                    cart = [];
                    resetCustomer();
                    appliedCouponCode = null;
                    document.getElementById('receivedCashInput').value = '';
                    renderCart();
                    loadProducts(); // refresh stock

                    // SweetAlert confirmation with print button
                    Swal.fire({
                        icon: 'success',
                        title: 'Thanh toán thành công!',
                        text: `Mã hóa đơn: ${data.order_code} - Tổng tiền: ${formatVND(data.total)}`,
                        showCancelButton: true,
                        confirmButtonText: '🖨️ In Hóa Đơn',
                        cancelButtonText: 'Đóng & Đơn Mới',
                        confirmButtonColor: '#e11d48',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.open(data.receipt_url, '_blank', 'width=450,height=650');
                        }
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Thanh toán thất bại', text: data.message || 'Có lỗi xảy ra.' });
                }
            } catch (err) {
                console.error(err);
                Swal.fire({ icon: 'error', title: 'Lỗi mạng hoặc hệ thống', text: 'Vui lòng kiểm tra lại kết nối.' });
            } finally {
                btn.disabled = false;
                btn.innerHTML = `
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>THANH TOÁN & IN HÓA ĐƠN</span>
                `;
            }
        }

        function formatVND(val) {
            return new Intl.NumberFormat('vi-VN').format(Math.round(val)) + 'đ';
        }

        function parseNumberFromVND(str) {
            return parseFloat(str.replace(/[^\d]/g, '')) || 0;
        }
    </script>
</body>
</html>
