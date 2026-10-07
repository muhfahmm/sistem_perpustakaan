@extends('layouts.admin')

@section('title', 'Tambah Buku Baru')

@section('content')
<header class="admin-page-heading">
    <div>
        <h1 class="admin-page-title">Tambah Buku</h1>
        <p>Masukkan identitas bibliografi dan jumlah koleksi.</p>
    </div>
    <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Kembali ke daftar</a>
</header>

<section class="admin-form-card p-3 p-md-4 book-form">
    <form id="createBookForm" method="POST" action="{{ route('admin.books.store') }}">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr auto; gap: 14px; margin-bottom: 14px; align-items: end;">
            <div style="flex: 1;">
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">ISBN / Barcode Buku</label>
                <input type="text" id="isbn_input" name="isbn" value="{{ old('isbn') }}" autofocus placeholder="Scan barcode buku di sini..." style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.95rem; font-weight: 600; font-family: monospace;">
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

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Penulis <span style="color:#dd4b39;">*</span></label>
                <input type="text" id="penulis_input" name="penulis" value="{{ old('penulis') }}" required maxlength="255" style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px;">
                @error('penulis') <span style="color: #dd4b39; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Penerbit <span style="color:#dd4b39;">*</span></label>
                <input type="text" id="penerbit_input" name="penerbit" value="{{ old('penerbit') }}" required maxlength="150" style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px;">
                @error('penerbit') <span style="color: #dd4b39; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 14px;">
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Kota terbit</label>
                <input type="text" id="kota_terbit_input" name="kota_terbit" value="{{ old('kota_terbit') }}" maxlength="100" style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px;">
                @error('kota_terbit') <span style="color: #dd4b39; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Tahun terbit <span style="color:#dd4b39;">*</span></label>
                <input type="number" id="tahun_terbit_input" name="tahun_terbit" value="{{ old('tahun_terbit') }}" min="1000" max="{{ now()->year + 1 }}" required style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px;">
                @error('tahun_terbit') <span style="color: #dd4b39; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Edisi / cetakan</label>
                <input type="text" name="edisi" value="{{ old('edisi') }}" maxlength="50" placeholder="Contoh: Edisi ke-2" style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px;">
                @error('edisi') <span style="color: #dd4b39; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 14px;">
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Jumlah halaman</label>
                <input type="number" id="jumlah_halaman_input" name="jumlah_halaman" value="{{ old('jumlah_halaman') }}" min="1" max="65535" style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px;">
                @error('jumlah_halaman') <span style="color: #dd4b39; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Bahasa</label>
                <input type="text" id="bahasa_input" name="bahasa" value="{{ old('bahasa') }}" maxlength="50" placeholder="Contoh: Indonesia" style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px;">
                @error('bahasa') <span style="color: #dd4b39; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Klasifikasi</label>
                <input type="text" name="klasifikasi" value="{{ old('klasifikasi') }}" maxlength="30" placeholder="DDC / nomor panggil" style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px;">
                @error('klasifikasi') <span style="color: #dd4b39; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div style="margin-bottom: 14px;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Lokasi rak</label>
            <input type="text" name="lokasi_rak" value="{{ old('lokasi_rak') }}" maxlength="60" placeholder="Contoh: Rak A-02" style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px;">
            @error('lokasi_rak') <span style="color: #dd4b39; font-size: 0.75rem;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Sinopsis / catatan</label>
            <textarea id="deskripsi_input" name="deskripsi" rows="3" maxlength="10000" style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px;">{{ old('deskripsi') }}</textarea>
            @error('deskripsi') <span style="color: #dd4b39; font-size: 0.75rem;">{{ $message }}</span> @enderror
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

@push('styles')
<style>
    .book-form label { display: block !important; margin-bottom: .4rem !important; color: #34483d; font-size: .86rem !important; font-weight: 600 !important; }
    .book-form input:not([type=hidden]), .book-form select, .book-form textarea { width: 100% !important; min-height: 42px; padding: .55rem .75rem !important; color: #212529; background-color: #fff; border: 1px solid #d8e2dc !important; border-radius: 7px !important; font: inherit !important; font-size: .9rem !important; }
    .book-form textarea { min-height: 100px; }
    .book-form input:focus, .book-form select:focus, .book-form textarea:focus { outline: 0; border-color: #198754 !important; box-shadow: 0 0 0 .2rem rgba(25, 135, 84, .13); }
    .book-form form > div[style*="grid-template-columns"] { grid-template-columns: repeat(2, minmax(0, 1fr)) !important; }
    .book-form form > div[style*="grid-template-columns: 1fr auto"] { grid-template-columns: minmax(0, 1fr) 120px !important; }
    .book-form button[type=submit] { background: #198754; }
    @media (max-width: 575.98px) {
        .book-form form > div[style*="grid-template-columns"] { grid-template-columns: 1fr !important; }
    }
</style>
@endpush

@push('scripts')
<script>
const isbnInput = document.getElementById('isbn_input');
const judulInput = document.getElementById('judul_input');

// Focus on page load
window.addEventListener('DOMContentLoaded', () => {
    if (isbnInput) isbnInput.focus();
});

// Hardware Scanner Event Handling on ISBN Input
if (isbnInput) {
    isbnInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            judulInput.focus();
        }
    });
}
</script>
@endpush
@endsection
