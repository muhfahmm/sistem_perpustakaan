<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') | Perpusku</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #1e293b;
            --muted: #64748b;
            --paper: #f1f5f9;
            --panel: #ffffff;
            --line: #e2e8f0;
            --teal: #00a65a;
            --teal-soft: #e3f2ef;
            --orange: #f39c12;
            --yellow-soft: #fff2d9;
            --blue-soft: #e8f0f8;
            --red: #dd4b39;
            --blue-main: #3c8dbc;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            color: var(--ink);
            background: var(--paper);
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .layout {
            display: grid;
            grid-template-columns: 260px 1fr; /* Default Desktop */
            min-height: 100vh;
        }

        /* Large Widescreen Desktop (> 1400px): Wider sidebar */
        @media (min-width: 1400px) {
            .layout {
                grid-template-columns: 275px 1fr;
            }
        }

        /* Laptop Screens (1024px - 1399px): Slightly smaller compact sidebar */
        @media (max-width: 1399px) and (min-width: 1024px) {
            .layout {
                grid-template-columns: 220px 1fr;
            }
            nav a, nav form button {
                padding: 0 14px !important;
                font-size: 0.83rem !important;
            }
            .nav-label {
                padding-left: 14px !important;
            }
            .brand {
                padding: 0 14px !important;
            }
            .profile {
                padding: 12px 14px !important;
            }
        }

        /* Tablet / Mobile (< 1024px) */
        @media (max-width: 1023px) {
            .layout {
                grid-template-columns: 1fr;
            }
            aside {
                display: none;
            }
        }

        aside {
            display: flex;
            flex-direction: column;
            padding: 0 0 20px;
            color: #e2e8f0;
            background: #1e293b; /* Dark Navy Slate */
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            height: 56px;
            margin: 0;
            padding: 0 20px;
            color: #ffffff;
            background: #008d4c;
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.01em;
        }

        .brand-mark {
            display: grid;
            width: 32px;
            height: 32px;
            place-items: center;
            color: #1e293b;
            background: #f6d55c;
            border-radius: 50%;
            font-weight: 800;
            font-size: 1rem;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            background: #111827;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .profile-avatar {
            display: grid;
            width: 40px;
            height: 40px;
            place-items: center;
            color: #111827;
            background: #f5d76e;
            border: 2px solid #ffffff;
            border-radius: 50%;
            font-weight: 800;
            font-size: 1.05rem;
            text-transform: uppercase;
        }

        .profile-name {
            color: #ffffff;
            font-size: 0.9rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .profile-role {
            display: inline-block;
            margin-top: 4px;
            padding: 2px 8px;
            color: #ffffff;
            background: #d97706;
            border-radius: 4px;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.03em;
        }

        .nav-label {
            padding: 16px 20px 6px;
            color: #94a3b8; /* Clear readable slate color */
            background: #182232;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        nav { display: grid; gap: 0; }

        nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 44px;
            padding: 0 20px;
            color: #cbd5e1;
            font-size: 0.88rem;
            font-weight: 500;
            text-decoration: none;
            border-left: 4px solid transparent;
            transition: all 0.15s ease-in-out;
        }

        nav a.active, nav a:hover {
            color: #ffffff;
            background: #0f172a;
            font-weight: 600;
            border-left-color: var(--teal);
        }

        nav .icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            font-size: 1.05rem;
            text-align: center;
            opacity: 0.9;
        }

        nav form { margin: 0; }

        nav form button {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            min-height: 44px;
            padding: 0 20px;
            color: #cbd5e1;
            background: transparent;
            border: 0;
            border-left: 4px solid transparent;
            cursor: pointer;
            font: inherit;
            font-size: 0.88rem;
            font-weight: 500;
            text-align: left;
            transition: all 0.15s ease-in-out;
        }

        nav form button:hover {
            color: #f87171;
            background: #0f172a;
            font-weight: 600;
            border-left-color: #ef4444;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 18px 20px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            color: #64748b;
            font-size: 0.75rem;
            line-height: 1.5;
            font-weight: 500;
        }

        main {
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 56px;
            padding: 0 30px;
            background: #00a65a;
            color: #fff;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
        }

        .topbar-brand-sub {
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.88rem;
            font-weight: 700;
        }

        .topbar-avatar {
            display: grid;
            width: 32px;
            height: 32px;
            place-items: center;
            color: #008d4c;
            background: #ffffff;
            border-radius: 50%;
            font-weight: 800;
            font-size: 0.85rem;
            text-transform: uppercase;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .main-content {
            padding: 28px 32px 48px;
        }

        header { margin-bottom: 24px; }

        h1 {
            margin: 0;
            color: #0f172a;
            font-size: 1.65rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            font-size: 0.88rem;
            font-weight: 500;
            margin-bottom: 20px;
        }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        .panel {
            background: var(--panel);
            border-top: 4px solid #3c8dbc;
            border-radius: 6px;
            padding: 20px 22px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        }

        .panel-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--line);
        }

        h2 { margin: 0; font-size: 1.1rem; font-weight: 700; color: #0f172a; }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 0.83rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.15s ease;
        }
        .btn-primary { background: var(--blue-main); color: #fff; }
        .btn-primary:hover { background: #357ca5; }
        .btn-success { background: var(--teal); color: #fff; }
        .btn-success:hover { background: #008d4c; }
        .btn-secondary { background: #f1f5f9; color: #334155; border-color: #cbd5e1; }
        .btn-secondary:hover { background: #e2e8f0; }

        table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
        th { padding: 12px 10px; color: #475569; background: #f8fafc; font-size: 0.75rem; font-weight: 700; text-align: left; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid #e2e8f0; }
        td { padding: 14px 10px; border-bottom: 1px solid var(--line); }
    </style>
    @stack('styles')
</head>
<body>
    <div class="layout">
        @include('layouts.partials.sidebar')

        <main>
            @include('layouts.partials.topbar')

            <div class="main-content">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
