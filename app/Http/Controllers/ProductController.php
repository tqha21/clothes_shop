<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Lọc theo từ khóa tìm kiếm (Search)
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('category', function($qCat) use ($searchTerm) {
                      $qCat->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // 1. Lọc theo Danh mục (Category)
        // Nếu người dùng chọn một danh mục cụ thể (vd: "Quần nam" id=45)
        // Chúng ta lấy ID đó và tất cả các ID con của nó.
        if ($request->has('category') && $request->category != '') {
            $catId = $request->category;
            $allCatIds = Category::where('id', $catId)->orWhere('parent_id', $catId)->pluck('id');
            $query->whereIn('category_id', $allCatIds);
        } else {
            // 2. Lọc theo Nhóm (Gender) CHỈ KHI KHÔNG có category cụ thể
            // Tránh việc "Nam" đè lên "Quần nam" và hiện ra cả "Áo nam"
            if ($request->has('gender') && $request->gender != '') {
                $g = $request->gender;
                
                $catQuery = Category::query();
                if ($g === 'nam') {
                    $catQuery->where('name', 'like', '% nam%')->where('name', 'not like', '%trẻ%');
                } elseif ($g === 'nu') {
                    $catQuery->where('name', 'like', '% nữ%')->where('name', 'not like', '%trẻ%');
                } elseif ($g === 'tre-em') {
                    $catQuery->where(function($q) {
                        $q->where('name', 'like', '%trẻ em%')
                          ->orWhere('name', 'like', '%bé gái%')
                          ->orWhere('name', 'like', '%bé trai%');
                    });
                } elseif ($g === 'be-trai') {
                    $catQuery->where('name', 'like', '%bé trai%');
                } elseif ($g === 'be-gai') {
                    $catQuery->where('name', 'like', '%bé gái%');
                }
                
                $categoryIds = $catQuery->pluck('id');
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Price filter
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;
            case 'best_selling':
                $query->orderBy('sold_count', 'desc');
                break;
            case 'sale':
                $query->whereNotNull('sale_price')
                      ->whereRaw('sale_price < price')
                      ->orderByRaw('(price - sale_price) / price DESC');
                break;
            default: // newest
                $query->latest();
        }

        $perPage = 12;
        $products = $query->paginate($perPage);
        // Sidebar Categories: Lấy Danh mục Gốc (Cha) và Loại bỏ mục rỗng
        $categoriesQuery = Category::whereNull('parent_id')->where(function ($q) {
            $q->has('products')->orHas('children.products');
        });

        if ($request->has('gender') && $request->gender != '') {
            $g = $request->gender;
            if ($g === 'nam') {
                $categoriesQuery->where('name', 'like', '% nam%')->where('name', 'not like', '%trẻ%');
            } elseif ($g === 'nu') {
                $categoriesQuery->where('name', 'like', '% nữ%')->where('name', 'not like', '%trẻ%');
            } elseif (in_array($g, ['tre-em', 'be-trai', 'be-gai'])) {
                $categoriesQuery->where(function($q) {
                    $q->where('name', 'like', '%trẻ em%')
                      ->orWhere('name', 'like', '%bé%');
                });
            }
        }
        $categories = $categoriesQuery->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->with(['category', 'reviews' => function($query) {
            $query->approved()->latest()->take(10);
        }])->firstOrFail();
        
        // Increment view count
        $product->increment('view_count');
        
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
