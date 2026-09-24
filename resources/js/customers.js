/* Magello shared UI helpers: tema, toast, dialog konfirmasi.
   Muat di semua halaman: <script src="{{ asset('js/magello.js') }}" defer></script>
   Snippet anti-flicker tema ada di <head> tiap layout (lihat order/index.blade.php). */
(function () {
    var THEME_KEY = 'magello-theme';
    var root = document.documentElement;

    function currentTheme() {
        return root.getAttribute('data-theme') === 'light' ? 'light' : 'dark';
    }

    function setTheme(theme) {
        root.setAttribute('data-theme', theme);
        try { localStorage.setItem(THEME_KEY, theme); } catch (e) {}
        document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
            btn.setAttribute('aria-checked', theme === 'dark' ? 'true' : 'false');
            btn.setAttribute('title', theme === 'dark' ? 'Ganti ke mode terang' : 'Ganti ke mode gelap');
        });
    }

    document.addEventListener('click', function (event) {
        var toggle = event.target.closest('[data-theme-toggle]');
        if (toggle) setTheme(currentTheme() === 'dark' ? 'light' : 'dark');
    });

    document.addEventListener('DOMContentLoaded', function () { setTheme(currentTheme()); });

    /* ---------- Toast ---------- */
    function toast(message, type, duration) {
        var region = document.getElementById('toast-region');
        if (!region || !message) return;
        var el = document.createElement('div');
        el.className = 'toast' + (type ? ' is-' + type : '');
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

    /* ---------- Dialog konfirmasi (mengembalikan Promise<boolean>) ---------- */
    function confirmDialog(options) {
        options = options || {};
        return new Promise(function (resolve) {
            var dialog = document.createElement('dialog');
            dialog.className = 'dialog';
            dialog.setAttribute('aria-labelledby', 'confirm-title');

            var title = document.createElement('h3');
            title.id = 'confirm-title';
            title.textContent = options.title || 'Lanjutkan?';
            var body = document.createElement('p');
            body.textContent = options.message || '';

            var actions = document.createElement('div');
            actions.className = 'dialog-actions';
            var cancel = document.createElement('button');
            cancel.type = 'button';
            cancel.className = 'btn btn-ghost';
            cancel.textContent = options.cancelText || 'Batal';
            var ok = document.createElement('button');
            ok.type = 'button';
            ok.className = 'btn ' + (options.danger ? 'btn-danger' : '');
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

    window.MagelloUI = { toast: toast, confirm: confirmDialog, setTheme: setTheme };
})();