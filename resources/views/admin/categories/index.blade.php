@extends('layouts.admin')

@section('title', 'Kategori Buku')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
@endif
<header class="admin-page-heading">
    <div>
        <h1 class="admin-page-title">Kategori Buku</h1>
        <p>Kelola pengelompokan koleksi perpustakaan.</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Tambah kategori
    </button>
</header>

<section class="admin-page-card">
    <div class="admin-page-card-header">
        <div>
            <h2 class="h6 fw-semibold mb-1">Daftar kategori</h2>
            <span class="small text-secondary">{{ number_format($categories->total()) }} kategori terdaftar</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover admin-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 70px;">No.</th>
                    <th>Nama kategori</th>
                    <th class="text-center">Koleksi</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $index => $cat)
                    <tr>
                        <td class="text-center text-secondary">{{ $categories->firstItem() + $index }}</td>
                        <td class="fw-semibold text-dark">{{ $cat->kategori }}</td>
                        <td class="text-center"><span class="badge rounded-pill text-bg-success-subtle text-success-emphasis">{{ number_format($cat->total_buku) }} buku</span></td>
                        <td class="text-end text-nowrap">
                            <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#editModal" data-category-id="{{ $cat->id }}" data-category-name="{{ $cat->kategori }}">Edit</button>
                            <form method="POST" action="{{ route('admin.categories.destroy', $cat->id) }}" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-5 text-center text-secondary">Belum ada kategori. Tambahkan kategori pertama untuk mulai mengelompokkan buku.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($categories->hasPages())
        <div class="d-flex justify-content-center border-top p-3 category-pagination">
            {{ $categories->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    @endif
</section>

<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="addModalLabel">Tambah kategori</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <label for="newCategoryName" class="form-label">Nama kategori</label>
                    <input id="newCategoryName" type="text" name="kategori" value="{{ old('kategori') }}" class="form-control @error('kategori') is-invalid @enderror" maxlength="100" required placeholder="Contoh: Novel, Sejarah">
                    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="editModalLabel">Edit kategori</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <label for="editKategoriInput" class="form-label">Nama kategori</label>
                    <input type="text" id="editKategoriInput" name="kategori" class="form-control" maxlength="100" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .category-pagination nav { justify-content: center; }
    .category-pagination .pagination { margin-bottom: 0; }
    .category-pagination .page-link { color: #198754; }
    .category-pagination .active > .page-link { color: #fff; background-color: #198754; border-color: #198754; }
</style>
@endpush

@push('scripts')
<script>
document.getElementById('editModal').addEventListener('show.bs.modal', (event) => {
    const button = event.relatedTarget;
    const form = document.getElementById('editForm');
    form.action = `{{ url('admin-panel/categories') }}/${button.dataset.categoryId}`;
    document.getElementById('editKategoriInput').value = button.dataset.categoryName;
});

</script>
@endpush
@endsection
