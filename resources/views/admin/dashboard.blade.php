@extends('admin.layouts.app')

@section('title', 'Bảng Điều Khiển')
@section('page-title', 'Tổng Quan')
@section('page-description', 'Số liệu thống kê & hoạt động')

@section('content')
<!-- Professional Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Products Card -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 group">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-600 shadow-sm group-hover:bg-red-600 group-hover:text-white transition-all duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <div class="text-right">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">Sản Phẩm</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $totalProducts }}</h3>
            </div>
        </div>
        <div class="mt-5 flex items-center justify-between border-t border-gray-50 pt-3">
            <span class="text-xs font-medium text-gray-500">Mức độ tồn kho</span>
            <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-md">+12% tháng qua</span>
        </div>
    </div>

    <!-- Total Orders Card -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 group border-l-4 border-l-red-600">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 bg-gray-800 rounded-xl flex items-center justify-center text-white shadow-sm group-hover:scale-105 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div class="text-right">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">Đơn Hàng</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</h3>
            </div>
        </div>
        <div class="mt-5 flex items-center justify-between border-t border-gray-50 pt-3">
            <span class="text-xs font-medium text-gray-500">Số lượng giao dịch</span>
            <span class="text-xs font-bold text-red-600">TỔNG TOÀN THỜI GIAN</span>
        </div>
    </div>

    <!-- Pending Orders Card -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 group">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center text-yellow-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="text-right">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">Chờ Xử Lý</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $pendingOrders }}</h3>
            </div>
        </div>
        <div class="mt-5 flex items-center justify-between border-t border-gray-50 pt-3">
            <span class="text-xs font-medium text-gray-500">Cần hành động</span>
            <span class="text-xs font-bold text-yellow-600 bg-yellow-50 px-2 py-0.5 rounded-md animate-pulse">ĐANG CHỜ</span>
        </div>
    </div>

    <!-- Revenue Card -->
    <div class="bg-gray-800 rounded-2xl p-6 shadow-md text-white group hover:-translate-y-1 transition-transform duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-red-600/20 rounded-full -mr-12 -mt-12 blur-2xl"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-6">
                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-widest">Doanh Thu Thuần</p>
                <div class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center text-white text-lg shadow-sm">💰</div>
            </div>
            <h3 class="text-2xl font-bold mb-2">{{ number_format($revenue) }} đ</h3>
            <div class="flex items-center gap-2 mt-4 pt-4 border-t border-white/10">
                <span class="w-1.5 h-1.5 bg-green-400 rounded-full"></span>
                <span class="text-xs font-medium text-gray-400">Đã thống kê</span>
            </div>
        </div>
    </div>
</div>

