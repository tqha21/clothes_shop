@extends('admin.layouts.app')

@section('page-title', 'Quản Lý Ca Làm Việc & Thu Ngân')
@section('page-description', 'Mở/đóng ca bán hàng, chấm công, đối soát tiền mặt và bàn giao ca')

@section('content')
<div class="space-y-6">

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-sm animate-fadeIn">
        <div class="flex items-center">
            <svg class="h-5 w-5 text-emerald-500 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-xl shadow-sm">
        <div class="flex items-center">
            <svg class="h-5 w-5 text-rose-500 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-semibold text-rose-800">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Top Row: Today's Schedule & Attendance -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Card Chấm Công Hôm Nay -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Lịch ca & Chấm công hôm nay</span>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded bg-slate-100 text-slate-600">{{ now()->format('d/m/Y') }}</span>
                </div>

                @if($todaySchedule)
                    <div class="space-y-2">
                        <div class="flex items-center gap-2.5">
                            <span class="w-3 h-3 rounded-full" style="background-color: {{ $todaySchedule->shift->color ?? '#6366f1' }}"></span>
                            <span class="font-bold text-base text-slate-800">{{ $todaySchedule->shift->name ?? 'Ca làm việc' }}</span>
                            <span class="text-xs font-semibold text-slate-500">({{ $todaySchedule->shift->time_range ?? '' }})</span>
                        </div>
                        <div class="text-xs text-slate-500">
                            Trạng thái: <span class="font-semibold text-slate-700">{{ $todaySchedule->status_label }}</span>
                        </div>
                        <div class="text-xs text-slate-500">
                            Giờ vào: <strong class="text-slate-800">{{ $todaySchedule->check_in_at ? $todaySchedule->check_in_at->format('H:i:s') : 'Chưa chấm công' }}</strong>
                        </div>
                        <div class="text-xs text-slate-500">
                            Giờ ra: <strong class="text-slate-800">{{ $todaySchedule->check_out_at ? $todaySchedule->check_out_at->format('H:i:s') : 'Chưa chấm công' }}</strong>
                        </div>
                    </div>
                @else
                    <div class="py-4 text-center text-slate-400 text-xs">
                        Hôm nay bạn chưa được phân ca trong lịch biểu.
                    </div>
                @endif
            </div>

            <!-- Attendance Buttons -->
            @if($todaySchedule)
            <div class="pt-4 border-t border-slate-100 flex gap-2">
                @if(!$todaySchedule->check_in_at)
                <form method="POST" action="{{ route('staff.shifts.attendance') }}" class="flex-1">
                    @csrf
                    <input type="hidden" name="schedule_id" value="{{ $todaySchedule->id }}">
                    <input type="hidden" name="action" value="check_in">
                    <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                        📍 Chấm công Vào Ca
                    </button>
                </form>
                @elseif(!$todaySchedule->check_out_at)
                <form method="POST" action="{{ route('staff.shifts.attendance') }}" class="flex-1">
                    @csrf
                    <input type="hidden" name="schedule_id" value="{{ $todaySchedule->id }}">
                    <input type="hidden" name="action" value="check_out">
                    <button type="submit" class="w-full py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition shadow-sm">
                        🏁 Chấm công Ra Ca
                    </button>
                </form>
                @else
                <div class="w-full text-center py-2 text-xs font-bold text-emerald-600 bg-emerald-50 rounded-xl">
                    ✓ Đã hoàn thành chấm công ngày hôm nay
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Card Trạng Thái Ca Bán Hàng (Open vs Not Open) -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            @if($currentSession)
                <!-- Phiên ca đang mở -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-emerald-500 animate-ping"></span>
                            <h3 class="font-extrabold text-base text-slate-900">PHIÊN CA BÁN HÀNG ĐANG MỞ: {{ $currentSession->code }}</h3>
                        </div>
                        <span class="text-xs bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold px-3 py-1 rounded-full">
                            Mở lúc {{ $currentSession->opened_at->format('H:i d/m/Y') }}
                        </span>
                    </div>

                    <!-- Shift Money Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs mb-4">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="text-slate-400 font-medium">Tiền đầu ca</div>
                            <div class="text-sm font-extrabold text-slate-800 mt-1">{{ number_format($currentSession->opening_cash) }}đ</div>
                        </div>
                        <div class="p-3 bg-emerald-50/60 rounded-xl border border-emerald-100">
                            <div class="text-emerald-700 font-medium">Thu tiền mặt (bán hàng)</div>
                            <div class="text-sm font-extrabold text-emerald-800 mt-1">+{{ number_format($currentSession->cash_sales) }}đ</div>
                        </div>
                        <div class="p-3 bg-indigo-50/60 rounded-xl border border-indigo-100">
                            <div class="text-indigo-700 font-medium">Thẻ / QR / Ví ĐT</div>
                            <div class="text-sm font-extrabold text-indigo-800 mt-1">{{ number_format($currentSession->non_cash_sales) }}đ</div>
                        </div>
                        <div class="p-3 bg-amber-50/60 rounded-xl border border-amber-100">
                            <div class="text-amber-700 font-medium">Số đơn đã tạo</div>
                            <div class="text-sm font-extrabold text-amber-800 mt-1">{{ $currentSession->orders_count }} đơn</div>
                        </div>
                    </div>

                    <!-- Expected Cash Highlight -->
                    <div class="p-4 bg-gradient-to-r from-slate-900 to-slate-800 text-white rounded-xl flex items-center justify-between shadow-md">
                        <div>
                            <div class="text-xs text-slate-400 font-medium">TIỀN MẶT DỰ KIẾN HIỆN TẠI TRONG KÉT:</div>
                            <div class="text-2xl font-black text-emerald-400 mt-0.5">
                                {{ number_format($currentSession->current_expected_cash) }}đ
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="openCashMovementModal('in')" class="px-3 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold transition">
                                + Thu khác
                            </button>
                            <button onclick="openCashMovementModal('out')" class="px-3 py-2 bg-rose-600 hover:bg-rose-500 text-white rounded-lg text-xs font-bold transition">
                                - Chi khác / Nộp
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('pos.index') }}" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl transition shadow-md shadow-rose-600/20 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Mở Màn Hình POS Bán Hàng
                    </a>

                    <button onclick="openCloseShiftModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl transition">
                        🔒 Đóng Ca & Bàn Giao Tiền
                    </button>
                </div>
            @else
                <!-- Chưa mở ca: Form mở ca mới -->
                <div>
                    <h3 class="font-extrabold text-base text-slate-900 mb-1">MỞ CA BÁN HÀNG MỚI (OPEN SHIFT)</h3>
                    <p class="text-xs text-slate-500 mb-4">Vui lòng kiểm đếm tiền mặt đầu ca trong két trước khi kích hoạt phiên bán hàng.</p>

                    <form method="POST" action="{{ route('staff.shifts.open') }}" class="space-y-3.5">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Cửa hàng làm việc:</label>
                                <select name="store_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl outline-none font-semibold">
                                    @foreach($stores as $st)
                                        @if(auth()->user()->canAccessStore($st->id))
                                        <option value="{{ $st->id }}" {{ auth()->user()->store_id === $st->id ? 'selected' : '' }}>{{ $st->name }} ({{ $st->city }})</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Tiền mặt đầu ca (VNĐ):</label>
                                <input type="number" name="opening_cash" value="1000000" min="0" step="1000" required
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl font-mono font-bold text-slate-900 outline-none focus:border-rose-500">
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1 text-xs">Ghi chú mở ca (tùy chọn):</label>
                            <input type="text" name="open_note" placeholder="Ví dụ: Nhận ca từ bạn Lan, tiền mặt 1.000.000đ gồm 10 tờ 100k..."
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs outline-none focus:border-rose-500">
                        </div>

                        @if($todaySchedule)
                        <input type="hidden" name="shift_schedule_id" value="{{ $todaySchedule->id }}">
                        @endif

                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-rose-600/30">
                                🚀 Mở Ca & Bắt Đầu Bán Hàng
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- History: Recent Sessions -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-extrabold text-sm text-slate-900">Lịch Sử Các Phiên Ca Bán Hàng Gần Đây</h4>
            <span class="text-xs text-slate-500">Hiển thị 10 phiên gần nhất</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider text-[11px] border-b border-slate-100">
                        <th class="px-5 py-3">Mã ca</th>
                        <th class="px-5 py-3">Cửa hàng</th>
                        <th class="px-5 py-3">Thời gian mở - đóng</th>
                        <th class="px-5 py-3 text-right">Tiền đầu ca</th>
                        <th class="px-5 py-3 text-right">Thu tiền mặt</th>
                        <th class="px-5 py-3 text-right">Tiền kiểm đếm</th>
                        <th class="px-5 py-3 text-right">Chênh lệch</th>
                        <th class="px-5 py-3 text-center">Trạng thái</th>
                        <th class="px-5 py-3">Bàn giao cho</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($recentSessions as $sess)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-5 py-3 font-mono font-bold text-slate-900">{{ $sess->code }}</td>
                        <td class="px-5 py-3 font-medium">{{ $sess->store->name ?? 'N/A' }}</td>
                        <td class="px-5 py-3">
                            <div>{{ $sess->opened_at->format('H:i d/m') }}</div>
                            <div class="text-[11px] text-slate-400">{{ $sess->closed_at ? $sess->closed_at->format('H:i d/m/Y') : 'Đang mở' }}</div>
                        </td>
                        <td class="px-5 py-3 text-right font-mono">{{ number_format($sess->opening_cash) }}đ</td>
                        <td class="px-5 py-3 text-right font-mono font-semibold text-emerald-600">+{{ number_format($sess->cash_sales) }}đ</td>
                        <td class="px-5 py-3 text-right font-mono font-bold">
                            {{ $sess->counted_cash !== null ? number_format($sess->counted_cash) . 'đ' : '—' }}
                        </td>
                        <td class="px-5 py-3 text-right font-mono font-bold">
                            @if($sess->difference === null)
                                —
                            @elseif($sess->difference == 0)
                                <span class="text-emerald-600">0đ (Khớp)</span>
                            @elseif($sess->difference > 0)
                                <span class="text-emerald-600">+{{ number_format($sess->difference) }}đ (Thừa)</span>
                            @else
                                <span class="text-rose-600">{{ number_format($sess->difference) }}đ (Thiếu)</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-center">
                            @if($sess->isOpen())
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700">Đang mở</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">Đã đóng</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-slate-500">
                            {{ $sess->handoverUser->name ?? '—' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-5 py-8 text-center text-slate-400">Bạn chưa có phiên ca nào trước đây.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Thu / Chi Khác Trong Ca -->
@if($currentSession)
<div id="cashMovementModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full p-5 shadow-2xl space-y-4">
        <div class="flex justify-between items-center">
            <h4 class="font-bold text-sm text-slate-900" id="cashModalTitle">Thu / Chi Tiền Mặt Trong Ca</h4>
            <button onclick="closeCashModal()" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
        </div>

        <form method="POST" action="{{ route('staff.shifts.cash-movement', $currentSession->id) }}" class="space-y-3 text-xs">
            @csrf
            <input type="hidden" name="type" id="cashMovementType" value="in">

            <div>
                <label class="block font-bold text-slate-700 mb-1">Số tiền (VNĐ):</label>
                <input type="number" name="amount" min="1000" step="1000" required placeholder="50000"
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl font-mono font-bold text-slate-900 outline-none focus:border-rose-500">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Lý do phát sinh:</label>
                <input type="text" name="reason" required placeholder="Ví dụ: Khách trả nợ, chi trả tiền ship, nộp tiền két chính..."
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl outline-none focus:border-rose-500">
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="closeCashModal()" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl">Hủy</button>
                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl shadow-md">Xác nhận</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Đóng Ca & Bàn Giao -->
<div id="closeShiftModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full p-5 shadow-2xl space-y-4">
        <div class="flex justify-between items-center">
            <h4 class="font-bold text-sm text-slate-900">Đóng Ca & Đối Soát Tiền Mặt</h4>
            <button onclick="closeCloseShiftModal()" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
        </div>

        <form method="POST" action="{{ route('staff.shifts.close', $currentSession->id) }}" class="space-y-3.5 text-xs">
            @csrf

            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                <div class="text-slate-500">Tiền mặt dự kiến theo hệ thống:</div>
                <div class="text-lg font-black text-slate-900 font-mono mt-0.5" id="expectedCashVal" data-val="{{ $currentSession->current_expected_cash }}">
                    {{ number_format($currentSession->current_expected_cash) }}đ
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Tiền mặt thực tế kiểm đếm được (VNĐ):</label>
                <input type="number" id="countedCashInput" name="counted_cash" oninput="calcCloseDifference()" required min="0" step="1000"
                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl font-mono font-bold text-slate-900 text-base outline-none focus:border-rose-500">
            </div>

            <!-- Difference display -->
            <div class="p-2.5 rounded-xl border flex justify-between items-center font-bold" id="diffBox" style="display:none;">
                <span>Chênh lệch:</span>
                <span id="diffVal" class="font-mono">0đ</span>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Bàn giao ca cho nhân viên:</label>
                <select name="handover_to" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl outline-none font-semibold">
                    <option value="">-- Chọn nhân viên nhận ca --</option>
                    @foreach($colleagues as $c)
                    <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->employee_code ?? 'NV'.$c->id }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Ghi chú đối soát / bàn giao:</label>
                <textarea name="close_note" rows="2" placeholder="Ghi chú về chênh lệch hoặc số tiền mặt bàn giao lại..."
                          class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl outline-none focus:border-rose-500"></textarea>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="closeCloseShiftModal()" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl">Hủy</button>
                <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-md">
                    Xác Nhận Đóng Ca
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
    function openCashMovementModal(type) {
        document.getElementById('cashMovementType').value = type;
        document.getElementById('cashModalTitle').textContent = type === 'in' ? 'Thu Khác Trong Ca' : 'Chi Khác / Nộp Tiền Trong Ca';
        document.getElementById('cashMovementModal').classList.remove('hidden');
    }
    function closeCashModal() {
        document.getElementById('cashMovementModal').classList.add('hidden');
    }

    function openCloseShiftModal() {
        document.getElementById('closeShiftModal').classList.remove('hidden');
    }
    function closeCloseShiftModal() {
        document.getElementById('closeShiftModal').classList.add('hidden');
    }

    function calcCloseDifference() {
        const expected = parseFloat(document.getElementById('expectedCashVal').dataset.val) || 0;
        const counted = parseFloat(document.getElementById('countedCashInput').value) || 0;
        const diff = counted - expected;

        const box = document.getElementById('diffBox');
        const val = document.getElementById('diffVal');
        box.style.display = 'flex';

        if (diff === 0) {
            box.className = 'p-2.5 rounded-xl border flex justify-between items-center font-bold bg-emerald-50 border-emerald-200 text-emerald-700';
            val.textContent = '0đ (Khớp 100%)';
        } else if (diff > 0) {
            box.className = 'p-2.5 rounded-xl border flex justify-between items-center font-bold bg-emerald-50 border-emerald-200 text-emerald-700';
            val.textContent = `+${new Intl.NumberFormat('vi-VN').format(diff)}đ (Thừa)`;
        } else {
            box.className = 'p-2.5 rounded-xl border flex justify-between items-center font-bold bg-rose-50 border-rose-200 text-rose-700';
            val.textContent = `${new Intl.NumberFormat('vi-VN').format(diff)}đ (Thiếu)`;
        }
    }
</script>
@endsection

