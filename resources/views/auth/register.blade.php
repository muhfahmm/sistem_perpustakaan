<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; background: #f5f7f6; color: #24352c; font-family: "Plus Jakarta Sans", "Segoe UI", sans-serif; }
        .auth-card { width: min(100%, 460px); border: 1px solid #e7ece8; border-radius: 16px; box-shadow: 0 12px 36px rgba(27, 54, 39, .06); }
        .brand { color: #16734f; font-weight: 800; letter-spacing: -.03em; }
        .brand-icon { display: block; width: 48px; height: 48px; object-fit: contain; background: #fff; border-radius: 10px; }
        .auth-title { font-size: 1.6rem; font-weight: 750; letter-spacing: -.04em; }
        .muted { color: #77837b; }
        .form-label { font-size: .9rem; font-weight: 650; }
        .form-control { min-height: 44px; border-color: #dce5de; border-radius: 10px; }
        .form-control:focus { border-color: #25835d; box-shadow: 0 0 0 .2rem rgba(37, 131, 93, .12); }
        .input-group .form-control { border-radius: 10px 0 0 10px; }
        .input-group .btn { border-color: #dce5de; border-radius: 0 10px 10px 0; }
        .input-group-text { border-color: #dce5de; }
        .btn-auth { min-height: 46px; color: #fff; background: #19764f; border: 0; border-radius: 10px; font-weight: 700; }
        .btn-auth:hover { color: #fff; background: #125f40; }
        .auth-link { color: #16734f; font-weight: 700; text-decoration: none; }
        .auth-link:hover { text-decoration: underline; }
        .password-strength { height: 4px; background: #e7eee9; border-radius: 99px; }
        .password-strength .progress-bar { background: #19764f; transition: width .2s ease; }
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
                    <h1 class="auth-title mb-2">Daftar akun admin</h1>
                    <p class="muted mb-0">Lengkapi data berikut untuk membuat akun.</p>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger rounded-3" role="alert">{{ session('error') }}</div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success rounded-3" role="alert">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('admin.register.attempt') }}" autocomplete="off">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Username admin</label>
                        <input id="username" type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Contoh: pustakawan" required maxlength="100" autofocus autocomplete="username">
                        @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="telepon" class="form-label">Nomor WhatsApp / telepon</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">+62</span>
                            <input id="telepon" type="tel" name="telepon" class="form-control border-start-0 @error('telepon') is-invalid @enderror" value="{{ old('telepon') }}" placeholder="8123456789" required autocomplete="tel">
                        </div>
                        @error('telepon') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" required minlength="8" autocomplete="new-password">
                            <button class="btn btn-outline-secondary" type="button" data-toggle-password="password" aria-label="Tampilkan password">Lihat</button>
                        </div>
                        <div class="progress password-strength mt-2" role="progressbar" aria-label="Kekuatan password" aria-valuemin="0" aria-valuemax="100">
                            <div id="strength-bar" class="progress-bar" style="width: 0%"></div>
                        </div>
                        @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Konfirmasi password</label>
                        <div class="input-group">
                            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required minlength="8" autocomplete="new-password">
                            <button class="btn btn-outline-secondary" type="button" data-toggle-password="password_confirmation" aria-label="Tampilkan konfirmasi password">Lihat</button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-auth w-100">Daftar</button>
                </form>
                <p class="text-center muted mt-4 mb-0">Sudah punya akun admin? <a class="auth-link" href="{{ route('admin.login') }}">Masuk</a></p>
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

        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('strength-bar');
        passwordInput.addEventListener('input', () => {
            const length = passwordInput.value.length;
            strengthBar.style.width = length === 0 ? '0%' : length < 6 ? '30%' : length < 8 ? '65%' : '100%';
            strengthBar.classList.toggle('bg-danger', length > 0 && length < 6);
            strengthBar.classList.toggle('bg-warning', length >= 6 && length < 8);
        });
    </script>
</body>
</html>
