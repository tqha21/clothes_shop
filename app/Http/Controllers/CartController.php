<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        $price = $product->sale_price ?? $product->price;
        
        // Generate unique ID for product + variants
        $cartId = $product->id;
        if ($request->has('size')) {
            $cartId .= '_' . $request->size;
        }
        if ($request->has('color')) {
            $cartId .= '_' . $request->color;
        }

        if (isset($cart[$cartId])) {
            $cart[$cartId]['quantity'] += $request->quantity ?? 1;
        } else {
            $cart[$cartId] = [
                'id' => $product->id, // Keep original product ID reference
                'name' => $product->name,
                'price' => $price,
                'quantity' => $request->quantity ?? 1,
                'image' => $product->image,
                'size' => $request->size,
                'color' => $request->color,
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm sản phẩm vào giỏ hàng!',
                'cartCount' => count(session('cart', []))
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->product_id])) {
            $cart[$request->product_id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Đã cập nhật giỏ hàng!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }
}
