@extends('layouts.admin')

@section('title', 'Tambah Buku Baru')

@push('styles')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
@endpush

@section('content')
<header style="margin-bottom: 20px;">
    <h1>Tambah Buku Baru</h1>
</header>

<section class="panel" style="max-width: 650px;">
    <div style="background: #eef8f4; border-left: 4px solid #00a65a; padding: 10px 14px; margin-bottom: 16px; border-radius: 3px; font-size: 0.8rem; color: #1e5e3a;">
        🔌 <strong>Hardware Barcode Scanner Ready:</strong> Tempelkan kursor atau langsung scan barcode ISBN pada buku. Sistem akan otomatis melakukan lookup detail buku dan mengisi kolom secara otomatis.
    </div>

    <form id="createBookForm" method="POST" action="{{ route('admin.books.store') }}">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr auto; gap: 14px; margin-bottom: 14px; align-items: end;">
            <div style="flex: 1;">
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">ISBN / Barcode Buku</label>
                <div style="display: flex; gap: 6px;">
                    <input type="text" id="isbn_input" name="isbn" value="{{ old('isbn') }}" autofocus placeholder="Scan barcode buku di sini..." style="flex: 1; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.95rem; font-weight: 600; font-family: monospace;">
                    <button type="button" onclick="performIsbnLookup()" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.8rem;" title="Lookup Data ISBN">🔍 Cari</button>
                    <button type="button" onclick="startIsbnScanner()" class="btn btn-secondary" style="padding: 6px 10px; font-size: 0.78rem;" title="Scan Barcode ISBN via Kamera HP / Webcam">📷 Kamera</button>
                </div>
                <div id="isbn_lookup_status" style="margin-top: 4px; font-size: 0.75rem; font-weight: 600;"></div>
                @error('isbn') <span style="color: #dd4b39; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Stok Buku</label>
                <input type="number" name="stok" value="{{ old('stok', 1) }}" min="1" required style="width: 100px; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
                @error('stok') <span style="color: #dd4b39; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div style="margin-bottom: 14px;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Judul Buku <span style="color:#dd4b39;">*</span></label>
            <input type="text" id="judul_input" name="judul" value="{{ old('judul') }}" required style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
            @error('judul') <span style="color: #dd4b39; font-size: 0.75rem;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 14px;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Penulis <span style="color:#dd4b39;">*</span></label>
            <input type="text" id="penulis_input" name="penulis" value="{{ old('penulis') }}" required style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
            @error('penulis') <span style="color: #dd4b39; font-size: 0.75rem;">{{ $message }}</span> @enderror
        </div>

        @if(isset($categories) && count($categories) > 0)
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Kategori Buku</label>
            <select name="kategori_id" id="kategori_select" style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('kategori_id') == $cat->id ? 'selected' : '' }}>{{ $cat->kategori }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Simpan Buku</button>
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

const isbnInput = document.getElementById('isbn_input');
const judulInput = document.getElementById('judul_input');
const penulisInput = document.getElementById('penulis_input');
const statusDiv = document.getElementById('isbn_lookup_status');

// Focus on page load
window.addEventListener('DOMContentLoaded', () => {
    if (isbnInput) isbnInput.focus();
});

// Hardware Scanner Event Handling on ISBN Input
if (isbnInput) {
    isbnInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const code = isbnInput.value.trim();
            if (code.length >= 8) {
                performIsbnLookup();
            } else {
                judulInput.focus();
            }
        }
    });
}

async function performIsbnLookup() {
    const code = isbnInput.value.trim();
    if (!code) return;

    statusDiv.style.color = '#00a65a';
    statusDiv.innerText = '⏳ Mencari metadata buku...';

    try {
        const response = await fetch(`{{ route('admin.books.lookup_isbn') }}?isbn=${encodeURIComponent(code)}`);
        const data = await response.json();

        if (data.found) {
            playAudioBeep(true);
            statusDiv.style.color = '#00a65a';
            statusDiv.innerText = '✓ ' + data.message;
            if (data.title) judulInput.value = data.title;
            if (data.author) penulisInput.value = data.author;
            
            if (!judulInput.value) {
                judulInput.focus();
            } else if (!penulisInput.value) {
                penulisInput.focus();
            } else {
                document.getElementById('createBookForm').querySelector('button[type="submit"]').focus();
            }
        } else {
            playAudioBeep(false);
            statusDiv.style.color = '#f39c12';
            statusDiv.innerText = 'ℹ ' + data.message;
            judulInput.focus();
        }
    } catch (err) {
        statusDiv.style.color = '#dd4b39';
        statusDiv.innerText = '✕ Gagal melakukan koneksi lookup.';
        judulInput.focus();
    }
}

function startIsbnScanner() {
    const modal = document.getElementById('isbn-scanner-modal');
    modal.style.display = 'flex';
    document.getElementById('scan-status').innerText = 'Kamera aktif. Silakan scan barcode...';
    document.getElementById('scan-status').style.color = '#00a65a';

    if (!html5QrCode) {
        html5QrCode = new Html5Qrcode("isbn-reader");
    }

    const config = {
        fps: 25,
        qrbox: (viewfinderWidth, viewfinderHeight) => {
            const minEdge = Math.min(viewfinderWidth, viewfinderHeight);
            const width = Math.floor(minEdge * 0.85);
            const height = Math.floor(minEdge * 0.5);
            return { width: width, height: height };
        },
        aspectRatio: 1.333,
        formatsToSupport: [
            Html5QrcodeSupportedFormats.EAN_13,
            Html5QrcodeSupportedFormats.EAN_8,
            Html5QrcodeSupportedFormats.CODE_128,
            Html5QrcodeSupportedFormats.UPC_A,
            Html5QrcodeSupportedFormats.UPC_E,
            Html5QrcodeSupportedFormats.QR_CODE
        ],
        experimentalFeatures: {
            useBarCodeDetectorIfSupported: true
        }
    };

    const cameraConfig = { facingMode: "environment" };

    html5QrCode.start(
        cameraConfig,
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
        html5QrCode.start(
            { facingMode: "user" },
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
                setTimeout(() => { stopIsbnScanner(); }, 600);
            },
            (errorMessage) => {}
        ).catch(err2 => {
            document.getElementById('scan-status').innerText = 'Gagal membuka kamera: ' + err2;
            document.getElementById('scan-status').style.color = '#dd4b39';
        });
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
