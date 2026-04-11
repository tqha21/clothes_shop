<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $wishlistItems = Wishlist::where('user_id', auth()->id())
            ->with('product')
            ->latest()
            ->get();
            
        return view('wishlist.index', compact('wishlistItems'));
    }

    public function add(Product $product)
    {
        Wishlist::firstOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Đã thêm vào yêu thích!']);
        }

        return back()->with('success', 'Đã thêm vào yêu thích!');
    }

    public function remove(Product $product)
    {
        Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Đã xóa khỏi yêu thích!']);
        }

        return back()->with('success', 'Đã xóa khỏi yêu thích!');
    }
}
