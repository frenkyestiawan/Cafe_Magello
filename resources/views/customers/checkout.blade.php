<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Cafe Magello</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-slate-800">
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Checkout</h1>

        @if(session('error'))
            <div class="mb-4 rounded bg-red-100 border border-red-300 text-red-800 px-4 py-3">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow p-5">
                <h2 class="text-xl font-semibold mb-4">Ringkasan Pesanan</h2>
                @foreach($cart as $item)
                    <div class="flex justify-between py-2 border-b">
                        <span>{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                        <span>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                    </div>
                @endforeach
                <div class="flex justify-between font-bold mt-4">
                    <span>Total</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-5">
                <form action="{{ route('order.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="customer_name" class="block text-sm font-medium mb-1">Nama Pemesan</label>
                        <input type="text" id="customer_name" name="customer_name" required class="w-full border border-slate-300 rounded px-3 py-2">
                    </div>
                    <div>
                        <label for="customer_phone" class="block text-sm font-medium mb-1">Nomor Telepon</label>
                        <input type="text" id="customer_phone" name="customer_phone" required class="w-full border border-slate-300 rounded px-3 py-2">
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white py-2 rounded">Konfirmasi Pesanan</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
