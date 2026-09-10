@extends('layouts.admin')

@section('title', 'Laporan Sirkulasi')

@section('content')
<header style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
    <h1>Laporan & Statistik Sirkulasi</h1>
    <button onclick="window.print()" class="btn btn-secondary">🖨 Cetak Laporan</button>
</header>

<section class="panel" style="margin-bottom: 20px;">
    <form method="GET" action="{{ route('admin.reports.index') }}" style="display: flex; align-items: flex-end; gap: 14px;">
        <div>
            <label style="display: block; font-size: 0.75rem; font-weight: 600; margin-bottom: 4px;">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ $startDate }}" style="padding: 6px 10px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.8rem;">
        </div>
        <div>
            <label style="display: block; font-size: 0.75rem; font-weight: 600; margin-bottom: 4px;">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ $endDate }}" style="padding: 6px 10px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.8rem;">
        </div>
        <button type="submit" class="btn btn-primary">Filter Laporan</button>
    </form>
</section>

<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 20px;">
    <div style="background: #fff; border-top: 3px solid #3c8dbc; padding: 16px; border-radius: 3px;">
        <div style="font-size: 0.8rem; color: #68777d;">Total Peminjaman Periode Ini</div>
        <div style="font-size: 1.8rem; font-weight: 700; color: #3c8dbc; margin-top: 4px;">{{ number_format($totalLoans) }}</div>
    </div>
    <div style="background: #fff; border-top: 3px solid #00a65a; padding: 16px; border-radius: 3px;">
        <div style="font-size: 0.8rem; color: #68777d;">Berhasil Dikembalikan</div>
        <div style="font-size: 1.8rem; font-weight: 700; color: #00a65a; margin-top: 4px;">{{ number_format($returnedLoans) }}</div>
    </div>
    <div style="background: #fff; border-top: 3px solid #dd4b39; padding: 16px; border-radius: 3px;">
        <div style="font-size: 0.8rem; color: #68777d;">Terlambat / Menunggak</div>
        <div style="font-size: 1.8rem; font-weight: 700; color: #dd4b39; margin-top: 4px;">{{ number_format($overdueLoans) }}</div>
    </div>
</div>

<section class="panel">
    <div class="panel-heading">
        <h2>Rincian Transaksi Peminjaman ({{ date('d M Y', strtotime($startDate)) }} - {{ date('d M Y', strtotime($endDate)) }})</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Buku & Peminjam</th>
                <th>Tgl Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($recentReports as $report)
                <tr>
                    <td><code>{{ $report->loan_code }}</code></td>
                    <td>
                        <strong style="color: #222;">{{ $report->book_title }}</strong>
                        <span style="display: block; font-size: 0.75rem; color: #68777d;">{{ $report->borrower }}</span>
                    </td>
                    <td>{{ date('d M Y', strtotime($report->loan_date)) }}</td>
                    <td>{{ date('d M Y', strtotime($report->due_date)) }}</td>
                    <td><span class="status {{ $report->status }}">{{ ucfirst($report->status) }}</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #68777d; padding: 24px;">Tidak ada transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 16px;">
        {{ $recentReports->links() }}
    </div>
</section>
@endsection
