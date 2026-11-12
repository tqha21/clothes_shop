<?php

namespace App\Http\Controllers\Staff;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Models\CashMovement;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ShiftSchedule;
use App\Models\ShiftSession;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShiftSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Màn hình quản lý ca của nhân viên: Mở ca / Đóng ca / Chấm công / Bàn giao tiền.
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $currentSession = $user->currentShiftSession();

        // Lịch phân công hôm nay của nhân viên
        $todaySchedule = ShiftSchedule::where('user_id', $user->id)
            ->whereDate('work_date', now()->toDateString())
            ->with('shift')
            ->first();

        // Các ca gần đây của nhân viên
        $recentSessions = ShiftSession::where('user_id', $user->id)
            ->with(['store', 'handoverUser', 'cashMovements'])
            ->latest('opened_at')
            ->take(10)
            ->get();

        // Danh sách nhân viên cùng cửa hàng để bàn giao ca
        $colleagues = User::where('status', 'active')
            ->where('id', '!=', $user->id)
            ->whereIn('role', ['staff', 'manager', 'admin'])
            ->when($user->store_id, fn ($q) => $q->where('store_id', $user->store_id))
            ->get();

        $stores = Store::where('status', 'active')->get();

        return view('staff.shifts.index', compact('user', 'currentSession', 'todaySchedule', 'recentSessions', 'colleagues', 'stores'));
    }

    /**
     * Mở ca làm việc (Open Shift).
     */
    public function open(Request $request)
    {
        $request->validate([
            'store_id' => 'required|integer|exists:stores,id',
            'opening_cash' => 'required|numeric|min:0',
            'open_note' => 'nullable|string|max:500',
            'shift_schedule_id' => 'nullable|integer|exists:shift_schedules,id',
        ]);

        /** @var User $user */
        $user = Auth::user();

        if ($user->currentShiftSession()) {
            return back()->with('error', 'Bạn đang có một ca làm việc đang mở. Vui lòng đóng ca hiện tại trước khi mở ca mới.');
        }

        if (!$user->canAccessStore($request->store_id)) {
            return back()->with('error', 'Bạn không có quyền mở ca tại cửa hàng này.');
        }

        $code = 'CA' . now()->format('ymdHi') . '-' . $user->id;

        $session = ShiftSession::create([
            'code' => $code,
            'user_id' => $user->id,
            'store_id' => $request->store_id,
            'shift_schedule_id' => $request->shift_schedule_id,
            'opened_at' => now(),
            'opening_cash' => $request->opening_cash,
            'status' => 'open',
            'open_note' => $request->open_note,
        ]);

        // Tự động check-in nếu có lịch làm việc gắn kèm
        if ($request->shift_schedule_id) {
            $schedule = ShiftSchedule::find($request->shift_schedule_id);
            if ($schedule && !$schedule->check_in_at) {
                $schedule->update(['check_in_at' => now(), 'status' => 'confirmed']);
            }
        }

        return redirect()->route('staff.shifts.index')->with('success', 'Đã mở ca làm việc thành công! Mã ca: ' . $code);
    }

    /**
     * Đóng ca làm việc và đối soát tiền mặt (Close Shift).
     */
    public function close(Request $request, ShiftSession $session)
    {
        $this->authorize('operate', $session);

        $request->validate([
            'counted_cash' => 'required|numeric|min:0',
            'close_note' => 'nullable|string|max:500',
            'handover_to' => 'nullable|integer|exists:users,id',
        ]);

        // BR-09: Kiểm tra các giao dịch thanh toán chưa giải quyết
        $pendingPayments = Payment::where('shift_session_id', $session->id)
            ->where('status', 'pending')
            ->count();

        if ($pendingPayments > 0) {
            return back()->with('error', "Không thể đóng ca! Còn {$pendingPayments} giao dịch thanh toán chưa được xác nhận hoặc hủy. Vui lòng xử lý trước khi đóng ca.");
        }

        DB::transaction(function () use ($session, $request) {
            $expectedCash = $session->current_expected_cash;
            $countedCash = (float) $request->input('counted_cash');
            $difference = round($countedCash - $expectedCash, 2);

            $session->update([
                'status' => 'closed',
                'closed_at' => now(),
                'expected_cash' => $expectedCash,
                'counted_cash' => $countedCash,
                'difference' => $difference,
                'close_note' => $request->input('close_note'),
                'handover_to' => $request->input('handover_to'),
                'closed_by' => Auth::id(),
            ]);

            // Tự động check-out lịch ca gắn kèm
            if ($session->shift_schedule_id) {
                $schedule = ShiftSchedule::find($session->shift_schedule_id);
                if ($schedule && !$schedule->check_out_at) {
                    $schedule->update(['check_out_at' => now(), 'status' => 'completed']);
                }
            }
        });

        return redirect()->route('staff.shifts.index')->with('success', 'Đã đóng ca làm việc và hoàn tất bàn giao!');
    }

    /**
     * Thu / Chi / Nộp tiền mặt phát sinh trong ca.
     */
    public function cashMovement(Request $request, ShiftSession $session)
    {
        $this->authorize('operate', $session);

        $request->validate([
            'type' => 'required|in:in,out,deposit',
            'amount' => 'required|numeric|min:1000',
            'reason' => 'required|string|max:255',
        ]);

        $amount = (float) $request->input('amount');
        $type = $request->input('type');

        // Nếu là chi hoặc nộp tiền, kiểm tra số tiền mặt hiện có
        if (in_array($type, ['out', 'deposit'])) {
            $currentExpected = $session->current_expected_cash;
            if ($currentExpected < $amount) {
                return back()->with('error', 'Số tiền mặt hiện tại trong két (' . number_format($currentExpected) . 'đ) không đủ để thực hiện thao tác này.');
            }
        }

        DB::transaction(function () use ($session, $type, $amount, $request) {
            CashMovement::create([
                'shift_session_id' => $session->id,
                'user_id' => Auth::id(),
                'type' => $type,
                'amount' => $amount,
                'reason' => $request->input('reason'),
            ]);

            if ($type === 'in') {
                $session->increment('cash_in', $amount);
            } else {
                $session->increment('cash_out', $amount);
            }
        });

        return back()->with('success', 'Đã ghi nhận giao dịch tiền mặt trong ca!');
    }

    /**
     * Chấm công nhanh vào ca / ra ca (Attendance Check-in/Check-out).
     */
    public function attendance(Request $request)
    {
        $request->validate([
            'action' => 'required|in:check_in,check_out',
            'schedule_id' => 'required|integer|exists:shift_schedules,id',
        ]);

        $schedule = ShiftSchedule::whereKey($request->schedule_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($request->action === 'check_in') {
            if ($schedule->check_in_at) {
                return back()->with('error', 'Bạn đã chấm công vào ca lúc ' . $schedule->check_in_at->format('H:i d/m/Y'));
            }
            $schedule->update([
                'check_in_at' => now(),
                'status' => 'confirmed',
            ]);
            return back()->with('success', 'Chấm công VÀO ca thành công lúc ' . now()->format('H:i'));
        }

        if ($request->action === 'check_out') {
            if (!$schedule->check_in_at) {
                return back()->with('error', 'Bạn chưa chấm công vào ca!');
            }
            if ($schedule->check_out_at) {
                return back()->with('error', 'Bạn đã chấm công ra ca lúc ' . $schedule->check_out_at->format('H:i d/m/Y'));
            }
            $schedule->update([
                'check_out_at' => now(),
                'status' => 'completed',
            ]);
            return back()->with('success', 'Chấm công RA ca thành công lúc ' . now()->format('H:i'));
        }

        return back();
    }
}

