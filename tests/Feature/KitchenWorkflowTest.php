<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KitchenWorkflowTest extends TestCase
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

    public function test_payment_success_keeps_order_in_kitchen_queue(): void
    {
        $table = \App\Models\RestaurantTable::firstOrCreate(
            ['table_number' => 'KDS-2'],
            ['capacity' => 4, 'is_available' => true]
        );

        $order = \App\Models\Order::create([
            'restaurant_table_id' => $table->id,
            'order_code' => 'ORD-KDS-002',
            'customer_name' => 'Pembeli Bayar',
            'customer_phone' => '081234567891',
            'status' => 'menunggu',
            'total_amount' => 15000,
            'payment_status' => 'unpaid',
        ]);

        $this->post(route('payment.check-status', $order->id))
            ->assertRedirect(route('order.show', $order->id));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'menunggu',
            'payment_status' => 'paid',
        ]);
    }

    public function test_admin_monitoring_shows_current_order_status_label(): void
    {
        $adminUser = User::factory()->create([
            'email' => 'admin-monitor@example.com',
            'role' => 'admin',
            'password' => bcrypt('secret123'),
        ]);

        $table = \App\Models\RestaurantTable::firstOrCreate(
            ['table_number' => 'ADM-1'],
            ['capacity' => 4, 'is_available' => true]
        );

        $order = \App\Models\Order::create([
            'restaurant_table_id' => $table->id,
            'order_code' => 'ORD-ADM-001',
            'customer_name' => 'Monitoring Admin',
            'customer_phone' => '081234567892',
            'status' => 'diproses',
            'total_amount' => 20000,
            'payment_status' => 'paid',
        ]);

        $this->actingAs($adminUser)
            ->get(route('admin.orders.index'))
            ->assertOk()
            ->assertSee('Diproses')
            ->assertSee($order->order_code);

        $this->actingAs($adminUser)
            ->get(route('admin.orders.index', ['status' => 'diproses']))
            ->assertOk();
    }

    public function test_application_uses_wib_timezone_for_activity_timestamps(): void
    {
        $this->assertSame('Asia/Jakarta', config('app.timezone'));
    }

    public function test_admin_reporting_counts_only_picked_up_orders_as_completed_transactions(): void
    {
        $adminUser = User::factory()->create([
            'email' => 'admin-report@example.com',
            'role' => 'admin',
            'password' => bcrypt('secret123'),
        ]);

        $table = \App\Models\RestaurantTable::firstOrCreate(
            ['table_number' => 'ADM-2'],
            ['capacity' => 4, 'is_available' => true]
        );

        \App\Models\Order::create([
            'restaurant_table_id' => $table->id,
            'order_code' => 'ORD-ADM-REPORT',
            'customer_name' => 'Laporan Keuangan',
            'customer_phone' => '081234567895',
            'status' => 'sudah_diambil',
            'total_amount' => 35000,
            'payment_status' => 'paid',
        ]);

        $this->actingAs($adminUser)
            ->get(route('admin.reports.index'))
            ->assertOk()
            ->assertSee('Rp 35.000')
            ->assertSee('1');
    }

    public function test_kitchen_dashboard_prioritizes_oldest_orders_first(): void
    {
        $kitchenUser = User::factory()->create([
            'email' => 'kitchen-order-priority@example.com',
            'role' => 'kitchen',
            'password' => bcrypt('secret123'),
        ]);

        $table = \App\Models\RestaurantTable::firstOrCreate(
            ['table_number' => 'KDS-3'],
            ['capacity' => 4, 'is_available' => true]
        );

        $oldOrder = \App\Models\Order::create([
            'restaurant_table_id' => $table->id,
            'order_code' => 'ORD-KDS-OLD',
            'customer_name' => 'Pelanggan Lama',
            'customer_phone' => '081234567893',
            'status' => 'menunggu',
            'total_amount' => 15000,
            'payment_status' => 'paid',
            'created_at' => now()->subMinutes(20),
            'updated_at' => now()->subMinutes(20),
        ]);

        $newOrder = \App\Models\Order::create([
            'restaurant_table_id' => $table->id,
            'order_code' => 'ORD-KDS-NEW',
            'customer_name' => 'Pelanggan Baru',
            'customer_phone' => '081234567894',
            'status' => 'menunggu',
            'total_amount' => 25000,
            'payment_status' => 'paid',
            'created_at' => now()->subMinutes(5),
            'updated_at' => now()->subMinutes(5),
        ]);

        $this->actingAs($kitchenUser)
            ->get(route('kitchen.dashboard'))
            ->assertOk()
            ->assertSeeInOrder([$oldOrder->order_code, $newOrder->order_code]);
    }
}
