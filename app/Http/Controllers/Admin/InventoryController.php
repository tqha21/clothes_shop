<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Models\InventoryLog;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreStock;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function __construct(protected StockService $stockService)
    {
    }

    /**
     * Bảng kiểm kho đa cửa hàng.
     */
    public function index(Request $request)
    {
        $stores = Store::where('status', 'active')->get();
        $selectedStoreId = $request->input('store_id');
        $search = trim($request->input('search', ''));

        $query = Product::with(['category', 'storeStocks.store'])->latest();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(20);

        // Nạp số lượng tồn theo cửa hàng được chọn (nếu có)
        $storeStocksMap = [];
        if ($selectedStoreId) {
            $storeStocksMap = StoreStock::where('store_id', $selectedStoreId)
                ->pluck('quantity', 'product_id')
                ->all();
        }

        return view('admin.inventory.index', compact('products', 'stores', 'selectedStoreId', 'storeStocksMap'));
    }

    /**
     * Lịch sử biến động kho (Audit Trail).
     */
    public function history(Request $request)
    {
        $query = InventoryLog::with(['product', 'user', 'store', 'order'])->latest();

        if ($request->filled('store_id')) {
            $query->where('store_id', $request->store_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        $logs = $query->paginate(50);
        $stores = Store::where('status', 'active')->get();

        return view('admin.inventory.history', compact('logs', 'stores'));
    }

    /**
     * Nhập / Xuất / Điều chỉnh / Kiểm kê kho.
     */
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'store_id' => 'nullable|exists:stores,id',
            'type' => 'required|in:import,export,adjustment,stocktake',
            'quantity' => 'required|integer|min:0',
            'note' => 'nullable|string',
        ]);

        $storeId = (int) ($request->store_id ?: $this->stockService->defaultStoreId());
        $productId = (int) $request->product_id;
        $qty = (int) $request->quantity;
        $userId = Auth::id();
        $note = $request->note;

        try {
            if ($request->type === 'import') {
                $this->stockService->import($storeId, $productId, $qty, $userId, $note);
            } elseif ($request->type === 'export') {
                $this->stockService->export($storeId, $productId, $qty, $userId, $note);
            } else {
                $this->stockService->adjust($storeId, $productId, $qty, $userId, $note, $request->type);
            }

            $product = Product::find($productId);
            $store = Store::find($storeId);

            return back()->with('success', "Cập nhật kho thành công cho sản phẩm: {$product->name} tại {$store->name}!");
        } catch (BusinessException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Chuyển kho giữa 2 cửa hàng (Stock Transfer).
     */
    public function transfer(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'from_store_id' => 'required|exists:stores,id',
            'to_store_id' => 'required|exists:stores,id|different:from_store_id',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:255',
        ]);

        try {
            $transfer = $this->stockService->transfer(
                fromStoreId: (int) $request->from_store_id,
                toStoreId: (int) $request->to_store_id,
                productId: (int) $request->product_id,
                qty: (int) $request->quantity,
                userId: Auth::id(),
                note: $request->note
            );

            return back()->with('success', "Chuyển kho thành công! Mã phiếu chuyển: {$transfer->code}");
        } catch (BusinessException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
