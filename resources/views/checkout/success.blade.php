@extends('layouts.storefront')

@section('title', 'Đặt Hàng Thành Công')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Success Header -->
    <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl shadow-2xl p-8 text-center text-white mb-8">
        <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h1 class="text-4xl font-bold mb-3">Đặt Hàng Thành Công!</h1>
        <p class="text-green-100 text-lg">Cảm ơn bạn đã tin tưởng và đặt hàng tại cửa hàng chúng tôi</p>
        <div class="mt-6 bg-white/20 backdrop-blur-sm rounded-lg p-4 inline-block">
            <p class="text-sm text-green-100 mb-1">Mã đơn hàng của bạn</p>
            <p class="text-3xl font-bold">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>
    </div>

    @if($order->payment_method === 'BANK')
    <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 text-center max-w-lg mx-auto border-2 border-red-500">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Thông Tin Chuyển Khoản</h2>
        <p class="text-gray-600 mb-6">Quét mã QR bằng ứng dụng ngân hàng của bạn để thanh toán</p>
        
        <div class="bg-gray-50 p-4 rounded-xl inline-block mb-6 shadow-sm border border-gray-200">
            <!-- Sử dụng API của VietQR để tạo QR động: https://api.vietqr.io/image/<BANK_ID>-<ACCOUNT_NO>-<TEMPLATE>.jpg?amount=<AMOUNT>&addInfo=<DESCRIPTION>&accountName=<ACCOUNT_NAME> -->
            <img src="https://img.vietqr.io/image/techcombank-202108059999-compact2.jpg?amount={{ $order->total }}&addInfo=Thanh toan don hang {{ $order->id }}&accountName=TA QUANG HA" 
                 alt="Mã QR Chuyển Khoản" 
                 class="w-64 h-64 object-contain rounded-lg">
        </div>

        <div class="space-y-3 text-left bg-gray-50 p-4 rounded-xl border border-gray-100">
            <div class="flex justify-between items-center pb-2 border-b border-gray-200">
                <span class="text-gray-500">Ngân hàng:</span>
                <span class="font-bold text-gray-900">Techcombank</span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-gray-200">
                <span class="text-gray-500">Chủ tài khoản:</span>
                <span class="font-bold text-gray-900">TẠ QUANG HÀ</span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-gray-200">
                <span class="text-gray-500">Số tài khoản:</span>
                <div class="flex items-center gap-2">
                    <span class="font-bold text-red-600 font-mono text-lg">202108059999</span>
                </div>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-gray-200">
                <span class="text-gray-500">Số tiền:</span>
                <span class="font-bold text-blue-600 text-lg">{{ number_format($order->total) }}đ</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-500">Nội dung chuyển khoản:</span>
                <span class="font-bold text-gray-900 font-mono bg-yellow-100 px-2 py-1 rounded">Thanh toan don hang {{ $order->id }}</span>
            </div>
        </div>
        <p class="text-sm text-red-600 mt-4 italic">Lưu ý: Đơn hàng sẽ được xử lý sau khi nhận được thanh toán.</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Delivery Status Timeline -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Trạng Thái Đơn Hàng
                </h2>

                <!-- Timeline -->
                <div class="relative">
                    <!-- Pending -->
                    <div class="flex items-start mb-8">
                        <div class="flex flex-col items-center mr-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white shadow-lg">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="w-1 h-16 bg-gradient-to-b from-green-500 to-gray-300"></div>
                        </div>
                        <div class="flex-1 pt-2">
                            <h3 class="font-bold text-lg text-gray-900">Đơn hàng đã được đặt</h3>
                            <p class="text-gray-600 text-sm">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            <p class="text-green-600 text-sm mt-1">✓ Hoàn thành</p>
                        </div>
                    </div>

                    <!-- Processing -->
                    <div class="flex items-start mb-8">
                        <div class="flex flex-col items-center mr-4">
                            <div class="w-12 h-12 {{ in_array($order->status, ['processing', 'shipping', 'delivered']) ? 'bg-gradient-to-r from-blue-500 to-cyan-600' : 'bg-gray-300' }} rounded-full flex items-center justify-center text-white shadow-lg">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="w-1 h-16 {{ in_array($order->status, ['shipping', 'delivered']) ? 'bg-gradient-to-b from-blue-500 to-purple-500' : 'bg-gray-300' }}"></div>
                        </div>
                        <div class="flex-1 pt-2">
                            <h3 class="font-bold text-lg {{ in_array($order->status, ['processing', 'shipping', 'delivered']) ? 'text-gray-900' : 'text-gray-400' }}">Đang xử lý</h3>
                            <p class="text-gray-600 text-sm">Đang chuẩn bị hàng</p>
                            @if(in_array($order->status, ['processing', 'shipping', 'delivered']))
                                <p class="text-blue-600 text-sm mt-1">✓ Hoàn thành</p>
                            @else
                                <p class="text-gray-400 text-sm mt-1">⏳ Chờ xử lý</p>
                            @endif
                        </div>
                    </div>

                    <!-- Shipping -->
                    <div class="flex items-start mb-8">
                        <div class="flex flex-col items-center mr-4">
                            <div class="w-12 h-12 {{ in_array($order->status, ['shipping', 'delivered']) ? 'bg-gradient-to-r from-purple-500 to-pink-600' : 'bg-gray-300' }} rounded-full flex items-center justify-center text-white shadow-lg">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path>
                                    <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"></path>
                                </svg>
                            </div>
                            <div class="w-1 h-16 {{ $order->status == 'delivered' ? 'bg-gradient-to-b from-purple-500 to-yellow-500' : 'bg-gray-300' }}"></div>
                        </div>
                        <div class="flex-1 pt-2">
                            <h3 class="font-bold text-lg {{ in_array($order->status, ['shipping', 'delivered']) ? 'text-gray-900' : 'text-gray-400' }}">Đang giao hàng</h3>
                            <p class="text-gray-600 text-sm">Đơn hàng đang trên đường giao đến bạn</p>
                            @if(in_array($order->status, ['shipping', 'delivered']))
                                <p class="text-purple-600 text-sm mt-1">✓ Đang giao</p>
                            @else
                                <p class="text-gray-400 text-sm mt-1">⏳ Chờ giao hàng</p>
                            @endif
                        </div>
                    </div>

                    <!-- Delivered -->
                    <div class="flex items-start">
                        <div class="flex flex-col items-center mr-4">
                            <div class="w-12 h-12 {{ $order->status == 'delivered' ? 'bg-gradient-to-r from-yellow-500 to-orange-600' : 'bg-gray-300' }} rounded-full flex items-center justify-center text-white shadow-lg">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z"></path>
                                    <path d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 pt-2">
                            <h3 class="font-bold text-lg {{ $order->status == 'delivered' ? 'text-gray-900' : 'text-gray-400' }}">Đã giao hàng</h3>
                            <p class="text-gray-600 text-sm">Đơn hàng đã được giao thành công</p>
                            @if($order->status == 'delivered')
                                <p class="text-orange-600 text-sm mt-1">✓ Hoàn thành</p>
                            @else
                                <p class="text-gray-400 text-sm mt-1">⏳ Chưa giao</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Sản Phẩm Đã Đặt
                </h2>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex gap-4 pb-4 border-b border-gray-200 last:border-0">
                        <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                            <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover" alt="{{ $item->product->name }}">
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 mb-1">{{ $item->product->name }}</h3>
                            <p class="text-sm text-gray-600">Số lượng: {{ $item->quantity }}</p>
                            <p class="text-sm text-gray-600">Đơn giá: {{ number_format($item->price) }}đ</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-lg text-purple-600">{{ number_format($item->price * $item->quantity) }}đ</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-xl p-6 sticky top-24">
                <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Thông Tin Đơn Hàng
                </h2>
                
                <div class="space-y-3 mb-6">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-purple-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">Khách hàng</p>
                            <p class="font-semibold">{{ $order->customer_name }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">Số điện thoại</p>
                            <p class="font-semibold">{{ $order->customer_phone }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">Địa chỉ giao hàng</p>
                            <p class="font-semibold">{{ $order->customer_address }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">Thanh toán</p>
                            <p class="font-semibold">
                                @if($order->payment_method == 'COD')
                                    Tiền mặt (COD)
                                @elseif($order->payment_method == 'BANK')
                                    Chuyển khoản
                                @elseif($order->payment_method == 'VNPAY')
                                    Ví điện tử VNPAY
                                @else
                                    Ví điện tử
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="border-t pt-4 space-y-2">
                    <div class="flex justify-between text-lg">
                        <span class="font-bold">Tổng tiền:</span>
                        <span class="font-bold text-purple-600 text-2xl">{{ number_format($order->total) }}đ</span>
                    </div>
                </div>

                <div class="mt-6 space-y-3">
                    <a href="{{ route('home') }}" class="block w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-3 rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all font-semibold text-center">
                        Về Trang Chủ
                    </a>
                    <a href="{{ route('products.index') }}" class="block w-full bg-white border-2 border-purple-600 text-purple-600 px-6 py-3 rounded-lg hover:bg-purple-50 transition-all font-semibold text-center">
                        Tiếp Tục Mua Sắm
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
