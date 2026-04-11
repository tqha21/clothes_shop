<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. CẬP NHẬT ẢNH CHO DANH MỤC CHA ĐỂ HIỂN THỊ ĐẸP TRONG MEGA MENU
        $parentCategories = Category::whereNull('parent_id')->get();
        // Dùng ảnh HTD vòng tròn đỏ như mẫu của người dùng
        $sampleImageUrl = 'https://placehold.co/400x400/DC2626/FFFFFF/png?text=HTD';

        foreach ($parentCategories as $index => $cat) {
            $cat->update([
                // Gán URL dạng dummy_url để view biết xử lý
                'image' => 'dummy_url|' . $sampleImageUrl
            ]);
        }

        // 2. TỰ ĐỘNG SINH 2 SẢN PHẨM CHO MỖI DANH MỤC CON (NAM / NỮ / TRẺ EM)
        $childCategories = Category::whereNotNull('parent_id')->get();

        $brands = ['Zara', 'H&M', 'Uniqlo', 'Canifa', 'Owen', 'Aristino', 'Nike', 'Adidas', 'Puma', 'Routine'];
        $adjectives = ['Premium', 'Essential', 'Classic', 'Modern', 'Slim Fit', 'Regular Fit', 'Oversize', 'Comfort', 'Sport', 'Tech'];
        $modelCodes = ['Air', 'Pro', 'Max', 'Ultra', 'Core', 'Originals', 'Collection', 'Edition', 'Active'];
        
        $totalProducts = 0;

        foreach ($childCategories as $cat) {
            // Trẻ em chỉ cần 1 sản phẩm, Nam/Nữ cần 2
            $catNameLower = strtolower($cat->name);
            $numProducts = (str_contains($catNameLower, 'bé') || str_contains($catNameLower, 'trẻ em') || str_contains($catNameLower, 'jean') || str_contains($catNameLower, 'thể thao')) ? 1 : 2;
            $usedNames = [];
            
            for ($i = 0; $i < $numProducts; $i++) {
                do {
                    $brand = $brands[array_rand($brands)];
                    $adj = $adjectives[array_rand($adjectives)];
                    $model = $modelCodes[array_rand($modelCodes)];
                    // Mix formulas for more variety
                    if (rand(1, 10) > 5) {
                        $name = $cat->name . ' ' . $brand . ' ' . $adj;
                    } else {
                        $name = $cat->name . ' ' . $brand . ' ' . $model . ' ' . $adj;
                    }
                } while (in_array($name, $usedNames));
                $usedNames[] = $name;
                $price = rand(15, 80) * 10000; // 150k - 800k
                $sale_price = rand(1, 4) == 1 ? $price * 0.8 : null; // 25% cơ hội có giảm giá
                
                $colorsHex = ['1E3A8A', '9D174D', '065F46', 'B45309', '4C1D95', 'DC2626', '2563EB', '059669', 'D97706', 'DB2777'];
                $bgHex = $colorsHex[array_rand($colorsHex)];
                $nameParts = explode(' ', $cat->name);
                $lastNamePart = end($nameParts);
                $productImage = 'dummy_url|https://placehold.co/600x800/' . $bgHex . '/FFFFFF/png?text=' . urlencode($brand . '\r\n' . $lastNamePart);

                Product::create([
                    'category_id' => $cat->id,
                    'name' => $name,
                    'slug' => Str::slug($name) . '-' . uniqid(),
                    'price' => $price,
                    'sale_price' => $sale_price,
                    'image' => $productImage,
                    'stock' => rand(10, 150),
                    'description' => 'Sản phẩm ' . $cat->name . ' chất lượng cao từ thương hiệu ' . $brand . '.',
                    'content' => 'Chất liệu thoáng mát, thiết kế hiện đại phù hợp với mọi độ tuổi. Đường may tỉ mỉ, form chuẩn tôn dáng. Hàng chính hãng 100%.',
                ]);
                $totalProducts++;
            }
        }

        $this->command->info('✅ Tái tạo thành công ' . $totalProducts . ' sản phẩm (2 sản phẩm/danh mục con)!');
    }
}
