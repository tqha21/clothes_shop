<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreStock;
use App\Models\User;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class POSController extends Controller
{
    public function __construct(protected StockService $stockService)
    {
        $this->middleware('auth');
    }

    /**
     * Màn hình bán hàng tại quầy.
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // 1. Xác định cửa hàng làm việc
        $stores = Store::where('status', 'active')->get();
        $selectedStoreId = (int) ($request->store_id ?? $user->store_id ?? $stores->first()?->id ?? 1);

        if (!$user->canAccessStore($selectedStoreId)) {
            $selectedStoreId = (int) ($user->store_id ?? $stores->first()?->id ?? 1);
        }

        $currentStore = Store::find($selectedStoreId);

        // 2. Phiên ca hiện tại của nhân viên
        $activeSession = $user->currentShiftSession();

        // 3. Danh mục sản phẩm
        $categories = Category::all();

        // 4. Lịch sử các đơn POS gần đây của cửa hàng này
        $recentOrders = Order::pos()
            ->where('store_id', $selectedStoreId)
            ->with(['items.product', 'staff'])
            ->latest()
            ->take(10)
            ->get();

        return view('pos.index', compact('user', 'stores', 'currentStore', 'activeSession', 'categories', 'recentOrders'));
    }

    /**
     * API Tìm kiếm sản phẩm theo từ khóa / danh mục / tồn kho tại cửa hàng.
     */
    public function products(Request $request)
    {
        $storeId = (int) $request->input('store_id', Auth::user()->store_id ?? 1);
        $search = trim($request->input('q', ''));
        $categoryId = $request->input('category_id');

        $query = Product::with('category');

        if ($categoryId && $categoryId !== 'all') {
            $query->where('category_id', $categoryId);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $products = $query->take(30)->get();

        // Nạp số lượng tồn tại cửa hàng
        $productIds = $products->pluck('id')->all();
        $stockMap = StoreStock::where('store_id', $storeId)
            ->whereIn('product_id', $productIds)
            ->pluck('quantity', 'product_id')
            ->all();

        $data = $products->map(function ($p) use ($stockMap) {
            $stock = $stockMap[$p->id] ?? 0;
            return [
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->sku,
                'price' => (float) $p->price,
                'sale_price' => (float) $p->sale_price,
                'effective_price' => $p->effective_price,
                'stock' => (int) $stock,
                'image_url' => $p->image ? asset('storage/' . $p->image) : asset('images/default-product.png'),
                'sizes' => is_array($p->sizes) ? $p->sizes : [],
                'colors' => is_array($p->colors) ? $p->colors : [],
                'category_name' => $p->category->name ?? '',
            ];
        });

        return response()->json(['success' => true, 'products' => $data]);
    }

    /**
     * Tra cứu nhanh bằng mã vạch / SKU hoặc ID sản phẩm.
     */
    public function barcodeLookup(Request $request)
    {
        $code = trim($request->input('code', ''));
        $storeId = (int) $request->input('store_id', Auth::user()->store_id ?? 1);

        // Hỗ trợ mã định dạng: SP00012, 12, hoặc tên
        $productId = null;
        if (preg_match('/^SP(\d+)$/i', $code, $m)) {
            $productId = (int) $m[1];
        } elseif (is_numeric($code)) {
            $productId = (int) $code;
        }

        $product = null;
        if ($productId) {
            $product = Product::with('category')->find($productId);
        }
        if (!$product) {
            $product = Product::with('category')->where('name', 'like', "%{$code}%")->first();
        }

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm với mã này.']);
        }

        $stock = StoreStock::where('store_id', $storeId)->where('product_id', $product->id)->value('quantity') ?? 0;

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'price' => (float) $product->price,
                'effective_price' => $product->effective_price,
                'stock' => (int) $stock,
                'image_url' => $product->image ? asset('storage/' . $product->image) : asset('images/default-product.png'),
                'sizes' => is_array($product->sizes) ? $product->sizes : [],
                'colors' => is_array($product->colors) ? $product->colors : [],
            ]
        ]);
    }

    /**
     * Tra cứu khách hàng theo SĐT để tích điểm / chiết khấu thành viên.
     */
    public function searchCustomer(Request $request)
    {
        $phone = trim($request->input('phone', ''));
        if (!$phone) {
            return response()->json(['success' => false, 'message' => 'Vui lòng nhập số điện thoại.']);
        }

        $customer = User::where('phone', $phone)->orWhere('email', $phone)->first();
        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'Khách hàng chưa có trong hệ thống.']);
        }

        return response()->json([
            'success' => true,
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'points' => (int) $customer->points,
                'tier' => $customer->membership_tier_label,
                'discount_percent' => (int) $customer->discount_percent,
            ]
        ]);
    }
}

