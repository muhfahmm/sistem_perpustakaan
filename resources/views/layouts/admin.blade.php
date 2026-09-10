<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') | Perpusku</title>
    <style>
        :root {
            --ink: #263238;
            --muted: #68777d;
            --paper: #ecf0f5;
            --panel: #ffffff;
            --line: #dfe4e8;
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
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
        }

        .layout {
            display: grid;
            grid-template-columns: 230px 1fr;
            min-height: 100vh;
        }

        aside {
            display: flex;
            flex-direction: column;
            padding: 0 0 20px;
            color: #eef8f4;
            background: #222d32;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            height: 50px;
            margin: 0;
            padding: 0 20px;
            color: #fff;
            background: #008d4c;
            font: 600 1.15rem 'Segoe UI', sans-serif;
        }

        .brand-mark {
            display: grid;
            width: 30px;
            height: 30px;
            place-items: center;
            color: #fff;
            background: #f6d55c;
            border-radius: 50%;
            font: 700 0.95rem 'Segoe UI', sans-serif;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 15px;
            background: #1e282c;
        }

        .profile-avatar {
            display: grid;
            width: 38px;
            height: 38px;
            place-items: center;
            color: #222d32;
            background: #f5d76e;
            border: 2px solid #dcecf0;
            border-radius: 50%;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
        }

        .profile-name {
            color: #fff;
            font-size: 0.82rem;
            font-weight: 600;
        }

        .profile-role {
            display: inline-block;
            margin-top: 3px;
            padding: 2px 6px;
            color: #fff;
            background: #f39c12;
            border-radius: 2px;
            font-size: 0.65rem;
        }

        .nav-label {
            padding: 12px 15px 8px;
            color: #6d858c;
            background: #1b2529;
            font-size: 0.65rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        nav { display: grid; gap: 0; }

        nav a {
            display: flex;
            align-items: center;
            gap: 11px;
            min-height: 42px;
            padding: 0 20px;
            color: #c7d1d4;
            font-size: 0.82rem;
            text-decoration: none;
        }

        nav a.active, nav a:hover {
            color: #fff;
            background: #1b2529;
        }

        nav .icon {
            width: 18px;
            color: #c7d1d4;
            text-align: center;
            font-weight: 700;
        }

        nav form { margin: 0; }

        nav form button {
            display: flex;
            align-items: center;
            gap: 11px;
            width: 100%;
            min-height: 42px;
            padding: 0 20px;
            color: #c7d1d4;
            background: transparent;
            border: 0;
            cursor: pointer;
            font: inherit;
            font-size: 0.82rem;
            text-align: left;
        }

        nav form button:hover {
            color: #fff;
            background: #1b2529;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 16px 20px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #8aa4af;
            font-size: 0.75rem;
            line-height: 1.4;
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
            height: 50px;
            padding: 0 30px;
            background: #00a65a;
            color: #fff;
        }

        .topbar-brand-sub {
            font-size: 0.85rem;
            font-weight: 600;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .topbar-avatar {
            display: grid;
            width: 28px;
            height: 28px;
            place-items: center;
            color: #008d4c;
            background: #ffffff;
            border-radius: 50%;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
        }

        .main-content {
            padding: 24px 30px 40px;
        }

        header { margin-bottom: 24px; }

        h1 {
            margin: 0;
            color: #333;
            font-size: 1.6rem;
            font-weight: 600;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 3px;
            font-size: 0.85rem;
            margin-bottom: 20px;
        }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        .panel {
            background: var(--panel);
            border-top: 3px solid #d2d6de;
            border-radius: 3px;
            padding: 18px;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        }

        .panel-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--line);
        }

        h2 { margin: 0; font-size: 1.05rem; font-weight: 600; color: #333; }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 3px;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
        }
        .btn-primary { background: var(--blue-main); color: #fff; }
        .btn-primary:hover { background: #357ca5; }
        .btn-success { background: var(--teal); color: #fff; }
        .btn-success:hover { background: #008d4c; }
        .btn-secondary { background: #e4e7ea; color: #333; border-color: #d2d6de; }

        table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
        th { padding: 10px 8px; color: #555; background: #f4f5f7; font-size: 0.72rem; font-weight: 600; text-align: left; text-transform: uppercase; }
        td { padding: 12px 8px; border-bottom: 1px solid var(--line); }

        @media (max-width: 900px) {
            .layout { grid-template-columns: 1fr; }
            aside { display: none; }
        }
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
