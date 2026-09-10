@extends('layouts.admin')

@section('title', 'Dashboard Overview')

@push('styles')
<style>
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

    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
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
        .stats { grid-template-columns: repeat(2, 1fr); }
        .content-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
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
            <a class="panel-link" href="{{ route('admin.loans.index') }}">Lihat semua</a>
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
            <a class="action" href="{{ route('admin.books.create') }}"><span class="action-icon">+</span> Tambah buku baru</a>
            <a class="action" href="{{ route('admin.loans.index') }}"><span class="action-icon">✓</span> Tinjau pengajuan</a>
            <a class="action" href="{{ route('admin.qrcode.index') }}"><span class="action-icon">▦</span> Buat QR Code</a>
            <a class="action" href="{{ route('admin.reports.index') }}"><span class="action-icon">↓</span> Unduh laporan</a>
        </div>
        <div class="notice">
            <strong>{{ number_format($stats['overdueLoans']) }} pinjaman terlambat.</strong><br>
            Data ditampilkan langsung dari database perpustakaan.
        </div>
    </section>
</div>
@endsection
