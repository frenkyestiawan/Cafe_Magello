<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order {{ $order->order_code }} - Magello Cafe</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">

    <script>
        try {
            var t = localStorage.getItem('magello-theme');
            document.documentElement.setAttribute('data-theme', t === 'light' ? 'light' : 'dark');
        } catch (e) {}
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/magello.css', 'resources/js/magello.js'])

    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; color: #000 !important; }
            .receipt-card { border: none !important; box-shadow: none !important; background: #fff !important; color: #000 !important; width: 100% !important; max-width: 100% !important; }
            .receipt-card * { color: #000 !important; }
        }
    </style>
</head>
<body>
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <symbol id="i-sun" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M12 3v2M12 19v2M3 12h2M19 12h2M5.6 5.6 7 7M17 17l1.4 1.4M5.6 18.4 7 17M17 7l1.4-1.4"/></symbol>
    <symbol id="i-moon" viewBox="0 0 24 24"><path d="M20 14.5A8 8 0 0 1 9.5 4 8 8 0 1 0 20 14.5Z"/></symbol>
    <symbol id="i-utensils" viewBox="0 0 24 24"><path d="M7 3v8M4 3v5a3 3 0 0 0 6 0V3M7 11v10M17 21V3c-2 1-3 4-3 8h3"/></symbol>
    <symbol id="i-printer" viewBox="0 0 24 24"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v8H6z"/></symbol>
</svg>

<div id="toast-region" class="toast-region" aria-live="polite"></div>

<!-- Navbar -->
<header class="navbar no-print">
    <div class="container navbar-inner">
        <a href="{{ route('home') }}" class="brand" aria-label="Magello, ke halaman utama">
            <span class="brand-mark"><svg class="icon"><use href="#i-utensils"/></svg></span>
            Magello
        </a>
        <div class="nav-actions">
            <button type="button" class="theme-switch" role="switch" aria-checked="true" aria-label="Mode gelap" data-theme-toggle>
                <span class="knob">
                    <svg class="icon i-moon"><use href="#i-moon"/></svg>
                    <svg class="icon i-sun"><use href="#i-sun"/></svg>
                </span>
            </button>
            <a href="{{ route('home') }}" class="btn btn-ghost" style="font-size: 13px;">
                Kembali ke Home
            </a>
        </div>
    </div>
</header>

<main class="container" style="padding-block: 32px; display: flex; justify-content: center;">
    <div style="width: 100%; max-width: 480px;">
        <div class="card receipt-card" style="padding: 28px; font-family: 'Courier New', Courier, monospace;">
            <!-- Order Header -->
            <div style="text-align: center; margin-bottom: 20px; border-bottom: 2px dashed var(--border); padding-bottom: 16px;">
                <h1 style="font-size: 22px; font-weight: 700; margin-bottom: 4px; letter-spacing: 0.5px; color: var(--heading); font-family: 'Courier New', Courier, monospace;">ORDER #{{ $order->order_code }}</h1>
                <p style="color: var(--muted); font-size: 14px;">Meja {{ $order->restaurantTable->table_number ?? '-' }}</p>
                <p style="color: var(--muted); font-size: 12px; margin-top: 4px;">{{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>

            <!-- Order Items -->
            <div style="margin-bottom: 20px;">
                @foreach($order->orderDetails as $detail)
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; font-size: 14px;">
                    <div style="flex: 1; padding-right: 12px;">
                        <p style="font-weight: 600; color: var(--text);">
                            {{ $detail->menu->name }}
                            @if(!empty($detail->variant_name))
                                <span style="font-size: 12px; color: var(--accent);">({{ $detail->variant_name }})</span>
                            @endif
                            @if($detail->level)
                                <span style="font-size: 12px; color: var(--muted);">Lvl {{ $detail->level }}</span>
                            @endif
                        </p>
                        @if($detail->notes)
                        <p style="font-size: 12px; color: var(--muted); font-style: italic;">Catatan: {{ $detail->notes }}</p>
                        @endif
                    </div>
                    <div style="text-align: right; white-space: nowrap;">
                        <p style="font-weight: 600; color: var(--muted);">x{{ $detail->quantity }}</p>
                        <p style="color: var(--text);">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Divider -->
            <div style="border-top: 2px dashed var(--border); margin-block: 16px;"></div>

            <!-- Total -->
            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 18px; font-weight: 700; margin-bottom: 20px;">
                <span style="color: var(--heading);">TOTAL</span>
                <span style="color: var(--price);">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>

            <!-- Customer Info -->
            @if($order->customer_name || $order->customer_phone)
            <div style="background: var(--surface-2); border: 1px solid var(--border); border-radius: 8px; padding: 12px; margin-bottom: 20px; font-size: 13px;">
                @if($order->customer_name)
                <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                    <span style="color: var(--muted);">Nama:</span>
                    <span style="color: var(--text); font-weight: 600;">{{ $order->customer_name }}</span>
                </div>
                @endif
                @if($order->customer_phone)
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--muted);">No HP:</span>
                    <span style="color: var(--text); font-weight: 600;">{{ $order->customer_phone }}</span>
                </div>
                @endif
            </div>
            @endif

            <!-- Order Status -->
            <div style="display: flex; flex-direction: column; gap: 6px; margin-bottom: 24px; font-size: 13px; border-top: 1px dashed var(--border); padding-top: 16px;">
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--muted);">Status Pesanan:</span>
                    <span class="badge" style="{{ $order->status === 'completed' ? 'background: var(--ok-bg); color: var(--ok-text); border: 1px solid var(--ok-border);' : 'background: var(--surface-2); color: var(--price); border: 1px solid var(--border);' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--muted);">Status Pembayaran:</span>
                    <span class="badge" style="{{ $order->payment_status === 'paid' ? 'background: var(--ok-bg); color: var(--ok-text); border: 1px solid var(--ok-border);' : 'background: var(--err-bg); color: var(--err-text); border: 1px solid var(--err-border);' }}">
                        {{ $order->payment_status === 'paid' ? 'Lunas' : 'Belum Lunas' }}
                    </span>
                </div>
            </div>

            <!-- Actions -->
            <div class="no-print" style="display: flex; gap: 12px;">
                @if($order->payment_status === 'unpaid')
                <a href="{{ route('payment.show', $order->id) }}" class="btn" style="flex: 1; min-height: 44px; text-align: center;">
                    Bayar Sekarang
                </a>
                @else
                <a href="{{ route('order.index') }}" class="btn btn-ghost" style="flex: 1; min-height: 44px; text-align: center;">
                    Pesanan Baru
                </a>
                @endif
                <button type="button" onclick="window.print()" class="btn btn-ghost" style="flex: 1; min-height: 44px;">
                    <svg class="icon"><use href="#i-printer"/></svg>
                    Cetak Struk
                </button>
            </div>
        </div>
    </div>
</main>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.MagelloUI && window.MagelloUI.toast) {
            window.MagelloUI.toast("{{ session('success') }}", "success");
        }
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.MagelloUI && window.MagelloUI.toast) {
            window.MagelloUI.toast("{{ session('error') }}", "error");
        }
    });
</script>
@endif
</body>
</html>