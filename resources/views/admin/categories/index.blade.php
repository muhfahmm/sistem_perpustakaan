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

    <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
        <thead>
            <tr>
                <th style="width: 70px; text-align: center;">No</th>
                <th>Nama Kategori</th>
                <th style="width: 180px; text-align: center;">Total Koleksi Buku</th>
                <th style="width: 140px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $index => $cat)
                <tr>
                    <td style="text-align: center; color: #64748b; font-weight: 500;">{{ $categories->firstItem() + $index }}</td>
                    <td><strong style="color: #0f172a; font-size: 0.9rem;">{{ $cat->kategori }}</strong></td>
                    <td style="text-align: center;">
                        <span style="display: inline-block; padding: 4px 10px; background: #e0f2fe; color: #0369a1; border-radius: 12px; font-size: 0.78rem; font-weight: 600;">{{ $cat->total_buku }} Buku</span>
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 6px; justify-content: center;">
                            <button type="button" onclick="openEditModal({{ $cat->id }}, '{{ addslashes($cat->kategori) }}')" class="btn btn-secondary" style="padding: 4px 10px; font-size: 0.75rem;">Edit</button>
                            <form method="POST" action="{{ route('admin.categories.destroy', $cat->id) }}" style="display: inline-block;" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary" style="padding: 4px 10px; font-size: 0.75rem; color: #dc2626; border-color: #fecaca; background: #fff5f5;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #64748b; padding: 28px;">Belum ada data kategori. Klik "+ Tambah Kategori" untuk membuat.</td>
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
