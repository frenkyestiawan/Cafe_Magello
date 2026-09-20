<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KitchenAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_kitchen_user_can_login_and_access_kitchen_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'kitchen@example.com',
            'role' => 'kitchen',
            'password' => bcrypt('secret123'),
        ]);

        $this->post('/login', [
            'email' => 'kitchen@example.com',
            'password' => 'secret123',
        ])->assertRedirect('/kitchen');

        $this->assertAuthenticatedAs($user);
        $this->get('/kitchen')->assertOk();
    }

    public function test_non_kitchen_user_is_blocked_from_kitchen_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'customer@example.com',
            'role' => 'customer',
            'password' => bcrypt('secret123'),
        ]);

        $this->actingAs($user)
            ->get('/kitchen')
            ->assertRedirect('/login');
    }

    public function test_guest_is_redirected_to_login_when_trying_to_access_kitchen_dashboard(): void
    {
        $this->get('/kitchen')->assertRedirect('/login');
    }

    public function test_kitchen_status_flow_follows_valid_order(): void
    {
        $kitchenUser = User::factory()->create([
            'email' => 'kitchen-flow@example.com',
            'role' => 'kitchen',
            'password' => bcrypt('secret123'),
        ]);

        $table = \App\Models\RestaurantTable::firstOrCreate(
            ['table_number' => 'KDS-1'],
            ['capacity' => 4, 'is_available' => true]
        );

        $order = \App\Models\Order::create([
            'restaurant_table_id' => $table->id,
            'order_code' => 'ORD-KDS-001',
            'customer_name' => 'Test Kitchen',
            'customer_phone' => '081234567890',
            'status' => 'menunggu',
            'total_amount' => 10000,
            'payment_status' => 'unpaid',
        ]);

        $this->actingAs($kitchenUser)
            ->patch(route('kitchen.orders.start', $order))
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'diproses',
        ]);

        $this->actingAs($kitchenUser)
            ->patch(route('kitchen.orders.complete', $order))
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'selesai',
        ]);

        $this->actingAs($kitchenUser)
            ->patch(route('kitchen.orders.pickup', $order))
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'sudah_diambil',
        ]);
    }
}
