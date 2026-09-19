@extends('layouts.admin')

@section('title', 'Riwayat Pinjaman - ' . $user->nama)

@section('content')
<header style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary" style="margin-bottom: 10px; font-weight: 600;">← Kembali ke Daftar Peminjam</a>
        <h1 style="margin: 0; font-size: 1.5rem; color: #0f172a;">Riwayat Peminjaman Buku</h1>
        <p style="margin: 4px 0 0 0; color: #64748b; font-size: 0.85rem;">Daftar buku yang pernah dan sedang dipinjam oleh <strong>{{ $user->nama }}</strong></p>
    </div>
</header>

<!-- PROFILE CARD PEMINJAM -->
<div class="panel" style="margin-bottom: 24px; border-top: 4px solid #00a65a;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #64748b;">DATA PEMINJAM</span>
            <h2 style="margin: 4px 0 0 0; font-size: 1.25rem; color: #0f172a;">{{ $user->nama }}</h2>
            <div style="margin-top: 6px; font-size: 0.85rem; color: #475569; display: flex; gap: 16px; flex-wrap: wrap;">
                <span>Email: {{ $user->email }}</span>
                <span>WA: <code>+{{ $user->telepon }}</code></span>
            </div>
        </div>
        <div>
            @if ($user->status_aktif)
                <span style="display: inline-block; padding: 6px 14px; background: #dcfce7; color: #15803d; border-radius: 9999px; font-size: 0.82rem; font-weight: 700;">Status: Aktif</span>
            @else
                <span style="display: inline-block; padding: 6px 14px; background: #fee2e2; color: #991b1b; border-radius: 9999px; font-size: 0.82rem; font-weight: 700;">Status: Nonaktif</span>
            @endif
        </div>
    </div>
</div>

<!-- TABEL PINJAMAN USER -->
<section class="panel">
    <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Daftar Buku Dipinjam</h2>
        <span style="font-size: 0.82rem; color: #64748b; font-weight: 600;">Total Peminjaman: {{ $loans->total() }} Transaksi</span>
    </div>

    <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
        <thead>
            <tr>
                <th style="width: 14%;">Kode Transaksi</th>
                <th style="width: 28%;">Buku</th>
                <th style="width: 18%;">Tgl Pinjam / Jatuh Tempo</th>
                <th style="width: 14%;">Tgl Kembali</th>
                <th style="width: 12%; text-align: center;">Status</th>
                <th style="width: 14%; text-align: center;">Kirim Notif</th>
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
                    $statusVal = is_object($loan->status) ? $loan->status->value : $loan->status;
                @endphp
                <tr>
                    <td><code>{{ $loan->kode_pinjam }}</code></td>
                    <td>
                        <strong style="color: #0f172a; display: block;">{{ $loan->book->judul ?? '-' }}</strong>
                        <span style="font-size: 0.75rem; color: #64748b;">ISBN: {{ $loan->book->isbn ?? '-' }}</span>
                    </td>
                    <td>
                        <div>{{ $loan->tanggal_pinjam ? $loan->tanggal_pinjam->format('d M Y') : '-' }}</div>
                        <span style="font-size: 0.75rem; color: #dc2626; font-weight: 600;">Tempo: {{ $loan->jatuh_tempo ? $loan->jatuh_tempo->format('d M Y') : '-' }}</span>
                    </td>
                    <td>
                        @if ($loan->tanggal_kembali)
                            <span style="color: #16a34a; font-weight: 600;">{{ $loan->tanggal_kembali->format('d M Y') }}</span>
                        @else
                            <span style="color: #94a3b8;">- Belum Kembali -</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        @if ($statusVal === 'returned')
                            <span style="background: #10b981; color: #ffffff; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center;">Dikembalikan</span>
                        @elseif ($statusVal === 'borrowed' || $statusVal === 'approved')
                            <span style="background: #2563eb; color: #ffffff; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center;">Dipinjam</span>
                        @elseif ($statusVal === 'pending')
                            <span style="background: #f59e0b; color: #ffffff; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center;">Menunggu</span>
                        @elseif ($statusVal === 'overdue')
                            <span style="background: #ef4444; color: #ffffff; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center;">Terlambat</span>
                        @else
                            <span class="status {{ $statusVal }}">{{ $statusLabels[$statusVal] ?? ucfirst($statusVal) }}</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 4px; justify-content: center; align-items: center; flex-wrap: wrap;">
                            <form method="POST" action="{{ route('admin.loans.notify_wa', $loan) }}" target="_blank" style="display: inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-secondary" style="padding: 4px 8px; font-size: 0.72rem; color: #16a34a; border-color: #bbf7d0; background: #f0fdf4;" title="Kirim notifikasi WA ke WA API">Kirim WA</button>
                            </form>
                            <form method="POST" action="{{ route('admin.loans.notify_email', $loan) }}" target="_blank" style="display: inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-secondary" style="padding: 4px 8px; font-size: 0.72rem; color: #2563eb; border-color: #bfdbfe; background: #eff6ff;" title="Kirim notifikasi Email">Kirim Email</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #64748b; padding: 28px;">Belum ada riwayat peminjaman buku untuk peminjam ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 16px;">
        {{ $loans->links() }}
    </div>
</section>
@endsection
