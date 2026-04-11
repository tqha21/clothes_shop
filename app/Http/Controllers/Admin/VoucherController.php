<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Display a listing of all vouchers/coupons.
     */
    public function index(Request $request)
    {
        $query = Coupon::withCount('savedByUsers');

        if ($request->filled('search')) {
            $query->where('code', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $coupons = $query->latest()->paginate(15)->withQueryString();

        return view('admin.vouchers.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new voucher.
     */
    public function create()
    {
        return view('admin.vouchers.create');
    }

    /**
     * Store a newly created voucher.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code'             => 'required|string|max:50|unique:coupons,code',
            'type'             => 'required|in:percentage,fixed',
            'value'            => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit'      => 'nullable|integer|min:1',
            'expires_at'       => 'nullable|date|after:today',
            'is_active'        => 'sometimes|boolean',
        ]);

        Coupon::create([
            'code'             => strtoupper($request->code),
            'type'             => $request->type,
            'value'            => $request->value,
            'min_order_amount' => $request->min_order_amount ?? 0,
            'usage_limit'      => $request->usage_limit,
            'expires_at'       => $request->expires_at,
            'is_active'        => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Đã tạo voucher mới thành công!');
    }

    /**
     * Show the form for editing a voucher.
     */
    public function edit(Coupon $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    /**
     * Update the specified voucher.
     */
    public function update(Request $request, Coupon $voucher)
    {
        $request->validate([
            'code'             => 'required|string|max:50|unique:coupons,code,' . $voucher->id,
            'type'             => 'required|in:percentage,fixed',
            'value'            => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit'      => 'nullable|integer|min:1',
            'expires_at'       => 'nullable|date',
            'is_active'        => 'sometimes|boolean',
        ]);

        $voucher->update([
            'code'             => strtoupper($request->code),
            'type'             => $request->type,
            'value'            => $request->value,
            'min_order_amount' => $request->min_order_amount ?? 0,
            'usage_limit'      => $request->usage_limit,
            'expires_at'       => $request->expires_at,
            'is_active'        => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Cập nhật voucher thành công!');
    }

    /**
     * Remove the specified voucher.
     */
    public function destroy(Coupon $voucher)
    {
        $voucher->delete();
        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Đã xóa voucher thành công!');
    }
}
