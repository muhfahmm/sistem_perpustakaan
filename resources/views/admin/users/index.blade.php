@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')
<header style="margin-bottom: 20px;">
    <h1>Manajemen User / Anggota</h1>
</header>

<section class="panel">
    <div class="panel-heading">
        <h2>Daftar Anggota Peminjam</h2>
        <form method="GET" action="{{ route('admin.users.index') }}" style="display: flex; gap: 8px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, WA..." style="padding: 6px 10px; font-size: 0.8rem; border: 1px solid #d2d6de; border-radius: 3px;">
            <button type="submit" class="btn btn-secondary">Cari</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Anggota</th>
                <th>Email</th>
                <th>No. WhatsApp</th>
                <th>Status</th>
                <th>Terdaftar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td><strong style="color: #222;">{{ $user->name }}</strong></td>
                    <td>{{ $user->email }}</td>
                    <td><code>{{ $user->phone }}</code></td>
                    <td>
                        @if ($user->is_active)
                            <span style="display: inline-block; padding: 2px 8px; background: #d4edda; color: #155724; border-radius: 3px; font-size: 0.72rem; font-weight: 600;">Aktif</span>
                        @else
                            <span style="display: inline-block; padding: 2px 8px; background: #f8d7da; color: #721c24; border-radius: 3px; font-size: 0.72rem; font-weight: 600;">Nonaktif</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-secondary" style="font-size: 0.75rem;">Edit Status</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #68777d; padding: 24px;">Belum ada anggota terdaftar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 16px;">
        {{ $users->links() }}
    </div>
</section>
@endsection
