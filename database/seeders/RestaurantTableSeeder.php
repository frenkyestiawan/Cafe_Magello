<?php

namespace Database\Seeders;

use App\Models\RestaurantTable;
use App\Services\TableQrCodeGenerator;
use Illuminate\Database\Seeder;

class RestaurantTableSeeder extends Seeder
{
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

        foreach ($tables as $tableData) {
            $table = RestaurantTable::firstOrCreate(
                ['table_number' => $tableData['table_number']],
                [
                    'capacity' => $tableData['capacity'],
                    'is_available' => $tableData['is_available'],
                ]
            );

            $qrCodeUrl = app(TableQrCodeGenerator::class)->generate($table);

            $table->update([
                'capacity' => $tableData['capacity'],
                'is_available' => $tableData['is_available'],
                'qr_code' => $qrCodeUrl,
            ]);

            echo "Meja {$table->table_number} - QR Code: {$qrCodeUrl}\n";
        }
    }
}
