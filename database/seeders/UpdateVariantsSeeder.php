<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class UpdateVariantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::all()->each(function ($product) {
            $name = mb_strtolower($product->name, 'UTF-8');
            $catName = mb_strtolower($product->category->name ?? '', 'UTF-8');

            // Xác định pool màu theo loại sản phẩm
            if (str_contains($name, 'jean') || str_contains($name, 'bò')) {
                $pool = ['Xanh Jean Nhạt', 'Xanh Jean Đậm', 'Đen'];
            } elseif (str_contains($name, 'kaki')) {
                $pool = ['Be', 'Nâu', 'Đen', 'Xanh Rêu', 'Kem'];
            } elseif (str_contains($name, 'trắng')) {
                $pool = ['Trắng', 'Be', 'Xám Nhạt'];
            } elseif (str_contains($name, 'đen')) {
                $pool = ['Đen', 'Xám Đậm', 'Xanh Navy'];
            } elseif (str_contains($name, 'hồng')) {
                $pool = ['Hồng', 'Trắng', 'Be'];
            } elseif (str_contains($name, 'thun') || str_contains($name, 'cotton')) {
                $pool = ['Đen', 'Trắng', 'Xanh Navy', 'Xám', 'Đỏ'];
            } elseif (str_contains($name, 'mũ') || str_contains($name, 'nón')) {
                $pool = ['Đen', 'Trắng', 'Đỏ', 'Be'];
            } elseif (str_contains($catName, 'quần')) {
                $pool = ['Đen', 'Xám', 'Xanh Navy', 'Be', 'Nâu'];
            } else {
                $pool = ['Đen', 'Trắng', 'Xanh Navy', 'Be', 'Xám'];
            }

            // Số màu phải BẰNG số ảnh (main + extras) để không bị mismatch
            $extraImages = is_array($product->images) ? $product->images : (json_decode($product->images ?? '[]', true) ?? []);
            $totalImgs = 1 + count($extraImages);
            $poolCount  = count($pool);

            $colors = [];
            for ($i = 0; $i < $totalImgs; $i++) {
                $colors[] = $pool[$i % $poolCount];
            }

            // Sizes
            if ($product->category_id == 5) {
                $product->sizes = ['Free Size'];
            } else {
                $product->sizes = ['S', 'M', 'L', 'XL'];
            }

            $product->colors = $colors;
            $product->save();
        });
    }
}
