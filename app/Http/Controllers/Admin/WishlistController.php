<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Display all favorited products across all customers.
     */
    public function index(Request $request)
    {
        $query = Wishlist::with(['user', 'product', 'product.category']);

        if ($request->filled('search')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $wishlists = $query->latest()->paginate(20)->withQueryString();

        // Top favorited products
        $topProducts = Wishlist::selectRaw('product_id, count(*) as count')
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        return view('admin.wishlist.index', compact('wishlists', 'topProducts'));
    }

    /**
     * Remove a specific wishlist entry.
     */
    public function destroy(Wishlist $wishlist)
    {
        $wishlist->delete();
        return redirect()->route('admin.wishlist.index')
            ->with('success', 'Đã xóa mục yêu thích thành công!');
    }
}
