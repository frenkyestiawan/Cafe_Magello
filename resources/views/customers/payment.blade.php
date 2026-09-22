<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Magello Cafe</title>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">Pembayaran QRIS</h1>
                    <p class="text-gray-600">Scan QR code untuk melakukan pembayaran</p>
                </div>

                <!-- Payment Info -->
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Order #{{ $order->order_code }}</span>
                        <span class="text-orange-600 font-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="text-sm text-gray-500">
                        Meja {{ $order->restaurantTable->table_number ?? '-' }}
                    </div>
                </div>

                <!-- QR Code Display -->
                <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-8 mb-6 text-center">
                    <div class="bg-white p-4 rounded-lg inline-block mb-4">
                        <!-- Placeholder QR Code -->
                        <div class="w-48 h-48 bg-gray-200 flex items-center justify-center">
                            <div class="text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                                <p class="text-gray-400 text-sm">QR Code</p>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-2">Scan menggunakan aplikasi e-wallet Anda</p>
                    <p class="text-gray-500 text-xs">DANA, GoPay, OVO, ShopeePay, dll</p>
                </div>

                <!-- Timer -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-yellow-800 font-medium">Sisa Waktu:</span>
                        </div>
                        <span id="timer" class="text-yellow-800 font-bold text-xl">10:00</span>
                    </div>
                </div>

                <!-- Check Status Button -->
                <form action="{{ route('payment.check-status', $order->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <button type="submit" class="w-full bg-orange-500 text-white py-3 rounded-lg font-semibold hover:bg-orange-600 transition">
                        Cek Status Bayar
                    </button>
                </form>

                <!-- Cancel Button -->
                <div class="mt-4 text-center">
                    <a href="{{ route('order.show', $order->id) }}" class="text-gray-600 hover:text-orange-600 text-sm">
                        Batalkan & Kembali
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

    <script>
        // Timer countdown
        let timeLeft = 600; // 10 minutes in seconds
        const timerElement = document.getElementById('timer');

        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft > 0) {
                timeLeft--;
                setTimeout(updateTimer, 1000);
            } else {
                timerElement.textContent = "00:00";
                // Redirect to order page if time expires
                window.location.href = "{{ route('order.show', $order->id) }}";
            }
        }

        updateTimer();
    </script>
</body>
</html>