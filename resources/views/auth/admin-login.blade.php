<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        html, body { width: 100%; min-height: 100%; margin: 0; }
        body {
            background: linear-gradient(135deg, #eef5f1 0%, #f7faf8 100%);
            color: #1f2d26;
            font-family: "Plus Jakarta Sans", sans-serif;
        }
        .login-main {
            width: 100%;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
        }
        .auth-container { width: 100%; max-width: 480px; margin: auto; }
        .auth-card {
            background: #ffffff;
            border: 1px solid #e1ebe5;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(20, 60, 40, 0.07);
        }
        .login-card-body { padding: 28px 30px; }
        .brand-header { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
        .brand-icon {
            width: 44px;
            height: 44px;
            flex-shrink: 0;
            object-fit: contain;
            padding: 5px;
            background: #f4fbf7;
            border: 1px solid #e2eee7;
            border-radius: 10px;
        }
        .brand-name { margin: 0; color: #166e4a; font-size: 0.95rem; font-weight: 700; line-height: 1.4; }
        .brand-sub { margin: 0; color: #6d7f76; font-size: 0.75rem; }
        .login-heading { margin-bottom: 20px; }
        .auth-title { margin-bottom: 5px; color: #162a20; font-size: 1.4rem; font-weight: 800; letter-spacing: -0.03em; }
        .muted { margin-bottom: 0; color: #697870; font-size: 0.83rem; line-height: 1.5; }
        .form-label { display: block; margin-bottom: 5px; color: #26382f; font-size: 0.83rem; font-weight: 650; }
        .login-field { margin-bottom: 14px; }
        .form-control {
            width: 100%;
            min-height: 44px;
            border: 1px solid #d3dfd7;
            border-radius: 9px;
            color: #1f2d26;
            font-size: 0.9rem;
        }
        .form-control::placeholder { color: #9cb1a5; }
        .form-control:focus {
            border-color: #25835d;
            box-shadow: 0 0 0 0.18rem rgba(37, 131, 93, 0.12);
        }
        .input-group-password { position: relative; }
        .input-group-password .form-control { padding-right: 46px; }
        .btn-toggle-eye {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            z-index: 5;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            padding: 0;
            border: none;
            border-radius: 9px;
            background: transparent;
            color: #7b8e83;
            cursor: pointer;
        }
        .btn-toggle-eye:hover, .btn-toggle-eye:focus { color: #166e4a; }
        .btn-toggle-eye:focus-visible { outline: 2px solid #25835d; outline-offset: -4px; }
        .form-check-input { border-color: #c7d6cc; cursor: pointer; }
        .form-check-input:checked { background-color: #17734d; border-color: #17734d; }
        .form-check-input:focus { border-color: #25835d; box-shadow: 0 0 0 0.18rem rgba(37, 131, 93, 0.12); }
        .form-check-label { cursor: pointer; }
        .login-options { margin-bottom: 18px; }
        .btn-auth {
            width: 100%;
            min-height: 44px;
            border: none;
            border-radius: 9px;
            background: #17734d;
            color: #ffffff;
            font-size: 0.92rem;
            font-weight: 700;
            letter-spacing: 0.01em;
            transition: background-color 0.2s ease;
        }
        .btn-auth:hover { background: #125f40; color: #ffffff; }
        .btn-auth:active { background: #104e35 !important; color: #ffffff !important; }
        .auth-link { color: #17734d; font-weight: 700; text-decoration: none; }
        .auth-link:hover { color: #104e35; text-decoration: underline; }
        .login-footer { margin-top: 16px; padding-top: 14px; }
        .alert { margin-bottom: 14px; padding: 9px 12px; border-radius: 9px; font-size: 0.83rem; }
        .invalid-feedback { font-size: 0.78rem; }

        @media (max-height: 700px) {
            .login-card-body { padding: 16px 24px; }
            .brand-header { margin-bottom: 12px; }
            .brand-icon { width: 36px; height: 36px; }
            .login-heading { margin-bottom: 12px; }
            .auth-title { font-size: 1.2rem; }
            .login-field { margin-bottom: 9px; }
            .login-options { margin-bottom: 12px; }
            .login-footer { margin-top: 10px; padding-top: 10px; }
            .alert { margin-bottom: 8px; padding: 6px 10px; }
        }
        @media (max-width: 400px) {
            .login-main { padding: 8px; }
            .login-card-body { padding: 20px 16px; }
            .auth-title { font-size: 1.25rem; }
        }
    </style>
</head>
<body>
    <main class="login-main">
        <div class="auth-container">
            <div class="auth-card">
                <div class="login-card-body">
                    <div class="brand-header">
                        <img class="brand-icon" src="{{ asset('images/logo-sekolah.png') }}" alt="Logo SMK Al-Islam Surakarta">
                        <div>
                            <h2 class="brand-name">Perpustakaan Digital</h2>
                            <p class="brand-sub">SMK Al-Islam Surakarta</p>
                        </div>
                    </div>

                    <div class="login-heading">
                        <h1 class="auth-title">Masuk admin</h1>
                        <p class="muted">Silakan masukkan akun Anda untuk mengelola perpustakaan.</p>
                    </div>

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-circle me-1"></i> {{ session('error') }}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login.attempt') }}" autocomplete="on">
                        @csrf
                        <div class="login-field">
                            <label for="username" class="form-label">Username admin</label>
                            <input id="username" type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Masukkan username" required autofocus autocomplete="username">
                            @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="login-field">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group-password">
                                <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password" required autocomplete="current-password">
                                <button class="btn-toggle-eye" type="button" data-toggle-password="password" aria-label="Tampilkan password" aria-pressed="false">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="login-options">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1">
                                <label class="form-check-label muted" for="remember">Ingat saya</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-auth">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                        </button>
                    </form>

                    <div class="login-footer text-center border-top">
                        <p class="muted">
                            Belum punya akun admin?
                            <a class="auth-link" href="{{ route('admin.register') }}">Daftar</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.querySelectorAll('[data-toggle-password]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.togglePassword);
                const icon = button.querySelector('i');
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                button.setAttribute('aria-pressed', String(isPassword));
                if (isPassword) {
                    icon.classList.replace('bi-eye', 'bi-eye-slash');
                    button.setAttribute('aria-label', 'Sembunyikan password');
                } else {
                    icon.classList.replace('bi-eye-slash', 'bi-eye');
                    button.setAttribute('aria-label', 'Tampilkan password');
                }
            });
        });
    </script>
</body>
</html>