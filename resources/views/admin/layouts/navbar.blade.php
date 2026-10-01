@php
    /*
     * Menu admin. Edit di sini saja: satu sumber untuk desktop dan mobile.
     * 'route' = nama route; item dilewati otomatis bila route belum terdaftar.
     * 'match' = pola route untuk menandai menu aktif.
     */
    $navItems = [
        ['label' => 'Dashboard', 'icon' => 'home',    'route' => 'admin.dashboard',        'match' => 'admin.dashboard'],
        ['label' => 'Pesanan',   'icon' => 'receipt', 'route' => 'admin.orders.index',     'match' => 'admin.orders.*'],
        ['label' => 'Menu',      'icon' => 'coffee',  'route' => 'admin.menus.index',      'match' => 'admin.menus.*'],
        ['label' => 'Kategori',  'icon' => 'tag',     'route' => 'admin.categories.index', 'match' => 'admin.categories.*'],
        ['label' => 'Meja',      'icon' => 'grid',    'route' => 'admin.tables.index',     'match' => 'admin.tables.*'],
        ['label' => 'QR Code',   'icon' => 'qr',      'route' => 'admin.qr.index',         'match' => 'admin.qr.*'],
        ['label' => 'Laporan',   'icon' => 'chart',   'route' => 'admin.reports.index',    'match' => 'admin.reports.*'],
        ['label' => 'Dapur',     'icon' => 'flame',   'route' => 'admin.kitchen.index',    'match' => 'admin.kitchen.*'],
    ];

    $adminUser = auth()->user();
    $adminName = $adminUser->name ?? 'Admin';
    $adminInitial = strtoupper(mb_substr($adminName, 0, 1));
@endphp

<header class="adm-nav" id="adm-nav">
    <div class="adm-container adm-nav-inner">
        <a href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : '#' }}" class="adm-brand" aria-label="Cafe Magello, ke dashboard">
            <span class="adm-brand-mark" aria-hidden="true"><svg class="adm-icon"><use href="#i-coffee"/></svg></span>
            <span>Magello<small>Panel Admin</small></span>
        </a>

        <nav class="adm-nav-links" id="adm-nav-links" aria-label="Navigasi admin">
            @foreach($navItems as $item)
                @continue(! Route::has($item['route']))
                @php $active = request()->routeIs($item['match']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="adm-nav-link {{ $active ? 'is-active' : '' }}"
                   @if($active) aria-current="page" @endif>
                    <svg class="adm-icon" aria-hidden="true"><use href="#i-{{ $item['icon'] }}"/></svg>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="adm-nav-actions">
            <button type="button" class="adm-theme-switch" role="switch" aria-checked="true"
                    aria-label="Mode gelap" data-theme-toggle>
                <span class="adm-knob" aria-hidden="true">
                    <svg class="adm-icon adm-i-moon"><use href="#i-moon"/></svg>
                    <svg class="adm-icon adm-i-sun"><use href="#i-sun"/></svg>
                </span>
            </button>

            <div class="adm-dropdown" data-dropdown>
                <button type="button" class="adm-dropdown-trigger" data-dropdown-trigger
                        aria-haspopup="true" aria-expanded="false">
                    <span class="adm-avatar" aria-hidden="true">{{ $adminInitial }}</span>
                    <span class="adm-user-name">{{ $adminName }}</span>
                    <svg class="adm-icon" aria-hidden="true"><use href="#i-chevron-down"/></svg>
                </button>
                <div class="adm-dropdown-menu" data-dropdown-menu hidden>
                    <div class="adm-dropdown-head">
                        <strong>{{ $adminName }}</strong>
                        <span>Administrator</span>
                    </div>
                    @if(Route::has('logout'))
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="adm-dropdown-item is-danger">
                                <svg class="adm-icon" aria-hidden="true"><use href="#i-logout"/></svg>
                                Keluar
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <button type="button" class="adm-icon-btn adm-burger" data-nav-toggle
                    aria-controls="adm-nav-links" aria-expanded="false" aria-label="Buka menu">
                <svg class="adm-icon" aria-hidden="true"><use href="#i-menu"/></svg>
            </button>
        </div>
    </div>
</header>
