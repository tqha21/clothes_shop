<?php

namespace App\Services;

use App\Exceptions\BusinessException;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ShiftSession;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Service xử lý toàn bộ nghiệp vụ bán hàng tại quầy (POS).
 * Đảm bảo:
 * - Nhân viên phải có ca mở tại cửa hàng (hoặc admin/manager)
 * - Kiểm tra giá và tồn kho backend (không tin dữ liệu client)
 * - Khóa dòng tồn kho và trừ kho đúng cửa hàng (BR-04)
 * - Ghi nhận thanh toán và cập nhật số dư tiền mặt của phiên ca (BR-06)
 */
class POSService
{
    public function __construct(
        protected StockService $stock,
        protected OrderService $orderService
    ) {}

    /**
     * Tạo đơn bán hàng tại quầy (POS Sale).
     *
     * @param User $staff
     * @param int $storeId
     * @param array $items  [['product_id' => int, 'quantity' => int, 'size' => ?string, 'color' => ?string]]
     * @param array $paymentData ['method' => string, 'received_amount' => float, 'reference' => ?string, 'note' => ?string]
     * @param array $customerData ['name' => ?string, 'phone' => ?string, 'address' => ?string, 'user_id' => ?int]
     * @param string|null $couponCode
     * @return Order
     */
    public function createSale(
        User $staff,
        int $storeId,
        array $items,
        array $paymentData,
        array $customerData = [],
        ?string $couponCode = null
    ): Order {
        if (empty($items)) {
            throw new BusinessException('Đơn hàng POS phải có ít nhất 1 sản phẩm.');
        }

        // 1. Kiểm tra quyền cửa hàng
        if (!$staff->canAccessStore($storeId)) {
            throw new BusinessException('Bạn không được phân quyền bán hàng tại cửa hàng này.');
        }

        // 2. Kiểm tra phiên ca làm việc
        $session = $staff->currentShiftSession();
        if (!$session && $staff->role === 'staff') {
            throw new BusinessException('Bạn phải mở ca làm việc trước khi thực hiện bán hàng tại quầy.');
        }
        // Nếu admin/manager không mở ca, có thể tìm phiên ca mở tại cửa hàng đó hoặc null
        $sessionId = $session?->id;

        return DB::transaction(function () use ($staff, $storeId, $sessionId, $session, $items, $paymentData, $customerData, $couponCode) {
            // A. Đọc và khóa sản phẩm, kiểm tra tồn kho tại cửa hàng
            $subtotal = 0;
            $orderItemsData = [];
            $quantitiesToDeduct = [];

            foreach ($items as $item) {
                $productId = (int) ($item['product_id'] ?? 0);
                $qty = (int) ($item['quantity'] ?? 0);
                if ($qty <= 0) {
                    throw new BusinessException('Số lượng sản phẩm phải lớn hơn 0.');
                }

                $product = Product::whereKey($productId)->lockForUpdate()->first();
                if (!$product) {
                    throw new BusinessException("Sản phẩm #{$productId} không tồn tại.");
                }

                // Kiểm tra tồn tại cửa hàng
                $available = $this->stock->availableAt($storeId, $productId);
                if ($available < $qty) {
                    $storeName = Store::whereKey($storeId)->value('name');
                    throw new BusinessException("Sản phẩm \"{$product->name}\" tại {$storeName} chỉ còn {$available} sản phẩm, không đủ {$qty} sản phẩm.");
                }

                $unitPrice = $product->effective_price;
                $lineTotal = $unitPrice * $qty;
                $subtotal += $lineTotal;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $qty,
                    'price' => $unitPrice,
                    'original_price' => $product->price,
                    'size' => $item['size'] ?? null,
                    'color' => $item['color'] ?? null,
                ];

                $quantitiesToDeduct[$productId] = ($quantitiesToDeduct[$productId] ?? 0) + $qty;
            }

            // B. Áp dụng mã giảm giá (nếu có)
            $discountAmount = 0;
            $appliedCoupon = null;
            if ($couponCode) {
                $coupon = Coupon::where('code', $couponCode)->first();
                if ($coupon && $coupon->isValid($subtotal)) {
                    $discountAmount = $coupon->calculateDiscount($subtotal);
                    $appliedCoupon = $coupon;
                    $coupon->increment('used_count');
                }
            }

            $total = max(0, $subtotal - $discountAmount);

            // C. Khởi tạo đơn hàng POS
            $order = Order::create([
                'channel' => Order::CHANNEL_POS,
                'store_id' => $storeId,
                'staff_id' => $staff->id,
                'shift_session_id' => $sessionId,
                'user_id' => $customerData['user_id'] ?? null,
                'customer_name' => $customerData['name'] ?? 'Khách lẻ tại quầy',
                'customer_phone' => $customerData['phone'] ?? '',
                'customer_address' => $customerData['address'] ?? 'Tại quầy',
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'coupon_id' => $appliedCoupon?->id,
                'total' => $total,
                'status' => 'completed',
                'completed_at' => now(),
                'payment_method' => $paymentData['method'] ?? 'cash',
                'payment_status' => 'paid',
                'shipping_fee' => 0,
                'note' => $paymentData['note'] ?? null,
            ]);

            $this->orderService->assignCode($order);

            // D. Tạo order items
            foreach ($orderItemsData as $itemData) {
                $itemData['order_id'] = $order->id;
                OrderItem::create($itemData);

                // Tăng lượt bán sold_count
                Product::whereKey($itemData['product_id'])->increment('sold_count', $itemData['quantity']);
            }

            // E. Trừ kho đúng cửa hàng qua StockService
            $this->stock->deductForPosOrder($order, $quantitiesToDeduct, $staff->id);

            // F. Tạo bản ghi thanh toán (Payment)
            $method = strtolower($paymentData['method'] ?? 'cash');
            $receivedAmount = isset($paymentData['received_amount']) ? (float) $paymentData['received_amount'] : $total;
            $changeAmount = ($method === 'cash') ? max(0, $receivedAmount - $total) : 0;

            Payment::create([
                'order_id' => $order->id,
                'shift_session_id' => $sessionId,
                'store_id' => $storeId,
                'type' => 'payment',
                'method' => $method,
                'amount' => $total,
                'received_amount' => $receivedAmount,
                'change_amount' => $changeAmount,
                'status' => 'success',
                'reference' => $paymentData['reference'] ?? null,
                'note' => $paymentData['note'] ?? null,
                'processed_by' => $staff->id,
                'paid_at' => now(),
            ]);

            // G. Cập nhật số liệu phiên ca (nếu có ca mở)
            if ($session) {
                if ($method === 'cash') {
                    $session->cash_sales = (float) $session->cash_sales + $total;
                } else {
                    $session->non_cash_sales = (float) $session->non_cash_sales + $total;
                }
                $session->orders_count = (int) $session->orders_count + 1;
                $session->save();
            }

            // H. Tích điểm cho khách hàng thành viên nếu có
            if (!empty($customerData['user_id'])) {
                $customer = User::find($customerData['user_id']);
                if ($customer) {
                    $points = floor($total / 1000);
                    $customer->increment('points', $points);
                    $customer->updateMembershipTier();
                }
            }

            return $order->fresh(['items', 'store', 'staff', 'payments']);
        });
    }

    /**
     * Xử lý đổi trả / hoàn tiền đơn POS (Refund).
     */
    public function refund(Order $order, User $actor, array $returnItems, string $reason, string $refundMethod = 'cash'): Order
    {
        if (!$order->isPos()) {
            throw new BusinessException('Chỉ hỗ trợ hoàn trả tại quầy cho đơn hàng POS.');
        }

        return DB::transaction(function () use ($order, $actor, $returnItems, $reason, $refundMethod) {
            $locked = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            $totalRefundAmount = 0;

            foreach ($returnItems as $itemInput) {
                $itemId = (int) ($itemInput['order_item_id'] ?? 0);
                $qty = (int) ($itemInput['quantity'] ?? 0);
                if ($qty <= 0) continue;

                $item = OrderItem::whereKey($itemId)->where('order_id', $locked->id)->lockForUpdate()->first();
                if (!$item) {
                    throw new BusinessException("Mục hàng #{$itemId} không thuộc đơn hàng này.");
                }

                if ($qty > $item->refundable_quantity) {
                    throw new BusinessException("Sản phẩm \"{$item->display_name}\" chỉ còn hoàn trả tối đa {$item->refundable_quantity}.");
                }

                $item->refunded_quantity += $qty;
                $item->save();

                $refundAmount = $item->price * $qty;
                $totalRefundAmount += $refundAmount;

                // Nhập lại kho cửa hàng
                $this->stock->returnForOrder($locked, $item->product_id, $qty, $actor->id, "Khách trả hàng ({$reason})");

                // Giảm lượt bán
                Product::whereKey($item->product_id)->where('sold_count', '>=', $qty)->decrement('sold_count', $qty);
            }

            if ($totalRefundAmount <= 0) {
                throw new BusinessException('Vui lòng chọn ít nhất một sản phẩm với số lượng hợp lệ để hoàn trả.');
            }

            // Ghi nhận Payment refund
            $session = $actor->currentShiftSession();
            Payment::create([
                'order_id' => $locked->id,
                'shift_session_id' => $session?->id,
                'store_id' => $locked->store_id,
                'type' => 'refund',
                'method' => $refundMethod,
                'amount' => $totalRefundAmount,
                'status' => 'success',
                'note' => $reason,
                'processed_by' => $actor->id,
                'paid_at' => now(),
            ]);

            // Cập nhật số tiền đã hoàn trên đơn
            $locked->refunded_amount = (float) $locked->refunded_amount + $totalRefundAmount;
            if ($locked->refunded_amount >= $locked->total) {
                $locked->status = 'refunded';
                $locked->payment_status = 'refunded';
            } else {
                $locked->payment_status = 'partially_refunded';
            }
            $locked->save();

            // Cập nhật phiên ca
            if ($session && $refundMethod === 'cash') {
                $session->cash_refunds = (float) $session->cash_refunds + $totalRefundAmount;
                $session->save();
            }

            $this->orderService->log($locked, $actor, "Hoàn trả hàng: {$reason}, số tiền: " . number_format($totalRefundAmount) . 'đ');

            return $locked->fresh(['items', 'payments']);
        });
    }
}
