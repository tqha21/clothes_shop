@extends('layouts.storefront')

@section('title', $success ? 'Thanh toán thành công' : 'Thanh toán thất bại')

@section('content')
<div class="max-w-3xl mx-auto py-12 px-4">
    @if($success)
    {{-- Success State --}}
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-8 text-center text-white">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold mb-2">Thanh Toán Thành Công!</h1>
            <p class="text-green-100">Đơn hàng của bạn đã được thanh toán qua VNPAY</p>
        </div>

        <div class="p-8">
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">Mã đơn hàng</p>
                    <p class="text-xl font-bold text-gray-900">#{{ $order ? str_pad($order->id, 6, '0', STR_PAD_LEFT) : 'N/A' }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">Số tiền</p>
                    <p class="text-xl font-bold text-red-600">{{ number_format($vnp_Amount) }}đ</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">Mã giao dịch VNPAY</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $vnp_TransactionNo }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">Ngân hàng</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $vnp_BankCode }}</p>
                </div>
            </div>

            @if($vnp_PayDate)
            <div class="bg-blue-50 rounded-lg p-4 mb-6 text-center">
                <p class="text-sm text-blue-600">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Thời gian thanh toán: {{ substr($vnp_PayDate, 0, 4) }}/{{ substr($vnp_PayDate, 4, 2) }}/{{ substr($vnp_PayDate, 6, 2) }} {{ substr($vnp_PayDate, 8, 2) }}:{{ substr($vnp_PayDate, 10, 2) }}:{{ substr($vnp_PayDate, 12, 2) }}
                </p>
            </div>
            @endif

            <div class="flex gap-4">
                @if($order)
                <a href="{{ route('checkout.success', $order->id) }}" class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-3 rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all font-semibold text-center">
                    Xem Chi Tiết Đơn Hàng
                </a>
                @endif
                <a href="{{ route('products.index') }}" class="flex-1 bg-white border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-50 transition-all font-semibold text-center">
                    Tiếp Tục Mua Sắm
                </a>
            </div>
        </div>
    </div>

    @else
    {{-- Failure State --}}
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-red-500 to-rose-600 p-8 text-center text-white">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold mb-2">Thanh Toán Thất Bại</h1>
            <p class="text-red-100">{{ $message }}</p>
        </div>

        <div class="p-8">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-red-800">Giao dịch không thành công</p>
                        <p class="text-sm text-red-600 mt-1">Mã lỗi: {{ $vnp_ResponseCode }}</p>
                        @if($order)
                        <p class="text-sm text-red-600">Đơn hàng #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }} đã bị hủy.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('cart.index') }}" class="flex-1 bg-gradient-to-r from-red-600 to-rose-600 text-white px-6 py-3 rounded-lg hover:from-red-700 hover:to-rose-700 transition-all font-semibold text-center">
                    Thử Lại
                </a>
                <a href="{{ route('home') }}" class="flex-1 bg-white border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-50 transition-all font-semibold text-center">
                    Về Trang Chủ
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
