@extends('admin.layouts.app')

@section('title', 'Quản Lý Ca Làm Việc')
@section('page-title', 'Quản Lý Ca Làm Việc')
@section('page-description', 'Định nghĩa và quản lý các ca làm việc')

@section('content')
<div class="space-y-6">

    {{-- Header Actions --}}
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500">Tổng cộng <span class="font-semibold text-gray-800">{{ $shifts->count() }}</span> ca làm việc</p>
        </div>
        <button onclick="openModal('createModal')"
            class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Thêm Ca Mới
        </button>
    </div>

    {{-- Shifts Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-base font-semibold text-gray-800">Danh Sách Ca Làm Việc</h3>
        </div>

        @if($shifts->isEmpty())
        <div class="py-16 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-gray-500 font-medium">Chưa có ca làm việc nào</p>
            <p class="text-sm text-gray-400 mt-1">Nhấn "Thêm Ca Mới" để tạo ca đầu tiên</p>
        </div>
        @else
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50/80 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-3 text-left">Ca Làm Việc</th>
                    <th class="px-6 py-3 text-left">Thời Gian</th>
                    <th class="px-6 py-3 text-left">Màu Sắc</th>
                    <th class="px-6 py-3 text-left">Nhân Sự Tối Thiểu</th>
                    <th class="px-6 py-3 text-left">Số Lịch Đã Xếp</th>
                    <th class="px-6 py-3 text-right">Thao Tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($shifts as $shift)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-10 rounded-full" style="background-color: {{ $shift->color }}"></div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $shift->name }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">ID #{{ $shift->id }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium">{{ substr($shift->start_time, 0, 5) }}</span>
                            <span class="text-gray-400">&#8594;</span>
                            <span class="font-medium">{{ substr($shift->end_time, 0, 5) }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg border border-gray-200 shadow-sm" style="background-color: {{ $shift->color }}"></div>
                            <span class="text-sm font-mono text-gray-500">{{ $shift->color }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Tối thiểu {{ $shift->min_staff ?? 1 }} người
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1 px-3 py-1 text-sm font-semibold rounded-full bg-blue-50 text-blue-700">
                            {{ $shift->shiftSchedules()->count() }} lịch
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="openEditModal({{ $shift->id }}, '{{ addslashes($shift->name) }}', '{{ substr($shift->start_time, 0, 5) }}', '{{ substr($shift->end_time, 0, 5) }}', '{{ $shift->color }}', {{ $shift->min_staff ?? 1 }})"
                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Chỉnh sửa">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <form action="{{ route('admin.shifts.destroy', $shift) }}" method="POST"
                                class="btn-delete-form" data-message="Bạn có chắc muốn xóa ca '{{ $shift->name }}'?">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Xóa">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    {{-- Quick tip --}}
    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-start gap-3">
        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
            <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-blue-800">Lưu ý</p>
            <p class="text-xs text-blue-600 mt-0.5">Không thể xóa ca làm việc đã được dùng trong lịch xếp ca. Vui lòng xóa các lịch liên quan trước.</p>
        </div>
    </div>
</div>

{{-- ======================= CREATE MODAL ======================= --}}
<div id="createModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeModal('createModal')"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">Thêm Ca Làm Việc Mới</h3>
                <button onclick="closeModal('createModal')" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form action="{{ route('admin.shifts.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tên Ca <span class="text-red-500">*</span></label>
                    <input type="text" name="name" placeholder="VD: Ca Sáng, Ca Chiều, Ca Tối..."
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all"
                        required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Giờ Bắt Đầu <span class="text-red-500">*</span></label>
                        <input type="time" name="start_time"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Giờ Kết Thúc <span class="text-red-500">*</span></label>
                        <input type="time" name="end_time"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all"
                            required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Màu Hiển Thị <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="color" value="#3B82F6"
                                class="w-12 h-10 border border-gray-200 rounded-lg cursor-pointer p-1">
                            <span class="text-xs text-gray-500">Màu trên lịch</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nhân sự tối thiểu <span class="text-red-500">*</span></label>
                        <input type="number" name="min_staff" value="1" min="1" max="50"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all"
                            required placeholder="VD: 2 người">
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal('createModal')"
                        class="px-5 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                        Hủy
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow transition-all">
                        Tạo Ca
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ======================= EDIT MODAL ======================= --}}
<div id="editModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeModal('editModal')"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">Chỉnh Sửa Ca Làm Việc</h3>
                <button onclick="closeModal('editModal')" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="editForm" action="" method="POST" class="p-6 space-y-5">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tên Ca <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="editName"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all"
                        required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Giờ Bắt Đầu <span class="text-red-500">*</span></label>
                        <input type="time" name="start_time" id="editStartTime"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Giờ Kết Thúc <span class="text-red-500">*</span></label>
                        <input type="time" name="end_time" id="editEndTime"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all"
                            required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Màu Hiển Thị <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="color" id="editColor"
                                class="w-12 h-10 border border-gray-200 rounded-lg cursor-pointer p-1">
                            <span class="text-xs text-gray-500">Màu trên lịch</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nhân sự tối thiểu <span class="text-red-500">*</span></label>
                        <input type="number" name="min_staff" id="editMinStaff" min="1" max="50"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all"
                            required>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal('editModal')"
                        class="px-5 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                        Hủy
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow transition-all">
                        Lưu Thay Đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.body.style.overflow = '';
}
function openEditModal(id, name, startTime, endTime, color, minStaff) {
    document.getElementById('editForm').action = '/admin/shifts/' + id;
    document.getElementById('editName').value = name;
    document.getElementById('editStartTime').value = startTime;
    document.getElementById('editEndTime').value = endTime;
    document.getElementById('editColor').value = color;
    document.getElementById('editMinStaff').value = minStaff || 1;
    openModal('editModal');
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal('createModal');
        closeModal('editModal');
    }
});
@if($errors->any())
    openModal('createModal');
@endif
</script>
@endpush
