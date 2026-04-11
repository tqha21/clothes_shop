<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stores = [
            [
                'name' => 'Clothers Shops - Hoàn Kiếm',
                'address' => '123 Phố Huế, Quận Hoàn Kiếm, Hà Nội',
                'city' => 'Hà Nội',
                'district' => 'Hoàn Kiếm',
                'phone' => '024-3825-xxxx',
                'latitude' => 21.0285,
                'longitude' => 105.8542,
                'opening_hours' => '8:00 - 22:00 (Hàng ngày)',
                'status' => 'active',
                'image_url' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=600&h=400&fit=crop',
            ],
            [
                'name' => 'Clothers Shops - Cầu Giấy',
                'address' => '456 Trần Duy Hưng, Quận Cầu Giấy, Hà Nội',
                'city' => 'Hà Nội',
                'district' => 'Cầu Giấy',
                'phone' => '024-3756-xxxx',
                'latitude' => 21.0333,
                'longitude' => 105.7944,
                'opening_hours' => '8:00 - 22:00 (Hàng ngày)',
                'status' => 'active',
                'image_url' => 'https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?w=600&h=400&fit=crop',
            ],
            [
                'name' => 'Clothers Shops - Quận 1',
                'address' => '789 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh',
                'city' => 'TP. Hồ Chí Minh',
                'district' => 'Quận 1',
                'phone' => '028-3822-xxxx',
                'latitude' => 10.7769,
                'longitude' => 106.7009,
                'opening_hours' => '8:00 - 22:00 (Hàng ngày)',
                'status' => 'active',
                'image_url' => 'https://images.unsplash.com/photo-1567401893414-76b7b1e5a7a5?w=600&h=400&fit=crop',
            ],
            [
                'name' => 'Clothers Shops - Phú Nhuận',
                'address' => '321 Phan Đăng Lưu, Quận Phú Nhuận, TP.HCM',
                'city' => 'TP. Hồ Chí Minh',
                'district' => 'Phú Nhuận',
                'phone' => '028-3997-xxxx',
                'latitude' => 10.7993,
                'longitude' => 106.6767,
                'opening_hours' => '8:00 - 22:00 (Hàng ngày)',
                'status' => 'active',
                'image_url' => 'https://images.unsplash.com/photo-1528698827591-e19ccd7bc23d?w=600&h=400&fit=crop',
            ],
            [
                'name' => 'Clothers Shops - Hải Châu',
                'address' => '159 Trần Phú, Quận Hải Châu, Đà Nẵng',
                'city' => 'Đà Nẵng',
                'district' => 'Hải Châu',
                'phone' => '0236-3888-xxxx',
                'latitude' => 16.0544,
                'longitude' => 108.2022,
                'opening_hours' => '8:00 - 22:00 (Hàng ngày)',
                'status' => 'active',
                'image_url' => 'https://images.unsplash.com/photo-1604719312566-8912e9227c6a?w=600&h=400&fit=crop',
            ],
            [
                'name' => 'Clothers Shops - Ninh Kiều',
                'address' => '753 Trần Hưng Đạo, Quận Ninh Kiều, Cần Thơ',
                'city' => 'Cần Thơ',
                'district' => 'Ninh Kiều',
                'phone' => '0292-3812-xxxx',
                'latitude' => 10.0452,
                'longitude' => 105.7469,
                'opening_hours' => '8:00 - 22:00 (Hàng ngày)',
                'status' => 'active',
                'image_url' => 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?w=600&h=400&fit=crop',
            ],
        ];

        foreach ($stores as $store) {
            Store::create($store);
        }
    }
}
