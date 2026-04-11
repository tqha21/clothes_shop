@extends('layouts.storefront')

@section('title', 'Giới thiệu - HTD Store')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8 text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li><a href="{{ route('home') }}" class="hover:text-red-600 transition-colors">Trang chủ</a></li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 mx-1" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
                    <span class="ml-1 md:ml-2 font-medium">Giới thiệu</span>
                </li>
            </ol>
        </nav>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="relative h-64 md:h-96">
                <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&q=80&w=1200" alt="About HTD Store" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-8 md:p-12">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-black text-white mb-4">HTD STORE</h1>
                        <p class="text-lg text-gray-200 max-w-2xl font-medium">Thời trang cao cấp - Khẳng định phong cách riêng của bạn.</p>
                    </div>
                </div>
            </div>

            <div class="p-8 md:p-16">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                    <div>
                        <h2 class="text-3xl font-black text-gray-900 mb-8 border-l-4 border-red-600 pl-6">Câu chuyện của chúng tôi</h2>
                        <div class="prose max-w-none text-gray-600 leading-relaxed space-y-6">
                            <p>Chào mừng bạn đến với <strong>HTD Store</strong>, điểm đến hàng đầu cho những tín đồ yêu thời trang và phong cách sống hiện đại. Được thành lập với niềm đam mê mãnh liệt về cái đẹp và sự tinh tế, chúng tôi không chỉ bán quần áo, mà còn mang đến cho bạn một trải nghiệm thời trang hoàn toàn mới.</p>
                            <p>Tại HTD Store, mỗi sản phẩm đều được tuyển chọn kỹ lưỡng, từ chất liệu vải cao cấp đến từng đường kim mũi chỉ, nhằm đảm bảo sự thoải mái tối đa và tôn vinh nét đẹp cá tính của người mặc.</p>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-3xl font-black text-gray-900 mb-8 border-l-4 border-red-600 pl-6">Giá trị cốt lõi</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="p-6 bg-red-50 rounded-2xl">
                                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm mb-4">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <h3 class="font-bold text-gray-900 mb-2">Chất lượng</h3>
                                <p class="text-sm text-gray-600">Cam kết mang đến những sản phẩm bền đẹp và an toàn cho người sử dụng.</p>
                            </div>
                            <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100">
                                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm mb-4">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <h3 class="font-bold text-gray-900 mb-2">Sáng tạo</h3>
                                <p class="text-sm text-gray-600">Luôn cập nhật xu hướng mới nhất để bạn luôn là người dẫn đầu phong cách.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-20 text-center">
                    <h2 class="text-3xl font-black text-gray-900 mb-12">Tầm nhìn & Sứ mệnh</h2>
                    <div class="max-w-4xl mx-auto p-12 bg-gray-900 rounded-3xl text-white">
                        <p class="text-xl italic leading-relaxed">"Sứ mệnh của HTD Store là giúp mọi khách hàng cảm thấy tự tin và hạnh phúc hơn thông qua những trang phục mang dấu ấn cá nhân. Chúng tôi hướng tới việc trở thành thương hiệu thời trang uy tín hàng đầu, nơi niềm tin và chất lượng được đặt lên hàng đầu."</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
