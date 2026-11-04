<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn #HTD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }} - HTD Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Mono:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #0f172a;
            background-color: #f1f5f9;
        }
        .font-mono-code {
            font-family: 'Space Mono', monospace;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .invoice-container {
                border: none !important;
                box-shadow: none !important;
                max-width: 100% !important;
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            @page {
                size: A4 portrait;
                margin: 12mm 15mm;
            }
        }
    </style>
</head>
<body class="py-6 sm:py-10 px-3 sm:px-6">

    <!-- Top Action Bar (Screen Only) -->
    <div class="no-print max-w-4xl mx-auto mb-6 flex flex-wrap items-center justify-between gap-3 bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex items-center gap-2">
            <a href="javascript:history.back()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Quay Lại
            </a>
            <span class="text-xs text-slate-400">|</span>
            <span class="text-xs text-slate-500 font-medium">Bản in Hóa đơn bán hàng chuẩn khổ giấy A4</span>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" onclick="window.print()" class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-indigo-600 hover:opacity-95 text-white text-xs font-bold rounded-xl shadow-md transition flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                In Hóa Đơn Ngay (Ctrl + P)
            </button>
        </div>
    </div>

    <!-- Main Invoice Paper Container -->
    <div class="invoice-container max-w-4xl mx-auto bg-white border border-slate-300 shadow-xl rounded-2xl p-8 sm:p-12 relative overflow-hidden">

        <!-- Watermark / Stamp -->
        <div class="absolute right-8 top-36 opacity-10 pointer-events-none select-none border-4 border-red-600 text-red-600 font-black text-4xl uppercase px-6 py-2 rounded-2xl rotate-12">
            {{ $order->payment_method === 'COD' ? 'SHIP COD' : 'ĐÃ THANH TOÁN' }}
        </div>

        <!-- 1. Header: Store Brand & Invoice Meta -->
        <div class="border-b-2 border-slate-900 pb-6 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-red-600 to-indigo-600 text-white flex items-center justify-center font-black text-2xl shadow-md flex-shrink-0">
                    HTD
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-950 uppercase">HTD STORE</h1>
                    <p class="text-xs font-semibold text-red-600 uppercase tracking-widest mt-0.5">Thời Trang Chất Lượng & Hiện Đại</p>
                    <div class="text-[11.5px] text-slate-600 mt-2 space-y-0.5">
                        <p>📍 <strong>Trụ sở:</strong> Số 123 Đường Cầu Giấy, Quận Cầu Giấy, Hà Nội</p>
                        <p>📞 <strong>Hotline:</strong> 0383.390.611 | ✉️ <strong>Email:</strong> support@htdstore.vn</p>
                        <p>🌐 <strong>Website:</strong> www.htdstore.vn</p>
                    </div>
                </div>
            </div>

            <div class="text-left sm:text-right bg-slate-50 sm:bg-transparent p-4 sm:p-0 rounded-xl border sm:border-0 border-slate-200 w-full sm:w-auto">
                <h2 class="text-xl sm:text-2xl font-black uppercase tracking-wider text-slate-900">HÓA ĐƠN BÁN HÀNG</h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">PHIẾU XUẤT KHO KIÊM GIAO NHẬN</p>
                <div class="mt-3 space-y-1">
                    <p class="text-xs text-slate-600">
                        Mã đơn hàng: <strong class="text-base text-red-600 font-mono-code font-bold">#HTD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong>
                    </p>
                    <p class="text-xs text-slate-500">
                        Ngày đặt hàng: <strong>{{ $order->created_at->format('d/m/Y H:i') }}</strong>
                    </p>
                    <p class="text-xs text-slate-500">
                        Thời gian in: <strong>{{ now()->format('d/m/Y H:i:s') }}</strong>
                    </p>
                </div>
            </div>
        </div>

        <!-- 2. Customer & Shipping Info (2 Columns) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 text-xs sm:text-[13px]">
            <!-- Left: Customer Info -->
            <div class="p-4 bg-slate-50/80 rounded-xl border border-slate-200 space-y-2">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider border-b border-slate-200 pb-1.5 flex items-center gap-1.5">
                    <span>👤</span> THÔNG TIN NGƯỜI NHẬN
                </h3>
                <div class="space-y-1 pt-1 text-slate-700">
                    <p>Họ tên: <strong class="text-slate-950 font-bold text-sm">{{ $order->customer_name }}</strong></p>
                    <p>Số điện thoại: <strong class="text-slate-950 font-mono-code font-bold">{{ $order->customer_phone }}</strong></p>
                    <p class="leading-relaxed">Địa chỉ giao: <strong class="text-slate-900 font-medium">{{ $order->customer_address }}</strong></p>
                </div>
            </div>

            <!-- Right: Shipping & Payment Info -->
            <div class="p-4 bg-slate-50/80 rounded-xl border border-slate-200 space-y-2">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider border-b border-slate-200 pb-1.5 flex items-center gap-1.5">
                    <span>🚚</span> VẬN CHUYỂN & THANH TOÁN
                </h3>
                <div class="space-y-1 pt-1 text-slate-700">
                    <p>Đơn vị vận chuyển: <strong class="text-orange-600 font-bold">{{ $order->shipping_carrier ?? 'SPX Express (Shopee Xpress)' }}</strong></p>
                    <p>Hình thức giao: <strong class="text-slate-900 font-semibold">{{ $order->shipping_method_name }}</strong></p>
                    <p>Mã vận đơn: <strong class="font-mono-code text-slate-900 font-bold bg-white px-2 py-0.5 rounded border border-slate-200">{{ $order->formatted_tracking_code }}</strong></p>
                    <p>Thanh toán: <strong class="text-slate-900 font-bold uppercase">{{ $order->payment_method === 'COD' ? 'Thu hộ khi nhận hàng (COD)' : $order->payment_method }}</strong></p>
                </div>
            </div>
        </div>

        <!-- 3. Product Items Table -->
        <div class="mb-8">
            <table class="w-full text-left border-collapse border border-slate-300">
                <thead>
                    <tr class="bg-slate-900 text-white text-xs uppercase tracking-wider">
                        <th class="py-3 px-3 border border-slate-800 text-center w-12">STT</th>
                        <th class="py-3 px-4 border border-slate-800">Tên Sản Phẩm / Quy Cách</th>
                        <th class="py-3 px-3 border border-slate-800 text-center w-24">Phân Loại</th>
                        <th class="py-3 px-3 border border-slate-800 text-center w-16">SL</th>
                        <th class="py-3 px-3 border border-slate-800 text-right w-28">Đơn Giá</th>
                        <th class="py-3 px-3 border border-slate-800 text-right w-32">Thành Tiền</th>
                    </tr>
                </thead>
                <tbody class="text-xs sm:text-[13px] text-slate-800 divide-y divide-slate-200">
                    @php $stt = 1; @endphp
                    @foreach($order->items as $item)
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-3 px-3 border border-slate-200 text-center font-medium">{{ $stt++ }}</td>
                        <td class="py-3 px-4 border border-slate-200">
                            <strong class="text-slate-950 font-bold block">{{ $item->product->name ?? 'Sản phẩm' }}</strong>
                            <span class="text-[11px] text-slate-400 font-mono-code">Mã SP: #SP-{{ $item->product_id }}</span>
                        </td>
                        <td class="py-3 px-3 border border-slate-200 text-center text-slate-600">
                            @if($item->size)<span class="font-semibold">Size {{ $item->size }}</span>@endif
                            @if($item->size && $item->color) - @endif
                            @if($item->color)<span>{{ $item->color }}</span>@endif
                            @if(!$item->size && !$item->color)<span>Tiêu chuẩn</span>@endif
                        </td>
                        <td class="py-3 px-3 border border-slate-200 text-center font-bold text-slate-950">{{ $item->quantity }}</td>
                        <td class="py-3 px-3 border border-slate-200 text-right font-mono-code">{{ number_format($item->price) }}đ</td>
                        <td class="py-3 px-3 border border-slate-200 text-right font-mono-code font-bold text-slate-950">
                            {{ number_format($item->price * $item->quantity) }}đ
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- 4. Order Summary & Totals Calculation -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start mb-8 text-xs sm:text-sm">
            <!-- Left: Notes & Shopee Co-inspection Policy -->
            <div class="md:col-span-7 p-4 rounded-xl border border-dashed border-slate-300 space-y-2 text-slate-600">
                <p class="font-bold text-slate-900 flex items-center gap-1.5 uppercase text-xs tracking-wider">
                    <span>📌</span> CHÍNH SÁCH BÁN HÀNG & ĐỒNG KIỂM
                </p>
                <ul class="list-disc list-inside space-y-1 text-[12px] leading-relaxed text-slate-700">
                    <li><strong>Được đồng kiểm:</strong> Quý khách có quyền mở bưu phẩm kiểm tra đúng mẫu, đúng size cùng nhân viên giao hàng trước khi nhận và thanh toán.</li>
                    <li><strong>Đổi size miễn phí:</strong> HTD Store hỗ trợ đổi size miễn phí trong vòng 7 ngày nếu không vừa phom (sản phẩm còn nguyên tem mác).</li>
                    <li>Mọi thắc mắc hoặc hỗ trợ khẩn cấp, vui lòng liên hệ Hotline: <strong>0383.390.611</strong>.</li>
                </ul>
            </div>

            <!-- Right: Subtotal, Shipping, Discount, Grand Total -->
            <div class="md:col-span-5 bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-2.5">
                @php
                    $subtotalCalc = 0;
                    foreach($order->items as $it) {
                        $subtotalCalc += $it->price * $it->quantity;
                    }
                    $shippingFee = $order->shipping_fee ?? 0;
                    $discountCalc = ($subtotalCalc + $shippingFee) - $order->total;
                    if ($discountCalc < 0) $discountCalc = 0;
                @endphp

                <div class="flex justify-between text-slate-600">
                    <span>Tổng tiền hàng:</span>
                    <strong class="font-mono-code text-slate-900">{{ number_format($subtotalCalc) }}đ</strong>
                </div>

                @if($discountCalc > 0)
                <div class="flex justify-between text-emerald-600 font-semibold">
                    <span>Giảm giá / Voucher:</span>
                    <span class="font-mono-code">-{{ number_format($discountCalc) }}đ</span>
                </div>
                @endif

                <div class="flex justify-between text-slate-600">
                    <span>Cước vận chuyển ({{ $order->shipping_carrier ?? 'SPX' }}):</span>
                    <strong class="font-mono-code {{ $shippingFee == 0 ? 'text-emerald-600' : 'text-slate-900' }}">
                        {{ $shippingFee == 0 ? 'MIỄN PHÍ' : number_format($shippingFee) . 'đ' }}
                    </strong>
                </div>

                <div class="pt-2 border-t-2 border-slate-900 flex justify-between items-baseline">
                    <span class="text-sm font-bold text-slate-950 uppercase">TỔNG THANH TOÁN:</span>
                    <span class="text-xl font-black text-red-600 font-mono-code">
                        {{ number_format($order->total) }}đ
                    </span>
                </div>
            </div>
        </div>

        <!-- 5. Signatures -->
        <div class="grid grid-cols-2 gap-8 text-center text-xs sm:text-sm pt-6 border-t border-slate-200">
            <div>
                <p class="font-bold uppercase tracking-wider text-slate-900">NGƯỜI NHẬN HÀNG</p>
                <p class="text-[11px] text-slate-400 italic mt-0.5">(Ký, ghi rõ họ tên và xác nhận đồng kiểm)</p>
                <div class="h-20"></div>
                <p class="text-xs font-semibold text-slate-700">{{ $order->customer_name }}</p>
            </div>
            <div>
                <p class="font-bold uppercase tracking-wider text-slate-900">ĐẠI DIỆN HTD STORE</p>
                <p class="text-[11px] text-slate-400 italic mt-0.5">(Ký, đóng dấu xuất kho)</p>
                <div class="h-20 flex items-center justify-center">
                    <span class="text-[11px] font-bold text-red-600 border border-red-400 rounded px-2 py-0.5 uppercase rotate-[-5deg]">
                        ĐÃ XUẤT KHO HTD
                    </span>
                </div>
                <p class="text-xs font-bold text-slate-900 uppercase">TẠ QUANG HÀ</p>
            </div>
        </div>

        <!-- Footer Thank you -->
        <div class="mt-8 pt-4 border-t border-slate-100 text-center text-[11px] text-slate-400">
            Cảm ơn quý khách đã tin tưởng và lựa chọn mua sắm tại <strong>HTD Store</strong>! Hẹn gặp lại quý khách! ✨
        </div>

    </div>

    <!-- Auto Print Script if requested -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('autoprint') === '1') {
                setTimeout(() => {
                    window.print();
                }, 300);
            }
        });
    </script>
</body>
</html>

