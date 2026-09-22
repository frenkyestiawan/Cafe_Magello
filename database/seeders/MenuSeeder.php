<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use App\Models\MenuVariant;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $categoryMap = Category::query()->pluck('id', 'name');

        $menus = [
            ['category' => 'New Menu (Drinks)', 'name' => 'Macella', 'price' => 24000, 'description' => 'Espresso with soda, strawberry syrup and pineapple syrup'],
            ['category' => 'New Menu (Drinks)', 'name' => 'Marissa', 'price' => 24000, 'description' => 'Espresso with lemon syrup, passion fruit syrup and apple juice'],
            ['category' => 'New Menu (Drinks)', 'name' => 'Sunset', 'price' => 20000, 'description' => 'Fresh mocktail with orange sunkist syrup and vanilla syrup'],
            ['category' => 'New Menu (Drinks)', 'name' => 'Sunrise', 'price' => 20000, 'description' => 'Fresh mocktail with soda, pineapple syrup and passion fruit syrup'],
            ['category' => 'New Menu (Drinks)', 'name' => 'Peach Americano', 'price' => 18000, 'description' => 'Peach Americano'],
            ['category' => 'New Menu (Drinks)', 'name' => 'Orange Americano', 'price' => 18000, 'description' => 'Orange Americano'],
            ['category' => 'New Menu (Drinks)', 'name' => 'Caramel Macchiato', 'price' => 22000, 'description' => 'Caramel Macchiato'],
            ['category' => 'New Menu (Drinks)', 'name' => 'Mocha', 'price' => 20000, 'description' => 'Mocha'],
            ['category' => 'New Menu (Drinks)', 'name' => 'Vanilla Latte', 'price' => 18000, 'description' => 'Vanilla Latte'],
            ['category' => 'New Menu (Drinks)', 'name' => 'Peach Tea', 'price' => 18000, 'description' => 'Peach Tea'],
            ['category' => 'New Menu (Drinks)', 'name' => 'Passion Fruit Tea', 'price' => 18000, 'description' => 'Passion Fruit Tea'],
            ['category' => 'New Menu (Drinks)', 'name' => 'Cloudy Taro', 'price' => 23000, 'description' => 'Taro with whipped cream on top'],
            ['category' => 'New Menu (Drinks)', 'name' => 'Cloudy Thai Tea', 'price' => 23000, 'description' => 'Thai tea with whipped cream on top'],
            ['category' => 'New Menu (Food)', 'name' => 'Dimsum Goreng', 'price' => 15000, 'description' => 'Dimsum goreng'],
            ['category' => 'New Menu (Food)', 'name' => 'Dimsum Mentai', 'price' => 18000, 'description' => 'Dimsum mentai'],
            ['category' => 'New Menu (Food)', 'name' => 'Magello Chicken Steak', 'price' => 25000, 'description' => 'Chicken steak'],
            ['category' => 'New Menu (Food)', 'name' => 'Roti Gulung Mozarella', 'price' => 22000, 'description' => 'Mozarella roll'],
            ['category' => 'New Menu (Food)', 'name' => 'Cordon Bleu', 'price' => 27000, 'description' => 'Cordon bleu'],
            ['category' => 'New Menu (Food)', 'name' => 'BBQ Hotplate', 'price' => 27000, 'description' => 'BBQ hotplate'],
            ['category' => 'New Menu (Food)', 'name' => 'Sweet Sour Hotplate', 'price' => 27000, 'description' => 'Sweet sour hotplate'],
            ['category' => 'Signature', 'name' => 'Fabbura', 'price' => 20000, 'description' => 'Signature drink'],
            ['category' => 'Signature', 'name' => 'Jado', 'price' => 18000, 'description' => 'Signature drink'],
            ['category' => 'Signature', 'name' => 'Semme', 'price' => 18000, 'description' => 'Signature drink'],
            ['category' => 'Signature', 'name' => 'Pamulana', 'price' => 18000, 'description' => 'Signature drink'],
            ['category' => 'Signature', 'name' => 'Assara', 'price' => 21000, 'description' => 'Signature drink'],
            ['category' => 'Espresso Based', 'name' => 'Espresso', 'price' => 15000, 'description' => 'Espresso'],
            ['category' => 'Espresso Based', 'name' => 'Americano', 'price' => 14000, 'description' => 'Americano'],
            ['category' => 'Espresso Based', 'name' => 'Long Black', 'price' => 16000, 'description' => 'Long black'],
            ['category' => 'Espresso Based', 'name' => 'Coffe Latte', 'price' => 18000, 'description' => 'Coffee latte'],
            ['category' => 'Espresso Based', 'name' => 'Cappuccino', 'price' => 18000, 'description' => 'Cappuccino'],
            ['category' => 'Espresso Based', 'name' => 'Affogato', 'price' => 18000, 'description' => 'Affogato'],
            ['category' => 'Manual Brew', 'name' => 'V60', 'price' => 20000, 'description' => 'Manual brew'],
            ['category' => 'Ice Cream', 'name' => 'Chocolate', 'price' => 16000, 'description' => 'Ice cream chocolate'],
            ['category' => 'Ice Cream', 'name' => 'Caramel', 'price' => 16000, 'description' => 'Ice cream caramel'],
            ['category' => 'Ice Cream', 'name' => 'Strawberry', 'price' => 18000, 'description' => 'Ice cream strawberry'],
            ['category' => 'Ice Cream', 'name' => 'Vanilla Oreo', 'price' => 18000, 'description' => 'Vanilla oreo'],
            ['category' => 'Ice Cream', 'name' => 'Boba', 'price' => 16000, 'description' => 'Boba ice cream'],
            ['category' => 'Ice Cream', 'name' => 'Choco Chips', 'price' => 18000, 'description' => 'Choco chips'],
            ['category' => 'Mocktail', 'name' => 'Lychee Mojito', 'price' => 20000, 'description' => 'Lychee mojito'],
            ['category' => 'Mocktail', 'name' => 'Mojito', 'price' => 20000, 'description' => 'Mojito'],
            ['category' => 'Mocktail', 'name' => 'Strawberry Mojito', 'price' => 20000, 'description' => 'Strawberry mojito'],
            ['category' => 'Mocktail', 'name' => 'Purple Lemonade', 'price' => 22000, 'description' => 'Purple lemonade'],
            ['category' => 'Mocktail', 'name' => 'Jasmine Berry', 'price' => 22000, 'description' => 'Jasmine berry'],
            ['category' => 'Mocktail', 'name' => 'Tropical Summer', 'price' => 22000, 'description' => 'Tropical summer'],
            ['category' => 'Milk Based, Tea Based & Add-Ons', 'name' => 'Milky Berry', 'price' => 22000, 'description' => 'Milky berry'],
            ['category' => 'Milk Based, Tea Based & Add-Ons', 'name' => 'Chocolate', 'price' => 20000, 'description' => 'Chocolate milk'],
            ['category' => 'Milk Based, Tea Based & Add-Ons', 'name' => 'Red Velvet', 'price' => 20000, 'description' => 'Red velvet milk'],
            ['category' => 'Milk Based, Tea Based & Add-Ons', 'name' => 'Taro', 'price' => 20000, 'description' => 'Taro milk'],
            ['category' => 'Milk Based, Tea Based & Add-Ons', 'name' => 'Matcha', 'price' => 20000, 'description' => 'Matcha milk'],
            ['category' => 'Milk Based, Tea Based & Add-Ons', 'name' => 'Thai Tea', 'price' => 18000, 'description' => 'Thai tea'],
            ['category' => 'Milk Based, Tea Based & Add-Ons', 'name' => 'Lemon Tea', 'price' => 15000, 'description' => 'Lemon tea'],
            ['category' => 'Milk Based, Tea Based & Add-Ons', 'name' => 'Lychee Tea', 'price' => 18000, 'description' => 'Lychee tea'],
            ['category' => 'Milk Based, Tea Based & Add-Ons', 'name' => 'Air Mineral', 'price' => 5000, 'description' => 'Air mineral'],
            ['category' => 'Milk Based, Tea Based & Add-Ons', 'name' => 'Extra Shot', 'price' => 4000, 'description' => 'Extra shot'],
            ['category' => 'Milk Based, Tea Based & Add-Ons', 'name' => 'Extra Caramel', 'price' => 3000, 'description' => 'Extra caramel'],
            ['category' => 'Milk Based, Tea Based & Add-Ons', 'name' => 'Coffee Jelly', 'price' => 3000, 'description' => 'Coffee jelly'],
            ['category' => 'Milk Based, Tea Based & Add-Ons', 'name' => 'Oreo', 'price' => 3000, 'description' => 'Oreo topping'],
            ['category' => 'Milk Based, Tea Based & Add-Ons', 'name' => 'Boba', 'price' => 3000, 'description' => 'Boba topping'],
            ['category' => 'Milk Based, Tea Based & Add-Ons', 'name' => 'Mashed', 'price' => 3000, 'description' => 'Mashed topping'],
            ['category' => 'Milk Based, Tea Based & Add-Ons', 'name' => 'Ice Cream', 'price' => 4000, 'description' => 'Ice cream topping'],
            ['category' => 'Rice Dishes', 'name' => 'Nasi Goreng Sugih', 'price' => 25000, 'description' => 'Nasi goreng sugih'],
            ['category' => 'Rice Dishes', 'name' => 'Nasi Goreng Ayam', 'price' => 23000, 'description' => 'Nasi goreng ayam'],
            ['category' => 'Rice Dishes', 'name' => 'Nasi Goreng Teri', 'price' => 22000, 'description' => 'Nasi goreng teri'],
            ['category' => 'Rice Dishes', 'name' => 'Nasi Ayam Lengkuas', 'price' => 23000, 'description' => 'Nasi ayam lengkuas'],
            ['category' => 'Rice Dishes', 'name' => 'Nasi Ayam Bakar Bumbu Rujak', 'price' => 23000, 'description' => 'Nasi ayam bakar bumbu rujak'],
            ['category' => 'Rice Dishes', 'name' => 'Nasi Ayam Bawang', 'price' => 23000, 'description' => 'Nasi ayam bawang'],
            ['category' => 'Rice Dishes', 'name' => 'Chicken Katsu', 'price' => 24000, 'description' => 'Chicken katsu rice bowl'],
            ['category' => 'Rice Dishes', 'name' => 'Sambal Matah', 'price' => 24000, 'description' => 'Sambal matah rice bowl'],
            ['category' => 'Rice Dishes', 'name' => 'Blackpepper', 'price' => 24000, 'description' => 'Blackpepper rice bowl'],
            ['category' => 'Rice Dishes', 'name' => 'Teriyaki', 'price' => 24000, 'description' => 'Teriyaki rice bowl'],
            ['category' => 'Rice Dishes', 'name' => 'Salted Egg', 'price' => 24000, 'description' => 'Salted egg rice bowl'],
            ['category' => 'Spaghetti', 'name' => 'Bolognese', 'price' => 24000, 'description' => 'Spaghetti bolognese'],
            ['category' => 'Spaghetti', 'name' => 'Carbonara', 'price' => 24000, 'description' => 'Spaghetti carbonara'],
            ['category' => 'Spaghetti', 'name' => 'Aglio e Olio', 'price' => 24000, 'description' => 'Spaghetti aglio e olio'],
            ['category' => 'Mie Magello & Soups', 'name' => 'Mie Hot Plate', 'price' => 27000, 'description' => 'Mie hot plate'],
            ['category' => 'Mie Magello & Soups', 'name' => 'Mie Goreng Magello', 'price' => 18000, 'description' => 'Mie goreng magello'],
            ['category' => 'Mie Magello & Soups', 'name' => 'Mie Rebus Magello', 'price' => 18000, 'description' => 'Mie rebus magello'],
            ['category' => 'Mie Magello & Soups', 'name' => 'Mie Chili Oil', 'price' => 18000, 'description' => 'Mie chili oil'],
            ['category' => 'Mie Magello & Soups', 'name' => 'Nasi Tongseng Ayam', 'price' => 25000, 'description' => 'Nasi tongseng ayam'],
            ['category' => 'Mie Magello & Soups', 'name' => 'Soto Ayam', 'price' => 18000, 'description' => 'Soto ayam'],
            ['category' => 'Sweet Dish', 'name' => 'Roti Bakar Coklat', 'price' => 15000, 'description' => 'Roti bakar coklat'],
            ['category' => 'Sweet Dish', 'name' => 'Roti Bakar Coklat Keju', 'price' => 18000, 'description' => 'Roti bakar coklat keju'],
            ['category' => 'Sweet Dish', 'name' => 'Roti Bakar Sarikaya', 'price' => 15000, 'description' => 'Roti bakar sarikaya'],
            ['category' => 'Sweet Dish', 'name' => 'Roti Bakar Kacang', 'price' => 15000, 'description' => 'Roti bakar kacang'],
            ['category' => 'Sweet Dish', 'name' => 'Waffle Caramel', 'price' => 20000, 'description' => 'Waffle caramel'],
            ['category' => 'Sweet Dish', 'name' => 'Waffle Choco', 'price' => 20000, 'description' => 'Waffle choco'],
            ['category' => 'Sweet Dish', 'name' => 'Waffle Strawberry', 'price' => 20000, 'description' => 'Waffle strawberry'],
            ['category' => 'Sweet Dish', 'name' => 'Pisang Goreng Coklat Keju', 'price' => 18000, 'description' => 'Pisang goreng coklat keju'],
            ['category' => 'Sweet Dish', 'name' => 'Pisang Goreng Original', 'price' => 15000, 'description' => 'Pisang goreng original'],
            ['category' => 'Snack', 'name' => 'Mix Platter', 'price' => 25000, 'description' => 'Mix platter'],
            ['category' => 'Snack', 'name' => 'Kentang Goreng', 'price' => 18000, 'description' => 'Kentang goreng'],
            ['category' => 'Snack', 'name' => 'Nugget', 'price' => 17000, 'description' => 'Nugget'],
            ['category' => 'Snack', 'name' => 'Tahu Bakso', 'price' => 16000, 'description' => 'Tahu bakso'],
            ['category' => 'Snack', 'name' => 'Tempe Goreng', 'price' => 15000, 'description' => 'Tempe goreng'],
            ['category' => 'Snack', 'name' => 'Sandwich', 'price' => 18000, 'description' => 'Sandwich'],
            ['category' => 'Snack', 'name' => 'Risoles', 'price' => 16000, 'description' => 'Risoles'],
        ];

        $variantCatalog = [
            'Macella' => [['name' => 'Small', 'price' => 24000]],
            'Marissa' => [['name' => 'Small', 'price' => 24000]],
            'Sunset' => [['name' => 'Small', 'price' => 20000]],
            'Sunrise' => [['name' => 'Small', 'price' => 20000]],
            'Peach Americano' => [['name' => 'Small', 'price' => 18000], ['name' => 'Large', 'price' => 22000]],
            'Orange Americano' => [['name' => 'Small', 'price' => 18000], ['name' => 'Large', 'price' => 22000]],
            'Caramel Macchiato' => [['name' => 'Small', 'price' => 22000], ['name' => 'Large', 'price' => 24000]],
            'Mocha' => [['name' => 'Small', 'price' => 20000], ['name' => 'Large', 'price' => 24000]],
            'Vanilla Latte' => [['name' => 'Small', 'price' => 18000], ['name' => 'Large', 'price' => 22000]],
            'Peach Tea' => [['name' => 'Small', 'price' => 18000], ['name' => 'Large', 'price' => 22000]],
            'Passion Fruit Tea' => [['name' => 'Small', 'price' => 18000], ['name' => 'Large', 'price' => 22000]],
            'Cloudy Taro' => [['name' => 'Small', 'price' => 23000], ['name' => 'Large', 'price' => 27000]],
            'Cloudy Thai Tea' => [['name' => 'Small', 'price' => 23000], ['name' => 'Large', 'price' => 27000]],
            'Dimsum Goreng' => [['name' => 'Small', 'price' => 15000], ['name' => 'Medium', 'price' => 21000], ['name' => 'Large', 'price' => 28000]],
            'Dimsum Mentai' => [['name' => 'Small', 'price' => 18000], ['name' => 'Medium', 'price' => 24000], ['name' => 'Large', 'price' => 29000]],
            'Fabbura' => [['name' => 'Small', 'price' => 20000], ['name' => 'Large', 'price' => 24000]],
            'Jado' => [['name' => 'Small', 'price' => 18000], ['name' => 'Large', 'price' => 22000]],
            'Semme' => [['name' => 'Small', 'price' => 18000], ['name' => 'Large', 'price' => 22000]],
            'Pamulana' => [['name' => 'Small', 'price' => 18000], ['name' => 'Large', 'price' => 22000]],
            'Assara' => [['name' => 'Small', 'price' => 21000], ['name' => 'Large', 'price' => 25000]],
            'Americano' => [['name' => 'Small', 'price' => 14000], ['name' => 'Large', 'price' => 18000]],
            'Long Black' => [['name' => 'Small', 'price' => 16000], ['name' => 'Large', 'price' => 20000]],
            'Coffe Latte' => [['name' => 'Small', 'price' => 18000], ['name' => 'Large', 'price' => 22000]],
            'Cappuccino' => [['name' => 'Small', 'price' => 18000], ['name' => 'Large', 'price' => 22000]],
            'Chocolate' => [['name' => 'Small', 'price' => 16000]],
            'Red Velvet' => [['name' => 'Small', 'price' => 20000], ['name' => 'Large', 'price' => 24000]],
            'Taro' => [['name' => 'Small', 'price' => 20000], ['name' => 'Large', 'price' => 24000]],
            'Matcha' => [['name' => 'Small', 'price' => 20000], ['name' => 'Large', 'price' => 24000]],
            'Thai Tea' => [['name' => 'Small', 'price' => 18000], ['name' => 'Large', 'price' => 22000]],
            'Lemon Tea' => [['name' => 'Small', 'price' => 15000], ['name' => 'Large', 'price' => 19000]],
            'Lychee Tea' => [['name' => 'Small', 'price' => 18000], ['name' => 'Large', 'price' => 22000]],
            'Milky Berry' => [['name' => 'Small', 'price' => 22000]],
        ];

        foreach ($menus as $menu) {
            $categoryId = $categoryMap[$menu['category']] ?? null;

            if (!$categoryId) {
                continue;
            }

            $menuModel = Menu::firstOrCreate(
                ['name' => $menu['name'], 'category_id' => $categoryId],
                [
                    'price' => $menu['price'],
                    'description' => $menu['description'],
                    'is_available' => true,
                ]
            );

            $variants = $variantCatalog[$menu['name']] ?? [['name' => 'Small', 'price' => (int) $menu['price']]];

            foreach ($variants as $variant) {
                $menuModel->variants()->updateOrCreate(
                    ['name' => $variant['name']],
                    [
                        'price' => $variant['price'],
                        'is_available' => true,
                    ]
                );
            }
        }
    }
}
