<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of products and their current stock levels.
     */
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(20);
        return view('admin.inventory.index', compact('products'));
    }

    /**
     * Display the inventory transaction history.
     */
    public function history()
    {
        $logs = InventoryLog::with(['product', 'user'])
            ->latest()
            ->paginate(50);
        return view('admin.inventory.history', compact('logs'));
    }

    /**
     * Process stock updates (import, export, adjustment).
     */
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:import,export,adjustment',
            'quantity' => 'required|integer|min:0',
            'note' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $oldStock = $product->stock;
        $quantityChanged = 0;

        DB::beginTransaction();
        try {
            if ($request->type === 'import') {
                $product->increment('stock', $request->quantity);
                $quantityChanged = $request->quantity;
            } elseif ($request->type === 'export') {
                if ($product->stock < $request->quantity) {
                    return back()->with('error', 'Số lượng tồn kho không đủ để xuất!');
                }
                $product->decrement('stock', $request->quantity);
                $quantityChanged = -$request->quantity;
            } elseif ($request->type === 'adjustment') {
                $product->stock = $request->quantity;
                $product->save();
                $quantityChanged = $request->quantity - $oldStock;
            }

            // Create log entry
            InventoryLog::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => $request->type,
                'quantity_changed' => $quantityChanged,
                'note' => $request->note,
            ]);

            DB::commit();
            return back()->with('success', 'Cập nhật kho thành công cho sản phẩm: ' . $product->name);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
