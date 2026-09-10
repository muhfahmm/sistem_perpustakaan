<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | Perpusku</title>
    <!-- Google Fonts: Plus Jakarta Sans & Fraunces -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400..700;1,9..144,400..700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-body: #f8fafc;
            --navy-dark: #0f172a;
            --navy-sidebar: #1e293b;
            --navy-hover: #334155;
            --accent-gold: #d97706;
            --accent-soft: rgba(217, 119, 6, 0.12);
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --card-bg: #ffffff;
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
            background-color: var(--bg-body);
            color: var(--text-main);
            font-family: var(--font-sans);
        }

        .app-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }

        /* SIDEBAR */
        aside {
            background-color: var(--navy-dark);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            position: sticky;
            top: 0;
            height: 100vh;
            z-index: 10;
        }

        .sidebar-brand {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--accent-gold), #b45309);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.35);
        }

        .brand-text {
            font-family: var(--font-serif);
            font-size: 1.4rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.02em;
        }

        .admin-profile-card {
            margin: 1.25rem 1rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #38bdf8, #0284c7);
            color: #ffffff;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            text-transform: uppercase;
        }

        .admin-info {
            overflow: hidden;
        }

        .admin-username {
            font-size: 0.9rem;
            font-weight: 600;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .admin-badge {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--accent-gold);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .nav-section {
            padding: 0 1rem;
            flex: 1;
            overflow-y: auto;
        }

        .nav-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            margin: 1.25rem 0.5rem 0.5rem;
        }

        .nav-menu {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .nav-item a, .nav-item button {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem 1rem;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s ease;
            width: 100%;
            background: none;
            border: none;
            cursor: pointer;
            text-align: left;
        }

        .nav-item a:hover, .nav-item button:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.06);
        }

        .nav-item a.active {
            color: #ffffff;
            background: var(--accent-gold);
            font-weight: 600;
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-footer {
            padding: 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 0.78rem;
            color: #64748b;
            text-align: center;
        }

        /* MAIN CONTENT AREA */
        main {
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        /* TOP HEADER BAR */
        .top-header {
            height: 70px;
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 5;
        }

        .header-title-area {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-greeting {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--navy-dark);
        }

        .header-greeting span {
            color: var(--accent-gold);
        }

        .user-top-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--bg-body);
            padding: 6px 14px;
            border-radius: 30px;
            border: 1px solid var(--border-color);
        }

        .user-top-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--navy-dark);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
        }

        .user-top-username {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--navy-dark);
        }

        /* DASHBOARD BODY */
        .dashboard-body {
            padding: 2rem;
            flex: 1;
        }

        .page-heading {
            margin-bottom: 1.75rem;
        }

        .page-title {
            font-family: var(--font-serif);
            font-size: 2rem;
            font-weight: 700;
            color: var(--navy-dark);
            letter-spacing: -0.02em;
        }

        .page-subtitle {
            font-size: 0.88rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }

        /* STATS GRID */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 14px;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
        }

        .stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-blue { background: #e0f2fe; color: #0284c7; }
        .icon-amber { background: #fef3c7; color: #d97706; }
        .icon-emerald { background: #d1fae5; color: #059669; }
        .icon-rose { background: #ffe4e6; color: #e11d48; }

        .stat-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-muted);
        }

        .stat-value {
            font-family: var(--font-serif);
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--navy-dark);
            line-height: 1;
        }

        /* CONTENT GRID */
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
        }

        .panel-card {
            background: var(--card-bg);
            border-radius: 14px;
            border: 1px solid var(--border-color);
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
        }

        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .panel-title {
            font-family: var(--font-serif);
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--navy-dark);
        }

        .panel-action-link {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--accent-gold);
            text-decoration: none;
        }

        .panel-action-link:hover {
            text-decoration: underline;
        }

        /* TABLE STYLES */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.88rem;
        }

        .data-table th {
            text-align: left;
            padding: 0.75rem 1rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            background: #f8fafc;
            border-radius: 6px;
        }

        .data-table td {
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
            color: var(--text-main);
        }

        .book-title {
            font-weight: 600;
            color: var(--navy-dark);
            display: block;
        }

        .borrower-name {
            font-size: 0.78rem;
            color: var(--text-muted);
            display: block;
            margin-top: 2px;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-pending { background: #fef3c7; color: #b45309; }
        .status-approved, .status-borrowed { background: #dbeafe; color: #1d4ed8; }
        .status-returned { background: #d1fae5; color: #047857; }
        .status-overdue, .status-rejected, .status-lost { background: #ffe4e6; color: #be123c; }

        .empty-state {
            text-align: center;
            padding: 2.5rem 1rem;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* QUICK ACTIONS */
        .action-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.85rem 1rem;
            background: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--navy-dark);
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            background: #ffffff;
            border-color: var(--accent-gold);
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.12);
            transform: translateX(2px);
        }

        .action-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--accent-soft);
            color: var(--accent-gold);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* RESPONSIVE */
        @media (max-width: 1024px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .content-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            .app-layout { grid-template-columns: 1fr; }
            aside { display: none; }
            .stats-grid { grid-template-columns: 1fr; }
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

    <div class="app-layout">
        <!-- SIDEBAR -->
        <aside>
            <div class="sidebar-brand">
                <div class="brand-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                </div>
                <span class="brand-text">Perpusku</span>
            </div>

            <div class="admin-profile-card">
                <div class="admin-avatar">{{ $adminInitial }}</div>
                <div class="admin-info">
                    <div class="admin-username">{{ $adminUsername }}</div>
                    <span class="admin-badge">Administrator</span>
                </div>
            </div>

            <div class="nav-section">
                <div class="nav-label">Navigasi Utama</div>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="active">
                            <span class="nav-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></span>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#">
                            <span class="nav-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg></span>
                            <span>Manajemen Buku</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#">
                            <span class="nav-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></span>
                            <span>Manajemen User</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#">
                            <span class="nav-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="17 1 21 5 17 9"></polyline><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><polyline points="7 23 3 19 7 15"></polyline><path d="M21 13v2a4 4 0 0 1-4 4H3"></path></svg></span>
                            <span>Peminjaman</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#">
                            <span class="nav-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></span>
                            <span>Pengembalian</span>
                        </a>
                    </li>
                </ul>

                <div class="nav-label">Pengaturan</div>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit">
                                <span class="nav-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg></span>
                                <span>Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

            <div class="sidebar-footer">
                Perpusku System &copy; {{ date('Y') }}
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main>
            <!-- TOP HEADER BAR -->
            <header class="top-header">
                <div class="header-title-area">
                    <h2 class="header-greeting">Selamat datang kembali, <span>{{ $adminUsername }}</span></h2>
                </div>

                <div class="user-top-profile">
                    <div class="user-top-avatar">{{ $adminInitial }}</div>
                    <span class="user-top-username">{{ $adminUsername }}</span>
                </div>
            </header>

            <!-- DASHBOARD BODY -->
            <div class="dashboard-body">
                <div class="page-heading">
                    <h1 class="page-title">Dashboard Overview</h1>
                    <p class="page-subtitle">Ringkasan statistik dan aktivitas perpustakaan hari ini.</p>
                </div>

                <!-- STATS GRID -->
                <section class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-top">
                            <span class="stat-label">Total Koleksi Buku</span>
                            <div class="stat-icon icon-blue">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                            </div>
                        </div>
                        <div class="stat-value">{{ number_format($stats['books']) }}</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-top">
                            <span class="stat-label">Sedang Dipinjam</span>
                            <div class="stat-icon icon-amber">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            </div>
                        </div>
                        <div class="stat-value">{{ number_format($stats['activeLoans']) }}</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-top">
                            <span class="stat-label">Anggota Aktif</span>
                            <div class="stat-icon icon-emerald">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                            </div>
                        </div>
                        <div class="stat-value">{{ number_format($stats['activeMembers']) }}</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-top">
                            <span class="stat-label">Terlambat Kembali</span>
                            <div class="stat-icon icon-rose">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                            </div>
                        </div>
                        <div class="stat-value">{{ number_format($stats['overdueLoans']) }}</div>
                    </div>
                </section>

                <!-- CONTENT GRID -->
                <div class="content-grid">
                    <!-- RECENT LOANS TABLE -->
                    <div class="panel-card">
                        <div class="panel-header">
                            <h3 class="panel-title">Peminjaman Terbaru</h3>
                            <a href="#" class="panel-action-link">Lihat Semua</a>
                        </div>
                        <table class="data-table">
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
                                            <span class="book-title">{{ $loan->title }}</span>
                                            <span class="borrower-name">{{ $loan->borrower }}</span>
                                        </td>
                                        <td>{{ date('d M Y', strtotime($loan->loan_date)) }}</td>
                                        <td>
                                            <span class="badge-status status-{{ $loan->status }}">
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
                    </div>

                    <!-- QUICK ACTIONS -->
                    <div class="panel-card">
                        <div class="panel-header">
                            <h3 class="panel-title">Aksi Cepat</h3>
                        </div>
                        <div class="action-list">
                            <a href="#" class="action-btn">
                                <span class="action-icon">+</span>
                                <span>Tambah Buku Baru</span>
                            </a>
                            <a href="#" class="action-btn">
                                <span class="action-icon">✓</span>
                                <span>Tinjau Pengajuan</span>
                            </a>
                            <a href="#" class="action-btn">
                                <span class="action-icon">▦</span>
                                <span>Buat QR Code</span>
                            </a>
                            <a href="#" class="action-btn">
                                <span class="action-icon">↓</span>
                                <span>Unduh Laporan</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
