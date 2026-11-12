@extends('admin.layouts.app')

@section('page-title', 'Bảng Kiểm Kho Đa Cửa Hàng')
@section('page-description', 'Quản lý tồn kho theo từng chi nhánh, nhập/xuất và điều chuyển kho nội bộ')

@section('content')
<div class="space-y-6">
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm animate-fadeIn">
        <div class="flex items-center">
            <svg class="h-5 w-5 text-green-500 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm">
        <div class="flex items-center">
            <svg class="h-5 w-5 text-red-500 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Store Tabs & Filter Header -->
    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm space-y-3">
        <!-- Store Selection Chips -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs">
            <span class="text-gray-400 font-bold uppercase tracking-wider whitespace-nowrap">Kho / Chi nhánh:</span>
            <a href="{{ route('admin.inventory.index', request()->except('store_id')) }}"
               class="px-3.5 py-1.5 rounded-xl font-bold whitespace-nowrap transition {{ empty($selectedStoreId) ? 'bg-red-600 text-white shadow-sm shadow-red-600/20' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                🏛️ Toàn Hệ Thống (Kho Tổng)
            </a>
            @foreach($stores as $st)
            <a href="{{ route('admin.inventory.index', array_merge(request()->all(), ['store_id' => $st->id])) }}"
               class="px-3.5 py-1.5 rounded-xl font-bold whitespace-nowrap transition {{ ($selectedStoreId ?? '') == $st->id ? 'bg-red-600 text-white shadow-sm shadow-red-600/20' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                📍 {{ $st->name }} ({{ $st->city }})
            </a>
            @endforeach
        </div>

        <!-- Actions & Search Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 pt-2 border-t border-gray-100">
            <form method="GET" action="{{ route('admin.inventory.index') }}" class="relative flex-1 max-w-md">
                @if($selectedStoreId)
                <input type="hidden" name="store_id" value="{{ $selectedStoreId }}">
                @endif
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm sản phẩm theo tên hoặc SKU..."
                       class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all outline-none text-sm">
            </form>

            <div class="flex items-center gap-2.5">
                <button onclick="openTransferModal()" class="flex items-center gap-2 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl transition font-bold text-xs shadow-sm">
                    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    Chuyển Kho Chi Nhánh
                </button>

                <a href="{{ route('admin.inventory.history') }}" class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition font-bold text-xs shadow-sm">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Lịch Sử Biến Động
                </a>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100 text-gray-400 font-bold uppercase tracking-wider text-[11px]">
                        <th class="px-6 py-4">Sản phẩm</th>
                        <th class="px-6 py-4 text-center">Danh mục</th>
                        <th class="px-6 py-4 text-center">
                            {{ $selectedStoreId ? 'Tồn tại chi nhánh' : 'Tổng tồn hệ thống' }}
                        </th>
                        <th class="px-6 py-4 text-center">Trạng thái</th>
                        <th class="px-6 py-4 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @foreach($products as $product)
                    @php
                        $displayStock = $selectedStoreId ? ($storeStocksMap[$product->id] ?? 0) : $product->stock;
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0">
                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default-product.png') }}" class="w-full h-full object-cover">
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-gray-900 truncate">{{ $product->name }}</p>
                                    <p class="text-[11px] text-gray-400 font-mono mt-0.5 uppercase tracking-tighter">{{ $product->sku }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-semibold">
                                {{ $product->category->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-base font-black text-gray-900 font-mono">{{ number_format($displayStock) }}</span>
                            <span class="text-xs text-gray-400 font-medium ml-1">cái</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($displayStock <= 0)
                                <span class="px-2.5 py-1 bg-red-50 text-red-600 rounded-full text-[10px] font-bold uppercase tracking-wider border border-red-100">
                                    Hết hàng
                                </span>
                            @elseif($displayStock < 10)
                                <span class="px-2.5 py-1 bg-yellow-50 text-yellow-600 rounded-full text-[10px] font-bold uppercase tracking-wider border border-yellow-100">
                                    Sắp hết
                                </span>
                            @else
                                <span class="px-2.5 py-1 bg-green-50 text-green-600 rounded-full text-[10px] font-bold uppercase tracking-wider border border-green-100">
                                    Còn hàng
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="openInventoryModal({{ json_encode($product) }}, {{ $displayStock }})" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl transition text-xs font-bold shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Nhập / Xuất / Sửa
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
            {{ $products->links() }}
        </div>
    </div>
</div>

<!-- Stock Adjustment Modal -->
<div id="inventory-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeInventoryModal()"></div>
        
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative z-10 border border-gray-200">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50 rounded-t-2xl">
                <div>
                    <h3 class="text-base font-bold text-gray-900" id="product-name-modal">Điều chỉnh kho sản phẩm</h3>
                    <p class="text-xs text-gray-500">Nhập hàng, xuất hủy hoặc kiểm kê điều chỉnh</p>
                </div>
                <button onclick="closeInventoryModal()" class="text-gray-400 hover:text-gray-600 text-lg font-bold">✕</button>
            </div>

            <form action="{{ route('admin.inventory.update') }}" method="POST" class="p-6 space-y-4 text-xs">
                @csrf
                <input type="hidden" name="product_id" id="modal-product-id">

                <!-- Select Store -->
                <div>
                    <label class="block font-bold text-gray-700 uppercase tracking-wider text-[11px] mb-1.5">Áp dụng cho cửa hàng / kho:</label>
                    <select name="store_id" id="modal-store-id" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-xl outline-none font-semibold text-xs">
                        @foreach($stores as $st)
                        <option value="{{ $st->id }}" {{ ($selectedStoreId ?? '') == $st->id ? 'selected' : '' }}>{{ $st->name }} ({{ $st->city }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block font-bold text-gray-700 uppercase tracking-wider text-[11px] mb-2">Loại thao tác</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="import" class="sr-only peer" checked>
                            <div class="px-3 py-2.5 border-2 border-gray-200 rounded-xl flex flex-col items-center gap-1 peer-checked:border-green-500 peer-checked:bg-green-50 transition">
                                <span class="font-bold text-gray-700 peer-checked:text-green-700">NHẬP HÀNG</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="export" class="sr-only peer">
                            <div class="px-3 py-2.5 border-2 border-gray-200 rounded-xl flex flex-col items-center gap-1 peer-checked:border-red-500 peer-checked:bg-red-50 transition">
                                <span class="font-bold text-gray-700 peer-checked:text-red-700">XUẤT HÀNG</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="adjustment" class="sr-only peer">
                            <div class="px-3 py-2.5 border-2 border-gray-200 rounded-xl flex flex-col items-center gap-1 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition">
                                <span class="font-bold text-gray-700 peer-checked:text-blue-700">ĐIỀU CHỈNH</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-gray-700 uppercase tracking-wider text-[11px] mb-1.5">Số lượng</label>
                        <input type="number" name="quantity" required min="0" placeholder="VD: 50"
                               class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-xl font-bold font-mono text-sm outline-none focus:border-red-500">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 uppercase tracking-wider text-[11px] mb-1.5">Tồn hiện tại</label>
                        <div class="w-full px-3 py-2.5 bg-gray-100 border border-gray-200 rounded-xl text-gray-700 font-bold font-mono text-sm" id="current-stock-display">
                            0
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 uppercase tracking-wider text-[11px] mb-1.5">Lý do / Ghi chú</label>
                    <textarea name="note" rows="2" placeholder="Ví dụ: Nhập hàng đợt 1, kiểm kê định kỳ..."
                              class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:border-red-500"></textarea>
                </div>

                <div class="flex gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeInventoryModal()" class="flex-1 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-50">
                        HUỶ BỎ
                    </button>
                    <button type="submit" class="flex-1 py-2.5 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 shadow-md shadow-red-600/20">
                        XÁC NHẬN
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Stock Transfer Modal (Chuyển kho giữa 2 chi nhánh) -->
<div id="transfer-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeTransferModal()"></div>

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative z-10 border border-gray-200">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50 rounded-t-2xl">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Chuyển Kho Giữa Các Chi Nhánh</h3>
                    <p class="text-xs text-gray-500">Điều chuyển hàng hóa từ cửa hàng này sang cửa hàng khác</p>
                </div>
                <button onclick="closeTransferModal()" class="text-gray-400 hover:text-gray-600 text-lg font-bold">✕</button>
            </div>

            <form action="{{ route('admin.inventory.transfer') }}" method="POST" class="p-6 space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-gray-700 uppercase tracking-wider text-[11px] mb-1.5">Chọn sản phẩm cần chuyển:</label>
                    <select name="product_id" required class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-xl outline-none font-semibold text-xs">
                        @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} (Tổng tồn: {{ $p->stock }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-gray-700 uppercase tracking-wider text-[11px] mb-1.5">Từ chi nhánh (Xuất đi):</label>
                        <select name="from_store_id" required class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-xl outline-none font-semibold text-xs">
                            @foreach($stores as $st)
                            <option value="{{ $st->id }}">{{ $st->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 uppercase tracking-wider text-[11px] mb-1.5">Đến chi nhánh (Nhận về):</label>
                        <select name="to_store_id" required class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-xl outline-none font-semibold text-xs">
                            @foreach($stores as $st)
                            <option value="{{ $st->id }}" {{ $loop->iteration == 2 ? 'selected' : '' }}>{{ $st->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 uppercase tracking-wider text-[11px] mb-1.5">Số lượng chuyển:</label>
                    <input type="number" name="quantity" required min="1" placeholder="10"
                           class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-xl font-bold font-mono text-sm outline-none focus:border-red-500">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 uppercase tracking-wider text-[11px] mb-1.5">Ghi chú điều chuyển:</label>
                    <input type="text" name="note" placeholder="Ví dụ: Chuyển hàng hỗ trợ chi nhánh Cầu Giấy..."
                           class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:border-red-500">
                </div>

                <div class="flex gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeTransferModal()" class="flex-1 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-50">
                        HUỶ BỎ
                    </button>
                    <button type="submit" class="flex-1 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold shadow-md">
                        THỰC HIỆN CHUYỂN KHO
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openInventoryModal(product, currentStock) {
        document.getElementById('modal-product-id').value = product.id;
        document.getElementById('product-name-modal').innerText = 'Điều chỉnh kho: ' + product.name;
        document.getElementById('current-stock-display').innerText = currentStock;
        document.getElementById('inventory-modal').classList.remove('hidden');
    }

    function closeInventoryModal() {
        document.getElementById('inventory-modal').classList.add('hidden');
    }

    function openTransferModal() {
        document.getElementById('transfer-modal').classList.remove('hidden');
    }

    function closeTransferModal() {
        document.getElementById('transfer-modal').classList.add('hidden');
    }
</script>
@endsection
