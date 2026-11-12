<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Store;
use App\Models\User;
use App\Services\SalesReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesReportController extends Controller
{
    public function __construct(protected SalesReportService $reportService)
    {
        $this->middleware('auth');
    }

    /**
     * Báo cáo doanh số đa kênh và doanh thu theo nhân viên / ca / cửa hàng.
     */
    public function index(Request $request)
    {
        /** @var User $viewer */
        $viewer = Auth::user();

        // 1. Khoảng thời gian mặc định: 30 ngày gần nhất
        $range = $request->input('range', 'month');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($range === 'today') {
            $startDate = now()->toDateString();
            $endDate = now()->toDateString();
        } elseif ($range === 'week') {
            $startDate = now()->startOfWeek()->toDateString();
            $endDate = now()->endOfWeek()->toDateString();
        } elseif ($range === 'month' && !$startDate) {
            $startDate = now()->startOfMonth()->toDateString();
            $endDate = now()->endOfMonth()->toDateString();
        }

        $filters = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'store_id' => $request->input('store_id'),
            'staff_id' => $request->input('staff_id'),
            'channel' => $request->input('channel'),
        ];

        // 2. Lấy số liệu từ SalesReportService
        $overview = $this->reportService->getOverview($filters, $viewer);
        $staffSales = $this->reportService->getSalesByStaff($filters, $viewer);
        $storeSales = $this->reportService->getSalesByStore($filters, $viewer);
        $dailyTrends = $this->reportService->getDailyTrend($filters, $viewer, 14);

        // 3. Danh sách gần đây các đơn hàng phát sinh doanh thu
        $recentOrders = Order::query()
            ->revenueRecognized()
            ->with(['store', 'staff', 'items'])
            ->when(!empty($filters['store_id']), fn ($q) => $q->where('store_id', $filters['store_id']))
            ->when(!empty($filters['staff_id']), fn ($q) => $q->where('staff_id', $filters['staff_id']))
            ->when(!empty($filters['channel']), fn ($q) => $q->where('channel', $filters['channel']))
            ->when($startDate, fn ($q) => $q->where('completed_at', '>=', Carbon::parse($startDate)->startOfDay()))
            ->when($endDate, fn ($q) => $q->where('completed_at', '<=', Carbon::parse($endDate)->endOfDay()))
            ->latest('completed_at')
            ->take(15)
            ->get();

        // Danh sách stores & staff cho dropdown lọc
        $stores = Store::where('status', 'active')->get();
        $employees = User::whereIn('role', ['admin', 'manager', 'staff'])->where('status', 'active')->get();

        return view('admin.reports.sales', compact(
            'overview', 'staffSales', 'storeSales', 'dailyTrends',
            'recentOrders', 'stores', 'employees', 'filters', 'range'
        ));
    }

    /**
     * Xuất file CSV báo cáo doanh thu.
     */
    public function exportCsv(Request $request)
    {
        $viewer = Auth::user();
        $filters = [
            'start_date' => $request->input('start_date', now()->startOfMonth()->toDateString()),
            'end_date' => $request->input('end_date', now()->endOfMonth()->toDateString()),
            'store_id' => $request->input('store_id'),
            'staff_id' => $request->input('staff_id'),
            'channel' => $request->input('channel'),
        ];

        $orders = Order::query()
            ->revenueRecognized()
            ->with(['store', 'staff', 'payments'])
            ->when(!empty($filters['store_id']), fn ($q) => $q->where('store_id', $filters['store_id']))
            ->when(!empty($filters['staff_id']), fn ($q) => $q->where('staff_id', $filters['staff_id']))
            ->when(!empty($filters['channel']), fn ($q) => $q->where('channel', $filters['channel']))
            ->when($filters['start_date'], fn ($q) => $q->where('completed_at', '>=', Carbon::parse($filters['start_date'])->startOfDay()))
            ->when($filters['end_date'], fn ($q) => $q->where('completed_at', '<=', Carbon::parse($filters['end_date'])->endOfDay()))
            ->orderBy('completed_at', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="bao_cao_doanh_so_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, ['Mã đơn', 'Kênh', 'Cửa hàng', 'Nhân viên', 'Khách hàng', 'Tổng tiền', 'Đã hoàn', 'Doanh thu thuần', 'Hình thức TT', 'Thời gian hoàn tất']);

            foreach ($orders as $o) {
                fputcsv($handle, [
                    $o->display_code,
                    strtoupper($o->channel),
                    $o->store->name ?? 'Online',
                    $o->staff->name ?? 'N/A',
                    $o->customer_name,
                    $o->total,
                    $o->refunded_amount,
                    $o->net_total,
                    $o->payment_method,
                    $o->completed_at ? $o->completed_at->format('d/m/Y H:i') : '',
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}

