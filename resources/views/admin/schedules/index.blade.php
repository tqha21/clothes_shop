@extends('admin.layouts.app')

@section('title', 'Lịch Xếp Ca Làm Việc - Tháng ' . $month . '/' . $year)
@section('page-title', 'Quản Lý Lịch Ca Làm Việc')
@section('page-description', 'Xếp ca hàng loạt, theo dõi chỉ tiêu nhân sự, chống trùng ca và hoán đổi ca làm việc')

@section('content')
<div class="p-6 space-y-6">

    {{-- Alerts & Notifications --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-2xl px-5 py-4 shadow-sm">
            <div class="w-8 h-8 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0 text-green-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="flex-1">
                <div class="font-bold text-sm text-green-900">Thành công!</div>
                <div class="text-xs text-green-700 mt-0.5">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-2xl px-5 py-4 shadow-sm">
            <div class="w-8 h-8 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0 text-red-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div class="flex-1">
                <div class="font-bold text-sm text-red-900">Thông báo từ hệ thống</div>
                <div class="text-xs text-red-700 mt-0.5">{{ session('error') }}</div>
            </div>
        </div>
    @endif

    {{-- Cảnh báo trùng lịch chi tiết nếu có ca bị bỏ qua trong lần xếp hàng loạt --}}
    @if(session('skipped_conflicts') && count(session('skipped_conflicts')) > 0)
        <div class="bg-amber-50 border border-amber-200 text-amber-900 rounded-2xl p-4 text-xs">
            <div class="font-bold flex items-center gap-2 mb-2 text-amber-800">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Chi tiết các ca bị bỏ qua do trùng lịch:
            </div>
            <ul class="list-disc list-inside space-y-1 text-amber-700 pl-1">
                @foreach(session('skipped_conflicts') as $conflictMsg)
                    <li>{{ $conflictMsg }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Cảnh báo nếu trong tháng hiện tại đang có ca bị xếp trùng giờ --}}
    @if(count($conflictedScheduleIds) > 0)
        <div class="flex items-center justify-between bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl px-5 py-3.5 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                </span>
                <span class="text-xs font-bold">
                    Cảnh báo: Phát hiện {{ count($conflictedScheduleIds) }} ca làm việc đang bị trùng giờ trong tháng này! (Các ca này được đánh dấu biểu tượng ⚠️ trên lịch)
                </span>
            </div>
            <span class="text-[11px] bg-rose-100 text-rose-800 px-2.5 py-1 rounded-lg font-semibold">Cần kiểm tra</span>
        </div>
    @endif

    {{-- Header Action Bar --}}
    <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm space-y-4">
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">

            {{-- Month Navigation --}}
            <div class="flex items-center gap-2 bg-gray-50 rounded-2xl border border-gray-200/80 p-1.5 self-start">
                <a href="{{ route('admin.schedules.index', array_merge(request()->except(['year','month']), ['year'=>$prevMonth->year,'month'=>$prevMonth->month])) }}"
                   class="p-2 hover:bg-white rounded-xl transition text-gray-500 hover:text-gray-900 shadow-sm hover:shadow" title="Tháng trước">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div class="px-4 min-w-[150px] text-center">
                    <div class="font-extrabold text-gray-900 text-base leading-tight">Tháng {{ $month }}</div>
                    <div class="text-[11px] text-gray-500 font-medium">Năm {{ $year }}</div>
                </div>
                <a href="{{ route('admin.schedules.index', array_merge(request()->except(['year','month']), ['year'=>$nextMonth->year,'month'=>$nextMonth->month])) }}"
                   class="p-2 hover:bg-white rounded-xl transition text-gray-500 hover:text-gray-900 shadow-sm hover:shadow" title="Tháng sau">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('admin.schedules.index', ['year'=>now()->year,'month'=>now()->month]) }}"
                   class="ml-1 text-xs px-2.5 py-1.5 font-semibold text-gray-600 hover:text-red-600 hover:bg-white rounded-xl transition">
                    Hôm nay
                </a>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap items-center gap-2 justify-end">
                {{-- Nút Sao Chép Lịch Tuần --}}
                <button onclick="openModal('copyWeekModal')"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-2xl transition border border-indigo-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                    Sao Chép Tuần
                </button>

                {{-- Nút Đổi Ca / Chuyển Ca --}}
                <button onclick="openModal('swapModal')"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold rounded-2xl transition border border-amber-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    Đổi Ca
                </button>

                {{-- Nút Xuất Excel --}}
                <a href="{{ route('admin.schedules.export', request()->all()) }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-bold rounded-2xl transition border border-emerald-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Xuất Excel
                </a>

                {{-- Nút In Lịch (A4 Landscape) --}}
                <a href="{{ route('admin.schedules.print', request()->all()) }}" target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-2xl transition border border-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    In Lịch (PDF)
                </a>

                {{-- Nút Quản Lý Ca --}}
                <a href="{{ route('admin.shifts.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-gray-50 text-gray-700 text-xs font-bold rounded-2xl transition border border-gray-200">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    Định Nghĩa Ca
                </a>

                {{-- Nút Xếp Ca Mới (Chính) --}}
                <button onclick="openAddModal()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white text-xs font-bold rounded-2xl shadow-md shadow-red-200 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Xếp Ca Mới
                </button>
            </div>
        </div>

        {{-- Filter Bar & View Mode Tabs --}}
        <div class="pt-3 border-t border-gray-100 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
            {{-- Tabs --}}
            <div class="flex items-center gap-1 bg-gray-100 p-1 rounded-2xl self-start">
                <a href="{{ route('admin.schedules.index', array_merge(request()->all(), ['view_mode'=>'calendar'])) }}"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $viewMode === 'calendar' ? 'bg-white text-red-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Lịch Tháng
                </a>
                <a href="{{ route('admin.schedules.index', array_merge(request()->all(), ['view_mode'=>'unscheduled'])) }}"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $viewMode === 'unscheduled' ? 'bg-white text-red-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    Chưa Xếp Ca
                    @if($totalUnscheduled > 0)
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-red-100 text-red-700 font-extrabold">{{ $totalUnscheduled }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.schedules.index', array_merge(request()->all(), ['view_mode'=>'staffing'])) }}"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $viewMode === 'staffing' ? 'bg-white text-red-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Định Mức Ca
                    @if($totalShortageShifts > 0)
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-amber-100 text-amber-800 font-extrabold">Thiếu {{ $totalShortageShifts }}</span>
                    @endif
                </a>
            </div>

            {{-- Filter Form --}}
            <form method="GET" action="{{ route('admin.schedules.index') }}" class="flex flex-wrap items-center gap-2">
                <input type="hidden" name="year" value="{{ $year }}">
                <input type="hidden" name="month" value="{{ $month }}">
                <input type="hidden" name="view_mode" value="{{ $viewMode }}">

                {{-- Filter Store --}}
                <select name="store_id" onchange="this.form.submit()"
                    class="border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-700 focus:ring-2 focus:ring-red-500 outline-none bg-white">
                    <option value="">-- Tất cả cửa hàng --</option>
                    @foreach($stores as $st)
                        <option value="{{ $st->id }}" {{ $storeId == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                    @endforeach
                </select>

                {{-- Filter Employee --}}
                <select name="employee_id" onchange="this.form.submit()"
                    class="border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-700 focus:ring-2 focus:ring-red-500 outline-none bg-white">
                    <option value="">-- Tất cả nhân viên --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ $employeeId == $emp->id ? 'selected' : '' }}>
                            {{ $emp->name }} ({{ $emp->employee_code ?: 'NV'.$emp->id }})
                        </option>
                    @endforeach
                </select>

                {{-- Filter Shift --}}
                <select name="shift_id" onchange="this.form.submit()"
                    class="border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-700 focus:ring-2 focus:ring-red-500 outline-none bg-white">
                    <option value="">-- Tất cả ca làm --</option>
                    @foreach($shifts as $sh)
                        <option value="{{ $sh->id }}" {{ $shiftId == $sh->id ? 'selected' : '' }}>
                            {{ $sh->name }} ({{ substr($sh->start_time,0,5) }}-{{ substr($sh->end_time,0,5) }})
                        </option>
                    @endforeach
                </select>

                @if($storeId || $employeeId || $shiftId)
                    <a href="{{ route('admin.schedules.index', ['year'=>$year,'month'=>$month,'view_mode'=>$viewMode]) }}"
                       class="text-xs text-gray-500 hover:text-red-600 px-3 py-2 border border-gray-200 rounded-xl bg-white transition hover:border-red-300">
                        ✕ Bỏ lọc
                    </a>
                @endif
            </form>
        </div>
    </div>

    {{-- Stats Cards (KPIs) --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        {{-- Total shifts --}}
        <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-gray-500">Tổng ca trong tháng</span>
                <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">📅</span>
            </div>
            <div class="text-2xl font-black text-gray-900">{{ $totalSchedules }}</div>
            <div class="text-[11px] text-gray-400 mt-0.5">{{ $endOfMonth->day }} ngày làm việc</div>
        </div>

        {{-- Active Employees --}}
        <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-gray-500">Nhân sự đã xếp lịch</span>
                <span class="w-8 h-8 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-sm font-bold">👥</span>
            </div>
            <div class="text-2xl font-black text-gray-900">{{ $totalEmployees }} <span class="text-xs text-gray-400 font-semibold">/ {{ $employees->count() }} NV</span></div>
            <div class="text-[11px] text-green-600 font-semibold mt-0.5">Đạt {{ $employees->count() > 0 ? round(($totalEmployees / $employees->count()) * 100) : 0 }}% nhân sự</div>
        </div>

        {{-- Unscheduled Employees --}}
        <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm cursor-pointer hover:border-red-300 transition"
             onclick="window.location.href='{{ route('admin.schedules.index', array_merge(request()->all(), ['view_mode'=>'unscheduled'])) }}'">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-gray-500">Chưa được xếp ca</span>
                <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm font-bold">⚠️</span>
            </div>
            <div class="text-2xl font-black {{ $totalUnscheduled > 0 ? 'text-rose-600' : 'text-gray-900' }}">
                {{ $totalUnscheduled }} <span class="text-xs font-semibold text-gray-400">nhân viên</span>
            </div>
            <div class="text-[11px] {{ $totalUnscheduled > 0 ? 'text-rose-500 font-bold' : 'text-gray-400' }} mt-0.5">
                {{ $totalUnscheduled > 0 ? 'Click xem & xếp bổ sung →' : 'Tất cả đã có ca' }}
            </div>
        </div>

        {{-- Staff Shortage Shifs --}}
        <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm cursor-pointer hover:border-amber-300 transition"
             onclick="window.location.href='{{ route('admin.schedules.index', array_merge(request()->all(), ['view_mode'=>'staffing'])) }}'">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-gray-500">Ca thiếu người</span>
                <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-bold">⚡</span>
            </div>
            <div class="text-2xl font-black {{ $totalShortageShifts > 0 ? 'text-amber-600' : 'text-gray-900' }}">
                {{ $totalShortageShifts }} <span class="text-xs font-semibold text-gray-400">ca</span>
            </div>
            <div class="text-[11px] {{ $totalShortageShifts > 0 ? 'text-amber-600 font-bold' : 'text-gray-400' }} mt-0.5">
                {{ $totalShortageShifts > 0 ? 'Chưa đủ định mức tối thiểu' : 'Đạt chỉ tiêu tất cả ca' }}
            </div>
        </div>

        {{-- Conflicted Shifts Warning --}}
        <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-gray-500">Trùng lịch làm</span>
                <span class="w-8 h-8 rounded-xl {{ count($conflictedScheduleIds) > 0 ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-500' }} flex items-center justify-center text-sm font-bold">🛡️</span>
            </div>
            <div class="text-2xl font-black {{ count($conflictedScheduleIds) > 0 ? 'text-red-600' : 'text-green-600' }}">
                {{ count($conflictedScheduleIds) }} <span class="text-xs font-semibold text-gray-400">ca trùng</span>
            </div>
            <div class="text-[11px] {{ count($conflictedScheduleIds) > 0 ? 'text-red-500 font-bold' : 'text-green-600 font-semibold' }} mt-0.5">
                {{ count($conflictedScheduleIds) > 0 ? 'Cần kiểm tra chỉnh sửa' : 'Hệ thống an toàn' }}
            </div>
        </div>
    </div>

    {{-- Shift Color Legend & Quick Indicators --}}
    <div class="flex flex-wrap items-center justify-between gap-3 bg-white rounded-2xl px-5 py-3 border border-gray-100 shadow-sm">
        <div class="flex flex-wrap items-center gap-3">
            <span class="text-xs font-bold text-gray-700">Các ca làm việc:</span>
            @foreach($shifts as $shift)
            <div class="flex items-center gap-2 bg-gray-50 border border-gray-200/60 rounded-xl px-3 py-1 text-xs">
                <span class="w-3 h-3 rounded-full flex-shrink-0" style="background-color: {{ $shift->color }}"></span>
                <span class="font-bold text-gray-800">{{ $shift->name }}</span>
                <span class="text-[11px] text-gray-500">({{ substr($shift->start_time,0,5) }} - {{ substr($shift->end_time,0,5) }})</span>
                <span class="text-[10px] bg-white border border-gray-200 px-1.5 py-0.5 rounded text-amber-700 font-bold" title="Chỉ tiêu tối thiểu">
                    min {{ $shift->min_staff ?? 1 }}
                </span>
            </div>
            @endforeach
        </div>

        <div class="flex items-center gap-3 text-xs text-gray-500">
            <div class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span>Đủ người</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                <span>Thiếu người</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                <span>Chưa có ai</span>
            </div>
        </div>
    </div>

    {{-- TAB 1: CALENDAR VIEW --}}
    @if($viewMode === 'calendar')
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        {{-- Day of Week Headers --}}
        <div class="grid grid-cols-7 border-b border-gray-100 bg-gray-50/70">
            @foreach([
                ['code'=>'T2','name'=>'Thứ Hai'],
                ['code'=>'T3','name'=>'Thứ Ba'],
                ['code'=>'T4','name'=>'Thứ Tư'],
                ['code'=>'T5','name'=>'Thứ Năm'],
                ['code'=>'T6','name'=>'Thứ Sáu'],
                ['code'=>'T7','name'=>'Thứ Bảy','isWeekend'=>true],
                ['code'=>'CN','name'=>'Chủ Nhật','isWeekend'=>true],
            ] as $d)
            <div class="py-3 px-2 text-center {{ !empty($d['isWeekend']) ? 'text-rose-500' : 'text-gray-700' }}">
                <div class="text-xs font-extrabold uppercase tracking-wider">{{ $d['code'] }}</div>
                <div class="text-[10px] font-normal opacity-70 hidden sm:block">{{ $d['name'] }}</div>
            </div>
            @endforeach
        </div>

        {{-- Grid Days --}}
        <div class="grid grid-cols-7 divide-x divide-y divide-gray-100">
            @foreach($calendarDays as $day)
            @php
                $dateKey = $day['date']->format('Y-m-d');
                $daySchedules = $schedules[$dateKey] ?? collect();
                $statsForDay = $dayShiftStats[$dateKey] ?? [];
            @endphp
            <div class="min-h-[140px] p-2 relative group flex flex-col justify-between transition-colors
                {{ !$day['isCurrentMonth'] ? 'bg-gray-50/40 text-gray-300' : 'bg-white' }}
                {{ $day['isToday'] ? 'ring-2 ring-inset ring-red-500/20 bg-red-50/30' : '' }}
                {{ $day['isWeekend'] && $day['isCurrentMonth'] ? 'bg-rose-50/10' : '' }}"
                id="cell-{{ $dateKey }}">

                <div>
                    {{-- Cell Header: Date Number + Shift Status Badges --}}
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full inline-flex items-center justify-center
                            {{ $day['isToday'] ? 'bg-red-600 text-white font-extrabold shadow-sm' : ($day['isWeekend'] ? 'text-rose-500 font-extrabold' : 'text-gray-800') }}">
                            {{ $day['date']->day }}
                        </span>

                        {{-- Hiển thị số nhân viên mỗi ca (Chỉ tiêu min_staff) --}}
                        @if($day['isCurrentMonth'] && !empty($statsForDay))
                        <div class="flex items-center gap-1" title="Số nhân sự từng ca trong ngày">
                            @foreach($statsForDay as $shId => $st)
                                @php
                                    $bgColor = $st['status'] === 'sufficient' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' :
                                              ($st['status'] === 'lacking' ? 'bg-amber-50 text-amber-800 border-amber-200' : 'bg-rose-50 text-rose-700 border-rose-200');
                                @endphp
                                <span class="text-[9px] px-1.5 py-0.2 rounded border font-bold {{ $bgColor }}"
                                      title="{{ $st['shift']->name }}: {{ $st['count'] }}/{{ $st['min_staff'] }} nhân sự ({{ $st['status'] === 'sufficient' ? 'Đủ' : ($st['status'] === 'lacking' ? 'Thiếu người' : 'Trống') }})">
                                    {{ mb_substr($st['shift']->name, 0, 1) }}:{{ $st['count'] }}/{{ $st['min_staff'] }}
                                </span>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    {{-- Schedule Pills for this day --}}
                    <div class="space-y-1">
                        @foreach($daySchedules->take(4) as $schedule)
                        @php
                            $isConflicted = in_array($schedule->id, $conflictedScheduleIds);
                        @endphp
                        <div class="group/pill relative flex items-center justify-between gap-1 rounded-lg px-2 py-1 text-white text-[11px] font-semibold leading-tight shadow-sm hover:shadow transition"
                             style="background-color: {{ $schedule->shift->color ?? '#3B82F6' }}"
                             title="{{ $schedule->user->name }} - {{ $schedule->shift->name }} ({{ substr($schedule->shift->start_time,0,5) }}-{{ substr($schedule->shift->end_time,0,5) }}) [{{ $schedule->status_label }}]">

                            <div class="flex items-center gap-1 truncate">
                                @if($isConflicted)
                                    <span class="bg-red-600 text-white rounded px-1 text-[9px] font-black animate-pulse" title="Trùng lịch làm!">⚠️</span>
                                @endif
                                <span class="truncate">{{ $schedule->user->name }}</span>
                            </div>

                            <span class="text-[9px] opacity-80 flex-shrink-0">{{ mb_substr($schedule->shift->name ?? '', 0, 3) }}</span>

                            {{-- Hover Actions: Delete & Quick Swap --}}
                            <div class="absolute inset-y-0 right-0 bg-gray-900/80 rounded-r-lg px-1.5 flex items-center gap-1 opacity-0 group-hover/pill:opacity-100 transition-opacity">
                                <button type="button" onclick="quickSwapModal({{ $schedule->id }}, '{{ addslashes($schedule->user->name) }}', '{{ $dateKey }}', '{{ addslashes($schedule->shift->name) }}')"
                                    class="text-amber-300 hover:text-white p-0.5" title="Đổi ca này">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                </button>
                                <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST"
                                      onsubmit="return confirm('Xóa lịch ca của {{ addslashes($schedule->user->name) }} ngày {{ $schedule->work_date->format('d/m/Y') }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-rose-300 hover:text-white p-0.5" title="Xóa ca">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach

                        @if($daySchedules->count() > 4)
                        <div class="text-[10px] text-gray-500 font-bold px-1 text-center bg-gray-100 rounded py-0.5">
                            +{{ $daySchedules->count() - 4 }} ca nữa...
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Quick Add Button on Hover --}}
                @if($day['isCurrentMonth'])
                <div class="pt-2 flex justify-end">
                    <button type="button" onclick="openAddModal('{{ $dateKey }}')"
                        class="w-6 h-6 rounded-full bg-gray-100 hover:bg-red-600 text-gray-500 hover:text-white text-xs font-bold flex items-center justify-center transition opacity-0 group-hover:opacity-100 shadow-sm"
                        title="Thêm ca ngày {{ $dateKey }}">
                        +
                    </button>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- TAB 2: UNSCHEDULED EMPLOYEES --}}
    @elseif($viewMode === 'unscheduled')
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold text-gray-900">Danh Sách Nhân Viên Chưa Được Xếp Ca</h3>
                <p class="text-xs text-gray-500 mt-0.5">Phát hiện và xếp ca nhanh cho các nhân sự chưa có lịch trong tháng {{ $month }}/{{ $year }} để tránh bỏ sót.</p>
            </div>
            <span class="px-3 py-1 bg-red-50 text-red-700 rounded-full text-xs font-bold border border-red-200">
                {{ $totalUnscheduled }} nhân sự cần xếp lịch
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-600 uppercase tracking-wider font-bold">
                    <tr>
                        <th class="px-6 py-4">Nhân viên</th>
                        <th class="px-6 py-4">Mã NV</th>
                        <th class="px-6 py-4">Cửa hàng / Chi nhánh</th>
                        <th class="px-6 py-4">Vai trò</th>
                        <th class="px-6 py-4">Số ca đã xếp tháng này</th>
                        <th class="px-6 py-4">Trạng thái</th>
                        <th class="px-6 py-4 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($employeeStats as $emp)
                    @php
                        $isZero = $emp->assigned_shifts_count === 0;
                        $isLow = $emp->assigned_shifts_count > 0 && $emp->assigned_shifts_count < 10;
                    @endphp
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $emp->avatar_url }}" class="w-9 h-9 rounded-full object-cover border border-gray-200">
                                <div>
                                    <div class="font-bold text-gray-900 text-sm">{{ $emp->name }}</div>
                                    <div class="text-[11px] text-gray-400">{{ $emp->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-mono font-semibold text-gray-600">
                            {{ $emp->employee_code ?: ('NV' . $emp->id) }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-700">
                            {{ $emp->store->name ?? 'Tổng công ty' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-gray-100 text-gray-700">
                                {{ ucfirst($emp->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-extrabold {{ $isZero ? 'text-red-600' : ($isLow ? 'text-amber-600' : 'text-emerald-600') }}">
                                {{ $emp->assigned_shifts_count }} ca
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($isZero)
                                <span class="px-3 py-1 rounded-full text-[11px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200 flex items-center gap-1 w-max">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span> Chưa có ca nào
                                </span>
                            @elseif($isLow)
                                <span class="px-3 py-1 rounded-full text-[11px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200 flex items-center gap-1 w-max">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span> Ít ca (< 10 ca)
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-1 w-max">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Đã xếp đủ
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button type="button" onclick="openAddModalForUser({{ $emp->id }}, '{{ addslashes($emp->name) }}')"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Xếp Ca Ngay
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-10 text-gray-400">Không có dữ liệu nhân viên.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- TAB 3: STAFFING ANALYSIS VIEW --}}
    @elseif($viewMode === 'staffing')
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold text-gray-900">Theo Dõi Định Mức Nhân Sự Từng Ca</h3>
                <p class="text-xs text-gray-500 mt-0.5">Kiểm tra ca nào đang thiếu người so với chỉ tiêu tối thiểu trong từng ngày của tháng {{ $month }}/{{ $year }}.</p>
            </div>
            <span class="px-3 py-1 bg-amber-50 text-amber-800 rounded-full text-xs font-bold border border-amber-200">
                {{ $totalShortageShifts }} ca đang thiếu người
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-600 uppercase tracking-wider font-bold">
                    <tr>
                        <th class="px-6 py-4">Ngày làm việc</th>
                        <th class="px-6 py-4">Thứ</th>
                        @foreach($shifts as $sh)
                        <th class="px-6 py-4">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $sh->color }}"></span>
                                <span>{{ $sh->name }}</span>
                                <span class="text-[10px] text-gray-400 font-normal">(min {{ $sh->min_staff ?? 1 }})</span>
                            </div>
                        </th>
                        @endforeach
                        <th class="px-6 py-4 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($calendarDays as $day)
                    @if($day['isCurrentMonth'])
                    @php
                        $dKey = $day['date']->format('Y-m-d');
                        $stats = $dayShiftStats[$dKey] ?? [];
                        $hasShortage = false;
                        foreach($stats as $st) { if($st['is_shortage']) { $hasShortage = true; break; } }
                    @endphp
                    <tr class="hover:bg-gray-50/70 transition {{ $hasShortage ? 'bg-amber-50/20' : '' }}">
                        <td class="px-6 py-3.5 font-bold text-gray-800">
                            {{ $day['date']->format('d/m/Y') }}
                            @if($day['isToday'])
                                <span class="ml-1 text-[10px] bg-red-600 text-white px-2 py-0.5 rounded-full">Hôm nay</span>
                            @endif
                        </td>
                        <td class="px-6 py-3.5 font-medium {{ $day['isWeekend'] ? 'text-rose-600 font-bold' : 'text-gray-500' }}">
                            {{ $day['date']->translatedFormat('l') }}
                        </td>
                        @foreach($shifts as $sh)
                        @php
                            $st = $stats[$sh->id] ?? ['count'=>0, 'min_staff'=>$sh->min_staff??1, 'status'=>'empty'];
                        @endphp
                        <td class="px-6 py-3.5">
                            @if($st['status'] === 'sufficient')
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    ✓ Đủ ({{ $st['count'] }}/{{ $st['min_staff'] }})
                                </span>
                            @elseif($st['status'] === 'lacking')
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-800 border border-amber-300">
                                    ⚠️ Thiếu ({{ $st['count'] }}/{{ $st['min_staff'] }})
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    ✕ Trống (0/{{ $st['min_staff'] }})
                                </span>
                            @endif
                        </td>
                        @endforeach
                        <td class="px-6 py-3.5 text-right">
                            <button type="button" onclick="openAddModal('{{ $dKey }}')"
                                class="text-xs text-red-600 hover:text-red-700 font-bold hover:underline">
                                + Xếp ca ngày này
                            </button>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Detail Schedule Table (Always visible at bottom for detailed review) --}}
    @if($totalSchedules > 0)
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-extrabold text-gray-900 text-sm">Danh Sách Chi Tiết Ca Tháng {{ $month }}/{{ $year }}</h3>
                <p class="text-xs text-gray-500">Cập nhật trạng thái hoặc xóa lịch ca nhanh chóng.</p>
            </div>
            <span class="text-xs font-bold text-gray-600 bg-gray-100 px-3 py-1 rounded-full">{{ $totalSchedules }} ca</span>
        </div>
        <div class="overflow-x-auto max-h-[500px]">
            <table class="w-full text-xs">
                <thead class="bg-gray-50 text-gray-600 uppercase tracking-wider font-bold sticky top-0 bg-gray-50 z-10">
                    <tr>
                        <th class="px-6 py-3 text-left">Nhân viên</th>
                        <th class="px-6 py-3 text-left">Ca làm việc</th>
                        <th class="px-6 py-3 text-left">Ngày làm việc</th>
                        <th class="px-6 py-3 text-left">Trạng thái</th>
                        <th class="px-6 py-3 text-left">Ghi chú</th>
                        <th class="px-6 py-3 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($allSchedules->sortBy('work_date') as $schedule)
                    @php $isConflicted = in_array($schedule->id, $conflictedScheduleIds); @endphp
                    <tr class="hover:bg-gray-50 transition {{ $isConflicted ? 'bg-red-50/30' : '' }}">
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2">
                                <img src="{{ $schedule->user->avatar_url }}" class="w-7 h-7 rounded-full object-cover">
                                <div>
                                    <div class="font-bold text-gray-900 flex items-center gap-1">
                                        {{ $schedule->user->name }}
                                        @if($isConflicted)
                                            <span class="text-red-600 font-extrabold" title="Trùng lịch làm!">⚠️</span>
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-gray-400">{{ $schedule->user->employee_code ?: 'NV'.$schedule->user_id }} - {{ $schedule->user->store->name ?? 'Tổng công ty' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: {{ $schedule->shift->color }}"></span>
                                <span class="font-bold text-gray-800">{{ $schedule->shift->name }}</span>
                                <span class="text-gray-400">({{ substr($schedule->shift->start_time,0,5) }}-{{ substr($schedule->shift->end_time,0,5) }})</span>
                            </div>
                        </td>
                        <td class="px-6 py-3 font-semibold text-gray-700">
                            {{ $schedule->work_date->format('d/m/Y') }}
                            <span class="text-[10px] text-gray-400 ml-1">({{ $schedule->work_date->translatedFormat('l') }})</span>
                        </td>
                        <td class="px-6 py-3">
                            <form action="{{ route('admin.schedules.update', $schedule) }}" method="POST">
                                @csrf @method('PUT')
                                <select name="status" onchange="this.form.submit()"
                                    class="text-xs px-2.5 py-1 rounded-lg border-0 font-bold cursor-pointer {{ $schedule->status_color }}">
                                    <option value="scheduled" {{ $schedule->status === 'scheduled' ? 'selected' : '' }}>Đã xếp</option>
                                    <option value="confirmed" {{ $schedule->status === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                    <option value="absent" {{ $schedule->status === 'absent' ? 'selected' : '' }}>Vắng mặt</option>
                                    <option value="completed" {{ $schedule->status === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                </select>
                                <input type="hidden" name="note" value="{{ $schedule->note }}">
                            </form>
                        </td>
                        <td class="px-6 py-3 text-gray-500 max-w-xs truncate" title="{{ $schedule->note }}">
                            {{ $schedule->note ?: '—' }}
                        </td>
                        <td class="px-6 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button type="button" onclick="quickSwapModal({{ $schedule->id }}, '{{ addslashes($schedule->user->name) }}', '{{ $schedule->work_date->format('Y-m-d') }}', '{{ addslashes($schedule->shift->name) }}')"
                                    class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Đổi ca này">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                </button>
                                <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST"
                                    onsubmit="return confirm('Xóa lịch ca của {{ addslashes($schedule->user->name) }} ngày {{ $schedule->work_date->format('d/m/Y') }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Xóa ca">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

{{-- ========================================================================= --}}
{{-- MODAL 1: XẾP CA (ĐƠN LẺ & HÀNG LOẠT THEO TUẦN/THÁNG)                      --}}
{{-- ========================================================================= --}}
<div id="addModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[92vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 sticky top-0 bg-white z-10">
            <h3 class="text-base font-extrabold text-gray-900 flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">📅</span>
                Xếp Ca Làm Việc Nhân Viên
            </h3>
            <button onclick="closeModal('addModal')" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Switch Mode: Single vs Bulk --}}
        <div class="flex border-b border-gray-100 bg-gray-50/50 p-2 gap-2">
            <button type="button" onclick="switchAddTab('bulk')" id="tabBtnBulk"
                class="flex-1 py-2.5 text-xs font-bold rounded-2xl transition bg-white text-red-600 shadow-sm border border-gray-200/60 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Xếp Hàng Loạt Theo Tuần / Tháng
            </button>
            <button type="button" onclick="switchAddTab('single')" id="tabBtnSingle"
                class="flex-1 py-2.5 text-xs font-bold rounded-2xl transition text-gray-600 hover:text-gray-900 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Xếp 1 Ca Đơn Lẻ
            </button>
        </div>

        {{-- FORM HÀNG LOẠT (BULK STORE) --}}
        <div id="addFormBulk" class="p-6">
            <form action="{{ route('admin.schedules.bulk') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="schedule_type" id="bulkScheduleType" value="range">

                {{-- Chọn Ca Làm Việc --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">1. Chọn ca làm việc <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                        @foreach($shifts as $idx => $sh)
                        <label class="flex items-center gap-2.5 border border-gray-200 rounded-2xl p-3 cursor-pointer hover:border-red-400 has-[:checked]:border-red-500 has-[:checked]:bg-red-50/50 transition">
                            <input type="radio" name="shift_id" value="{{ $sh->id }}" {{ $idx === 0 ? 'checked' : '' }} class="text-red-600">
                            <div>
                                <div class="font-bold text-xs text-gray-800 flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $sh->color }}"></span>
                                    {{ $sh->name }}
                                </div>
                                <div class="text-[10px] text-gray-400">{{ substr($sh->start_time,0,5) }}-{{ substr($sh->end_time,0,5) }} (min {{ $sh->min_staff??1 }})</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Chọn Nhiều Nhân Viên --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-xs font-bold text-gray-700">2. Chọn nhân viên áp dụng <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-2 text-[11px]">
                            <button type="button" onclick="toggleAllEmployees(true)" class="text-blue-600 hover:underline font-semibold">Chọn tất cả</button>
                            <span class="text-gray-300">|</span>
                            <button type="button" onclick="toggleAllEmployees(false)" class="text-red-500 hover:underline font-semibold">Bỏ chọn</button>
                        </div>
                    </div>
                    <div class="border border-gray-200 rounded-2xl p-3 max-h-44 overflow-y-auto grid grid-cols-1 sm:grid-cols-2 gap-2 bg-gray-50/40">
                        @foreach($employees as $emp)
                        <label class="flex items-center gap-2.5 p-2 rounded-xl bg-white border border-gray-200/80 hover:border-red-300 cursor-pointer transition">
                            <input type="checkbox" name="user_ids[]" value="{{ $emp->id }}" class="bulk-user-checkbox text-red-600 rounded">
                            <img src="{{ $emp->avatar_url }}" class="w-6 h-6 rounded-full object-cover">
                            <div class="truncate">
                                <div class="font-bold text-xs text-gray-800 truncate">{{ $emp->name }}</div>
                                <div class="text-[10px] text-gray-400 truncate">{{ $emp->employee_code ?: 'NV'.$emp->id }} - {{ $emp->store->name ?? 'Tổng cty' }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Chọn Khoảng Thời Gian / Tuần / Tháng --}}
                <div class="border-t border-gray-100 pt-3">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">3. Khoảng thời gian xếp ca <span class="text-red-500">*</span></label>

                    {{-- Quick Range Buttons --}}
                    <div class="flex flex-wrap gap-1.5 mb-3">
                        <button type="button" onclick="setQuickDateRange('this_week')" class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-[11px] font-semibold">Tuần này</button>
                        <button type="button" onclick="setQuickDateRange('next_week')" class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-[11px] font-semibold">Tuần sau</button>
                        <button type="button" onclick="setQuickDateRange('this_month')" class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-[11px] font-semibold">Cả tháng này</button>
                        <button type="button" onclick="setQuickDateRange('next_month')" class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-[11px] font-semibold">Cả tháng sau</button>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <span class="block text-[11px] text-gray-500 mb-1">Từ ngày:</span>
                            <input type="date" name="start_date" id="bulkStartDate"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-red-500 outline-none">
                        </div>
                        <div>
                            <span class="block text-[11px] text-gray-500 mb-1">Đến ngày:</span>
                            <input type="date" name="end_date" id="bulkEndDate"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-red-500 outline-none">
                        </div>
                    </div>

                    {{-- Lặp theo thứ trong tuần --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-[11px] font-bold text-gray-700">Lặp theo các thứ trong tuần:</span>
                            <div class="flex gap-2 text-[10px]">
                                <button type="button" onclick="toggleWeekdays('all')" class="text-blue-600 hover:underline">Tất cả ngày</button>
                                <span class="text-gray-300">|</span>
                                <button type="button" onclick="toggleWeekdays('mon_fri')" class="text-blue-600 hover:underline">T2 - T6</button>
                                <span class="text-gray-300">|</span>
                                <button type="button" onclick="toggleWeekdays('weekend')" class="text-rose-600 hover:underline">Cuối tuần (T7, CN)</button>
                            </div>
                        </div>
                        <div class="grid grid-cols-7 gap-1 text-center">
                            @foreach([
                                ['val'=>1,'name'=>'T2'],
                                ['val'=>2,'name'=>'T3'],
                                ['val'=>3,'name'=>'T4'],
                                ['val'=>4,'name'=>'T5'],
                                ['val'=>5,'name'=>'T6'],
                                ['val'=>6,'name'=>'T7'],
                                ['val'=>7,'name'=>'CN'],
                            ] as $dw)
                            <label class="cursor-pointer border border-gray-200 rounded-xl py-2 px-1 text-xs font-bold text-gray-700 hover:border-red-400 has-[:checked]:bg-red-600 has-[:checked]:text-white has-[:checked]:border-red-600 transition block">
                                <input type="checkbox" name="days_of_week[]" value="{{ $dw['val'] }}" checked class="sr-only dow-checkbox">
                                <span>{{ $dw['name'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Ghi chú (Tùy chọn)</label>
                    <input type="text" name="note" placeholder="VD: Phân công lịch làm tháng mới..."
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-red-500 outline-none">
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeModal('addModal')"
                        class="flex-1 border border-gray-200 text-gray-600 hover:bg-gray-50 font-bold py-2.5 rounded-2xl transition text-xs">
                        Hủy
                    </button>
                    <button type="submit"
                        class="flex-1 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white font-bold py-2.5 rounded-2xl transition text-xs shadow-md shadow-red-200 flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Xác Nhận Xếp Ca Hàng Loạt
                    </button>
                </div>
            </form>
        </div>

        {{-- FORM ĐƠN LẺ (SINGLE STORE) --}}
        <div id="addFormSingle" class="p-6 hidden">
            <form action="{{ route('admin.schedules.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Nhân viên <span class="text-red-500">*</span></label>
                    <select name="user_id" id="singleUserId" required onchange="triggerConflictCheck()"
                        class="w-full border border-gray-200 rounded-2xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-red-500 outline-none">
                        <option value="">-- Chọn nhân viên --</option>
                        @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->employee_code ?: 'NV'.$emp->id }}) - {{ $emp->store->name ?? 'Tổng cty' }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Ca làm việc <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                        @foreach($shifts as $idx => $sh)
                        <label class="flex items-center gap-2 border border-gray-200 rounded-xl p-2.5 cursor-pointer hover:border-red-400 has-[:checked]:border-red-500 has-[:checked]:bg-red-50 transition">
                            <input type="radio" name="shift_id" value="{{ $sh->id }}" {{ $idx === 0 ? 'checked' : '' }} onchange="triggerConflictCheck()" class="text-red-600 single-shift-radio">
                            <div>
                                <div class="font-bold text-xs text-gray-800 flex items-center gap-1">
                                    <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $sh->color }}"></span>
                                    {{ $sh->name }}
                                </div>
                                <div class="text-[10px] text-gray-400">{{ substr($sh->start_time,0,5) }}-{{ substr($sh->end_time,0,5) }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Ngày làm việc <span class="text-red-500">*</span></label>
                    <input type="date" name="work_date" id="singleWorkDate" required onchange="triggerConflictCheck()"
                        class="w-full border border-gray-200 rounded-2xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-red-500 outline-none">
                </div>

                {{-- Hộp cảnh báo trùng lịch Live AJAX --}}
                <div id="conflictAlertBox" class="hidden bg-amber-50 border border-amber-300 text-amber-900 rounded-2xl p-3 text-xs flex items-center gap-2">
                    <span class="text-lg">⚠️</span>
                    <span id="conflictAlertMsg" class="font-semibold"></span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Ghi chú</label>
                    <input type="text" name="note" placeholder="Ghi chú (tùy chọn)..."
                        class="w-full border border-gray-200 rounded-2xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-red-500 outline-none">
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeModal('addModal')"
                        class="flex-1 border border-gray-200 text-gray-600 hover:bg-gray-50 font-bold py-2.5 rounded-2xl transition text-xs">
                        Hủy
                    </button>
                    <button type="submit"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 rounded-2xl transition text-xs shadow-md shadow-red-200 flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Xếp Ca
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- MODAL 2: SAO CHÉP LỊCH TUẦN TRƯỚC (COPY WEEK MODAL)                        --}}
{{-- ========================================================================= --}}
<div id="copyWeekModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <h3 class="text-base font-extrabold text-gray-900 flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">📋</span>
                Sao Chép Lịch Tuần
            </h3>
            <button onclick="closeModal('copyWeekModal')" class="p-2 text-gray-400 hover:text-gray-600 rounded-xl transition">✕</button>
        </div>

        <p class="text-xs text-gray-500">
            Tự động nhân bản toàn bộ ca làm việc từ tuần nguồn sang tuần đích chỉ với 1 click, giúp quản lý không phải sắp lại lịch tuần tiếp theo.
        </p>

        <form action="{{ route('admin.schedules.copy-week') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Tuần Nguồn (Tuần có sẵn lịch cần sao chép) <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-2">
                    <input type="date" name="source_monday" value="{{ $lastMonday }}" required
                        class="w-full border border-gray-200 rounded-2xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-indigo-500 outline-none">
                    <button type="button" onclick="this.form.source_monday.value='{{ $currentMonday }}'"
                        class="text-[11px] px-2.5 py-2.5 bg-gray-100 hover:bg-gray-200 rounded-xl font-bold whitespace-nowrap">
                        Tuần này
                    </button>
                </div>
                <span class="text-[10px] text-gray-400">Chọn ngày Thứ Hai của tuần nguồn</span>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Tuần Đích (Tuần cần dán lịch mới) <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-2">
                    <input type="date" name="target_monday" value="{{ $nextMonday }}" required
                        class="w-full border border-gray-200 rounded-2xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-indigo-500 outline-none">
                    <button type="button" onclick="this.form.target_monday.value='{{ $nextMonday }}'"
                        class="text-[11px] px-2.5 py-2.5 bg-gray-100 hover:bg-gray-200 rounded-xl font-bold whitespace-nowrap">
                        Tuần sau
                    </button>
                </div>
                <span class="text-[10px] text-gray-400">Chọn ngày Thứ Hai của tuần đích</span>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Áp dụng cho cửa hàng (Tùy chọn)</label>
                <select name="store_id" class="w-full border border-gray-200 rounded-2xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="">-- Toàn bộ cửa hàng --</option>
                    @foreach($stores as $st)
                        <option value="{{ $st->id }}">{{ $st->name }}</option>
                    @endforeach
                </select>
            </div>

            <label class="flex items-center gap-2.5 p-3 rounded-2xl border border-gray-200 bg-gray-50 cursor-pointer">
                <input type="checkbox" name="override" value="1" class="text-indigo-600 rounded">
                <span class="text-xs text-gray-700 font-semibold">Ghi đè (Xóa lịch cũ ở tuần đích trước khi dán)</span>
            </label>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('copyWeekModal')"
                    class="flex-1 border border-gray-200 text-gray-600 hover:bg-gray-50 font-bold py-2.5 rounded-2xl transition text-xs">
                    Hủy
                </button>
                <button type="submit"
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-2xl transition text-xs shadow-md shadow-indigo-200">
                    Bắt Đầu Sao Chép
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- MODAL 3: ĐỔI CA / CHUYỂN GIAO LÀM THAY (SWAP MODAL)                        --}}
{{-- ========================================================================= --}}
<div id="swapModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <h3 class="text-base font-extrabold text-gray-900 flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center">🔄</span>
                Đổi Ca & Phân Công Làm Thay
            </h3>
            <button onclick="closeModal('swapModal')" class="p-2 text-gray-400 hover:text-gray-600 rounded-xl transition">✕</button>
        </div>

        <div class="flex border-b border-gray-100 gap-2 p-1 bg-gray-50 rounded-2xl">
            <button type="button" onclick="switchSwapType('swap')" id="btnSwapModeSwap"
                class="flex-1 py-2 text-xs font-bold rounded-xl transition bg-white text-amber-800 shadow-sm border border-gray-200/60">
                Hoán Đổi 2 Ca (Swap)
            </button>
            <button type="button" onclick="switchSwapType('transfer')" id="btnSwapModeTransfer"
                class="flex-1 py-2 text-xs font-bold rounded-xl transition text-gray-600 hover:text-gray-900">
                Chuyển Ca Làm Thay (Cover)
            </button>
        </div>

        <form action="{{ route('admin.schedules.swap') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="swap_type" id="swapTypeInput" value="swap">

            {{-- Chế độ 1: Hoán đổi 2 ca --}}
            <div id="swapFieldsetSwap" class="space-y-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Ca của Nhân viên A <span class="text-red-500">*</span></label>
                    <select name="schedule_1_id" id="schedule1Select"
                        class="w-full border border-gray-200 rounded-2xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-amber-500 outline-none">
                        <option value="">-- Chọn ca của nhân viên thứ nhất --</option>
                        @foreach($allSchedules->sortBy('work_date') as $sch)
                            <option value="{{ $sch->id }}">
                                {{ $sch->user->name }} | {{ $sch->work_date->format('d/m/Y') }} | {{ $sch->shift->name }} ({{ substr($sch->shift->start_time,0,5) }}-{{ substr($sch->shift->end_time,0,5) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Ca của Nhân viên B <span class="text-red-500">*</span></label>
                    <select name="schedule_2_id" id="schedule2Select"
                        class="w-full border border-gray-200 rounded-2xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-amber-500 outline-none">
                        <option value="">-- Chọn ca của nhân viên thứ hai --</option>
                        @foreach($allSchedules->sortBy('work_date') as $sch)
                            <option value="{{ $sch->id }}">
                                {{ $sch->user->name }} | {{ $sch->work_date->format('d/m/Y') }} | {{ $sch->shift->name }} ({{ substr($sch->shift->start_time,0,5) }}-{{ substr($sch->shift->end_time,0,5) }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Chế độ 2: Làm thay --}}
            <div id="swapFieldsetTransfer" class="space-y-3 hidden">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Chọn ca cần chuyển giao <span class="text-red-500">*</span></label>
                    <select name="schedule_id" id="transferScheduleSelect"
                        class="w-full border border-gray-200 rounded-2xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-amber-500 outline-none">
                        <option value="">-- Chọn ca cần chuyển giao --</option>
                        @foreach($allSchedules->sortBy('work_date') as $sch)
                            <option value="{{ $sch->id }}">
                                {{ $sch->user->name }} | {{ $sch->work_date->format('d/m/Y') }} | {{ $sch->shift->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Nhân viên nhận làm thay <span class="text-red-500">*</span></label>
                    <select name="target_user_id"
                        class="w-full border border-gray-200 rounded-2xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-amber-500 outline-none">
                        <option value="">-- Chọn nhân viên nhận làm thay --</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->employee_code ?: 'NV'.$emp->id }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('swapModal')"
                    class="flex-1 border border-gray-200 text-gray-600 hover:bg-gray-50 font-bold py-2.5 rounded-2xl transition text-xs">
                    Hủy
                </button>
                <button type="submit"
                    class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-bold py-2.5 rounded-2xl transition text-xs shadow-md shadow-amber-200">
                    Xác Nhận Đổi Ca
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Modal control helpers
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.body.style.overflow = '';
}

// Add Schedule Modal
function openAddModal(date) {
    if (date) {
        document.getElementById('singleWorkDate').value = date;
        document.getElementById('bulkStartDate').value = date;
        document.getElementById('bulkEndDate').value = date;
    }
    openModal('addModal');
}

function openAddModalForUser(userId, userName) {
    document.getElementById('singleUserId').value = userId;
    switchAddTab('single');
    openModal('addModal');
}

function switchAddTab(tab) {
    var bForm = document.getElementById('addFormBulk');
    var sForm = document.getElementById('addFormSingle');
    var bBtn  = document.getElementById('tabBtnBulk');
    var sBtn  = document.getElementById('tabBtnSingle');

    if (tab === 'bulk') {
        bForm.classList.remove('hidden');
        sForm.classList.add('hidden');
        bBtn.classList.add('bg-white', 'text-red-600', 'shadow-sm', 'border', 'border-gray-200/60');
        bBtn.classList.remove('text-gray-600');
        sBtn.classList.remove('bg-white', 'text-red-600', 'shadow-sm', 'border', 'border-gray-200/60');
        sBtn.classList.add('text-gray-600');
    } else {
        sForm.classList.remove('hidden');
        bForm.classList.add('hidden');
        sBtn.classList.add('bg-white', 'text-red-600', 'shadow-sm', 'border', 'border-gray-200/60');
        sBtn.classList.remove('text-gray-600');
        bBtn.classList.remove('bg-white', 'text-red-600', 'shadow-sm', 'border', 'border-gray-200/60');
        bBtn.classList.add('text-gray-600');
    }
}

// Bulk helpers
function toggleAllEmployees(state) {
    document.querySelectorAll('.bulk-user-checkbox').forEach(cb => cb.checked = state);
}

function toggleWeekdays(mode) {
    document.querySelectorAll('.dow-checkbox').forEach(function(cb) {
        var val = parseInt(cb.value); // 1..7 (1=Mon, 7=Sun)
        if (mode === 'all') {
            cb.checked = true;
        } else if (mode === 'mon_fri') {
            cb.checked = (val >= 1 && val <= 5);
        } else if (mode === 'weekend') {
            cb.checked = (val === 6 || val === 7);
        }
    });
}

function setQuickDateRange(type) {
    var today = new Date();
    var s = new Date();
    var e = new Date();

    if (type === 'this_week') {
        // Monday of this week
        var day = today.getDay() || 7;
        s.setDate(today.getDate() - day + 1);
        e.setDate(s.getDate() + 6);
    } else if (type === 'next_week') {
        var day = today.getDay() || 7;
        s.setDate(today.getDate() - day + 8);
        e.setDate(s.getDate() + 6);
    } else if (type === 'this_month') {
        s = new Date(today.getFullYear(), today.getMonth(), 1);
        e = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    } else if (type === 'next_month') {
        s = new Date(today.getFullYear(), today.getMonth() + 1, 1);
        e = new Date(today.getFullYear(), today.getMonth() + 2, 0);
    }

    var formatDate = function(d) {
        var month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();
        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;
        return [year, month, day].join('-');
    };

    document.getElementById('bulkStartDate').value = formatDate(s);
    document.getElementById('bulkEndDate').value = formatDate(e);
}

// Live AJAX Conflict Check
function triggerConflictCheck() {
    var userId = document.getElementById('singleUserId').value;
    var workDate = document.getElementById('singleWorkDate').value;
    var shiftId = null;
    var checkedRadio = document.querySelector('.single-shift-radio:checked');
    if (checkedRadio) shiftId = checkedRadio.value;

    var box = document.getElementById('conflictAlertBox');
    var msg = document.getElementById('conflictAlertMsg');

    if (!userId || !workDate || !shiftId) {
        box.classList.add('hidden');
        return;
    }

    fetch('{{ route("admin.schedules.check-conflict") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            user_id: userId,
            work_date: workDate,
            shift_id: shiftId
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.conflict) {
            msg.textContent = data.message;
            box.classList.remove('hidden');
        } else {
            box.classList.add('hidden');
        }
    })
    .catch(err => console.error(err));
}

// Swap Modal helpers
function switchSwapType(type) {
    document.getElementById('swapTypeInput').value = type;
    var fSwap = document.getElementById('swapFieldsetSwap');
    var fTrans = document.getElementById('swapFieldsetTransfer');
    var bSwap = document.getElementById('btnSwapModeSwap');
    var bTrans = document.getElementById('btnSwapModeTransfer');

    if (type === 'swap') {
        fSwap.classList.remove('hidden');
        fTrans.classList.add('hidden');
        bSwap.classList.add('bg-white', 'text-amber-800', 'shadow-sm', 'border', 'border-gray-200/60');
        bSwap.classList.remove('text-gray-600');
        bTrans.classList.remove('bg-white', 'text-amber-800', 'shadow-sm', 'border', 'border-gray-200/60');
        bTrans.classList.add('text-gray-600');
    } else {
        fTrans.classList.remove('hidden');
        fSwap.classList.add('hidden');
        bTrans.classList.add('bg-white', 'text-amber-800', 'shadow-sm', 'border', 'border-gray-200/60');
        bTrans.classList.remove('text-gray-600');
        bSwap.classList.remove('bg-white', 'text-amber-800', 'shadow-sm', 'border', 'border-gray-200/60');
        bSwap.classList.add('text-gray-600');
    }
}

function quickSwapModal(scheduleId, userName, date, shiftName) {
    document.getElementById('schedule1Select').value = scheduleId;
    document.getElementById('transferScheduleSelect').value = scheduleId;
    openModal('swapModal');
}

// Auto init date range on modal open if empty
document.addEventListener('DOMContentLoaded', function() {
    setQuickDateRange('this_week');
});
</script>
@endpush
@endsection