<!-- Advanced Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Revenue Line Chart -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6 lg:p-8">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Biểu Đồ Doanh Thu</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Dữ liệu hiệu suất trong 6 tháng qua</p>
                </div>
            </div>
            <select class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 text-xs font-medium text-gray-600 focus:ring-1 focus:ring-red-500 outline-none">
                <option>Hàng Tháng ({{ now()->year }})</option>
                <option>Hàng Tuần (Hiện tại)</option>
            </select>
        </div>
        <div class="relative" style="height: 300px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Status Distribution -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 lg:p-8">
        <div class="mb-8 text-center">
            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-widest mb-1">Phân bổ trạng thái</p>
            <h2 class="text-lg font-bold text-gray-800">Tiến Độ Đơn Hàng</h2>
        </div>
        <div class="relative flex items-center justify-center" style="height: 220px;">
            <canvas id="orderStatusChart"></canvas>
        </div>
        <div class="mt-8 space-y-3">
            @php
                $statusConfig = [
                    'pending' => ['bg' => 'bg-yellow-400', 'label' => 'ĐANG CH  Ờ', 'key' => 'pending'],
                    'shipping' => ['bg' => 'bg-blue-500', 'label' => 'ĐANG GIAO', 'key' => 'shipping'],
                    'completed' => ['bg' => 'bg-green-500', 'label' => 'THÀNH CÔNG', 'key' => 'completed'],
                    'cancelled' => ['bg' => 'bg-gray-400', 'label' => 'ĐÃ HỦY', 'key' => 'cancelled'],
                ];
            @endphp
            @foreach($statusConfig as $status)
                <div class="flex items-center justify-between p-2.5 bg-gray-50 rounded-xl border border-gray-100 px-4">
                    <div class="flex items-center gap-3">
                        <span class="w-2.5 h-2.5 rounded-full {{ $status['bg'] }}"></span>
                        <span class="text-xs font-semibold text-gray-600 tracking-wide">{{ $status['label'] }}</span>
                    </div>
                    <span class="text-sm font-bold text-gray-900">{{ $ordersByStatus[$status['key']] ?? 0 }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modern Orders Table -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-gray-50 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Giao Dịch Gần Đây</h2>
            <p class="text-xs text-gray-500 mt-0.5">Dữ liệu đơn hàng mới nhất từ cửa hàng</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-800 text-white text-[11px] font-medium rounded-lg hover:bg-gray-900 transition-colors shadow-sm">Xem Tất Cả</a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Mã ĐH</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Khách Hàng</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Tổng Tiền</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Trạng Thái</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Thời Gian</th>
                    <th class="px-6 py-4 text-center text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Thao Tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($recentOrders as $order)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="font-medium text-gray-900">#{{ $order->id }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($order->customer_name) }}&background=f3f4f6&color=374151" class="w-9 h-9 rounded-lg shadow-sm border border-gray-100">
                            <div>
                                <p class="font-semibold text-gray-800 leading-none mb-1">{{ $order->customer_name }}</p>
                                <p class="text-[11px] text-gray-500">{{ $order->customer_email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="font-semibold text-red-600">{{ number_format($order->total) }} đ</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $badgeStyles = [
                                'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                'shipping' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'completed' => 'bg-green-50 text-green-700 border-green-200',
                                'cancelled' => 'bg-gray-50 text-gray-600 border-gray-200',
                            ];
                            $statusLabels = [
                                'pending' => 'ĐANG CHỜ',
                                'shipping' => 'ĐANG GIAO',
                                'completed' => 'THÀNH CÔNG',
                                'cancelled' => 'ĐÃ HỦY',
                            ];
                        @endphp
                        <span class="inline-flex px-2.5 py-1 rounded-md text-[10px] font-semibold tracking-wide border {{ $badgeStyles[$order->status] }}">
                            {{ $statusLabels[$order->status] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <a href="{{ route('admin.orders.show', $order) }}" class="w-8 h-8 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg flex items-center justify-center transition-colors inline-flex">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400 font-medium text-sm">Chưa có giao dịch nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Top 5 Products -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-50 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Top 5 Sản Phẩm Bán Chạy</h2>
                <p class="text-xs text-gray-500 mt-0.5">Dựa trên số lượng đã bán</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="px-3 py-1.5 bg-gray-50 text-gray-600 text-[11px] font-medium rounded-lg hover:bg-gray-100 transition-colors">Xem kho</a>
        </div>
        <div class="p-0">
            <table class="w-full">
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($topProducts as $top)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                                    <img src="{{ asset('storage/' . $top->image) }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <a href="{{ route('admin.products.edit', $top) }}" class="font-semibold text-gray-800 hover:text-red-600 line-clamp-1" title="{{ $top->name }}">{{ $top->name }}</a>
                                    <p class="text-[11px] text-gray-500 mt-0.5 font-mono">{{ number_format($top->price) }}đ</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-right">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold text-green-700 bg-green-50 border border-green-100">
                                {{ $top->sold_count }} <span class="text-[10px] text-green-600 font-normal">đã bán</span>
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="px-6 py-8 text-center text-gray-400 font-medium text-xs">Chưa có dữ liệu</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Activity Log -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-50 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Hoạt Động Gần Đây</h2>
                <p class="text-xs text-gray-500 mt-0.5">Nhật ký thao tác hệ thống</p>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-200 before:to-transparent">
                @forelse($recentActivities as $activity)
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-red-100 text-red-600 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 absolute left-0 md:left-1/2 -translate-x-1/2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] ml-12 md:ml-0 md:group-even:pr-6 md:group-odd:pl-6">
                        <div class="p-3 bg-white border border-gray-100 rounded-xl shadow-sm">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-bold text-gray-900">{{ $activity->causer ? $activity->causer->name : 'Hệ thống' }}</span>
                                <time class="text-[10px] text-gray-400">{{ $activity->created_at->diffForHumans() }}</time>
                            </div>
                            <p class="text-xs text-gray-600 leading-snug">{{ $activity->description }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-6">
                    <p class="text-sm text-gray-400 font-medium">Chưa có hoạt động nào được ghi nhận.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const chartGradient = revenueCtx.createLinearGradient(0, 0, 0, 400);
    chartGradient.addColorStop(0, 'rgba(220, 38, 38, 0.15)');
    chartGradient.addColorStop(1, 'rgba(220, 38, 38, 0)');

    const monthlyData = @json($monthlyRevenue);
    const labels = [];
    const revenueValues = [];
    for (let i = 5; i >= 0; i--) {
        const d = new Date();
        d.setMonth(d.getMonth() - i);
        const key = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0');
        labels.push(new Intl.DateTimeFormat('vi-VN', { month: 'short' }).format(d));
        const found = monthlyData.find(m => m.month === key);
        revenueValues.push(found ? parseFloat(found.revenue) : 0);
    }

    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'DOANH THU',
                data: revenueValues,
                borderColor: '#DC2626',
                backgroundColor: chartGradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#DC2626',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: '#DC2626',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1f2937',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: { size: 12, family: 'Inter' },
                    bodyFont: { size: 14, weight: 'bold', family: 'Inter' },
                    displayColors: false,
                    callbacks: {
                        label: function(c) { return new Intl.NumberFormat('vi-VN').format(c.parsed.y) + ' đ'; }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
                    ticks: {
                        font: { size: 11 },
                        color: '#6b7280',
                        callback: v => v >= 1000000 ? (v/1000000).toFixed(1) + 'M' : (v/1000).toFixed(0) + 'K'
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 }, color: '#4b5563' }
                }
            }
        }
    });

    const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
    const statusData = @json($ordersByStatus);
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['ĐANG CHỜ', 'ĐANG GIAO', 'THÀNH CÔNG', 'ĐÃ HỦY'],
            datasets: [{
                data: [
                    statusData.pending || 0,
                    statusData.shipping || 0,
                    statusData.completed || 0,
                    statusData.cancelled || 0
                ],
                backgroundColor: ['#facc15', '#3b82f6', '#22c55e', '#9ca3af'],
                hoverOffset: 8,
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1f2937',
                    padding: 10,
                    cornerRadius: 8,
                }
            }
        }
    });
});
</script>
@endpush
