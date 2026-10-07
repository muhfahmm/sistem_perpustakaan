<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; background: #f5f7f6; color: #24352c; font-family: "Plus Jakarta Sans", "Segoe UI", sans-serif; }
        .auth-card { width: min(100%, 420px); border: 1px solid #e7ece8; border-radius: 16px; box-shadow: 0 12px 36px rgba(27, 54, 39, .06); }
        .brand { color: #16734f; font-weight: 800; letter-spacing: -.03em; }
        .brand-icon { display: block; width: 48px; height: 48px; object-fit: contain; background: #fff; border-radius: 10px; }
        .auth-title { font-size: 1.6rem; font-weight: 750; letter-spacing: -.04em; }
        .muted { color: #77837b; }
        .form-label { font-size: .9rem; font-weight: 650; }
        .form-control { min-height: 46px; border-color: #dce5de; border-radius: 10px; }
        .form-control:focus { border-color: #25835d; box-shadow: 0 0 0 .2rem rgba(37, 131, 93, .12); }
        .input-group .form-control { border-radius: 10px 0 0 10px; }
        .input-group .btn { border-color: #dce5de; border-radius: 0 10px 10px 0; }
        .btn-auth { min-height: 46px; color: #fff; background: #19764f; border: 0; border-radius: 10px; font-weight: 700; }
        .btn-auth:hover { color: #fff; background: #125f40; }
        .auth-link { color: #16734f; font-weight: 700; text-decoration: none; }
        .auth-link:hover { text-decoration: underline; }
        .form-check-input:checked { background-color: #19764f; border-color: #19764f; }
    </style>
</head>
<body>
    <main class="container min-vh-100 d-flex align-items-center justify-content-center py-4">
        <div class="auth-card card">
            <div class="card-body p-4 p-sm-5">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <img class="brand-icon" src="{{ asset('images/logo-sekolah.png') }}" alt="Logo SMK Al-Islam Surakarta">
                </div>
                <div class="mb-4">
                    <h1 class="auth-title mb-2">Masuk admin</h1>
                    <p class="muted mb-0">Masuk untuk melanjutkan ke panel perpustakaan.</p>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger rounded-3" role="alert">{{ session('error') }}</div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success rounded-3" role="alert">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('admin.login.attempt') }}" autocomplete="off">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Username admin</label>
                        <input id="username" type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Masukkan username" required autofocus autocomplete="username">
                        @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password" required autocomplete="current-password">
                            <button class="btn btn-outline-secondary" type="button" data-toggle-password="password" aria-label="Tampilkan password">Lihat</button>
                        </div>
                        @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label muted" for="remember">Ingat saya</label>
                    </div>
                    <button type="submit" class="btn btn-auth w-100">Masuk</button>
                </form>
                <p class="text-center muted mt-4 mb-0">Belum punya akun admin? <a class="auth-link" href="{{ route('admin.register') }}">Daftar</a></p>
            </div>
        </div>
    </main>
    <script>
        document.querySelectorAll('[data-toggle-password]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.togglePassword);
                const show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                button.textContent = show ? 'Sembunyi' : 'Lihat';
                button.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
            });
        });
    </script>
</body>
</html>
