<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    public function run()
    {
        $shifts = [
            ['name' => 'Ca Sáng',  'start_time' => '06:00:00', 'end_time' => '14:00:00', 'color' => '#3B82F6'],
            ['name' => 'Ca Chiều', 'start_time' => '14:00:00', 'end_time' => '22:00:00', 'color' => '#F59E0B'],
            ['name' => 'Ca Tối',   'start_time' => '22:00:00', 'end_time' => '06:00:00', 'color' => '#8B5CF6'],
        ];

        foreach ($shifts as $shift) {
            Shift::firstOrCreate(['name' => $shift['name']], $shift);
        }
    }
}

