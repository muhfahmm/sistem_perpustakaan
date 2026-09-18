@extends('layouts.admin')

@section('title', 'Edit Buku')

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
            <input type="text" name="judul" value="{{ old('judul', $book->judul) }}" required style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
            @error('judul') <span style="color: #dd4b39; font-size: 0.75rem;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 14px;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Penulis</label>
            <input type="text" name="penulis" value="{{ old('penulis', $book->penulis) }}" required style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
            @error('penulis') <span style="color: #dd4b39; font-size: 0.75rem;">{{ $message }}</span> @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">ISBN</label>
                <input type="text" id="isbn_input" name="isbn" value="{{ old('isbn', $book->isbn) }}" style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
                @error('isbn') <span style="color: #dd4b39; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Stok Total</label>
                <input type="number" name="stok" value="{{ old('stok', $book->stok) }}" min="0" required style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
                @error('stok') <span style="color: #dd4b39; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
        </div>

        @if(isset($categories) && count($categories) > 0)
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Kategori Buku</label>
            <select name="kategori_id" style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('kategori_id', $book->kategori_id) == $cat->id ? 'selected' : '' }}>{{ $cat->kategori }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Perbarui Buku</button>
            <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</section>
@endsection
