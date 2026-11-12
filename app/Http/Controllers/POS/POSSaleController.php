<?php

namespace App\Http\Controllers\POS;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\POSService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class POSSaleController extends Controller
{
    public function __construct(protected POSService $posService)
    {
        $this->middleware('auth');
    }

    /**
     * Tạo đơn bán hàng tại quầy.
     */
    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required|integer|exists:stores,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.size' => 'nullable|string',
            'items.*.color' => 'nullable|string',
            'payment.method' => 'required|in:cash,card,bank_transfer,ewallet',
            'payment.received_amount' => 'nullable|numeric|min:0',
            'payment.reference' => 'nullable|string',
            'payment.note' => 'nullable|string',
            'customer.name' => 'nullable|string',
            'customer.phone' => 'nullable|string',
            'customer.address' => 'nullable|string',
            'customer.user_id' => 'nullable|integer|exists:users,id',
            'coupon_code' => 'nullable|string',
        ]);

        /** @var \App\Models\User $staff */
        $staff = Auth::user();

        try {
            $order = $this->posService->createSale(
                staff: $staff,
                storeId: (int) $request->input('store_id'),
                items: $request->input('items'),
                paymentData: $request->input('payment'),
                customerData: $request->input('customer', []),
                couponCode: $request->input('coupon_code')
            );

            return response()->json([
                'success' => true,
                'message' => 'Tạo đơn hàng thành công!',
                'order_id' => $order->id,
                'order_code' => $order->code,
                'total' => $order->total,
                'receipt_url' => route('pos.sales.receipt', $order->id),
            ]);
        } catch (BusinessException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra trong quá trình thanh toán: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Xem chi tiết đơn hàng POS.
     */
    public function show(Order $order)
    {
        $this->authorize('viewPos', $order);
        $order->load(['items.product', 'store', 'staff', 'payments', 'shiftSession']);

        return response()->json([
            'success' => true,
            'order' => $order,
        ]);
    }

    /**
     * Màn hình / Modal in hóa đơn nhiệt POS (80mm/58mm).
     */
    public function receipt(Order $order)
    {
        $order->load(['items.product', 'store', 'staff', 'payments']);
        return view('pos.receipt', compact('order'));
    }

    /**
     * Xử lý hoàn tiền / đổi trả đơn hàng POS.
     */
    public function refund(Request $request, Order $order)
    {
        $this->authorize('refund', $order);

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.order_item_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
            'refund_method' => 'required|in:cash,bank_transfer',
        ]);

        try {
            $updatedOrder = $this->posService->refund(
                order: $order,
                actor: Auth::user(),
                returnItems: $request->input('items'),
                reason: $request->input('reason'),
                refundMethod: $request->input('refund_method')
            );

            return response()->json([
                'success' => true,
                'message' => 'Xử lý trả hàng và hoàn tiền thành công!',
                'order' => $updatedOrder,
            ]);
        } catch (BusinessException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            report($e);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

