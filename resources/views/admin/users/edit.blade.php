@extends('layouts.admin')

@section('title', 'Edit Peminjam')

@section('content')
<header class="admin-page-heading">
    <div>
        <h1 class="admin-page-title">Edit Data Peminjam</h1>
        <p>Perbarui informasi kontak dan status keanggotaan.</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Kembali ke daftar</a>
</header>

<section class="admin-form-card">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        <div class="p-3 p-md-4">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama lengkap <span class="text-danger">*</span></label>
                <input id="nama" type="text" name="nama" value="{{ old('nama', $user->nama) }}" class="form-control @error('nama') is-invalid @enderror" required maxlength="100" autocomplete="name">
                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Alamat email <span class="text-danger">*</span></label>
                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required maxlength="150" autocomplete="email">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="telepon" class="form-label">Nomor WhatsApp / telepon <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">+62</span>
                    <input id="telepon" type="tel" name="telepon" value="{{ old('telepon', preg_replace('/^(\+62|62|0)/', '', $user->telepon)) }}" class="form-control @error('telepon') is-invalid @enderror" required maxlength="20" autocomplete="tel-national">
                    @error('telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div>
                <label for="status_aktif" class="form-label">Status anggota <span class="text-danger">*</span></label>
                <select id="status_aktif" name="status_aktif" class="form-select" required>
                    <option value="1" {{ old('status_aktif', $user->status_aktif) ? 'selected' : '' }}>Aktif — dapat meminjam</option>
                    <option value="0" {{ !old('status_aktif', $user->status_aktif) ? 'selected' : '' }}>Nonaktif — tidak dapat meminjam</option>
                </select>
            </div>
        </div>
        <div class="d-flex justify-content-end gap-2 border-top bg-light-subtle p-3">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-success"><i class="bi bi-check2 me-1" aria-hidden="true"></i> Simpan perubahan</button>
        </div>
    </form>
</section>
@endsection
