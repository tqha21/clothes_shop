@extends('admin.layouts.app')

@section('title', 'Lịch Xếp Ca - Tháng ' . $month . '/' . $year)
@section('page-title', 'Lịch Xếp Ca Làm Việc')
@section('page-description', 'Quản lý lịch làm việc nhân viên theo tháng')

@section('content')
<div class="p-6">

    {{-- Alerts --}}
    @if(session('success'))
        <div class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-xl px-5 py-3.5">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-5 py-3.5">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-medium text-sm">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Header Controls --}}
    <div class="flex flex-col lg:flex-row items-start lg:items-center gap-4 mb-6">

        {{-- Month Navigation --}}
        <div class="flex items-center gap-2 bg-white rounded-2xl border border-gray-100 shadow-sm px-2 py-2">
            <a href="{{ route('admin.schedules.index', array_merge(request()->except(['year','month']), ['year'=>$prevMonth->year,'month'=>$prevMonth->month])) }}"
               class="p-2 hover:bg-gray-100 rounded-xl transition-colors text-gray-500 hover:text-gray-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div class="px-4 min-w-[160px] text-center">
                <div class="font-bold text-gray-800 text-lg">Tháng {{ $month }}</div>
                <div class="text-xs text-gray-500 font-medium">Năm {{ $year }}</div>
            </div>
            <a href="{{ route('admin.schedules.index', array_merge(request()->except(['year','month']), ['year'=>$nextMonth->year,'month'=>$nextMonth->month])) }}"
               class="p-2 hover:bg-gray-100 rounded-xl transition-colors text-gray-500 hover:text-gray-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('admin.schedules.index') }}" class="flex flex-wrap items-center gap-2 flex-1">
            <input type="hidden" name="year" value="{{ $year }}">
            <input type="hidden" name="month" value="{{ $month }}">

            <select name="store_id" onchange="this.form.submit()"
                class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-700 focus:ring-2 focus:ring-red-500 outline-none bg-white shadow-sm">
                <option value="">-- Tất cả cửa hàng --</option>
                @foreach($stores as $store)
                    <option value="{{ $store->id }}" {{ $storeId == $store->id ? 'selected' : '' }}>
                        {{ $store->name }}
                    </option>
                @endforeach
            </select>

            <select name="employee_id" onchange="this.form.submit()"
                class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-700 focus:ring-2 focus:ring-red-500 outline-none bg-white shadow-sm">
                <option value="">-- Tất cả nhân viên --</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" {{ $employeeId == $emp->id ? 'selected' : '' }}>
                        {{ $emp->name }} @if($emp->employee_code)({{ $emp->employee_code }})@endif
                    </option>
                @endforeach
            </select>

            @if($storeId || $employeeId)
            <a href="{{ route('admin.schedules.index', ['year'=>$year,'month'=>$month]) }}"
               class="text-sm text-gray-500 hover:text-red-600 px-3 py-2.5 border border-gray-200 rounded-xl bg-white hover:border-red-300 transition-colors">
                ✕ Bỏ lọc
            </a>
            @endif
        </form>

        {{-- Buttons --}}
        <div class="flex gap-2 ml-auto">
            <a href="{{ route('admin.shifts.index') }}"
               class="flex items-center gap-2 px-4 py-2.5 border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors bg-white shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Quản lý Ca
            </a>
            <button onclick="openAddModal()"
                class="flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Xếp Ca
            </button>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="text-2xl font-black text-gray-800">{{ $totalSchedules }}</div>
            <div class="text-xs font-medium text-gray-500 mt-1">Tổng ca trong tháng</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="text-2xl font-black text-gray-800">{{ $totalEmployees }}</div>
            <div class="text-xs font-medium text-gray-500 mt-1">Nhân viên có lịch</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="text-2xl font-black text-gray-800">{{ $shifts->count() }}</div>
            <div class="text-xs font-medium text-gray-500 mt-1">Loại ca làm việc</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="text-2xl font-black text-gray-800">{{ $endOfMonth->day }}</div>
            <div class="text-xs font-medium text-gray-500 mt-1">Ngày trong tháng</div>
        </div>
    </div>

    {{-- Shift Legend --}}
    <div class="flex flex-wrap gap-2 mb-4">
        @foreach($shifts as $shift)
        <div class="flex items-center gap-2 bg-white border border-gray-100 rounded-xl px-3 py-1.5 shadow-sm">
            <span class="w-3 h-3 rounded-full flex-shrink-0" style="background-color: {{ $shift->color }}"></span>
            <span class="text-xs font-semibold text-gray-700">{{ $shift->name }}</span>
            <span class="text-xs text-gray-400">{{ substr($shift->start_time,0,5) }}-{{ substr($shift->end_time,0,5) }}</span>
        </div>
        @endforeach
    </div>

    {{-- Calendar --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        {{-- Day Headers --}}
        <div class="grid grid-cols-7 border-b border-gray-100">
            @foreach(['T2','T3','T4','T5','T6','T7','CN'] as $day)
            <div class="py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider
                {{ $day === 'T7' || $day === 'CN' ? 'text-red-400' : '' }}">
                {{ $day }}
            </div>
            @endforeach
        </div>

        {{-- Calendar Grid --}}
        <div class="grid grid-cols-7">
            @foreach($calendarDays as $i => $day)
            @php
                $dateKey = $day['date']->format('Y-m-d');
                $daySchedules = $schedules[$dateKey] ?? collect();
            @endphp
            <div class="min-h-[120px] border-b border-r border-gray-50 p-2 relative
                {{ !$day['isCurrentMonth'] ? 'bg-gray-50/50' : '' }}
                {{ $day['isToday'] ? 'bg-red-50/50' : '' }}
                {{ $day['isWeekend'] ? 'bg-orange-50/20' : '' }}"
                id="day-{{ $dateKey }}">

                {{-- Date number --}}
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-bold leading-none
                        {{ !$day['isCurrentMonth'] ? 'text-gray-300' : '' }}
                        {{ $day['isToday'] ? 'bg-red-600 text-white w-7 h-7 rounded-full flex items-center justify-center text-xs' : 'text-gray-700' }}
                        {{ $day['isWeekend'] && !$day['isToday'] ? 'text-red-500' : '' }}">
                        {{ $day['date']->day }}
                    </span>
                    @if($day['isCurrentMonth'] && $daySchedules->isNotEmpty())
                    <span class="text-xs text-gray-400 font-medium">{{ $daySchedules->count() }}</span>
                    @endif
                </div>

                {{-- Schedules for this day --}}
                <div class="space-y-0.5">
                    @foreach($daySchedules->take(3) as $schedule)
                    <div class="group relative flex items-center gap-1 rounded-md px-1.5 py-0.5 cursor-pointer text-white text-[10px] font-semibold leading-tight"
                        style="background-color: {{ $schedule->shift->color }}"
                        title="{{ $schedule->user->name }} - {{ $schedule->shift->name }} ({{ $schedule->status_label }})">
                        <span class="truncate flex-1">{{ $schedule->user->name }}</span>
                        {{-- Delete button on hover --}}
                        <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST"
                            class="opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0"
                            onsubmit="return confirm('Xóa lịch ca này?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-white/80 hover:text-white">✕</button>
                        </form>
                    </div>
                    @endforeach
                    @if($daySchedules->count() > 3)
                    <div class="text-[10px] text-gray-400 font-medium px-1.5">
                        +{{ $daySchedules->count() - 3 }} thêm...
                    </div>
                    @endif
                </div>

                {{-- Quick add button --}}
                @if($day['isCurrentMonth'])
                <button onclick="openAddModal('{{ $dateKey }}')"
                    class="absolute bottom-1 right-1 w-5 h-5 bg-gray-200 hover:bg-red-500 text-gray-500 hover:text-white rounded-full text-xs flex items-center justify-center transition-colors opacity-0 hover:opacity-100 group-hover:opacity-100"
                    title="Thêm ca ngày này">+</button>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- Schedule List (below calendar) --}}
    @if($totalSchedules > 0)
    <div class="mt-6 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800">Chi tiết lịch tháng {{ $month }}/{{ $year }}</h3>
            <span class="text-sm text-gray-500">{{ $totalSchedules }} ca</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">Nhân viên</th>
                        <th class="px-6 py-3 text-left font-semibold">Ca làm việc</th>
                        <th class="px-6 py-3 text-left font-semibold">Ngày làm</th>
                        <th class="px-6 py-3 text-left font-semibold">Trạng thái</th>
                        <th class="px-6 py-3 text-left font-semibold">Ghi chú</th>
                        <th class="px-6 py-3 text-right font-semibold">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($schedules->flatten()->sortBy('work_date') as $schedule)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2">
                                <img src="{{ $schedule->user->avatar_url }}" class="w-7 h-7 rounded-full">
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $schedule->user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $schedule->user->employee_code }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: {{ $schedule->shift->color }}"></span>
                                <span class="font-medium text-gray-700">{{ $schedule->shift->name }}</span>
                                <span class="text-xs text-gray-400">{{ $schedule->shift->time_range }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3 font-medium text-gray-700">
                            {{ $schedule->work_date->format('d/m/Y') }}
                            <span class="text-xs text-gray-400 ml-1">({{ $schedule->work_date->translatedFormat('l') }})</span>
                        </td>
                        <td class="px-6 py-3">
                            <form action="{{ route('admin.schedules.update', $schedule) }}" method="POST">
                                @csrf @method('PUT')
                                <select name="status" onchange="this.form.submit()"
                                    class="text-xs px-2 py-1 rounded-lg border-0 font-semibold cursor-pointer {{ $schedule->status_color }}">
                                    <option value="scheduled" {{ $schedule->status === 'scheduled' ? 'selected' : '' }}>Đã xếp</option>
                                    <option value="confirmed" {{ $schedule->status === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                    <option value="absent" {{ $schedule->status === 'absent' ? 'selected' : '' }}>Vắng mặt</option>
                                    <option value="completed" {{ $schedule->status === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                </select>
                                <input type="hidden" name="note" value="{{ $schedule->note }}">
                            </form>
                        </td>
                        <td class="px-6 py-3 text-gray-500 text-xs">{{ $schedule->note ?: '—' }}</td>
                        <td class="px-6 py-3 text-right">
                            <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST"
                                onsubmit="return confirm('Xóa lịch ca này?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

{{-- Add Schedule Modal --}}
<div id="addModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 sticky top-0 bg-white">
            <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Xếp Ca Làm Việc
            </h3>
            <button onclick="closeAddModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Tab switch: Single / Bulk --}}
        <div class="flex border-b border-gray-100">
            <button onclick="showTab('single')" id="tabSingle"
                class="flex-1 py-3 text-sm font-semibold transition-colors border-b-2 border-red-600 text-red-600">
                Xếp 1 ngày
            </button>
            <button onclick="showTab('bulk')" id="tabBulk"
                class="flex-1 py-3 text-sm font-semibold transition-colors border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                Xếp nhiều ngày
            </button>
        </div>

        {{-- Single Add --}}
        <div id="formSingle" class="p-6">
            <form action="{{ route('admin.schedules.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nhân viên <span class="text-red-500">*</span></label>
                    <select name="user_id" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500 outline-none transition">
                        <option value="">-- Chọn nhân viên --</option>
                        @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }} @if($emp->employee_code)({{ $emp->employee_code }})@endif @if($emp->store)- {{ $emp->store->name }}@endif</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ca làm việc <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 gap-2">
                        @foreach($shifts as $shift)
                        <label class="flex items-center gap-3 border border-gray-200 rounded-xl p-3 cursor-pointer hover:border-red-300 has-[:checked]:border-red-500 has-[:checked]:bg-red-50 transition-all">
                            <input type="radio" name="shift_id" value="{{ $shift->id }}" class="text-red-600">
                            <span class="w-4 h-4 rounded-full flex-shrink-0" style="background-color: {{ $shift->color }}"></span>
                            <div>
                                <div class="font-semibold text-sm text-gray-800">{{ $shift->name }}</div>
                                <div class="text-xs text-gray-500">{{ substr($shift->start_time,0,5) }} – {{ substr($shift->end_time,0,5) }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ngày làm <span class="text-red-500">*</span></label>
                    <input type="date" name="work_date" id="singleDate" required
                        min="{{ $startOfMonth->format('Y-m-d') }}" max="{{ $endOfMonth->format('Y-m-d') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ghi chú</label>
                    <input type="text" name="note" placeholder="VD: Ca thêm do thiếu nhân sự..."
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500 outline-none transition">
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeAddModal()"
                        class="flex-1 border border-gray-200 text-gray-600 hover:bg-gray-50 font-semibold py-3 rounded-xl transition-colors text-sm">
                        Hủy
                    </button>
                    <button type="submit"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-xl transition-colors text-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Xếp ca
                    </button>
                </div>
            </form>
        </div>

        {{-- Bulk Add --}}
        <div id="formBulk" class="p-6 hidden">
            <form action="{{ route('admin.schedules.bulk') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nhân viên <span class="text-red-500">*</span></label>
                    <select name="user_id" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500 outline-none transition">
                        <option value="">-- Chọn nhân viên --</option>
                        @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }} @if($emp->employee_code)({{ $emp->employee_code }})@endif</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ca làm việc <span class="text-red-500">*</span></label>
                    <select name="shift_id" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500 outline-none transition">
                        <option value="">-- Chọn ca --</option>
                        @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}">{{ $shift->name }} ({{ substr($shift->start_time,0,5) }}-{{ substr($shift->end_time,0,5) }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Chọn các ngày <span class="text-red-500">*</span>
                        <span class="text-xs text-gray-400 font-normal ml-1">(click để chọn/bỏ chọn)</span>
                    </label>
                    {{-- Mini calendar for bulk day selection --}}
                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                        <div class="bg-gray-50 grid grid-cols-7 text-center text-xs font-bold text-gray-500 py-2">
                            @foreach(['T2','T3','T4','T5','T6','T7','CN'] as $d)
                            <div>{{ $d }}</div>
                            @endforeach
                        </div>
                        <div class="grid grid-cols-7">
                            @foreach($calendarDays as $day)
                            @php $dKey = $day['date']->format('Y-m-d'); @endphp
                            @if($day['isCurrentMonth'])
                            <label class="relative aspect-square flex items-center justify-center cursor-pointer group">
                                <input type="checkbox" name="work_dates[]" value="{{ $dKey }}"
                                    class="sr-only peer" {{ $day['isToday'] ? 'checked' : '' }}>
                                <span class="w-8 h-8 flex items-center justify-center text-sm rounded-full
                                    peer-checked:bg-red-600 peer-checked:text-white
                                    group-hover:bg-red-100 text-gray-700
                                    {{ $day['isWeekend'] ? 'text-red-500' : '' }}
                                    {{ $day['isToday'] ? 'ring-2 ring-red-600' : '' }}
                                    transition-colors font-medium">
                                    {{ $day['date']->day }}
                                </span>
                            </label>
                            @else
                            <div class="aspect-square"></div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="mt-2 flex gap-2">
                        <button type="button" onclick="selectAllWeekdays()" class="text-xs text-blue-600 hover:underline">Chọn T2-T6</button>
                        <span class="text-gray-300">|</span>
                        <button type="button" onclick="selectAll()" class="text-xs text-blue-600 hover:underline">Chọn tất cả</button>
                        <span class="text-gray-300">|</span>
                        <button type="button" onclick="deselectAll()" class="text-xs text-red-500 hover:underline">Bỏ chọn</button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ghi chú</label>
                    <input type="text" name="note" placeholder="Ghi chú cho tất cả các ngày..."
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500 outline-none transition">
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeAddModal()"
                        class="flex-1 border border-gray-200 text-gray-600 hover:bg-gray-50 font-semibold py-3 rounded-xl transition-colors text-sm">
                        Hủy
                    </button>
                    <button type="submit"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-xl transition-colors text-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Xếp nhiều ca
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddModal(date) {
    if (date) {
        var input = document.getElementById('singleDate');
        if (input) input.value = date;
        // Also check in bulk
        var checkboxes = document.querySelectorAll('#formBulk input[type=checkbox]');
        checkboxes.forEach(cb => cb.checked = (cb.value === date));
    }
    document.getElementById('addModal').classList.remove('hidden');
}
function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
}
function showTab(tab) {
    var single = document.getElementById('formSingle');
    var bulk = document.getElementById('formBulk');
    var tabS = document.getElementById('tabSingle');
    var tabB = document.getElementById('tabBulk');
    if (tab === 'single') {
        single.classList.remove('hidden');
        bulk.classList.add('hidden');
        tabS.classList.add('border-red-600','text-red-600');
        tabS.classList.remove('border-transparent','text-gray-500');
        tabB.classList.remove('border-red-600','text-red-600');
        tabB.classList.add('border-transparent','text-gray-500');
    } else {
        bulk.classList.remove('hidden');
        single.classList.add('hidden');
        tabB.classList.add('border-red-600','text-red-600');
        tabB.classList.remove('border-transparent','text-gray-500');
        tabS.classList.remove('border-red-600','text-red-600');
        tabS.classList.add('border-transparent','text-gray-500');
    }
}
function selectAllWeekdays() {
    var checkboxes = document.querySelectorAll('#formBulk input[type=checkbox]');
    checkboxes.forEach(function(cb) {
        var date = new Date(cb.value);
        var dow = date.getDay(); // 0=Sun, 1=Mon, ..., 5=Fri, 6=Sat
        cb.checked = (dow >= 1 && dow <= 5);
    });
}
function selectAll() {
    document.querySelectorAll('#formBulk input[type=checkbox]').forEach(cb => cb.checked = true);
}
function deselectAll() {
    document.querySelectorAll('#formBulk input[type=checkbox]').forEach(cb => cb.checked = false);
}
document.getElementById('addModal').addEventListener('click', function(e) {
    if (e.target === this) closeAddModal();
});
// Hover reveal delete on calendar entries
document.querySelectorAll('[id^="day-"] .group').forEach(function(el) {
    // handled by CSS group-hover
});
</script>
@endsection

