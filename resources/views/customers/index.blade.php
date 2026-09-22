<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                                <form action="{{ route('order.add-to-cart') }}" method="POST" class="js-add-to-cart-form" data-id="{{ $menu->id }}" data-name="{{ $menu->name }}" data-price="{{ $menu->price }}">
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

            <div id="customer-cart-empty" class="text-center py-12" hidden>
                <div class="text-gray-400 text-6xl mb-4">🛒</div>
                <p class="text-gray-500">Keranjang kosong</p>
                <p class="text-gray-400 text-sm">Silakan pilih menu terlebih dahulu</p>
            </div>

            <div id="customer-cart-list" class="space-y-4 mb-6"></div>

            <div id="customer-cart-total" class="border-t-2 border-gray-200 pt-4 mb-6" hidden>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Subtotal</span>
                    <span id="customer-subtotal" class="font-medium">Rp 0</span>
                </div>
                <div class="flex justify-between items-center text-xl font-bold">
                    <span>Total</span>
                    <span id="customer-total" class="text-blue-600">Rp 0</span>
                </div>
            </div>

            @if($tableId)
                <a href="{{ route('order.checkout') }}" id="customer-checkout" class="block w-full bg-orange-500 text-white py-3 rounded-lg font-semibold hover:bg-orange-600 transition text-center" hidden>
                    Lanjut ke Pembayaran
                </a>
            @else
                <a href="{{ route('order.select-table') }}" class="block w-full bg-gray-400 text-white py-3 rounded-lg font-semibold text-center cursor-not-allowed">
                    Pilih Meja Terlebih Dahulu
                </a>
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
        const cartKey = 'magello-cart';
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        function normalizeCartData(rawCart) {
            const normalized = {};
            Object.keys(rawCart || {}).forEach(function (key) {
                const item = rawCart[key];
                if (!item || !item.name) return;
                const qty = Number(item.quantity || item.qty || 0);
                if (qty <= 0) return;
                normalized[String(item.id || key)] = {
                    id: String(item.id || key),
                    name: item.name,
                    price: Number(item.price || 0),
                    quantity: qty,
                    qty: qty
                };
            });
            return normalized;
        }

        function syncCartToServer(cart) {
            if (!csrfToken) return;
            fetch('{{ route('order.sync-cart') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ cart: normalizeCartData(cart) })
            }).catch(function () {});
        }

        function rupiah(value) {
            return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
        }

        function saveCart(cart) {
            localStorage.setItem(cartKey, JSON.stringify(cart));
            syncCartToServer(cart);
            renderCart();
        }

        function addToCart(id, name, price) {
            const stored = JSON.parse(localStorage.getItem(cartKey) || '{}');
            const current = stored[id] || { id: String(id), name, price: Number(price), qty: 0 };
            current.qty = Number(current.qty || 0) + 1;
            stored[id] = current;
            saveCart(stored);
        }

        function updateQuantity(id, delta) {
            const stored = JSON.parse(localStorage.getItem(cartKey) || '{}');
            const item = stored[id];
            if (!item) return;
            item.qty = Math.max(0, Number(item.qty || 0) + delta);
            if (item.qty <= 0) delete stored[id];
            saveCart(stored);
        }

        function renderCart() {
            const stored = JSON.parse(localStorage.getItem(cartKey) || '{}');
            const items = Object.values(stored || {}).filter(item => item && Number(item.qty || 0) > 0);
            const list = document.getElementById('customer-cart-list');
            const empty = document.getElementById('customer-cart-empty');
            const totalBox = document.getElementById('customer-cart-total');
            const subtotalEl = document.getElementById('customer-subtotal');
            const totalEl = document.getElementById('customer-total');
            const checkoutBtn = document.getElementById('customer-checkout');

            if (!list || !empty || !totalBox || !subtotalEl || !totalEl) return;

            list.innerHTML = '';
            let subtotal = 0;

            items.forEach(function (item) {
                const qty = Number(item.qty || 0);
                const price = Number(item.price || 0);
                subtotal += qty * price;

                const row = document.createElement('div');
                row.className = 'flex justify-between items-start border-b border-gray-200 pb-4';
                row.innerHTML = `
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800">${item.name}</h4>
                        <p class="text-blue-600 font-medium">${rupiah(price)}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="flex items-center border border-gray-300 rounded">
                            <button type="button" class="px-2 py-1 text-lg" data-action="decrease" data-id="${item.id}">-</button>
                            <span class="w-8 text-center">${qty}</span>
                            <button type="button" class="px-2 py-1 text-lg" data-action="increase" data-id="${item.id}">+</button>
                        </div>
                        <button type="button" class="text-red-500 hover:text-red-700 ml-2" data-action="remove" data-id="${item.id}">✕</button>
                    </div>
                `;
                list.appendChild(row);
            });

            const hasItems = items.length > 0;
            empty.hidden = hasItems;
            list.hidden = !hasItems;
            totalBox.hidden = !hasItems;
            if (checkoutBtn) checkoutBtn.hidden = !hasItems;

            subtotalEl.textContent = rupiah(subtotal);
            totalEl.textContent = rupiah(subtotal);

            list.querySelectorAll('button[data-action]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const action = button.getAttribute('data-action');
                    const id = button.getAttribute('data-id');
                    if (!id) return;
                    if (action === 'increase') updateQuantity(id, 1);
                    if (action === 'decrease') updateQuantity(id, -1);
                    if (action === 'remove') updateQuantity(id, -9999);
                });
            });
        }

        document.querySelectorAll('.js-add-to-cart-form').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                const id = form.getAttribute('data-id');
                const name = form.getAttribute('data-name');
                const price = Number(form.getAttribute('data-price') || 0);
                addToCart(id, name, price);
                form.submit();
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            renderCart();
            const savedCart = JSON.parse(localStorage.getItem(cartKey) || '{}');
            if (Object.keys(savedCart).length) {
                syncCartToServer(savedCart);
            }
        });

        window.addEventListener('storage', function (event) {
            if (event.key === cartKey) {
                renderCart();
            }
        });

        function filterMenu(category) {
            document.querySelectorAll('.category-btn').forEach(btn => {
                if (btn.dataset.category == category || (category === 'all' && btn.dataset.category === 'all')) {
                    btn.classList.remove('bg-white', 'text-gray-700');
                    btn.classList.add('bg-orange-500', 'text-white');
                } else {
                    btn.classList.remove('bg-orange-500', 'text-white');
                    btn.classList.add('bg-white', 'text-gray-700');
                }
            });

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