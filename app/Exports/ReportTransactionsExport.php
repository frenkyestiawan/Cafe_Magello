<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ReportTransactionsExport implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithMapping
{
    public function __construct(private readonly Collection $orders)
    {
    }

    public function collection(): Collection
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return [
            'Kode Pesanan',
            'Nama Pelanggan',
            'No. HP',
            'Meja',
            'Metode Pembayaran',
            'Jumlah Item',
            'Total Pembayaran (Rp)',
            'Status',
            'Tanggal & Waktu',
        ];
    }

    public function map($order): array
    {
        return [
            $order->order_code,
            $order->customer_name,
            $order->customer_phone ?? '-',
            $order->restaurantTable->table_number ?? 'Takeaway',
            strtoupper($order->payment?->payment_method ?? 'cash'),
            $order->orderDetails->sum('quantity'),
            (float) $order->total_amount,
            $order->status_label,
            $order->created_at->format('d/m/Y H:i'),
        ];
    }

    public function columnFormats(): array
    {
        return ['G' => NumberFormat::FORMAT_NUMBER_00];
    }
}