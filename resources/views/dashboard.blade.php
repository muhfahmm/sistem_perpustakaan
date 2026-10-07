@extends('layouts.admin')

@section('title', 'Dashboard')

@push('styles')
<style>
    .dashboard-page { --dashboard-green: #16845b; --dashboard-ink: #19352b; --dashboard-muted: #718078; }
    .dashboard-page header { margin-bottom: 1.5rem; }
    .dashboard-page h1 { color: var(--dashboard-ink); font-size: 1.65rem; font-weight: 750; letter-spacing: -.04em; }
    .dashboard-subtitle { color: var(--dashboard-muted); }
    .dashboard-card { height: 100%; border: 1px solid #e6eee9; border-radius: 14px; box-shadow: 0 4px 16px rgba(25, 53, 43, .04); }
    .dashboard-stat { color: var(--dashboard-ink); text-decoration: none; transition: transform .15s ease, box-shadow .15s ease; }
    .dashboard-stat:hover { color: var(--dashboard-ink); transform: translateY(-2px); box-shadow: 0 10px 24px rgba(25, 53, 43, .08); }
    .dashboard-stat-icon { display: grid; width: 42px; height: 42px; place-items: center; color: var(--dashboard-green); background: #eaf5ef; border-radius: 12px; font-size: 1.25rem; }
    .dashboard-stat-value { color: var(--dashboard-ink); font-size: 1.85rem; font-weight: 800; letter-spacing: -.05em; line-height: 1.1; }
    .dashboard-stat-label { color: var(--dashboard-muted); font-size: .85rem; font-weight: 600; }
    .dashboard-section-title { color: var(--dashboard-ink); font-size: 1rem; font-weight: 750; }
    .dashboard-link { color: var(--dashboard-green); font-size: .85rem; font-weight: 700; text-decoration: none; }
    .dashboard-link:hover { color: #116b49; text-decoration: underline; }
    .dashboard-page .table { --bs-table-bg: transparent; font-size: .88rem; }
    .dashboard-page .table thead th { color: #748179; font-size: .72rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; }
    .dashboard-page .table > :not(caption) > * > * { padding: .8rem .75rem; }
    .loan-book { color: var(--dashboard-ink); font-weight: 700; }
    .loan-borrower { color: var(--dashboard-muted); font-size: .78rem; }
    .quick-action { display: flex; align-items: center; gap: .75rem; padding: .85rem; color: var(--dashboard-ink); background: #fff; border: 1px solid #e6eee9; border-radius: 10px; font-size: .88rem; font-weight: 650; text-decoration: none; transition: background .15s ease, border-color .15s ease; }
    .quick-action:hover { color: var(--dashboard-green); background: #f6faf7; border-color: #b9d9c7; }
    .quick-action-icon { display: grid; width: 34px; height: 34px; place-items: center; color: var(--dashboard-green); background: #eaf5ef; border-radius: 9px; font-size: 1.05rem; }
    .dashboard-page .badge { font-weight: 650; }
    @media (max-width: 575.98px) {
        .dashboard-page h1 { font-size: 1.4rem; }
        .dashboard-page .card-body { padding: 1rem !important; }
    }
</style>
@endpush

@section('content')
<div class="dashboard-page">
    <header class="d-flex flex-wrap align-items-end justify-content-between gap-2">
        <div>
            <h1 class="mb-1">Dashboard Admin</h1>
            <p class="dashboard-subtitle mb-0">Ringkasan aktivitas perpustakaan.</p>
        </div>
        <a href="{{ route('admin.books.create') }}" class="btn btn-success px-3">+ Tambah buku</a>
    </header>

    <section class="row g-3 mb-4" aria-label="Statistik perpustakaan">
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="{{ route('admin.books.index') }}" class="card dashboard-card dashboard-stat text-decoration-none">
                <div class="card-body d-flex align-items-center justify-content-between gap-3 p-3 p-lg-4">
                    <div>
                        <div class="dashboard-stat-label mb-2">Total koleksi buku</div>
                        <div class="dashboard-stat-value">{{ number_format($stats['books']) }}</div>
                    </div>
                    <span class="dashboard-stat-icon" aria-hidden="true">▤</span>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="{{ route('admin.loans.index') }}" class="card dashboard-card dashboard-stat text-decoration-none">
                <div class="card-body d-flex align-items-center justify-content-between gap-3 p-3 p-lg-4">
                    <div>
                        <div class="dashboard-stat-label mb-2">Sedang dipinjam</div>
                        <div class="dashboard-stat-value">{{ number_format($stats['activeLoans']) }}</div>
                    </div>
                    <span class="dashboard-stat-icon" aria-hidden="true">↗</span>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="{{ route('admin.users.index') }}" class="card dashboard-card dashboard-stat text-decoration-none">
                <div class="card-body d-flex align-items-center justify-content-between gap-3 p-3 p-lg-4">
                    <div>
                        <div class="dashboard-stat-label mb-2">Anggota aktif</div>
                        <div class="dashboard-stat-value">{{ number_format($stats['activeMembers']) }}</div>
                    </div>
                    <span class="dashboard-stat-icon" aria-hidden="true">♙</span>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="{{ route('admin.loans.index') }}" class="card dashboard-card dashboard-stat text-decoration-none">
                <div class="card-body d-flex align-items-center justify-content-between gap-3 p-3 p-lg-4">
                    <div>
                        <div class="dashboard-stat-label mb-2">Terlambat</div>
                        <div class="dashboard-stat-value">{{ number_format($stats['overdueLoans']) }}</div>
                    </div>
                    <span class="dashboard-stat-icon text-danger bg-danger-subtle" aria-hidden="true">!</span>
                </div>
            </a>
        </div>
    </section>

    <div class="row g-3">
        <div class="col-12 col-xl-8">
            <section class="card dashboard-card">
                <div class="card-body p-3 p-lg-4">
                    <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                        <h2 class="dashboard-section-title mb-0">Peminjaman terbaru</h2>
                        <a class="dashboard-link" href="{{ route('admin.loans.index') }}">Lihat semua <span aria-hidden="true">→</span></a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Buku &amp; peminjam</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Status</th>
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
                                        $statusClasses = [
                                            'pending' => 'text-bg-warning',
                                            'approved' => 'text-bg-info',
                                            'borrowed' => 'text-bg-primary',
                                            'returned' => 'text-bg-success',
                                            'overdue' => 'text-bg-danger',
                                            'rejected' => 'text-bg-secondary',
                                            'lost' => 'text-bg-dark',
                                        ];
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="loan-book">{{ $loan->title }}</div>
                                            <div class="loan-borrower">{{ $loan->borrower }}</div>
                                        </td>
                                        <td class="text-nowrap">{{ date('d M Y', strtotime($loan->loan_date)) }}</td>
                                        <td>
                                            <span class="badge rounded-pill {{ $statusClasses[$loan->status] ?? 'text-bg-secondary' }}">
                                                {{ $statusLabels[$loan->status] ?? ucfirst($loan->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-4 text-center text-secondary">Belum ada data peminjaman.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>

        <div class="col-12 col-xl-4">
            <section class="card dashboard-card">
                <div class="card-body p-3 p-lg-4">
                    <h2 class="dashboard-section-title mb-3">Aksi cepat</h2>
                    <div class="d-grid gap-2">
                        <a class="quick-action" href="{{ route('admin.books.create') }}">
                            <span class="quick-action-icon" aria-hidden="true">+</span>
                            <span>Tambah buku baru</span>
                        </a>
                        <a class="quick-action" href="{{ route('admin.reports.index') }}">
                            <span class="quick-action-icon" aria-hidden="true">↓</span>
                            <span>Lihat laporan sirkulasi</span>
                        </a>
                    </div>

                    <div class="alert {{ $stats['overdueLoans'] > 0 ? 'alert-warning' : 'alert-success' }} mt-3 mb-0 small" role="status">
                        @if ($stats['overdueLoans'] > 0)
                            <strong>{{ number_format($stats['overdueLoans']) }} pinjaman terlambat.</strong>
                            Periksa daftar peminjaman untuk tindak lanjut.
                        @else
                            Tidak ada pinjaman terlambat saat ini.
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
