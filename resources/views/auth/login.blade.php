<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk &middot; Cafe Magello</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">

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
</head>
<body class="adm-body">
    @include('admin.layouts.icons')

    <button type="button" class="adm-theme-switch adm-auth-theme" role="switch" aria-checked="true"
            aria-label="Mode gelap" data-theme-toggle>
        <span class="adm-knob" aria-hidden="true">
            <svg class="adm-icon adm-i-moon"><use href="#i-moon"/></svg>
            <svg class="adm-icon adm-i-sun"><use href="#i-sun"/></svg>
        </span>
    </button>

    <main class="adm-auth">
        <div class="adm-card adm-auth-card">
            <div class="adm-auth-brand">
                <span class="adm-brand-mark" aria-hidden="true"><svg class="adm-icon"><use href="#i-coffee"/></svg></span>
                <h1>Login</h1>
                <p>Masuk untuk mengelola Cafe Magello</p>
            </div>

            @if($errors->any() || session('error'))
                <div class="adm-alert adm-alert--err adm-alert--spaced" role="alert">
                    <svg class="adm-icon" aria-hidden="true"><use href="#i-alert"/></svg>
                    <div>
                        <ul>
                            @if(session('error'))
                                <li>{{ session('error') }}</li>
                            @endif
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="adm-form">
                @csrf

                <div>
                    <label for="email" class="adm-label">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="adm-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           autocomplete="username" placeholder="admin@example.com" required
                           @if($errors->has('email')) aria-invalid="true" @endif>
                </div>

                <div>
                    <label for="password" class="adm-label">Password</label>
                    <input type="password" id="password" name="password" class="adm-input"
                           autocomplete="current-password" placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="adm-btn adm-btn--block">Masuk</button>
                <a href="{{ route('home') }}" class="adm-btn adm-btn--ghost adm-btn--block">Kembali ke Beranda</a>
            </form>
        </div>
    </main>

    <div class="adm-toast-region" id="adm-toast-region" aria-live="polite"
         data-success="{{ session('success') }}"
         data-error="{{ session('error') }}"></div>

</body>
</html>
