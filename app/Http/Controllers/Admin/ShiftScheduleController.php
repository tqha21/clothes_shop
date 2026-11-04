<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Models\ShiftSchedule;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShiftScheduleController extends Controller
{
    /**
     * Display the schedule calendar (monthly view).
     */
    public function index(Request $request)
    {
        $year       = (int) $request->get('year', now()->year);
        $month      = (int) $request->get('month', now()->month);
        $storeId    = $request->get('store_id');
        $employeeId = $request->get('employee_id');

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

        $allSchedules = $query->get();

        // Group by Y-m-d for calendar cells
        $schedules = $allSchedules->groupBy(fn($s) => $s->work_date->format('Y-m-d'));

        // Calendar grid
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

        return view('admin.schedules.index', compact(
            'year',
            'month',
            'storeId',
            'employeeId',
            'startOfMonth',
            'endOfMonth',
            'prevMonth',
            'nextMonth',
            'calendarDays',
            'schedules',
            'employees',
            'shifts',
            'stores',
            'totalSchedules',
            'totalEmployees'
        ));
    }

    /**
     * Store a new shift schedule entry.
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

        // Check duplicate
        $exists = ShiftSchedule::where('user_id', $validated['user_id'])
            ->where('shift_id', $validated['shift_id'])
            ->where('work_date', $validated['work_date'])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Nhân viên đã được xếp ca này vào ngày đã chọn!')
                ->withInput();
        }

        ShiftSchedule::create([
            'user_id'   => $validated['user_id'],
            'shift_id'  => $validated['shift_id'],
            'work_date' => $validated['work_date'],
            'note'      => $validated['note'] ?? null,
            'status'    => $validated['status'] ?? 'scheduled',
        ]);

        return redirect()->back()->with('success', 'Đã xếp ca thành công!');
    }

    /**
     * Bulk store: assign one employee + one shift to multiple dates.
     */
    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'user_id'      => 'required|exists:users,id',
            'shift_id'     => 'required|exists:shifts,id',
            'work_dates'   => 'required|array|min:1',
            'work_dates.*' => 'required|date',
            'note'         => 'nullable|string|max:500',
        ], [
            'user_id.required'    => 'Vui lòng chọn nhân viên.',
            'shift_id.required'   => 'Vui lòng chọn ca làm việc.',
            'work_dates.required' => 'Vui lòng chọn ít nhất một ngày.',
        ]);

        $created = 0;
        $skipped = 0;

        foreach ($validated['work_dates'] as $date) {
            $exists = ShiftSchedule::where('user_id', $validated['user_id'])
                ->where('shift_id', $validated['shift_id'])
                ->where('work_date', $date)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            ShiftSchedule::create([
                'user_id'   => $validated['user_id'],
                'shift_id'  => $validated['shift_id'],
                'work_date' => $date,
                'note'      => $validated['note'] ?? null,
                'status'    => 'scheduled',
            ]);
            $created++;
        }

        $msg = "Đã xếp {$created} ca thành công!";
        if ($skipped > 0) {
            $msg .= " ({$skipped} ca bị bỏ qua do trùng lịch)";
        }

        return redirect()->back()->with('success', $msg);
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
