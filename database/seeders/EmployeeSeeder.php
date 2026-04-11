<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            [
                'name' => 'Nguyễn Văn An',
                'email' => 'nva@htdstore.com',
                'phone' => '0901234567',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'employee_code' => 'NV001',
                'position' => 'Nhân viên bán hàng',
                'department' => 'Kinh doanh',
                'hire_date' => '2024-01-15',
                'salary' => 10000000,
                'status' => 'active',
            ],
            [
                'name' => 'Trần Thị Bình',
                'email' => 'ttb@htdstore.com',
                'phone' => '0902345678',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'employee_code' => 'NV002',
                'position' => 'Trưởng phòng kinh doanh',
                'department' => 'Kinh doanh',
                'hire_date' => '2023-06-01',
                'salary' => 15000000,
                'status' => 'active',
            ],
            [
                'name' => 'Lê Văn Cường',
                'email' => 'lvc@htdstore.com',
                'phone' => '0903456789',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'employee_code' => 'NV003',
                'position' => 'Nhân viên kho',
                'department' => 'Kho vận',
                'hire_date' => '2024-03-10',
                'salary' => 9000000,
                'status' => 'active',
            ],
            [
                'name' => 'Phạm Thị Dung',
                'email' => 'ptd@htdstore.com',
                'phone' => '0904567890',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'employee_code' => 'NV004',
                'position' => 'Nhân viên marketing',
                'department' => 'Marketing',
                'hire_date' => '2023-09-20',
                'salary' => 12000000,
                'status' => 'active',
            ],
            [
                'name' => 'Hoàng Văn Em',
                'email' => 'hve@htdstore.com',
                'phone' => '0905678901',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'employee_code' => 'NV005',
                'position' => 'Kế toán',
                'department' => 'Tài chính',
                'hire_date' => '2023-11-05',
                'salary' => 13000000,
                'status' => 'active',
            ],
            [
                'name' => 'Đỗ Thị Phương',
                'email' => 'dtp@htdstore.com',
                'phone' => '0906789012',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'employee_code' => 'NV006',
                'position' => 'Nhân viên bán hàng',
                'department' => 'Kinh doanh',
                'hire_date' => '2024-02-01',
                'salary' => 10000000,
                'status' => 'active',
            ],
            [
                'name' => 'Vũ Văn Giang',
                'email' => 'vvg@htdstore.com',
                'phone' => '0907890123',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'employee_code' => 'NV007',
                'position' => 'Nhân viên IT',
                'department' => 'Công nghệ',
                'hire_date' => '2023-08-15',
                'salary' => 14000000,
                'status' => 'active',
            ],
            [
                'name' => 'Ngô Thị Hoa',
                'email' => 'nth@htdstore.com',
                'phone' => '0908901234',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'employee_code' => 'NV008',
                'position' => 'Nhân viên chăm sóc khách hàng',
                'department' => 'Dịch vụ khách hàng',
                'hire_date' => '2024-01-20',
                'salary' => 9500000,
                'status' => 'active',
            ],
        ];

        foreach ($employees as $employee) {
            User::create($employee);
        }

        $this->command->info('Đã tạo ' . count($employees) . ' nhân viên mẫu!');
    }
}
