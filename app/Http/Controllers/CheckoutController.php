<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\InventoryLog;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\UserSavedCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
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
                ->with('coupon_success', 'Áp dụng mã giảm giá thành công! Bạn được giảm ' . number_format($discount) . 'đ');
        }

        // Voucher đã lưu của user (để hiển thị trong checkout)
        $savedCoupons = collect();
        if (Auth::check()) {
            $savedCoupons = UserSavedCoupon::with('coupon')
                ->where('user_id', Auth::id())
                ->where('is_used', false)
                ->whereHas('coupon', function($q) {
                    $q->where('is_active', true)
                      ->where(function($q2) {
                          $q2->whereNull('expires_at')->orWhere('expires_at', '>', now());
                      });
                })
                ->get();
        }

        // Apply discount
        if (session('coupon_code')) {
            $total = $total - session('discount', 0);
        }

        return view('checkout.index', compact('cart', 'total', 'subtotal', 'membershipDiscount', 'availableCoupons', 'savedCoupons'));
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
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // === Validate stock for all items before creating order ===
        foreach ($cart as $cartId => $item) {
            $productId = $item['id'] ?? $cartId;
            $product = Product::find($productId);
            if (!$product) {
                return redirect()->route('cart.index')
                    ->with('error', 'Sản phẩm "' . ($item['name'] ?? '') . '" không còn tồn tại!');
            }
            if ($product->stock < $item['quantity']) {
                return redirect()->route('cart.index')
                    ->with('error', 'Sản phẩm "' . $product->name . '" chỉ còn ' . $product->stock . ' sản phẩm trong kho. Vui lòng điều chỉnh số lượng!');
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

        // Apply discount if coupon exists
        $discount = session()->get('discount', 0);
        $couponId = session()->get('coupon_id');
        $finalTotal = $total - $discount;

        // Build full address
        $fullAddress = $request->customer_address;
        if ($request->ward_name && $request->district_name && $request->province_name) {
            $fullAddress = trim($request->customer_address) . ', ' . $request->ward_name . ', ' . $request->district_name . ', ' . $request->province_name;
        }

        // Create order (user must be authenticated)
        $order = Order::create([
            'user_id' => auth()->id(),
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $fullAddress,
            'total' => $finalTotal,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
        ]);

        // Create order items
        foreach ($cart as $cartId => $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item['id'] ?? $cartId,
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
                'size'       => $item['size'] ?? null,
                'color'      => $item['color'] ?? null,
            ]);

            // Update product stock and sold_count
            $productId = $item['id'] ?? $cartId;
            $product = Product::find($productId);
            if ($product) {
                $product->decrement('stock', $item['quantity']);
                $product->increment('sold_count', $item['quantity']);

                // Log inventory change for sale
                InventoryLog::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'type' => 'sale',
                    'quantity_changed' => -$item['quantity'],
                    'note' => 'Bán hàng (Đơn hàng #' . $order->id . ')'
                ]);
            }
        }

        // Update coupon usage if applied
        if ($couponId) {
            $coupon = Coupon::find($couponId);
            if ($coupon) {
                $coupon->increment('used_count');
            }
            // Mark saved coupon as used
            if (Auth::check()) {
                UserSavedCoupon::where('user_id', Auth::id())
                    ->where('coupon_id', $couponId)
                    ->update(['is_used' => true]);
            }
        }

        // Add points to user and update membership tier
        if (Auth::check()) {
            $user = Auth::user();
            $pointsEarned = floor($finalTotal / 1000);
            $user->increment('points', $pointsEarned);
            $user->updateMembershipTier();
        }

        // Clear cart and coupon session
        session()->forget(['cart', 'coupon_code', 'coupon_id', 'discount']);

        // If VNPAY, redirect to VNPAY payment gateway -> Bypassed per request
        // if ($request->payment_method === 'VNPAY') {
        //     return $this->redirectToVnpay($order);
        // }

        return redirect()->route('checkout.success', $order->id);
    }

    /**
     * Build VNPAY payment URL and redirect user to VNPAY gateway.
     */
    private function redirectToVnpay(Order $order)
    {
        $vnp_TmnCode = config('vnpay.tmn_code');
        $vnp_HashSecret = config('vnpay.hash_secret');
        $vnp_Url = config('vnpay.url');
        $vnp_ReturnUrl = config('vnpay.return_url');

        $vnp_TxnRef = $order->id . '_' . time(); // Mã giao dịch unique
        $vnp_OrderInfo = 'Thanh toan don hang #' . $order->id;
        $vnp_Amount = $order->total * 100; // VNPAY yêu cầu nhân 100
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

        // Sort by key
        ksort($inputData);

        // Build query string
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

        // Create secure hash
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

        // Save txn ref to session for verification
        session()->put('vnpay_order_id', $order->id);

        return redirect()->away($vnp_Url);
    }

    /**
     * Handle VNPAY return callback after payment.
     */
    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = config('vnpay.hash_secret');
        $inputData = [];

        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        // Remove hash params before verification
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);

        // Sort and build hash data
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

        // Extract order ID from vnp_TxnRef (format: orderId_timestamp)
        $txnRef = $request->vnp_TxnRef ?? '';
        $orderId = explode('_', $txnRef)[0] ?? null;
        $order = $orderId ? Order::find($orderId) : null;

        $vnp_ResponseCode = $request->vnp_ResponseCode ?? '';

        if ($secureHash === $vnp_SecureHash) {
            if ($vnp_ResponseCode == '00') {
                // Payment successful
                if ($order) {
                    // Don't overwrite if already completed
                    if ($order->status === 'pending') {
                        $order->update(['status' => 'pending']); // Đơn pending, chờ xác nhận
                    }
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
                // Payment failed - cancel the order
                if ($order && $order->status === 'pending') {
                    $order->update(['status' => 'cancelled']);
                    
                    // Restore stock and decrement sold_count
                    foreach ($order->items as $item) {
                        if ($item->product) {
                            $item->product->increment('stock', $item->quantity);
                            $item->product->decrement('sold_count', $item->quantity);
                        }
                    }
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
            // Invalid checksum
            return view('checkout.vnpay_return', [
                'success' => false,
                'message' => 'Chữ ký không hợp lệ! Giao dịch bị nghi ngờ giả mạo.',
                'order' => $order,
                'vnp_ResponseCode' => 'INVALID_HASH',
                'vnp_TransactionNo' => '',
                'vnp_Amount' => 0,
                'vnp_BankCode' => '',
                'vnp_PayDate' => '',
            ]);
        }
    }

    public function success($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);
        return view('checkout.success', compact('order'));
    }
}
