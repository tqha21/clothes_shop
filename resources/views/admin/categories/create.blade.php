@extends('admin.layouts.app')

@section('title', 'Danh Mục')
@section('page-title', 'Thêm Danh Mục Mới')
@section('page-description', 'Khởi tạo phân loại danh mục mới')

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <!-- Primary Intelligence Block -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group">
            <div class="bg-gray-800 p-6 flex items-center justify-between border-b border-white/10 relative overflow-hidden">
                <div class="absolute inset-0 bg-red-500/10 translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                <div class="relative z-10">
                    <h3 class="text-xs font-semibold text-white uppercase tracking-widest mb-1">Thông Tin Cơ Bản</h3>
                    <p class="text-xs text-gray-400 font-medium">Tên gọi và cấp bậc danh mục</p>
                </div>
                <div class="relative z-10 w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center text-white shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                </div>
            </div>

            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Name Identity -->
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest">Tên Danh Mục <span class="text-red-600">*</span></label>
                        <div class="relative">
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-900 focus:ring-2 focus:ring-red-500 focus:bg-white focus:border-red-500 transition-all outline-none"
                                   placeholder="VD: Áo Thun, Quần Jean">
                            @error('name')
                                <p class="text-[11px] font-semibold text-red-600 mt-1.5 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Hierarchy Status -->
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest">Danh Mục Cha</label>
                        <div class="relative">
                            <select name="parent_id"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-900 focus:ring-2 focus:ring-red-500 focus:bg-white focus:border-red-500 transition-all appearance-none cursor-pointer outline-none">
                                <option value="">Không có (Danh Mục Gốc)</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Asset Deployment Block -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group">
            <div class="bg-gray-800 p-6 flex items-center justify-between border-b border-white/10 relative overflow-hidden">
                <div class="absolute inset-0 bg-red-500/10 -translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                <div class="relative z-10">
                    <h3 class="text-xs font-semibold text-white uppercase tracking-widest mb-1">Hình Ảnh</h3>
                    <p class="text-xs text-gray-400 font-medium">Tải lên hình ảnh đại diện</p>
                </div>
                <div class="relative z-10 w-10 h-10 bg-gray-700 rounded-xl flex items-center justify-center text-white border border-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            
            <div class="p-8">
                <div class="relative">
                    <label class="flex flex-col items-center justify-center w-full h-80 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer bg-gray-50/50 hover:bg-white hover:border-red-400 transition-all group/upload overflow-hidden">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-10 relative z-10">
                            <div class="w-16 h-16 bg-white rounded-xl shadow-sm flex items-center justify-center mb-4 group-hover/upload:scale-105 transition-transform border border-gray-100">
                                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            </div>
                            <p class="mb-2 text-sm font-bold text-gray-800">Tải lên hình ảnh</p>
                            <p class="text-xs text-gray-500 font-medium mb-3">Kéo thả hoặc nhấn để chọn</p>
                            <span class="px-5 py-2 bg-gray-800 text-white text-xs font-semibold rounded-lg shadow-sm">Chọn Tệp</span>
                        </div>
                        <input type="file" name="image" class="hidden" accept="image/*" onchange="previewImage(event)">
                        
                        <div id="imagePreview" class="absolute inset-0 hidden bg-white">
                            <img id="preview" class="w-full h-full object-cover" alt="Preview">
                            <div class="absolute inset-0 bg-gray-900/40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                <p class="text-white text-sm font-bold">Nhấn để đổi ảnh</p>
                            </div>
                        </div>
                    </label>
                </div>
                @error('image')
                    <p class="text-[11px] font-semibold text-red-600 mt-2 ml-2">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Command Execution Bar -->
        <div class="flex items-center gap-4 pt-6">
            <button type="submit" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-gray-800 text-white px-8 py-3.5 rounded-xl font-bold text-sm hover:bg-gray-900 transition-all shadow-sm active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Lưu Danh Mục
            </button>
            <a href="{{ route('admin.categories.index') }}" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-white text-gray-600 px-8 py-3.5 rounded-xl font-bold text-sm hover:bg-gray-50 transition-all border border-gray-200 active:scale-95">
                Hủy Bỏ
            </a>
        </div>
    </form>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
