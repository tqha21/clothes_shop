@extends('layouts.storefront')

@section('title', 'Hệ Thống Cửa Hàng')

@section('content')
<!-- Page Header (Rich Hero Banner) -->
<div class="relative rounded-2xl overflow-hidden shadow-2xl mb-12 h-[350px]">
    <!-- Background Image (Architecture/Interior) -->
    <img src="https://images.unsplash.com/photo-1595663245834-2e0984aa086a?w=1600&h=600&fit=crop" 
         alt="Cửa hàng của chúng tôi" 
         class="w-full h-full object-cover">
    
    <!-- Gradient Overlay (Brand Red) -->
    <div class="absolute inset-0 bg-gradient-to-r from-red-900/80 via-red-800/40 to-transparent flex items-center">
        <div class="p-12 md:px-20 text-white max-w-3xl">
            <div class="inline-flex items-center gap-2 bg-red-600 text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full mb-4 shadow-lg border border-red-500/30">
                <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                Hệ Thống Toàn Quốc
            </div>
            <h1 class="text-5xl md:text-7xl font-black mb-4 uppercase tracking-tighter drop-shadow-2xl">
                TÌM CỬA HÀNG <br>
                <span class="text-yellow-400">GẦN BẠN NHẤT</span>
            </h1>
            <p class="text-lg md:text-xl text-red-50/90 font-medium max-w-xl drop-shadow-md">
                Trải nghiệm không gian mua sắm sang trọng, chuyên nghiệp và nhận tư vấn trực tiếp từ đội ngũ chuyên gia của chúng tôi.
            </p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4">
    <!-- Store Filters (Modern Floating Design) -->
    <div class="bg-white rounded-2xl shadow-xl p-8 -mt-24 relative z-10 border border-gray-100">
        <form method="GET" action="{{ route('stores.index') }}" id="storeFilterForm" class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
            <div class="md:col-span-5">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Tìm kiếm theo khu vực
                </label>
                <div class="relative group">
                    <input type="text" 
                           name="search" 
                           id="searchInput"
                           value="{{ $search ?? '' }}"
                           placeholder="Nhập tên thành phố, quận, hoặc tên cửa hàng..." 
                           class="w-full pl-4 pr-12 py-4 bg-gray-50 border-2 border-transparent rounded-xl focus:border-red-600 focus:bg-white focus:outline-none transition-all duration-300 text-gray-800 font-medium shadow-inner">
                </div>
            </div>
            <div class="md:col-span-4">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Chọn thành phố
                </label>
                <select name="city" 
                        id="citySelect"
                        class="w-full px-4 py-4 bg-gray-50 border-2 border-transparent rounded-xl focus:border-red-600 focus:bg-white focus:outline-none transition-all duration-300 text-gray-800 font-medium cursor-pointer shadow-inner appearance-none">
                    <option value="">Tất cả thành phố</option>
                    @foreach($cities as $cityOption)
                        <option value="{{ $cityOption }}" {{ ($city ?? '') == $cityOption ? 'selected' : '' }}>
                            {{ $cityOption }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-3">
                <button type="submit" class="w-full bg-red-600 text-white px-8 py-4 rounded-xl font-black uppercase tracking-widest hover:bg-black transition-all duration-500 shadow-[0_10px_20px_-10px_rgba(220,38,38,0.5)] active:scale-95 group flex items-center justify-center gap-2">
                    XEM KẾT QUẢ
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Store Stats (Minimalist Style) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8 my-16">
        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 flex items-center gap-4 group hover:bg-white hover:shadow-xl transition-all duration-300">
            <div class="w-14 h-14 bg-red-100 text-red-600 rounded-xl flex items-center justify-center text-2xl group-hover:bg-red-600 group-hover:text-white transition-colors duration-300">🏢</div>
            <div>
                <div class="text-3xl font-black text-gray-900 group-hover:text-red-600 transition-colors">50+</div>
                <div class="text-[10px] uppercase font-bold text-gray-400 tracking-tighter">Cửa hàng toàn quốc</div>
            </div>
        </div>
        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 flex items-center gap-4 group hover:bg-white hover:shadow-xl transition-all duration-300">
            <div class="w-14 h-14 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center text-2xl group-hover:bg-yellow-400 group-hover:text-white transition-colors duration-300">🗺️</div>
            <div>
                <div class="text-3xl font-black text-gray-900 group-hover:text-red-600 transition-colors">15</div>
                <div class="text-[10px] uppercase font-bold text-gray-400 tracking-tighter">Tỉnh thành lớn</div>
            </div>
        </div>
        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 flex items-center gap-4 group hover:bg-white hover:shadow-xl transition-all duration-300">
            <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-2xl group-hover:bg-black group-hover:text-white transition-colors duration-300">⏰</div>
            <div>
                <div class="text-3xl font-black text-gray-900 group-hover:text-red-600 transition-colors">8h-22h</div>
                <div class="text-[10px] uppercase font-bold text-gray-400 tracking-tighter">Phục vụ linh hoạt</div>
            </div>
        </div>
        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 flex items-center gap-4 group hover:bg-white hover:shadow-xl transition-all duration-300">
            <div class="w-14 h-14 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-2xl group-hover:bg-green-600 group-hover:text-white transition-colors duration-300">🛡️</div>
            <div>
                <div class="text-3xl font-black text-gray-900 group-hover:text-red-600 transition-colors">100%</div>
                <div class="text-[10px] uppercase font-bold text-gray-400 tracking-tighter">Cam kết chính hãng</div>
            </div>
        </div>
    </div>

    <!-- Store Listings -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20" id="storeGrid">
        @forelse($stores as $store)
            <!-- Store Card -->
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 group flex flex-col h-full store-card"
                 data-name="{{ strtolower($store->name) }}"
                 data-city="{{ strtolower($store->city) }}"
                 data-address="{{ strtolower($store->address) }}"
                 data-district="{{ strtolower($store->district) }}">
                <div class="relative overflow-hidden h-64">
                    <img src="{{ $store->image_url }}" 
                         alt="{{ $store->name }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors duration-300"></div>
                    @if($store->is_open)
                        <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm text-green-600 px-4 py-2 rounded-full text-xs font-black shadow-xl flex items-center gap-2 border border-green-500/20">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                            ĐANG MỞ CỬA
                        </div>
                    @else
                        <div class="absolute top-4 right-4 bg-gray-900/90 backdrop-blur-sm text-gray-300 px-4 py-2 rounded-full text-xs font-bold shadow-xl">
                            🔴 ĐANG ĐÓNG CỬA
                        </div>
                    @endif
                </div>
                <div class="p-8 flex-1 flex flex-col">
                    <div class="mb-6">
                        <span class="text-[10px] font-black tracking-widest text-red-600 uppercase mb-1 block">{{ $store->city }}</span>
                        <h3 class="text-2xl font-black text-gray-900 group-hover:text-red-600 transition-colors duration-300 leading-tight">{{ $store->name }}</h3>
                    </div>
                    
                    <div class="space-y-4 mb-8 flex-1">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-gray-50 rounded-lg flex items-center justify-center text-lg shadow-sm">📍</div>
                            <div class="flex-1">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-0.5">Địa chỉ</span>
                                <span class="text-sm text-gray-600 font-medium leading-relaxed">{{ $store->address }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-gray-50 rounded-lg flex items-center justify-center text-lg shadow-sm">📞</div>
                            <div>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-0.5">Điện thoại</span>
                                <span class="text-sm text-gray-900 font-bold tracking-tighter">{{ $store->phone }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-gray-50 rounded-lg flex items-center justify-center text-lg shadow-sm">🕒</div>
                            <div>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-0.5">Giờ hoạt động</span>
                                <span class="text-sm text-gray-600 font-medium">{{ $store->opening_hours }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ $store->direction_url }}" 
                           target="_blank" 
                           class="flex-1 bg-black text-white text-center px-6 py-4 rounded-xl font-bold uppercase tracking-widest text-xs hover:bg-red-600 transition-all duration-300 shadow-xl flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            CHỈ ĐƯỜNG
                        </a>
                        <a href="tel:{{ $store->phone }}" 
                           class="w-14 h-14 bg-gray-100 text-gray-900 rounded-xl font-bold hover:bg-yellow-400 transition-all duration-300 flex items-center justify-center shadow-lg group-hover:rotate-12">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-24 bg-white rounded-3xl border-2 border-dashed border-gray-100">
                <div class="text-8xl mb-6 grayscale opacity-20">🔎</div>
                <h3 class="text-3xl font-black text-gray-900 mb-2 uppercase tracking-tighter">KHÔNG TÌM THẤY <span class="text-red-600">CỬA HÀNG</span></h3>
                <p class="text-gray-500 font-medium">Chúng tôi chưa có mặt tại khu vực này. Bạn có thể mua hàng Online để được giao tận nơi!</p>
                <div class="mt-8">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-black text-white px-8 py-3 rounded-full font-bold uppercase tracking-widest text-xs hover:bg-red-600 transition-colors">
                        XEM SẢN PHẨM MỚI
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Benefits Section (Modern Grid) -->
    <div class="bg-black text-white rounded-3xl p-12 md:p-20 mb-20 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-red-600/10 blur-3xl rounded-full"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-yellow-400/10 blur-3xl rounded-full"></div>
        
        <div class="relative z-10">
            <div class="text-center mb-16">
                <span class="text-[10px] font-black text-red-500 uppercase tracking-[0.3em] mb-3 block">SERVICE QUALITY</span>
                <h2 class="text-4xl md:text-5xl font-black uppercase tracking-tighter mb-4">LỢI ÍCH KHI ĐẾN <span class="text-yellow-400">TRỰC TIẾP</span></h2>
                <div class="w-20 h-1 bg-red-600 mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="group">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:bg-red-600 transition-all duration-300 group-hover:-translate-y-2">👔</div>
                    <h3 class="font-black text-xl mb-4 uppercase tracking-tight group-hover:text-yellow-400 transition-colors">Trải Nghiệm Thật</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Cảm nhận trực tiếp chất liệu vải cao cấp và mặc thử để chọn được form dáng ưng ý nhất cho bản thân.</p>
                </div>
                <div class="group">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:bg-red-600 transition-all duration-300 group-hover:-translate-y-2">💁</div>
                    <h3 class="font-black text-xl mb-4 uppercase tracking-tight group-hover:text-yellow-400 transition-colors">Personal Styling</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Đội ngũ nhân viên am hiểu thời trang sẽ tư vấn cho bạn cách phối đồ phù hợp với từng hoàn cảnh và vóc dáng.</p>
                </div>
                <div class="group">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:bg-red-600 transition-all duration-300 group-hover:-translate-y-2">🎁</div>
                    <h3 class="font-black text-xl mb-4 uppercase tracking-tight group-hover:text-yellow-400 transition-colors">Store Benefits</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Tận hưởng các chương trình ưu đãi đặc quyền, voucher quà tặng dành riêng cho khách hàng mua hàng tại quầy.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section (Dynamic Banner) -->
    <div class="relative rounded-3xl overflow-hidden mb-20 group">
        <img src="https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=1600&h=400&fit=crop" class="w-full h-[400px] object-cover group-hover:scale-105 transition-transform duration-1000">
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent flex items-center justify-center text-center p-8">
            <div class="max-w-2xl">
                <h2 class="text-4xl md:text-6xl font-black text-white uppercase tracking-tighter mb-8 shadow-black drop-shadow-2xl">
                    KHÔNG TÌM THẤY <br>
                    CỬA HÀNG <span class="text-red-600">GẦN BẠN?</span>
                </h2>
                <a href="{{ route('products.index') }}" class="inline-block bg-white text-black px-12 py-5 rounded-full font-black text-lg uppercase tracking-widest hover:bg-yellow-400 hover:scale-110 transition-all duration-500 shadow-2xl active:scale-95">
                    MUA ONLINE NGAY
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Client-side Search Script (Refined) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const citySelect = document.getElementById('citySelect');
    const storeCards = document.querySelectorAll('.store-card');
    
    function filterStores() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedCity = citySelect.value.toLowerCase().trim();
        let visibleCount = 0;
        
        storeCards.forEach(card => {
            const name = card.dataset.name;
            const city = card.dataset.city;
            const address = card.dataset.address;
            const district = card.dataset.district;
            
            const matchesSearch = !searchTerm || 
                name.includes(searchTerm) || 
                address.includes(searchTerm) || 
                district.includes(searchTerm) ||
                city.includes(searchTerm);
            
            const matchesCity = !selectedCity || city.includes(selectedCity);
            
            if (matchesSearch && matchesCity) {
                card.style.display = 'flex';
                card.classList.add('animate-fadeIn');
                visibleCount++;
            } else {
                card.style.display = 'none';
                card.classList.remove('animate-fadeIn');
            }
        });

        // Hide/Show Empty State if needed (could be improved further with JS-rendered empty state)
    }
    
    searchInput.addEventListener('input', filterStores);
    citySelect.addEventListener('change', filterStores);
});
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
    animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>
@endsection
