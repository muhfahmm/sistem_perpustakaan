<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') | Perpustakaan SMK Al-Islam Surakarta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @stack('vendor-styles')
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
            --teal: #198754;
            --teal-soft: #e8f3ed;
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
        }

        /* Tablet / Mobile (< 1024px) */
        @media (max-width: 1023px) {
            .layout {
                grid-template-columns: 1fr;
            }
            .admin-sidebar {
                display: none;
            }
        }

        .admin-sidebar {
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
            width: 100%;
            height: 100vh;
            min-height: 100vh;
            overflow-y: auto;
            background: #fff;
            border-right: 1px solid var(--line);
        }

        .admin-brand {
            min-height: 64px;
            color: #212529;
            font-size: 1.2rem;
            font-weight: 700;
            text-decoration: none;
        }

        .admin-brand-logo { width: 40px; height: 40px; flex: 0 0 auto; object-fit: contain; border-radius: 8px; background: #fff; }

        .admin-profile {
            border-top: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
        }

        .admin-avatar {
            width: 38px;
            height: 38px;
            color: #495057;
            background: #e9ecef;
        }

        .sidebar-heading {
            color: #6c757d;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .admin-sidebar .nav-link {
            color: #495057;
            border-radius: .375rem;
            font-size: .9rem;
        }

        .admin-sidebar .nav-link:hover {
            color: #146c43;
            background: var(--teal-soft);
        }

        .admin-sidebar .nav-link.active {
            color: #fff;
            background: var(--teal);
        }

        .admin-sidebar .nav-link.text-danger {
            color: #dc3545 !important;
        }

        .admin-sidebar .nav-link.text-danger:hover {
            color: #b02a37 !important;
            background: #f8d7da;
        }

        .admin-sidebar .nav-link i { width: 1.1rem; text-align: center; }
        .admin-mobile-sidebar { --bs-offcanvas-width: 280px; }
        .admin-mobile-sidebar .nav-link { color: #495057; }
        .admin-mobile-sidebar .nav-link:hover { color: #146c43; background: var(--teal-soft); }
        .admin-mobile-sidebar .nav-link.active { color: #fff; background: var(--teal); }
        .admin-mobile-sidebar .nav-link.text-danger { color: #dc3545 !important; }
        .admin-mobile-sidebar .nav-link.text-danger:hover { color: #b02a37 !important; background: #f8d7da; }
        .admin-menu-toggle { display: none; }

        @media (max-width: 1023px) {
            .admin-menu-toggle { display: inline-flex; }
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
            min-height: 56px;
            padding: 0 24px;
            color: #212529;
            background: #fff;
            border-bottom: 1px solid var(--line);
        }

        .topbar-brand-name { color: #212529; font-size: .85rem; font-weight: 700; }
        .topbar-brand-logo { width: 34px; height: 34px; object-fit: contain; border-radius: 6px; background: #fff; }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.88rem;
            font-weight: 600;
        }

        .topbar-avatar {
            display: grid;
            width: 32px;
            height: 32px;
            place-items: center;
            color: #495057;
            background: #e9ecef;
            border-radius: 50%;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
        }

        .main-content {
            padding: 28px 32px 48px;
        }

        .main-content > header { margin-bottom: 24px; }

        .main-content h1 {
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
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
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
        .btn-primary { background: var(--teal); color: #fff; }
        .btn-primary:hover { background: #157347; color: #fff; }
        .btn-success { background: var(--teal); color: #fff; }
        .btn-success:hover { background: #008d4c; }
        .btn.btn-secondary { background: #f1f5f9; color: #334155 !important; border-color: #cbd5e1; }
        .btn.btn-secondary:hover, .btn.btn-secondary:focus-visible { background: #e2e8f0; color: #1f2937 !important; border-color: #94a3b8; }
        .btn.btn-outline-success { color: #198754; border-color: #198754; background: transparent; }
        .btn.btn-outline-success:hover { color: #fff; border-color: #198754; background: #198754; }
        .btn.btn-outline-secondary { color: #475569; border-color: #cbd5e1; background: transparent; }
        .btn.btn-outline-secondary:hover { color: #1f2937; border-color: #adb5bd; background: #f1f5f9; }
        .btn.btn-outline-danger { color: #dc3545; border-color: #dc3545; background: transparent; }
        .btn.btn-outline-danger:hover { color: #fff; border-color: #dc3545; background: #dc3545; }

        table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
        th { padding: 12px 10px; color: #475569; background: #f8fafc; font-size: 0.75rem; font-weight: 700; text-align: left; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #e2e8f0; }
        td { padding: 12px 10px; border-bottom: 1px solid var(--line); }

        .admin-page-heading { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; }
        .admin-page-heading p { margin: 4px 0 0; color: var(--muted); font-size: .88rem; }
        .admin-page-title { margin: 0; color: #0f172a; font-size: 1.55rem; font-weight: 700; letter-spacing: -.025em; }
        .admin-page-subtitle { margin: 5px 0 0; color: var(--muted); font-size: .9rem; }
        .admin-page-card { overflow: hidden; background: #fff; border: 1px solid var(--line); border-radius: 10px; box-shadow: 0 2px 8px rgba(15, 23, 42, .04); }
        .admin-page-card-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; padding: 16px 20px; border-bottom: 1px solid var(--line); }
        .admin-page-card-body { padding: 20px; }
        .admin-table { margin-bottom: 0; vertical-align: middle; }
        .admin-table thead th { color: #64748b; background: #f8faf9; font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .045em; white-space: nowrap; }
        .admin-table td { padding-top: 13px; padding-bottom: 13px; }
        .admin-form-card { max-width: 820px; background: #fff; border: 1px solid var(--line); border-radius: 10px; box-shadow: 0 2px 8px rgba(15, 23, 42, .04); }
        .admin-form-card .form-label { color: #34483d; font-size: .88rem; font-weight: 600; }
        .admin-form-card .form-control, .admin-form-card .form-select { min-height: 42px; border-color: #d8e2dc; border-radius: 7px; }
        .admin-form-card .form-control:focus, .admin-form-card .form-select:focus { border-color: #198754; box-shadow: 0 0 0 .2rem rgba(25, 135, 84, .13); }

        @media (max-width: 767.98px) {
            .main-content { padding: 20px 16px 36px; }
            .topbar { padding: 0 16px; }
            .admin-page-card-header, .admin-page-card-body { padding: 16px; }
            .admin-page-title { font-size: 1.35rem; }
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('vendor-scripts')
    @stack('scripts')
</body>
</html>
