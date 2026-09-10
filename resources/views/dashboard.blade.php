<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | Perpusku</title>
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

        /* SIDEBAR */
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

        nav {
            display: grid;
            gap: 0;
        }

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

        nav form {
            margin: 0;
        }

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

        /* MAIN AREA */
        main {
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        /* TOPBAR (NO V1.0 TEXT, SHOWS ACTIVE ADMIN USERNAME) */
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

        header {
            margin-bottom: 24px;
        }

        h1 {
            margin: 0;
            color: #333;
            font-size: 1.6rem;
            font-weight: 600;
        }

        /* STATS BOXES */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat {
            position: relative;
            min-height: 110px;
            padding: 16px;
            color: #fff;
            border-radius: 3px;
            overflow: hidden;
            background: var(--blue-main);
        }

        .stat:nth-child(2) { background: #f39c12; }
        .stat:nth-child(3) { background: #00a65a; }
        .stat:nth-child(4) { background: #dd4b39; }

        .stat-value {
            margin: 0 0 4px;
            font-size: 2rem;
            font-weight: 700;
        }

        .stat-label {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .stat-footer {
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            padding: 4px 10px;
            color: rgba(255, 255, 255, 0.85);
            background: rgba(0, 0, 0, 0.12);
            font-size: 0.72rem;
            text-align: center;
        }

        /* CONTENT GRID */
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

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

        h2 {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 600;
            color: #333;
        }

        .panel-link {
            color: var(--blue-main);
            font-size: 0.78rem;
            text-decoration: none;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }

        th {
            padding: 8px;
            color: #555;
            background: #f4f5f7;
            font-size: 0.72rem;
            font-weight: 600;
            text-align: left;
            text-transform: uppercase;
        }

        td {
            padding: 12px 8px;
            border-bottom: 1px solid var(--line);
        }

        .book { font-weight: 600; color: #222; }
        .borrower { display: block; margin-top: 2px; color: var(--muted); font-size: 0.75rem; }

        .status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        .status.pending { color: #966313; background: var(--yellow-soft); }
        .status.borrowed { color: #3d6484; background: var(--blue-soft); }
        .status.returned, .status.approved { color: #327469; background: var(--teal-soft); }
        .status.overdue, .status.lost, .status.rejected { color: #9b3d35; background: #fbe6e3; }

        .empty-state {
            padding: 24px 0;
            color: var(--muted);
            text-align: center;
        }

        .quick-actions { display: grid; gap: 8px; }

        .action {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            color: var(--ink);
            border: 1px solid var(--line);
            border-radius: 3px;
            font-size: 0.82rem;
            text-decoration: none;
            background: #fafafa;
        }

        .action:hover {
            border-color: var(--blue-main);
            background: #f0f7fb;
        }

        .action-icon {
            color: var(--orange);
            font-weight: 700;
        }

        .notice {
            margin-top: 16px;
            padding: 14px;
            color: #73562c;
            background: var(--yellow-soft);
            border-left: 3px solid var(--orange);
            font-size: 0.78rem;
            line-height: 1.4;
        }

        @media (max-width: 900px) {
            .layout { grid-template-columns: 1fr; }
            aside { display: none; }
            .stats { grid-template-columns: repeat(2, 1fr); }
            .content-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    @php
        /** @var \App\Models\Admin|null $currentAdmin */
        $currentAdmin = auth('admin')->user();
        $adminUsername = $currentAdmin->username ?? 'Admin';
        $adminInitial = strtoupper(substr($adminUsername, 0, 1));
    @endphp

    <div class="layout">
        <!-- SIDEBAR -->
        <aside>
            <div class="brand">
                <span class="brand-mark">P</span> Perpusku
            </div>

            <div class="profile">
                <span class="profile-avatar">{{ $adminInitial }}</span>
                <div>
                    <div class="profile-name">{{ $adminUsername }}</div>
                    <span class="profile-role">Administrator</span>
                </div>
            </div>

            <nav aria-label="Navigasi admin">
                <div class="nav-label">Main navigation</div>
                <a class="active" href="{{ route('admin.dashboard') }}"><span class="icon">⌂</span> Dashboard</a>
                <a href="#"><span class="icon">▤</span> Manajemen Buku</a>
                <a href="#"><span class="icon">♙</span> Manajemen User</a>
                <a href="#"><span class="icon">↔</span> Peminjaman</a>
                <a href="#"><span class="icon">✓</span> Pengembalian</a>
                <a href="#"><span class="icon">▦</span> QR Code</a>
                <a href="#"><span class="icon">◌</span> Notifikasi WA</a>
                <a href="#"><span class="icon">▥</span> Laporan</a>

                <div class="nav-label">Setting</div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"><span class="icon">↪</span> Logout</button>
                </form>
            </nav>

            <div class="sidebar-footer">
                Panel admin<br>Perpustakaan digital
            </div>
        </aside>

        <!-- MAIN AREA -->
        <main>
            <!-- TOPBAR (CLEAN, SHOWS ACTIVE ADMIN USERNAME) -->
            <div class="topbar">
                <div class="topbar-brand-sub">Panel Administrasi Perpustakaan</div>
                <div class="topbar-user">
                    <span>{{ $adminUsername }}</span>
                    <span class="topbar-avatar">{{ $adminInitial }}</span>
                </div>
            </div>

            <!-- MAIN CONTENT -->
            <div class="main-content">
                <header>
                    <h1>Dashboard Admin</h1>
                </header>

                <!-- STATS BOXES -->
                <section class="stats" aria-label="Statistik perpustakaan">
                    <article class="stat">
                        <div class="stat-value">{{ number_format($stats['books']) }}</div>
                        <div class="stat-label">Total Koleksi Buku</div>
                        <div class="stat-footer">Data Perpustakaan</div>
                    </article>
                    <article class="stat">
                        <div class="stat-value">{{ number_format($stats['activeLoans']) }}</div>
                        <div class="stat-label">Sedang Dipinjam</div>
                        <div class="stat-footer">Data Perpustakaan</div>
                    </article>
                    <article class="stat">
                        <div class="stat-value">{{ number_format($stats['activeMembers']) }}</div>
                        <div class="stat-label">Anggota Aktif</div>
                        <div class="stat-footer">Data Perpustakaan</div>
                    </article>
                    <article class="stat">
                        <div class="stat-value">{{ number_format($stats['overdueLoans']) }}</div>
                        <div class="stat-label">Terlambat Dikembalikan</div>
                        <div class="stat-footer">Data Perpustakaan</div>
                    </article>
                </section>

                <div class="content-grid">
                    <!-- RECENT LOANS -->
                    <section class="panel">
                        <div class="panel-heading">
                            <h2>Peminjaman Terbaru</h2>
                            <a class="panel-link" href="#">Lihat semua</a>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Buku & Peminjam</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentLoans as $loan)
                                    @php
                                        $statusLabels = [
                                            'pending' => 'Menunggu',
                                            'approved' => 'Disetujui',
                                            'borrowed' => 'Dipinjam',
                                            'returned' => 'Dikembalikan',
                                            'overdue' => 'Terlambat',
                                            'rejected' => 'Ditolak',
                                            'lost' => 'Hilang',
                                        ];
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="book">{{ $loan->title }}</span>
                                            <span class="borrower">{{ $loan->borrower }}</span>
                                        </td>
                                        <td>{{ date('d M Y', strtotime($loan->loan_date)) }}</td>
                                        <td>
                                            <span class="status {{ $loan->status }}">
                                                {{ $statusLabels[$loan->status] ?? ucfirst($loan->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="empty-state">Belum ada data peminjaman.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </section>

                    <!-- QUICK ACTIONS -->
                    <section class="panel">
                        <div class="panel-heading">
                            <h2>Aksi Cepat</h2>
                        </div>
                        <div class="quick-actions">
                            <a class="action" href="#"><span class="action-icon">+</span> Tambah buku baru</a>
                            <a class="action" href="#"><span class="action-icon">✓</span> Tinjau pengajuan</a>
                            <a class="action" href="#"><span class="action-icon">▦</span> Buat QR Code</a>
                            <a class="action" href="#"><span class="action-icon">↓</span> Unduh laporan</a>
                        </div>
                        <div class="notice">
                            <strong>{{ number_format($stats['overdueLoans']) }} pinjaman terlambat.</strong><br>
                            Data ditampilkan langsung dari database perpustakaan.
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
