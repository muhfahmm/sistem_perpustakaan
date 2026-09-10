@extends('layouts.admin')

@section('title', 'Peminjaman Buku')

@section('content')
<header style="margin-bottom: 20px;">
    <h1>Daftar Peminjaman Buku</h1>
</header>

<section class="panel">
    <div class="panel-heading">
        <h2>Transaksi Peminjaman</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>Kode Transaksi</th>
                <th>Buku & Peminjam</th>
                <th>Tgl Pinjam / Jatuh Tempo</th>
                <th>Status</th>
                <th>Aksi Persetujuan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($loans as $loan)
                @php
                    $statusLabels = [
                        'pending' => 'Menunggu Persetujuan',
                        'approved' => 'Disetujui',
                        'borrowed' => 'Dipinjam',
                        'returned' => 'Dikembalikan',
                        'overdue' => 'Terlambat',
                        'rejected' => 'Ditolak',
                        'lost' => 'Hilang',
                    ];
                @endphp
                <tr>
                    <td><code>{{ $loan->loan_code }}</code></td>
                    <td>
                        <strong style="color: #222;">{{ $loan->book->title ?? '-' }}</strong>
                        <span style="display: block; font-size: 0.75rem; color: #68777d;">Peminjam: {{ $loan->user->name ?? '-' }}</span>
                    </td>
                    <td>
                        {{ date('d M Y', strtotime($loan->loan_date)) }}
                        <span style="display: block; font-size: 0.75rem; color: #dd4b39;">Jatuh Tempo: {{ date('d M Y', strtotime($loan->due_date)) }}</span>
                    </td>
                    <td>
                        <span class="status {{ $loan->status }}">{{ $statusLabels[$loan->status] ?? ucfirst($loan->status) }}</span>
                    </td>
                    <td>
                        @if ($loan->status === 'pending')
                            <form method="POST" action="{{ route('admin.loans.approve', $loan) }}" style="display: inline-block;">
                                @csrf
                                <input type="hidden" name="due_date" value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                                <button type="submit" class="btn btn-success" style="font-size: 0.75rem;">Approve</button>
                            </form>

                            <form method="POST" action="{{ route('admin.loans.reject', $loan) }}" style="display: inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-secondary" style="font-size: 0.75rem; color: #dd4b39;">Tolak</button>
                            </form>
                        @else
                            <span style="font-size: 0.75rem; color: #999;">- Selesai -</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #68777d; padding: 24px;">Belum ada pengajuan peminjaman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 16px;">
        {{ $loans->links() }}
    </div>
</section>
@endsection
