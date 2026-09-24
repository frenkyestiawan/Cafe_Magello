<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pembayaran - Magello Cafe</title>
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
<body class="page-bg page-payment">
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <symbol id="i-sun" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M12 3v2M12 19v2M3 12h2M19 12h2M5.6 5.6 7 7M17 17l1.4 1.4M5.6 18.4 7 17M17 7l1.4-1.4"/></symbol>
    <symbol id="i-moon" viewBox="0 0 24 24"><path d="M20 14.5A8 8 0 0 1 9.5 4 8 8 0 1 0 20 14.5Z"/></symbol>
    <symbol id="i-utensils" viewBox="0 0 24 24"><path d="M7 3v8M4 3v5a3 3 0 0 0 6 0V3M7 11v10M17 21V3c-2 1-3 4-3 8h3"/></symbol>
    <symbol id="i-qr" viewBox="0 0 24 24"><path d="M3 3h6v6H3zm12 0h6v6h-6zM3 15h6v6H3zm12 12v-3h3v3zm3-6v3h3v-3zm-3-3h6v3h-6zm-3 0h3v3h-3zm0 3v3h-3v-3zm-6-3h3v3H9zm0-3h3v3H9z"/></symbol>
    <symbol id="i-clock" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></symbol>
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
                    <svg class="icon" style="width: 28px; height: 28px;"><use href="#i-qr"/></svg>
                </div>
                <h1 style="font-size: 24px; margin-bottom: 6px;">Pembayaran QRIS</h1>
                <p style="color: var(--muted); font-size: 14px;">Scan QR code untuk melakukan pembayaran</p>
            </div>

            <!-- Payment Info -->
            <div class="notice" style="flex-direction: column; gap: 6px; margin-bottom: 20px; background: var(--bg-elev);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--muted); font-size: 14px;">Order #{{ $order->order_code }}</span>
                    <span style="color: var(--price); font-weight: 700; font-size: 18px;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
                <div style="font-size: 13px; color: var(--muted);">
                    Meja {{ $order->restaurantTable->table_number ?? '-' }}
                </div>
            </div>

            <!-- QR Code Display -->
            <div style="background: var(--surface-2); border: 2px dashed var(--border); border-radius: 16px; padding: 24px; text-align: center; margin-bottom: 20px;">
                <div style="background: #ffffff; padding: 16px; border-radius: 12px; display: inline-block; margin-bottom: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    <!-- Placeholder QR Code -->
                    <div style="width: 192px; height: 192px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                        <div style="text-align: center;">
                            <svg class="icon" style="width: 64px; height: 64px; color: #9ca3af; margin: 0 auto 8px;"><use href="#i-qr"/></svg>
                            <p style="color: #6b7280; font-size: 13px; font-weight: 600; font-family: sans-serif;">QR Code QRIS</p>
                        </div>
                    </div>
                </div>
                <p style="color: var(--text); font-size: 14px; font-weight: 500; margin-bottom: 4px;">Scan menggunakan aplikasi e-wallet Anda</p>
                <p style="color: var(--muted); font-size: 12px;">DANA, GoPay, OVO, ShopeePay, BCA, dll</p>
            </div>

            <!-- Timer -->
            <div class="notice is-warn" style="align-items: center; justify-content: space-between; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <svg class="icon" style="color: var(--price);"><use href="#i-clock"/></svg>
                    <span style="font-weight: 600; font-size: 14px;">Sisa Waktu Pembayaran:</span>
                </div>
                <span id="timer" style="font-weight: 700; font-size: 18px; font-family: monospace; color: var(--price);">10:00</span>
            </div>

            <!-- Check Status Button -->
            <form action="{{ route('payment.check-status', $order->id) }}" method="POST" style="margin-bottom: 12px;">
                @csrf
                <button type="submit" class="btn btn-block" style="min-height: 46px; font-size: 15px;">
                    Cek Status Bayar
                </button>
            </form>

            <!-- Cancel Button -->
            <div style="text-align: center;">
                <a href="{{ route('order.show', $order->id) }}" class="btn btn-ghost btn-block" style="min-height: 40px; font-size: 13px; border-color: transparent;">
                    Batalkan & Kembali
                </a>
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

<script>
    // Timer countdown
    let timeLeft = 600; // 10 minutes in seconds
    const timerElement = document.getElementById('timer');

    function updateTimer() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        if (timerElement) {
            timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }
        
        if (timeLeft > 0) {
            timeLeft--;
            setTimeout(updateTimer, 1000);
        } else {
            if (timerElement) timerElement.textContent = "00:00";
            window.location.href = "{{ route('order.show', $order->id) }}";
        }
    }

    updateTimer();
</script>
</body>
</html>