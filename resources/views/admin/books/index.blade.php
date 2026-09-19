@extends('layouts.admin')

@section('title', 'Manajemen Buku')

@section('content')
<header style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
    <h1>Manajemen Buku</h1>
    <a href="{{ route('admin.books.create') }}" class="btn btn-primary">+ Tambah Buku Baru</a>
</header>

<section class="panel">
    <div class="panel-heading">
        <h2>Daftar Koleksi Buku</h2>
        <form method="GET" action="{{ route('admin.books.index') }}" style="display: flex; gap: 8px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, ISBN..." style="padding: 6px 10px; font-size: 0.8rem; border: 1px solid #d2d6de; border-radius: 3px;">
            <button type="submit" class="btn btn-secondary">Cari</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Judul Buku</th>
                <th>ISBN</th>
                <th>Stok / Tersedia</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($books as $book)
                <tr>
                    <td>
                        <strong style="color: #222;">{{ $book->judul }}</strong>
                    </td>
                    <td><code>{{ $book->isbn ?? '-' }}</code></td>
                    <td>{{ $book->tersedia }} / {{ $book->stok }} unit</td>
                    <td>
                        <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-secondary" style="font-size: 0.75rem;">Edit</a>
                        <form method="POST" action="{{ route('admin.books.destroy', $book) }}" style="display: inline-block;" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-secondary" style="font-size: 0.75rem; color: #dd4b39;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #68777d; padding: 24px;">Belum ada data buku.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 16px;">
        {{ $books->links() }}
    </div>
</section>

@if (session('cannot_delete_book'))
<!-- MODAL PERINGATAN GAGAL HAPUS BUKU -->
<div id="cannotDeleteBookModal" style="display: flex; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #fff; width: 100%; max-width: 450px; border-radius: 8px; padding: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
        <div style="display: flex; align-items: center; gap: 12px; border-bottom: 2px solid #ef4444; padding-bottom: 12px; margin-bottom: 16px;">
            <span style="font-size: 1.8rem;">🚫</span>
            <div>
                <h3 style="margin: 0; font-size: 1.1rem; color: #0f172a;">Buku Tidak Dapat Dihapus!</h3>
                <p style="margin: 2px 0 0 0; font-size: 0.78rem; color: #64748b;">Proteksi integritas riwayat transaksi perpustakaan.</p>
            </div>
        </div>

        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; padding: 14px; margin-bottom: 20px;">
            <div style="font-weight: 700; color: #991b1b; font-size: 0.95rem;">{{ session('cannot_delete_book')['title'] }}</div>
            <div style="font-size: 0.8rem; color: #7f1d1d; margin-top: 4px;">ISBN: <code>{{ session('cannot_delete_book')['isbn'] }}</code></div>
            <div style="margin-top: 8px; padding-top: 8px; border-top: 1px dashed #fca5a5; font-size: 0.82rem; color: #991b1b; line-height: 1.4;">
                {{ session('cannot_delete_book')['message'] }}
            </div>
        </div>

        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <a href="{{ route('admin.loans.index') }}" class="btn btn-secondary" style="padding: 8px 14px; font-size: 0.85rem;">Cek Data Peminjaman</a>
            <button type="button" onclick="document.getElementById('cannotDeleteBookModal').style.display='none'" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.85rem; background: #ef4444; border-color: #ef4444;">Paham & Tutup</button>
        </div>
    </div>
</div>
@endif
@endsection
