@extends('layouts.storefront')

@section('title', 'Tin Tức Thời Trang')

@section('content')
<!-- Page Header -->
<div class="bg-white border-b border-gray-200 py-8">
    <div class="container mx-auto px-4">
        <nav class="text-sm text-gray-600 mb-4">
            <a href="{{ route('home') }}" class="hover:text-red-600">Trang chủ</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">Tin tức</span>
        </nav>
        
        <h1 class="text-4xl font-bold text-gray-900 mb-2">TIN THỜI TRANG</h1>
        <p class="text-gray-600">Cập nhật xu hướng thời trang mới nhất</p>
    </div>
</div>

<!-- Posts Grid -->
<div class="container mx-auto px-4 py-12">
    @if($posts->count() > 0)
    <div class="grid grid-cols-3 gap-8">
        @foreach($posts as $post)
        <div class="bg-white rounded-lg overflow-hidden shadow hover:shadow-xl transition-shadow">
            <a href="{{ route('posts.show', $post->slug) }}">
                <img src="{{ $post->image }}" alt="{{ $post->title }}" class="w-full h-64 object-cover hover:scale-105 transition-transform duration-300">
            </a>
            <div class="p-6">
                <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ $post->published_at->format('d/m/Y') }}</span>
                </div>
                
                <a href="{{ route('posts.show', $post->slug) }}">
                    <h3 class="font-bold text-lg mb-3 line-clamp-2 hover:text-red-600 transition-colors">
                        {{ $post->title }}
                    </h3>
                </a>
                
                @if($post->excerpt)
                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                    {{ $post->excerpt }}
                </p>
                @endif
                
                <a href="{{ route('posts.show', $post->slug) }}" class="inline-flex items-center gap-2 text-red-600 hover:text-red-700 font-medium text-sm">
                    Đọc tiếp
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    @if($posts->hasPages())
    <div class="mt-12">
        {{ $posts->links() }}
    </div>
    @endif
    
    @else
    <!-- Empty State -->
    <div class="text-center py-16">
        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Chưa có bài viết nào</h3>
        <p class="text-gray-600">Vui lòng quay lại sau</p>
    </div>
    @endif
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
