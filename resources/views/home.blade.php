<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magello Cafe - Coffee, Food & Enjoy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-amber-50 to-orange-100 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-orange-600">Magello</h1>
                </div>
                <div>
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-orange-600 text-sm font-medium">
                        Login Admin
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="flex items-center justify-center min-h-[calc(100vh-4rem)] px-4">
        <div class="text-center max-w-2xl">
            <!-- Logo/Brand -->
            <div class="mb-8">
                <div class="inline-block bg-orange-500 text-white rounded-full p-6 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z" />
                    </svg>
                </div>
                <h1 class="text-5xl font-bold text-gray-800 mb-2">Magello Cafe</h1>
                <p class="text-xl text-gray-600">Coffee, Food & Enjoy</p>
            </div>

            <!-- Description -->
            <p class="text-gray-600 mb-8 text-lg">
                Nikmati pengalaman memesan menu favorit Anda dengan mudah dan cepat. 
                Scan QR Code yang tersedia di meja untuk mulai pemesanan.
            </p>

            <!-- CTA Button -->
            <div class="space-y-4">
                <a href="{{ route('order.select-table') }}" 
                   class="inline-block bg-orange-500 text-white px-8 py-4 rounded-full text-lg font-semibold hover:bg-orange-600 transition transform hover:scale-105 shadow-lg">
                    Pesan Sekarang
                </a>

                <div class="flex items-center justify-center gap-2 text-gray-500 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                    <span>Scan QR Code di meja untuk memesan</span>
                </div>
            </div>

            <!-- Features -->
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="text-orange-500 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800">Cepat</h3>
                    <p class="text-gray-600 text-sm">Pesan menu dalam hitungan detik</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="text-orange-500 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800">Mudah</h3>
                    <p class="text-gray-600 text-sm">Tanpa antri, langsung dari meja</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="text-orange-500 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800">Nyaman</h3>
                    <p class="text-gray-600 text-sm">Menu variatif dan harga terjangkau</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 py-6 text-center text-gray-600 text-sm">
            <p>&copy; 2026 Magello Cafe. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>