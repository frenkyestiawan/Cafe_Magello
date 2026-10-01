/* Cafe Magello - Admin UI
   Satu-satunya script untuk area admin. Dimuat dari layout admin:
   <script src="{{ asset('js/admin.js') }}" defer></script>

   Hook berbasis atribut (tanpa JS inline di Blade):
   [data-theme-toggle]           tombol ganti tema
   [data-nav-toggle]             tombol burger navbar (mobile)
   [data-dropdown]               wadah dropdown; di dalamnya [data-dropdown-trigger] + [data-dropdown-menu]
   form[data-confirm]            tampilkan dialog konfirmasi sebelum submit (opsional: data-confirm-title, data-confirm-ok, data-confirm-danger)
   input[data-filter="#id"]      filter teks baris <tbody> pada tabel #id
   [data-autosubmit]             form / field yang otomatis submit saat berubah
   [data-clock] [data-weather]   widget jam & cuaca di hero dashboard
   #adm-toast-region[data-success|data-error]   flash message dari server

   API publik: window.AdminUI = { toast, confirm, setTheme }
   (alias window.MagelloUI disediakan agar view admin lama tetap jalan) */
(function () {
    'use strict';

    var THEME_KEY = 'magello-theme';
    var root = document.documentElement;

    /* ---------- Tema ---------- */
    function currentTheme() {
        return root.getAttribute('data-theme') === 'light' ? 'light' : 'dark';
    }

    function setTheme(theme) {
        root.setAttribute('data-theme', theme);
        try { localStorage.setItem(THEME_KEY, theme); } catch (e) { /* storage dinonaktifkan */ }
        document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
            btn.setAttribute('aria-checked', theme === 'dark' ? 'true' : 'false');
            btn.setAttribute('title', theme === 'dark' ? 'Ganti ke mode terang' : 'Ganti ke mode gelap');
        });
    }

    /* ---------- Toast ---------- */
    function toast(message, type, duration) {
        var region = document.getElementById('adm-toast-region');
        if (!region || !message) return;
        var el = document.createElement('div');
        el.className = 'adm-toast' + (type ? ' is-' + type : '');
        el.setAttribute('role', type === 'error' ? 'alert' : 'status');
        var text = document.createElement('span');
        text.textContent = message;
        var close = document.createElement('button');
        close.type = 'button';
        close.setAttribute('aria-label', 'Tutup notifikasi');
        close.textContent = '\u2715';
        close.addEventListener('click', function () { el.remove(); });
        el.append(text, close);
        region.appendChild(el);
        setTimeout(function () { el.remove(); }, duration || 4500);
    }

    /* ---------- Dialog konfirmasi (Promise<boolean>) ---------- */
    function confirmDialog(options) {
        options = options || {};
        return new Promise(function (resolve) {
            var dialog = document.createElement('dialog');
            dialog.className = 'adm-dialog';
            dialog.setAttribute('aria-labelledby', 'adm-confirm-title');

            var title = document.createElement('h3');
            title.id = 'adm-confirm-title';
            title.textContent = options.title || 'Lanjutkan?';
            var body = document.createElement('p');
            body.textContent = options.message || '';

            var actions = document.createElement('div');
            actions.className = 'adm-dialog-actions';
            var cancel = document.createElement('button');
            cancel.type = 'button';
            cancel.className = 'adm-btn adm-btn--ghost';
            cancel.textContent = options.cancelText || 'Batal';
            var ok = document.createElement('button');
            ok.type = 'button';
            ok.className = 'adm-btn' + (options.danger ? ' adm-btn--danger' : '');
            ok.textContent = options.confirmText || 'Ya, lanjutkan';
            actions.append(cancel, ok);

            dialog.append(title, body, actions);
            document.body.appendChild(dialog);

            function finish(result) {
                dialog.close();
                dialog.remove();
                resolve(result);
            }
            cancel.addEventListener('click', function () { finish(false); });
            ok.addEventListener('click', function () { finish(true); });
            dialog.addEventListener('cancel', function (e) { e.preventDefault(); finish(false); });
            dialog.showModal();
            cancel.focus();
        });
    }

    /* ---------- Navbar mobile ---------- */
    function setNav(open) {
        var nav = document.getElementById('adm-nav');
        if (!nav) return;
        nav.classList.toggle('is-open', open);
        var btn = nav.querySelector('[data-nav-toggle]');
        if (btn) {
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
            btn.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
        }
    }

    /* ---------- Dropdown ---------- */
    function closeDropdowns(except) {
        document.querySelectorAll('[data-dropdown]').forEach(function (box) {
            if (box === except) return;
            var menu = box.querySelector('[data-dropdown-menu]');
            var trigger = box.querySelector('[data-dropdown-trigger]');
            if (menu) menu.hidden = true;
            if (trigger) trigger.setAttribute('aria-expanded', 'false');
        });
    }

    /* ---------- Event delegation ---------- */
    document.addEventListener('click', function (event) {
        var target = event.target;

        var themeBtn = target.closest('[data-theme-toggle]');
        if (themeBtn) setTheme(currentTheme() === 'dark' ? 'light' : 'dark');

        var navBtn = target.closest('[data-nav-toggle]');
        if (navBtn) {
            var nav = document.getElementById('adm-nav');
            setNav(!(nav && nav.classList.contains('is-open')));
        } else if (target.closest('.adm-nav-link')) {
            setNav(false);
        }

        var trigger = target.closest('[data-dropdown-trigger]');
        var box = trigger ? trigger.closest('[data-dropdown]') : null;
        if (box) {
            var menu = box.querySelector('[data-dropdown-menu]');
            var willOpen = menu ? menu.hidden : false;
            closeDropdowns(box);
            if (menu) {
                menu.hidden = !willOpen;
                trigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
            }
        } else if (!target.closest('[data-dropdown-menu]')) {
            closeDropdowns(null);
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') return;
        closeDropdowns(null);
        setNav(false);
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth > 1100) setNav(false);
    });

    /* Konfirmasi sebelum submit form (hapus, ubah status, dll) */
    document.addEventListener('submit', function (event) {
        var form = event.target;
        if (!(form instanceof HTMLFormElement)) return;

        if (form.hasAttribute('data-confirm') && form.dataset.confirmed !== '1') {
            event.preventDefault();
            confirmDialog({
                title: form.getAttribute('data-confirm-title') || 'Lanjutkan?',
                message: form.getAttribute('data-confirm'),
                confirmText: form.getAttribute('data-confirm-ok') || 'Ya, lanjutkan',
                danger: form.hasAttribute('data-confirm-danger')
            }).then(function (ok) {
                if (!ok) return;
                form.dataset.confirmed = '1';
                if (typeof form.requestSubmit === 'function') form.requestSubmit(); else form.submit();
            });
            return;
        }

        /* Cegah klik ganda pada tombol submit */
        var submit = form.querySelector('[type="submit"].adm-btn');
        if (submit && !submit.classList.contains('is-loading')) {
            submit.classList.add('is-loading');
            submit.setAttribute('aria-disabled', 'true');
        }
    });

    /* Auto-submit filter */
    document.addEventListener('change', function (event) {
        var el = event.target.closest('[data-autosubmit]');
        if (!el) return;
        var form = el.tagName === 'FORM' ? el : el.closest('form');
        if (form) form.submit();
    });

    /* Filter teks pada tabel */
    document.addEventListener('input', function (event) {
        var input = event.target.closest('input[data-filter]');
        if (!input) return;
        var table = document.querySelector(input.getAttribute('data-filter'));
        if (!table) return;
        var query = input.value.trim().toLowerCase();
        var visible = 0;
        table.querySelectorAll('tbody tr:not([data-filter-empty])').forEach(function (row) {
            var match = row.textContent.toLowerCase().indexOf(query) !== -1;
            row.hidden = !match;
            if (match) visible++;
        });
        var empty = table.querySelector('[data-filter-empty]');
        if (empty) empty.hidden = visible !== 0;
    });

    /* ---------- Jam & cuaca (hero dashboard) ---------- */
    function initClock() {
        var box = document.querySelector('[data-clock]');
        if (!box) return;
        var timeEl = box.querySelector('[data-clock-time]');
        var dateEl = box.querySelector('[data-clock-date]');

        function tick() {
            var now = new Date();
            if (timeEl) {
                timeEl.textContent = now.toLocaleTimeString('id-ID', { hour12: false }).replace(/\./g, ':');
            }
            if (dateEl) {
                dateEl.textContent = now.toLocaleDateString('id-ID', {
                    weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
                });
            }
        }
        tick();
        setInterval(tick, 1000);
    }

    function weatherLabel(code) {
        if (code === 0) return 'Cerah';
        if (code <= 3) return 'Berawan';
        if (code === 45 || code === 48) return 'Berkabut';
        if (code >= 51 && code <= 57) return 'Gerimis';
        if (code >= 61 && code <= 67) return 'Hujan';
        if (code >= 80 && code <= 82) return 'Hujan lebat';
        if (code >= 95) return 'Badai petir';
        return 'Berawan';
    }

    function initWeather() {
        var box = document.querySelector('[data-weather]');
        if (!box || !window.fetch) return;
        var tempEl = box.querySelector('[data-weather-temp]');
        var descEl = box.querySelector('[data-weather-desc]');
        var place = box.getAttribute('data-place') || '';
        var cacheKey = 'magello-weather';

        function render(data) {
            if (tempEl) tempEl.textContent = Math.round(data.temp) + '\u00B0C';
            if (descEl) descEl.textContent = weatherLabel(data.code) + (place ? ' di ' + place : '');
        }

        try {
            var cached = JSON.parse(sessionStorage.getItem(cacheKey) || 'null');
            if (cached && Date.now() - cached.at < 15 * 60 * 1000) { render(cached); return; }
        } catch (e) { /* abaikan cache rusak */ }

        var url = 'https://api.open-meteo.com/v1/forecast?latitude=' + encodeURIComponent(box.getAttribute('data-lat')) +
            '&longitude=' + encodeURIComponent(box.getAttribute('data-lon')) +
            '&current=temperature_2m,weather_code';

        fetch(url)
            .then(function (res) { return res.ok ? res.json() : Promise.reject(); })
            .then(function (json) {
                var data = { temp: json.current.temperature_2m, code: json.current.weather_code, at: Date.now() };
                try { sessionStorage.setItem(cacheKey, JSON.stringify(data)); } catch (e) { /* abaikan */ }
                render(data);
            })
            .catch(function () { /* biarkan placeholder jika offline */ });
    }

    /* ---------- Init ---------- */
    document.addEventListener('DOMContentLoaded', function () {
        setTheme(currentTheme());
        closeDropdowns(null);

        var region = document.getElementById('adm-toast-region');
        if (region) {
            toast(region.getAttribute('data-success'), 'success');
            toast(region.getAttribute('data-error'), 'error', 6500);
        }

        initClock();
        initWeather();
    });

    window.AdminUI = { toast: toast, confirm: confirmDialog, setTheme: setTheme };
    if (!window.MagelloUI) window.MagelloUI = window.AdminUI;
})();
