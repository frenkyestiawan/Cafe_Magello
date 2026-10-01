<?php

namespace App\Services;

use App\Models\RestaurantTable;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Support\Facades\Storage;

class TableQrCodeGenerator
{
    public function destinationUrl(string $tableNumber): string
    {
        return rtrim(config('app.url'), '/') . route('order.with-table', [
            'tableNumber' => $tableNumber,
        ], false);
    }

    public function generate(RestaurantTable $table, ?string $tableNumber = null): string
    {
        $qrCode = new QrCode(
            data: $this->destinationUrl($tableNumber ?? $table->table_number),
            size: 300,
            margin: 10,
        );
        $path = 'qr-codes/table-' . $table->id . '.svg';
        $svg = (new SvgWriter())->write($qrCode)->getString();

        Storage::disk('public')->put($path, $svg);

        return '/storage/' . $path;
    }
}