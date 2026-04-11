@extends('admin.layouts.app')

@section('title', 'Chi Tiết Đơn Hàng')
@section('page-title', 'Chi Tiết Giao Dịch')
@section('page-description', 'Mã Đơn Hàng: #' . $order->id)

@section('content')
<div class="max-w-5xl">
    <!-- Header -->
    <div class="bg-gray-900 rounded-2xl shadow-sm p-8 mb-8 text-white relative overflow-hidden group print:bg-white print:text-black print:border-b print:border-gray-200 print:shadow-none print:rounded-none">
        <div class="absolute top-0 right-0 w-80 h-80 bg-red-500/10 rounded-full -mr-40 -mt-40 blur-3xl transition-all group-hover:bg-red-500/20 print:hidden"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h2 class="text-2xl font-bold tracking-tight text-white print:text-black">Đơn Hàng #{{ $order->id }}</h2>
                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse mt-1 print:hidden"></span>
                </div>
                <p class="text-xs text-gray-400 font-medium print:text-gray-600">Khởi tạo lúc {{ $order->created_at->format('d/m/Y \l\ú\c H:i') }}</p>
            </div>
            
            <div class="text-left md:text-right">
                <div class="mb-2">
                    @if($order->status == 'pending')
                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-[10px] font-bold tracking-wide uppercase bg-yellow-500 text-black shadow-sm">
                            Chờ Duyệt
                        </span>
                    @elseif($order->status == 'shipping')
                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-[10px] font-bold tracking-wide uppercase bg-blue-600 text-white shadow-sm">
                            Đang Giao
                        </span>
                    @elseif($order->status == 'completed')
                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-[10px] font-bold tracking-wide uppercase bg-green-600 text-white shadow-sm">
                            Đã Hoàn Thành
                        </span>
                    @else
                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-[10px] font-bold tracking-wide uppercase bg-red-600 text-white shadow-sm">
                            Đã Hủy
                        </span>
                    @endif
                </div>
                <p class="text-2xl font-bold text-white tracking-tight print:text-black">{{ number_format($order->total) }}đ</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Customer Intelligence -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <h3 class="text-sm font-bold text-gray-800 mb-6 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                Thông Tin Khách Hàng
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-white rounded-lg border border-gray-200 flex items-center justify-center text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Họ và Tên</span>
                    </div>
                    <p class="text-sm font-bold text-gray-900 ml-11">{{ $order->customer_name }}</p>
                </div>

                <!-- Phone -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-white rounded-lg border border-gray-200 flex items-center justify-center text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Số Điện Thoại</span>
                    </div>
                    <p class="text-sm font-bold text-gray-900 ml-11">{{ $order->customer_phone }}</p>
                </div>

                <!-- Address -->
                <div class="md:col-span-2 bg-gray-50 rounded-xl p-5 border border-gray-100">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-white rounded-lg border border-gray-200 flex items-center justify-center text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Địa Chỉ Nhận Hàng</span>
                    </div>
                    <p class="text-sm font-bold text-gray-900 ml-11">{{ $order->customer_address }}</p>
                </div>

                <!-- Payment Method -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-white rounded-lg border border-gray-200 flex items-center justify-center text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Thanh Toán</span>
                    </div>
                    <p class="text-sm font-bold text-gray-900 ml-11">
                        @if($order->payment_method == 'cod')
                            Thanh toán khi nhận hàng (COD)
                        @else
                            {{ $order->payment_method }}
                        @endif
                    </p>
                </div>

                <!-- Date -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-white rounded-lg border border-gray-200 flex items-center justify-center text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Ngày Đặt Hàng</span>
                    </div>
                    <p class="text-sm font-bold text-gray-900 ml-11">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- System Operations -->
        <div class="bg-gray-800 rounded-2xl shadow-sm p-8 text-white relative overflow-hidden print:hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-red-500/10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
            
            <h3 class="text-sm font-bold text-gray-300 mb-8 relative z-10 flex items-center gap-3">
                 Cập Nhật Trạng Thái
            </h3>
            
            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="relative z-10">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Chọn trạng thái mới</label>
                    <select name="status" class="w-full bg-gray-900/50 border border-gray-700 rounded-xl px-4 py-3 text-sm font-bold text-white focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all cursor-pointer outline-none appearance-none">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }} class="bg-gray-800 text-white">Chờ Duyệt</option>
                        <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }} class="bg-gray-800 text-white">Đang Giao</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }} class="bg-gray-800 text-white">Hoàn Thành</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }} class="bg-gray-800 text-white">Hủy Đơn</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-3 bg-red-600 text-white rounded-xl font-bold text-sm hover:bg-red-700 transition-all shadow-sm active:scale-95">
                    Lưu Thay Đổi
                </button>
            </form>

            <!-- Order Timeline (Simplified) -->
            <div class="mt-8 pt-6 border-t border-gray-700 relative z-10">
                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-4">Lịch Sử Cập Nhật</h4>
                <div class="space-y-4">
                    @foreach($histories as $history)
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 {{ $loop->first ? 'bg-green-500 animate-pulse' : 'bg-gray-500' }} rounded-full mt-1.5"></div>
                        <div>
                            <p class="text-xs font-semibold text-gray-300 mb-0.5">{{ $history->description }}</p>
                            <p class="text-[11px] text-gray-500">{{ $history->created_at->format('d/m/Y H:i:s') }} - Bở người dùng #{{ $history->causer_id }}</p>
                        </div>
                    </div>
                    @endforeach
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-gray-500 rounded-full mt-1.5"></div>
                        <div>
                            <p class="text-xs font-semibold text-gray-300 mb-0.5">Đặt Hàng Thành Công</p>
                            <p class="text-[11px] text-gray-500">{{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Manifest -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                Danh Sách Sản Phẩm
            </h3>
            <span class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-lg text-[11px] font-bold tracking-wide">{{ $order->items->count() }} Sản Phẩm</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-8 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Sản Phẩm</th>
                        <th class="px-8 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Đơn Giá</th>
                        <th class="px-8 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Số Lượng</th>
                        <th class="px-8 py-4 text-right text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Thành Tiền</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($order->items as $item)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-8 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 overflow-hidden rounded-xl border border-gray-100">
                                    @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover" alt="{{ $item->product->name }}">
                                    @else
                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center text-[10px] font-medium text-gray-400">NO IMG</div>
                                    @endif
                                </div>
                                <div class="max-w-xs">
                                    <p class="text-sm font-bold text-gray-800 line-clamp-2 leading-tight">{{ $item->product->name }}</p>
                                    <p class="text-[11px] font-medium text-gray-500 mt-1">Mã SP: #{{ $item->product->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-4">
                            <span class="font-bold text-gray-800">{{ number_format($item->price) }}đ</span>
                        </td>
                        <td class="px-8 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-md text-[11px] font-semibold bg-gray-100 text-gray-700">
                                x{{ $item->quantity }}
                            </span>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <span class="font-bold text-red-600">{{ number_format($item->price * $item->quantity) }}đ</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="bg-gray-50 px-8 py-6 border-t border-gray-100">
            <div class="flex flex-col items-end">
                <div class="w-full md:w-64 space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="font-medium text-gray-600">Tạm Tính</span>
                        <span class="font-bold text-gray-900">{{ number_format($order->total) }}đ</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="font-medium text-gray-600">Phí Vận Chuyển</span>
                        <span class="font-bold text-gray-900">0đ</span>
                    </div>
                    <div class="h-px bg-gray-200 my-2"></div>
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-gray-800">Tổng Cấu</span>
                        <span class="text-xl font-bold text-red-600">{{ number_format($order->total) }}đ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Controls -->
    <div class="flex flex-col md:flex-row gap-4 justify-end print:hidden">
        <a href="{{ route('admin.orders.print', $order) }}" target="_blank" class="px-6 py-3 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold text-sm hover:bg-gray-50 hover:text-gray-900 transition-all shadow-sm active:scale-95 inline-flex justify-center items-center gap-2">
             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            In Phiếu
        </a>
        <a href="{{ route('admin.orders.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold text-sm hover:bg-gray-200 transition-all text-center">
            Trở Về Danh Sách
        </a>
    </div>
</div>
@endsection
