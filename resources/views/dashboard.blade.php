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
            nav form { margin: 0; }

            nav form button {
                display: flex;
                align-items: center;
                gap: 11px;
                width: 100%;
                min-height: 43px;
                padding: 0 20px;
                color: #c7d1d4;
                background: transparent;
                border: 0;
                cursor: pointer;
                font: inherit;
                text-align: left;
            }

            nav form button:hover { color: #fff; background: #1b2529; }
            --blue-soft: #e8f0f8;
            --red: #dd4b39;
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
            height: 48px;
            margin: 0;
            padding: 0 20px;
            color: #fff;
            background: #008d4c;
            font: 600 1.2rem 'Segoe UI', sans-serif;
        }

        .brand-mark {
            display: grid;
            width: 32px;
            height: 32px;
            place-items: center;
            color: #fff;
            background: #f6d55c;
            border-radius: 50%;
            font: 700 1rem 'Segoe UI', sans-serif;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px 14px;
            background: #1e282c;
        }

        .profile-avatar {
            display: grid;
            width: 42px;
            height: 42px;
            place-items: center;
            color: #222d32;
            background: #f5d76e;
            border: 3px solid #dcecf0;
            border-radius: 50%;
            font-size: 1.15rem;
        }

        .profile-name {
            color: #fff;
            font-size: .78rem;
            font-weight: 600;
        }

        .profile-role {
            display: inline-block;
            margin-top: 5px;
            padding: 3px 6px;
            color: #fff;
            background: #f39c12;
            font-size: .62rem;
        }

        .nav-label {
            padding: 12px 15px 8px;
            color: #6d858c;
            background: #1b2529;
            font-size: .62rem;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        nav { display: grid; gap: 0; padding-top: 42px; }

        nav a {
            display: flex;
            align-items: center;
            gap: 11px;
            min-height: 43px;
            padding: 0 20px;
            color: #c7d1d4;
            border-radius: 0;
            font-size: .8rem;
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

        nav a::after {
            margin-left: auto;
            color: #c7d1d4;
            font-size: .95rem;
        }

        nav a:nth-child(2)::after,
        nav a:nth-child(4)::after,
        nav a:nth-child(6)::after,
        nav a:nth-child(7)::after,
        nav a:nth-child(8)::after { content: '‹'; transform: rotate(180deg); }

        .sidebar-footer {
            margin-top: auto;
            margin: 24px 0 0;
            padding: 18px 20px 0;
            border-top: 1px solid rgba(255, 255, 255, .14);
            color: #aebfc1;
            font-size: .75rem;
            line-height: 1.5;
        }

        main {
            min-width: 0;
            padding: 0 clamp(30px, 4vw, 58px) 50px;
        }

        main::before {
            content: 'Sistem Informasi Perpustakaan Berbasis Web v1.0';
            display: flex;
            align-items: center;
            justify-content: flex-end;
            height: 48px;
            margin: 0 calc(clamp(30px, 4vw, 58px) * -1) 32px;
            padding: 0 clamp(18px, 3vw, 32px);
            color: #fff;
            background: #00a65a;
            font-size: .76rem;
            font-weight: 600;
        }

        header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 26px;
        }

        .eyebrow {
            margin: 0 0 8px;
            display: none;
        }

        h1 {
            margin: 0;
            color: #333;
            font: 400 clamp(1.55rem, 2.5vw, 2rem)/1.2 'Segoe UI', sans-serif;
        }

        .admin {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #536167;
            font-size: .82rem;
        }

        .avatar {
            display: grid;
            width: 38px;
            height: 38px;
            place-items: center;
            color: #fff;
            background: #3c8dbc;
            border-radius: 50%;
            font-weight: 700;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            margin-bottom: 28px;
        }

        .stat, .panel {
            background: var(--panel);
            border: 0;
            border-radius: 2px;
        }

        .stat {
            position: relative;
            min-height: 118px;
            padding: 20px 12px 38px;
            color: #fff;
            overflow: hidden;
            background: #3c8dbc;
        }

        .stat:nth-child(2) { background: #f39c12; }
        .stat:nth-child(3) { background: #00a65a; }
        .stat:nth-child(4) { background: #dd4b39; }

        .stat-label {
            color: rgba(255, 255, 255, .95);
            font-size: .8rem;
        }

        .stat-value {
            margin: 0 0 10px;
            font: 400 2rem 'Segoe UI', sans-serif;
        }

        .stat-note {
            position: absolute;
            right: 12px;
            bottom: 38px;
            color: rgba(255, 255, 255, .15);
            font-size: 0;
        }

        .stat-note::after {
            content: '▥';
            font-size: 2.8rem;
        }
        .stat:nth-child(2) .stat-note::after { content: '♟'; }
        .stat:nth-child(3) .stat-note::after { content: '▥'; }
        .stat:nth-child(4) .stat-note::after { content: '▥'; }

        .stat::after {
            content: 'More info  ⓘ';
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            padding: 6px 10px;
            color: rgba(255, 255, 255, .95);
            background: rgba(0, 0, 0, .13);
            font-size: .72rem;
            text-align: center;
        }

        .content-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.5fr) minmax(260px, .85fr);
            gap: 24px;
        }

        .panel { padding: 20px; }

        .panel-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        h2 {
            margin: 0;
            font: 400 1.15rem 'Segoe UI', sans-serif;
        }

        .panel-link {
            color: #3c8dbc;
            font-size: .75rem;
            text-decoration: none;
        }

        table { width: 100%; border-collapse: collapse; font-size: .8rem; }
        th {
            padding: 0 0 12px;
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit"><span class="icon">↪</span> Logout</button>
                    </form>
            font-size: .68rem;
            font-weight: 400;
            letter-spacing: .08em;
            text-align: left;
            text-transform: uppercase;
        }
        td { padding: 14px 8px 14px 0; border-top: 1px solid var(--line); }
        td:last-child, th:last-child { text-align: right; }
        .book { font-weight: 700; }
        .borrower { display: block; margin-top: 4px; color: var(--muted); font-size: .72rem; font-weight: 400; }

        .status {
            display: inline-block;
            padding: 5px 8px;
            border-radius: 4px;
            font-size: .67rem;
            white-space: nowrap;
        }
        .status.pending { color: #966313; background: var(--yellow-soft); }
        .status.borrowed { color: #3d6484; background: var(--blue-soft); }
        .status.returned { color: #327469; background: var(--teal-soft); }
        .status.approved { color: #327469; background: var(--teal-soft); }
        .status.overdue, .status.lost, .status.rejected { color: #9b3d35; background: #fbe6e3; }

        .empty-state {
            padding: 28px 0;
            color: var(--muted);
            text-align: center;
        }

        .quick-actions { display: grid; gap: 10px; }
        .action {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px;
            color: var(--ink);
            border: 1px solid var(--line);
            border-radius: 5px;
            font-size: .8rem;
            text-decoration: none;
        }
        .action:hover { border-color: #3c8dbc; background: #f8fbfd; }
        .action-icon { color: var(--orange); font-size: 1.1rem; }

        .notice {
            margin-top: 20px;
            padding: 17px;
            color: #73562c;
            background: var(--yellow-soft);
            border-left: 3px solid var(--orange);
            font-size: .78rem;
            line-height: 1.5;
        }

        @media (max-width: 900px) {
            .layout { grid-template-columns: 1fr; }
            aside { padding: 0 0 18px; }
            .brand { margin: 0; }
            nav { grid-template-columns: repeat(3, 1fr); }
            .sidebar-footer { display: none; }
            .stats { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 600px) {
            main { padding: 26px 16px 36px; }
            header { display: block; }
            .admin { margin-top: 20px; }
            nav { grid-template-columns: repeat(2, 1fr); }
            .stats, .content-grid { grid-template-columns: 1fr; }
            table { font-size: .72rem; }
            td { padding-right: 4px; }
        }
    </style>
</head>
<body>
    <div class="layout">
        <aside>
            <div class="brand"><span class="brand-mark">P</span> Perpusku</div>
            <div class="profile">
                <span class="profile-avatar">A</span>
                <div><div class="profile-name">Admin Perpusku</div><span class="profile-role">Administrator</span></div>
            </div>
            <nav aria-label="Navigasi admin">
                <div class="nav-label">Main navigation</div>
                <a class="active" href="/"><span class="icon">⌂</span> Dashboard</a>
                <a href="#"><span class="icon">▤</span> Manajemen Buku</a>
                <a href="#"><span class="icon">♙</span> Manajemen User</a>
                <a href="#"><span class="icon">↔</span> Peminjaman</a>
                <a href="#"><span class="icon">✓</span> Pengembalian</a>
                <a href="#"><span class="icon">▦</span> QR Code</a>
                <a href="#"><span class="icon">◌</span> Notifikasi WA</a>
                <a href="#"><span class="icon">▥</span> Laporan</a>
                <div class="nav-label">Setting</div>
                <a href="#"><span class="icon">♟</span> Pengguna Sistem</a>
                <a href="#"><span class="icon">↪</span> Logout</a>
            </nav>
            <div class="sidebar-footer">Panel admin<br>Perpustakaan digital</div>
        </aside>

        <main>
            <header>
                <div>
                    <p class="eyebrow">Ringkasan hari ini</p>
                    <h1>Dashboard admin</h1>
                </div>
                <div class="admin"><span>Administrator</span><span class="avatar">A</span></div>
            </header>

            <section class="stats" aria-label="Statistik perpustakaan">
                <article class="stat"><div class="stat-label">Total koleksi buku</div><div class="stat-value">{{ number_format($stats['books']) }}</div><div class="stat-note">Data saat ini</div></article>
                <article class="stat"><div class="stat-label">Sedang dipinjam</div><div class="stat-value">{{ number_format($stats['activeLoans']) }}</div><div class="stat-note">Data saat ini</div></article>
                <article class="stat"><div class="stat-label">Anggota aktif</div><div class="stat-value">{{ number_format($stats['activeMembers']) }}</div><div class="stat-note">Data saat ini</div></article>
                <article class="stat"><div class="stat-label">Terlambat dikembalikan</div><div class="stat-value">{{ number_format($stats['overdueLoans']) }}</div><div class="stat-note">Data saat ini</div></article>
            </section>

            <div class="content-grid">
                <section class="panel">
                    <div class="panel-heading"><h2>Peminjaman terbaru</h2><a class="panel-link" href="#">Lihat semua</a></div>
                    <table>
                        <thead><tr><th>Buku & peminjam</th><th>Tanggal</th><th>Status</th></tr></thead>
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
                                    <td><span class="book">{{ $loan->title }}</span><span class="borrower">{{ $loan->borrower }}</span></td>
                                    <td>{{ date('d M Y', strtotime($loan->loan_date)) }}</td>
                                    <td><span class="status {{ $loan->status }}">{{ $statusLabels[$loan->status] ?? ucfirst($loan->status) }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="empty-state">Belum ada data peminjaman.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </section>

                <section class="panel">
                    <div class="panel-heading"><h2>Aksi cepat</h2></div>
                    <div class="quick-actions">
                        <a class="action" href="#"><span class="action-icon">+</span> Tambah buku baru</a>
                        <a class="action" href="#"><span class="action-icon">✓</span> Tinjau pengajuan</a>
                        <a class="action" href="#"><span class="action-icon">▦</span> Buat QR Code</a>
                        <a class="action" href="#"><span class="action-icon">↓</span> Unduh laporan</a>
                    </div>
                    <div class="notice"><strong>{{ number_format($stats['overdueLoans']) }} pinjaman terlambat.</strong><br>Data ditampilkan langsung dari database perpustakaan.</div>
                </section>
            </div>
        </main>
    </div>
</body>
</html>
