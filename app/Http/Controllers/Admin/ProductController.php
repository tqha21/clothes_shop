<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('search') && $request->search != '') {
             $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('stock_status') && $request->stock_status != '') {
            if ($request->stock_status == 'in_stock') $query->where('stock', '>', 20);
            elseif ($request->stock_status == 'low_stock') $query->whereBetween('stock', [1, 20]);
            elseif ($request->stock_status == 'out_of_stock') $query->where('stock', 0);
        }

        if ($request->has('price_range') && $request->price_range != '') {
            if ($request->price_range == 'under_500') $query->where('price', '<', 500000);
            elseif ($request->price_range == '500_to_1m') $query->whereBetween('price', [500000, 1000000]);
            elseif ($request->price_range == 'over_1m') $query->where('price', '>', 1000000);
        }

        $products = $query->latest()->paginate(20)->appends($request->all());
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function quickEdit(Request $request, Product $product)
    {
        $request->validate([
            'price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0'
        ]);
        
        if ($request->has('price')) $product->price = $request->price;
        if ($request->has('sale_price')) $product->sale_price = $request->sale_price;
        if ($request->has('stock')) $product->stock = $request->stock;
        
        $product->save();
        
        return response()->json(['success' => true, 'message' => 'Cập nhật thành công!']);
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'colors' => 'nullable|array',
            'sizes' => 'nullable|array',
        ]);

        $data = $request->except(['image', 'images']);
        $slug = Str::slug($request->name);
        $count = Product::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . time();
        }
        $data['slug'] = $slug;
        $data['colors'] = array_values(array_unique($request->input('colors', [])));
        $data['sizes'] = array_values(array_unique($request->input('sizes', [])));

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/products'), $imageName);
            $data['image'] = 'products/' . $imageName;
        }

        $extraImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $imageName = time() . "_{$index}." . $file->extension();
                $file->move(public_path('storage/products'), $imageName);
                $extraImages[] = 'products/' . $imageName;
            }
            $data['images'] = $extraImages;
        }

        // Tự động trim colors để số màu = số ảnh (1 main + extra)
        $totalImgs = 1 + count($extraImages);
        if (!empty($data['colors']) && count($data['colors']) > $totalImgs) {
            $data['colors'] = array_slice($data['colors'], 0, $totalImgs);
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được tạo thành công! (Màu: ' . count($data['colors']) . ', Ảnh: ' . $totalImgs . ')');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'colors' => 'nullable|array',
            'sizes' => 'nullable|array',
        ]);

        $data = $request->except(['image', 'images']);
        $slug = Str::slug($request->name);
        $count = Product::where('slug', $slug)->where('id', '!=', $product->id)->count();
        if ($count > 0) {
            $slug = $slug . '-' . time();
        }
        $data['slug'] = $slug;
        $data['colors'] = array_values(array_unique($request->input('colors', [])));
        $data['sizes'] = array_values(array_unique($request->input('sizes', [])));

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/products'), $imageName);
            $data['image'] = 'products/' . $imageName;
        }

        // Ảnh phụ: nếu upload mới thì thay thế, không thì giữ ảnh cũ
        if ($request->hasFile('images')) {
            $newImages = [];
            foreach ($request->file('images') as $index => $file) {
                $imageName = time() . "_{$index}." . $file->extension();
                $file->move(public_path('storage/products'), $imageName);
                $newImages[] = 'products/' . $imageName;
            }
            $data['images'] = $newImages;
            $currentExtraImages = $newImages;
        } else {
            $currentExtraImages = is_array($product->images) ? $product->images : [];
        }

        // Tự động trim colors để số màu = số ảnh (1 main + extra)
        $totalImgs = 1 + count($currentExtraImages);
        if (!empty($data['colors']) && count($data['colors']) > $totalImgs) {
            $data['colors'] = array_slice($data['colors'], 0, $totalImgs);
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được cập nhật thành công! (Màu: ' . count($data['colors']) . ', Ảnh: ' . $totalImgs . ')');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được xóa thành công!');
    }
}
