<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan - Cafe Magello</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-stone-100 text-slate-800">
    <div class="max-w-5xl mx-auto p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold">Daftar Menu</h1>
            <a href="{{ route('order.select-table') }}" class="bg-amber-600 text-white px-4 py-2 rounded">Pilih Meja</a>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded bg-green-100 border border-green-300 text-green-800 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded bg-red-100 border border-red-300 text-red-800 px-4 py-3">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow p-5">
                <h2 class="text-xl font-semibold mb-4">Menu</h2>
                @if(isset($menus) && $menus->count())
                    <div class="space-y-3">
                        @foreach($menus as $menu)
                            <div class="flex items-center justify-between border-b pb-3">
                                <div>
                                    <p class="font-medium">{{ $menu->name }}</p>
                                    <p class="text-sm text-slate-500">{{ $menu->category?->name ?? 'Umum' }}</p>
                                </div>
                                <span class="font-semibold">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-slate-500">Belum ada menu yang tersedia.</p>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow p-5">
                <h2 class="text-xl font-semibold mb-4">Keranjang</h2>
                @if(isset($cart) && !empty($cart))
                    <div class="space-y-3">
                        @foreach($cart as $item)
                            <div class="flex items-center justify-between border-b pb-2">
                                <span>{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                                <span>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                        <div class="pt-3 font-bold text-right">
                            Total: Rp {{ number_format($subtotal ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('order.checkout') }}" class="block text-center bg-green-600 text-white px-4 py-2 rounded">Checkout</a>
                    </div>
                @else
                    <p class="text-slate-500">Keranjang masih kosong.</p>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
