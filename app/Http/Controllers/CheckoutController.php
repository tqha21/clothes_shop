<?php

namespace App\Http\Controllers;

use App\Exceptions\BusinessException;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\UserSavedCoupon;
use App\Services\OrderService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(
        protected StockService $stockService,
        protected OrderService $orderService
    ) {}

    public function index(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $membershipDiscount = 0;
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->discount_percent > 0) {
                $membershipDiscount = ($total * $user->discount_percent) / 100;
            }
        }
        $subtotal = $total;
        $total = $subtotal - $membershipDiscount;

        // Get available coupons for dropdown
        $availableCoupons = Coupon::where('is_active', true)
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->where(function($query) {
                $query->whereNull('usage_limit')
                      ->orWhereRaw('used_count < usage_limit');
            })
            ->orderBy('value', 'desc')
            ->get();

        // Handle coupon application
        if ($request->has('apply_coupon')) {
            $code = strtoupper($request->apply_coupon);
            $coupon = Coupon::where('code', $code)->first();

            if (!$coupon) {
                return redirect()->route('checkout.index')
                    ->with('coupon_error', 'Mã giảm giá không tồn tại!');
            }

            if (!$coupon->isValid($total)) {
                $message = 'Mã giảm giá không hợp lệ!';
                
                if (!$coupon->is_active) {
                    $message = 'Mã giảm giá đã bị vô hiệu hóa!';
                } elseif ($coupon->expires_at && $coupon->expires_at->isPast()) {
                    $message = 'Mã giảm giá đã hết hạn!';
                } elseif ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
                    $message = 'Mã giảm giá đã hết lượt sử dụng!';
                } elseif ($total < $coupon->min_order_amount) {
                    $message = 'Đơn hàng tối thiểu ' . number_format($coupon->min_order_amount) . 'đ để sử dụng mã này!';
                }

                return redirect()->route('checkout.index')
                    ->with('coupon_error', $message);
            }

            $discount = $coupon->calculateDiscount($total);
            session()->put('coupon_code', $code);
            session()->put('coupon_id', $coupon->id);
            session()->put('discount', $discount);

            return redirect()->route('checkout.index')
                ->with('coupon_success', 'Áp dụng mã giảm giá thành công! Giảm ' . number_format($discount) . 'đ');
        }

        // Handle coupon removal
        if ($request->has('remove_coupon')) {
            session()->forget(['coupon_code', 'coupon_id', 'discount']);
            return redirect()->route('checkout.index')
                ->with('success', 'Đã hủy mã giảm giá!');
        }

        return view('checkout.index', compact('cart', 'total', 'subtotal', 'membershipDiscount', 'availableCoupons'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'province_name' => 'nullable|string',
            'district_name' => 'nullable|string',
            'ward_name' => 'nullable|string',
            'payment_method' => 'required|in:COD,BANK,EWALLET,VNPAY',
            'shipping_method' => 'nullable|in:standard,express,economy',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $total = 0;
        $quantities = [];
        foreach ($cart as $cartId => $item) {
            $total += $item['price'] * $item['quantity'];
            $productId = (int) ($item['id'] ?? $cartId);
            $quantities[$productId] = ($quantities[$productId] ?? 0) + (int) $item['quantity'];
        }

        // Validate stock via StockService
        foreach ($quantities as $productId => $qty) {
            $available = $this->stockService->totalAvailable($productId);
            if ($available < $qty) {
                $productName = Product::whereKey($productId)->value('name') ?? ('#' . $productId);
                return redirect()->route('cart.index')
                    ->with('error', 'Sản phẩm "' . $productName . '" chỉ còn ' . $available . ' sản phẩm trong hệ thống kho. Vui lòng điều chỉnh số lượng!');
            }
        }

        $membershipDiscount = 0;
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->discount_percent > 0) {
                $membershipDiscount = ($total * $user->discount_percent) / 100;
            }
        }
        $subtotal = $total;
        $total = $subtotal - $membershipDiscount;

        // Xử lý hình thức vận chuyển phong cách Shopee
        $shippingMethod = $request->input('shipping_method', 'standard');
        $shippingFee = 25000;
        $shippingCarrier = 'SPX Express (Shopee Xpress)';
        $estimatedDelivery = now()->addDays(2)->format('d/m') . ' - ' . now()->addDays(3)->format('d/m/Y');

        if ($shippingMethod === 'express') {
            $shippingFee = 45000;
            $shippingCarrier = 'Shopee Xpress Hỏa Tốc (Instant 2-4h)';
            $estimatedDelivery = 'Hôm nay (' . now()->addHours(3)->format('H:i') . ' - ' . now()->addHours(4)->format('H:i, d/m/Y') . ')';
        } elseif ($shippingMethod === 'economy') {
            $shippingFee = 15000;
            $shippingCarrier = 'Viettel Post / VNPost Tiết Kiệm';
            $estimatedDelivery = now()->addDays(3)->format('d/m') . ' - ' . now()->addDays(5)->format('d/m/Y');
        } else {
            if ($subtotal >= 500000) {
                $shippingFee = 0;
            }
        }

        $trackingCode = 'SPX-VN' . date('ymd') . rand(100000, 999999);
        $discount = session()->get('discount', 0);
        $couponId = session()->get('coupon_id');
        $finalTotal = max(0, $total - $discount + $shippingFee);

        // Build full address
        $fullAddress = $request->customer_address;
        if ($request->ward_name && $request->district_name && $request->province_name) {
            $fullAddress = trim($request->customer_address) . ', ' . $request->ward_name . ', ' . $request->district_name . ', ' . $request->province_name;
        }

        try {
            $order = DB::transaction(function () use (
                $request, $cart, $quantities, $fullAddress, $subtotal, $discount, $couponId,
                $finalTotal, $shippingMethod, $shippingCarrier, $shippingFee, $trackingCode, $estimatedDelivery
            ) {
                // Tạo đơn hàng Online
                $order = Order::create([
                    'channel' => Order::CHANNEL_ONLINE,
                    'user_id' => auth()->id(),
                    'customer_name' => $request->customer_name,
                    'customer_phone' => $request->customer_phone,
                    'customer_address' => $fullAddress,
                    'subtotal' => $subtotal,
                    'discount_amount' => $discount,
                    'coupon_id' => $couponId,
                    'total' => $finalTotal,
                    'status' => 'pending',
                    'payment_method' => $request->payment_method,
                    'payment_status' => $request->payment_method === 'VNPAY' ? 'pending' : 'unpaid',
                    'shipping_method' => $shippingMethod,
                    'shipping_carrier' => $shippingCarrier,
                    'shipping_fee' => $shippingFee,
                    'tracking_code' => $trackingCode,
                    'estimated_delivery' => $estimatedDelivery,
                ]);

                $this->orderService->assignCode($order);

                // Tạo order items
                foreach ($cart as $cartId => $item) {
                    $productId = (int) ($item['id'] ?? $cartId);
                    $product = Product::find($productId);

                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $productId,
                        'product_name' => $product?->name ?? ($item['name'] ?? ''),
                        'original_price' => $product?->price ?? $item['price'],
                        'quantity'   => $item['quantity'],
                        'price'      => $item['price'],
                        'size'       => $item['size'] ?? null,
                        'color'      => $item['color'] ?? null,
                    ]);

                    if ($product) {
                        $product->increment('sold_count', $item['quantity']);
                    }
                }

                // Trừ kho đa cửa hàng an toàn qua StockService
                $this->stockService->deductForOnlineOrder($order, $quantities, auth()->id());

                // Khởi tạo bản ghi thanh toán
                Payment::create([
                    'order_id' => $order->id,
                    'type' => 'payment',
                    'method' => strtolower($request->payment_method),
                    'amount' => $finalTotal,
                    'status' => 'pending',
                    'note' => 'Đơn hàng online ' . $order->display_code,
                ]);

                return $order;
            });

            // Update coupon usage if applied
            if ($couponId) {
                $coupon = Coupon::find($couponId);
                if ($coupon) {
                    $coupon->increment('used_count');
                }
                if (Auth::check()) {
                    UserSavedCoupon::where('user_id', Auth::id())
                        ->where('coupon_id', $couponId)
                        ->update(['is_used' => true]);
                }
            }

            // Tích điểm thành viên
            if (Auth::check()) {
                $user = Auth::user();
                $pointsEarned = floor($finalTotal / 1000);
                $user->increment('points', $pointsEarned);
                $user->updateMembershipTier();
            }

            session()->forget(['cart', 'coupon_code', 'coupon_id', 'discount']);

            if ($request->payment_method === 'VNPAY') {
                return $this->redirectToVnpay($order);
            }

            return redirect()->route('checkout.success', $order->id);
        } catch (BusinessException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        } catch (\Exception $e) {
            report($e);
            return redirect()->route('cart.index')->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    private function redirectToVnpay(Order $order)
    {
        $vnp_TmnCode = config('vnpay.tmn_code');
        $vnp_HashSecret = config('vnpay.hash_secret');
        $vnp_Url = config('vnpay.url');
        $vnp_ReturnUrl = config('vnpay.return_url');

        $vnp_TxnRef = $order->id . '_' . time();
        $vnp_OrderInfo = 'Thanh toan don hang #' . $order->id;
        $vnp_Amount = $order->total * 100;
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        ksort($inputData);

        $query = "";
        $hashdata = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

        session()->put('vnpay_order_id', $order->id);

        return redirect()->away($vnp_Url);
    }

    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = config('vnpay.hash_secret');
        $inputData = [];

        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);

        ksort($inputData);
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $txnRef = $request->vnp_TxnRef ?? '';
        $orderId = explode('_', $txnRef)[0] ?? null;
        $order = $orderId ? Order::find($orderId) : null;
        $vnp_ResponseCode = $request->vnp_ResponseCode ?? '';

        if ($secureHash === $vnp_SecureHash) {
            if ($vnp_ResponseCode == '00') {
                if ($order) {
                    $order->update(['payment_status' => 'paid']);
                    Payment::where('order_id', $order->id)->where('status', 'pending')->update([
                        'status' => 'success',
                        'paid_at' => now(),
                        'reference' => $request->vnp_TransactionNo ?? '',
                    ]);
                }
                
                session()->forget('vnpay_order_id');

                return view('checkout.vnpay_return', [
                    'success' => true,
                    'message' => 'Thanh toán VNPAY thành công!',
                    'order' => $order,
                    'vnp_ResponseCode' => $vnp_ResponseCode,
                    'vnp_TransactionNo' => $request->vnp_TransactionNo ?? '',
                    'vnp_Amount' => ($request->vnp_Amount ?? 0) / 100,
                    'vnp_BankCode' => $request->vnp_BankCode ?? '',
                    'vnp_PayDate' => $request->vnp_PayDate ?? '',
                ]);
            } else {
                // Hủy đơn và hoàn kho an toàn qua OrderService
                if ($order && $order->status === 'pending') {
                    $this->orderService->cancel($order, null, 'Thanh toán VNPAY không thành công');
                }

                session()->forget('vnpay_order_id');

                return view('checkout.vnpay_return', [
                    'success' => false,
                    'message' => 'Thanh toán VNPAY thất bại! Mã lỗi: ' . $vnp_ResponseCode,
                    'order' => $order,
                    'vnp_ResponseCode' => $vnp_ResponseCode,
                    'vnp_TransactionNo' => '',
                    'vnp_Amount' => ($request->vnp_Amount ?? 0) / 100,
                    'vnp_BankCode' => '',
                    'vnp_PayDate' => '',
                ]);
            }
        } else {
            return view('checkout.vnpay_return', [
                'success' => false,
                'message' => 'Chữ ký không hợp lệ! Giao dịch bị nghi ngờ giả mạo.',
                'order' => $order,
                'vnp_ResponseCode' => 'INVALID_HASH',
            ]);
        }
    }

    public function success(Order $order)
    {
        return view('checkout.success', compact('order'));
    }
}
