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
        // Hapus semua meja yang ada untuk fresh start
        RestaurantTable::query()->delete();
        
        $tables = [
            ['table_number' => '01', 'capacity' => 2, 'is_available' => true],
            ['table_number' => '02', 'capacity' => 2, 'is_available' => true],
            ['table_number' => '03', 'capacity' => 4, 'is_available' => true],
            ['table_number' => '04', 'capacity' => 4, 'is_available' => true],
            ['table_number' => '05', 'capacity' => 6, 'is_available' => true],
            ['table_number' => '06', 'capacity' => 6, 'is_available' => true],
        ];

        foreach ($tables as $tableData) {
            $createdTable = RestaurantTable::create($tableData);
            
            // Generate QR Code URL
            $qrCodeUrl = 'http://127.0.0.1:8000/order/table/' . $createdTable->table_number;
            $qrCodeApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrCodeUrl);
            
            // Update with QR Code
            $createdTable->qr_code = $qrCodeApiUrl;
            $createdTable->save();
            
            echo "Meja {$createdTable->table_number} - QR Code: {$qrCodeApiUrl}\n";
        }
    }
}
