<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Pemesan - Magello Cafe</title>
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
    @vite(['resources/css/customers.css', 'resources/js/customers.js'])
</head>
<body class="page-bg page-checkout">
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <symbol id="i-sun" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M12 3v2M12 19v2M3 12h2M19 12h2M5.6 5.6 7 7M17 17l1.4 1.4M5.6 18.4 7 17M17 7l1.4-1.4"/></symbol>
    <symbol id="i-moon" viewBox="0 0 24 24"><path d="M20 14.5A8 8 0 0 1 9.5 4 8 8 0 1 0 20 14.5Z"/></symbol>
    <symbol id="i-utensils" viewBox="0 0 24 24"><path d="M7 3v8M4 3v5a3 3 0 0 0 6 0V3M7 11v10M17 21V3c-2 1-3 4-3 8h3"/></symbol>
    <symbol id="i-user" viewBox="0 0 24 24"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></symbol>
</svg>

<div id="toast-region" class="toast-region" aria-live="polite"></div>

<!-- Navbar -->
<header class="navbar">
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
            <a href="{{ route('order.index') }}" class="btn btn-ghost" style="font-size: 13px;">
                Kembali ke Menu
            </a>
        </div>
    </div>
</header>

<main class="container" style="padding-block: 32px; display: flex; justify-content: center;">
    <div style="width: 100%; max-width: 520px;">
        <div class="card" style="padding: 28px;">
            <div style="text-align: center; margin-bottom: 24px;">
                <div style="display: inline-grid; place-items: center; width: 56px; height: 56px; border-radius: 50%; background: var(--surface-2); color: var(--accent); margin-bottom: 12px; border: 1px solid var(--border);">
                    <svg class="icon" style="width: 28px; height: 28px;"><use href="#i-user"/></svg>
                </div>
                <h1 style="font-size: 24px; margin-bottom: 6px;">Data Pemesan</h1>
                <p style="color: var(--muted); font-size: 14px;">Silakan lengkapi data untuk konfirmasi pesanan</p>
            </div>

            <!-- Order Summary -->
            <div class="notice" style="flex-direction: column; gap: 10px; margin-bottom: 24px; background: var(--bg-elev);">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed var(--border); padding-bottom: 8px;">
                    <strong style="color: var(--heading); font-size: 14px;">Ringkasan Pesanan</strong>
                    <span class="badge">{{ count($cart) }} Item</span>
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px; max-height: 180px; overflow-y: auto;">
                    @foreach($cart as $item)
                    <div style="display: flex; justify-content: space-between; font-size: 13px;">
                        <span style="color: var(--text);">{{ $item['name'] }} <span style="color: var(--muted);">x{{ $item['quantity'] }}</span></span>
                        <span style="color: var(--muted); font-weight: 500;">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                <div style="border-top: 1px solid var(--border); margin-top: 4px; padding-top: 10px; display: flex; justify-content: space-between; font-weight: 700;">
                    <span style="color: var(--heading);">Total</span>
                    <span style="color: var(--price); font-size: 16px;">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('order.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 18px;">
                @csrf

                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--heading); margin-bottom: 6px;">
                        Nama Lengkap <span style="color: var(--err-text);">*</span>
                    </label>
                    <div class="field" style="border-radius: 12px; height: 44px;">
                        <input type="text" name="customer_name" placeholder="Masukkan nama lengkap Anda" required style="width: 100%;">
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--heading); margin-bottom: 6px;">
                        Nomor HP <span style="color: var(--err-text);">*</span>
                    </label>
                    <div class="field" style="border-radius: 12px; height: 44px;">
                        <input type="tel" name="customer_phone" placeholder="Contoh: 08123456789"
                               pattern="[0-9]{10,13}" title="Nomor HP harus 10-13 digit angka" required style="width: 100%;">
                    </div>
                    <p style="color: var(--muted); font-size: 12px; margin-top: 4px;">Digunakan untuk notifikasi status pesanan</p>
                </div>

                <div style="display: flex; gap: 12px; margin-top: 8px;">
                    <a href="{{ route('order.index') }}" class="btn btn-ghost" style="flex: 1; min-height: 44px;">
                        Kembali
                    </a>
                    <button type="submit" class="btn" style="flex: 1; min-height: 44px;">
                        Konfirmasi Pesanan
                    </button>
                </div>
            </form>
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