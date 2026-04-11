@extends('admin.layouts.app')

@section('title', 'Nhân Viên')
@section('page-title', 'Hồ Sơ Nhân Viên')
@section('page-description', 'Thông tin chi tiết nhân sự: ' . $employee->name)

@section('content')
<div class="max-w-4xl space-y-8">
    <!-- Focal Identity Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group">
        <div class="bg-gray-50 p-8 flex flex-col md:flex-row items-center gap-8 relative overflow-hidden border-b border-gray-100">
            
            <div class="relative z-10 w-24 h-24 bg-red-50 rounded-2xl flex items-center justify-center text-red-500 text-3xl font-bold border border-red-100 shadow-sm overflow-hidden group/avatar">
                <span class="relative z-10">{{ substr($employee->name, 0, 1) }}</span>
            </div>

            <div class="relative z-10 flex-1 text-center md:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-50 border border-red-100 mb-3 text-red-600">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                    <span class="text-[10px] font-bold uppercase tracking-wider">{{ $employee->employee_code }}</span>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $employee->name }}</h2>
                <p class="text-sm font-semibold text-gray-500">{{ $employee->position }} <span class="mx-2 text-gray-300">|</span> {{ $employee->department }}</p>
            </div>

            <div class="relative z-10 flex flex-col gap-3 w-full md:w-auto">
                <div class="flex items-center justify-between md:justify-end gap-3 px-4 py-3 bg-white rounded-xl border border-gray-200">
                    <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-widest leading-none">Trạng Thái</span>
                    @if($employee->status == 'active')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-semibold tracking-wide bg-green-50 text-green-600 border border-green-100 uppercase">
                            Đang Làm Việc
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-semibold tracking-wide bg-gray-100 text-gray-500 border border-gray-200 uppercase">
                            Đã Nghỉ Việc
                        </span>
                    @endif
                </div>
                <a href="{{ route('admin.employees.edit', $employee) }}" class="flex items-center justify-center gap-2 px-6 py-3 bg-red-600 text-white rounded-xl font-bold text-xs uppercase tracking-wide hover:bg-red-700 transition-all shadow-sm active:scale-95 group/btn border border-red-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Chỉnh Sửa Hồ Sơ
                </a>
            </div>
        </div>

        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Communication Matrix -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 border-l-2 border-red-500 pl-4 py-1">
                        <div>
                            <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Thông Tin Liên Hệ</h3>
                            <p class="text-[11px] text-gray-500 font-medium">Email và số điện thoại</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="group/item">
                            <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-1.5 px-1">Địa Chỉ Email</p>
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100 group-hover/item:border-gray-200 transition-all">
                                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-gray-500 border border-gray-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-800">{{ $employee->email }}</p>
                            </div>
                        </div>

                        <div class="group/item">
                            <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-1.5 px-1">Số Điện Thoại</p>
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100 group-hover/item:border-gray-200 transition-all">
                                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-gray-500 border border-gray-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1.01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-800">{{ $employee->phone ?? 'Chưa Cập Nhật' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Professional Protocol -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 border-l-2 border-gray-400 pl-4 py-1">
                        <div>
                            <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Thông Tin Công Việc</h3>
                            <p class="text-[11px] text-gray-500 font-medium">Bắt đầu làm việc và mức lương</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="group/item">
                            <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-1.5 px-1">Ngày Bắt Đầu</p>
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100 group-hover/item:border-gray-200 transition-all">
                                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-gray-500 border border-gray-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-800">{{ $employee->hire_date ? $employee->hire_date->format('d/m/Y') : 'Chưa Xác Định' }}</p>
                            </div>
                        </div>

                        <div class="group/item">
                            <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-1.5 px-1">Mức Lương (VNĐ)</p>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100 group-hover/item:border-gray-200 transition-all">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-gray-500 border border-gray-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <p class="text-base font-bold text-gray-800">{{ $employee->formatted_salary }}</p>
                                </div>
                                <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest">VNĐ/Tháng</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Temporal Matrix -->
            <div class="mt-12 pt-8 border-t border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-6 bg-gray-50 rounded-xl border border-gray-100 flex flex-col items-center text-center">
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-2">Ngày Tạo Hồ Sơ</p>
                        <p class="text-sm font-bold text-gray-800 mb-1">{{ $employee->created_at->format('d/m/Y') }}</p>
                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wide">{{ $employee->created_at->format('H:i') }}</p>
                    </div>

                    <div class="p-6 bg-gray-50 rounded-xl border border-gray-100 flex flex-col items-center text-center">
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-2">Cập Nhật Gần Nhất</p>
                        <p class="text-sm font-bold text-gray-800 mb-1">{{ $employee->updated_at->format('d/m/Y') }}</p>
                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wide">{{ $employee->updated_at->format('H:i') }}</p>
                    </div>

                    @if($employee->hire_date)
                    <div class="p-6 bg-red-50 rounded-xl border border-red-100 flex flex-col items-center text-center group">
                        <p class="text-[10px] font-semibold text-red-500 uppercase tracking-widest mb-2">Thời Gian Làm Việc</p>
                        <p class="text-sm font-bold text-red-700 mb-1">{{ str_replace([' years', ' year', ' months', ' month', ' days', ' day', ' hours', ' hour', ' minutes', ' minute', ' seconds', ' second', ' ago', 'before', 'after'], [' năm', ' năm', ' tháng', ' tháng', ' ngày', ' ngày', ' giờ', ' giờ', ' phút', ' phút', ' giây', ' giây', ' trước', 'trước', 'sau'], $employee->hire_date->diffForHumans(null, true)) }}</p>
                        <p class="text-[10px] text-red-400 font-semibold uppercase tracking-wide">Từ Ngày Bắt Đầu</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Control -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.employees.index') }}" class="inline-flex items-center gap-2 bg-white text-gray-600 px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-wide hover:bg-gray-50 transition-all border border-gray-200 shadow-sm active:scale-95 group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Quay Lại Danh Sách
        </a>
    </div>
</div>
@endsection
