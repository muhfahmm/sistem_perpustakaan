@extends('layouts.admin')

@section('title', 'Tambah Peminjam')

@section('content')
<header class="admin-page-heading">
    <div>
        <h1 class="admin-page-title">Tambah Peminjam</h1>
        <p>Daftarkan anggota baru ke perpustakaan.</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Kembali ke daftar</a>
</header>

<section class="admin-form-card">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="p-3 p-md-4">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama lengkap <span class="text-danger">*</span></label>
                <input id="nama" type="text" name="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" required maxlength="100" autocomplete="name" placeholder="Nama sesuai identitas">
                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Alamat email <span class="text-danger">*</span></label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required maxlength="150" autocomplete="email" placeholder="nama@contoh.com">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="telepon" class="form-label">Nomor WhatsApp / telepon <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">+62</span>
                    <input id="telepon" type="tel" name="telepon" value="{{ old('telepon') }}" class="form-control @error('telepon') is-invalid @enderror" required maxlength="20" autocomplete="tel-national" placeholder="8123456789">
                    @error('telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div>
                <label for="status_aktif" class="form-label">Status anggota <span class="text-danger">*</span></label>
                <select id="status_aktif" name="status_aktif" class="form-select" required>
                    <option value="1" {{ old('status_aktif', '1') == '1' ? 'selected' : '' }}>Aktif — dapat meminjam</option>
                    <option value="0" {{ old('status_aktif') == '0' ? 'selected' : '' }}>Nonaktif — tidak dapat meminjam</option>
                </select>
            </div>
        </div>
        <div class="d-flex justify-content-end gap-2 border-top bg-light-subtle p-3">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-success"><i class="bi bi-check2 me-1" aria-hidden="true"></i> Simpan anggota</button>
        </div>
    </form>
</section>
@endsection
