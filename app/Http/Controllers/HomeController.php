<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Featured products (latest 8)
        $featuredProducts = Product::latest()->take(8)->get();
        
        // New arrivals (latest 15 - 5 for flash sale, 10 for new arrivals section)
        $newArrivals = Product::newArrivals()->take(15)->get();
        
        // Best sellers (top 8 by sold_count)
        $bestSellers = Product::bestSellers()->take(8)->get();
        
        // All categories
        $categories = Category::all();
        
        // Featured categories (first 4)
        $featuredCategories = Category::take(4)->get();
        
        // Sơ mi Nam
        $categorySomi = Category::where('name', 'like', '%sơ mi%')->first();
        $somiProducts = Product::whereHas('category', function($q){ $q->where('name', 'like', '%sơ mi%'); })->take(4)->get();
        
        // Áo Polo
        $categoryPolo = Category::where('name', 'like', '%polo%')->first();
        $poloProducts = Product::whereHas('category', function($q){ $q->where('name', 'like', '%polo%'); })->take(4)->get();
        
        // Jeans Flex
        $categoryJeans = Category::where('name', 'like', '%jean%')->first();
        $jeansProducts = Product::whereHas('category', function($q){ $q->where('name', 'like', '%jean%'); })->take(4)->get();

        // Quần Âu (dùng kaki tạm thay)
        $categoryQuanAu = Category::where('name', 'like', '%kaki%')->orWhere('name', 'like', '%quần âu%')->first();
        $quanAuProducts = Product::whereHas('category', function($q){ 
            $q->where('name', 'like', '%kaki%')->orWhere('name', 'like', '%quần âu%'); 
        })->take(4)->get();

        // Áo Thun Basic
        $categoryAoThun = Category::where('name', 'like', '%thun%')->first();
        $aoThunProducts = Product::whereHas('category', function($q){ $q->where('name', 'like', '%thun%'); })->take(4)->get();

        // Áo Phao 4S & Áo Khoác
        $categoryAoPhao = Category::where('name', 'like', '%phao%')->first();
        $aoPhaoProducts = Product::whereHas('category', function($q) {
            $q->where('name', 'like', '%phao%')->orWhere('name', 'like', '%khoác%');
        })->take(4)->get();
        
        // Phụ kiện trẻ em
        $categoryPhuKien = Category::where('name', 'like', '%phụ kiện trẻ em%')->first() 
            ?? Category::where('name', 'like', '%trẻ em%')->first()
            ?? Category::where('name', 'like', '%phụ kiện%')->first();

        $phuKienProducts = $categoryPhuKien ? Product::whereHas('category', function($q) use ($categoryPhuKien) {
            $q->where('id', $categoryPhuKien->id);
        })->take(4)->get() : collect();
        
        return view('home', compact(
            'featuredProducts',
            'newArrivals',
            'bestSellers',
            'categories',
            'featuredCategories',
            'somiProducts',
            'poloProducts',
            'categoryJeans',
            'jeansProducts',
            'categoryQuanAu',
            'quanAuProducts',
            'categoryAoThun',
            'aoThunProducts',
            'categoryAoPhao',
            'aoPhaoProducts',
            'categoryPhuKien',
            'phuKienProducts'
        ));
    }
}
