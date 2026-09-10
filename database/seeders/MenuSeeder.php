<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'name' => 'Kopi Susu Gula Aren',
                'price' => 25000,
                'description' => 'Kopi susu dengan gula aren asli',
                'is_available' => true,
            ],
            [
                'name' => 'Es Teh Manis',
                'price' => 10000,
                'description' => 'Teh manis dingin segar',
                'is_available' => true,
            ],
            [
                'name' => 'Nasi Goreng',
                'price' => 35000,
                'description' => 'Nasi goreng spesial dengan telur',
                'is_available' => true,
            ],
            [
                'name' => 'Mie Goreng',
                'price' => 30000,
                'description' => 'Mie goreng dengan sayuran',
                'is_available' => true,
            ],
            [
                'name' => 'Dimsum',
                'price' => 15000,
                'description' => 'Dimsum ayam lembut',
                'is_available' => true,
            ],
            [
                'name' => 'Kentang Goreng',
                'price' => 12000,
                'description' => 'Kentang goreng renyah',
                'is_available' => true,
            ],
            [
                'name' => 'Americano',
                'price' => 20000,
                'description' => 'Kopi hitam Americano',
                'is_available' => true,
            ],
            [
                'name' => 'Latte',
                'price' => 28000,
                'description' => 'Latte dengan susu segar',
                'is_available' => true,
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
