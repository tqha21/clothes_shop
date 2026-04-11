@extends('admin.layouts.app')

@section('title', 'Danh Mục')
@section('page-title', 'Quản Lý Danh Mục')
@section('page-description', 'Kiến trúc và phân loại danh mục sản phẩm')

@section('content')
<!-- Professional Header Actions -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
    <div class="flex items-center gap-6">
        <div class="bg-white rounded-2xl px-6 py-3 shadow-sm border border-gray-100 flex items-center gap-3">
            <span class="w-2 h-2 bg-red-600 rounded-full animate-pulse"></span>
            <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Tổng Danh Mục: </span>
            <span class="text-lg font-black text-gray-900">{{ $categories->total() }}</span>
        </div>
    </div>
    
    <a href="{{ route('admin.categories.create') }}" class="group flex items-center gap-3 bg-gray-900 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-red-600 transition-all shadow-xl shadow-gray-900/10 hover:shadow-red-600/20 transform hover:-translate-y-1">
        <div class="w-6 h-6 bg-white/10 rounded-lg flex items-center justify-center group-hover:bg-white/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
        </div>
        Thêm Danh Mục
    </a>
</div>

<!-- Category Grid Architecture -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @forelse($categories as $category)
    <div class="group bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden hover:shadow-2xl hover:border-red-600/20 transition-all duration-500 flex flex-col">
        <!-- Card Header (Dark & Professional) -->
        <div class="bg-[#1A1A1A] p-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-red-600/10 rounded-full -mr-16 -mt-16 blur-2xl group-hover:bg-red-600/20 transition-colors"></div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-12 h-12 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center text-red-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest bg-white/5 px-3 py-1 rounded-lg border border-white/5">Mã: #{{ $category->id }}</span>
                </div>
                <h3 class="text-xl font-black text-white tracking-tighter uppercase mb-1">{{ $category->name }}</h3>
                <p class="text-[10px] font-bold text-red-500 uppercase tracking-widest opacity-80 group-hover:opacity-100 transition-opacity">{{ $category->slug }}</p>
            </div>
        </div>
        
        <!-- Card Body -->
        <div class="p-8 flex-1 flex flex-col justify-between">
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100 mb-8">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Đã Liên Kết</span>
                </div>
                <div class="flex items-baseline gap-1">
                    <span class="text-2xl font-black text-gray-900 tracking-tighter">{{ $category->products_count }}</span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Sản phẩm</span>
                </div>
            </div>
            
            <div class="flex gap-4">
                <a href="{{ route('admin.categories.edit', $category) }}" class="flex-1 h-12 inline-flex items-center justify-center gap-2 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-black transition-all shadow-lg shadow-gray-900/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Chỉnh Sửa
                </a>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="flex-1 btn-delete-form" data-message="CẢNH BÁO: Danh mục này có thể chứa kết nối sản phẩm quan trọng.">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full h-12 inline-flex items-center justify-center gap-2 bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-red-600 hover:text-white transition-all border border-red-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Xóa Bỏ
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full">
        <div class="bg-white rounded-[2.5rem] border-2 border-dashed border-gray-100 p-20 text-center">
            <div class="w-24 h-24 bg-gray-50 rounded-[2rem] flex items-center justify-center text-4xl mx-auto mb-8 shadow-inner">🗂️</div>
            <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tighter mb-4">Danh Mục Trống</h2>
            <p class="text-gray-400 font-bold max-w-sm mx-auto mb-10 text-sm">Chưa có danh mục nào trong hệ thống. Hãy khởi tạo một danh mục mới.</p>
            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-3 bg-red-600 text-white px-10 py-5 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-red-600/20 hover:bg-black transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tạo Danh Mục Mới
            </a>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination Architecture -->
@if($categories->hasPages())
<div class="mt-16 flex justify-center">
    {{ $categories->links() }}
</div>
@endif

<style>
    .pagination { @apply flex gap-2; }
    .page-item { @apply rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden transition-all; }
    .page-link { @apply flex items-center justify-center w-10 h-10 text-xs font-black text-gray-900 uppercase; }
    .page-item.active { @apply bg-red-600 border-red-600 shadow-lg shadow-red-600/20; }
    .page-item.active .page-link { @apply text-white; }
    .page-item:hover:not(.active) { @apply bg-gray-50 border-gray-200 transform -translate-y-0.5; }
</style>
@endsection
