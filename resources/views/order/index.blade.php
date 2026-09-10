<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magello Cafe - Self Order</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex h-screen">
        <!-- Main Content - Menu Items -->
        <div class="flex-1 p-6 overflow-y-auto">
            <div class="max-w-6xl mx-auto">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-800">Magello Cafe</h1>
                    <p class="text-gray-600">Silakan pilih menu yang Anda inginkan</p>
                </div>

                <!-- Table Selection -->
                @if(!$tableId)
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">Pilih Meja</h2>
                    <form action="{{ route('order.set-table') }}" method="POST">
                        @csrf
                        <div class="flex gap-4">
                            <input type="text" name="table_number" placeholder="Nomor Meja" 
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                                Pilih Meja
                            </button>
                        </div>
                    </form>
                </div>
                @else
                @php
                    $table = \App\Models\RestaurantTable::find($tableId);
                @endphp
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    <span class="font-semibold">Meja Terpilih:</span> {{ $table ? $table->table_number : $tableId }}
                </div>
                @endif

                <!-- Category Filter -->
                <div class="flex gap-2 mb-6 flex-wrap">
                    <button class="px-4 py-2 bg-blue-500 text-white rounded-full font-medium">Semua</button>
                    <button class="px-4 py-2 bg-white text-gray-700 rounded-full font-medium hover:bg-gray-100">Kopi</button>
                    <button class="px-4 py-2 bg-white text-gray-700 rounded-full font-medium hover:bg-gray-100">Non-Kopi</button>
                    <button class="px-4 py-2 bg-white text-gray-700 rounded-full font-medium hover:bg-gray-100">Makanan</button>
                    <button class="px-4 py-2 bg-white text-gray-700 rounded-full font-medium hover:bg-gray-100">Camilan</button>
                </div>

                <!-- Menu Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($menus as $menu)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <div class="h-48 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">Image</span>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-2">{{ $menu->name }}</h3>
                            <p class="text-gray-600 text-sm mb-3">{{ $menu->description ?? 'Tidak ada deskripsi' }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-bold text-blue-600">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                <form action="{{ route('order.add-to-cart') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
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
            <form action="{{ route('order.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Nama Pelanggan (Opsional)</label>
                    <input type="text" name="customer_name" placeholder="Masukkan nama Anda"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-3 rounded-lg font-semibold hover:bg-blue-600 transition">
                    Lanjut ke Pembayaran
                </button>
            </form>
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
</body>
</html>