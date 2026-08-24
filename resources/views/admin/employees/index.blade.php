@extends('admin.layouts.app')

@section('title', 'Nhân Viên')
@section('page-title', 'Quản Lý Nhân Sự')
@section('page-description', 'Danh sách nhân viên và cấp quyền truy cập')

@section('content')
<!-- Filter Bar -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 overflow-hidden relative group">
    <div class="absolute top-0 right-0 w-64 h-64 bg-red-500/5 rounded-full -mr-32 -mt-32 blur-3xl transition-all group-hover:bg-red-500/10"></div>
    
    <form method="GET" action="{{ route('admin.employees.index') }}" class="relative z-10 flex flex-col md:flex-row gap-4 items-end">
        <div class="flex-1 w-full">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-2">Tìm Kiếm Nhân Viên</label>
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Tìm theo tên, mã NV, email..." 
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-900 focus:ring-2 focus:ring-red-500 focus:bg-white focus:border-red-500 transition-all outline-none">
                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="w-full md:w-64">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-2">Trạng Thái</label>
            <div class="relative">
                <select name="status" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-900 focus:ring-2 focus:ring-red-500 focus:bg-white focus:border-red-500 transition-all appearance-none cursor-pointer outline-none">
                    <option value="">Tất Cả</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang Làm Việc</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Đã Nghỉ Việc</option>
                </select>
                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </div>
        
        <div class="flex gap-3 w-full md:w-auto">
            <button type="submit" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-gray-800 text-white px-6 py-3 rounded-xl font-semibold text-xs uppercase tracking-wider hover:bg-gray-900 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Tìm Kiếm
            </button>
            
            @if(request('search') || request('status'))
            <a href="{{ route('admin.employees.index') }}" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-red-50 text-red-600 px-6 py-3 rounded-xl font-semibold text-xs uppercase tracking-wider hover:bg-red-100 transition-all border border-red-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Hủy Lọc
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Add Button -->
<div class="mb-8 flex justify-between items-center">
    <h3 class="text-sm font-bold text-gray-800 flex items-center gap-3">
        <span class="w-8 h-[2px] bg-red-500 rounded-full"></span>
        Danh Sách Nhân Viên
    </h3>
    <a href="{{ route('admin.employees.create') }}" class="inline-flex items-center gap-2 bg-red-600 text-white px-5 py-2.5 rounded-xl font-semibold text-xs uppercase tracking-wide hover:bg-red-700 transition-all shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        Thêm Nhân Viên Mới
    </a>
</div>

<!-- Employees Table -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Niên Giám</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Hồ Sơ Nhân Sự</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Tài Khoản</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Chức Danh</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Phòng Ban</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Cửa Hàng</th>
                    <th class="px-6 py-4 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Trạng Thái</th>
                    <th class="px-6 py-4 text-center text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Thao Tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($employees as $employee)
                <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                    <td class="px-6 py-4">
                        <span class="font-bold text-gray-800 group-hover:text-red-500 transition-colors">{{ $employee->employee_code }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-700 text-sm font-bold border border-gray-200">
                                {{ substr($employee->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">{{ $employee->name }}</p>
                                <p class="text-[11px] text-gray-500">{{ $employee->phone ?? 'Chưa cập nhật' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-semibold text-gray-600">{{ $employee->email }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-semibold text-gray-800">{{ $employee->position }}</span>
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-500">
                        {{ $employee->department }}
                    </td>
                    <td class="px-6 py-4">
                        @if($employee->store)
                            <span class="font-semibold text-gray-800">{{ $employee->store->name }}</span>
                        @else
                            <span class="text-xs italic text-gray-400">Chưa liên kết</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($employee->status == 'active')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-semibold tracking-wide bg-green-50 text-green-600 border border-green-100">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                Làm Việc
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-semibold tracking-wide bg-gray-100 text-gray-500 border border-gray-200">
                                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></span>
                                Đã Nghỉ
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.employees.show', $employee) }}" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors border border-blue-100" title="Chi tiết">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            <a href="{{ route('admin.employees.edit', $employee) }}" class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition-colors border border-yellow-100" title="Sửa">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            @if($employee->status == 'active')
                            <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}" class="inline btn-delete-form" data-message="Bạn có chắc chắn muốn vô hiệu hóa nhân viên này?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors border border-red-100" title="Vô hiệu hóa">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-12 text-center text-gray-500 font-medium text-sm">Chưa có dữ liệu nhân viên.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($employees->hasPages())
<div class="mt-8 flex justify-center">
    {{ $employees->links() }}
</div>
@endif

<style>
    .pagination { @apply flex gap-2; }
    .page-item { @apply rounded-lg border border-gray-100 bg-white shadow-sm transition-all; }
    .page-link { @apply flex items-center justify-center w-9 h-9 text-xs font-semibold text-gray-700; }
    .page-item.active { @apply bg-red-600 border-red-600 shadow-md; }
    .page-item.active .page-link { @apply text-white; }
    .page-item:hover:not(.active) { @apply bg-gray-50 border-gray-200 transform -translate-y-0.5; }
</style>
@endsection

