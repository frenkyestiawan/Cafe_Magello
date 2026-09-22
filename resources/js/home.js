(function () {
    var root = document.documentElement;

    /* ---------- Tema ---------- */
    var toggle = document.getElementById('theme-toggle');
    function syncToggle() {
        if (!toggle) return;
        toggle.setAttribute('aria-checked', root.classList.contains('dark') ? 'true' : 'false');
    }
    if (toggle) {
        syncToggle();
        toggle.addEventListener('click', function () {
            var dark = root.classList.toggle('dark');
            try { localStorage.setItem('magello-theme', dark ? 'dark' : 'light'); } catch (e) {}
            syncToggle();
        });
    }

    /* ---------- Navigasi mobile ---------- */
    var navBtn = document.getElementById('nav-toggle');
    var mobileNav = document.getElementById('mobile-nav');
    if (navBtn && mobileNav) {
        navBtn.addEventListener('click', function () {
            var open = mobileNav.hidden;
            mobileNav.hidden = !open;
            navBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        Array.prototype.forEach.call(document.querySelectorAll('.js-close-nav'), function (a) {
            a.addEventListener('click', function () {
                mobileNav.hidden = true;
                navBtn.setAttribute('aria-expanded', 'false');
            });
        });
    }

    /* ---------- Status buka / tutup (zona WIB) ---------- */
    var OPEN = document.body.dataset.openTime || '09:00';
    var CLOSE = document.body.dataset.closeTime || '23:00';
    var BUSY = document.body.dataset.kitchenBusy === '1';
    function toMin(t) {
      var p = String(t || '00:00').split(':');
      return parseInt(p[0], 10) * 60 + parseInt(p[1], 10);
    }
    function nowWib() {
      var parts = new Intl.DateTimeFormat('en-GB', { timeZone: 'Asia/Jakarta', hour: '2-digit', minute: '2-digit', hour12: false }).formatToParts(new Date());
      var h = 0, m = 0;
      parts.forEach(function (p) {
        if (p.type === 'hour') h = parseInt(p.value, 10) % 24;
        if (p.type === 'minute') m = parseInt(p.value, 10);
      });
      return h * 60 + m;
    }
    function dotTime(t) { return String(t).replace(':', '.'); }
    function updateStatus() {
      var n = nowWib();
      var o = toMin(OPEN);
      var c = toMin(CLOSE);
      var isOpen = o <= c ? (n >= o && n < c) : (n >= o || n < c);
      var state = !isOpen ? 'closed' : (BUSY ? 'busy' : 'open');
      var label = state === 'closed' ? 'Tutup, buka ' + dotTime(OPEN)
                : state === 'busy' ? 'Sedang ramai, penyajian lebih lama'
                : 'Buka sampai ' + dotTime(CLOSE);
      Array.prototype.forEach.call(document.querySelectorAll('.js-status'), function (el) {
        el.setAttribute('data-state', state);
        var target = el.querySelector('.js-status-label');
        if (target) target.textContent = label;
      });
    }
    updateStatus();
    setInterval(updateStatus, 60000);

    /* ---------- Filter & cari menu ---------- */
    var chips = Array.prototype.slice.call(document.querySelectorAll('.js-chip'));
    var cards = Array.prototype.slice.call(document.querySelectorAll('.menu-card'));
    var search = document.getElementById('menu-search');
    var emptyMsg = document.getElementById('menu-empty');
    var countMsg = document.getElementById('menu-count');
    var activeCat = 'Semua';
    function applyFilter() {
      var term = search ? search.value.trim().toLowerCase() : '';
      var shown = 0;
      cards.forEach(function (c) {
        var okCat = activeCat === 'Semua' || c.getAttribute('data-cat') === activeCat;
        var hay = (c.getAttribute('data-name') + ' ' + c.getAttribute('data-desc')).toLowerCase();
        var ok = okCat && (!term || hay.indexOf(term) > -1);
        c.hidden = !ok;
        if (ok) shown++;
      });
      if (emptyMsg) emptyMsg.hidden = shown !== 0;
      if (countMsg) countMsg.textContent = shown + ' menu ditampilkan';
    }
    chips.forEach(function (chip) {
      chip.addEventListener('click', function () {
        activeCat = chip.getAttribute('data-cat');
        chips.forEach(function (c) { c.setAttribute('aria-pressed', c === chip ? 'true' : 'false'); });
        applyFilter();
      });
    });
    if (search) search.addEventListener('input', applyFilter);

    /* ---------- Keranjang (front-end; sambungkan ke sistem pesanan Anda) ---------- */
    var KEY = 'magello-cart';
    function getCartKey(menuId, variantId) {
      return String(menuId) + ':' + String(variantId || 'base');
    }
    var cart = {};
    try {
      var raw = JSON.parse(localStorage.getItem(KEY) || '{}');
      Object.keys(raw).forEach(function (k) {
        var it = raw[k];
        if (it && typeof it.name === 'string' && isFinite(it.price) && isFinite(it.qty) && it.qty > 0) {
          var menuId = String(it.id || k.split(':')[0]);
          var variantId = it.variant_id || (String(k).indexOf(':') > -1 ? k.split(':')[1] : null);
          var itemKey = getCartKey(menuId, variantId);
          cart[itemKey] = {
            key: itemKey,
            id: menuId,
            name: it.name,
            price: Number(it.price),
            qty: Math.floor(it.qty),
            variant_id: variantId || null,
            variant_name: it.variant_name || null
          };
        }
      });
    } catch (e) { cart = {}; }
    function save() { try { localStorage.setItem(KEY, JSON.stringify(cart)); } catch (e) {} }
    function normalizeCartForServer() {
      var payload = {};
      Object.keys(cart).forEach(function (id) {
        var item = cart[id];
        if (!item) return;
        payload[id] = {
          id: String(item.id),
          name: item.name,
          price: Number(item.price),
          quantity: Number(item.qty || item.quantity || 1),
          qty: Number(item.qty || item.quantity || 1),
          variant_id: item.variant_id || null,
          variant_name: item.variant_name || null
        };
      });
      return payload;
    }
    function syncCartToServer() {
      var token = document.querySelector('meta[name="csrf-token"]');
      if (!token) return;
      fetch('/order/sync-cart', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token.getAttribute('content'),
          'Accept': 'application/json'
        },
        body: JSON.stringify({ cart: normalizeCartForServer() })
      }).catch(function () {});
    }
    function rupiah(n) { return 'Rp ' + n.toLocaleString('id-ID'); }

    var list = document.getElementById('cart-list');
    var emptyBox = document.getElementById('cart-empty');
    var footer = document.getElementById('cart-footer');

    function refreshMenuCardPrice(card) {
      if (!card) return;
      var selected = card.querySelector('.js-menu-variant-input:checked');
      var priceEl = card.querySelector('.js-menu-price');
      var addBtn = card.querySelector('.js-add');
      if (!selected || !priceEl || !addBtn) return;

      var price = Number(selected.getAttribute('data-variant-price') || addBtn.getAttribute('data-price') || 0);
      priceEl.textContent = 'Rp ' + Number(price).toLocaleString('id-ID');
      addBtn.setAttribute('data-price', String(price));
      addBtn.setAttribute('data-variant-id', selected.getAttribute('data-variant-id') || '');
      addBtn.setAttribute('data-variant-name', selected.getAttribute('data-variant-name') || '');
    }

    function render() {
      var items = Object.keys(cart).map(function (k) { return cart[k]; });
      var n = 0, total = 0;
      items.forEach(function (i) { n += i.qty; total += i.qty * i.price; });

      Array.prototype.forEach.call(document.querySelectorAll('.js-cart-count'), function (el) {
        el.textContent = n;
        el.hidden = n === 0;
      });
      Array.prototype.forEach.call(document.querySelectorAll('.js-cart-total'), function (el) {
        el.textContent = rupiah(total);
      });
      Array.prototype.forEach.call(document.querySelectorAll('.js-add'), function (b) {
        var menuId = b.getAttribute('data-id');
        var aggregateQty = 0;
        Object.keys(cart).forEach(function (key) {
          if (String(cart[key].id) === String(menuId)) aggregateQty += Number(cart[key].qty || 0);
        });
        var bub = b.querySelector('.qty-bubble');
        if (bub) {
          if (aggregateQty > 0) { bub.textContent = aggregateQty; bub.hidden = false; }
          else { bub.hidden = true; }
        }
      });

      if (list) {
        list.textContent = '';
        items.forEach(function (i) {
          var row = document.createElement('div'); row.className = 'cart-row';

          var info = document.createElement('div');
          var nm = document.createElement('p'); nm.className = 'font-medium text-heading';
          var displayName = i.name + (i.variant_name ? ' (' + i.variant_name + ')' : '');
          nm.textContent = displayName;
          var pr = document.createElement('p'); pr.className = 'text-sm text-muted'; pr.textContent = rupiah(i.price);
          info.appendChild(nm); info.appendChild(pr);

          var step = document.createElement('div'); step.className = 'stepper';
          var minus = document.createElement('button'); minus.type = 'button'; minus.textContent = '-';
          minus.setAttribute('aria-label', 'Kurangi ' + displayName); minus.setAttribute('data-act', 'dec'); minus.setAttribute('data-key', i.key || getCartKey(i.id, i.variant_id));
          var q = document.createElement('span'); q.textContent = i.qty;
          var plus = document.createElement('button'); plus.type = 'button'; plus.textContent = '+';
          plus.setAttribute('aria-label', 'Tambah ' + displayName); plus.setAttribute('data-act', 'inc'); plus.setAttribute('data-key', i.key || getCartKey(i.id, i.variant_id));
          step.appendChild(minus); step.appendChild(q); step.appendChild(plus);

          row.appendChild(info); row.appendChild(step);
          list.appendChild(row);
        });
      }

      var has = items.length > 0;
      if (list) list.hidden = !has;
      if (footer) footer.hidden = !has;
      if (emptyBox) emptyBox.hidden = has;
    }

    function add(id, name, price, variantId, variantName) {
      var key = getCartKey(id, variantId);
      if (cart[key]) { cart[key].qty += 1; }
      else {
        cart[key] = {
          key: key,
          id: String(id),
          name: name,
          price: Number(price || 0),
          qty: 1,
          variant_id: variantId || null,
          variant_name: variantName || null
        };
      }
      save(); syncCartToServer(); render();
    }

    var toast = document.getElementById('toast');
    var toastTimer = null;
    function showToast(msg) {
      if (!toast) return;
      toast.textContent = msg; toast.classList.add('is-on');
      clearTimeout(toastTimer);
      toastTimer = setTimeout(function () { toast.classList.remove('is-on'); }, 1800);
    }

    Array.prototype.forEach.call(document.querySelectorAll('.js-menu-variant-input'), function (input) {
      input.addEventListener('change', function () {
        refreshMenuCardPrice(input.closest('.menu-card'));
      });
    });

    Array.prototype.forEach.call(document.querySelectorAll('.js-add'), function (b) {
      b.addEventListener('click', function () {
        var card = b.closest('.menu-card');
        var selected = card ? card.querySelector('.js-menu-variant-input:checked') : null;
        var variantId = selected ? selected.getAttribute('data-variant-id') : b.getAttribute('data-variant-id');
        var variantName = selected ? selected.getAttribute('data-variant-name') : b.getAttribute('data-variant-name');
        var price = selected ? Number(selected.getAttribute('data-variant-price')) : Number(b.getAttribute('data-price') || 0);
        var name = b.getAttribute('data-name');
        add(b.getAttribute('data-id'), name, price, variantId, variantName);
        showToast(name + (variantName ? ' (' + variantName + ')' : '') + ' ditambahkan');
      });
    });

    if (list) {
      list.addEventListener('click', function (e) {
        var btn = e.target.closest('button[data-act]');
        if (!btn) return;
        var key = btn.getAttribute('data-key');
        if (!key || !cart[key]) return;
        if (btn.getAttribute('data-act') === 'inc') { cart[key].qty += 1; }
        else { cart[key].qty -= 1; if (cart[key].qty <= 0) delete cart[key]; }
        save(); syncCartToServer(); render();
      });
    }
    var cartClear = document.getElementById('cart-clear');
    if (cartClear) {
      cartClear.addEventListener('click', function () { cart = {}; save(); syncCartToServer(); render(); });
    }

    /* ---------- Drawer ---------- */
    var drawer = document.getElementById('cart-drawer');
    var overlay = document.getElementById('overlay');
    var lastFocus = null;
    function openCart(trigger) {
      lastFocus = trigger || document.activeElement;
      if (drawer) drawer.classList.add('is-open');
      if (overlay) overlay.classList.add('is-open');
      if (drawer) drawer.setAttribute('aria-hidden', 'false');
      var cartClose = document.getElementById('cart-close');
      if (cartClose) cartClose.focus();
    }
    function closeCart() {
      if (drawer) {
        drawer.classList.remove('is-open');
        drawer.setAttribute('aria-hidden', 'true');
      }
      if (overlay) overlay.classList.remove('is-open');
      if (lastFocus && lastFocus.focus) lastFocus.focus();
    }
    Array.prototype.forEach.call(document.querySelectorAll('.js-open-cart'), function (b) {
      b.addEventListener('click', function () { openCart(b); });
    });
    var cartClose = document.getElementById('cart-close');
    if (cartClose) cartClose.addEventListener('click', closeCart);
    if (overlay) overlay.addEventListener('click', closeCart);
    var cartToMenu = document.getElementById('cart-to-menu');
    if (cartToMenu) {
      cartToMenu.addEventListener('click', function () { lastFocus = null; closeCart(); });
    }
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && drawer && drawer.classList.contains('is-open')) closeCart();
    });

    render();
    applyFilter();
})();
