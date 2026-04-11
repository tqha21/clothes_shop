@extends('admin.layouts.app')

@section('page-title', 'Lịch Sử Biến Động Kho')
@section('page-description', 'Theo dõi toàn bộ quá trình nhập, xuất và điều chỉnh tồn kho')

@section('content')
<div class="space-y-6">
    <!-- Back Button & Page Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.inventory.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-red-600 transition-colors group">
            <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Quay lại bảng kiểm kho
        </a>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-all font-semibold text-xs uppercase tracking-wider shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Xuất file Excel
            </button>
        </div>
    </div>

    <!-- Inventory Logs Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Thời gian</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Sản phẩm</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest text-center">Người thao tác</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest text-center">Loại biến động</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest text-center">Thay đổi</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Ghi chú</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-bold text-gray-900">{{ $log->created_at->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $log->created_at->format('H:i:s') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($log->product)
                                <div class="w-8 h-8 rounded bg-gray-100 flex-shrink-0">
                                    <img src="{{ asset('storage/' . $log->product->image) }}" class="w-full h-full object-cover rounded shadow-sm">
                                </div>
                                <span class="text-xs font-bold text-gray-700 line-clamp-1 truncate max-w-[150px]">{{ $log->product->name }}</span>
                                @else
                                <span class="text-xs text-gray-400 italic">Sản phẩm đã bị xoá</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($log->user)
                            <div class="flex flex-col items-center">
                                <span class="text-[11px] font-bold text-gray-900">{{ $log->user->name }}</span>
                                <span class="text-[9px] px-1.5 py-0.5 bg-gray-100 text-gray-500 rounded uppercase tracking-tighter">{{ $log->user->role }}</span>
                            </div>
                            @elseif($log->type === 'sale')
                            <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-100">HỆ THỐNG</span>
                            @else
                            <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center">
                                @if($log->type === 'import')
                                    <span class="px-2.5 py-1 bg-green-50 text-green-600 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-green-100">Nhập hàng</span>
                                @elseif($log->type === 'export')
                                    <span class="px-2.5 py-1 bg-red-50 text-red-600 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-red-100">Xuất kho</span>
                                @elseif($log->type === 'adjustment')
                                    <span class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-blue-100">Điều chỉnh</span>
                                @else
                                    <span class="px-2.5 py-1 bg-purple-50 text-purple-600 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-purple-100">Bán hàng</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($log->quantity_changed > 0)
                                <span class="text-sm font-black text-green-600">+ {{ number_format($log->quantity_changed) }}</span>
                            @else
                                <span class="text-sm font-black text-red-600">{{ number_format($log->quantity_changed) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs text-gray-500 italic max-w-xs truncate">{{ $log->note ?: 'Nhiều nội dung...' }}</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <p class="text-gray-400 font-medium">Chưa có lịch sử biến động kho.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
