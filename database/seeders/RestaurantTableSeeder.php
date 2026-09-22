<?php

namespace Database\Seeders;

use App\Models\RestaurantTable;
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

            $qrCodeUrl = 'http://127.0.0.1:8000/order/table/' . $table->table_number;
            $qrCodeApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrCodeUrl);

            $table->update([
                'capacity' => $tableData['capacity'],
                'is_available' => $tableData['is_available'],
                'qr_code' => $qrCodeApiUrl,
            ]);

            echo "Meja {$table->table_number} - QR Code: {$qrCodeApiUrl}\n";
        }
    }
}
