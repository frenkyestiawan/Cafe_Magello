<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cafe Magello</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/auth.css'])
</head>
<body>
    <div class="auth-card">
        <div class="auth-header">
            <div class="brand-badge">
                <i class="fas fa-mug-hot"></i>
            </div>
            <h1 class="auth-title">Cafe Magello</h1>
            <p class="auth-subtitle">Masuk ke akun Anda</p>
        </div>

        @if ($errors->any())
            <div class="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="alert">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="email" class="label">Email</label>
                <div class="input-wrap">
                    <span class="input-icon"><i class="fas fa-envelope"></i></span>
                    <input type="email" id="email" name="email" required class="auth-input" placeholder="admin@example.com">
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="label">Password</label>
                <div class="input-wrap">
                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                    <input type="password" id="password" name="password" required class="auth-input" placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>Masuk
            </button>
        </form>

        <div class="auth-footer">
            <a href="{{ route('home') }}" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Beranda
            </a>
        </div>
    </div>
    @vite(['resources/js/auth.js'])
</body>
</html>