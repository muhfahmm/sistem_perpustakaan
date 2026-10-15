<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Admin</title>
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
        .register-main {
            width: 100%;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
        }
        .auth-container { width: 100%; max-width: 500px; margin: auto; }
        .auth-card {
            background: #ffffff;
            border: 1px solid #e1ebe5;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(20, 60, 40, 0.07);
        }
        .register-card-body { padding: 24px 30px; }
        .brand-header { display: flex; align-items: center; gap: 12px; margin-bottom: 18px; }
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
        .brand-name {
            margin: 0;
            color: #14593c;
            font-size: 1rem;
            font-weight: 750;
            line-height: 1.25;
            letter-spacing: -0.015em;
        }
        .brand-sub {
            margin: 2px 0 0;
            color: #5c6e64;
            font-size: 0.78rem;
            font-weight: 500;
            line-height: 1.25;
            letter-spacing: 0.01em;
        }
        .register-heading { margin-bottom: 18px; }
        .auth-title { margin-bottom: 5px; color: #162a20; font-size: 1.35rem; font-weight: 800; letter-spacing: -0.03em; }
        .muted { margin-bottom: 0; color: #697870; font-size: 0.82rem; line-height: 1.5; }
        .form-label { display: block; margin-bottom: 5px; color: #26382f; font-size: 0.82rem; font-weight: 650; }
        .form-control {
            width: 100%;
            min-height: 42px;
            border: 1px solid #d3dfd7;
            border-radius: 9px;
            color: #1f2d26;
            font-size: 0.88rem;
        }
        .form-control::placeholder { color: #9cb1a5; }
        .form-control:focus {
            border-color: #25835d;
            box-shadow: 0 0 0 0.18rem rgba(37, 131, 93, 0.12);
        }
        .register-field { margin-bottom: 11px; }
        .input-group-phone .input-group-text {
            display: flex;
            align-items: center;
            padding: 0 13px;
            background: #f7faf8;
            border-color: #d3dfd7;
            border-radius: 9px 0 0 9px;
            color: #4a5c53;
            font-size: 0.88rem;
            font-weight: 600;
        }
        .input-group-phone .form-control { border-radius: 0 9px 9px 0; }
        .input-group-password { position: relative; }
        .input-group-password .form-control { padding-right: 45px; }
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
        .register-confirm { margin-bottom: 16px; }
        .btn-auth {
            width: 100%;
            min-height: 42px;
            border: none;
            border-radius: 9px;
            background: #17734d;
            color: #ffffff;
            font-size: 0.9rem;
            font-weight: 700;
            transition: background-color 0.2s ease;
        }
        .btn-auth:hover { background: #125f40; color: #ffffff; }
        .btn-auth:active { background: #104e35 !important; color: #ffffff !important; }
        .auth-link { color: #17734d; font-weight: 700; text-decoration: none; }
        .auth-link:hover { color: #104e35; text-decoration: underline; }
        .register-footer { margin-top: 14px; padding-top: 12px; }
        .alert { margin-bottom: 14px; padding: 9px 12px; border-radius: 9px; font-size: 0.82rem; }
        .invalid-feedback, .password-error { font-size: 0.78rem; }

        @media (max-height: 760px) {
            .register-card-body { padding: 16px 24px; }
            .brand-header { margin-bottom: 11px; }
            .brand-icon { width: 36px; height: 36px; }
            .register-heading { margin-bottom: 11px; }
            .auth-title { font-size: 1.2rem; }
            .register-field { margin-bottom: 7px; }
            .register-confirm { margin-bottom: 10px; }
            .register-footer { margin-top: 9px; padding-top: 9px; }
            .alert { margin-bottom: 8px; padding: 6px 10px; }
        }
        @media (max-width: 400px) {
            .register-main { padding: 8px; }
            .register-card-body { padding: 18px 16px; }
            .auth-title { font-size: 1.2rem; }
        }
    </style>
</head>
<body>
    <main class="register-main">
        <div class="auth-container">
            <div class="auth-card">
                <div class="register-card-body">
                    <div class="brand-header">
                        <img class="brand-icon" src="{{ asset('images/logo-sekolah.png') }}" alt="Logo SMK Al-Islam Surakarta">
                        <div>
                            <h2 class="brand-name">Perpustakaan Digital</h2>
                            <p class="brand-sub">SMK Al-Islam Surakarta</p>
                        </div>
                    </div>

                    <div class="register-heading">
                        <h1 class="auth-title">Daftar akun admin</h1>
                        <p class="muted">Lengkapi data di bawah ini untuk membuat akun baru.</p>
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

                    <form method="POST" action="{{ route('admin.register.attempt') }}" autocomplete="on">
                        @csrf
                        <div class="register-field">
                            <label for="username" class="form-label">Username admin</label>
                            <input id="username" type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Contoh: pustakawan" maxlength="100" required autofocus autocomplete="username">
                            @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="register-field">
                            <label for="telepon" class="form-label">Nomor WhatsApp / telepon</label>
                            <div class="input-group input-group-phone">
                                <span class="input-group-text">+62</span>
                                <input id="telepon" type="tel" name="telepon" class="form-control @error('telepon') is-invalid @enderror" value="{{ old('telepon') }}" placeholder="8123456789" required autocomplete="tel-national">
                            </div>
                            @error('telepon') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="register-field">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group-password">
                                <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" minlength="8" required autocomplete="new-password">
                                <button class="btn-toggle-eye" type="button" data-toggle-password="password" aria-label="Tampilkan password" aria-pressed="false">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password') <div class="password-error text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="register-confirm">
                            <label for="password_confirmation" class="form-label">Konfirmasi password</label>
                            <div class="input-group-password">
                                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" minlength="8" required autocomplete="new-password">
                                <button class="btn-toggle-eye" type="button" data-toggle-password="password_confirmation" aria-label="Tampilkan konfirmasi password" aria-pressed="false">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-auth">Daftar</button>
                    </form>

                    <div class="register-footer text-center border-top">
                        <p class="muted">
                            Sudah punya akun admin?
                            <a class="auth-link" href="{{ route('admin.login') }}">Masuk</a>
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