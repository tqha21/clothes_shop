@extends('admin.layouts.app')

@section('title', 'Quản Lý Yêu Thích')
@section('page-title', 'Quản Lý Sản Phẩm Yêu Thích')
@section('page-description', 'Danh sách sản phẩm được khách hàng thêm vào yêu thích')

@section('content')
{{-- Stats + Search --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="md:col-span-3 bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-500 flex-shrink-0">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $wishlists->total() }}</p>
            <p class="text-xs text-gray-500 font-medium mt-0.5">Tổng mục yêu thích</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $wishlists->pluck('user_id')->unique()->count() }}</p>
            <p class="text-xs text-gray-500 font-medium mt-0.5">Khách hàng</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Main Table --}}
    <div class="lg:col-span-2 space-y-4">
        {{-- Search --}}
        <form action="{{ route('admin.wishlist.index') }}" method="GET" class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex gap-3">
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo sản phẩm hoặc khách hàng..."
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-9 pr-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-4 rounded-xl text-sm transition-colors">Tìm</button>
            @if(request('search'))
                <a href="{{ route('admin.wishlist.index') }}" class="flex items-center text-xs text-red-600 font-medium hover:underline px-2">Xóa</a>
            @endif
        </form>

        {{-- Table --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-5 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Sản Phẩm</th>
                            <th class="px-5 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Khách Hàng</th>
                            <th class="px-5 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Ngày Thêm</th>
                            <th class="px-5 py-4 text-center text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Xóa</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">
                        @forelse($wishlists as $item)
                        <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg overflow-hidden border border-gray-100 flex-shrink-0">
                                        @if($item->product)
                                            @if(str_starts_with($item->product->image ?? '', 'dummy_url|'))
                                                <img src="{{ str_replace('dummy_url|', '', $item->product->image) }}" class="w-full h-full object-cover" alt="">
                                            @else
                                                <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover" alt="">
                                            @endif
                                        @else
                                            <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        @if($item->product)
                                            <p class="text-sm font-semibold text-gray-800 truncate max-w-[160px] group-hover:text-red-600 transition-colors">{{ $item->product->name }}</p>
                                            <p class="text-xs text-gray-400">{{ $item->product->category->name ?? '-' }} &bull; {{ number_format($item->product->sale_price ?? $item->product->price) }}đ</p>
                                        @else
                                            <p class="text-sm text-gray-400 italic">Sản phẩm đã bị xóa</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                @if($item->user)
                                    <p class="text-sm font-semibold text-gray-800">{{ $item->user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $item->user->email }}</p>
                                @else
                                    <p class="text-sm text-gray-400 italic">Người dùng không tồn tại</p>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-xs text-gray-500">
                                {{ $item->created_at->format('d/m/Y') }}
                                <span class="block text-[10px] text-gray-400">{{ $item->created_at->diffForHumans() }}</span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <form action="{{ route('admin.wishlist.destroy', $item) }}" method="POST" class="inline btn-delete-form" data-message="Xóa mục yêu thích này của khách hàng?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 bg-red-50 hover:bg-red-100 text-red-500 rounded-lg flex items-center justify-center transition-colors border border-red-100 mx-auto">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-gray-400">
                                    <svg class="w-12 h-12 text-gray-200" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                    <p class="font-medium text-sm">Chưa có sản phẩm yêu thích nào.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($wishlists->hasPages())
        <div class="flex justify-center">
            {{ $wishlists->links() }}
        </div>
        @endif
    </div>

    {{-- Top Products Sidebar --}}
    <div class="space-y-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-800">Top Sản Phẩm Được Yêu Thích</h3>
                <p class="text-xs text-gray-400 mt-0.5">5 sản phẩm được thêm nhiều nhất</p>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($topProducts as $index => $item)
                <div class="flex items-center gap-3 px-5 py-3.5">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 {{ $index === 0 ? 'bg-yellow-100 text-yellow-700' : ($index === 1 ? 'bg-gray-100 text-gray-600' : ($index === 2 ? 'bg-orange-100 text-orange-700' : 'bg-gray-50 text-gray-500')) }}">
                        {{ $index + 1 }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $item->product->name ?? 'Không tìm thấy' }}</p>
                        <p class="text-xs text-gray-400">{{ number_format($item->product->price ?? 0) }}đ</p>
                    </div>
                    <span class="flex-shrink-0 inline-flex items-center gap-1 bg-red-50 text-red-600 text-xs font-bold px-2 py-0.5 rounded-lg">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        {{ $item->count }}
                    </span>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-gray-400 text-xs">Chưa có dữ liệu</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
