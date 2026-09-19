@extends('layouts.admin')

@section('title', 'Peminjaman Buku')

@section('content')
<header style="margin-bottom: 20px;">
    <h1>Daftar Peminjaman Buku</h1>
</header>

<section class="panel">
    @if (!empty($selectedUser))
        <div style="background: #e0f2fe; border: 1px solid #bae6fd; border-radius: 6px; padding: 10px 14px; margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between;">
            <div style="font-size: 0.88rem; color: #0369a1; font-weight: 600;">
                👤 Menampilkan daftar pinjaman buku untuk peminjam: <strong>{{ $selectedUser->nama }}</strong> ({{ $selectedUser->telepon }})
            </div>
            <a href="{{ route('admin.loans.index') }}" class="btn btn-secondary" style="padding: 4px 10px; font-size: 0.78rem;">✕ Tampilkan Semua Transaksi</a>
        </div>
    @endif

    <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <h2>Transaksi Peminjaman</h2>
        <form method="GET" action="{{ route('admin.loans.index') }}" style="display: flex; align-items: center; gap: 8px; margin: 0;">
            @if (request('user_id'))
                <input type="hidden" name="user_id" value="{{ request('user_id') }}">
            @endif
            <label for="filter_status" style="font-size: 0.82rem; font-weight: 600; color: #475569;">Filter Status:</label>
            <select name="status" id="filter_status" onchange="this.form.submit()" style="padding: 6px 12px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.83rem; color: #0f172a; background-color: #fff; cursor: pointer; font-weight: 600; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                <option value="">-- Semua Status --</option>
                <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>📖 Masih Dipinjam</option>
                <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>✅ Sudah Dikembalikan</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Menunggu Persetujuan</option>
                <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>⚠️ Terlambat</option>
            </select>
        </form>
    </div>

    <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
        <thead>
            <tr>
                <th style="width: 15%;">Kode Transaksi</th>
                <th style="width: 30%;">Buku & Peminjam</th>
                <th style="width: 20%;">Tgl Pinjam / Jatuh Tempo</th>
                <th style="width: 12%; text-align: center;">Status</th>
                <th style="width: 11%; text-align: center;">Detail & QR</th>
                <th style="width: 12%; text-align: center;">Aksi Persetujuan</th>
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
                        <strong style="color: #0f172a;">{{ $loan->book->judul ?? '-' }}</strong>
                        <span style="display: block; font-size: 0.75rem; color: #64748b;">Peminjam: {{ $loan->user->nama ?? '-' }}</span>
                    </td>
                    <td>
                        {{ $loan->tanggal_pinjam ? $loan->tanggal_pinjam->format('d M Y') : '-' }}
                        <span style="display: block; font-size: 0.75rem; color: #dc2626;">Jatuh Tempo: {{ $loan->jatuh_tempo ? $loan->jatuh_tempo->format('d M Y') : '-' }}</span>
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
                    <!-- KOLOM TERPISAH DETAIL & QR CODE PEMINJAMAN -->
                    <td style="text-align: center;">
                        <button type="button" 
                                onclick="showLoanDetailModal('{{ $loan->kode_pinjam }}', '{{ addslashes($loan->book->judul ?? '-') }}', '{{ addslashes($loan->book->isbn ?? '-') }}', '{{ addslashes($loan->user->nama ?? '-') }}', '{{ $loan->user->telepon ?? '-' }}', '{{ $loan->user->email ?? '-' }}', '{{ $loan->tanggal_pinjam ? $loan->tanggal_pinjam->format('d M Y') : '-' }}', '{{ $loan->jatuh_tempo ? $loan->jatuh_tempo->format('d M Y') : '-' }}', '{{ $statusLabels[$statusVal] ?? ucfirst($statusVal) }}')" 
                                class="btn btn-primary" 
                                style="padding: 4px 10px; font-size: 0.75rem; font-weight: 600;">
                            Detail & QR
                        </button>
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 4px; justify-content: center; align-items: center; flex-wrap: wrap;">
                            @if ($statusVal === 'pending')
                                <form method="POST" action="{{ route('admin.loans.approve', $loan) }}" style="display: inline-block;">
                                    @csrf
                                    <input type="hidden" name="due_date" value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                                    <button type="submit" class="btn btn-success" style="padding: 4px 8px; font-size: 0.75rem;">Approve</button>
                                </form>

                                <form method="POST" action="{{ route('admin.loans.reject', $loan) }}" style="display: inline-block;" onsubmit="return confirm('Tolak pengajuan peminjaman ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary" style="padding: 4px 8px; font-size: 0.75rem; color: #dc2626; border-color: #fecaca; background: #fff5f5;">Tolak</button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('admin.loans.destroy', $loan) }}" style="display: inline-block;" onsubmit="return confirm('Yakin ingin menghapus data peminjaman ini? Jika buku belum dikembalikan, stok buku akan dikembalikan otomatis.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary" style="padding: 4px 8px; font-size: 0.75rem; color: #dc2626; border-color: #fecaca; background: #fff5f5;" title="Hapus transaksi">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #64748b; padding: 28px;">Belum ada pengajuan peminjaman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 16px;">
        {{ $loans->links() }}
    </div>
</section>

<!-- MODAL POPUP DETAIL PEMINJAMAN & QR CODE -->
<div id="loanDetailModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #fff; width: 100%; max-width: 420px; border-radius: 8px; padding: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
        <div style="text-align: center; border-bottom: 2px solid #0284c7; padding-bottom: 12px; margin-bottom: 16px;">
            <h3 style="margin: 0; font-size: 1.15rem; color: #0f172a;">📄 Detail Peminjaman Buku</h3>
            <p style="margin: 4px 0 0 0; font-size: 0.78rem; color: #64748b;">Informasi Transaksi & Kode QR Peminjaman</p>
        </div>

        <div id="printDetailArea" style="font-size: 0.85rem; color: #334155; line-height: 1.6;">
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px; margin-bottom: 14px;">
                <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; font-weight: 700;">Kode Transaksi</div>
                <div style="font-size: 1.05rem; font-weight: 800; color: #0284c7; font-family: monospace;" id="mdl_loan_code">-</div>
            </div>

            <div style="margin-bottom: 10px;">
                <span style="color: #64748b; font-size: 0.78rem; display: block;">Judul Buku:</span>
                <strong style="color: #0f172a; font-size: 0.95rem;" id="mdl_book_title">-</strong>
                <span style="font-size: 0.78rem; color: #64748b; display: block;" id="mdl_book_isbn">ISBN: -</span>
            </div>

            <div style="border-top: 1px dashed #cbd5e1; padding-top: 10px; margin-bottom: 10px;">
                <span style="color: #64748b; font-size: 0.78rem; display: block;">Data Peminjam:</span>
                <strong style="color: #0f172a;" id="mdl_user_name">-</strong>
                <div style="font-size: 0.8rem; color: #475569;" id="mdl_user_phone">WA: -</div>
                <div style="font-size: 0.8rem; color: #475569;" id="mdl_user_email">Email: -</div>
            </div>

            <div style="border-top: 1px dashed #cbd5e1; padding-top: 10px; margin-bottom: 14px; display: flex; justify-content: space-between;">
                <div>
                    <span style="color: #64748b; font-size: 0.75rem; display: block;">Tgl Pinjam:</span>
                    <strong style="color: #0f172a;" id="mdl_loan_date">-</strong>
                </div>
                <div>
                    <span style="color: #64748b; font-size: 0.75rem; display: block;">Jatuh Tempo:</span>
                    <strong style="color: #dc2626;" id="mdl_due_date">-</strong>
                </div>
                <div>
                    <span style="color: #64748b; font-size: 0.75rem; display: block;">Status:</span>
                    <strong style="color: #16a34a;" id="mdl_status">-</strong>
                </div>
            </div>

            <!-- KODE QR TRANSAKSI -->
            <div style="text-align: center; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; padding: 12px; margin-bottom: 16px;">
                <div style="font-size: 0.75rem; color: #475569; font-weight: 700; margin-bottom: 6px;">KODE QR TRANSAKSI</div>
                <div style="display: inline-block; background: #fff; padding: 8px; border: 2px solid #0f172a; border-radius: 4px; font-weight: 800; font-family: monospace; font-size: 1rem;" id="mdl_qr_box">
                    [ QR: <span id="mdl_qr_code">-</span> ]
                </div>
                <div style="font-size: 0.72rem; color: #64748b; margin-top: 6px;">Scan barcode / QR ini saat pengembalian buku</div>
            </div>
        </div>

        <div style="display: flex; gap: 8px; justify-content: flex-end;">
            <button type="button" onclick="closeLoanDetailModal()" class="btn btn-secondary">Tutup</button>
            <button type="button" onclick="printLoanDetailAndClose()" class="btn btn-primary">🖨️ Cetak Nota</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showLoanDetailModal(code, title, isbn, user, phone, email, date, due, status) {
    document.getElementById('mdl_loan_code').innerText = code;
    document.getElementById('mdl_book_title').innerText = title;
    document.getElementById('mdl_book_isbn').innerText = isbn;
    document.getElementById('mdl_user_name').innerText = user;
    document.getElementById('mdl_user_phone').innerText = 'WA: ' + phone;
    document.getElementById('mdl_user_email').innerText = 'Email: ' + email;
    document.getElementById('mdl_loan_date').innerText = date;
    document.getElementById('mdl_due_date').innerText = due;
    document.getElementById('mdl_status').innerText = status;
    document.getElementById('mdl_qr_code').innerText = code;

    document.getElementById('loanDetailModal').style.display = 'flex';
}

function closeLoanDetailModal() {
    document.getElementById('loanDetailModal').style.display = 'none';
}

function printLoanDetailAndClose() {
    window.print();
    closeLoanDetailModal();
}

window.addEventListener('afterprint', function() {
    closeLoanDetailModal();
});
</script>
@endpush
@endsection
