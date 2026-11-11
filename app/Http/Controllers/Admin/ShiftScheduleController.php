<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Models\ShiftSchedule;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftScheduleController extends Controller
{
    /**
     * Display the schedule calendar and management dashboard.
     */
    public function index(Request $request)
    {
        $year       = (int) $request->get('year', now()->year);
        $month      = (int) $request->get('month', now()->month);
        $storeId    = $request->get('store_id');
        $employeeId = $request->get('employee_id');
        $shiftId    = $request->get('shift_id');
        $viewMode   = $request->get('view_mode', 'calendar'); // 'calendar' | 'unscheduled' | 'staffing'

        // Clamp month & adjust year if needed
        if ($month < 1) {
            $month = 12;
            $year--;
        } elseif ($month > 12) {
            $month = 1;
            $year++;
        }

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth   = Carbon::create($year, $month, 1)->endOfMonth();
        $prevMonth    = Carbon::create($year, $month, 1)->subMonth();
        $nextMonth    = Carbon::create($year, $month, 1)->addMonth();

        // Build schedule query
        $query = ShiftSchedule::with(['user.store', 'shift'])
            ->whereYear('work_date', $year)
            ->whereMonth('work_date', $month);

        if ($storeId) {
            $query->whereHas('user', function ($q) use ($storeId) {
                $q->where('store_id', (int) $storeId);
            });
        }
        if ($employeeId) {
            $query->where('user_id', (int) $employeeId);
        }
        if ($shiftId) {
            $query->where('shift_id', (int) $shiftId);
        }

        $allSchedules = $query->get();

        // Group by Y-m-d for calendar cells
        $schedules = $allSchedules->groupBy(fn($s) => $s->work_date->format('Y-m-d'));

        // Calendar grid days
        $calendarDays = $this->buildCalendar($year, $month);

        // Fetch employees & stores for filter/forms
        $employeesQuery = User::employees()->with('store')->orderBy('name');
        if ($storeId) {
            $employeesQuery->where('store_id', $storeId);
        }
        $employees = $employeesQuery->get();

        $shifts = Shift::orderBy('start_time')->get();
        $stores = Store::orderBy('name')->get();

        $totalSchedules = $allSchedules->count();
        $totalEmployees = $allSchedules->pluck('user_id')->unique()->count();

        // 1. Phân tích định mức nhân sự mỗi ca theo từng ngày (Hiển thị số nhân viên mỗi ca & phát hiện thiếu người)
        $dayShiftStats = [];
        $totalShortageShifts = 0; // Tổng số ca bị thiếu người trong tháng
        foreach ($calendarDays as $day) {
            if (!$day['isCurrentMonth']) continue;
            $dKey = $day['date']->format('Y-m-d');
            $dayScheds = $schedules[$dKey] ?? collect();

            $dayShiftStats[$dKey] = [];
            foreach ($shifts as $shift) {
                $count = $dayScheds->where('shift_id', $shift->id)->count();
                $minStaff = $shift->min_staff ?? 1;
                $isShortage = $count < $minStaff;
                if ($isShortage) {
                    $totalShortageShifts++;
                }
                $dayShiftStats[$dKey][$shift->id] = [
                    'shift'       => $shift,
                    'count'       => $count,
                    'min_staff'   => $minStaff,
                    'is_shortage' => $isShortage,
                    'status'      => $count === 0 ? 'empty' : ($isShortage ? 'lacking' : 'sufficient'),
                ];
            }
        }

        // 2. Thống kê nhân viên chưa xếp ca hoặc đã xếp ca
        $employeeScheduleCounts = $allSchedules->groupBy('user_id')->map->count();
        $employeeStats = $employees->map(function ($emp) use ($employeeScheduleCounts) {
            $emp->assigned_shifts_count = $employeeScheduleCounts[$emp->id] ?? 0;
            return $emp;
        })->sortBy('assigned_shifts_count')->values();

        $unscheduledEmployees = $employeeStats->where('assigned_shifts_count', 0)->values();
        $totalUnscheduled = $unscheduledEmployees->count();

        // 3. Quét phát hiện các ca bị trùng lịch (time conflict) trong toàn bộ lịch tháng
        $conflictedScheduleIds = [];
        foreach ($allSchedules->groupBy(fn($s) => $s->user_id . '_' . $s->work_date->format('Y-m-d')) as $userDayGroup) {
            if ($userDayGroup->count() > 1) {
                $items = $userDayGroup->values();
                for ($i = 0; $i < $items->count(); $i++) {
                    for ($j = $i + 1; $j < $items->count(); $j++) {
                        $s1 = $items[$i];
                        $s2 = $items[$j];
                        if (!$s1->shift || !$s2->shift) continue;
                        if ($s1->shift_id === $s2->shift_id || self::isTimeOverlap($s1->shift->start_time, $s1->shift->end_time, $s2->shift->start_time, $s2->shift->end_time)) {
                            $conflictedScheduleIds[] = $s1->id;
                            $conflictedScheduleIds[] = $s2->id;
                        }
                    }
                }
            }
        }
        $conflictedScheduleIds = array_unique($conflictedScheduleIds);

        // Chuẩn bị các tuần cho chức năng "Sao chép lịch tuần trước"
        $currentMonday = now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
        $lastMonday    = now()->subWeek()->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
        $nextMonday    = now()->addWeek()->startOfWeek(Carbon::MONDAY)->format('Y-m-d');

        return view('admin.schedules.index', compact(
            'year',
            'month',
            'storeId',
            'employeeId',
            'shiftId',
            'viewMode',
            'startOfMonth',
            'endOfMonth',
            'prevMonth',
            'nextMonth',
            'calendarDays',
            'schedules',
            'allSchedules',
            'employees',
            'shifts',
            'stores',
            'totalSchedules',
            'totalEmployees',
            'dayShiftStats',
            'totalShortageShifts',
            'employeeStats',
            'unscheduledEmployees',
            'totalUnscheduled',
            'conflictedScheduleIds',
            'currentMonday',
            'lastMonday',
            'nextMonday'
        ));
    }

    /**
     * Store a single shift schedule entry with overlap validation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'   => 'required|exists:users,id',
            'shift_id'  => 'required|exists:shifts,id',
            'work_date' => 'required|date',
            'note'      => 'nullable|string|max:500',
            'status'    => 'nullable|in:scheduled,confirmed,absent,completed',
        ], [
            'user_id.required'   => 'Vui lòng chọn nhân viên.',
            'user_id.exists'     => 'Nhân viên không tồn tại.',
            'shift_id.required'  => 'Vui lòng chọn ca làm việc.',
            'shift_id.exists'    => 'Ca làm việc không tồn tại.',
            'work_date.required' => 'Vui lòng chọn ngày làm việc.',
            'work_date.date'     => 'Ngày làm việc không hợp lệ.',
        ]);

        // CẢNH BÁO TRÙNG LỊCH: Kiểm tra chồng chéo giờ làm việc
        $conflict = self::checkOverlap((int) $validated['user_id'], $validated['work_date'], (int) $validated['shift_id']);
        if ($conflict) {
            $user = User::find($validated['user_id']);
            $dateStr = Carbon::parse($validated['work_date'])->format('d/m/Y');
            return redirect()->back()
                ->with('error', "⚠️ CẢNH BÁO TRÙNG LỊCH: Nhân viên {$user->name} vào ngày {$dateStr} đã có lịch ({$conflict['reason']}). Vui lòng chọn ca khác hoặc ngày khác!")
                ->withInput();
        }

        ShiftSchedule::create([
            'user_id'   => $validated['user_id'],
            'shift_id'  => $validated['shift_id'],
            'work_date' => $validated['work_date'],
            'note'      => $validated['note'] ?? null,
            'status'    => $validated['status'] ?? 'scheduled',
        ]);

        return redirect()->back()->with('success', 'Đã xếp ca làm việc thành công!');
    }

    /**
     * Bulk store: Assign shifts to multiple employees across multiple dates or date ranges.
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'shift_id'      => 'required|exists:shifts,id',
            'user_ids'      => 'required|array|min:1',
            'user_ids.*'    => 'required|exists:users,id',
            'schedule_type' => 'required|in:dates,range',
            'note'          => 'nullable|string|max:500',
        ], [
            'shift_id.required' => 'Vui lòng chọn ca làm việc.',
            'user_ids.required' => 'Vui lòng chọn ít nhất một nhân viên.',
            'user_ids.min'      => 'Vui lòng chọn ít nhất một nhân viên.',
        ]);

        $userIds = $request->input('user_ids', []);
        $shiftId = (int) $request->input('shift_id');
        $note    = $request->input('note');
        $dates   = [];

        if ($request->input('schedule_type') === 'range') {
            $request->validate([
                'start_date'     => 'required|date',
                'end_date'       => 'required|date|after_or_equal:start_date',
                'days_of_week'   => 'nullable|array',
            ], [
                'start_date.required' => 'Vui lòng chọn ngày bắt đầu.',
                'end_date.required'   => 'Vui lòng chọn ngày kết thúc.',
                'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            ]);

            $start = Carbon::parse($request->input('start_date'));
            $end   = Carbon::parse($request->input('end_date'));
            $dow   = $request->input('days_of_week', []);

            $cur = $start->copy();
            while ($cur->lte($end)) {
                $isoDow = $cur->dayOfWeekIso;
                if (empty($dow) || in_array((string)$isoDow, $dow) || in_array((int)$isoDow, $dow)) {
                    $dates[] = $cur->format('Y-m-d');
                }
                $cur->addDay();
            }
        } else {
            // Selected individual dates
            $request->validate([
                'work_dates'   => 'required|array|min:1',
                'work_dates.*' => 'required|date',
            ], [
                'work_dates.required' => 'Vui lòng chọn ít nhất một ngày làm việc.',
            ]);
            $dates = $request->input('work_dates', []);
        }

        if (empty($dates)) {
            return redirect()->back()->with('error', 'Không có ngày nào hợp lệ được chọn trong khoảng thời gian đã lọc!')->withInput();
        }

        $created = 0;
        $skipped = [];

        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if (!$user) continue;

            foreach ($dates as $date) {
                // Kiểm tra trùng lịch
                $conflict = self::checkOverlap((int) $userId, $date, $shiftId);
                if ($conflict) {
                    $skipped[] = "{$user->name} ngày " . Carbon::parse($date)->format('d/m/Y') . " ({$conflict['reason']})";
                    continue;
                }

                ShiftSchedule::create([
                    'user_id'   => $userId,
                    'shift_id'  => $shiftId,
                    'work_date' => $date,
                    'note'      => $note,
                    'status'    => 'scheduled',
                ]);
                $created++;
            }
        }

        $msg = "Đã xếp hàng loạt thành công {$created} ca làm việc!";
        if (count($skipped) > 0) {
            $msg .= " Bỏ qua " . count($skipped) . " ca do trùng lịch.";
            return redirect()->back()->with('success', $msg)->with('skipped_conflicts', array_slice($skipped, 0, 10));
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Copy schedule from source week to target week.
     */
    public function copyWeek(Request $request)
    {
        $validated = $request->validate([
            'source_monday' => 'required|date',
            'target_monday' => 'required|date|different:source_monday',
            'store_id'      => 'nullable|exists:stores,id',
            'override'      => 'nullable|boolean',
        ], [
            'source_monday.required' => 'Vui lòng chọn tuần nguồn để sao chép.',
            'target_monday.required' => 'Vui lòng chọn tuần đích cần dán lịch.',
            'target_monday.different' => 'Tuần đích phải khác tuần nguồn.',
        ]);

        $sourceMonday = Carbon::parse($validated['source_monday'])->startOfWeek(Carbon::MONDAY);
        $sourceSunday = $sourceMonday->copy()->endOfWeek(Carbon::SUNDAY);
        $targetMonday = Carbon::parse($validated['target_monday'])->startOfWeek(Carbon::MONDAY);

        $query = ShiftSchedule::with('shift')
            ->whereBetween('work_date', [$sourceMonday->format('Y-m-d'), $sourceSunday->format('Y-m-d')]);

        if (!empty($validated['store_id'])) {
            $storeId = (int) $validated['store_id'];
            $query->whereHas('user', fn($q) => $q->where('store_id', $storeId));
        }

        $sourceSchedules = $query->get();

        if ($sourceSchedules->isEmpty()) {
            return redirect()->back()->with('error', "Tuần nguồn từ " . $sourceMonday->format('d/m/Y') . " đến " . $sourceSunday->format('d/m/Y') . " chưa có ca làm việc nào để sao chép!");
        }

        $override = (bool) $request->input('override', false);
        $copied = 0;
        $skipped = 0;

        DB::beginTransaction();
        try {
            if ($override) {
                // Xóa các ca ở tuần đích của những nhân viên / cửa hàng được sao chép
                $targetSunday = $targetMonday->copy()->endOfWeek(Carbon::SUNDAY);
                $deleteQuery = ShiftSchedule::whereBetween('work_date', [$targetMonday->format('Y-m-d'), $targetSunday->format('Y-m-d')]);
                if (!empty($validated['store_id'])) {
                    $deleteQuery->whereHas('user', fn($q) => $q->where('store_id', (int) $validated['store_id']));
                }
                $deleteQuery->delete();
            }

            foreach ($sourceSchedules as $src) {
                $dayOffset = $src->work_date->diffInDays($sourceMonday);
                $targetDate = $targetMonday->copy()->addDays($dayOffset)->format('Y-m-d');

                // Check overlap
                $conflict = self::checkOverlap((int) $src->user_id, $targetDate, (int) $src->shift_id);
                if ($conflict) {
                    $skipped++;
                    continue;
                }

                ShiftSchedule::create([
                    'user_id'   => $src->user_id,
                    'shift_id'  => $src->shift_id,
                    'work_date' => $targetDate,
                    'note'      => $src->note,
                    'status'    => 'scheduled',
                ]);
                $copied++;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi sao chép lịch: ' . $e->getMessage());
        }

        $msg = "Đã sao chép thành công {$copied} ca từ tuần " . $sourceMonday->format('d/m') . " sang tuần " . $targetMonday->format('d/m/Y') . "!";
        if ($skipped > 0) {
            $msg .= " ({$skipped} ca bị bỏ qua do trùng lịch đã có)";
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Swap shift between two employees or transfer shift (cover).
     */
    public function swap(Request $request)
    {
        $swapType = $request->input('swap_type', 'swap'); // 'swap' | 'transfer'

        if ($swapType === 'transfer') {
            // Chuyển ca: Nhân viên B làm thay ca của Nhân viên A
            $request->validate([
                'schedule_id'    => 'required|exists:shift_schedules,id',
                'target_user_id' => 'required|exists:users,id',
            ], [
                'schedule_id.required'    => 'Vui lòng chọn ca làm việc cần chuyển giao.',
                'target_user_id.required' => 'Vui lòng chọn nhân viên nhận làm thay.',
            ]);

            $schedule   = ShiftSchedule::with(['user', 'shift'])->findOrFail($request->input('schedule_id'));
            $targetUser = User::findOrFail($request->input('target_user_id'));

            if ($schedule->user_id == $targetUser->id) {
                return redirect()->back()->with('error', 'Nhân viên nhận ca phải khác nhân viên đang giữ ca!');
            }

            // Kiểm tra nhân viên B có bị trùng giờ không
            $conflict = self::checkOverlap($targetUser->id, $schedule->work_date->format('Y-m-d'), $schedule->shift_id);
            if ($conflict) {
                return redirect()->back()->with('error', "Không thể chuyển ca: {$targetUser->name} đã có lịch ({$conflict['reason']}) vào ngày " . $schedule->work_date->format('d/m/Y') . "!");
            }

            $oldUserName = $schedule->user->name;
            $schedule->update([
                'user_id' => $targetUser->id,
                'note'    => trim(($schedule->note ? $schedule->note . ' | ' : '') . "Làm thay cho {$oldUserName} (chuyển lúc " . now()->format('d/m H:i') . ")"),
            ]);

            return redirect()->back()->with('success', "Đã chuyển ca ngày " . $schedule->work_date->format('d/m/Y') . " từ {$oldUserName} sang cho {$targetUser->name} thành công!");
        } else {
            // Hoán đổi ca giữa 2 nhân viên (A làm ca của B và B làm ca của A)
            $request->validate([
                'schedule_1_id' => 'required|exists:shift_schedules,id',
                'schedule_2_id' => 'required|exists:shift_schedules,id|different:schedule_1_id',
            ], [
                'schedule_1_id.required'  => 'Vui lòng chọn ca của nhân viên thứ nhất.',
                'schedule_2_id.required'  => 'Vui lòng chọn ca của nhân viên thứ hai.',
                'schedule_2_id.different' => 'Hai ca hoán đổi phải khác nhau.',
            ]);

            $sched1 = ShiftSchedule::with(['user', 'shift'])->findOrFail($request->input('schedule_1_id'));
            $sched2 = ShiftSchedule::with(['user', 'shift'])->findOrFail($request->input('schedule_2_id'));

            if ($sched1->user_id == $sched2->user_id) {
                return redirect()->back()->with('error', 'Không thể đổi ca giữa cùng một nhân viên!');
            }

            $user1 = $sched1->user;
            $user2 = $sched2->user;

            // Kiểm tra xem User 1 có bị trùng khi nhận Ca 2 không
            $conflict1 = self::checkOverlap($user1->id, $sched2->work_date->format('Y-m-d'), $sched2->shift_id, $sched1->id);
            if ($conflict1) {
                return redirect()->back()->with('error', "Không thể đổi ca: {$user1->name} bị trùng lịch vào ngày " . $sched2->work_date->format('d/m/Y') . " ({$conflict1['reason']})!");
            }

            // Kiểm tra xem User 2 có bị trùng khi nhận Ca 1 không
            $conflict2 = self::checkOverlap($user2->id, $sched1->work_date->format('Y-m-d'), $sched1->shift_id, $sched2->id);
            if ($conflict2) {
                return redirect()->back()->with('error', "Không thể đổi ca: {$user2->name} bị trùng lịch vào ngày " . $sched1->work_date->format('d/m/Y') . " ({$conflict2['reason']})!");
            }

            DB::transaction(function () use ($sched1, $sched2, $user1, $user2) {
                $sched1->update([
                    'user_id' => $user2->id,
                    'note'    => trim(($sched1->note ? $sched1->note . ' | ' : '') . "Đổi ca với {$user1->name} lúc " . now()->format('d/m H:i')),
                ]);

                $sched2->update([
                    'user_id' => $user1->id,
                    'note'    => trim(($sched2->note ? $sched2->note . ' | ' : '') . "Đổi ca với {$user2->name} lúc " . now()->format('d/m H:i')),
                ]);
            });

            return redirect()->back()->with('success', "Đã hoán đổi ca thành công giữa {$user1->name} và {$user2->name}!");
        }
    }

    /**
     * AJAX endpoint to check conflict dynamically before form submit.
     */
    public function checkConflict(Request $request)
    {
        $userId    = (int) $request->input('user_id');
        $shiftId   = (int) $request->input('shift_id');
        $workDate  = $request->input('work_date');
        $excludeId = $request->input('exclude_id') ? (int) $request->input('exclude_id') : null;

        if (!$userId || !$shiftId || !$workDate) {
            return response()->json(['conflict' => false]);
        }

        $conflict = self::checkOverlap($userId, $workDate, $shiftId, $excludeId);

        if ($conflict) {
            $user = User::find($userId);
            return response()->json([
                'conflict' => true,
                'message'  => "⚠️ Cảnh báo: Nhân viên {$user->name} đã có lịch ({$conflict['reason']}) vào ngày " . Carbon::parse($workDate)->format('d/m/Y') . "!",
            ]);
        }

        return response()->json(['conflict' => false]);
    }

    /**
     * Export schedule to Excel/CSV with UTF-8 BOM.
     */
    public function export(Request $request)
    {
        $year       = (int) $request->get('year', now()->year);
        $month      = (int) $request->get('month', now()->month);
        $storeId    = $request->get('store_id');
        $employeeId = $request->get('employee_id');

        $query = ShiftSchedule::with(['user.store', 'shift'])
            ->whereYear('work_date', $year)
            ->whereMonth('work_date', $month);

        if ($storeId) {
            $query->whereHas('user', fn($q) => $q->where('store_id', (int) $storeId));
        }
        if ($employeeId) {
            $query->where('user_id', (int) $employeeId);
        }

        $schedules = $query->orderBy('work_date')->orderBy('shift_id')->get();

        $filename = "Lich_Lam_Viec_Thang_{$month}_{$year}.csv";

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($schedules, $month, $year) {
            $output = fopen('php://output', 'w');
            // Write UTF-8 BOM so Excel opens with proper Vietnamese encoding
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header info
            fputcsv($output, ["BẢNG PHÂN CÔNG LỊCH LÀM VIỆC NHÂN VIÊN - THÁNG {$month}/{$year}"]);
            fputcsv($output, ["Thời gian xuất:", now()->format('d/m/Y H:i:s'), "Tổng số ca:", $schedules->count()]);
            fputcsv($output, []); // empty line

            // Table headers
            fputcsv($output, [
                'STT',
                'Mã Nhân Viên',
                'Họ và Tên',
                'Cửa Hàng / Chi Nhánh',
                'Ngày Làm Việc',
                'Thứ',
                'Ca Làm Việc',
                'Giờ Bắt Đầu',
                'Giờ Kết Thúc',
                'Trạng Thái',
                'Ghi Chú',
            ]);

            $stt = 1;
            foreach ($schedules as $s) {
                fputcsv($output, [
                    $stt++,
                    $s->user->employee_code ?? ('NV' . $s->user_id),
                    $s->user->name,
                    $s->user->store->name ?? 'Tổng công ty',
                    $s->work_date->format('d/m/Y'),
                    $s->work_date->translatedFormat('l'),
                    $s->shift->name ?? 'N/A',
                    substr($s->shift->start_time ?? '', 0, 5),
                    substr($s->shift->end_time ?? '', 0, 5),
                    $s->status_label,
                    $s->note ?? '',
                ]);
            }

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Print view for Shift Schedules (A4 Print-ready).
     */
    public function print(Request $request)
    {
        $year       = (int) $request->get('year', now()->year);
        $month      = (int) $request->get('month', now()->month);
        $storeId    = $request->get('store_id');
        $employeeId = $request->get('employee_id');

        $query = ShiftSchedule::with(['user.store', 'shift'])
            ->whereYear('work_date', $year)
            ->whereMonth('work_date', $month);

        if ($storeId) {
            $query->whereHas('user', fn($q) => $q->where('store_id', (int) $storeId));
        }
        if ($employeeId) {
            $query->where('user_id', (int) $employeeId);
        }

        $schedules = $query->get();
        $store = $storeId ? Store::find($storeId) : null;

        // Group by user then by date
        $employeesQuery = User::employees()->with('store')->orderBy('name');
        if ($storeId) {
            $employeesQuery->where('store_id', $storeId);
        }
        $employees = $employeesQuery->get();

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth   = Carbon::create($year, $month, 1)->endOfMonth();
        $daysInMonth  = range(1, $endOfMonth->day);

        // Matrix [userId][day] => collection of schedules
        $matrix = [];
        foreach ($employees as $emp) {
            $matrix[$emp->id] = [];
            for ($d = 1; $d <= $endOfMonth->day; $d++) {
                $matrix[$emp->id][$d] = [];
            }
        }

        foreach ($schedules as $s) {
            $d = $s->work_date->day;
            if (isset($matrix[$s->user_id][$d])) {
                $matrix[$s->user_id][$d][] = $s;
            }
        }

        $shifts = Shift::all();

        return view('admin.schedules.print', compact(
            'year',
            'month',
            'store',
            'employees',
            'daysInMonth',
            'matrix',
            'shifts',
            'schedules',
            'startOfMonth'
        ));
    }

    /**
     * Update a schedule entry (status/note).
     */
    public function update(Request $request, ShiftSchedule $schedule)
    {
        $validated = $request->validate([
            'status' => 'required|in:scheduled,confirmed,absent,completed',
            'note'   => 'nullable|string|max:500',
        ]);

        $schedule->update($validated);

        return redirect()->back()->with('success', 'Cập nhật trạng thái ca thành công!');
    }

    /**
     * Remove a schedule entry.
     */
    public function destroy(ShiftSchedule $schedule)
    {
        $schedule->delete();
        return redirect()->back()->with('success', 'Đã xóa lịch ca làm việc!');
    }

    /**
     * Check if assigning user to newShiftId on workDate conflicts with existing schedules.
     */
    public static function checkOverlap(int $userId, string $workDate, int $newShiftId, ?int $excludeScheduleId = null): ?array
    {
        $newShift = Shift::find($newShiftId);
        if (!$newShift) return null;

        $existingSchedules = ShiftSchedule::with('shift')
            ->where('user_id', $userId)
            ->where('work_date', $workDate)
            ->when($excludeScheduleId, fn($q) => $q->where('id', '!=', $excludeScheduleId))
            ->get();

        foreach ($existingSchedules as $sched) {
            $existingShift = $sched->shift;
            if (!$existingShift) continue;

            // Nếu cùng shift_id
            if ($existingShift->id == $newShift->id) {
                return [
                    'conflict'          => true,
                    'existing_shift'    => $existingShift,
                    'existing_schedule' => $sched,
                    'reason'            => "đã có ca '{$existingShift->name}' ({$existingShift->time_range})",
                ];
            }

            // Kiểm tra khoảng thời gian giao nhau
            if (self::isTimeOverlap($newShift->start_time, $newShift->end_time, $existingShift->start_time, $existingShift->end_time)) {
                return [
                    'conflict'          => true,
                    'existing_shift'    => $existingShift,
                    'existing_schedule' => $sched,
                    'reason'            => "trùng khung giờ với ca '{$existingShift->name}' ({$existingShift->time_range})",
                ];
            }
        }

        return null;
    }

    /**
     * Calculate whether two time ranges overlap (supporting overnight shifts).
     */
    public static function isTimeOverlap(string $start1, string $end1, string $start2, string $end2): bool
    {
        $intervals1 = self::getTimeIntervals($start1, $end1);
        $intervals2 = self::getTimeIntervals($start2, $end2);

        foreach ($intervals1 as [$a, $b]) {
            foreach ($intervals2 as [$c, $d]) {
                if (max($a, $c) < min($b, $d)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get 1 or 2 minute intervals for a shift (splits overnight shifts at midnight).
     */
    private static function getTimeIntervals(string $start, string $end): array
    {
        $s = self::timeToMinutes($start);
        $e = self::timeToMinutes($end);

        if ($e <= $s) {
            // Ca qua đêm: từ start đến 24:00 (1440) và từ 00:00 (0) đến end
            return [
                [$s, 1440],
                [0, $e],
            ];
        }

        return [
            [$s, $e],
        ];
    }

    /**
     * Convert time string "HH:MM" to minutes from 00:00.
     */
    private static function timeToMinutes(string $time): int
    {
        $parts = explode(':', $time);
        return ((int)($parts[0] ?? 0)) * 60 + ((int)($parts[1] ?? 0));
    }

    /**
     * Build calendar array for month view (Monday to Sunday).
     */
    private function buildCalendar($year, $month)
    {
        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth   = Carbon::create($year, $month, 1)->endOfMonth();

        $startDay = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $endDay   = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);

        $days = [];
        $current = $startDay->copy();

        while ($current <= $endDay) {
            $days[] = [
                'date'           => $current->copy(),
                'isCurrentMonth' => $current->month === (int) $month,
                'isToday'        => $current->isToday(),
                'isWeekend'      => $current->isWeekend(),
            ];
            $current->addDay();
        }

        return $days;
    }
}
