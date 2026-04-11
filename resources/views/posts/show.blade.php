@extends('layouts.storefront')

@section('title', $post->title)

@section('content')
<!-- Post Header -->
<div class="bg-white border-b border-gray-200 py-8">
    <div class="container mx-auto px-4">
        <nav class="text-sm text-gray-600 mb-4">
            <a href="{{ route('home') }}" class="hover:text-red-600">Trang chủ</a>
            <span class="mx-2">/</span>
            <a href="{{ route('posts.index') }}" class="hover:text-red-600">Tin tức</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">{{ $post->title }}</span>
        </nav>
        
        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>
        
        <div class="flex items-center gap-4 text-sm text-gray-600">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>{{ $post->published_at->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Post Content -->
<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="col-span-2">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <!-- Featured Image -->
                <img src="{{ $post->image }}" alt="{{ $post->title }}" class="w-full h-96 object-cover">
                
                <!-- Content -->
                <div class="p-8">
                    @if($post->excerpt)
                    <div class="text-xl text-gray-700 mb-6 italic border-l-4 border-red-600 pl-4 py-2 bg-gray-50">
                        {{ $post->excerpt }}
                    </div>
                    @endif
                    
                    <div class="prose prose-lg max-w-none">
                        {!! $post->content !!}
                    </div>
                </div>
            </div>
            
            <!-- Share Buttons -->
            <div class="mt-8 bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Chia sẻ bài viết</h3>
                <div class="flex gap-3">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('posts.show', $post->slug)) }}" target="_blank" class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('posts.show', $post->slug)) }}&text={{ urlencode($post->title) }}" target="_blank" class="flex items-center gap-2 bg-sky-500 text-white px-4 py-2 rounded hover:bg-sky-600 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        Twitter
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-span-1">
            <!-- Related Posts -->
            @if($relatedPosts->count() > 0)
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Bài viết liên quan</h3>
                <div class="space-y-4">
                    @foreach($relatedPosts as $related)
                    <a href="{{ route('posts.show', $related->slug) }}" class="block group">
                        <div class="flex gap-3">
                            <img src="{{ $related->image }}" alt="{{ $related->title }}" class="w-24 h-24 object-cover rounded group-hover:opacity-75 transition-opacity">
                            <div class="flex-1">
                                <h4 class="font-semibold text-sm text-gray-900 group-hover:text-red-600 transition-colors line-clamp-2 mb-1">
                                    {{ $related->title }}
                                </h4>
                                <p class="text-xs text-gray-500">{{ $related->published_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                
                <a href="{{ route('posts.index') }}" class="block mt-4 text-center text-red-600 hover:text-red-700 font-medium text-sm">
                    Xem tất cả bài viết →
                </a>
            </div>
            @endif
            
            <!-- Categories/Tags (Optional) -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Danh mục</h3>
                <div class="space-y-2">
                    <a href="{{ route('posts.index') }}" class="block text-gray-700 hover:text-red-600 transition-colors">
                        Tất cả bài viết
                    </a>
                    <a href="{{ route('posts.index') }}" class="block text-gray-700 hover:text-red-600 transition-colors">
                        Xu hướng thời trang
                    </a>
                    <a href="{{ route('posts.index') }}" class="block text-gray-700 hover:text-red-600 transition-colors">
                        Mẹo phối đồ
                    </a>
                    <a href="{{ route('posts.index') }}" class="block text-gray-700 hover:text-red-600 transition-colors">
                        Tin tức
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.prose h2 {
    font-size: 1.875rem;
    font-weight: 700;
    margin-top: 2rem;
    margin-bottom: 1rem;
    color: #1F2937;
}

.prose h3 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    color: #374151;
}

.prose p {
    margin-bottom: 1rem;
    line-height: 1.75;
    color: #4B5563;
}

.prose ul {
    list-style-type: disc;
    margin-left: 1.5rem;
    margin-bottom: 1rem;
}

.prose li {
    margin-bottom: 0.5rem;
    color: #4B5563;
}

.prose strong {
    font-weight: 600;
    color: #1F2937;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
