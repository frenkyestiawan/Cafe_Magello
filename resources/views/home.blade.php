<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Magello Hangout Space - Coffee, Food &amp; Enjoy</title>
    <meta name="description" content="Pesan kopi, makanan, dan camilan langsung dari meja di Magello Hangout Space.">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Jost:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/css/home.css', 'resources/js/app.js', 'resources/js/home.js'])

    <!-- Terapkan tema sebelum halaman dirender agar tidak berkedip -->
    <script>
        (function () {
            var theme = null;
            try { theme = localStorage.getItem('magello-theme'); } catch (e) {}
            if (!theme) theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            if (theme === 'dark') document.documentElement.classList.add('dark');
        })();
    </script>

    
</head>
<body class="pb-24 md:pb-0" data-open-time="{{ $openTime }}" data-close-time="{{ $closeTime }}" data-kitchen-busy="{{ $kitchenBusy ? '1' : '0' }}">

    <!-- Ornamen bunga garis tipis (terinspirasi hiasan kepala di logo) -->
    <svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
        <defs>
            <symbol id="orn" viewBox="0 0 100 64">
                <g fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
                    <g transform="rotate(-72 50 60)"><path d="M50 60V26"/><ellipse cx="50" cy="17" rx="3.2" ry="7"/><circle cx="50" cy="38" r="2.6"/></g>
                    <g transform="rotate(-48 50 60)"><path d="M50 60V26"/><ellipse cx="50" cy="17" rx="3.2" ry="7"/><circle cx="50" cy="38" r="2.6"/></g>
                    <g transform="rotate(-24 50 60)"><path d="M50 60V26"/><ellipse cx="50" cy="17" rx="3.2" ry="7"/><circle cx="50" cy="38" r="2.6"/></g>
                    <g><path d="M50 60V26"/><ellipse cx="50" cy="17" rx="3.2" ry="7"/><circle cx="50" cy="38" r="2.6"/></g>
                    <g transform="rotate(24 50 60)"><path d="M50 60V26"/><ellipse cx="50" cy="17" rx="3.2" ry="7"/><circle cx="50" cy="38" r="2.6"/></g>
                    <g transform="rotate(48 50 60)"><path d="M50 60V26"/><ellipse cx="50" cy="17" rx="3.2" ry="7"/><circle cx="50" cy="38" r="2.6"/></g>
                    <g transform="rotate(72 50 60)"><path d="M50 60V26"/><ellipse cx="50" cy="17" rx="3.2" ry="7"/><circle cx="50" cy="38" r="2.6"/></g>
                    <path d="M42 61h16"/>
                </g>
            </symbol>
        </defs>
    </svg>

    <a href="#menu" class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-[100] btn-primary">Lewati ke menu</a>

    <!-- ===================== NAVBAR ===================== -->
    <nav class="site-nav" aria-label="Navigasi utama">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4 relative">

            <a href="#beranda" class="flex items-center gap-2.5 z-10" aria-label="Magello Hangout Space, ke awal halaman">
                <img src="{{ asset('images/logo-icon.png') }}" alt="" width="30" height="40" class="h-10 w-auto">
                <span class="font-display text-2xl font-semibold tracking-wide text-heading">Magello</span>
            </a>

            <div class="hidden md:flex items-center gap-7 absolute left-1/2 -translate-x-1/2 z-0">
                <a href="#menu" class="nav-link">Menu</a>
                <a href="#tentang" class="nav-link">Tentang</a>
                <a href="#lokasi" class="nav-link">Lokasi</a>
                <a href="{{ $trackUrl }}" class="nav-link">Cek Pesanan</a>
            </div>

            <div class="flex items-center gap-2.5 z-10 ml-auto">
                <span class="pill hidden lg:inline-flex js-status" data-state="closed">
                    <span class="dot"></span><span class="js-status-label">Memeriksa jam buka</span>
                </span>

                @if ($tableNo !== '')
                    <span class="pill hidden sm:inline-flex" title="Nomor meja Anda">Meja {{ $tableNo }}</span>
                @endif

                <button id="theme-toggle" type="button" role="switch" aria-checked="false" aria-label="Mode gelap" class="theme-toggle">
                    <span class="knob">
                        <svg class="i-sun" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M4.9 4.9l1.4 1.4m11.4 11.4l1.4 1.4M2 12h2m16 0h2M4.9 19.1l1.4-1.4m11.4-11.4l1.4-1.4"/></svg>
                        <svg class="i-moon" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.8A9 9 0 1111.2 3 7 7 0 0021 12.8z"/></svg>
                    </span>
                </button>

                <button type="button" class="icon-btn js-open-cart" aria-label="Buka keranjang">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span class="count-bubble js-cart-count" hidden>0</span>
                </button>

                <a href="{{ route('login') }}" class="btn-ghost hidden sm:inline-flex">Login</a>
                <a href="{{ route('order.select-table') }}" class="btn-primary hidden md:inline-flex">Pesan</a>

                <button id="nav-toggle" type="button" class="icon-btn md:hidden" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="mobile-nav">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
            </div>
        </div>

        <div id="mobile-nav" class="md:hidden border-t" style="border-color: var(--line); background: var(--bg);" hidden>
            <div class="max-w-6xl mx-auto px-4 py-4 flex flex-col gap-3">
                <span class="pill self-start js-status" data-state="closed"><span class="dot"></span><span class="js-status-label">Memeriksa jam buka</span></span>
                <a href="#menu" class="nav-link self-start js-close-nav">Menu</a>
                <a href="#tentang" class="nav-link self-start js-close-nav">Tentang</a>
                <a href="#lokasi" class="nav-link self-start js-close-nav">Lokasi</a>
                <a href="{{ $trackUrl }}" class="nav-link self-start js-close-nav">Cek Pesanan</a>
                <a href="{{ route('login') }}" class="btn-ghost self-start sm:hidden">Login</a>
            </div>
        </div>
    </nav>

    <main>
        <!-- ===================== HERO ===================== -->
        <header id="beranda" class="relative overflow-hidden">
            <div class="hero-glow" aria-hidden="true"></div>

            <div class="hero-content relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-12 md:pt-14 md:pb-16 grid md:grid-cols-[1.1fr_.9fr] gap-6 md:gap-10 items-center">

                <div class="hero-copy order-2 md:order-1">
                    <div class="flex flex-wrap items-center gap-2 mb-5">
                        <span class="pill js-status" data-state="closed"><span class="dot"></span><span class="js-status-label">Memeriksa jam buka</span></span>
                        @if ($tableNo !== '')
                            <span class="pill">Meja {{ $tableNo }}</span>
                        @endif
                    </div>

                    <h1 class="h-display text-[3rem] sm:text-6xl lg:text-[5rem] leading-none">
                        Duduk santai, pesan dari meja.
                    </h1>

                    <p class="mt-5 max-w-md text-lg text-muted">
                        Kopi, makanan, dan camilan Magello bisa dipesan langsung dari tempat Anda duduk.
                        Scan QR Code di meja, pilih menu, dan pesanan segera disiapkan.
                    </p>

                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        <a href="{{ route('order.select-table') }}" class="btn-primary btn-lg">
                            {{ $tableNo !== '' ? 'Lanjut memesan' : 'Pesan sekarang' }}
                        </a>
                        <a href="#menu" class="btn-ghost btn-lg">Lihat menu</a>
                    </div>

                    <p class="mt-5 text-sm text-muted">Estimasi penyajian sekitar 10-15 menit, tergantung menu.</p>
                </div>

                <!-- Logo asli -->
                <div class="hero-logo-wrap order-1 md:order-2 justify-self-center">
                    <img src="{{ asset('images/logo-magello-light.png') }}" alt="Logo Magello Hangout Space"
                         width="447" height="447" class="hero-logo logo-light w-[240px] sm:w-[340px] md:w-[440px] h-auto">
                    <img src="{{ asset('images/logo-magello-dark.png') }}" alt="Logo Magello Hangout Space"
                         width="447" height="447" class="hero-logo logo-dark w-[240px] sm:w-[340px] md:w-[440px] h-auto">
                </div>
            </div>
        </header>

        <!-- ===================== PANEL INFO ===================== -->
        <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-12" aria-label="Informasi singkat">
            <div class="info-panel">
                <div class="info-cell">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="info-label">Jam buka</p>
                        <p class="info-value">Weekdays 09.00 - 23.00<br>Weekends 09.00 - 00.00</p>
                        <p class="info-label">WIB</p>
                    </div>
                </div>
                <a href="#lokasi" class="info-cell">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <div>
                        <p class="info-label">Lokasi</p>
                        <p class="info-value">Pekanbaru, Riau</p>
                        <p class="info-label">Lihat alamat</p>
                    </div>
                </a>
                <div class="info-cell">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <div>
                        <p class="info-label">Estimasi penyajian</p>
                        <p class="info-value">10-15 menit</p>
                        <p class="info-label">Minuman lebih cepat</p>
                    </div>
                </div>
                <a href="{{ $trackUrl }}" class="info-cell">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <div>
                        <p class="info-label">Sudah memesan?</p>
                        <p class="info-value">Cek pesanan</p>
                        <p class="info-label">Pantau statusnya</p>
                    </div>
                </a>
            </div>
        </section>

        <!-- ===================== MENU FAVORIT ===================== -->
        <section id="menu" class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 md:pt-28 pb-20">
            <div class="ornament-divider max-w-md mx-auto" aria-hidden="true">
                <span class="line"></span>
                <svg class="w-16 h-10 shrink-0"><use href="#orn"/></svg>
                <span class="line"></span>
            </div>

            <div class="mt-6 text-center">
                <h2 class="h-display text-4xl md:text-5xl">Menu favorit</h2>
                <p class="mt-3 text-muted">Pilihan yang paling sering dipesan. Daftar lengkap tersedia saat Anda memesan.</p>
            </div>

            <div class="mt-10 flex flex-col md:flex-row md:items-center gap-4 justify-between min-w-0">
                <div class="category-slider-container flex-1 min-w-0 flex items-center gap-2">
                    <button type="button" class="slider-arrow js-slider-prev" aria-label="Geser kategori ke kiri">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>

                    <div class="category-scroll-wrap" role="group" aria-label="Filter kategori" style="flex: 1 1 auto; min-width: 0; overflow-x: auto; overflow-y: hidden; -webkit-overflow-scrolling: touch; scrollbar-width: none; -ms-overflow-style: none;">
                        <div class="category-scroll-inner" style="display: flex !important; flex-wrap: nowrap !important; align-items: center; gap: 0.55rem; white-space: nowrap; width: max-content; min-width: max-content;">
                            @foreach ($categories as $cat)
                                <button type="button" class="chip js-chip" style="flex: 0 0 auto !important; white-space: nowrap !important;" data-cat="{{ $cat }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}">{{ $cat }}</button>
                            @endforeach
                        </div>
                    </div>

                    <button type="button" class="slider-arrow js-slider-next" aria-label="Geser kategori ke kanan">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                    </button>
                </div>

                <div class="search-box md:w-72" style="flex-shrink: 0;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input id="menu-search" type="search" placeholder="Cari menu" aria-label="Cari menu" autocomplete="off">
                </div>
            </div>

            <p id="menu-count" class="sr-only" role="status" aria-live="polite"></p>

            <div id="menu-grid" class="mt-8 grid grid-cols-1 min-[480px]:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach ($menus as $m)
                    @php
                        $variants = collect($m['variants'] ?? [])->sortBy(function ($variant) {
                            $order = ['Small' => 1, 'Medium' => 2, 'Large' => 3];
                            return $order[$variant['name']] ?? 99;
                        })->values();
                        $selectedVariant = $variants->first();
                    @endphp
                    <article class="menu-card"
                             data-cat="{{ $m['category'] }}"
                             data-name="{{ $m['name'] }}"
                             data-desc="{{ $m['desc'] }}">
                        <div class="menu-oval-wrap">
                            <div class="menu-oval">
                                @if (!empty($m['image']))
                                    <img src="{{ $m['image'] }}" alt="{{ $m['name'] }}" loading="lazy">
                                @else
                                    <div class="menu-visual-content" aria-hidden="true"></div>
                                @endif
                            </div>
                            @if (!empty($m['badge']))
                                <span class="badge">{{ $m['badge'] }}</span>
                            @endif
                        </div>

                        <h3 class="font-display text-[1.45rem] font-semibold leading-tight text-heading">{{ $m['name'] }}</h3>
                        <p class="text-sm text-muted">{{ $m['desc'] }}</p>

                        @if ($variants->isNotEmpty())
                            <div class="menu-variant-group" role="radiogroup" aria-label="Variant {{ $m['name'] }}">
                                @foreach ($variants as $variant)
                                    <label class="menu-variant-option">
                                        <input
                                            type="radio"
                                            name="variant_{{ $m['id'] }}"
                                            value="{{ $variant['id'] ?? $variant['name'] }}"
                                            data-variant-id="{{ $variant['id'] ?? '' }}"
                                            data-variant-name="{{ $variant['name'] }}"
                                            data-variant-price="{{ $variant['price'] ?? $m['price'] }}"
                                            {{ $loop->first ? 'checked' : '' }}
                                            class="js-menu-variant-input"
                                        >
                                        <span>{{ $variant['name'] }}</span>
                                        <small>Rp {{ number_format((float) ($variant['price'] ?? $m['price']), 0, ',', '.') }}</small>
                                    </label>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-auto pt-4 flex items-end justify-between gap-2">
                            <div>
                                <p class="font-medium text-heading js-menu-price">Rp {{ number_format((float) ($selectedVariant['price'] ?? $m['price']), 0, ',', '.') }}</p>
                                <p class="eta">Sekitar {{ $m['eta'] }} menit</p>
                            </div>
                            <button type="button" class="add-btn js-add"
                                    data-id="{{ $m['id'] }}"
                                    data-name="{{ $m['name'] }}"
                                    data-price="{{ (float) ($selectedVariant['price'] ?? $m['price']) }}"
                                    data-variant-id="{{ $selectedVariant['id'] ?? '' }}"
                                    data-variant-name="{{ $selectedVariant['name'] ?? '' }}"
                                    aria-label="Tambah {{ $m['name'] }} ke keranjang">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                                <span class="qty-bubble" hidden>0</span>
                            </button>
                        </div>
                    </article>
                @endforeach
            </div>

            <div id="menu-empty" class="mt-10 text-center" hidden>
                <p class="font-display text-2xl text-heading">Menu tidak ditemukan</p>
                <p class="text-muted mt-1">Coba kata kunci lain atau pilih kategori Semua.</p>
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('order.select-table') }}" class="btn-ghost">Lihat semua menu</a>
            </div>
        </section>

        <!-- ===================== TENTANG (CERITA) ===================== -->
        <section id="tentang" class="story">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24 grid md:grid-cols-[.8fr_1.2fr] gap-12 items-center">
                <div class="justify-self-center">
                    <!-- Ganti isi ini dengan <img src="..." alt="Suasana Magello"> jika foto sudah ada -->
                    <div class="story-oval" aria-hidden="true">
                        <svg class="w-40 h-28 opacity-70"><use href="#orn"/></svg>
                    </div>
                </div>

                <div>
                    <h2 class="h-display text-4xl md:text-5xl">Ruang singgah untuk kopi dan obrolan</h2>
                    <p class="mt-5 max-w-lg text-lg" style="color: var(--on-brand-muted);">
                        Magello Hangout Space dibuat sebagai tempat untuk berhenti sejenak: menikmati kopi,
                        makan, dan berbincang tanpa terburu-buru.
                    </p>
                    <p class="mt-4 max-w-lg text-lg" style="color: var(--on-brand-muted);">
                        Tanpa antri di kasir. Anda cukup memilih menu dari meja, lalu pesanan kami siapkan.
                    </p>
                    <a href="#lokasi" class="btn-on-brand mt-8">Lihat lokasi</a>
                </div>
            </div>
        </section>

        <!-- ===================== CARA MEMESAN ===================== -->
        <section id="cara-memesan" class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
            <div class="text-center">
                <h2 class="h-display text-4xl md:text-5xl">Cara memesan</h2>
                <p class="mt-3 text-muted">Empat langkah, semuanya dari meja Anda.</p>
            </div>

            <ol class="mt-12 grid grid-cols-1 md:grid-cols-4 gap-10 md:gap-6">
                <li class="step flex md:block gap-5">
                    <span class="step-num shrink-0">1</span>
                    <div class="md:mt-5">
                        <h3 class="font-display text-2xl font-semibold text-heading">Scan QR Code</h3>
                        <p class="text-muted text-sm mt-1">Arahkan kamera ke QR Code di meja Anda.</p>
                    </div>
                </li>
                <li class="step flex md:block gap-5">
                    <span class="step-num shrink-0">2</span>
                    <div class="md:mt-5">
                        <h3 class="font-display text-2xl font-semibold text-heading">Pilih menu</h3>
                        <p class="text-muted text-sm mt-1">Tambahkan kopi, makanan, atau camilan ke keranjang.</p>
                    </div>
                </li>
                <li class="step flex md:block gap-5">
                    <span class="step-num shrink-0">3</span>
                    <div class="md:mt-5">
                        <h3 class="font-display text-2xl font-semibold text-heading">Kirim pesanan</h3>
                        <p class="text-muted text-sm mt-1">Periksa keranjang, lalu kirim. Pesanan langsung kami terima.</p>
                    </div>
                </li>
                <li class="step flex md:block gap-5">
                    <span class="step-num shrink-0">4</span>
                    <div class="md:mt-5">
                        <h3 class="font-display text-2xl font-semibold text-heading">Pantau status</h3>
                        <p class="text-muted text-sm mt-1">Lihat perkembangan pesanan lewat Cek Pesanan.</p>
                    </div>
                </li>
            </ol>

            <div class="mt-12 text-center">
                <a href="{{ route('order.select-table') }}" class="btn-primary btn-lg">Pesan sekarang</a>
            </div>
        </section>
    </main>

    <!-- ===================== FOOTER / LOKASI ===================== -->
    <footer id="lokasi" class="site-footer">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-10">
            <div class="md:col-span-1">
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo-icon.png') }}" alt="" width="30" height="40" class="h-10 w-auto">
                    <span class="font-display text-2xl font-semibold" style="color: var(--on-brand);">Magello</span>
                </div>
                <p class="mt-4 text-sm" style="color: var(--on-brand-muted);">Hangout Space<br>Coffee, Food &amp; Enjoy</p>
            </div>

            <div>
                <h3 class="footer-title">Jelajahi</h3>
                <ul class="mt-3 space-y-2 text-sm">
                    <li><a href="#menu">Menu</a></li>
                    <li><a href="#tentang">Tentang</a></li>
                    <li><a href="#cara-memesan">Cara memesan</a></li>
                    <li><a href="{{ $trackUrl }}">Cek Pesanan</a></li>
                    <li><a href="{{ route('login') }}">Login</a></li>
                </ul>
            </div>

            <div>
                <h3 class="footer-title">Jam buka</h3>
                <p class="mt-3 text-sm" style="color: var(--on-brand-muted);">
                    Weekdays 09.00 - 23.00<br>
                    Weekends 09.00 - 00.00 WIB
                </p>
                <span class="pill mt-3 js-status" data-state="closed" style="background: transparent; color: var(--on-brand); border-color: var(--line-strong);">
                    <span class="dot"></span><span class="js-status-label">Memeriksa jam buka</span>
                </span>
            </div>

            <div>
                <h3 class="footer-title">Lokasi</h3>
                <p class="mt-3 text-sm" style="color: var(--on-brand-muted);">Pekanbaru, Riau, Indonesia</p>
                <!-- Tambahkan alamat lengkap dan tautan Google Maps di sini -->
            </div>
        </div>

        <div style="border-top: 1px solid rgba(251, 238, 220, .12);">
            <p class="max-w-6xl mx-auto px-4 py-5 text-center text-xs" style="color: var(--on-brand-muted);">
                &copy; 2026 Magello Hangout Space. All rights reserved.
            </p>
        </div>
    </footer>

    <!-- ===================== BAR PESAN (MOBILE) ===================== -->
    <div class="mobile-bar md:hidden">
        <button type="button" class="btn-ghost flex-1 js-open-cart" aria-label="Buka keranjang">
            Keranjang
            <span class="count-bubble js-cart-count" style="position: static;" hidden>0</span>
        </button>
        <a href="{{ route('order.select-table') }}" class="btn-primary flex-1">Pesan</a>
    </div>

    <!-- ===================== KERANJANG ===================== -->
    <div id="overlay" class="overlay"></div>
    <aside id="cart-drawer" class="drawer" role="dialog" aria-modal="true" aria-labelledby="cart-title" aria-hidden="true">
        <div class="flex items-center justify-between px-5 h-16 shrink-0" style="border-bottom: 1px solid var(--line);">
            <h2 id="cart-title" class="font-display text-2xl font-semibold text-heading">Keranjang</h2>
            <button type="button" id="cart-close" class="icon-btn" aria-label="Tutup keranjang">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-5">
            <div id="cart-empty" class="py-14 text-center">
                <p class="font-display text-2xl text-heading">Keranjang masih kosong</p>
                <p class="text-muted mt-1 text-sm">Tambahkan menu favorit Anda untuk mulai memesan.</p>
                <a href="#menu" id="cart-to-menu" class="btn-ghost mt-6">Lihat menu</a>
            </div>
            <div id="cart-list" hidden></div>
        </div>

        <div id="cart-footer" class="px-5 py-5 shrink-0" style="border-top: 1px solid var(--line);" hidden>
            <div class="flex items-center justify-between mb-4">
                <span class="text-muted">Total</span>
                <span class="font-display text-2xl font-semibold text-heading js-cart-total">Rp 0</span>
            </div>
            <a href="{{ route('order.select-table') }}" class="btn-primary btn-lg w-full">
                {{ $tableNo !== '' ? 'Lanjut memesan' : 'Lanjut pilih meja' }}
            </a>
            <button type="button" id="cart-clear" class="w-full mt-3 text-sm text-muted underline underline-offset-4">Kosongkan keranjang</button>
        </div>
    </aside>

    <div id="toast" class="toast" role="status" aria-live="polite"></div>

</body>
</html>