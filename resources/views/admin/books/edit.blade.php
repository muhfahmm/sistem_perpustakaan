@extends('layouts.admin')

@section('title', 'Edit Buku')

@push('styles')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
@endpush

@section('content')
<header style="margin-bottom: 20px;">
    <h1>Edit Data Buku</h1>
</header>

<section class="panel" style="max-width: 650px;">
    <form method="POST" action="{{ route('admin.books.update', $book) }}">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 14px;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Judul Buku</label>
            <input type="text" name="title" value="{{ old('title', $book->title) }}" required style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
        </div>

        <div style="margin-bottom: 14px;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Penulis</label>
            <input type="text" name="author" value="{{ old('author', $book->author) }}" required style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Penerbit</label>
                <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}" style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
            </div>
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Tahun Terbit</label>
                <input type="number" name="year" value="{{ old('year', $book->year) }}" style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">ISBN</label>
                <div style="display: flex; gap: 6px;">
                    <input type="text" id="isbn_input" name="isbn" value="{{ old('isbn', $book->isbn) }}" style="flex: 1; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
                    <button type="button" onclick="startIsbnScanner()" class="btn btn-secondary" style="padding: 6px 10px; font-size: 0.78rem;" title="Scan Barcode ISBN via Kamera HP / Webcam">📷 Scan</button>
                </div>
            </div>
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Stok Total</label>
                <input type="number" name="stock" value="{{ old('stock', $book->stock) }}" min="0" required style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Deskripsi / Sinopsis</label>
            <textarea name="description" rows="4" style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">{{ old('description', $book->description) }}</textarea>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Perbarui Buku</button>
            <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</section>

<!-- MODAL CAMERA ISBN SCANNER -->
<div id="isbn-scanner-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #ffffff; width: 100%; max-width: 440px; border-radius: 8px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); text-align: center;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <h3 style="margin: 0; font-size: 1.05rem; color: #0f172a;">Scan Barcode ISBN Buku</h3>
            <button type="button" onclick="stopIsbnScanner()" style="background: none; border: none; font-size: 1.4rem; cursor: pointer; color: #64748b;">&times;</button>
        </div>
        <p style="font-size: 0.78rem; color: #64748b; margin-bottom: 12px;">Arahkan kamera ke kode Barcode ISBN (EAN-13) di sampul belakang buku.</p>
        <div id="isbn-reader" style="width: 100%; min-height: 250px; background: #0f172a; border-radius: 6px; overflow: hidden;"></div>
        <div id="scan-status" style="margin-top: 10px; font-size: 0.8rem; font-weight: 600; color: #00a65a;">Kamera aktif. Silakan scan barcode...</div>
        <button type="button" onclick="stopIsbnScanner()" class="btn btn-secondary" style="margin-top: 14px; width: 100%;">Tutup Kamera</button>
    </div>
</div>

@push('scripts')
<script>
let html5QrCode = null;

function startIsbnScanner() {
    const modal = document.getElementById('isbn-scanner-modal');
    modal.style.display = 'flex';
    document.getElementById('scan-status').innerText = 'Kamera aktif. Silakan scan barcode...';
    document.getElementById('scan-status').style.color = '#00a65a';

    if (!html5QrCode) {
        html5QrCode = new Html5Qrcode("isbn-reader");
    }

    const config = {
        fps: 15,
        qrbox: { width: 280, height: 160 },
        aspectRatio: 1.333
    };

    html5QrCode.start(
        { facingMode: "environment" },
        config,
        (decodedText, decodedResult) => {
            document.getElementById('isbn_input').value = decodedText;
            document.getElementById('scan-status').innerText = '✓ ISBN Ditemukan: ' + decodedText;

            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                osc.type = 'sine';
                osc.frequency.value = 880;
                osc.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.15);
            } catch (e) {}

            setTimeout(() => {
                stopIsbnScanner();
            }, 600);
        },
        (errorMessage) => {}
    ).catch(err => {
        document.getElementById('scan-status').innerText = 'Gagal membuka kamera: ' + err;
        document.getElementById('scan-status').style.color = '#dd4b39';
    });
}

function stopIsbnScanner() {
    if (html5QrCode && html5QrCode.isScanning) {
        html5QrCode.stop().then(() => {
            document.getElementById('isbn-scanner-modal').style.display = 'none';
        }).catch(err => {
            document.getElementById('isbn-scanner-modal').style.display = 'none';
        });
    } else {
        document.getElementById('isbn-scanner-modal').style.display = 'none';
    }
}
</script>
@endpush
@endsection
