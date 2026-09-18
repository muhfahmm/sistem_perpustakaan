@extends('layouts.admin')

@section('title', 'Kategori Buku')

@section('content')
<header style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
    <h1>Kategori Buku</h1>
    <button type="button" onclick="openAddModal()" class="btn btn-primary">+ Tambah Kategori</button>
</header>

<section class="panel">
    <div class="panel-heading">
        <h2>Daftar Kategori Buku</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 80px;">No</th>
                <th>Nama Kategori</th>
                <th style="width: 160px;">Total Koleksi Buku</th>
                <th style="width: 150px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $index => $cat)
                <tr>
                    <td>{{ $categories->firstItem() + $index }}</td>
                    <td><strong style="color: #222;">{{ $cat->kategori }}</strong></td>
                    <td><span class="status borrowed">{{ $cat->total_buku }} Buku</span></td>
                    <td>
                        <button type="button" onclick="openEditModal({{ $cat->id }}, '{{ addslashes($cat->kategori) }}')" class="btn btn-secondary" style="font-size: 0.75rem;">Edit</button>
                        <form method="POST" action="{{ route('admin.categories.destroy', $cat->id) }}" style="display: inline-block;" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-secondary" style="font-size: 0.75rem; color: #dd4b39;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #68777d; padding: 24px;">Belum ada data kategori. Klik "+ Tambah Kategori" untuk membuat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 16px;">
        {{ $categories->links() }}
    </div>
</section>

<!-- MODAL ADD KATEGORI -->
<div id="addModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #fff; width: 100%; max-width: 420px; border-radius: 4px; border-top: 4px solid #00a65a; padding: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <h3 style="margin-top: 0; font-size: 1.1rem; color: #222; margin-bottom: 14px;">Tambah Kategori Buku</h3>
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Nama Kategori</label>
                <input type="text" name="kategori" required placeholder="Contoh: Novel, Pemrograman, Sejarah" style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeAddModal()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-success">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT KATEGORI -->
<div id="editModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #fff; width: 100%; max-width: 420px; border-radius: 4px; border-top: 4px solid #3c8dbc; padding: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <h3 style="margin-top: 0; font-size: 1.1rem; color: #222; margin-bottom: 14px;">Edit Kategori Buku</h3>
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Nama Kategori</label>
                <input type="text" id="editKategoriInput" name="kategori" required style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeEditModal()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Perbarui Kategori</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openAddModal() {
    document.getElementById('addModal').style.display = 'flex';
}
function closeAddModal() {
    document.getElementById('addModal').style.display = 'none';
}
function openEditModal(id, name) {
    const form = document.getElementById('editForm');
    form.action = '{{ url("admin-panel/categories") }}/' + id;
    document.getElementById('editKategoriInput').value = name;
    document.getElementById('editModal').style.display = 'flex';
}
function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>
@endpush
@endsection
