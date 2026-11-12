<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {
    }

    public function index(Request $request)
    {
        $query = Order::with(['user', 'store', 'staff'])->latest();

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('channel') && $request->channel != '') {
            $query->where('channel', $request->channel);
        }

        $orders = $query->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function exportCsv(Request $request)
    {
        $query = Order::with(['user', 'store', 'staff'])->latest();

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('channel') && $request->channel != '') {
            $query->where('channel', $request->channel);
        }

        $orders = $query->get();

        $filename = "orders_export_" . date('Y-m-d_H-i-s') . ".csv";
        $headers = array(
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['Ma DH', 'Kenh', 'Cua Hang', 'Khach Hang', 'So Dien Thoai', 'Dia Chi', 'Tong Tien', 'Trang Thai', 'Phuong Thuc TT', 'Ngay Dat'];

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            // Add BOM for Excel UTF-8
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->display_code,
                    strtoupper($order->channel),
                    $order->store->name ?? 'Online',
                    $order->customer_name,
                    $order->customer_phone,
                    $order->customer_address,
                    $order->total,
                    $order->status_label,
                    $order->payment_method,
                    $order->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'store', 'staff', 'payments', 'shiftSession']);
        $histories = ActivityLog::where('subject_type', get_class($order))
                                ->where('subject_id', $order->id)
                                ->latest()
                                ->get();
        return view('admin.orders.show', compact('order', 'histories'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,shipping,completed,cancelled',
        ]);

        try {
            $this->orderService->updateStatus($order, $request->status, Auth::user());

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Trạng thái đơn hàng đã được cập nhật thành công!');
        } catch (BusinessException $e) {
            return redirect()->route('admin.orders.show', $order)->with('error', $e->getMessage());
        } catch (\Exception $e) {
            report($e);
            return redirect()->route('admin.orders.show', $order)->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function print(Order $order)
    {
        $order->load(['user', 'items.product', 'store', 'staff', 'payments']);
        return view('orders.invoice', compact('order'));
    }
}
