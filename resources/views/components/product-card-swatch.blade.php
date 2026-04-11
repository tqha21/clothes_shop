@props(['product', 'badge' => null])
<div class="bg-white rounded-lg hover:shadow-xl transition-all group p-3 border border-transparent hover:border-gray-200">
    <div class="relative overflow-hidden rounded text-center">
        <a href="{{ route('products.show', $product->slug) }}">
            @if(str_starts_with($product->image, 'dummy_url|'))
                <img src="{{ str_replace('dummy_url|', '', $product->image) }}" 
            @else
                <img src="{{ asset('storage/' . $product->image) }}" 
            @endif
                 alt="{{ $product->name }}" 
                 class="w-full aspect-[3/4] object-cover group-hover:scale-105 transition-transform duration-500 rounded">
        </a>
        
        @if($badge)
        <div class="absolute bottom-0 left-0 bg-red-600 text-white px-2 py-1 text-xs font-bold uppercase w-full">
            {{ $badge }}
        </div>
        @elseif($product->sale_price)
        <div class="absolute bottom-0 left-0 bg-red-600 text-white px-2 py-1 text-xs font-bold uppercase w-full">
            GIẢM {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
        </div>
        @endif
        
        {{-- Hover overlay --}}
        <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
    </div>
    
    <div class="pt-3">
        <div class="flex items-center gap-2 mb-1">
            @if($product->sale_price)
                <span class="text-gray-900 font-bold text-lg">{{ number_format($product->sale_price) }}đ</span>
                @if(!$badge) <!-- If there's a custom badge, maybe don't show original price to keep it clean, or show it small -->
                <span class="text-gray-400 line-through text-xs">{{ number_format($product->price) }}đ</span>
                @endif
            @else
                <span class="text-gray-900 font-bold text-lg">{{ number_format($product->price) }}đ</span>
            @endif
        </div>
        
        <a href="{{ route('products.show', $product->slug) }}" class="block">
            <h3 class="text-sm text-gray-700 mb-2 line-clamp-2 hover:text-red-600 transition-colors" title="{{ $product->name }}">
                {{ $product->name }}
            </h3>
        </a>

        @if(!empty($product->colors))
        @php
            $colorMap = [
                'Đen' => '#111111', 'Trắng' => '#ffffff', 'Đỏ' => '#ef4444',
                'Xanh dương' => '#3b82f6', 'Vàng' => '#eab308', 'Xanh lá' => '#22c55e',
                'Xám' => '#6b7280', 'Hồng' => '#ec4899', 'Tím' => '#a855f7',
                'Cam' => '#f97316', 'Nâu' => '#78350f', 'Be' => '#d2b48c',
                'Xanh Navy' => '#1e3a8a', 'Xanh than' => '#1c2d4a', 'Ghi' => '#9ca3af',
                'Xanh Jean Nhạt' => '#5b85b8', 'Xanh Jean Đậm' => '#1a3a5c',
                'Xám Khói' => '#8a8f9c', 'Xanh Rêu' => '#4a7c5e', 'Kem' => '#f5e6c8',
                'Xám Đậm' => '#374151', 'Xám Nhạt' => '#d1d5db', 'Xanh Lá' => '#16a34a',
            ];
        @endphp
        <div class="flex flex-wrap items-center gap-1 mt-auto">
            @foreach(array_unique($product->colors) as $colorName)
                @php
                    $hex = $colorMap[$colorName] ?? (preg_match('/^#[a-f0-9]{6}$/i', $colorName) ? $colorName : '#cccccc');
                @endphp
                <span class="w-4 h-4 rounded-full border border-gray-300 inline-block shadow-sm" style="background-color: {{ $hex }}" title="{{ $colorName }}"></span>
            @endforeach
        </div>
        @endif
    </div>
</div>
