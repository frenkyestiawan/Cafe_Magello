<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Models\RestaurantTable;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

class ReportExportTest extends TestCase
{
    public function test_admin_can_download_report_transactions_as_excel(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $table = RestaurantTable::create([
            'table_number' => 'EX-1',
            'capacity' => 4,
            'is_available' => true,
        ]);
        $order = Order::create([
            'restaurant_table_id' => $table->id,
            'order_code' => 'ORD-EXCEL-001',
            'customer_name' => 'Pelanggan Excel',
            'customer_phone' => '081234567890',
            'status' => Order::STATUS_SUDAH_DIAMBI,
            'total_amount' => 35000,
            'payment_status' => 'paid',
        ]);
        Payment::create([
            'order_id' => $order->id,
            'amount' => 35000,
            'payment_method' => 'qris',
            'status' => 'paid',
        ]);

        $cashOrder = Order::create([
            'restaurant_table_id' => $table->id,
            'order_code' => 'ORD-EXCEL-002',
            'customer_name' => 'Pelanggan Tunai',
            'customer_phone' => '081234567891',
            'status' => Order::STATUS_SUDAH_DIAMBI,
            'total_amount' => 20000,
            'payment_status' => 'paid',
        ]);
        Payment::create([
            'order_id' => $cashOrder->id,
            'amount' => 20000,
            'payment_method' => 'cash',
            'status' => 'paid',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.reports.export-excel', ['payment_method' => 'qris']));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->assertStringContainsString('laporan-transaksi-', $response->headers->get('content-disposition'));
        $this->assertStringContainsString('.xlsx', $response->headers->get('content-disposition'));

        $spreadsheet = IOFactory::load($response->baseResponse->getFile()->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $this->assertSame('Kode Pesanan', $sheet->getCell('A1')->getValue());
        $this->assertSame('ORD-EXCEL-001', $sheet->getCell('A2')->getValue());
        $this->assertSame('QRIS', $sheet->getCell('E2')->getValue());
        $this->assertSame(2, $sheet->getHighestDataRow());
    }
}