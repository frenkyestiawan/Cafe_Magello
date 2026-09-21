<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cafe Magello customer experience">
    <title>@yield('title', 'Cafe Magello')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Jost:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/css/home.css', 'resources/js/app.js', 'resources/js/home.js'])

    <script>
        (function () {
            var theme = null;
            try { theme = localStorage.getItem('magello-theme'); } catch (e) {}
            if (!theme) theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            if (theme === 'dark') document.documentElement.classList.add('dark');
        })();
    </script>
</head>
<body class="pb-24 md:pb-0 text-slate-800 antialiased" data-open-time="{{ $openTime ?? '09:00' }}" data-close-time="{{ $closeTime ?? '23:00' }}" data-kitchen-busy="{{ $kitchenBusy ?? false ? '1' : '0' }}">
    <main class="min-h-screen px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl">
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>
