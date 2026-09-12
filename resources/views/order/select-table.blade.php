<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Meja - Magello Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-amber-50 to-orange-100 min-h-screen">
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

    <!-- Content -->
    <div class="flex items-center justify-center min-h-[calc(100vh-4rem)] px-4">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="text-center mb-8">
                    <div class="inline-block bg-orange-100 rounded-full p-4 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">Pilih Meja</h1>
                    <p class="text-gray-600">Scan QR Code di meja atau pilih meja secara manual</p>
                </div>

                <!-- QR Code Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-blue-800 font-medium text-sm">Scan QR Code</p>
                            <p class="text-blue-600 text-xs mt-1">Scan QR Code yang tersedia di meja Anda untuk otomatis terdeteksi</p>
                        </div>
                    </div>
                </div>

                <!-- Manual Selection -->
                <div class="border-t border-gray-200 pt-6">
                    <p class="text-center text-gray-500 text-sm mb-4">Atau pilih meja secara manual:</p>
                    
                    <form action="{{ route('order.set-table') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2">Nomor Meja</label>
                            <input type="text" name="table_number" placeholder="Contoh: 05" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                   required>
                        </div>
                        <button type="submit" class="w-full bg-orange-500 text-white py-3 rounded-lg font-semibold hover:bg-orange-600 transition">
                            Pilih Meja
                        </button>
                    </form>
                </div>

                <!-- Back Button -->
                <div class="mt-6 text-center">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-orange-600 text-sm">
                        ← Kembali ke Homepage
                    </a>
                </div>
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