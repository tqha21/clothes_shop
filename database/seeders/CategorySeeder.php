<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Xóa dữ liệu cũ (chú ý: xóa con trước cha)
        Category::whereNotNull('parent_id')->delete();
        Category::whereNull('parent_id')->delete();

        $structure = [
            // ===== NAM =====
            'Áo khoác nam' => [
                'Áo phao nam',
                'Áo gió nam',
                'Áo chống nắng nam',
                'Áo vest nam',
                'Áo mang tô nam',
            ],
            'Áo giữ nhiệt nam' => [
                'Áo giữ nhiệt dài tay nam',
                'Áo giữ nhiệt ngắn tay nam',
            ],
            'Áo nam' => [
                'Áo thun nam',
                'Áo polo nam',
                'Áo sơ mi nam',
                'Áo len nam',
            ],
            'Quần nam' => [
                'Quần jean nam',
                'Quần kaki nam',
                'Quần short nam',
                'Quần jogger nam',
            ],
            'Đồ bộ nam' => [
                'Bộ mặc nhà nam',
                'Bộ thể thao nam',
            ],
            'Đồ thể thao nam' => [
                'Áo thể thao nam',
                'Quần thể thao nam',
            ],
            // ===== NỮ =====
            'Áo khoác nữ' => [
                'Áo phao nữ',
                'Áo gió nữ',
                'Áo chống nắng nữ',
                'Áo vest nữ',
                'Áo mang tô nữ',
            ],
            'Áo giữ nhiệt nữ' => [
                'Áo giữ nhiệt dài tay nữ',
                'Áo giữ nhiệt ngắn tay nữ',
            ],
            'Áo nữ' => [
                'Áo thun nữ',
                'Áo polo nữ',
                'Áo sơ mi nữ',
                'Áo len nữ',
                'Áo croptop nữ',
            ],
            'Quần nữ' => [
                'Quần jean nữ',
                'Quần kaki nữ',
                'Quần short nữ',
                'Quần palazzo nữ',
            ],
            'Đồ bộ nữ' => [
                'Bộ mặc nhà nữ',
                'Bộ thể thao nữ',
            ],
            'Đồ thể thao nữ' => [
                'Áo thể thao nữ',
                'Quần thể thao nữ',
            ],
            'Đầm và chân váy nữ' => [
                'Đầm liền thân',
                'Chân váy ngắn',
                'Chân váy dài',
                'Đầm dự tiệc',
            ],
            'Phụ kiện nữ' => [
                'Túi xách nữ',
                'Mũ nón nữ',
                'Thắt lưng nữ',
            ],
            // ===== TRẺ EM =====
            'Áo giữ nhiệt trẻ em' => [
                'Áo giữ nhiệt bé trai',
                'Áo giữ nhiệt bé gái',
            ],
            'Áo trẻ em' => [
                'Áo thun bé trai',
                'Áo thun bé gái',
                'Áo khoác bé trai',
                'Áo khoác bé gái',
            ],
            'Quần trẻ em' => [
                'Quần bé trai',
                'Quần bé gái',
                'Quần short trẻ em',
            ],
            'Đồ bộ trẻ em' => [
                'Bộ mặc nhà trẻ em',
                'Bộ thể thao trẻ em',
            ],
            'Sản phẩm khác' => [
                'Đồng phục học sinh',
                'Đồ ngủ trẻ em',
            ],
            'Phụ kiện trẻ em' => [
                'Mũ trẻ em',
                'Tất trẻ em',
            ],
        ];

        foreach ($structure as $parentName => $children) {
            $parent = Category::create([
                'name'      => $parentName,
                'slug'      => Str::slug($parentName) . '-' . uniqid(),
                'parent_id' => null,
            ]);

            foreach ($children as $childName) {
                Category::create([
                    'name'      => $childName,
                    'slug'      => Str::slug($childName) . '-' . uniqid(),
                    'parent_id' => $parent->id,
                ]);
            }
        }

        $this->command->info('✅ Category seeder chạy thành công! Đã tạo ' . Category::count() . ' danh mục.');
    }
}
