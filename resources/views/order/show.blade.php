<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order {{ $order->order_code }} - Magello Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full">
        <!-- Order Header -->
        <div class="text-center mb-6 border-b-2 border-gray-200 pb-4">
            <h1 class="text-2xl font-bold text-gray-800">ORDER #{{ $order->order_code }}</h1>
            <p class="text-gray-600 mt-2">Meja {{ $order->restaurantTable->table_number ?? '-' }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <!-- Order Items -->
        <div class="mb-6">
            @foreach($order->orderDetails as $detail)
            <div class="flex justify-between items-start mb-3">
                <div class="flex-1">
                    <p class="font-medium text-gray-800">{{ $detail->menu->name }}</p>
                    @if($detail->level)
                    <p class="text-sm text-gray-500">Level {{ $detail->level }}</p>
                    @endif
                    @if($detail->notes)
                    <p class="text-sm text-gray-500 italic">{{ $detail->notes }}</p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="font-medium">x{{ $detail->quantity }}</p>
                    <p class="text-gray-600">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-300 my-4"></div>

        <!-- Total -->
        <div class="flex justify-between items-center text-xl font-bold mb-6">
            <span>Total</span>
            <span class="text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
        </div>

        <!-- Order Status -->
        <div class="space-y-2 mb-6">
            <div class="flex justify-between">
                <span class="text-gray-600">Status:</span>
                <span class="font-medium {{ $order->status === 'completed' ? 'text-green-600' : 'text-orange-600' }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Pembayaran:</span>
                <span class="font-medium {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-red-600' }}">
                    {{ $order->payment_status === 'paid' ? 'Lunas' : 'Belum Lunas' }}
                </span>
            </div>
        </div>

        <!-- Customer Name -->
        @if($order->customer_name)
        <div class="text-center text-gray-600 mb-6">
            <p>Pelanggan: {{ $order->customer_name }}</p>
        </div>
        @endif

        <!-- Actions -->
        <div class="flex gap-4">
            <a href="{{ route('order.index') }}" class="flex-1 bg-gray-200 text-gray-800 py-3 rounded-lg font-semibold hover:bg-gray-300 transition text-center">
                Pesanan Baru
            </a>
            <button onclick="window.print()" class="flex-1 bg-blue-500 text-white py-3 rounded-lg font-semibold hover:bg-blue-600 transition">
                Cetak Struk
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
        {{ session('success') }}
    </div>
    @endif
</body>
</html>