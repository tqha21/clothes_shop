<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $revenue = Order::where('status', 'completed')->sum('total');
        $recentOrders = Order::with('user')->latest()->take(10)->get();

        // Doanh thu 6 tháng gần nhất
        $monthlyRevenue = Order::where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total) as revenue")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Đơn hàng theo trạng thái
        $ordersByStatus = Order::selectRaw("status, COUNT(*) as count")
            ->groupBy('status')
            ->pluck('count', 'status');

        $topProducts = Product::orderBy('sold_count', 'desc')->take(5)->get();
        $recentActivities = ActivityLog::with('causer')->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'pendingOrders',
            'revenue',
            'recentOrders',
            'monthlyRevenue',
            'ordersByStatus',
            'topProducts',
            'recentActivities'
        ));
    }
}
