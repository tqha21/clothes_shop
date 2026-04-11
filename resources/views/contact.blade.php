@extends('layouts.storefront')

@section('title', 'Liên hệ - HTD Store')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8 text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li><a href="{{ route('home') }}" class="hover:text-red-600 transition-colors">Trang chủ</a></li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 mx-1" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
                    <span class="ml-1 md:ml-2 font-medium">Liên hệ</span>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Information -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 md:p-12">
                <h1 class="text-4xl font-black text-gray-900 mb-8">Liên hệ với chúng tôi</h1>
                <p class="text-gray-600 mb-12 leading-relaxed">Chúng tôi luôn sẵn sàng lắng nghe và giải đáp mọi thắc mắc của bạn. Đừng ngần ngại liên hệ với HTD Store qua các kênh thông tin dưới đây.</p>

                <div class="space-y-8">
                    <div class="flex items-start gap-6">
                        <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center flex-shrink-0 text-red-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1 uppercase tracking-wider text-sm">Địa chỉ trụ sở</h3>
                            <p class="text-gray-600">56 534 Đường Láng, Phường Yên Hoà, Quận Đống Đa, Hà Nội</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-6">
                        <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center flex-shrink-0 text-red-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1 uppercase tracking-wider text-sm">Số điện thoại</h3>
                            <p class="text-gray-600 text-lg font-bold">0383390611</p>
                            <p class="text-sm text-gray-400 font-medium">Hỗ trợ 24/7 (ZALO)</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-6">
                        <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center flex-shrink-0 text-red-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1 uppercase tracking-wider text-sm">Email hỗ trợ</h3>
                            <p class="text-gray-600">support@htdstore.vn</p>
                            <p class="text-gray-600">kenda@gmail.com</p>
                        </div>
                    </div>
                </div>

                <!-- Social Links -->
                <div class="mt-16 pt-12 border-t border-gray-100">
                    <h3 class="font-bold text-gray-900 mb-6 uppercase tracking-wider text-sm">Theo dõi chúng tôi</h3>
                    <div class="flex gap-4">
                        <a href="#" class="w-12 h-12 bg-gray-900 text-white rounded-xl flex items-center justify-center hover:bg-blue-600 transition-all active:scale-90 shadow-lg shadow-gray-200">
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                        </a>
                        <a href="#" class="w-12 h-12 bg-gray-900 text-white rounded-xl flex items-center justify-center hover:bg-pink-600 transition-all active:scale-90 shadow-lg shadow-gray-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </a>
                        <a href="#" class="w-12 h-12 bg-gray-900 text-white rounded-xl flex items-center justify-center hover:bg-red-600 transition-all active:scale-90 shadow-lg shadow-gray-200">
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="bg-gray-900 rounded-3xl p-8 md:p-12 text-white shadow-2xl">
                <h2 class="text-3xl font-black mb-8">Gửi tin nhắn</h2>
                <form action="#" class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Họ và tên</label>
                            <input type="text" class="w-full bg-gray-800 border border-gray-700 rounded-2xl px-6 py-4 outline-none focus:ring-2 focus:ring-red-500 transition-all" placeholder="Nguyễn Văn A">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Email</label>
                            <input type="email" class="w-full bg-gray-800 border border-gray-700 rounded-2xl px-6 py-4 outline-none focus:ring-2 focus:ring-red-500 transition-all" placeholder="email@example.com">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Chủ đề</label>
                        <input type="text" class="w-full bg-gray-800 border border-gray-700 rounded-2xl px-6 py-4 outline-none focus:ring-2 focus:ring-red-500 transition-all" placeholder="Tiêu đề tin nhắn...">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Nội dung</label>
                        <textarea rows="6" class="w-full bg-gray-800 border border-gray-700 rounded-2xl px-6 py-4 outline-none focus:ring-2 focus:ring-red-500 transition-all" placeholder="Nhập nội dung bạn muốn gửi tới chúng tôi..."></textarea>
                    </div>
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-black py-5 rounded-2xl transition-all shadow-xl shadow-red-900/40 active:scale-[0.98]">
                        GỬI TIN NHẮN NGAY
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
