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
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, penulis, ISBN..." style="padding: 6px 10px; font-size: 0.8rem; border: 1px solid #d2d6de; border-radius: 3px;">
            <button type="submit" class="btn btn-secondary">Cari</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Judul & Penulis</th>
                <th>Penerbit & Tahun</th>
                <th>ISBN</th>
                <th>Stok / Tersedia</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($books as $book)
                <tr>
                    <td>
                        <strong style="color: #222;">{{ $book->title }}</strong>
                        <span style="display: block; font-size: 0.75rem; color: #68777d;">Penulis: {{ $book->author }}</span>
                    </td>
                    <td>{{ $book->publisher ?? '-' }} ({{ $book->year ?? '-' }})</td>
                    <td><code>{{ $book->isbn ?? '-' }}</code></td>
                    <td>{{ $book->available }} / {{ $book->stock }} unit</td>
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
                    <td colspan="5" style="text-align: center; color: #68777d; padding: 24px;">Belum ada data buku.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 16px;">
        {{ $books->links() }}
    </div>
</section>
@endsection
