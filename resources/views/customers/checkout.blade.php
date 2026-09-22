<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pemesan - Magello Cafe</title>
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
                    <a href="{{ route('order.index') }}" class="text-gray-600 hover:text-orange-600 text-sm font-medium">
                        Kembali ke Menu
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex items-center justify-center p-4 min-h-[calc(100vh-4rem)]">
        <div class="max-w-lg w-full">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="text-center mb-8">
                    <div class="inline-block bg-orange-100 rounded-full p-4 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">Data Pemesan</h1>
                    <p class="text-gray-600">Silakan lengkapi data untuk konfirmasi pesanan</p>
                </div>

                <!-- Order Summary -->
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-orange-800 mb-2">Ringkasan Pesanan</h3>
                    <div class="space-y-2">
                        @foreach($cart as $item)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-700">{{ $item['name'] }} x{{ $item['quantity'] }}</span>
                            <span class="text-gray-700">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="border-t border-orange-300 mt-3 pt-3 flex justify-between font-bold">
                        <span class="text-orange-800">Total</span>
                        <span class="text-orange-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('order.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="customer_name" placeholder="Masukkan nama lengkap Anda"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                               required>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">
                            Nomor HP <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="customer_phone" placeholder="Contoh: 08123456789"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                               pattern="[0-9]{10,13}"
                               title="Nomor HP harus 10-13 digit angka"
                               required>
                        <p class="text-gray-500 text-xs mt-1">Digunakan untuk notifikasi status pesanan</p>
                    </div>

                    <div class="flex gap-4">
                        <a href="{{ route('order.index') }}" class="flex-1 bg-gray-200 text-gray-800 py-3 rounded-lg font-semibold hover:bg-gray-300 transition text-center">
                            Kembali
                        </a>
                        <button type="submit" class="flex-1 bg-orange-500 text-white py-3 rounded-lg font-semibold hover:bg-orange-600 transition">
                            Konfirmasi Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
        {{ session('error') }}
    </div>
    @endif
</body>
</html>