@extends('layouts.customer')

@section('title', 'Scan QR Meja - Cafe Magello')

@section('content')
    <style>
        /* ---------- Palet: Light (default) ---------- */
        :root {
            --scan-bg: #FDF8F2;          /* Warm Cream */
            --scan-frame: #FFFFFF;       /* Pure White */
            --scan-edge: #4A2E2B;        /* Deep Coffee Brown */
            --scan-text: #2B1B17;        /* Dark Espresso Brown */
            --scan-muted: #6E5A53;
            --scan-btn: #4A2E2B;
            --scan-btn-text: #FDF8F2;
            --scan-btn-edge: #4A2E2B;
            --scan-accent: #F97316;      /* oranye brand Magello: sudut bingkai + garis scan */
            --scan-ok: #2F7D4F;
            --scan-error-bg: #FEF2F2;
            --scan-error-text: #B91C1C;
            --scan-error-edge: #FECACA;
        }

        /* ---------- Palet: Dark ----------
           Aktif mengikuti pengaturan HP/OS, atau class "dark" di <html>.
           Kalau layout Anda belum mendukung dark mode, hapus blok @media di bawah ini. */
        @media (prefers-color-scheme: dark) {
            :root {
                --scan-bg: #1A1412;      /* Dark Mocha */
                --scan-frame: #2A1E1B;   /* Dark Chocolate */
                --scan-edge: #E6C594;    /* Warm Gold */
                --scan-text: #E6C594;
                --scan-muted: #B79F7C;
                --scan-btn: #2A1E1B;
                --scan-btn-text: #E6C594;
                --scan-btn-edge: rgba(230, 197, 148, .35);
                --scan-accent: #E6C594;
                --scan-ok: #8FD19E;
                --scan-error-bg: #2E1716;
                --scan-error-text: #FCA5A5;
                --scan-error-edge: #5B2724;
            }
        }
        html.dark {
            --scan-bg: #1A1412;
            --scan-frame: #2A1E1B;
            --scan-edge: #E6C594;
            --scan-text: #E6C594;
            --scan-muted: #B79F7C;
            --scan-btn: #2A1E1B;
            --scan-btn-text: #E6C594;
            --scan-btn-edge: rgba(230, 197, 148, .35);
            --scan-accent: #E6C594;
            --scan-ok: #8FD19E;
            --scan-error-bg: #2E1716;
            --scan-error-text: #FCA5A5;
            --scan-error-edge: #5B2724;
        }

        /* Background seluruh halaman mengikuti palet scan */
        body:has(.scan-page) {
            background: var(--scan-bg) !important;
        }

        .scan-page {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2.5rem 1.25rem 3rem;
            color: var(--scan-text);
            text-align: center;
        }

        .scan-title {
            margin: 0;
            font-size: 1.875rem;
            line-height: 1.15;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .scan-sub {
            margin: .625rem 0 0;
            max-width: 26rem;
            font-size: 1rem;
            line-height: 1.5;
            color: var(--scan-muted);
        }

        .scan-alert {
            margin-top: 1.25rem;
            width: min(100%, 22.5rem);
            padding: .75rem 1rem;
            border-radius: .875rem;
            border: 1px solid var(--scan-error-edge);
            background: var(--scan-error-bg);
            color: var(--scan-error-text);
            font-size: .875rem;
            text-align: left;
        }

        /* ---------- Viewfinder ---------- */
        .scan-frame {
            position: relative;
            margin-top: 1.75rem;
            width: min(100%, 22.5rem);
            aspect-ratio: 1 / 1;
            overflow: hidden;
            border-radius: 1.75rem;
            background: var(--scan-frame);
            border: 2px solid var(--scan-edge);
        }

        /* Reset gaya bawaan html5-qrcode: tanpa border, padding, atau overlay gelap */
        #qr-reader {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            padding: 0 !important;
            border: 0 !important;
            background: transparent;
        }
        #qr-reader video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover;
            display: block;
        }
        #qr-reader__scan_region,
        #qr-reader__scan_region img { width: 100%; height: 100%; min-height: 0 !important; }
        #qr-reader__dashboard { display: none !important; }

        /* Sudut bingkai */
        .scan-corner {
            position: absolute;
            width: 2.25rem;
            height: 2.25rem;
            border: 0 solid var(--scan-accent);
            pointer-events: none;
            filter: drop-shadow(0 0 2px rgba(0, 0, 0, .35));
            transition: border-color .2s ease;
        }
        .scan-corner--tl { top: .875rem; left: .875rem; border-top-width: 4px; border-left-width: 4px; border-top-left-radius: 1rem; }
        .scan-corner--tr { top: .875rem; right: .875rem; border-top-width: 4px; border-right-width: 4px; border-top-right-radius: 1rem; }
        .scan-corner--bl { bottom: .875rem; left: .875rem; border-bottom-width: 4px; border-left-width: 4px; border-bottom-left-radius: 1rem; }
        .scan-corner--br { bottom: .875rem; right: .875rem; border-bottom-width: 4px; border-right-width: 4px; border-bottom-right-radius: 1rem; }

        /* Garis scan: satu-satunya gerakan di halaman ini */
        .scan-line {
            position: absolute;
            left: 1.25rem;
            right: 1.25rem;
            top: 8%;
            height: 2px;
            border-radius: 2px;
            background: linear-gradient(90deg, transparent, var(--scan-accent) 20%, var(--scan-accent) 80%, transparent);
            box-shadow: 0 0 10px var(--scan-accent);
            opacity: 0;
            pointer-events: none;
        }
        .scan-frame[data-state="scanning"] .scan-line {
            opacity: .9;
            animation: scan-sweep 2.4s ease-in-out infinite;
        }
        @keyframes scan-sweep {
            0%, 100% { top: 8%; }
            50% { top: 92%; }
        }

        .scan-frame[data-state="success"] .scan-corner { border-color: var(--scan-ok); }

        /* Overlay error / sukses */
        .scan-overlay {
            position: absolute;
            inset: 0;
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: .75rem;
            padding: 1.5rem;
            background: var(--scan-frame);
            color: var(--scan-text);
        }
        .scan-frame[data-state="error"] .scan-overlay--error,
        .scan-frame[data-state="success"] .scan-overlay--ok { display: flex; }
        .scan-overlay svg { width: 2.5rem; height: 2.5rem; }
        .scan-overlay--ok svg { color: var(--scan-ok); }
        .scan-overlay strong { font-size: 1.125rem; }
        .scan-overlay p { margin: 0; font-size: .9rem; line-height: 1.45; color: var(--scan-muted); max-width: 16rem; }

        /* ---------- Status & tombol ---------- */
        .scan-status {
            margin: 1.25rem 0 0;
            min-height: 1.5rem;
            font-size: .95rem;
            font-weight: 500;
        }
        .scan-hint {
            margin: .25rem 0 0;
            font-size: .85rem;
            color: var(--scan-muted);
        }

        .scan-actions {
            display: flex;
            gap: .75rem;
            margin-top: 1.25rem;
        }
        .scan-btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .7rem 1.125rem;
            border-radius: 999px;
            border: 1px solid var(--scan-btn-edge);
            background: var(--scan-btn);
            color: var(--scan-btn-text);
            font: inherit;
            font-size: .9rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform .15s ease, opacity .15s ease;
        }
        .scan-btn:hover { opacity: .92; }
        .scan-btn:active { transform: scale(.97); }
        .scan-btn[hidden] { display: none; }
        .scan-btn[aria-pressed="true"] { background: var(--scan-accent); color: #2B1B17; border-color: var(--scan-accent); }
        .scan-btn svg { width: 1.125rem; height: 1.125rem; }
        .scan-btn:focus-visible { outline: 3px solid var(--scan-accent); outline-offset: 3px; }

        @media (prefers-reduced-motion: reduce) {
            .scan-frame[data-state="scanning"] .scan-line { animation: none; top: 50%; }
            .scan-btn { transition: none; }
        }
    </style>

    <div class="scan-page">
        <h1 class="scan-title">Scan QR meja</h1>
        <p class="scan-sub">Arahkan kamera ke QR Code yang ada di meja Anda. Meja akan terpilih otomatis.</p>

        @if(session('error'))
            <div class="scan-alert" role="alert">{{ session('error') }}</div>
        @endif

        <div class="scan-frame" id="scan-frame" data-state="starting">
            <div id="qr-reader"></div>

            <span class="scan-corner scan-corner--tl"></span>
            <span class="scan-corner scan-corner--tr"></span>
            <span class="scan-corner scan-corner--bl"></span>
            <span class="scan-corner scan-corner--br"></span>
            <div class="scan-line"></div>

            {{-- Kamera gagal dibuka --}}
            <div class="scan-overlay scan-overlay--error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 3l18 18"/><path d="M9.5 5h5l1.5 2H19a2 2 0 0 1 2 2v7.5"/><path d="M5 7a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12"/><path d="M10.1 10.2a3 3 0 0 0 3.7 3.7"/>
                </svg>
                <strong>Kamera belum bisa dipakai</strong>
                <p id="scan-error-text">Izinkan akses kamera di browser Anda, lalu coba lagi.</p>
                <button type="button" class="scan-btn" id="scan-retry">Coba lagi</button>
            </div>

            {{-- QR berhasil terbaca --}}
            <div class="scan-overlay scan-overlay--ok" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9"/><path d="M8 12.5l2.8 2.8L16 9.5"/>
                </svg>
                <strong id="scan-ok-text">Meja terdeteksi</strong>
                <p>Membuka menu…</p>
            </div>
        </div>

        <p class="scan-status" id="scan-status" role="status" aria-live="polite">Menyiapkan kamera…</p>
        <p class="scan-hint">Pastikan QR Code berada di dalam bingkai dan cukup terang.</p>

        <div class="scan-actions">
            <button type="button" class="scan-btn" id="scan-torch" aria-pressed="false" hidden>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13 2L4 14h7l-1 8 9-12h-7l1-8z"/></svg>
                Flash
            </button>
            <button type="button" class="scan-btn" id="scan-switch">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 7h-9a4 4 0 0 0-4 4v1"/><path d="M17 4l3 3-3 3"/><path d="M4 17h9a4 4 0 0 0 4-4v-1"/><path d="M7 20l-3-3 3-3"/></svg>
                Ganti kamera
            </button>
        </div>

        {{-- Form tersembunyi: dikirim otomatis setelah QR terbaca --}}
        <form id="table-form" action="{{ route('order.set-table') }}" method="POST" hidden>
            @csrf
            <input type="hidden" name="table_number" id="table_number">
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        (function () {
            var frame = document.getElementById('scan-frame');
            var statusEl = document.getElementById('scan-status');
            var errorText = document.getElementById('scan-error-text');
            var okText = document.getElementById('scan-ok-text');
            var retryBtn = document.getElementById('scan-retry');
            var torchBtn = document.getElementById('scan-torch');
            var switchBtn = document.getElementById('scan-switch');
            var form = document.getElementById('table-form');
            var tableInput = document.getElementById('table_number');

            var scanner = null;
            var facing = 'environment';
            var torchFeature = null;
            var torchOn = false;
            var busy = false;
            var handled = false;
            var invalidTimer = null;
            var navTimer = null;

            function setState(state, message) {
                frame.dataset.state = state;
                if (message) statusEl.textContent = message;
            }

            /* Ambil nomor meja dari isi QR. Mendukung:
               - angka langsung            -> "5"
               - URL dengan query           -> ?table=5 / ?meja=5 / ?table_number=5
               - URL dengan path            -> /table/5 atau /meja/5
               - teks                       -> "Meja 5"
               Sesuaikan jika format QR Anda berbeda. */
            function extractTable(text) {
                text = String(text || '').trim();
                if (/^\d{1,3}$/.test(text)) return text;

                try {
                    var url = new URL(text, window.location.origin);
                    var keys = ['table', 'table_number', 'meja', 'no_meja'];
                    for (var i = 0; i < keys.length; i++) {
                        var v = url.searchParams.get(keys[i]);
                        if (v) return v;
                    }
                    var m = url.pathname.match(/(?:table|meja)\/([A-Za-z0-9-]+)/i);
                    if (m) return m[1];
                } catch (e) { /* bukan URL */ }

                var t = text.match(/(?:meja|table)\s*[-_:#]?\s*(\d+)/i);
                return t ? t[1] : null;
            }

            /* Tentukan tujuan dari isi QR:
               - link berisi /order, /menu, /meja atau /table -> langsung dibuka.
                 Yang dipakai hanya path + query-nya, domainnya diganti domain yang sedang
                 dibuka (jadi tetap jalan walau QR dicetak dari localhost / domain lain).
               - selain itu -> ambil nomor meja lalu kirim ke order.set-table. */
            function resolveDestination(text) {
                try {
                    var url = new URL(text);
                    if (/^https?:$/.test(url.protocol) && /\/(order|menu|meja|table)(\/|$)/i.test(url.pathname)) {
                        return {
                            type: 'url',
                            href: window.location.origin + url.pathname + url.search + url.hash,
                            table: extractTable(text)
                        };
                    }
                } catch (e) { /* bukan URL */ }

                var table = extractTable(text);
                return table ? { type: 'table', table: table } : null;
            }

            function onScanSuccess(decodedText) {
                if (handled) return;
                var text = String(decodedText || '').trim();
                var dest = resolveDestination(text);

                if (!dest) {
                    setState('scanning', 'QR Code ini bukan QR meja Magello. Coba QR yang lain.');
                    clearTimeout(invalidTimer);
                    invalidTimer = setTimeout(function () {
                        if (!handled) setState('scanning', 'Arahkan kamera ke QR Code di meja.');
                    }, 2500);
                    return;
                }

                handled = true;
                if (navigator.vibrate) navigator.vibrate(60);
                var label = dest.table ? 'Meja ' + dest.table + ' terdeteksi' : 'QR Code terdeteksi';
                okText.textContent = label;
                setState('success', label);

                // Pindah halaman tanpa menunggu kamera berhenti (stop() bisa macet di beberapa browser)
                setTimeout(function () {
                    try {
                        if (dest.type === 'url') {
                            window.location.assign(dest.href);
                        } else {
                            tableInput.value = dest.table;
                            form.submit();
                        }
                    } catch (e) {
                        showNavFailure(text);
                    }
                }, 250);
                try { scanner.stop().catch(function () {}); } catch (e) {}

                // Jaring pengaman: jika 5 detik masih di halaman ini, tampilkan isi QR agar mudah dilacak
                navTimer = setTimeout(function () { showNavFailure(text); }, 5000);
            }

            function showNavFailure(text) {
                errorText.textContent = 'Halaman tidak berpindah. Isi QR: ' + text.slice(0, 120);
                setState('error', 'Gagal membuka menu.');
            }

            function describeError(err) {
                var name = err && err.name ? err.name : '';
                var msg = String(err || '');

                if (!window.isSecureContext) return 'Kamera hanya bisa dipakai lewat koneksi aman (HTTPS).';
                if (name === 'NotAllowedError' || /permission|denied/i.test(msg)) return 'Akses kamera ditolak. Izinkan kamera di pengaturan browser, lalu coba lagi.';
                if (name === 'NotFoundError' || /no camera|not found/i.test(msg)) return 'Kamera tidak ditemukan di perangkat ini.';
                if (name === 'NotReadableError') return 'Kamera sedang dipakai aplikasi lain. Tutup aplikasi itu, lalu coba lagi.';
                return 'Terjadi kendala saat membuka kamera. Coba lagi.';
            }

            function setupTorch() {
                torchBtn.hidden = true;
                torchOn = false;
                torchBtn.setAttribute('aria-pressed', 'false');
                try {
                    torchFeature = scanner.getRunningTrackCameraCapabilities().torchFeature();
                    if (torchFeature.isSupported()) torchBtn.hidden = false;
                } catch (e) { torchFeature = null; }
            }

            function start() {
                if (busy) return Promise.resolve();
                busy = true;
                handled = false;
                clearTimeout(navTimer);
                setState('starting', 'Menyiapkan kamera…');

                if (typeof Html5Qrcode === 'undefined') {
                    errorText.textContent = 'Pemindai gagal dimuat. Periksa koneksi internet, lalu muat ulang halaman.';
                    setState('error', 'Pemindai gagal dimuat.');
                    busy = false;
                    return Promise.resolve();
                }

                if (!scanner) {
                    scanner = new Html5Qrcode('qr-reader', {
                        verbose: false,
                        formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE]
                    });
                }

                // Tanpa "qrbox" -> seluruh frame dipindai dan tidak ada overlay gelap dari library.
                return scanner
                    .start({ facingMode: facing }, { fps: 12, aspectRatio: 1 }, onScanSuccess, function () {})
                    .then(function () {
                        setState('scanning', 'Arahkan kamera ke QR Code di meja.');
                        setupTorch();
                    })
                    .catch(function (err) {
                        errorText.textContent = describeError(err);
                        setState('error', 'Kamera belum bisa dipakai.');
                    })
                    .then(function () { busy = false; });
            }

            function stop() {
                if (scanner && scanner.isScanning) {
                    return scanner.stop().catch(function () {});
                }
                return Promise.resolve();
            }

            retryBtn.addEventListener('click', start);

            switchBtn.addEventListener('click', function () {
                if (busy) return;
                facing = facing === 'environment' ? 'user' : 'environment';
                stop().then(start);
            });

            torchBtn.addEventListener('click', function () {
                if (!torchFeature) return;
                torchOn = !torchOn;
                torchFeature.apply(torchOn)
                    .then(function () { torchBtn.setAttribute('aria-pressed', String(torchOn)); })
                    .catch(function () { torchOn = !torchOn; });
            });

            // Matikan kamera saat halaman ditutup / berpindah tab
            window.addEventListener('pagehide', stop);
            document.addEventListener('visibilitychange', function () {
                if (document.hidden) {
                    stop();
                } else if (!handled && frame.dataset.state !== 'error') {
                    start();
                }
            });

            start();
        })();
    </script>
@endsection