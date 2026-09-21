<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Cafe Magello</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800">
    <div class="max-w-3xl mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Pembayaran</h1>

        @if(session('success'))
            <div class="mb-4 rounded bg-green-100 border border-green-300 text-green-800 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex justify-between py-2 border-b">
                <span>Nomor Order</span>
                <strong>{{ $order->order_code }}</strong>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span>Total</span>
                <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
            </div>
            <div class="mt-5">
                <form action="{{ route('payment.check-status', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-emerald-600 text-white py-3 rounded">Bayar Sekarang</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
