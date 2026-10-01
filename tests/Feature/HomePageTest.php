<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_home_page_displays_best_seller_when_there_are_no_orders(): void
    {
        $category = Category::create(['name' => 'Kopi']);

        Menu::create([
            'name' => 'Americano',
            'category_id' => $category->id,
            'price' => 18000,
            'description' => 'Kopi hitam.',
            'is_available' => true,
            'is_best_seller' => true,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Americano')
            ->assertSee('Best Seller');
    }
}