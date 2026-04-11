<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\UserSavedCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Trang ví voucher: danh sách voucher đã lưu + voucher có sẵn
     */
    public function index()
    {
        $userId = Auth::id();

        // Voucher user đã lưu (load coupon kèm)
        $savedCoupons = UserSavedCoupon::with('coupon')
            ->where('user_id', $userId)
            ->orderByDesc('saved_at')
            ->get();

        // Tất cả voucher còn hiệu lực (chưa hết hạn, chưa full)
        $allCoupons = Coupon::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->where(function ($q) {
                $q->whereNull('usage_limit')->orWhereRaw('used_count < usage_limit');
            })
            ->orderByDesc('value')
            ->get();

        // ID các coupon user đã lưu
        $savedIds = $savedCoupons->pluck('coupon_id')->toArray();

        return view('vouchers.index', compact('savedCoupons', 'allCoupons', 'savedIds'));
    }

    /**
     * Lưu voucher vào ví
     */
    public function save(Request $request)
    {
        $request->validate(['coupon_id' => 'required|exists:coupons,id']);

        $userId   = Auth::id();
        $couponId = $request->coupon_id;

        // Kiểm tra đã lưu chưa
        $exists = UserSavedCoupon::where('user_id', $userId)
            ->where('coupon_id', $couponId)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Bạn đã lưu voucher này rồi!');
        }

        UserSavedCoupon::create([
            'user_id'   => $userId,
            'coupon_id' => $couponId,
            'saved_at'  => now(),
        ]);

        return back()->with('success', '🎉 Đã lưu voucher vào ví thành công!');
    }

    /**
     * Xóa voucher khỏi ví
     */
    public function remove(Request $request)
    {
        $request->validate(['coupon_id' => 'required|exists:coupons,id']);

        UserSavedCoupon::where('user_id', Auth::id())
            ->where('coupon_id', $request->coupon_id)
            ->delete();

        return back()->with('success', 'Đã xóa voucher khỏi ví.');
    }
}
