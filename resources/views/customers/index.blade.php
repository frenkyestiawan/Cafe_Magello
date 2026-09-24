<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pesan Menu - Magello Cafe</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">

    {{-- Terapkan tema sebelum render agar tidak berkedip. Default: gelap (sesuai mockup). --}}
    <script>
        try {
            var t = localStorage.getItem('magello-theme');
            document.documentElement.setAttribute('data-theme', t === 'light' ? 'light' : 'dark');
        } catch (e) {}
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
@vite(['resources/css/customers.css', 'resources/css/order.css', 'resources/js/customers.js'])
</head>
<body>

{{-- Sprite ikon (dipakai ulang, tidak ada duplikasi SVG) --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <symbol id="i-bag" viewBox="0 0 24 24"><path d="M6 8h12l-1 12H7L6 8Z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></symbol>
    <symbol id="i-search" viewBox="0 0 24 24"><circle cx="11" cy="11" r="6"/><path d="m20 20-4-4"/></symbol>
    <symbol id="i-arrow" viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></symbol>
    <symbol id="i-sun" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M12 3v2M12 19v2M3 12h2M19 12h2M5.6 5.6 7 7M17 17l1.4 1.4M5.6 18.4 7 17M17 7l1.4-1.4"/></symbol>
    <symbol id="i-moon" viewBox="0 0 24 24"><path d="M20 14.5A8 8 0 0 1 9.5 4 8 8 0 1 0 20 14.5Z"/></symbol>
    <symbol id="i-info" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01"/></symbol>
    <symbol id="i-table" viewBox="0 0 24 24"><path d="M3 9h18M5 9v10M19 9v10M3 5h18v4H3z"/></symbol>
    <symbol id="i-utensils" viewBox="0 0 24 24"><path d="M7 3v8M4 3v5a3 3 0 0 0 6 0V3M7 11v10M17 21V3c-2 1-3 4-3 8h3"/></symbol>
    <symbol id="i-x" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6 6 18"/></symbol>
    <symbol id="i-plus" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></symbol>
    <symbol id="i-minus" viewBox="0 0 24 24"><path d="M5 12h14"/></symbol>
</svg>

<div id="toast-region" class="toast-region" aria-live="polite"></div>

{{-- ============ Navbar ============ --}}
<header class="navbar">
    <div class="container navbar-inner">
        <a href="{{ route('home') }}" class="brand" aria-label="Magello, ke halaman utama">
            <span class="brand-mark"><svg class="icon"><use href="#i-utensils"/></svg></span>
            Magello
        </a>

        <nav class="nav-links" aria-label="Navigasi utama">
            <a href="{{ route('home') }}">Home</a>
            <a href="#favorit">Menu Favorit</a>
            <a href="#menu">Semua Menu</a>
        </nav>

        <div class="nav-actions">
            <button type="button" class="theme-switch" role="switch" aria-checked="true" aria-label="Mode gelap" data-theme-toggle>
                <span class="knob">
                    <svg class="icon i-moon"><use href="#i-moon"/></svg>
                    <svg class="icon i-sun"><use href="#i-sun"/></svg>
                </span>
            </button>
            <a href="{{ route('order.select-table') }}" class="btn">{{ $tableId ? 'Ganti Meja' : 'Pilih Meja' }}</a>
        </div>
    </div>
</header>

<main class="container page">
    {{-- ============ Hero & info ============ --}}
    <section class="hero">
        <span class="eyebrow">Magello Hangout Space</span>
        <h1>Pesan dari meja</h1>
        <p>Pilih menu favorit Anda atau lihat seluruh menu yang tersedia.</p>
    </section>

    <div class="stack">
        <div class="notice">
            <svg class="icon"><use href="#i-info"/></svg>
            <div>
                <strong>Informasi Pemesanan</strong>
                <p>Pesanan Anda diproses setelah checkout. Estimasi penyajian 10–15 menit. Beberapa menu tersedia dalam beberapa ukuran dengan harga berbeda.</p>
            </div>
        </div>

        @if($tableId)
            <div class="notice is-ok" role="status">
                <svg class="icon"><use href="#i-table"/></svg>
                <div>
                    <strong>Meja {{ $tableLabel }} dipilih</strong>
                    <p>Pesanan dine-in. Salah meja? <a class="link" href="{{ route('order.select-table') }}">Ganti meja</a></p>
                </div>
            </div>
        @else
            <div class="notice is-warn" role="alert">
                <svg class="icon"><use href="#i-table"/></svg>
                <div>
                    <strong>Belum ada meja terpilih</strong>
                    <p><a class="link" href="{{ route('order.select-table') }}">Pilih meja</a> atau scan QR code di meja Anda sebelum checkout.</p>
                </div>
            </div>
        @endif
    </div>

    {{-- ============ Menu favorit ============ --}}
    @if($favorites->isNotEmpty())
    <section id="favorit" class="section" aria-labelledby="favorit-title">
        <div class="section-head">
            <div class="ornament"><svg class="icon"><use href="#i-utensils"/></svg></div>
            <h2 id="favorit-title">Menu Favorit</h2>
            <p>Pilihan yang paling sering dipesan oleh pelanggan.</p>
        </div>

        <div class="fav-grid">
            @foreach($favorites as $menu)
                @php
                    $variants = $menu->available_variants;
                    $startPrice = $variants->isNotEmpty() ? $variants->first()->price : $menu->price;
                    $img = $menu->formatted_image;
                @endphp
                <article class="fav-card">
                    @if(! empty($menu->is_best_seller))<span class="badge">Best Seller</span>@endif
                    <div class="fav-media">
                        <div class="oval">
                            @if($img)
                                <img src="{{ $img }}" alt="{{ $menu->name }}" loading="lazy" onerror="this.remove()">
                            @else
                                <svg class="icon"><use href="#i-utensils"/></svg>
                            @endif
                        </div>
                    </div>
                    <span class="eyebrow">{{ $categoryNames[$menu->category_id] ?? 'Menu' }}</span>
                    <h3>{{ $menu->name }}</h3>
                    <p class="desc">{{ \Illuminate\Support\Str::limit($menu->description, 48) }}</p>
                    <div class="fav-foot">
                        <div>
                            <div class="price">{{ $variants->count() > 1 ? 'Mulai ' : '' }}Rp {{ number_format($startPrice, 0, ',', '.') }}</div>
                            <div class="eta">Sekitar {{ (int) ($menu->prep_time ?? 10) }} menit</div>
                        </div>
                        <button type="button" class="icon-btn" data-jump="{{ $menu->id }}" aria-label="Lihat {{ $menu->name }} di daftar menu" title="Lihat di daftar menu">
                            <svg class="icon"><use href="#i-arrow"/></svg>
                        </button>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ============ Semua menu + keranjang ============ --}}
    <section id="menu" class="section" aria-labelledby="menu-title">
        <div class="list-head">
            <div>
                <span class="eyebrow">Daftar Lengkap</span>
                <h2 id="menu-title">Semua Menu</h2>
            </div>
            <div class="filters">
                <label class="field">
                    <span class="sr-only">Filter kategori</span>
                    <select id="filter-category">
                        <option value="all">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="field">
                    <svg class="icon"><use href="#i-search"/></svg>
                    <span class="sr-only">Cari menu</span>
                    <input id="filter-search" type="search" placeholder="Cari menu..." autocomplete="off">
                </label>
            </div>
        </div>

        <div class="order-layout">
            <div>
                <p class="result-count" id="result-count" aria-live="polite"></p>

                <div class="menu-list" id="menu-list">
                    @foreach($menus as $menu)
                        @php
                            $variants = $menu->available_variants;
                            $first = $variants->first();
                            $img = $menu->formatted_image;
                        @endphp
                        <article class="menu-row"
                                 data-menu-row
                                 data-id="{{ $menu->id }}"
                                 data-name="{{ $menu->name }}"
                                 data-category="{{ $menu->category_id }}"
                                 data-base-price="{{ $menu->price }}"
                                 data-search="{{ mb_strtolower($menu->name . ' ' . $menu->description) }}">
                            <div class="thumb">
                                @if($img)
                                    <img src="{{ $img }}" alt="" loading="lazy" onerror="this.remove()">
                                @else
                                    <svg class="icon"><use href="#i-utensils"/></svg>
                                @endif
                            </div>

                            <div class="row-body">
                                <span class="eyebrow">{{ $categoryNames[$menu->category_id] ?? 'Menu' }}</span>
                                <h3>{{ $menu->name }}</h3>
                                <p class="desc">{{ $menu->description ?: 'Tidak ada deskripsi' }}</p>

                                @if($variants->isNotEmpty())
                                    <div class="variants" role="radiogroup" aria-label="Ukuran {{ $menu->name }}">
                                        @foreach($variants as $variant)
                                            <label>
                                                <input type="radio"
                                                       name="variant_{{ $menu->id }}"
                                                       value="{{ $variant->id }}"
                                                       data-name="{{ $variant->name }}"
                                                       data-price="{{ $variant->price }}"
                                                       {{ $loop->first ? 'checked' : '' }}>
                                                <span>{{ $variant->name }} <b>Rp {{ number_format($variant->price, 0, ',', '.') }}</b></span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="row-side">
                                <span class="price js-price">Rp {{ number_format($first ? $first->price : $menu->price, 0, ',', '.') }}</span>
                                <div class="stepper" role="group" aria-label="Jumlah {{ $menu->name }}">
                                    <button type="button" data-action="decrease" aria-label="Kurangi {{ $menu->name }}" disabled><svg class="icon"><use href="#i-minus"/></svg></button>
                                    <output class="js-qty">0</output>
                                    <button type="button" data-action="increase" aria-label="Tambah {{ $menu->name }}"><svg class="icon"><use href="#i-plus"/></svg></button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="empty" id="menu-empty" hidden>
                    <svg class="icon"><use href="#i-search"/></svg>
                    <strong>Menu tidak ditemukan</strong>
                    <p>Coba kata kunci lain atau pilih kategori yang berbeda.</p>
                    <button type="button" class="btn btn-ghost" id="reset-filter">Reset filter</button>
                </div>

                <div class="more"><button type="button" class="btn btn-ghost" id="show-more" hidden>Tampilkan lebih banyak</button></div>
            </div>

            {{-- Keranjang --}}
            <div class="cart-backdrop" id="cart-backdrop"></div>
            <aside class="card cart" id="cart" aria-label="Pesanan Anda">
                <div class="cart-head">
                    <div>
                        <span class="eyebrow">Pesanan Anda</span>
                        <h2>Keranjang</h2>
                    </div>
                    <button type="button" class="icon-btn" id="cart-close" aria-label="Tutup keranjang" style="display:none"><svg class="icon"><use href="#i-x"/></svg></button>
                </div>

                <div class="cart-body">
                    <div class="cart-empty" id="cart-empty">
                        <span class="bag"><svg class="icon"><use href="#i-bag"/></svg></span>
                        <strong>Keranjang masih kosong</strong>
                        <p>Pilih menu favorit Anda untuk mulai memesan.</p>
                    </div>
                    <div id="cart-list"></div>
                </div>

                <div class="cart-foot" id="cart-foot" hidden>
                    <div class="total-row"><span>Subtotal</span><span id="cart-subtotal">Rp 0</span></div>
                    <div class="total-row grand"><span>Total</span><span id="cart-total">Rp 0</span></div>

                    @if($tableId)
                        <a href="{{ route('order.checkout') }}" id="checkout" class="btn btn-block">Lanjut ke Pembayaran</a>
                    @else
                        <a href="{{ route('order.select-table') }}" class="btn btn-block">Pilih Meja Terlebih Dahulu</a>
                    @endif
                    <button type="button" class="link-btn" id="clear-cart">Kosongkan keranjang</button>
                </div>
            </aside>
        </div>
    </section>
