@extends('layouts.admin')

@section('title', 'Manajemen Buku')

@section('content')
<header class="admin-page-heading">
    <div>
        <h1 class="admin-page-title">Manajemen Buku</h1>
        <p>Kelola identitas bibliografi, stok, dan lokasi koleksi.</p>
    </div>
    <a href="{{ route('admin.books.create') }}" class="btn btn-success"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Tambah buku</a>
</header>

<section class="admin-page-card">
    <div class="admin-page-card-header">
        <div>
            <h2 class="h6 fw-semibold mb-1">Koleksi buku</h2>
            <span class="small text-secondary">{{ number_format($books->total()) }} judul terdaftar</span>
        </div>
        <form method="GET" action="{{ route('admin.books.index') }}" class="d-flex gap-2">
            <label for="bookSearch" class="visually-hidden">Cari judul, penulis, ISBN, atau lokasi</label>
            <input id="bookSearch" type="search" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari buku..." style="min-width: 220px;">
            <button type="submit" class="btn btn-sm btn-outline-success">Cari</button>
            @if (request('search'))
                <a href="{{ route('admin.books.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover admin-table">
            <thead>
                <tr>
                    <th>Identitas buku</th>
                    <th>ISBN / klasifikasi</th>
                    <th>Lokasi rak</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">QR buku</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($books as $book)
                    <tr>
                        <td>
                            <div class="fw-semibold text-dark">{{ $book->judul }}</div>
                            <div class="small text-secondary">{{ $book->penulis ?: 'Penulis belum dicatat' }}</div>
                            <div class="small text-secondary">
                                {{ $book->penerbit ?: 'Penerbit belum dicatat' }}{{ $book->tahun_terbit ? ', '.$book->tahun_terbit : '' }}
                                · {{ $book->category?->kategori ?? 'Tanpa kategori' }}
                            </div>
                            @if (!$book->penulis || !$book->penerbit || !$book->tahun_terbit)
                                <span class="badge rounded-pill text-bg-warning mt-1">Metadata belum lengkap</span>
                            @endif
                        </td>
                        <td>
                            <code>{{ $book->isbn ?? '-' }}</code>
                            <div class="small text-secondary">{{ $book->klasifikasi ?: 'Belum diklasifikasi' }}</div>
                            @if ($book->isbn && !\App\Support\Isbn::isValid($book->isbn))
                                <span class="small text-danger">Periksa ISBN</span>
                            @endif
                        </td>
                        <td>{{ $book->lokasi_rak ?: '-' }}</td>
                        <td class="text-center text-nowrap">{{ $book->tersedia }} / {{ $book->stok }}</td>
                        <td class="text-center">
                            @php
                                $bookQrValue = $book->isbn ?: 'BOOK-'.$book->id;
                            @endphp
                            <div class="book-qr-code mx-auto" data-qr-value="{{ $bookQrValue }}" aria-label="QR buku {{ $book->judul }}"></div>
                            <code class="small">{{ $bookQrValue }}</code>
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-sm btn-outline-success">Edit</a>
                            <form method="POST" action="{{ route('admin.books.destroy', $book) }}" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-5 text-center text-secondary">
                            {{ request('search') ? 'Tidak ada buku yang cocok dengan pencarian.' : 'Belum ada koleksi buku. Tambahkan buku pertama untuk mulai mengisi katalog.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($books->hasPages())
        <div class="d-flex justify-content-center border-top p-3">
            {{ $books->withQueryString()->onEachSide(1)->links() }}
        </div>
    @endif
</section>

@push('styles')
<style>
    .book-qr-code { display: grid; width: 88px; height: 88px; margin-bottom: 4px; place-items: center; }
    .book-qr-code img, .book-qr-code canvas { display: block; width: 80px; height: 80px; }
</style>
@endpush

@push('vendor-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
@endpush

@push('scripts')
<script>
document.querySelectorAll('[data-qr-value]').forEach((container) => {
    const value = container.dataset.qrValue;
    if (typeof QRCode !== 'function') {
        container.textContent = value;
        container.classList.add('text-danger', 'small');
        return;
    }

    new QRCode(container, {
        text: value,
        width: 80,
        height: 80,
        colorDark: '#111827',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.M
    });
});
</script>
@endpush

@if (session('cannot_delete_book'))
    <div class="modal fade" id="cannotDeleteBookModal" tabindex="-1" aria-labelledby="cannotDeleteBookModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h2 class="modal-title fs-5 text-danger" id="cannotDeleteBookModalLabel">Buku tidak dapat dihapus</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="fw-semibold">{{ session('cannot_delete_book')['title'] }}</div>
                    <div class="small text-secondary mb-3">ISBN: {{ session('cannot_delete_book')['isbn'] }}</div>
                    <p class="mb-0">{{ session('cannot_delete_book')['message'] }}</p>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('admin.loans.index', ['status' => 'semua', 'search' => session('cannot_delete_book')['title']]) }}" class="btn btn-outline-success">Lihat riwayat peminjaman</a>
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Mengerti</button>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>bootstrap.Modal.getOrCreateInstance(document.getElementById('cannotDeleteBookModal')).show();</script>
    @endpush
@endif
@endsection
