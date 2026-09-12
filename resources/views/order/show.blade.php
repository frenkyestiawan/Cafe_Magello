<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order {{ $order->order_code }} - Magello Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-orange-600">Magello</a>
                </div>
                <div>
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-orange-600 text-sm font-medium">
                        Kembali ke Home
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex items-center justify-center p-4 min-h-[calc(100vh-4rem)]">
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full" style="font-family: 'Courier New', monospace;">
        <!-- Order Header -->
        <div class="text-center mb-6 border-b-2 border-dashed border-gray-300 pb-4">
            <h1 class="text-2xl font-bold text-gray-800">ORDER #{{ $order->order_code }}</h1>
            <p class="text-gray-600 mt-2">Meja {{ $order->restaurantTable->table_number ?? '-' }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <!-- Order Items -->
        <div class="mb-6">
            @foreach($order->orderDetails as $detail)
            <div class="flex justify-between items-start mb-2">
                <div class="flex-1">
                    <p class="font-medium text-gray-800">
                        {{ $detail->menu->name }}
                        @if($detail->level)
                        <span class="text-sm text-gray-500"> Level {{ $detail->level }}</span>
                        @endif
                    </p>
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
        <div class="border-t-2 border-dashed border-gray-300 my-4"></div>

        <!-- Total -->
        <div class="flex justify-between items-center text-xl font-bold mb-6">
            <span>Total</span>
            <span class="text-orange-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
        </div>

        <!-- Customer Info -->
        @if($order->customer_name || $order->customer_phone)
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            @if($order->customer_name)
            <div class="flex justify-between text-sm mb-2">
                <span class="text-gray-600">Nama:</span>
                <span class="text-gray-800">{{ $order->customer_name }}</span>
            </div>
            @endif
            @if($order->customer_phone)
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">No HP:</span>
                <span class="text-gray-800">{{ $order->customer_phone }}</span>
            </div>
            @endif
        </div>
        @endif

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

        <!-- Actions -->
        <div class="flex gap-4">
            <a href="{{ route('order.index') }}" class="flex-1 bg-gray-200 text-gray-800 py-3 rounded-lg font-semibold hover:bg-gray-300 transition text-center">
                Pesanan Baru
            </a>
            <button onclick="window.print()" class="flex-1 bg-orange-500 text-white py-3 rounded-lg font-semibold hover:bg-orange-600 transition">
                Cetak Struk
            </button>
        </div>
        </div>

    @if(session('success'))
    <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
        {{ session('success') }}
    </div>
    @endif
    </div>
</body>
</html>