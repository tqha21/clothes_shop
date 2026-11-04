<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * Display a listing of shifts.
     */
    public function index()
    {
        $shifts = Shift::orderBy('start_time')->get();
        return view('admin.shifts.index', compact('shifts'));
    }

    /**
     * Store a newly created shift.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i',
            'color'      => 'required|string|max:20',
        ], [
            'name.required'       => 'Tên ca là bắt buộc.',
            'start_time.required' => 'Giờ bắt đầu là bắt buộc.',
            'end_time.required'   => 'Giờ kết thúc là bắt buộc.',
            'color.required'      => 'Màu ca là bắt buộc.',
        ]);

        Shift::create($request->only(['name', 'start_time', 'end_time', 'color']));

        return redirect()->route('admin.shifts.index')
                         ->with('success', 'Thêm ca làm việc "' . $request->name . '" thành công!');
    }

    /**
     * Update the specified shift.
     */
    public function update(Request $request, Shift $shift)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i',
            'color'      => 'required|string|max:20',
        ], [
            'name.required'       => 'Tên ca là bắt buộc.',
            'start_time.required' => 'Giờ bắt đầu là bắt buộc.',
            'end_time.required'   => 'Giờ kết thúc là bắt buộc.',
            'color.required'      => 'Màu ca là bắt buộc.',
        ]);

        $shift->update($request->only(['name', 'start_time', 'end_time', 'color']));

        return redirect()->route('admin.shifts.index')
                         ->with('success', 'Cập nhật ca làm việc thành công!');
    }

    /**
     * Remove the specified shift.
     */
    public function destroy(Shift $shift)
    {
        // Check if shift is used in schedules
        if ($shift->shiftSchedules()->exists()) {
            return redirect()->route('admin.shifts.index')
                             ->with('error', 'Không thể xóa ca "' . $shift->name . '" vì đã có lịch làm việc sử dụng ca này!');
        }

        $name = $shift->name;
        $shift->delete();

        return redirect()->route('admin.shifts.index')
                         ->with('success', 'Đã xóa ca làm việc "' . $name . '"!');
    }
}
