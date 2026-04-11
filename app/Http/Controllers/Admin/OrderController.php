<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function exportCsv(Request $request)
    {
        $query = Order::with('user')->latest();

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
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

        $columns = ['Ma DH', 'Khach Hang', 'Email', 'So Dien Thoai', 'Dia Chi', 'Tong Tien', 'Trang Thai', 'Phuong Thuc TT', 'Ngay Dat'];

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            // Add BOM for Excel UTF-8
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                $row['Ma DH']  = $order->id;
                $row['Khach Hang'] = $order->customer_name;
                $row['Email'] = $order->customer_email;
                $row['So Dien Thoai'] = $order->customer_phone;
                $row['Dia Chi'] = $order->customer_address;
                $row['Tong Tien'] = $order->total;
                $row['Trang Thai'] = $order->status;
                $row['Phuong Thuc TT'] = $order->payment_method;
                $row['Ngay Dat'] = $order->created_at->format('Y-m-d H:i:s');

                fputcsv($file, array($row['Ma DH'], $row['Khach Hang'], $row['Email'], $row['So Dien Thoai'], $row['Dia Chi'], $row['Tong Tien'], $row['Trang Thai'], $row['Phuong Thuc TT'], $row['Ngay Dat']));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function show(Order $order)
    {
        $order->load('user', 'items.product');
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

        // If order is cancelled, we need to restore stock and decrement sold_count
        if ($request->status === 'cancelled' && $order->status !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                    $item->product->decrement('sold_count', $item->quantity);
                }
            }
        }

        $order->update(['status' => $request->status]);

        ActivityLog::create([
            'description' => "Cập nhật trạng thái đơn hàng thành: " . $request->status,
            'causer_id' => auth()->id(),
            'subject_type' => get_class($order),
            'subject_id' => $order->id,
        ]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Trạng thái đơn hàng đã được cập nhật!');
    }

    public function print(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.print', compact('order'));
    }
}
