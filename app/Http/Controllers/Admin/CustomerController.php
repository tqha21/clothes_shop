<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(Request $request)
    {
        $query = User::customers()->with('orders');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->withCount('orders')
                          ->withSum('orders', 'total')
                          ->orderBy('created_at', 'desc')
                          ->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created customer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['role'] = 'customer';

        User::create($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Thêm khách hàng mới thành công!');
    }

    /**
     * Display the specified customer.
     */
    public function show(User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        $customer->load(['orders' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(10);
        }]);

        $totalOrders = $customer->orders()->count();
        $totalSpent = $customer->orders()->sum('total');
        $recentOrders = $customer->orders()->orderBy('created_at', 'desc')->limit(10)->get();

        // Chart Data for the last 6 months
        $chartData = collect(range(5, 0))->map(function ($i) use ($customer) {
            $monthStart = now()->subMonths($i)->startOfMonth();
            $monthEnd = now()->subMonths($i)->endOfMonth();
            
            $ordersInMonth = $customer->orders()
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->get();
                
            return [
                'month' => $monthStart->format('m/Y'),
                'orders_count' => $ordersInMonth->count(),
                'total_spent' => $ordersInMonth->sum('total')
            ];
        });
        
        $chartLabels = $chartData->pluck('month')->toJson();
        $chartOrders = $chartData->pluck('orders_count')->toJson();
        $chartSpent = $chartData->pluck('total_spent')->toJson();

        return view('admin.customers.show', compact('customer', 'totalOrders', 'totalSpent', 'recentOrders', 'chartLabels', 'chartOrders', 'chartSpent'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer.
     */
    public function update(Request $request, User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($customer->id)],
            'phone' => 'nullable|string|max:20',
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Cập nhật thông tin khách hàng thành công!');
    }

    /**
     * Remove the specified customer.
     */
    public function destroy(User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        // Optional: Check if customer has orders
        if ($customer->orders()->count() > 0) {
            return back()->with('error', 'Không thể xóa khách hàng đã có đơn hàng!');
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Xóa khách hàng thành công!');
    }
}
