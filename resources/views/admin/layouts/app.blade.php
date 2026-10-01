<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') &middot; Cafe Magello</title>

    {{-- Anti-flicker tema: harus inline agar jalan sebelum CSS dirender --}}
    <script>
        (function () {
            var t = 'dark';
            try { t = localStorage.getItem('magello-theme') === 'light' ? 'light' : 'dark'; } catch (e) {}
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
    @stack('styles')
</head>
<body class="adm-body">
    <a class="adm-skip" href="#adm-content">Lewati ke konten</a>

    @include('admin.layouts.icons')
    @include('admin.layouts.navbar')

    <main id="adm-content" class="adm-main">
        <div class="adm-container">
            @hasSection('page-title')
                <div class="adm-page-head">
                    <div>
                        <h1>@yield('page-title')</h1>
                        @hasSection('page-subtitle')
                            <p>@yield('page-subtitle')</p>
                        @endif
                    </div>
                    @hasSection('page-actions')
                        <div class="adm-page-actions">@yield('page-actions')</div>
                    @endif
                </div>
            @endif

            @if($errors->any() && ! session('error'))
                <div class="adm-alert adm-alert--err adm-alert--spaced" role="alert">
                    <svg class="adm-icon" aria-hidden="true"><use href="#i-alert"/></svg>
                    <div>
                        <strong>Periksa kembali isian Anda.</strong>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    {{-- Flash message dibaca admin.js lewat atribut data-* --}}
    <div class="adm-toast-region" id="adm-toast-region" aria-live="polite"
         data-success="{{ session('success') }}"
         data-error="{{ session('error') }}"></div>

    @stack('scripts')
</body>
</html>
