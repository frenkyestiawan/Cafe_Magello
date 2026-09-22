<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use App\Models\MenuVariant;
use Database\Seeders\CategorySeeder;
use Database\Seeders\MenuSeeder;
use Tests\TestCase;

class MenuVariantTest extends TestCase
{
    public function test_menu_can_store_multiple_variants(): void
    {
        $category = Category::create(['name' => 'Makanan']);

        $menu = Menu::create([
            'name' => 'Dimsum Goreng',
            'category_id' => $category->id,
            'price' => 15000,
            'description' => 'Dimsum goreng renyah',
            'is_available' => true,
        ]);

        $menu->variants()->createMany([
            ['name' => 'Small', 'price' => 15000, 'is_available' => true],
            ['name' => 'Medium', 'price' => 22000, 'is_available' => true],
            ['name' => 'Large', 'price' => 28000, 'is_available' => true],
        ]);

        $this->assertCount(3, $menu->fresh()->variants);
        $this->assertTrue($menu->fresh()->variants->contains(fn ($variant) => $variant->name === 'Small'));
        $this->assertTrue(MenuVariant::query()->where('menu_id', $menu->id)->where('name', 'Medium')->exists());
    }

    public function test_magello_seeders_include_catalogue_items(): void
    {
        $this->seed(CategorySeeder::class);
        $this->seed(MenuSeeder::class);

        $this->assertDatabaseHas('categories', ['name' => 'New Menu (Drinks)']);
        $this->assertDatabaseHas('categories', ['name' => 'Signature']);
        $this->assertDatabaseHas('menus', ['name' => 'Peach Americano']);
        $this->assertDatabaseHas('menus', ['name' => 'Dimsum Goreng']);
        $this->assertDatabaseHas('menus', ['name' => 'Fabbura']);
    }
}
