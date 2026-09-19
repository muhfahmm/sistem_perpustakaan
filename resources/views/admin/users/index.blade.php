@extends('layouts.admin')

@section('title', 'Manajemen Peminjam')

@section('content')
<header style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
    <h1>Manajemen Peminjam / Anggota</h1>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Tambah Peminjam Baru</a>
</header>

<section class="panel">
    <div class="panel-heading">
        <h2>Daftar User Peminjam</h2>
        <form method="GET" action="{{ route('admin.users.index') }}" style="display: flex; gap: 8px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, WA..." style="padding: 6px 10px; font-size: 0.8rem; border: 1px solid #d2d6de; border-radius: 3px;">
            <button type="submit" class="btn btn-secondary">Cari</button>
        </form>
    </div>

    <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
        <thead>
            <tr>
                <th style="width: 60px; text-align: center;">No</th>
                <th>Nama Peminjam</th>
                <th>Email</th>
                <th>No. WhatsApp</th>
                <th style="width: 120px; text-align: center;">Status</th>
                <th style="width: 140px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $index => $user)
                <tr>
                    <td style="text-align: center; color: #64748b;">{{ $users->firstItem() + $index }}</td>
                    <td><strong style="color: #0f172a; font-size: 0.9rem;">{{ $user->nama }}</strong></td>
                    <td>{{ $user->email }}</td>
                    <td><code>{{ $user->telepon }}</code></td>
                    <td style="text-align: center;">
                        @if ($user->status_aktif)
                            <span style="display: inline-block; padding: 3px 10px; background: #dcfce7; color: #15803d; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">Aktif</span>
                        @else
                            <span style="display: inline-block; padding: 3px 10px; background: #fee2e2; color: #991b1b; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">Nonaktif</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 6px; justify-content: center;">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-secondary" style="padding: 4px 10px; font-size: 0.75rem;">Edit</a>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline-block;" onsubmit="return confirm('Yakin ingin menghapus peminjam ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary" style="padding: 4px 10px; font-size: 0.75rem; color: #dc2626; border-color: #fecaca; background: #fff5f5;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #64748b; padding: 28px;">Belum ada peminjam terdaftar. Klik "+ Tambah Peminjam Baru" untuk mendaftar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 16px;">
        {{ $users->links() }}
    </div>
</section>
@endsection
