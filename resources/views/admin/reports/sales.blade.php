@extends('admin.layouts.app')

@section('page-title', 'Báo Cáo Doanh Số Đa Kênh & Nhân Viên')
@section('page-description', 'Theo dõi doanh thu Online & POS, hiệu suất bán hàng của nhân viên và từng chi nhánh')

@section('content')
<div class="space-y-6">

    <!-- Filters & Actions Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-3">
        <form method="GET" action="{{ route('admin.reports.sales') }}" class="flex flex-wrap items-center justify-between gap-3 text-xs">
            <div class="flex flex-wrap items-center gap-2">
                <!-- Preset ranges -->
                <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl">
                    <a href="{{ route('admin.reports.sales', array_merge(request()->except('range','start_date','end_date'), ['range' => 'today'])) }}"
                       class="px-3 py-1.5 rounded-lg font-bold transition {{ ($range ?? '') === 'today' ? 'bg-white text-rose-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                        Hôm nay
                    </a>
                    <a href="{{ route('admin.reports.sales', array_merge(request()->except('range','start_date','end_date'), ['range' => 'week'])) }}"
                       class="px-3 py-1.5 rounded-lg font-bold transition {{ ($range ?? '') === 'week' ? 'bg-white text-rose-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                        Tuần này
                    </a>
                    <a href="{{ route('admin.reports.sales', array_merge(request()->except('range','start_date','end_date'), ['range' => 'month'])) }}"
                       class="px-3 py-1.5 rounded-lg font-bold transition {{ ($range ?? '') === 'month' ? 'bg-white text-rose-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                        Tháng này
                    </a>
                </div>

                <!-- Custom Dates -->
                <div class="flex items-center gap-1.5">
                    <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" class="px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg outline-none font-medium">
                    <span class="text-slate-400">đến</span>
                    <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}" class="px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg outline-none font-medium">
                </div>

                <!-- Store Filter -->
                <select name="store_id" class="px-3 py-1.5 bg-slate-50 border border-slate-300 rounded-lg outline-none font-semibold">
                    <option value="">-- Tất cả cửa hàng --</option>
                    @foreach($stores as $st)
                    <option value="{{ $st->id }}" {{ ($filters['store_id'] ?? '') == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                    @endforeach
                </select>

                <!-- Staff Filter -->
                <select name="staff_id" class="px-3 py-1.5 bg-slate-50 border border-slate-300 rounded-lg outline-none font-semibold">
                    <option value="">-- Tất cả nhân viên --</option>
                    @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" {{ ($filters['staff_id'] ?? '') == $emp->id ? 'selected' : '' }}>{{ $emp->name }} ({{ $emp->employee_code ?? 'NV'.$emp->id }})</option>
                    @endforeach
                </select>

                <!-- Channel Filter -->
                <select name="channel" class="px-3 py-1.5 bg-slate-50 border border-slate-300 rounded-lg outline-none font-semibold">
                    <option value="">-- Kênh: Tất cả --</option>
                    <option value="pos" {{ ($filters['channel'] ?? '') === 'pos' ? 'selected' : '' }}>Chỉ POS (Tại quầy)</option>
                    <option value="online" {{ ($filters['channel'] ?? '') === 'online' ? 'selected' : '' }}>Chỉ Online</option>
                </select>

                <button type="submit" class="px-4 py-1.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-lg transition shadow-sm">
                    Lọc dữ liệu
                </button>
            </div>

            <!-- Export CSV -->
            <a href="{{ route('admin.reports.sales.export', request()->all()) }}" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg transition shadow-sm flex items-center gap-1.5 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Xuất Excel / CSV
            </a>
        </form>
    </div>

    <!-- Overview KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Doanh thu thuần -->
        <div class="bg-gradient-to-br from-rose-600 to-rose-700 text-white p-5 rounded-2xl shadow-lg shadow-rose-600/20 relative overflow-hidden">
            <div class="text-xs font-bold uppercase tracking-wider text-rose-100">Doanh Thu Thuần</div>
            <div class="text-2xl font-black mt-2 font-mono">{{ number_format($overview['net_revenue']) }}đ</div>
            <div class="text-[11px] text-rose-200 mt-2 flex items-center justify-between">
                <span>Tổng gộp: {{ number_format($overview['gross_revenue']) }}đ</span>
                @if($overview['refunded_amount'] > 0)
                <span>-{{ number_format($overview['refunded_amount']) }}đ hoàn</span>
                @endif
            </div>
        </div>

        <!-- POS vs Online -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Kênh Bán Hàng Tại Quầy (POS)</div>
                <div class="text-2xl font-black mt-2 text-slate-800 font-mono">{{ number_format($overview['pos_revenue']) }}đ</div>
            </div>
            <div class="text-xs font-semibold text-slate-500 mt-3 pt-2 border-t border-slate-100 flex justify-between">
                <span>{{ $overview['pos_orders_count'] }} đơn hoàn thành</span>
                <span class="text-rose-600 font-bold">
                    {{ $overview['net_revenue'] > 0 ? round(($overview['pos_revenue'] / $overview['net_revenue']) * 100) : 0 }}%
                </span>
            </div>
        </div>

        <!-- Kênh Online -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Kênh Bán Hàng Online</div>
                <div class="text-2xl font-black mt-2 text-indigo-600 font-mono">{{ number_format($overview['online_revenue']) }}đ</div>
            </div>
            <div class="text-xs font-semibold text-slate-500 mt-3 pt-2 border-t border-slate-100 flex justify-between">
                <span>{{ $overview['online_orders_count'] }} đơn hoàn thành</span>
                <span class="text-indigo-600 font-bold">
                    {{ $overview['net_revenue'] > 0 ? round(($overview['online_revenue'] / $overview['net_revenue']) * 100) : 0 }}%
                </span>
            </div>
        </div>

        <!-- Đơn hàng & Giá trị TB -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Tổng Đơn & Giá Trị TB</div>
                <div class="text-2xl font-black mt-2 text-slate-800 font-mono">{{ $overview['total_orders'] }} <span class="text-sm font-normal text-slate-400">đơn</span></div>
            </div>
            <div class="text-xs font-semibold text-slate-500 mt-3 pt-2 border-t border-slate-100 flex justify-between">
                <span>TB mỗi đơn:</span>
                <span class="text-slate-800 font-bold">{{ number_format($overview['average_order_value']) }}đ</span>
            </div>
        </div>
    </div>

    <!-- Chart: Daily Trend -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h4 class="font-extrabold text-sm text-slate-900">Biểu Đồ Doanh Thu Theo Ngày (POS vs Online)</h4>
            <span class="text-xs text-slate-400">Đơn vị: VNĐ</span>
        </div>
        <div class="h-64">
            <canvas id="salesTrendChart"></canvas>
        </div>
    </div>

    <!-- Two Columns: Staff Performance & Store Performance -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Staff Sales Leaderboard -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h4 class="font-extrabold text-sm text-slate-900 flex items-center gap-2">
                    <span>🏆</span> Bảng Xếp Hạng Doanh Số Nhân Viên
                </h4>
                <span class="text-xs text-slate-400">{{ count($staffSales) }} nhân viên</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider text-[11px] border-b border-slate-100">
                            <th class="px-4 py-3">Hạng</th>
                            <th class="px-4 py-3">Nhân viên</th>
                            <th class="px-4 py-3 text-center">Số đơn</th>
                            <th class="px-4 py-3 text-right">Doanh thu thuần</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($staffSales as $idx => $st)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-4 py-3 font-bold">
                                @if($idx === 0)
                                    <span class="w-5 h-5 rounded-full bg-amber-400 text-slate-900 flex items-center justify-center text-xs font-black">1</span>
                                @elseif($idx === 1)
                                    <span class="w-5 h-5 rounded-full bg-slate-300 text-slate-800 flex items-center justify-center text-xs font-black">2</span>
                                @elseif($idx === 2)
                                    <span class="w-5 h-5 rounded-full bg-amber-600 text-white flex items-center justify-center text-xs font-black">3</span>
                                @else
                                    <span class="text-slate-400 font-mono pl-1">{{ $idx + 1 }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-slate-900">{{ $st['staff_name'] }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $st['employee_code'] }}</div>
                            </td>
                            <td class="px-4 py-3 text-center font-bold">{{ $st['orders_count'] }}</td>
                            <td class="px-4 py-3 text-right font-mono font-extrabold text-rose-600">
                                {{ number_format($st['net_sales']) }}đ
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-slate-400">Không có dữ liệu nhân viên trong khoảng thời gian này.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Store Sales Breakdown -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h4 class="font-extrabold text-sm text-slate-900 flex items-center gap-2">
                    <span>🏬</span> Doanh Thu Theo Cửa Hàng
                </h4>
                <span class="text-xs text-slate-400">{{ count($storeSales) }} chi nhánh</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider text-[11px] border-b border-slate-100">
                            <th class="px-4 py-3">Mã CH</th>
                            <th class="px-4 py-3">Tên cửa hàng</th>
                            <th class="px-4 py-3 text-center">Số đơn</th>
                            <th class="px-4 py-3 text-right">Doanh thu thuần</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($storeSales as $ss)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-4 py-3 font-mono font-bold text-slate-900">{{ $ss['store_code'] }}</td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-slate-800">{{ $ss['store_name'] }}</div>
                                <div class="text-[10px] text-slate-400">{{ $ss['city'] }}</div>
                            </td>
                            <td class="px-4 py-3 text-center font-bold">{{ $ss['orders_count'] }}</td>
                            <td class="px-4 py-3 text-right font-mono font-extrabold text-slate-900">
                                {{ number_format($ss['net_sales']) }}đ
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-slate-400">Không có dữ liệu cửa hàng trong khoảng thời gian này.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Revenue Orders Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-extrabold text-sm text-slate-900">Giao Dịch Ghi Nhận Doanh Thu Gần Đây</h4>
            <span class="text-xs text-slate-400">15 giao dịch mới nhất</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider text-[11px] border-b border-slate-100">
                        <th class="px-5 py-3">Mã Đơn</th>
                        <th class="px-5 py-3">Kênh</th>
                        <th class="px-5 py-3">Cửa hàng</th>
                        <th class="px-5 py-3">Nhân viên</th>
                        <th class="px-5 py-3">Khách hàng</th>
                        <th class="px-5 py-3 text-right">Tổng tiền</th>
                        <th class="px-5 py-3 text-right">Doanh thu thuần</th>
                        <th class="px-5 py-3">Thời gian ghi nhận</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($recentOrders as $ro)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-5 py-3 font-mono font-bold text-slate-900">
                            @if($ro->isPos())
                                <a href="{{ route('pos.sales.receipt', $ro->id) }}" target="_blank" class="text-rose-600 hover:underline">{{ $ro->display_code }}</a>
                            @else
                                <a href="{{ route('admin.orders.show', $ro->id) }}" class="text-indigo-600 hover:underline">{{ $ro->display_code }}</a>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            @if($ro->channel === 'pos')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-700">POS Quầy</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700">Online</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 font-medium">{{ $ro->store->name ?? 'Kho Tổng' }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $ro->staff->name ?? '—' }}</td>
                        <td class="px-5 py-3">{{ $ro->customer_name }}</td>
                        <td class="px-5 py-3 text-right font-mono">{{ number_format($ro->total) }}đ</td>
                        <td class="px-5 py-3 text-right font-mono font-bold text-emerald-600">{{ number_format($ro->net_total) }}đ</td>
                        <td class="px-5 py-3 text-slate-400 font-mono">{{ $ro->completed_at ? $ro->completed_at->format('d/m/Y H:i') : '' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-8 text-center text-slate-400">Không có đơn hàng nào phát sinh doanh thu trong bộ lọc.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const trendData = @json($dailyTrends);
        const ctx = document.getElementById('salesTrendChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: trendData.map(d => d.label),
                datasets: [
                    {
                        label: 'POS (Tại quầy)',
                        data: trendData.map(d => d.pos),
                        backgroundColor: '#e11d48',
                        borderRadius: 4,
                    },
                    {
                        label: 'Online',
                        data: trendData.map(d => d.online),
                        backgroundColor: '#6366f1',
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: true,
                        grid: { display: false }
                    },
                    y: {
                        stacked: true,
                        ticks: {
                            callback: function(value) {
                                return (value / 1000000) + ' tr';
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + new Intl.NumberFormat('vi-VN').format(context.raw) + 'đ';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
