<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order - Magello Cafe</title>
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

    <div class="flex h-[calc(100vh-4rem)]">
        <!-- Main Content - Menu Items -->
        <div class="flex-1 p-6 overflow-y-auto">
            <div class="max-w-6xl mx-auto">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-800">Magello Cafe</h1>
                    <p class="text-gray-600">Silakan pilih menu yang Anda inginkan</p>
                </div>

                <!-- Table Info -->
                @if($tableId)
                @php
                    $table = \App\Models\RestaurantTable::find($tableId);
                @endphp
                <div class="bg-orange-100 border-l-4 border-orange-500 p-4 mb-6 rounded-r-lg">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <div>
                            <p class="font-bold text-orange-800">Order Meja {{ $table ? $table->table_number : $tableId }}</p>
                            <p class="text-orange-600 text-sm">Pesanan dine-in</p>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 mb-6 rounded-r-lg">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <p class="font-bold text-yellow-800">Belum ada meja terpilih</p>
                            <p class="text-yellow-600 text-sm">
                                <a href="{{ route('order.select-table') }}" class="underline">Pilih meja</a> atau scan QR code
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Category Filter -->
                <div class="flex gap-2 mb-6 flex-wrap">
                                        <button onclick="filterMenu('all')" class="category-btn px-4 py-2 bg-orange-500 text-white rounded-full font-medium" data-category="all">Semua</button>
                    <button onclick="filterMenu(1)" class="category-btn px-4 py-2 bg-white text-gray-700 rounded-full font-medium hover:bg-gray-100" data-category="1">Kopi</button>
                    <button onclick="filterMenu(2)" class="category-btn px-4 py-2 bg-white text-gray-700 rounded-full font-medium hover:bg-gray-100" data-category="2">Non-Kopi</button>
                    <button onclick="filterMenu(3)" class="category-btn px-4 py-2 bg-white text-gray-700 rounded-full font-medium hover:bg-gray-100" data-category="3">Makanan</button>
                    <button onclick="filterMenu(4)" class="category-btn px-4 py-2 bg-white text-gray-700 rounded-full font-medium hover:bg-gray-100" data-category="4">Camilan</button>
                </div>

                <!-- Menu Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($menus as $menu)
                     <div class="menu-item bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition" data-category="{{ $menu->category_id }}">
                        <div class="h-48 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">Image</span>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-2">{{ $menu->name }}</h3>
                            <p class="text-gray-600 text-sm mb-3">{{ $menu->description ?? 'Tidak ada deskripsi' }}</p>
                            <div class="flex justify-between items-center">
                                                                <span class="text-xl font-bold text-orange-600">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                        <form action="{{ route('order.add-to-cart') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                                                        <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition">
                                        + Tambah
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar - Order Summary -->
        <div class="w-96 bg-white shadow-lg p-6 overflow-y-auto">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Pesanan Anda</h2>

            @if(empty($cart))
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">🛒</div>
                <p class="text-gray-500">Keranjang kosong</p>
                <p class="text-gray-400 text-sm">Silakan pilih menu terlebih dahulu</p>
            </div>
            @else
            <!-- Order Items -->
            <div class="space-y-4 mb-6">
                @foreach($cart as $item)
                <div class="flex justify-between items-start border-b border-gray-200 pb-4">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800">{{ $item['name'] }}</h4>
                        @if($item['level'])
                        <p class="text-sm text-gray-500">Level: {{ $item['level'] }}</p>
                        @endif
                        @if($item['notes'])
                        <p class="text-sm text-gray-500">Catatan: {{ $item['notes'] }}</p>
                        @endif
                        <p class="text-blue-600 font-medium">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <form action="{{ route('order.update-cart') }}" method="POST" class="flex items-center">
                            @csrf
                            <input type="hidden" name="menu_id" value="{{ $item['id'] }}">
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0" max="99"
                                   class="w-16 px-2 py-1 border border-gray-300 rounded text-center focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   onchange="this.form.submit()">
                        </form>
                        <a href="{{ route('order.remove-from-cart', $item['id']) }}" 
                           class="text-red-500 hover:text-red-700 ml-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Total -->
            <div class="border-t-2 border-gray-200 pt-4 mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-xl font-bold">
                    <span>Total</span>
                    <span class="text-blue-600">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Checkout Button -->
            @if($tableId)
                        @if(!empty($cart))
            <a href="{{ route('order.checkout') }}" class="block w-full bg-orange-500 text-white py-3 rounded-lg font-semibold hover:bg-orange-600 transition text-center">
                Lanjut ke Pembayaran
            </a>
            @else
            <div class="block w-full bg-gray-400 text-white py-3 rounded-lg font-semibold text-center cursor-not-allowed">
                Keranjang Kosong
            </div>
            @endif
            @else
            <a href="{{ route('order.select-table') }}" class="block w-full bg-gray-400 text-white py-3 rounded-lg font-semibold text-center cursor-not-allowed">
                Pilih Meja Terlebih Dahulu
            </a>
            @endif
            @endif
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
        function filterMenu(category) {
            // Update button styles
            document.querySelectorAll('.category-btn').forEach(btn => {
                if (btn.dataset.category == category || (category === 'all' && btn.dataset.category === 'all')) {
                    btn.classList.remove('bg-white', 'text-gray-700');
                    btn.classList.add('bg-orange-500', 'text-white');
                } else {
                    btn.classList.remove('bg-orange-500', 'text-white');
                    btn.classList.add('bg-white', 'text-gray-700');
                }
            });

            // Filter menu items
            document.querySelectorAll('.menu-item').forEach(item => {
                if (category === 'all' || item.dataset.category == category) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>