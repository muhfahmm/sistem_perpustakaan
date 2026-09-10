<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Admin | Perpusku</title>
    <!-- Google Fonts: Plus Jakarta Sans & Fraunces -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400..700;1,9..144,400..700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-page: #f8fafc;
            --navy-dark: #0f172a;
            --accent-gold: #d97706;
            --accent-soft: rgba(217, 119, 6, 0.12);
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --font-serif: 'Fraunces', Georgia, serif;
            --font-sans: 'Plus Jakarta Sans', -apple-system, sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-sans);
            background-color: var(--bg-page);
            color: var(--text-main);
            padding: 1.5rem;
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 16px;
            padding: 2.5rem 2rem;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            border: 1px solid #f1f5f9;
        }

        .brand-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 1.5rem;
        }

        .brand-logo-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--accent-gold), #b45309);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.3);
        }

        .brand-name {
            font-family: var(--font-serif);
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--navy-dark);
            letter-spacing: -0.02em;
        }

        .form-header {
            text-align: center;
            margin-bottom: 1.75rem;
        }

        .form-title {
            font-family: var(--font-serif);
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--navy-dark);
            margin-bottom: 0.35rem;
        }

        .form-subtitle {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .alert-box {
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.82rem;
            margin-bottom: 1.25rem;
        }

        .alert-error {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .form-group {
            margin-bottom: 1.15rem;
        }

        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.35rem;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            color: #94a3b8;
            pointer-events: none;
            transition: color 0.2s;
        }

        .form-input {
            width: 100%;
            height: 44px;
            padding: 0 14px 0 42px;
            font-family: var(--font-sans);
            font-size: 0.88rem;
            color: var(--text-main);
            background: #f8fafc;
            border: 1.5px solid var(--border-color);
            border-radius: 10px;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            background: #ffffff;
            border-color: var(--accent-gold);
            box-shadow: 0 0 0 3.5px var(--accent-soft);
        }

        .form-input:focus + .input-icon,
        .input-wrapper:focus-within .input-icon {
            color: var(--accent-gold);
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 0.5rem;
            margin-bottom: 1.25rem;
            font-size: 0.8rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #475569;
            cursor: pointer;
        }

        .remember-me input {
            accent-color: var(--accent-gold);
        }

        .forgot-link {
            color: var(--text-muted);
            text-decoration: none;
        }

        .forgot-link:hover {
            color: var(--accent-gold);
            text-decoration: underline;
        }

        .btn-submit {
            width: 100%;
            height: 46px;
            background: var(--navy-dark);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-family: var(--font-sans);
            font-size: 0.92rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            background: #1e293b;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.2);
        }

        .error-message {
            font-size: 0.76rem;
            color: #ef4444;
            margin-top: 4px;
            display: block;
        }

        .form-footer {
            margin-top: 1.75rem;
            text-align: center;
            font-size: 0.83rem;
            color: var(--text-muted);
        }

        .form-footer a {
            color: var(--accent-gold);
            font-weight: 600;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <main class="auth-card">
        <div class="brand-header">
            <div class="brand-logo-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
            </div>
            <span class="brand-name">Perpusku</span>
        </div>

        <div class="form-header">
            <h1 class="form-title">Masuk Admin</h1>
            <p class="form-subtitle">Masukkan username dan password admin Anda</p>
        </div>

        @if (session('error'))
            <div class="alert-box alert-error">{{ session('error') }}</div>
        @endif

        @if (session('success'))
            <div class="alert-box alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.attempt') }}" autocomplete="off">
            @csrf

            <!-- USERNAME -->
            <div class="form-group">
                <label for="username" class="form-label">Username Admin</label>
                <div class="input-wrapper">
                    <span class="input-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </span>
                    <input id="username" type="text" name="username" class="form-input" value="{{ old('username') }}" placeholder="Masukkan username" required autofocus>
                </div>
                @error('username') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <!-- PASSWORD -->
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-wrapper">
                    <span class="input-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </span>
                    <input id="password" type="password" name="password" class="form-input" placeholder="Masukkan password" required minlength="8">
                    <button type="button" class="toggle-password" onclick="toggleVisibility('password', this)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                </div>
                @error('password') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-options">
                <label class="remember-me">
                    <input type="checkbox" name="remember">
                    <span>Ingat saya</span>
                </label>
                <a href="#" class="forgot-link">Lupa password?</a>
            </div>

            <button type="submit" class="btn-submit">Masuk</button>
        </form>

        <div class="form-footer">
            Belum punya akun? <a href="{{ route('admin.register') }}">Daftar</a>
        </div>
    </main>

    <script>
        function toggleVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                btn.style.color = 'var(--accent-gold)';
            } else {
                input.type = 'password';
                btn.style.color = '#94a3b8';
            }
        }
    </script>
</body>
</html>
