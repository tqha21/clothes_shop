<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@shop.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'name' => 'Nguyễn Văn A',
            'email' => 'customer@shop.com',
            'password' => bcrypt('customer123'),
            'role' => 'customer',
        ]);
    }
}
