@extends('layouts.admin')

@section('title', 'Laporan Sirkulasi')

@section('content')
<header class="admin-page-heading">
    <div>
        <h1 class="admin-page-title">Laporan &amp; Statistik Sirkulasi</h1>
        <p>Ringkasan transaksi peminjaman berdasarkan periode.</p>
    </div>
    <button type="button" onclick="window.print()" class="btn btn-outline-success no-print">
        <i class="bi bi-printer me-1" aria-hidden="true"></i> Cetak laporan
    </button>
</header>

<section class="admin-page-card mb-4 no-print">
    <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-3 align-items-end p-3 p-md-4">
        <div class="col-12 col-sm-5 col-lg-4">
            <label for="start_date" class="form-label">Dari tanggal</label>
            <input id="start_date" type="date" name="start_date" value="{{ $startDate }}" class="form-control" required>
        </div>
        <div class="col-12 col-sm-5 col-lg-4">
            <label for="end_date" class="form-label">Sampai tanggal</label>
            <input id="end_date" type="date" name="end_date" value="{{ $endDate }}" class="form-control" required>
        </div>
        <div class="col-12 col-sm-2">
            <button type="submit" class="btn btn-success w-100">Terapkan</button>
        </div>
    </form>
</section>

<section class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card h-100 border-0 shadow-sm border-start border-4 border-success">
            <div class="card-body">
                <div class="small text-secondary">Total peminjaman periode ini</div>
                <div class="fs-2 fw-bold text-success mt-1">{{ number_format($totalLoans) }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card h-100 border-0 shadow-sm border-start border-4 border-success">
            <div class="card-body">
                <div class="small text-secondary">Berhasil dikembalikan</div>
                <div class="fs-2 fw-bold text-success mt-1">{{ number_format($returnedLoans) }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card h-100 border-0 shadow-sm border-start border-4 border-warning">
            <div class="card-body">
                <div class="small text-secondary">Terlambat / menunggak</div>
                <div class="fs-2 fw-bold text-warning-emphasis mt-1">{{ number_format($overdueLoans) }}</div>
            </div>
        </div>
    </div>
</section>

<section class="admin-page-card report-table">
    <div class="admin-page-card-header">
        <div>
            <h2 class="h6 fw-semibold mb-1">Rincian transaksi</h2>
            <span class="small text-secondary">{{ date('d M Y', strtotime($startDate)) }} – {{ date('d M Y', strtotime($endDate)) }}</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover admin-table">
            <thead>
                <tr>
                    <th>Kode transaksi</th>
                    <th>Buku &amp; peminjam</th>
                    <th>Tanggal pinjam</th>
                    <th>Jatuh tempo</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentReports as $report)
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
                        <td><code>{{ $report->kode_pinjam }}</code></td>
                        <td>
                            <div class="fw-semibold">{{ $report->book_title }}</div>
                            <div class="small text-secondary">{{ $report->borrower }}</div>
                        </td>
                        <td class="text-nowrap">{{ date('d M Y', strtotime($report->tanggal_pinjam)) }}</td>
                        <td class="text-nowrap">{{ date('d M Y', strtotime($report->jatuh_tempo)) }}</td>
                        <td><span class="badge rounded-pill {{ $statusClasses[$report->status] ?? 'text-bg-secondary' }}">{{ $statusLabels[$report->status] ?? ucfirst($report->status) }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-5 text-center text-secondary">Tidak ada transaksi pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($recentReports->hasPages())
        <div class="d-flex justify-content-center border-top p-3 no-print">
            {{ $recentReports->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    @endif
</section>

@push('styles')
<style>
    .report-table .table { min-width: 720px; }
    @media print {
        .no-print, .admin-sidebar, .topbar { display: none !important; }
        .main-content { padding: 0 !important; }
        .admin-page-card, .card { box-shadow: none !important; }
    }
</style>
@endpush
@endsection
