@extends('admin.layouts.app')

@section('title', 'Khách Hàng')
@section('page-title', 'Hồ Sơ Khách Hàng')
@section('page-description', 'Thông tin và lịch sử mua hàng của: ' . $customer->name)

@section('content')
<div class="max-w-5xl space-y-8">
    <!-- Customer Identity Focal Point -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group">
        <div class="bg-gray-50 p-8 flex flex-col md:flex-row items-center gap-8 relative overflow-hidden border-b border-gray-100">
            
            <div class="relative z-10 w-24 h-24 bg-red-50 rounded-2xl flex items-center justify-center text-red-500 text-3xl font-bold border border-red-100 shadow-sm overflow-hidden">
                <span class="relative z-10">{{ substr($customer->name, 0, 1) }}</span>
            </div>

            <div class="relative z-10 flex-1 text-center md:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-50 border border-red-100 mb-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                    <span class="text-[10px] font-bold text-red-600 uppercase tracking-widest">ID: #{{ $customer->id }}</span>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-1">{{ $customer->name }}</h2>
                <p class="text-sm font-semibold text-gray-500">{{ $customer->email }}</p>
            </div>

            <div class="relative z-10 flex flex-col gap-3 w-full md:w-auto">
                <div class="px-5 py-3 bg-white rounded-xl border border-gray-200">
                    <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-0.5">Ngày Đăng Ký</p>
                    <p class="text-xs font-bold text-gray-800">{{ $customer->created_at->format('d/m/Y') }}</p>
                </div>
                <a href="{{ route('admin.customers.edit', $customer) }}" class="flex items-center justify-center gap-2 px-6 py-2.5 bg-white text-gray-700 rounded-xl font-bold text-xs uppercase tracking-wide border border-gray-200 hover:bg-gray-50 hover:text-gray-900 transition-all shadow-sm active:scale-95 group/btn">
                    <svg class="w-4 h-4 group-hover/btn:-rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Sửa Thông Tin
                </a>
            </div>
        </div>

        <div class="p-8 divide-y divide-gray-100">
            <!-- Strategic Engagement Matrix -->
            <div class="pb-8">
                <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-6 pl-3 border-l-2 border-red-500">Thống Kê Khách Hàng</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-6 bg-red-50 rounded-xl border border-red-100 flex flex-col items-center text-center group">
                        <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center text-red-500 mb-4 border border-red-100 shadow-sm group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-[10px] font-semibold text-red-500 uppercase tracking-widest mb-1">Tổng Chi Tiêu</p>
                        <p class="text-xl font-bold text-red-700">{{ number_format($totalSpent) }}đ</p>
                    </div>

                    <div class="p-6 bg-gray-50 rounded-xl border border-gray-200 flex flex-col items-center text-center group">
                        <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center text-gray-600 mb-4 border border-gray-200 shadow-sm group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-1">Đơn Hàng Đã Mua</p>
                        <p class="text-xl font-bold text-gray-800">{{ $totalOrders }}</p>
                    </div>

                    <div class="p-6 bg-gray-50 rounded-xl border border-gray-200 flex flex-col items-center text-center group">
                        <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center text-gray-600 mb-4 border border-gray-200 shadow-sm group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-1">Giá Trị Trung Bình/Đơn</p>
                        <p class="text-xl font-bold text-gray-800">{{ $totalOrders > 0 ? number_format($totalSpent / $totalOrders) : 0 }}đ</p>
                    </div>
                </div>
            </div>

            <!-- Communication Lines -->
            <div class="py-8">
                <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-6 pl-3 border-l-2 border-gray-400">Thông Tin Liên Hệ</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-center gap-4 p-5 bg-gray-50 rounded-xl border border-gray-100 group hover:border-gray-200 transition-all">
                        <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center text-gray-500 border border-gray-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-0.5">Email</p>
                            <p class="text-sm font-bold text-gray-800">{{ $customer->email }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-5 bg-gray-50 rounded-xl border border-gray-100 group hover:border-gray-200 transition-all">
                        <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center text-gray-500 border border-gray-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-0.5">Số Điện Thoại</p>
                            <p class="text-sm font-bold text-gray-800">{{ $customer->phone ?? 'Chưa cập nhật' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Chart -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 px-8 py-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                </div>
                Biểu Đồ Mua Hàng (6 Tháng Gần Nhất)
            </h3>
        </div>
        <div class="p-8">
            <canvas id="customerChart" height="100"></canvas>
        </div>
    </div>

    <!-- Transactional History -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 px-8 py-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider flex items-center gap-3">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Lịch Sử Mua Hàng (10 Đơn Gần Nhất)
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-white border-b border-gray-100">
                        <th class="px-8 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Mã Đơn</th>
                        <th class="px-8 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Ngày Đặt</th>
                        <th class="px-8 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Giá Trị</th>
                        <th class="px-8 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Trạng Thái</th>
                        <th class="px-8 py-4 text-right text-[11px] font-bold text-gray-500 uppercase tracking-wider">Chi Tiết</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-8 py-4">
                            <span class="text-xs font-bold text-gray-800">#{{ $order->id }}</span>
                        </td>
                        <td class="px-8 py-4">
                            <p class="text-[13px] font-semibold text-gray-700 mb-0.5">{{ $order->created_at->format('d/m/Y') }}</p>
                            <p class="text-[11px] text-gray-500">{{ $order->created_at->format('H:i') }}</p>
                        </td>
                        <td class="px-8 py-4">
                            <span class="text-[13px] font-bold text-gray-800">{{ number_format($order->total) }}đ</span>
                        </td>
                        <td class="px-8 py-4">
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-50 text-yellow-600 border-yellow-100',
                                    'processing' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'shipping' => 'bg-purple-50 text-purple-600 border-purple-100',
                                    'completed' => 'bg-green-50 text-green-600 border-green-100',
                                    'cancelled' => 'bg-gray-100 text-gray-500 border-gray-200'
                                ];
                                $statusLabels = [
                                    'pending' => 'Chờ Xử Lý',
                                    'processing' => 'Đang Chuẩn Bị',
                                    'shipping' => 'Đang Giao',
                                    'completed' => 'Hoàn Thành',
                                    'cancelled' => 'Đã Hủy'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-semibold tracking-wide border {{ $statusClasses[$order->status] ?? 'bg-gray-100' }} uppercase">
                                {{ $statusLabels[$order->status] ?? $order->status }}
                            </span>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center justify-center p-2 bg-gray-50 text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-all border border-gray-100 hover:border-blue-100 active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center text-gray-500 text-sm">
                            Chưa có đơn hàng nào
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Navigation Control -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center gap-2 bg-white text-gray-600 px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-wide hover:bg-gray-50 transition-all border border-gray-200 shadow-sm active:scale-95 group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Quay Lại Danh Sách
        </a>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('customerChart').getContext('2d');
        const labels = {!! $chartLabels !!};
        const orderData = {!! $chartOrders !!};
        const spentData = {!! $chartSpent !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Số Đơn Hàng',
                        data: orderData,
                        backgroundColor: 'rgba(239, 68, 68, 0.2)',
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 2,
                        borderRadius: 4,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Tổng Chi Tiêu (VNĐ)',
                        data: spentData,
                        type: 'line',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 3,
                        tension: 0.3,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: { display: true, text: 'Số Đơn Hàng' },
                        grid: { drawOnChartArea: true, color: 'rgba(0,0,0,0.05)' },
                        ticks: { stepSize: 1 }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: { display: true, text: 'Chi Tiêu (VNĐ)' },
                        grid: { drawOnChartArea: false }
                    }
                }
            }
        });
    });
</script>
@endpush

@endsection
