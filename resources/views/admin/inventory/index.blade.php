@extends('admin.layouts.app')

@section('page-title', 'Bảng Kiểm Kho')
@section('page-description', 'Quản lý số lượng tồn kho và điều chỉnh sản phẩm')

@section('content')
<div class="space-y-6">
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm animate-fadeIn">
        <div class="flex items-center">
            <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm">
        <div class="flex items-center">
            <svg class="h-5 w-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Actions Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="relative flex-1 max-w-md">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input type="text" placeholder="Tìm kiếm sản phẩm..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all outline-none text-sm shadow-sm">
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.inventory.history') }}" class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-semibold text-sm shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Lịch sử kho
            </a>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Sản phẩm</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest text-center">Danh mục</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest text-center">Tồn kho hiện tại</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest text-center">Trạng thái</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($products as $product)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0">
                                    <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-gray-900 truncate">{{ $product->name }}</p>
                                    <p class="text-[11px] text-gray-400 font-mono mt-0.5 uppercase tracking-tighter">SKU: PROD-{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-md text-[11px] font-bold uppercase tracking-wider">
                                {{ $product->category->name }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="text-base font-bold text-gray-900">{{ number_format($product->stock) }}</span>
                            <span class="text-xs text-gray-400 font-medium ml-1">cái</span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex justify-center">
                                @if($product->stock <= 0)
                                    <span class="flex items-center gap-1.5 px-2.5 py-1 bg-red-50 text-red-600 rounded-full text-[10px] font-bold uppercase tracking-wider border border-red-100">
                                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span>
                                        Hết hàng
                                    </span>
                                @elseif($product->stock < 10)
                                    <span class="flex items-center gap-1.5 px-2.5 py-1 bg-yellow-50 text-yellow-600 rounded-full text-[10px] font-bold uppercase tracking-wider border border-yellow-100">
                                        <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></span>
                                        Sắp hết
                                    </span>
                                @else
                                    <span class="flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-600 rounded-full text-[10px] font-bold uppercase tracking-wider border border-green-100">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                        Còn hàng
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <button onclick="openInventoryModal({{ json_encode($product) }})" 
                                    class="inline-flex items-center gap-2 px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-all text-sm font-bold shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Điều chỉnh
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
        
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative z-10 transform transition-all animate-modalIn border border-gray-200">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50 rounded-t-2xl">
                <div>
                    <h3 class="text-lg font-bold text-gray-900" id="product-name-modal">Điều chỉnh kho sản phẩm</h3>
                    <p class="text-xs text-gray-500 font-medium">Nhập, xuất hoặc cập nhật tồn kho thủ công</p>
                </div>
                <button onclick="closeInventoryModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18"></path></svg>
                </button>
            </div>

            <form action="{{ route('admin.inventory.update') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <input type="hidden" name="product_id" id="modal-product-id">
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Loại thao tác</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="import" class="sr-only peer" checked>
                                <div class="px-3 py-3 border-2 border-gray-100 rounded-xl flex flex-col items-center gap-1 hover:bg-gray-50 peer-checked:border-green-500 peer-checked:bg-green-50 transition-all">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    <span class="text-[11px] font-bold text-gray-600 peer-checked:text-green-700">NHẬP HÀNG</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="export" class="sr-only peer">
                                <div class="px-3 py-3 border-2 border-gray-100 rounded-xl flex flex-col items-center gap-1 hover:bg-gray-50 peer-checked:border-red-500 peer-checked:bg-red-50 transition-all">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                    <span class="text-[11px] font-bold text-gray-600 peer-checked:text-red-700">XUẤT HÀNG</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="adjustment" class="sr-only peer">
                                <div class="px-3 py-3 border-2 border-gray-100 rounded-xl flex flex-col items-center gap-1 hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"></path></svg>
                                    <span class="text-[11px] font-bold text-gray-600 peer-checked:text-blue-700">CẬP NHẬT</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Số lượng</label>
                        <input type="number" name="quantity" required min="1" 
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all outline-none font-bold text-gray-900"
                               placeholder="VD: 50">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Tồn hiện tại</label>
                        <div class="w-full px-4 py-3 bg-gray-100 border border-gray-100 rounded-xl text-gray-500 font-bold" id="current-stock-display">
                            0
                        </div>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Lý do / Ghi chú</label>
                        <textarea name="note" rows="3" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all outline-none text-sm placeholder:text-gray-400"
                                placeholder="Nhập lý do thay đổi kho..."></textarea>
                    </div>
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeInventoryModal()" class="flex-1 px-4 py-3 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-50 transition-colors">
                        HUỶ BỎ
                    </button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition-all shadow-lg shadow-red-600/20">
                        XÁC NHẬN
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openInventoryModal(product) {
        document.getElementById('modal-product-id').value = product.id;
        document.getElementById('product-name-modal').innerText = 'Điều chỉnh kho: ' + product.name;
        document.getElementById('current-stock-display').innerText = product.stock;
        document.getElementById('inventory-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeInventoryModal() {
        document.getElementById('inventory-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>

<style>
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.95) translateY(-20px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    .animate-modalIn {
        animation: modalIn 0.3s ease-out forwards;
    }
    .animate-fadeIn {
        animation: fadeIn 0.4s ease-out forwards;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
