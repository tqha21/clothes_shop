<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class FixColorImageSeeder extends Seeder
{
    public function run()
    {
        // Define color hex map for images
        $colorImages = [
            'Đen' => ['hex' => '000000', 'name' => 'Den'],
            'Trắng' => ['hex' => 'ffffff', 'text_color' => '000000', 'name' => 'Trang'],
            'Đỏ' => ['hex' => 'ef4444', 'name' => 'Do'],
            'Xanh dương' => ['hex' => '3b82f6', 'name' => 'Xanh Duong'],
            'Vàng' => ['hex' => 'eab308', 'text_color' => '000000',  'name' => 'Vang'],
            'Xanh lá' => ['hex' => '22c55e', 'text_color' => '000000', 'name' => 'Xanh La'],
            'Xám' => ['hex' => '6b7280', 'name' => 'Xam'],
            'Hồng' => ['hex' => 'ec4899', 'name' => 'Hong'],
            'Tím' => ['hex' => 'a855f7', 'name' => 'Tim'],
            'Cam' => ['hex' => 'f97316', 'text_color' => '000000', 'name' => 'Cam'],
            'Nâu' => ['hex' => '78350f', 'name' => 'Nau'],
            'Be' => ['hex' => 'f5f5dc', 'text_color' => '000000', 'name' => 'Be'],
            'Xanh Navy' => ['hex' => '1e3a8a', 'name' => 'Navy'],
            'Xanh than' => ['hex' => '1e3a8a', 'name' => 'Navy'],
            'Ghi' => ['hex' => '9ca3af', 'text_color' => '000000', 'name' => 'Ghi'],
        ];

        Product::all()->each(function ($product) use ($colorImages) {
            if (!$product->colors || count($product->colors) == 0) return;

            $images = [];
            $brand = explode(' ', $product->name)[1] ?? 'Item';
            
            // First color goes to main image
            $firstColor = $product->colors[0];
            $config = $colorImages[$firstColor] ?? ['hex' => 'cccccc', 'name' => 'Color'];
            $textColor = $config['text_color'] ?? 'ffffff';
            $mainImage = 'dummy_url|https://placehold.co/600x800/' . $config['hex'] . '/' . $textColor . '/png?text=' . urlencode($brand . "\r\n" . $config['name']);
            $product->image = $mainImage;

            // Rest colors go to images array
            for ($i = 1; $i < count($product->colors); $i++) {
                $color = $product->colors[$i];
                $config = $colorImages[$color] ?? ['hex' => 'cccccc', 'name' => 'Color'];
                $textColor = $config['text_color'] ?? 'ffffff';
                $images[] = 'dummy_url|https://placehold.co/600x800/' . $config['hex'] . '/' . $textColor . '/png?text=' . urlencode($brand . "\r\n" . $config['name']);
            }
            
            $product->images = empty($images) ? null : $images;
            $product->save();
        });
        
        $this->command->info("✅ Fixed images matching colors for all products!");
    }
}
