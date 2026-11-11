<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng Phân Công Lịch Làm Việc - Tháng {{ $month }}/{{ $year }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { font-size: 11px; color: #000; }
            @page {
                size: A4 landscape;
                margin: 8mm;
            }
            .page-break { page-break-after: always; }
        }
        table { border-collapse: collapse; }
        th, td { border: 1px solid #cbd5e1; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 p-4 font-sans text-xs">

    {{-- Controls bar --}}
    <div class="no-print max-w-7xl mx-auto mb-4 bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
        <div>
            <h2 class="font-bold text-gray-800 text-sm">Xem trước bản in Lịch Làm Việc Tháng {{ $month }}/{{ $year }}</h2>
            <p class="text-xs text-gray-500">Bản in được tối ưu hóa cho khổ giấy A4 ngang (Landscape). Bấm In để in trực tiếp hoặc lưu dưới dạng PDF.</p>
        </div>
        <div class="flex gap-2">
            <button onclick="window.history.back()" class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-semibold hover:bg-gray-50">
                ← Quay lại
            </button>
            <button onclick="window.print()" class="px-5 py-2 bg-red-600 text-white rounded-lg text-xs font-semibold hover:bg-red-700 shadow flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                In lịch (Print / Lưu PDF)
            </button>
        </div>
    </div>

    {{-- Print Content Container --}}
    <div class="bg-white p-6 rounded-xl shadow-sm max-w-full mx-auto border border-gray-100">
        {{-- Header --}}
        <div class="flex justify-between items-start border-b border-gray-200 pb-4 mb-4">
            <div>
                <h1 class="font-black text-lg text-red-600 uppercase tracking-wide">HTD STORE - THỜI TRANG CHẤT LƯỢNG</h1>
                <p class="text-[11px] text-gray-600">Địa chỉ: 123 Cầu Giấy, Hà Nội | Hotline: 0383390611</p>
                <p class="text-[11px] text-gray-600">Chi nhánh: <span class="font-bold text-gray-800">{{ $store ? $store->name : 'Toàn bộ chi nhánh' }}</span></p>
            </div>
            <div class="text-right">
                <h2 class="text-base font-bold text-gray-800 uppercase">BẢNG PHÂN CÔNG LỊCH LÀM VIỆC</h2>
                <p class="text-xs font-semibold text-gray-700">Tháng {{ $month }} / {{ $year }}</p>
                <p class="text-[10px] text-gray-400 mt-1">Ngày lập biểu: {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        {{-- Shift Legend --}}
        <div class="flex flex-wrap items-center gap-3 mb-3 pb-2 text-[10px]">
            <span class="font-bold text-gray-700">Ký hiệu ca:</span>
            @foreach($shifts as $shift)
            <div class="flex items-center gap-1.5 px-2 py-0.5 rounded border border-gray-200">
                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $shift->color }}"></span>
                <span class="font-bold text-gray-800">{{ $shift->name }}</span>
                <span class="text-gray-500">({{ substr($shift->start_time, 0, 5) }} - {{ substr($shift->end_time, 0, 5) }})</span>
            </div>
            @endforeach
            <div class="ml-auto text-gray-500 font-medium">
                Tổng cộng: <strong class="text-gray-800">{{ $schedules->count() }} ca</strong> / <strong class="text-gray-800">{{ $employees->count() }} nhân viên</strong>
            </div>
        </div>

        {{-- Main Matrix Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-center text-[10px]">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 font-bold">
                        <th class="px-2 py-1.5 text-left min-w-[120px] sticky left-0 bg-gray-100">Nhân viên</th>
                        @foreach($daysInMonth as $d)
                        @php
                            $cDate = \Carbon\Carbon::create($year, $month, $d);
                            $isSun = $cDate->isSunday();
                            $isSat = $cDate->isSaturday();
                        @endphp
                        <th class="px-1 py-1 min-w-[28px] {{ $isSun ? 'bg-red-50 text-red-600' : ($isSat ? 'bg-orange-50 text-orange-600' : '') }}">
                            <div>{{ $d }}</div>
                            <div class="text-[8px] font-normal opacity-80">{{ $cDate->format('D') }}</div>
                        </th>
                        @endforeach
                        <th class="px-2 py-1.5 min-w-[50px] bg-gray-200 text-gray-800">Tổng ca</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $emp)
                    @php
                        $userTotal = 0;
                    @endphp
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-2 py-1 text-left font-medium text-gray-800 sticky left-0 bg-white border-r">
                            <div class="font-semibold">{{ $emp->name }}</div>
                            <div class="text-[9px] text-gray-400">{{ $emp->employee_code ?: ('NV' . $emp->id) }}</div>
                        </td>
                        @foreach($daysInMonth as $d)
                        @php
                            $cellSchedules = $matrix[$emp->id][$d] ?? [];
                            $userTotal += count($cellSchedules);
                            $cDate = \Carbon\Carbon::create($year, $month, $d);
                            $isWeekend = $cDate->isWeekend();
                        @endphp
                        <td class="p-0.5 {{ $isWeekend ? 'bg-gray-50/60' : '' }}">
                            @if(!empty($cellSchedules))
                                @foreach($cellSchedules as $cs)
                                <div class="px-1 py-0.5 rounded text-white font-bold leading-tight mb-0.5 text-[8px] truncate"
                                    style="background-color: {{ $cs->shift->color ?? '#3B82F6' }}"
                                    title="{{ $cs->shift->name ?? '' }}">
                                    {{ mb_substr($cs->shift->name ?? 'Ca', 0, 3) }}
                                </div>
                                @endforeach
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        @endforeach
                        <td class="px-1 py-1 font-bold text-gray-900 bg-gray-50">
                            {{ $userTotal }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Signature Section --}}
        <div class="grid grid-cols-3 gap-4 mt-8 pt-4 text-center text-xs text-gray-700">
            <div>
                <p class="font-bold">NGƯỜI LẬP BIỂU</p>
                <p class="text-[10px] text-gray-400 italic mb-16">(Ký và ghi rõ họ tên)</p>
                <p class="font-semibold text-gray-800">{{ auth()->user()->name ?? 'Admin' }}</p>
            </div>
            <div>
                <p class="font-bold">TRƯỞNG CA / GIÁM SÁT</p>
                <p class="text-[10px] text-gray-400 italic mb-16">(Ký và ghi rõ họ tên)</p>
                <p class="font-semibold text-gray-400">................................................</p>
            </div>
            <div>
                <p class="font-bold">QUẢN LÝ CỬA HÀNG / GIÁM ĐỐC</p>
                <p class="text-[10px] text-gray-400 italic mb-16">(Ký, đóng dấu duyệt)</p>
                <p class="font-semibold text-gray-400">................................................</p>
            </div>
        </div>
    </div>
</body>
</html>

