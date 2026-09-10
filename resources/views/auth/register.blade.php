<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Admin | Perpusku</title>
    <!-- Google Fonts: Plus Jakarta Sans & Fraunces -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400..700;1,9..144,400..700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #0f172a;
            --navy-card: #1e293b;
            --accent-gold: #d97706;
            --accent-hover: #b45309;
            --accent-soft: rgba(217, 119, 6, 0.12);
            --surface-light: #f8fafc;
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
            font-family: var(--font-sans);
            background-color: #f1f5f9;
            color: var(--text-main);
            overflow-x: hidden;
        }

        .page-container {
            display: flex;
            width: 100vw;
            min-height: 100vh;
        }

        /* LEFT HERO PANEL (60% width) */
        .hero-panel {
            flex: 1.2;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #1e3a5f 100%);
            color: #ffffff;
            padding: 4rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        /* Ambient Glow & Decorative Elements */
        .hero-panel::before {
            content: '';
            position: absolute;
            top: -20%;
            left: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(217, 119, 6, 0.25) 0%, rgba(0,0,0,0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-panel::after {
            content: '';
            position: absolute;
            bottom: -15%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(30, 58, 95, 0.6) 0%, rgba(0,0,0,0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-header {
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 2;
        }

        .brand-logo-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--accent-gold), #b45309);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px rgba(217, 119, 6, 0.35);
        }

        .brand-title {
            font-family: var(--font-serif);
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: #ffffff;
        }

        .hero-body {
            z-index: 2;
            max-width: 540px;
            margin: auto 0;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 30px;
            font-size: 0.82rem;
            font-weight: 500;
            color: #f1f5f9;
            margin-bottom: 1.8rem;
        }

        .hero-badge-dot {
            width: 8px;
            height: 8px;
            background-color: var(--accent-gold);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--accent-gold);
        }

        .hero-headline {
            font-family: var(--font-serif);
            font-size: 2.8rem;
            font-weight: 600;
            line-height: 1.2;
            letter-spacing: -0.02em;
            color: #ffffff;
            margin-bottom: 1.2rem;
        }

        .hero-subtext {
            font-size: 1rem;
            line-height: 1.6;
            color: #94a3b8;
            margin-bottom: 2.5rem;
        }

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.95rem;
            color: #cbd5e1;
        }

        .feature-icon {
            width: 26px;
            height: 26px;
            background: rgba(217, 119, 6, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-gold);
            flex-shrink: 0;
        }

        .hero-footer {
            z-index: 2;
            font-size: 0.85rem;
            color: #64748b;
        }

        /* RIGHT FORM PANEL (40% width) */
        .form-panel {
            flex: 1;
            background: #ffffff;
            padding: 4rem 3.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
        }

        .form-container {
            max-width: 420px;
            width: 100%;
            margin: 0 auto;
        }

        .form-header {
            margin-bottom: 2rem;
        }

        .form-title {
            font-family: var(--font-serif);
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            font-size: 0.88rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.4rem;
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
            transition: color 0.2s;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            height: 46px;
            padding: 0 14px 0 44px;
            font-family: var(--font-sans);
            font-size: 0.9rem;
            color: var(--text-main);
            background: #f8fafc;
            border: 1.5px solid var(--border-color);
            border-radius: 10px;
            outline: none;
            transition: all 0.2s ease-in-out;
        }

        .form-input:focus {
            background: #ffffff;
            border-color: var(--accent-gold);
            box-shadow: 0 0 0 4px var(--accent-soft);
        }

        .form-input:focus + .input-icon,
        .input-wrapper:focus-within .input-icon {
            color: var(--accent-gold);
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: var(--text-main);
        }

        /* Password Strength Bar */
        .strength-meter {
            height: 4px;
            width: 100%;
            background: #e2e8f0;
            border-radius: 2px;
            margin-top: 6px;
            overflow: hidden;
            display: flex;
        }

        .strength-bar {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease, background-color 0.3s ease;
        }

        .btn-submit {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, var(--bg-dark), var(--navy-card));
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-family: var(--font-sans);
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
            margin-top: 1.5rem;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #1e293b, #0f172a);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.25);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .error-message {
            display: block;
            font-size: 0.78rem;
            color: #ef4444;
            margin-top: 4px;
        }

        .form-footer {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .form-footer a {
            color: var(--accent-gold);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        .form-footer a:hover {
            color: var(--accent-hover);
            text-decoration: underline;
        }

        /* Responsive Breakpoints */
        @media (max-width: 960px) {
            .hero-panel {
                display: none;
            }
            .form-panel {
                padding: 3rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <!-- LEFT HERO PANEL -->
        <section class="hero-panel">
            <div class="hero-header">
                <div class="brand-logo-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                </div>
                <span class="brand-title">Perpusku</span>
            </div>

            <div class="hero-body">
                <div class="hero-badge">
                    <span class="hero-badge-dot"></span>
                    <span>Portal Administrator Perpusku</span>
                </div>
                <h1 class="hero-headline">Kelola Koleksi & Peminjaman Tanpa Hambatan.</h1>
                <p class="hero-subtext">Sistem manajemen perpustakaan modern yang terintegrasi penuh. Pantau riwayat buku, peminjam, dan sirkulasi digital dari satu portal presisi.</p>

                <div class="feature-list">
                    <div class="feature-item">
                        <div class="feature-icon">✓</div>
                        <span>Manajemen katalog & stok buku real-time</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">✓</div>
                        <span>Verifikasi QR Code peminjaman cepat</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">✓</div>
                        <span>Notifikasi otomatis via WhatsApp & Email</span>
                    </div>
                </div>
            </div>

            <div class="hero-footer">
                &copy; {{ date('Y') }} Perpusku System. Hak Cipta Dilindungi.
            </div>
        </section>

        <!-- RIGHT FORM PANEL -->
        <main class="form-panel">
            <div class="form-container">
                <div class="form-header">
                    <h2 class="form-title">Daftar Admin</h2>
                    <p class="form-subtitle">Buat akun administrator baru untuk mengelola sistem.</p>
                </div>

                <form method="POST" action="{{ route('admin.register.attempt') }}" autocomplete="off">
                    @csrf

                    <!-- USERNAME -->
                    <div class="form-group">
                        <label for="username" class="form-label">Username Admin</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            </span>
                            <input id="username" type="text" name="username" class="form-input" value="{{ old('username') }}" placeholder="cth: admin_perpus" required maxlength="100" autofocus>
                        </div>
                        @error('username') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <!-- EMAIL -->
                    <div class="form-group">
                        <label for="email" class="form-label">Alamat Email</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            </span>
                            <input id="email" type="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="admin@perpusku.id" required maxlength="150">
                        </div>
                        @error('email') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <!-- PASSWORD -->
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            </span>
                            <input id="password" type="password" name="password" class="form-input" placeholder="Minimal 8 karakter" required minlength="8" oninput="checkStrength(this.value)">
                            <button type="button" class="toggle-password" onclick="toggleVisibility('password', this)" title="Tampilkan/Sembunyikan Password">
                                <svg class="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </button>
                        </div>
                        <div class="strength-meter"><div id="strength-bar" class="strength-bar"></div></div>
                        @error('password') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <!-- PASSWORD CONFIRMATION -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                            </span>
                            <input id="password_confirmation" type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password" required minlength="8">
                            <button type="button" class="toggle-password" onclick="toggleVisibility('password_confirmation', this)" title="Tampilkan/Sembunyikan Password">
                                <svg class="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </button>
                        </div>
                    </div>

                    <!-- SUBMIT BUTTON -->
                    <button type="submit" class="btn-submit">
                        <span>Daftar Sekarang</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </button>
                </form>

                <div class="form-footer">
                    Sudah memiliki akun admin? <a href="{{ route('admin.login') }}">Masuk di sini</a>
                </div>
            </div>
        </main>
    </div>

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

        function checkStrength(val) {
            const bar = document.getElementById('strength-bar');
            if (val.length === 0) {
                bar.style.width = '0%';
            } else if (val.length < 6) {
                bar.style.width = '25%';
                bar.style.backgroundColor = '#ef4444';
            } else if (val.length < 8) {
                bar.style.width = '50%';
                bar.style.backgroundColor = '#f59e0b';
            } else if (/[A-Z]/.test(val) && /[0-9]/.test(val)) {
                bar.style.width = '100%';
                bar.style.backgroundColor = '#10b981';
            } else {
                bar.style.width = '75%';
                bar.style.backgroundColor = '#d97706';
            }
        }
    </script>
</body>
</html>
