<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Service tính toán và xuất báo cáo doanh số đa kênh, nhân viên, ca và cửa hàng (BR-07).
 * Nguyên tắc: Doanh thu thuần = Doanh thu hoàn thành - Hoàn tiền.
 * Chỉ tính các đơn đã hoàn thành/ghi nhận doanh thu.
 */
class SalesReportService
{
    /**
     * Lấy tổng quan doanh số theo bộ lọc.
     *
     * @param array $filters ['start_date', 'end_date', 'store_id', 'staff_id', 'channel', 'shift_session_id']
     * @param User|null $viewer
     * @return array
     */
    public function getOverview(array $filters = [], ?User $viewer = null): array
    {
        $query = $this->buildBaseQuery($filters, $viewer);

        $orders = (clone $query)->get();

        $grossRevenue = 0;
        $refundedAmount = 0;
        $posRevenue = 0;
        $onlineRevenue = 0;
        $posOrdersCount = 0;
        $onlineOrdersCount = 0;

        foreach ($orders as $order) {
            $gross = (float) $order->total;
            $refund = (float) $order->refunded_amount;
            $net = max(0, $gross - $refund);

            $grossRevenue += $gross;
            $refundedAmount += $refund;

            if ($order->channel === Order::CHANNEL_POS) {
                $posRevenue += $net;
                $posOrdersCount++;
            } else {
                $onlineRevenue += $net;
                $onlineOrdersCount++;
            }
        }

        $netRevenue = max(0, $grossRevenue - $refundedAmount);
        $totalOrders = $orders->count();
        $averageOrderValue = $totalOrders > 0 ? round($netRevenue / $totalOrders) : 0;

        return [
            'gross_revenue' => $grossRevenue,
            'refunded_amount' => $refundedAmount,
            'net_revenue' => $netRevenue,
            'total_orders' => $totalOrders,
            'pos_revenue' => $posRevenue,
            'online_revenue' => $onlineRevenue,
            'pos_orders_count' => $posOrdersCount,
            'online_orders_count' => $onlineOrdersCount,
            'average_order_value' => $averageOrderValue,
        ];
    }

    /**
     * Thống kê theo từng nhân viên (Staff Sales).
     */
    public function getSalesByStaff(array $filters = [], ?User $viewer = null): array
    {
        $query = $this->buildBaseQuery($filters, $viewer)
            ->whereNotNull('staff_id');

        $staffSales = (clone $query)
            ->selectRaw('staff_id, COUNT(id) as orders_count, SUM(total) as gross_sales, SUM(refunded_amount) as total_refunds')
            ->groupBy('staff_id')
            ->with('staff:id,name,employee_code,store_id')
            ->get()
            ->map(function ($row) {
                $net = max(0, (float) $row->gross_sales - (float) $row->total_refunds);
                return [
                    'staff_id' => $row->staff_id,
                    'staff_name' => $row->staff->name ?? 'N/A',
                    'employee_code' => $row->staff->employee_code ?? ('NV' . $row->staff_id),
                    'orders_count' => (int) $row->orders_count,
                    'gross_sales' => (float) $row->gross_sales,
                    'refunded_amount' => (float) $row->total_refunds,
                    'net_sales' => $net,
                ];
            })
            ->sortByDesc('net_sales')
            ->values()
            ->all();

        return $staffSales;
    }

    /**
     * Thống kê theo từng cửa hàng (Store Sales).
     */
    public function getSalesByStore(array $filters = [], ?User $viewer = null): array
    {
        $query = $this->buildBaseQuery($filters, $viewer)
            ->whereNotNull('store_id');

        return (clone $query)
            ->selectRaw('store_id, COUNT(id) as orders_count, SUM(total) as gross_sales, SUM(refunded_amount) as total_refunds')
            ->groupBy('store_id')
            ->with('store:id,name,code,city')
            ->get()
            ->map(function ($row) {
                $net = max(0, (float) $row->gross_sales - (float) $row->total_refunds);
                return [
                    'store_id' => $row->store_id,
                    'store_name' => $row->store->name ?? 'N/A',
                    'store_code' => $row->store->code ?? ('CH' . $row->store_id),
                    'city' => $row->store->city ?? '',
                    'orders_count' => (int) $row->orders_count,
                    'gross_sales' => (float) $row->gross_sales,
                    'net_sales' => $net,
                ];
            })
            ->sortByDesc('net_sales')
            ->values()
            ->all();
    }

