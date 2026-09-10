@extends('layouts.admin')

@section('title', 'Pengembalian Buku')

@section('content')
<header style="margin-bottom: 20px;">
    <h1>Pengembalian Buku</h1>
</header>

<section class="panel" style="margin-bottom: 24px; max-width: 600px;">
    <div class="panel-heading">
        <h2>Proses Pengembalian (Scan / Input Kode)</h2>
    </div>

    <form id="form-return-scan" onsubmit="processReturn(event)">
        <div style="display: flex; gap: 10px;">
            <input type="text" id="loan_code_input" placeholder="Masukkan / Scan Kode Peminjaman (misal: LN-xxx)" required style="flex: 1; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
            <button type="submit" class="btn btn-primary">Proses Pengembalian</button>
        </div>
        <div id="scan-feedback" style="margin-top: 10px; font-size: 0.85rem; font-weight: 600;"></div>
    </form>
</section>

<section class="panel">
    <div class="panel-heading">
        <h2>Riwayat Pengembalian Terakhir</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>Kode Pinjam</th>
                <th>Buku & Peminjam</th>
                <th>Tgl Pinjam / Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($returnedLoans as $loan)
                <tr>
                    <td><code>{{ $loan->loan_code }}</code></td>
                    <td>
                        <strong style="color: #222;">{{ $loan->book->title ?? '-' }}</strong>
                        <span style="display: block; font-size: 0.75rem; color: #68777d;">Peminjam: {{ $loan->user->name ?? '-' }}</span>
                    </td>
                    <td>
                        {{ date('d M Y', strtotime($loan->loan_date)) }}
                        <span style="display: block; font-size: 0.75rem; color: #00a65a;">Kembali: {{ $loan->return_date ? date('d M Y', strtotime($loan->return_date)) : '-' }}</span>
                    </td>
                    <td>
                        <span class="status {{ $loan->status }}">{{ ucfirst($loan->status) }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #68777d; padding: 24px;">Belum ada riwayat pengembalian.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 16px;">
        {{ $returnedLoans->links() }}
    </div>
</section>

@push('scripts')
<script>
async function processReturn(e) {
    e.preventDefault();
    const input = document.getElementById('loan_code_input');
    const feedback = document.getElementById('scan-feedback');
    const code = input.value.trim();

    if (!code) return;

    feedback.style.color = '#333';
    feedback.innerText = 'Memproses...';

    try {
        const response = await fetch('{{ route("admin.returns.scan") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ loan_code: code })
        });

        const result = await response.json();

        if (response.ok && result.success) {
            feedback.style.color = '#00a65a';
            feedback.innerText = '✓ ' + result.message;
            input.value = '';
            setTimeout(() => location.reload(), 1500);
        } else {
            feedback.style.color = '#dd4b39';
            feedback.innerText = '✕ ' + (result.message || 'Gagal memproses pengembalian.');
        }
    } catch (err) {
        feedback.style.color = '#dd4b39';
        feedback.innerText = '✕ Terjadi kesalahan koneksi.';
    }
}
</script>
@endpush
@endsection
