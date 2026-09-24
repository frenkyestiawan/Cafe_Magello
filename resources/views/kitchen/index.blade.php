<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KDS - Cafe Magello</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800" onload="setInterval(() => window.location.reload(), 10000)">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Kitchen Display System</p>
                <h1 class="text-3xl font-bold">Cafe Magello</h1>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm font-medium bg-slate-200 px-3 py-2 rounded-lg">{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Logout</button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg bg-emerald-100 border border-emerald-300 text-emerald-700 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-lg bg-red-100 border border-red-300 text-red-700 px-4 py-3">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-amber-600">MENUNGGU</h2>
                    <span class="bg-amber-100 text-amber-700 px-2 py-1 rounded-full text-sm font-semibold">{{ $waitingOrders->count() }}</span>
                </div>
                <div class="mb-3 text-[11px] uppercase tracking-[0.2em] text-slate-400">Auto refresh 10s</div>
                <div class="space-y-4">
                    @forelse($waitingOrders as $order)
                        <article class="border border-slate-200 rounded-xl p-4 bg-amber-50">
                            <div class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-2">Pesanan #{{ $order->order_code }}</div>
                            <div class="flex items-center justify-between text-sm font-semibold mb-2">
                                <span>Meja {{ $order->restaurantTable?->table_number ?? '-' }}</span>
                                <span>{{ $order->created_at->format('H:i') }}</span>
                            </div>
                            <p class="text-sm font-medium mb-3">Nama: {{ $order->customer_name ?? 'Pelanggan' }}</p>
                            <ul class="space-y-2 text-sm mb-4">
                                @foreach($order->orderDetails as $detail)
                                    <li class="flex justify-between gap-3">
                                        <span>
                                            {{ $detail->menu?->name ?? 'Menu' }}
                                            @if(!empty($detail->variant_name))
                                                <span class="text-xs text-slate-500">({{ $detail->variant_name }})</span>
                                            @endif
                                        </span>
                                        <span class="font-semibold">× {{ $detail->quantity }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-xs font-bold uppercase text-amber-700">Status: Menunggu</span>
                                <form action="{{ route('kitchen.orders.start', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg font-semibold">PROSES</button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-500">Tidak ada pesanan menunggu.</div>
                    @endforelse
                </div>
            </section>

            <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-orange-600">DIPROSES</h2>
                    <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded-full text-sm font-semibold">{{ $processingOrders->count() }}</span>
                </div>
                <div class="space-y-4">
                    @forelse($processingOrders as $order)
                        <article class="border border-slate-200 rounded-xl p-4 bg-orange-50">
                            <div class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-2">Pesanan #{{ $order->order_code }}</div>
                            <div class="flex items-center justify-between text-sm font-semibold mb-2">
                                <span>Meja {{ $order->restaurantTable?->table_number ?? '-' }}</span>
                                <span>{{ $order->created_at->format('H:i') }}</span>
                            </div>
                            <p class="text-sm font-medium mb-3">Nama: {{ $order->customer_name ?? 'Pelanggan' }}</p>
                            <ul class="space-y-2 text-sm mb-4">
                                @foreach($order->orderDetails as $detail)
                                    <li class="flex justify-between gap-3">
                                        <span>
                                            {{ $detail->menu?->name ?? 'Menu' }}
                                            @if(!empty($detail->variant_name))
                                                <span class="text-xs text-slate-500">({{ $detail->variant_name }})</span>
                                            @endif
                                        </span>
                                        <span class="font-semibold">× {{ $detail->quantity }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-xs font-bold uppercase text-orange-700">Status: Diproses</span>
                                <form action="{{ route('kitchen.orders.complete', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-semibold">SELESAI</button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-500">Tidak ada pesanan sedang diproses.</div>
                    @endforelse
                </div>
            </section>

            <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-emerald-600">SELESAI</h2>
                    <span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded-full text-sm font-semibold">{{ $completedOrders->count() }}</span>
                </div>
                <div class="space-y-4">
                    @forelse($completedOrders as $order)
                        <article class="border border-slate-200 rounded-xl p-4 bg-emerald-50">
                            <div class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-2">Pesanan #{{ $order->order_code }}</div>
                            <div class="flex items-center justify-between text-sm font-semibold mb-2">
                                <span>Meja {{ $order->restaurantTable?->table_number ?? '-' }}</span>
                                <span>{{ $order->created_at->format('H:i') }}</span>
                            </div>
                            <p class="text-sm font-medium mb-3">Nama: {{ $order->customer_name ?? 'Pelanggan' }}</p>
                            <ul class="space-y-2 text-sm mb-4">
                                @foreach($order->orderDetails as $detail)
                                    <li class="flex justify-between gap-3">
                                        <span>
                                            {{ $detail->menu?->name ?? 'Menu' }}
                                            @if(!empty($detail->variant_name))
                                                <span class="text-xs text-slate-500">({{ $detail->variant_name }})</span>
                                            @endif
                                        </span>
                                        <span class="font-semibold">× {{ $detail->quantity }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-xs font-bold uppercase text-emerald-700">Status: Selesai</span>
                                <form action="{{ route('kitchen.orders.pickup', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-semibold">AMBIL</button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-500">Tidak ada pesanan selesai.</div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</body>
</html>
