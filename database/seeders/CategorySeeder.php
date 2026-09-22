<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'New Menu (Drinks)',
            'New Menu (Food)',
            'Signature',
            'Espresso Based',
            'Manual Brew',
            'Ice Cream',
            'Mocktail',
            'Milk Based, Tea Based & Add-Ons',
            'Rice Dishes',
            'Spaghetti',
            'Mie Magello & Soups',
            'Sweet Dish',
            'Snack',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