    /**
     * Biểu đồ doanh thu theo ngày.
     */
    public function getDailyTrend(array $filters = [], ?User $viewer = null, int $days = 14): array
    {
        $startDate = !empty($filters['start_date']) ? Carbon::parse($filters['start_date']) : now()->subDays($days - 1)->startOfDay();
        $endDate = !empty($filters['end_date']) ? Carbon::parse($filters['end_date'])->endOfDay() : now()->endOfDay();

        $filters['start_date'] = $startDate->toDateString();
        $filters['end_date'] = $endDate->toDateString();

        $query = $this->buildBaseQuery($filters, $viewer);

        $driver = DB::getDriverName();
        $dateExpr = $driver === 'sqlite' ? "strftime('%Y-%m-%d', completed_at)" : "DATE(completed_at)";

        $rawStats = (clone $query)
            ->selectRaw("{$dateExpr} as date, channel, SUM(total - refunded_amount) as net_amount, COUNT(id) as cnt")
            ->groupBy(DB::raw($dateExpr), 'channel')
            ->get();

        $timeline = [];
        $current = $startDate->copy();
        while ($current <= $endDate) {
            $key = $current->toDateString();
            $timeline[$key] = [
                'date' => $key,
                'label' => $current->format('d/m'),
                'pos' => 0,
                'online' => 0,
                'total' => 0,
            ];
            $current->addDay();
        }

        foreach ($rawStats as $row) {
            $d = $row->date;
            if (isset($timeline[$d])) {
                $val = max(0, (float) $row->net_amount);
                if ($row->channel === Order::CHANNEL_POS) {
                    $timeline[$d]['pos'] += $val;
                } else {
                    $timeline[$d]['online'] += $val;
                }
                $timeline[$d]['total'] += $val;
            }
        }

        return array_values($timeline);
    }

    /**
     * Xây dựng base query chuẩn quy tắc ghi nhận doanh thu (BR-07) và phạm vi quyền (BR-03).
     */
    protected function buildBaseQuery(array $filters, ?User $viewer)
    {
        $query = Order::query()
            ->revenueRecognized();

        // 1. Phân quyền theo cửa hàng của viewer
        if ($viewer && !$viewer->isAdmin()) {
            if ($viewer->store_id) {
                $query->where('orders.store_id', $viewer->store_id);
            } else {
                // Nhân viên không thuộc cửa hàng nào -> không xem được doanh số cửa hàng
                $query->where('orders.staff_id', $viewer->id);
            }
        }

        // 2. Bộ lọc theo store
        if (!empty($filters['store_id'])) {
            $query->where('orders.store_id', (int) $filters['store_id']);
        }

        // 3. Bộ lọc theo nhân viên
        if (!empty($filters['staff_id'])) {
            $query->where('orders.staff_id', (int) $filters['staff_id']);
        }

        // 4. Bộ lọc theo kênh bán
        if (!empty($filters['channel'])) {
            $query->where('orders.channel', $filters['channel']);
        }

        // 5. Bộ lọc theo ca làm việc (shift_session)
        if (!empty($filters['shift_session_id'])) {
            $query->where('orders.shift_session_id', (int) $filters['shift_session_id']);
        }

        // 6. Bộ lọc khoảng ngày
        if (!empty($filters['start_date'])) {
            $query->where('orders.completed_at', '>=', Carbon::parse($filters['start_date'])->startOfDay());
        }
        if (!empty($filters['end_date'])) {
            $query->where('orders.completed_at', '<=', Carbon::parse($filters['end_date'])->endOfDay());
        }

        return $query;
    }
}

