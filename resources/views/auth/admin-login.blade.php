<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | Perpusku</title>
    <style>
        :root { --ink: #30322b; --sage: #344336; --cream: #f2efe4; --peach: #dfab93; --sky: #dce4f3; --muted: #77786f; }
        * { box-sizing: border-box; }
        body { min-height: 100vh; margin: 0; padding: 26px; color: var(--ink); background: var(--sky); font-family: 'Trebuchet MS', 'Segoe UI', sans-serif; }
        .shell { width: min(430px, 100%); margin: auto; overflow: hidden; background: #fff; border-radius: 12px; box-shadow: 0 20px 50px rgba(78, 91, 121, .16); }
        .topbar { display: flex; align-items: center; justify-content: space-between; min-height: 64px; padding: 0 32px; border-bottom: 1px solid #eeeade; }
        .brand { color: var(--sage); font: 700 1.2rem Georgia, serif; letter-spacing: -.04em; }
        .brand::first-letter { color: var(--peach); }
        .signup { display: flex; align-items: center; gap: 16px; color: #4e504a; font-size: .76rem; }
        .toplink { padding: 8px 25px; color: #fff; background: var(--sage); border-radius: 5px; text-decoration: none; font-size: .78rem; }
        .content { padding: 42px 48px 44px; background: var(--cream); }
        .form-side { display: flex; flex-direction: column; }
        h1 { margin: 0 0 14px; font: 400 2rem Georgia, serif; }
        .intro { margin: -22px 0 22px; color: var(--muted); font-size: .75rem; line-height: 1.5; }
        label { display: block; margin: 15px 0 6px; font-size: .68rem; font-weight: 700; }
        input { width: 100%; height: 35px; padding: 0 12px; color: var(--ink); background: rgba(255,255,255,.82); border: 0; border-radius: 5px; outline: none; font: .72rem 'Trebuchet MS', sans-serif; }
        input:focus { box-shadow: 0 0 0 2px rgba(52,67,54,.2); }
        .remember { display: flex; align-items: center; gap: 6px; margin-top: 12px; color: var(--muted); font-size: .68rem; font-weight: 400; }
        .remember input { width: 13px; height: 13px; }
        button { width: 100%; height: 36px; margin-top: 25px; color: #fff; background: var(--sage); border: 0; border-radius: 5px; cursor: pointer; font: .75rem 'Trebuchet MS', sans-serif; }
        button:hover { background: #263529; }
        .message { margin-bottom: 13px; padding: 9px 11px; color: #8d4735; background: #f4dcd2; border-radius: 4px; font-size: .7rem; }
        .success { color: #35654c; background: #dceee2; }
        .error { display: block; margin-top: 5px; color: #b24d3d; font-size: .65rem; }
        .forgot { margin-top: 8px; color: var(--muted); font-size: .68rem; text-align: center; text-decoration: none; }
        .bottom-links { display: flex; gap: 18px; margin-top: 26px; font-size: .68rem; }
        a { color: var(--ink); text-decoration: none; }
        .illustration { display: none; }
        .books { position: relative; width: 310px; height: 260px; transform: rotate(-3deg); }
        .book { position: absolute; left: 28px; width: 235px; height: 35px; border-radius: 4px 12px 7px 4px; box-shadow: 0 7px 0 rgba(48,50,43,.08); }
        .book::after { content: ''; position: absolute; right: 13px; top: 9px; width: 42px; height: 4px; background: rgba(255,255,255,.55); border-radius: 4px; }
        .book.one { bottom: 24px; background: #d96f70; transform: rotate(3deg); }
        .book.two { bottom: 57px; left: 43px; background: #e7b83f; transform: rotate(1deg); }
        .book.three { bottom: 92px; left: 20px; background: #4b9a82; transform: rotate(-2deg); }
        .book.four { bottom: 127px; left: 47px; background: #df8758; transform: rotate(2deg); }
        .book.five { bottom: 161px; left: 25px; height: 39px; background: #4d86aa; transform: rotate(-1deg); }
        .plant { position: absolute; right: 3px; bottom: 14px; width: 55px; height: 48px; background: #c88042; border-radius: 8px 8px 18px 18px; }
        .plant::before, .plant::after { content: ''; position: absolute; bottom: 39px; width: 20px; height: 58px; background: #518f72; border-radius: 100% 0 100% 0; transform: rotate(-23deg); }
        .plant::before { left: 3px; }
        .plant::after { right: 3px; transform: rotate(25deg) scaleX(-1); }
        .spark { position: absolute; color: #75c4d5; font-size: 2rem; }
        .spark.one { top: 30px; left: 49px; }
        .spark.two { right: 24px; top: 71px; font-size: 1.4rem; }
        @media (max-width: 520px) { body { padding: 12px; } .shell { border-radius: 10px; } .topbar { padding: 0 22px; } .content { padding: 34px 26px 32px; } .signup span { display: none; } }
    </style>
</head>
<body>
    <main class="shell">
        <header class="topbar">
            <a class="brand" href="{{ route('admin.login') }}">Perpusku</a>
            <div class="signup"><span>Belum punya akun?</span><a class="toplink" href="{{ route('admin.register') }}">Daftar</a></div>
        </header>
        <section class="content">
            <div class="form-side">
                <h1>Masuk</h1>
                <p class="intro">Area terbatas untuk administrator dan petugas perpustakaan.</p>
                @if (session('error')) <div class="message">{{ session('error') }}</div> @endif
                @if (session('success')) <div class="message success">{{ session('success') }}</div> @endif
                <form method="POST" action="{{ route('admin.login.attempt') }}" autocomplete="off">
                    @csrf
                    <label for="username">Username</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" placeholder="Masukkan username Anda" required autofocus>
                    @error('username') <span class="error">{{ $message }}</span> @enderror
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" placeholder="Masukkan password Anda" required minlength="8">
                    @error('password') <span class="error">{{ $message }}</span> @enderror
                    <label class="remember"><input type="checkbox" name="remember"> Ingat saya di perangkat ini</label>
                    <button type="submit">Masuk</button>
                </form>
                <a class="forgot" href="#">Lupa password?</a>
                <div class="bottom-links"><a href="{{ route('admin.register') }}">Buat akun baru</a><a href="{{ route('construction') }}">Tentang sistem</a></div>
            </div>
        </section>
    </main>
</body>
</html>
