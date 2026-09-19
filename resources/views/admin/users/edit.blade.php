@extends('layouts.admin')

@section('title', 'Edit Data Peminjam')

@section('content')
<header style="margin-bottom: 20px;">
    <h1>Edit Data Peminjam</h1>
</header>

<section class="panel" style="max-width: 550px;">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 14px;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Nama Lengkap <span style="color:#dd4b39;">*</span></label>
            <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" required style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
            @error('nama') <span style="color: #dd4b39; font-size: 0.75rem;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 14px;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Email <span style="color:#dd4b39;">*</span></label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
            @error('email') <span style="color: #dd4b39; font-size: 0.75rem;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 14px;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Nomor WhatsApp / Telepon <span style="color:#dd4b39;">*</span></label>
            <input type="text" name="telepon" value="{{ old('telepon', $user->telepon) }}" required style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
            @error('telepon') <span style="color: #dd4b39; font-size: 0.75rem;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 14px;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Ubah Password (Kosongkan jika tidak ingin diubah)</label>
            <input type="password" name="password" placeholder="Minimal 6 karakter" style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
            @error('password') <span style="color: #dd4b39; font-size: 0.75rem;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Status Keaktifan <span style="color:#dd4b39;">*</span></label>
            <select name="status_aktif" style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
                <option value="1" {{ old('status_aktif', $user->status_aktif) ? 'selected' : '' }}>Aktif (Bisa Meminjam Buku)</option>
                <option value="0" {{ !old('status_aktif', $user->status_aktif) ? 'selected' : '' }}>Nonaktif (Diblokir)</option>
            </select>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Perbarui Peminjam</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</section>
@endsection
