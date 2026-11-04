<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Danh sách đơn hàng của khách hàng kèm tab trạng thái
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $status = $request->query('status', 'all');

        $query = Order::with(['items.product'])
            ->where('user_id', $userId)
            ->latest();

        if ($status !== 'all' && in_array($status, ['pending', 'shipping', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        }

        $orders = $query->paginate(10)->withQueryString();

        // Đếm số lượng theo trạng thái để hiển thị badge ở các tab
        $counts = [
            'all' => Order::where('user_id', $userId)->count(),
            'pending' => Order::where('user_id', $userId)->where('status', 'pending')->count(),
            'shipping' => Order::where('user_id', $userId)->where('status', 'shipping')->count(),
            'completed' => Order::where('user_id', $userId)->where('status', 'completed')->count(),
            'cancelled' => Order::where('user_id', $userId)->where('status', 'cancelled')->count(),
        ];

        return view('customer.orders.index', compact('orders', 'status', 'counts'));
    }

    /**
     * Xem chi tiết đơn hàng & theo dõi lộ trình vận chuyển Shopee Tracking
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id() && !Auth::user()->isEmployee()) {
            abort(403, 'Bạn không có quyền xem đơn hàng này.');
        }

        $order->load(['items.product']);

        return view('customer.orders.show', compact('order'));
    }

    /**
     * In hóa đơn bán hàng chuyên nghiệp (Printable Invoice)
     */
    public function printInvoice(Order $order)
    {
        if ($order->user_id !== Auth::id() && !Auth::user()->isEmployee()) {
            abort(403, 'Bạn không có quyền in hóa đơn này.');
        }

        $order->load(['items.product', 'user']);

        return view('orders.invoice', compact('order'));
    }
}

