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

    <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
        <thead>
            <tr>
                <th style="width: 170px;">Kode Pinjam</th>
                <th>Buku & Peminjam</th>
                <th style="width: 200px;">Tgl Pinjam / Kembali</th>
                <th style="width: 140px; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($returnedLoans as $loan)
                <tr>
                    <td><code>{{ $loan->kode_pinjam }}</code></td>
                    <td>
                        <strong style="color: #0f172a;">{{ $loan->book->judul ?? '-' }}</strong>
                        <span style="display: block; font-size: 0.75rem; color: #64748b;">Peminjam: {{ $loan->user->nama ?? '-' }}</span>
                    </td>
                    <td>
                        {{ $loan->tanggal_pinjam ? $loan->tanggal_pinjam->format('d M Y') : '-' }}
                        <span style="display: block; font-size: 0.75rem; color: #16a34a; font-weight: 600;">Kembali: {{ $loan->tanggal_kembali ? $loan->tanggal_kembali->format('d M Y') : '-' }}</span>
                    </td>
                    <td style="text-align: center;">
                        @php
                            $statusVal = is_object($loan->status) ? $loan->status->value : $loan->status;
                        @endphp
                        <span class="status {{ $statusVal }}">{{ ucfirst($statusVal) }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #64748b; padding: 28px;">Belum ada riwayat pengembalian.</td>
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
function playAudioBeep(success = true) {
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.type = 'sine';
        osc.frequency.value = success ? 880 : 330;
        gain.gain.value = 0.2;
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.start();
        osc.stop(ctx.currentTime + (success ? 0.15 : 0.3));
    } catch (e) {}
}

const returnInput = document.getElementById('loan_code_input');

document.addEventListener('click', (e) => {
    if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'BUTTON' && e.target.tagName !== 'A') {
        if (returnInput) returnInput.focus();
    }
});
window.addEventListener('load', () => {
    if (returnInput) returnInput.focus();
});

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
            playAudioBeep(true);
            feedback.style.color = '#00a65a';
            feedback.innerText = '✓ ' + result.message;
            input.value = '';
            setTimeout(() => location.reload(), 1200);
        } else {
            playAudioBeep(false);
            feedback.style.color = '#dd4b39';
            feedback.innerText = '✕ ' + (result.message || 'Gagal memproses pengembalian.');
        }
    } catch (err) {
        playAudioBeep(false);
        feedback.style.color = '#dd4b39';
        feedback.innerText = '✕ Terjadi kesalahan koneksi.';
    }
}
</script>
@endpush
@endsection
