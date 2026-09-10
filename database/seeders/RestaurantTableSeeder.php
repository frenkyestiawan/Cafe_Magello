<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RestaurantTable;

class RestaurantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tables = [
            ['table_number' => '01', 'capacity' => 2, 'is_available' => true],
            ['table_number' => '02', 'capacity' => 2, 'is_available' => true],
            ['table_number' => '03', 'capacity' => 4, 'is_available' => true],
            ['table_number' => '04', 'capacity' => 4, 'is_available' => true],
            ['table_number' => '05', 'capacity' => 6, 'is_available' => true],
            ['table_number' => '06', 'capacity' => 6, 'is_available' => true],
        ];

        foreach ($tables as $table) {
            RestaurantTable::create($table);
        }
    }
}
