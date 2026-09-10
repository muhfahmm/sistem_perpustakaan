<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Admin | Perpusku</title>
    <style>
        :root { --ink: #30322b; --sage: #344336; --cream: #f2efe4; --peach: #dfab93; --sky: #dce4f3; --muted: #77786f; }
        * { box-sizing: border-box; }
        body { min-height: 100vh; margin: 0; padding: 26px; color: var(--ink); background: var(--sky); font-family: 'Trebuchet MS', 'Segoe UI', sans-serif; }
        .shell { width: min(460px, 100%); margin: auto; overflow: hidden; background: #fff; border-radius: 12px; box-shadow: 0 20px 50px rgba(78, 91, 121, .16); }
        .topbar { display: flex; align-items: center; justify-content: space-between; min-height: 64px; padding: 0 32px; border-bottom: 1px solid #eeeade; }
        .brand { color: var(--sage); font: 700 1.2rem Georgia, serif; letter-spacing: -.04em; }
        .brand::first-letter { color: var(--peach); }
        .signin { display: flex; align-items: center; gap: 16px; color: #4e504a; font-size: .76rem; }
        .toplink { padding: 8px 25px; color: #fff; background: var(--sage); border-radius: 5px; text-decoration: none; font-size: .78rem; }
        .content { padding: 38px 48px 42px; background: var(--cream); }
        .form-side { display: flex; flex-direction: column; }
        h1 { margin: 0 0 14px; font: 400 2.15rem Georgia, serif; }
        .intro { margin: 0 0 16px; color: var(--muted); font-size: .75rem; line-height: 1.5; }
        label { display: block; margin: 11px 0 5px; font-size: .68rem; font-weight: 700; }
        input { width: 100%; height: 33px; padding: 0 12px; color: var(--ink); background: rgba(255,255,255,.82); border: 0; border-radius: 5px; outline: none; font: .72rem 'Trebuchet MS', sans-serif; }
        input:focus { box-shadow: 0 0 0 2px rgba(52,67,54,.2); }
        button { width: 100%; height: 36px; margin-top: 22px; color: #fff; background: var(--sage); border: 0; border-radius: 5px; cursor: pointer; font: .75rem 'Trebuchet MS', sans-serif; }
        button:hover { background: #263529; }
        .error { display: block; margin-top: 4px; color: #b24d3d; font-size: .65rem; }
        .links { margin-top: 22px; font-size: .68rem; text-align: center; }
        a { color: var(--ink); text-decoration: none; }
        @media (max-width: 520px) { body { padding: 12px; } .shell { border-radius: 10px; } .topbar { padding: 0 22px; } .content { padding: 32px 26px 35px; } .signin span { display: none; } }
    </style>
</head>
<body>
    <main class="shell">
        <header class="topbar">
            <a class="brand" href="{{ route('admin.login') }}">Perpusku</a>
            <div class="signin"><span>Sudah punya akun?</span><a class="toplink" href="{{ route('admin.login') }}">Masuk</a></div>
        </header>
        <section class="content">
            <div class="form-side">
                <h1>Daftar Admin</h1>
                <p class="intro">Buat akun administrator untuk mengelola sistem perpustakaan.</p>
                <form method="POST" action="{{ route('admin.register.attempt') }}" autocomplete="off">
                    @csrf
                    <label for="username">Username</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" placeholder="Masukkan username admin" required maxlength="100" autofocus>
                    @error('username') <span class="error">{{ $message }}</span> @enderror

                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan alamat email" required maxlength="150">
                    @error('email') <span class="error">{{ $message }}</span> @enderror

                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" placeholder="Minimal 8 karakter" required minlength="8">
                    @error('password') <span class="error">{{ $message }}</span> @enderror

                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Ulangi password" required minlength="8">

                    <button type="submit">Daftar Admin</button>
                </form>
                <div class="links"><a href="{{ route('admin.login') }}">Kembali ke halaman masuk</a></div>
            </div>
        </section>
    </main>
</body>
</html>