</main>

{{-- Bar keranjang untuk layar kecil --}}
<button type="button" class="cart-bar" id="cart-bar" hidden>
    <span><small id="cart-bar-count">0 item</small>Lihat keranjang</span>
    <span id="cart-bar-total">Rp 0</span>
</button>

<script>
(function () {
    'use strict';

    var CART_KEY = 'magello-cart';
    var MAX_QTY = 99;
    var PAGE_SIZE = 12;
    var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    var syncUrl = @json(route('order.sync-cart'));

    var rows = Array.prototype.slice.call(document.querySelectorAll('[data-menu-row]'));
    var $ = function (id) { return document.getElementById(id); };
    var cartEl = $('cart');

    function rupiah(v) { return 'Rp ' + Number(v || 0).toLocaleString('id-ID'); }
    function h(tag, cls, text) {
        var el = document.createElement(tag);
        if (cls) el.className = cls;
        if (text != null) el.textContent = text;
        return el;
    }
    function icon(name) {
        var wrap = document.createElement('span');
        wrap.innerHTML = '<svg class="icon"><use href="#i-' + name + '"/></svg>';
        return wrap.firstChild;
    }

    /* ---------- Data keranjang ----------
       Kunci item = "<menu_id>:<variant_id|base>" supaya menu yang sama dengan
       ukuran berbeda menjadi baris terpisah, dan variant tidak hilang. */
    function keyOf(id, variantId) { return String(id) + ':' + String(variantId || 'base'); }

    function normalize(raw) {
        var out = {};
        Object.keys(raw || {}).forEach(function (k) {
            var it = raw[k];
            if (!it || !it.name) return;
            var qty = Number(it.qty || it.quantity || 0);
            if (qty <= 0) return;
            var id = String(it.id || it.menu_id || k.split(':')[0]);
            var variantId = it.variant_id || null;
            var key = keyOf(id, variantId);
            out[key] = {
                key: key, id: id, name: it.name, price: Number(it.price || 0),
                qty: Math.min(qty, MAX_QTY), variant_id: variantId, variant_name: it.variant_name || null
            };
        });
        return out;
    }

    function load() {
        try { return normalize(JSON.parse(localStorage.getItem(CART_KEY) || '{}')); }
        catch (e) { return {}; }
    }

    var cart = load();

    function toServer(c) {
        var out = {};
        Object.keys(c).forEach(function (k) {
            var it = c[k];
            out[k] = {
                key: it.key, id: it.id, menu_id: it.id, name: it.name,
                display_name: it.variant_name ? it.name + ' - ' + it.variant_name : it.name,
                price: it.price, quantity: it.qty, qty: it.qty,
                variant_id: it.variant_id, variant_name: it.variant_name
            };
        });
        return out;
    }

    /* ---------- Sinkronisasi ke server ---------- */
    var syncTimer = null;

    function sync() {
        if (!csrf) return Promise.resolve();
        return fetch(syncUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: JSON.stringify({ cart: toServer(cart) })
        }).then(function (res) { if (!res.ok) throw new Error('sync failed'); });
    }

    function scheduleSync() {
        clearTimeout(syncTimer);
        syncTimer = setTimeout(function () {
            sync().catch(function () {
                MagelloUI.toast('Keranjang belum tersimpan ke server. Periksa koneksi Anda.', 'error');
            });
        }, 300);
    }

    function save() {
        try { localStorage.setItem(CART_KEY, JSON.stringify(cart)); } catch (e) {}
        render();
        scheduleSync();
    }

    /* ---------- Baris menu ---------- */
    function selectionOf(row) {
        var checked = row.querySelector('input[type="radio"]:checked');
        var id = row.dataset.id;
        var variantId = checked ? checked.value : null;
        return {
            key: keyOf(id, variantId), id: id, name: row.dataset.name,
            price: Number(checked ? checked.dataset.price : row.dataset.basePrice),
            variant_id: variantId, variant_name: checked ? checked.dataset.name : null
        };
    }

    function refreshRow(row) {
        var sel = selectionOf(row);
        var qty = cart[sel.key] ? cart[sel.key].qty : 0;
        row.querySelector('.js-price').textContent = rupiah(sel.price);
        row.querySelector('.js-qty').textContent = qty;
        row.querySelector('[data-action="decrease"]').disabled = qty <= 0;
        row.querySelector('[data-action="increase"]').disabled = qty >= MAX_QTY;
        var any = Object.keys(cart).some(function (k) { return cart[k].id === row.dataset.id; });
        row.classList.toggle('is-selected', any);
    }

    function change(sel, delta) {
        var item = cart[sel.key] || {
            key: sel.key, id: sel.id, name: sel.name, price: sel.price, qty: 0,
            variant_id: sel.variant_id, variant_name: sel.variant_name
        };
        item.qty = Math.min(MAX_QTY, Math.max(0, item.qty + delta));
        if (item.qty <= 0) delete cart[sel.key]; else cart[sel.key] = item;
        save();
    }

    /* ---------- Render keranjang ---------- */
    function render() {
        var items = Object.keys(cart).map(function (k) { return cart[k]; });
        var list = $('cart-list');
        list.replaceChildren();

        var subtotal = 0, count = 0;
        items.forEach(function (it) {
            subtotal += it.qty * it.price;
            count += it.qty;

            var label = it.variant_name ? it.name + ' - ' + it.variant_name : it.name;
            var wrap = h('div', 'cart-item');

            var top = h('div', 'cart-item-row');
            var info = h('div');
            info.append(h('h4', null, label), h('p', 'unit', rupiah(it.price)));
            var remove = h('button', 'remove');
            remove.type = 'button';
            remove.dataset.action = 'remove';
            remove.dataset.key = it.key;
            remove.setAttribute('aria-label', 'Hapus ' + label);
            remove.title = 'Hapus';
            remove.appendChild(icon('x'));
            top.append(info, remove);

            var bottom = h('div', 'cart-item-row');
            var stepper = h('div', 'stepper');
            stepper.setAttribute('role', 'group');
            stepper.setAttribute('aria-label', 'Jumlah ' + label);
            var minus = h('button'); minus.type = 'button'; minus.dataset.action = 'cart-decrease'; minus.dataset.key = it.key;
            minus.setAttribute('aria-label', 'Kurangi ' + label); minus.appendChild(icon('minus'));
            var plus = h('button'); plus.type = 'button'; plus.dataset.action = 'cart-increase'; plus.dataset.key = it.key;
            plus.setAttribute('aria-label', 'Tambah ' + label); plus.appendChild(icon('plus'));
            plus.disabled = it.qty >= MAX_QTY;
            stepper.append(minus, h('output', null, String(it.qty)), plus);
            bottom.append(stepper, h('span', 'line', rupiah(it.qty * it.price)));

            wrap.append(top, bottom);
            list.appendChild(wrap);
        });

        var has = items.length > 0;
        $('cart-empty').hidden = has;
        $('cart-foot').hidden = !has;
        $('cart-subtotal').textContent = rupiah(subtotal);
        $('cart-total').textContent = rupiah(subtotal);
        $('cart-bar').hidden = !has;
        $('cart-bar-count').textContent = count + ' item';
        $('cart-bar-total').textContent = rupiah(subtotal);

        rows.forEach(refreshRow);
    }

    /* ---------- Event: klik ---------- */
    document.addEventListener('click', function (event) {
        var jump = event.target.closest('[data-jump]');
        if (jump) { jumpTo(jump.dataset.jump); return; }

        var btn = event.target.closest('[data-action]');
        if (!btn) return;
        var action = btn.dataset.action;

        var row = btn.closest('[data-menu-row]');
        if (row && (action === 'increase' || action === 'decrease')) {
            change(selectionOf(row), action === 'increase' ? 1 : -1);
            return;
        }

        var item = cart[btn.dataset.key];
        if (!item) return;
        if (action === 'cart-increase') change(item, 1);
        if (action === 'cart-decrease') change(item, -1);
        if (action === 'remove') {
            var label = item.variant_name ? item.name + ' - ' + item.variant_name : item.name;
            change(item, -item.qty);
            MagelloUI.toast(label + ' dihapus dari keranjang.');
        }
    });

    document.addEventListener('change', function (event) {
        if (event.target.matches('[data-menu-row] input[type="radio"]')) {
            refreshRow(event.target.closest('[data-menu-row]'));
        }
    });

    $('clear-cart').addEventListener('click', function () {
        MagelloUI.confirm({
            title: 'Kosongkan keranjang?',
            message: 'Semua menu yang sudah Anda pilih akan dihapus dari pesanan.',
            confirmText: 'Kosongkan', danger: true
        }).then(function (ok) {
            if (!ok) return;
            cart = {};
            save();
            MagelloUI.toast('Keranjang dikosongkan.', 'success');
        });
    });

    /* Pastikan keranjang tersinkron ke server sebelum pindah ke checkout. */
    var checkout = $('checkout');
    if (checkout) {
        checkout.addEventListener('click', function (event) {
            event.preventDefault();
            if (checkout.classList.contains('is-loading')) return;
            clearTimeout(syncTimer);
            checkout.classList.add('is-loading');
            checkout.setAttribute('aria-disabled', 'true');
            sync().then(function () {
                window.location.href = checkout.href;
            }).catch(function () {
                checkout.classList.remove('is-loading');
                checkout.removeAttribute('aria-disabled');
                MagelloUI.toast('Gagal melanjutkan ke pembayaran. Coba lagi.', 'error');
            });
        });
    }

    /* ---------- Filter, pencarian, tampilkan lebih banyak ---------- */
    var filterCat = $('filter-category'), filterSearch = $('filter-search');
    var shown = PAGE_SIZE;

    function applyFilter() {
        var cat = filterCat.value, q = filterSearch.value.trim().toLowerCase(), matched = 0;
        rows.forEach(function (row) {
            var ok = (cat === 'all' || row.dataset.category === cat) && (!q || row.dataset.search.indexOf(q) !== -1);
            if (ok) { matched++; row.hidden = matched > shown; } else { row.hidden = true; }
        });
        $('menu-empty').hidden = matched > 0;
        $('show-more').hidden = matched <= shown;
        $('result-count').textContent = matched > 0 ? matched + ' menu ditemukan' : '';
    }

    filterCat.addEventListener('change', function () { shown = PAGE_SIZE; applyFilter(); });
    filterSearch.addEventListener('input', function () { shown = PAGE_SIZE; applyFilter(); });
    $('show-more').addEventListener('click', function () { shown += PAGE_SIZE; applyFilter(); });
    $('reset-filter').addEventListener('click', function () {
        filterCat.value = 'all'; filterSearch.value = ''; shown = PAGE_SIZE; applyFilter();
    });

    function jumpTo(menuId) {
        var row = document.querySelector('[data-menu-row][data-id="' + menuId + '"]');
        if (!row) return;
        if (row.hidden) { filterCat.value = 'all'; filterSearch.value = ''; shown = rows.length; applyFilter(); }
        row.scrollIntoView({ behavior: 'smooth', block: 'center' });
        row.classList.remove('is-flash');
        void row.offsetWidth;
        row.classList.add('is-flash');
    }

    /* ---------- Keranjang di layar kecil ---------- */
    var backdrop = $('cart-backdrop'), closeBtn = $('cart-close');
    var mq = window.matchMedia('(max-width: 900px)');
    function setCartOpen(open) {
        cartEl.classList.toggle('is-open', open);
        backdrop.classList.toggle('is-open', open);
        document.body.style.overflow = open ? 'hidden' : '';
    }
    function syncCartChrome() {
        closeBtn.style.display = mq.matches ? '' : 'none';
        if (!mq.matches) setCartOpen(false);
    }
    $('cart-bar').addEventListener('click', function () { setCartOpen(true); });
    closeBtn.addEventListener('click', function () { setCartOpen(false); });
    backdrop.addEventListener('click', function () { setCartOpen(false); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setCartOpen(false); });
    mq.addEventListener('change', syncCartChrome);

    /* Sinkron antar tab */
    window.addEventListener('storage', function (event) {
        if (event.key === CART_KEY) { cart = load(); render(); }
    });

    /* ---------- Init ---------- */
    syncCartChrome();
    applyFilter();
    render();
    if (Object.keys(cart).length) scheduleSync();

    /* Pesan flash dari server -> toast */
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success')) MagelloUI.toast(@json(session('success')), 'success'); @endif
        @if(session('error')) MagelloUI.toast(@json(session('error')), 'error'); @endif
        @if($errors->any()) MagelloUI.toast(@json($errors->first()), 'error'); @endif
    });
})();
</script>
</body>
</html>