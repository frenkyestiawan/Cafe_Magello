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
            // Kopi (category_id: 1)
            [
                'name' => 'Kopi Susu Gula Aren',
                'price' => 25000,
                'category_id' => 1,
                'description' => 'Kopi susu dengan gula aren asli',
                'is_available' => true,
            ],
            [
                'name' => 'Americano',
                'price' => 20000,
                'category_id' => 1,
                'description' => 'Kopi hitam Americano',
                'is_available' => true,
            ],
            [
                'name' => 'Latte',
                'price' => 28000,
                'category_id' => 1,
                'description' => 'Latte dengan susu segar',
                'is_available' => true,
            ],
            [
                'name' => 'Cappuccino',
                'price' => 30000,
                'category_id' => 1,
                'description' => 'Cappuccino creamy',
                'is_available' => true,
            ],
            [
                'name' => 'Espresso',
                'price' => 18000,
                'category_id' => 1,
                'description' => 'Espresso strong',
                'is_available' => true,
            ],

            // Non-Kopi (category_id: 2)
            [
                'name' => 'Es Teh Manis',
                'price' => 10000,
                'category_id' => 2,
                'description' => 'Teh manis dingin segar',
                'is_available' => true,
            ],
            [
                'name' => 'Lemon Tea',
                'price' => 12000,
                'category_id' => 2,
                'description' => 'Teh lemon segar',
                'is_available' => true,
            ],
            [
                'name' => 'Jus Jeruk',
                'price' => 15000,
                'category_id' => 2,
                'description' => 'Jus jeruk segar',
                'is_available' => true,
            ],
            [
                'name' => 'Matcha Latte',
                'price' => 25000,
                'category_id' => 2,
                'description' => 'Matcha latte creamy',
                'is_available' => true,
            ],

            // Makanan (category_id: 3)
            [
                'name' => 'Nasi Goreng',
                'price' => 35000,
                'category_id' => 3,
                'description' => 'Nasi goreng spesial dengan telur',
                'is_available' => true,
            ],
            [
                'name' => 'Mie Goreng',
                'price' => 30000,
                'category_id' => 3,
                'description' => 'Mie goreng dengan sayuran',
                'is_available' => true,
            ],
            [
                'name' => 'Spaghetti',
                'price' => 40000,
                'category_id' => 3,
                'description' => 'Spaghetti bolognese',
                'is_available' => true,
            ],
            [
                'name' => 'Nasi Campur',
                'price' => 32000,
                'category_id' => 3,
                'description' => 'Nasi campur lengkap',
                'is_available' => true,
            ],

            // Camilan (category_id: 4)
            [
                'name' => 'Dimsum',
                'price' => 15000,
                'category_id' => 4,
                'description' => 'Dimsum ayam lembut',
                'is_available' => true,
            ],
            [
                'name' => 'Kentang Goreng',
                'price' => 12000,
                'category_id' => 4,
                'description' => 'Kentang goreng renyah',
                'is_available' => true,
            ],
            [
                'name' => 'Roti Bakar',
                'price' => 18000,
                'category_id' => 4,
                'description' => 'Roti bakar dengan topping',
                'is_available' => true,
            ],
            [
                'name' => 'Pisang Goreng',
                'price' => 15000,
                'category_id' => 4,
                'description' => 'Pisang goreng crispy',
                'is_available' => true,
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
