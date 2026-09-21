<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - Cafe Magello</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800">
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-3xl font-bold mb-4">Detail Pesanan</h1>

        <div class="bg-white rounded-xl shadow p-5 space-y-4">
            <div class="flex justify-between">
                <span class="text-slate-500">Kode Order</span>
                <strong>{{ $order->order_code }}</strong>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Meja</span>
                <strong>{{ $order->restaurantTable?->table_number ?? '-' }}</strong>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Status</span>
                <strong>{{ $order->status }}</strong>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Total</span>
                <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
            </div>
        </div>

        <div class="mt-6 bg-white rounded-xl shadow p-5">
            <h2 class="text-xl font-semibold mb-4">Isi Pesanan</h2>
            @foreach($order->orderDetails as $detail)
                <div class="flex justify-between py-2 border-b">
                    <span>{{ $detail->menu->name ?? 'Menu' }} x {{ $detail->quantity }}</span>
                    <span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            <a href="{{ route('payment.show', $order->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded">Lanjut ke Pembayaran</a>
        </div>
    </div>
</body>
</html>
