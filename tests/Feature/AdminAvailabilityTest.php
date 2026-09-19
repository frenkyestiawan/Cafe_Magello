<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Admin\AdminTableController;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AdminAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_menu_store_treats_zero_as_unavailable(): void
    {
        $category = Category::create(['name' => 'Minuman']);

        $request = Request::create('/admin/menus', 'POST', [
            'name' => 'Espresso',
            'category_id' => $category->id,
            'price' => 25000,
            'description' => 'Kopi hitam',
            'is_available' => '0',
        ]);

        app(AdminMenuController::class)->store($request);

        $this->assertDatabaseHas('menus', [
            'name' => 'Espresso',
            'is_available' => 0,
        ]);
    }

    public function test_table_store_treats_zero_as_unavailable(): void
    {
        $request = Request::create('/admin/tables', 'POST', [
            'table_number' => '12',
            'capacity' => 4,
            'is_available' => '0',
        ]);

        app(AdminTableController::class)->store($request);

        $this->assertDatabaseHas('restaurant_tables', [
            'table_number' => '12',
            'is_available' => 0,
        ]);
    }
}
