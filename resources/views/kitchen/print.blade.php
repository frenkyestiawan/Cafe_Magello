<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Pesanan {{ $order->order_code }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 16px;
            color: #111827;
        }
        .ticket {
            width: 240px;
            margin: 0 auto;
            border: 1px solid #d1d5db;
            padding: 12px;
        }
        .center {
            text-align: center;
        }
        .title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 8px;
        }
        .divider {
            border-top: 1px dashed #4b5563;
            margin: 8px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 4px 0;
            vertical-align: top;
            text-align: left;
        }
        .qty {
            text-align: right;
            white-space: nowrap;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
                background: #fff;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="center title">Cafe Magello</div>
        <div class="center">Slip Pesanan Dapur</div>

        <div class="divider"></div>

        <table>
            <tr><th>Nomor</th><td>:</td><td>{{ $order->order_code }}</td></tr>
            <tr><th>Meja</th><td>:</td><td>{{ $order->restaurantTable?->table_number ?? '-' }}</td></tr>
            <tr><th>Pelanggan</th><td>:</td><td>{{ $order->customer_name ?? '-' }}</td></tr>
            <tr><th>Waktu</th><td>:</td><td>{{ $order->created_at->format('d-m-Y H:i') }}</td></tr>
            <tr><th>Status</th><td>:</td><td>{{ $order->status }}</td></tr>
        </table>

        <div class="divider"></div>

        <table>
            @foreach($order->orderDetails as $detail)
                <tr>
                    <td>
                        {{ $detail->menu?->name ?? 'Menu' }}
                        @if(!empty($detail->variant_name))
                            <div style="font-size: 10px; color: #4b5563;">{{ $detail->variant_name }}</div>
                        @endif
                    </td>
                    <td class="qty">x {{ $detail->quantity }}</td>
                </tr>
            @endforeach
        </table>

        @if($order->orderDetails->contains(fn ($detail) => !empty($detail->notes)))
            <div class="divider"></div>
            <div><strong>Catatan:</strong></div>
            @foreach($order->orderDetails as $detail)
                @if(!empty($detail->notes))
                    <div>{{ $detail->menu?->name ?? 'Menu' }}: {{ $detail->notes }}</div>
                @endif
            @endforeach
        @endif

        <div class="divider"></div>
        <div class="center">Terima kasih</div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 16px;">
        <button onclick="window.print()" style="padding: 10px 16px; background: #111827; color: white; border: none; border-radius: 8px; cursor: pointer;">Cetak Slip</button>
    </div>
</body>
</html>
