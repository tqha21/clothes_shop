<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn #{{ $order->id }} - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; background: white; }
            .print-padding { padding: 0 !important; border: none !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-gray-100 py-10 px-4">
    <div class="max-w-4xl mx-auto bg-white border border-gray-200 shadow-sm rounded-xl overflow-hidden print-padding">
        <!-- Header -->
        <div class="p-8 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-3xl font-bold text-red-600 tracking-tight mb-1">{{ config('app.name', 'SHOP QUẦN ÁO') }}</h1>
                <p class="text-sm text-gray-500 font-medium italic">Thời trang cao cấp - Phong cách hiện đại</p>
                <div class="mt-4 space-y-1">
                    <p class="text-[12px] text-gray-600">Địa chỉ: 123 Đường ABC, Quận XYZ, TP.HCM</p>
                    <p class="text-[12px] text-gray-600">Hotline: 0909 123 456 - Email: contact@shopquanao.com</p>
                </div>
            </div>
            <div class="text-left md:text-right">
                <h2 class="text-xl font-bold text-gray-900 uppercase tracking-widest mb-2">Hóa Đơn Bán Hàng</h2>
                <p class="text-sm text-gray-500">Mã đơn: <span class="font-bold text-gray-900">#{{ $order->id }}</span></p>
                <p class="text-sm text-gray-500">Ngày in: {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-8 bg-gray-50/50">
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Thông tin khách hàng</h3>
                <div class="space-y-2">
                    <p class="text-sm font-bold text-gray-900">{{ $order->customer_name }}</p>
                    <p class="text-sm text-gray-600">{{ $order->customer_phone }}</p>
                    <p class="text-sm text-gray-600 line-clamp-2 leading-relaxed">{{ $order->customer_address }}</p>
                </div>
            </div>
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Chi tiết thanh toán</h3>
                <div class="space-y-2">
                    <p class="text-sm text-gray-600">Phương thức: <span class="font-bold text-gray-900 uppercase">{{ $order->payment_method == 'cod' ? 'Tiền mặt (COD)' : $order->payment_method }}</span></p>
                    <p class="text-sm text-gray-600">Tình trạng: 
                        <span class="font-bold text-gray-900">
                            @if($order->status == 'pending') Chờ duyệt
                            @elseif($order->status == 'shipping') Đang giao
                            @elseif($order->status == 'completed') Đã hoàn thành
                            @else Đã hủy @endif
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="p-8">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-gray-100">
                        <th class="py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Sản phẩm</th>
                        <th class="py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Đơn giá</th>
                        <th class="py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Số lượng</th>
                        <th class="py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Thành tiền</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="py-4">
                            <p class="text-sm font-bold text-gray-900">{{ $item->product->name }}</p>
                            <p class="text-[11px] text-gray-400 uppercase mt-0.5">Mã SP: #{{ $item->product->id }}</p>
                        </td>
                        <td class="py-4 text-center text-sm font-medium text-gray-700">{{ number_format($item->price) }}đ</td>
                        <td class="py-4 text-center text-sm font-medium text-gray-700">x{{ $item->quantity }}</td>
                        <td class="py-4 text-right text-sm font-bold text-gray-900">{{ number_format($item->price * $item->quantity) }}đ</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Summary -->
            <div class="mt-10 border-t border-gray-100 pt-6 flex justify-end">
                <div class="w-full md:w-64 space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Tạm tính:</span>
                        <span class="font-bold text-gray-800">{{ number_format($order->total) }}đ</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Phí vận chuyển:</span>
                        <span class="font-bold text-gray-800">0đ</span>
                    </div>
                    <div class="h-px bg-gray-200"></div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-900 uppercase">Tổng cộng:</span>
                        <span class="text-xl font-bold text-red-600">{{ number_format($order->total) }}đ</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-8 bg-gray-50 border-t border-gray-100">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-[11px] text-gray-400 italic">
                <p>Cảm ơn quý khách đã tin tưởng và mua sắm tại {{ config('app.name') }}!</p>
                <p>Mọi thắc mắc xin vui lòng liên hệ hotline: 0909 123 456</p>
            </div>
        </div>
    </div>

    <!-- Print Button (Visible only on screen) -->
    <div class="max-w-4xl mx-auto mt-8 flex justify-center gap-4 no-print">
        <button onclick="window.print()" class="px-8 py-3 bg-red-600 text-white rounded-xl font-bold text-sm uppercase tracking-wide hover:bg-red-700 transition-all shadow-md active:scale-95 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            In Ngay
        </button>
        <button onclick="window.close()" class="px-8 py-3 bg-white border border-gray-200 text-gray-600 rounded-xl font-bold text-sm uppercase tracking-wide hover:bg-gray-50 transition-all active:scale-95">
            Đóng
        </button>
    </div>

    <script>
        window.onload = function() {
            // Optional: window.print();
        }
    </script>
</body>
</html>
