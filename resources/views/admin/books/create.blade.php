@extends('layouts.admin')

@section('title', 'Tambah Buku Baru')

@section('content')
<header style="margin-bottom: 20px;">
    <h1>Tambah Buku Baru</h1>
</header>

<section class="panel" style="max-width: 650px;">
    <div style="background: #eef8f4; border-left: 4px solid #00a65a; padding: 10px 14px; margin-bottom: 16px; border-radius: 3px; font-size: 0.8rem; color: #1e5e3a;">
        🔌 <strong>Hardware Barcode Scanner Ready:</strong> Lakukan scan barcode ISBN pada buku. Detail buku akan terisi secara otomatis.
    </div>

    <form id="createBookForm" method="POST" action="{{ route('admin.books.store') }}">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr auto; gap: 14px; margin-bottom: 14px; align-items: end;">
            <div style="flex: 1;">
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">ISBN / Barcode Buku</label>
                <input type="text" id="isbn_input" name="isbn" value="{{ old('isbn') }}" autofocus placeholder="Scan barcode buku di sini..." style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.95rem; font-weight: 600; font-family: monospace;">
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

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Kategori Buku <span style="color:#dd4b39;">*</span></label>
            <select name="kategori_id" id="kategori_select" required style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
                <option value="">-- Pilih Kategori --</option>
                @if(isset($categories))
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('kategori_id') == $cat->id ? 'selected' : '' }}>{{ $cat->kategori }}</option>
                    @endforeach
                @endif
            </select>
            @error('kategori_id') <span style="color: #dd4b39; font-size: 0.75rem; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Simpan Buku</button>
            <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
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

const isbnInput = document.getElementById('isbn_input');
const judulInput = document.getElementById('judul_input');
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
            
            if (!judulInput.value) {
                judulInput.focus();
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
</script>
@endpush
@endsection
