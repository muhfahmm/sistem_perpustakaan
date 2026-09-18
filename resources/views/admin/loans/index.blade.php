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

    <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
        <thead>
            <tr>
                <th style="width: 170px;">Kode Transaksi</th>
                <th>Buku & Peminjam</th>
                <th style="width: 180px;">Tgl Pinjam / Jatuh Tempo</th>
                <th style="width: 160px; text-align: center;">Status</th>
                <th style="width: 150px; text-align: center;">Aksi Persetujuan</th>
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
                    <td><code>{{ $loan->kode_pinjam }}</code></td>
                    <td>
                        <strong style="color: #0f172a;">{{ $loan->book->judul ?? '-' }}</strong>
                        <span style="display: block; font-size: 0.75rem; color: #64748b;">Peminjam: {{ $loan->user->nama ?? '-' }}</span>
                    </td>
                    <td>
                        {{ $loan->tanggal_pinjam ? $loan->tanggal_pinjam->format('d M Y') : '-' }}
                        <span style="display: block; font-size: 0.75rem; color: #dc2626;">Jatuh Tempo: {{ $loan->jatuh_tempo ? $loan->jatuh_tempo->format('d M Y') : '-' }}</span>
                    </td>
                    <td style="text-align: center;">
                        @php
                            $statusVal = is_object($loan->status) ? $loan->status->value : $loan->status;
                        @endphp
                        <span class="status {{ $statusVal }}">{{ $statusLabels[$statusVal] ?? ucfirst($statusVal) }}</span>
                    </td>
                    <td style="text-align: center;">
                        @php
                            $statusVal = is_object($loan->status) ? $loan->status->value : $loan->status;
                        @endphp
                        @if ($statusVal === 'pending')
                            <form method="POST" action="{{ route('admin.loans.approve', $loan) }}" style="display: inline-block;">
                                @csrf
                                <input type="hidden" name="due_date" value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                                <button type="submit" class="btn btn-success" style="padding: 4px 10px; font-size: 0.75rem;">Approve</button>
                            </form>

                            <form method="POST" action="{{ route('admin.loans.reject', $loan) }}" style="display: inline-block;" onsubmit="return confirm('Tolak pengajuan peminjaman ini?')">
                                @csrf
                                <button type="submit" class="btn btn-secondary" style="padding: 4px 10px; font-size: 0.75rem; color: #dc2626; border-color: #fecaca; background: #fff5f5;">Tolak</button>
                            </form>
                        @else
                            <span style="font-size: 0.75rem; color: #64748b; font-weight: 500;">- Selesai -</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #64748b; padding: 28px;">Belum ada pengajuan peminjaman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 16px;">
        {{ $loans->links() }}
    </div>
</section>
@endsection
