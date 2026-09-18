<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Admin | Perpusku</title>
    <style>
        :root {
            --paper: #ecf0f5;
            --panel: #ffffff;
            --ink: #222d32;
            --muted: #68777d;
            --line: #dfe4e8;
            --teal: #00a65a;
            --teal-dark: #008d4c;
            --blue-main: #3c8dbc;
            --gold: #f6d55c;
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
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
            background-color: var(--paper);
            color: var(--ink);
            padding: 1.5rem;
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
            background: var(--panel);
            border-top: 4px solid var(--teal);
            border-radius: 4px;
            padding: 2.2rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .brand-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 1.25rem;
        }

        .brand-logo-icon {
            width: 36px;
            height: 36px;
            background: var(--teal-dark);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .brand-logo-icon span {
            display: grid;
            width: 22px;
            height: 22px;
            place-items: center;
            color: var(--ink);
            background: var(--gold);
            border-radius: 50%;
            font-size: 0.8rem;
        }

        .brand-name {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--teal-dark);
        }

        .form-header {
            text-align: center;
            margin-bottom: 1.5rem;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--line);
        }

        .form-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #333333;
            margin-bottom: 4px;
        }

        .form-subtitle {
            font-size: 0.82rem;
            color: var(--muted);
        }

        .alert-box {
            padding: 10px 14px;
            border-radius: 3px;
            font-size: 0.82rem;
            margin-bottom: 1.25rem;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .form-group {
            margin-bottom: 1.1rem;
        }

        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #333333;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .form-input {
            width: 100%;
            height: 40px;
            padding: 0 12px;
            font-family: inherit;
            font-size: 0.85rem;
            color: var(--ink);
            background: #ffffff;
            border: 1px solid #d2d6de;
            border-radius: 3px;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            border-color: var(--blue-main);
            box-shadow: 0 0 0 2px rgba(60, 141, 188, 0.2);
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            background: none;
            border: none;
            color: var(--muted);
            cursor: pointer;
            padding: 4px;
            font-size: 0.78rem;
            font-weight: 600;
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

        .btn-submit {
            width: 100%;
            height: 42px;
            background: var(--teal);
            color: #ffffff;
            border: none;
            border-radius: 3px;
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-submit:hover {
            background: var(--teal-dark);
        }

        .error-message {
            font-size: 0.76rem;
            color: #dd4b39;
            margin-top: 4px;
            display: block;
        }

        .form-footer {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.83rem;
            color: var(--muted);
        }

        .form-footer a {
            color: var(--blue-main);
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
                <span>P</span>
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
                    <input id="username" type="text" name="username" class="form-input" value="{{ old('username') }}" placeholder="Masukkan username admin" required autofocus>
                </div>
                @error('username') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <!-- PASSWORD -->
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-wrapper">
                    <input id="password" type="password" name="password" class="form-input" placeholder="Masukkan password" required minlength="8">
                    <button type="button" class="toggle-password" onclick="toggleVisibility('password', this)">Lihat</button>
                </div>
                @error('password') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-options">
                <label class="remember-me">
                    <input type="checkbox" name="remember">
                    <span>Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="btn-submit">Masuk</button>
        </form>

        <div class="form-footer">
            Belum punya akun admin? <a href="{{ route('admin.register') }}">Daftar</a>
        </div>
    </main>

    <script>
        function toggleVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                btn.innerText = 'Sembunyikan';
            } else {
                input.type = 'password';
                btn.innerText = 'Lihat';
            }
        }
    </script>
</body>
</html>
