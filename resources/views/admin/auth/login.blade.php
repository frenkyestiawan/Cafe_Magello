<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cafe Magello</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg: #f7efe6;
            --panel: rgba(255, 249, 243, 0.94);
            --ink: #2d1d1a;
            --muted: #705348;
            --line: #d9be9c;
            --brand: #5d312a;
            --brand-deep: #2f1d1b;
            --input: #fffdfb;
            --danger-bg: #fef2f2;
            --danger-text: #991b1b;
            --danger-line: #fca5a5;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg: #1a120f;
                --panel: rgba(38, 25, 21, 0.94);
                --ink: #f7ecdf;
                --muted: #d5b79b;
                --line: #5f433a;
                --brand: #f7d7a5;
                --brand-deep: #f3e1bc;
                --input: #1f1714;
                --danger-bg: rgba(127, 29, 29, 0.18);
                --danger-text: #fecaca;
                --danger-line: rgba(248, 113, 113, 0.6);
            }
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: radial-gradient(circle at top, rgba(214, 171, 112, 0.22), transparent 38%), var(--bg);
            color: var(--ink);
            font-family: "Segoe UI", sans-serif;
        }

        .auth-card {
            width: 100%;
            max-width: 430px;
            border: 1px solid var(--line);
            border-radius: 22px;
            background: var(--panel);
            box-shadow: 0 20px 50px rgba(30, 17, 14, 0.12);
            backdrop-filter: blur(10px);
        }

        .auth-header {
            padding: 28px 24px 18px;
            text-align: center;
        }

        .brand-badge {
            width: 58px;
            height: 58px;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 1px solid var(--line);
            background: rgba(93, 49, 42, 0.08);
            color: var(--brand);
            font-size: 22px;
        }

        .auth-title {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -0.04em;
            color: var(--ink);
        }

        .auth-subtitle {
            margin: 8px 0 0;
            font-size: 0.9rem;
            color: var(--muted);
            font-weight: 500;
        }

        .alert {
            margin: 0 24px 16px;
            border: 1px solid var(--danger-line);
            border-radius: 14px;
            background: var(--danger-bg);
            color: var(--danger-text);
            padding: 12px 14px;
            font-size: 0.9rem;
        }

        .alert ul {
            margin: 0;
            padding-left: 18px;
        }

        .alert li + li { margin-top: 4px; }

        .auth-form {
            padding: 0 24px 24px;
        }

        .form-group { margin-bottom: 18px; }

        .label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--ink);
        }

        .input-wrap { position: relative; }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 0.95rem;
        }

        .auth-input {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: var(--input);
            color: var(--ink);
            padding: 13px 14px 13px 40px;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .auth-input::placeholder { color: var(--muted); }

        .auth-input:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 4px rgba(93, 49, 42, 0.08);
        }

        .btn-login {
            width: 100%;
            border: none;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 0.95rem;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, var(--brand), var(--brand-deep));
            cursor: pointer;
            transition: transform 0.15s ease, opacity 0.2s ease;
            box-shadow: 0 10px 25px rgba(93, 49, 42, 0.22);
        }

        .btn-login:hover { opacity: 0.96; }
        .btn-login:active { transform: translateY(1px); }

        .auth-footer {
            text-align: center;
            padding: 0 24px 24px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--brand);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .back-link:hover { opacity: 0.8; }
    </style>
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

        <form action="{{ route('admin.login') }}" method="POST" class="auth-form">
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
</body>
</html>