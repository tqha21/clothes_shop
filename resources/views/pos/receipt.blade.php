<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn bán lẻ - {{ $order->display_code }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif, 'Courier New', Courier, monospace;
        }
        body {
            background-color: #f3f4f6;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .receipt-container {
            width: 80mm;
            background: #fff;
            padding: 15px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            color: #111;
            font-size: 13px;
            line-height: 1.4;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .store-title {
            font-size: 18px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .store-sub {
            font-size: 11px;
            color: #555;
            margin-bottom: 3px;
        }
        .divider {
            border-top: 1px dashed #444;
            margin: 10px 0;
        }
        .divider-double {
            border-top: 2px solid #111;
            margin: 10px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 3px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
        }
        .items-table th {
            font-size: 11px;
            text-transform: uppercase;
            border-bottom: 1px solid #111;
            padding: 4px 0;
        }
        .items-table td {
            padding: 5px 0;
            vertical-align: top;
            font-size: 12px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            font-size: 12px;
        }
        .summary-total {
            font-size: 15px;
            font-weight: 800;
        }
        .barcode {
            margin: 12px 0 6px 0;
            letter-spacing: 4px;
            font-family: monospace;
            font-size: 14px;
            font-weight: bold;
        }
        .no-print-bar {
            position: fixed;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 10px;
        }
        .btn {
            background: #e11d48;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            font-size: 13px;
        }
        .btn-secondary {
            background: #4b5563;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .receipt-container {
                box-shadow: none;
                width: 100%;
                padding: 0;
            }
            .no-print-bar {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="no-print-bar">
        <button class="btn" onclick="window.print()">🖨️ In Hóa Đơn</button>
        <a href="{{ route('pos.index') }}" class="btn btn-secondary">Quay lại POS</a>
    </div>

    <div class="receipt-container">
        <div class="text-center">
            <div class="store-title">HTD STORE</div>
            <div class="store-sub">{{ $order->store->name ?? 'Cửa Hàng Thời Trang HTD' }}</div>
            <div class="store-sub">{{ $order->store->address ?? 'Hà Nội' }}</div>
            <div class="store-sub">Hotline: {{ $order->store->phone ?? '1900 8888' }}</div>
        </div>

        <div class="divider"></div>

        <div class="text-center font-bold" style="font-size: 15px; margin-bottom: 6px;">HÓA ĐƠN BÁN LẺ</div>
        <div class="info-row">
            <span>Mã hóa đơn:</span>
            <span class="font-bold">{{ $order->display_code }}</span>
        </div>
        <div class="info-row">
            <span>Thời gian:</span>
            <span>{{ $order->completed_at ? $order->completed_at->format('d/m/Y H:i') : $order->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="info-row">
            <span>Thu ngân:</span>
            <span>{{ $order->staff->name ?? 'Thu ngân' }}</span>
        </div>
        @if($order->customer_name && $order->customer_name !== 'Khách lẻ tại quầy')
        <div class="info-row">
            <span>Khách hàng:</span>
            <span>{{ $order->customer_name }} {{ $order->customer_phone ? '('.$order->customer_phone.')' : '' }}</span>
        </div>
        @endif

        <div class="divider"></div>

        <table class="items-table">
            <thead>
                <tr>
                    <th class="text-left" style="width: 50%;">Sản phẩm</th>
                    <th class="text-center" style="width: 15%;">SL</th>
                    <th class="text-right" style="width: 35%;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td class="text-left">
                        <div class="font-bold">{{ $item->display_name }}</div>
                        @if($item->size || $item->color)
                        <div style="font-size: 11px; color: #666;">
                            {{ $item->size ? 'Size: ' . $item->size : '' }} {{ $item->color ? ' | Màu: ' . $item->color : '' }}
                        </div>
                        @endif
                        <div style="font-size: 11px; color: #666;">
                            {{ number_format($item->price) }}đ
                        </div>
                    </td>
                    <td class="text-center font-bold">{{ $item->quantity }}</td>
                    <td class="text-right font-bold">{{ number_format($item->line_total) }}đ</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider"></div>

        <div class="summary-row">
            <span>Tổng tiền hàng:</span>
            <span>{{ number_format($order->subtotal ?? $order->total) }}đ</span>
        </div>
        @if($order->discount_amount > 0)
        <div class="summary-row" style="color: #dc2626;">
            <span>Chiết khấu / Giảm giá:</span>
            <span>-{{ number_format($order->discount_amount) }}đ</span>
        </div>
        @endif

        <div class="divider-double"></div>

        <div class="summary-row summary-total">
            <span>THANH TOÁN:</span>
            <span>{{ number_format($order->total) }}đ</span>
        </div>

        @php
            $payment = $order->payments->first();
        @endphp
        <div class="summary-row" style="margin-top: 6px;">
            <span>Hình thức:</span>
            <span class="font-bold">{{ $payment ? $payment->method_label : strtoupper($order->payment_method) }}</span>
        </div>
        @if($payment && $payment->method === 'cash')
        <div class="summary-row">
            <span>Tiền khách đưa:</span>
            <span>{{ number_format($payment->received_amount ?? $order->total) }}đ</span>
        </div>
        <div class="summary-row">
            <span>Tiền thối lại:</span>
            <span class="font-bold">{{ number_format($payment->change_amount ?? 0) }}đ</span>
        </div>
        @endif

        <div class="divider"></div>

        <div class="text-center" style="margin-top: 10px;">
            <div class="barcode">*{{ $order->display_code }}*</div>
            <div style="font-size: 11px; color: #666;">Quý khách vui lòng kiểm tra hàng & hóa đơn trước khi rời quầy.</div>
            <div style="font-size: 11px; color: #666; margin-top: 2px;">Đổi hàng trong vòng 07 ngày kèm hóa đơn còn nguyên vẹn.</div>
            <div class="font-bold" style="margin-top: 8px;">CẢM ƠN QUÝ KHÁCH & HẸN GẶP LẠI!</div>
        </div>
    </div>

    <script>
        // Tự động bật hộp thoại in khi tải xong
        window.addEventListener('load', function() {
            setTimeout(function() {
                // window.print();
            }, 300);
        });
    </script>
</body>
</html>
