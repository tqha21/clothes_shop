<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'type' => 'percentage',
                'value' => 10,
                'min_order_amount' => 0,
                'usage_limit' => null,
                'expires_at' => Carbon::now()->addMonths(3),
                'is_active' => true,
            ],
            [
                'code' => 'SUMMER50K',
                'type' => 'fixed',
                'value' => 50000,
                'min_order_amount' => 300000,
                'usage_limit' => 100,
                'expires_at' => Carbon::now()->addMonths(2),
                'is_active' => true,
            ],
            [
                'code' => 'MEGA20',
                'type' => 'percentage',
                'value' => 20,
                'min_order_amount' => 500000,
                'usage_limit' => 50,
                'expires_at' => Carbon::now()->addMonth(),
                'is_active' => true,
            ],
            [
                'code' => 'FREESHIP',
                'type' => 'fixed',
                'value' => 30000,
                'min_order_amount' => 200000,
                'usage_limit' => null,
                'expires_at' => Carbon::now()->addMonths(6),
                'is_active' => true,
            ],
            [
                'code' => 'VIP100K',
                'type' => 'fixed',
                'value' => 100000,
                'min_order_amount' => 1000000,
                'usage_limit' => 20,
                'expires_at' => Carbon::now()->addWeeks(2),
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::firstOrCreate(['code' => $coupon['code']], $coupon);
        }
    }
}
