<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use Tests\TestCase;

class CustomerOrderIndexTest extends TestCase
{
    public function test_order_index_displays_real_category_names(): void
    {
        $category = Category::create(['name' => 'Kopi']);

        Menu::create([
            'name' => 'Americano',
            'category_id' => $category->id,
            'price' => 18000,
            'description' => 'Kopi hitam yang kaya rasa.',
            'is_available' => true,
        ]);

        $response = $this->get(route('order.index'));

        $response->assertOk();
        $response->assertSee('Kopi');
    }
}
