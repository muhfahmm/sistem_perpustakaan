@php
    /** @var \App\Models\Admin|null $currentAdmin */
    $currentAdmin = auth('admin')->user();
    $adminUsername = $currentAdmin->username ?? 'Admin';
    $adminInitial = strtoupper(substr($adminUsername, 0, 1));
@endphp

<aside>
    <div class="brand">
        <span class="brand-mark">P</span> Perpusku
    </div>

    <div class="profile">
        <span class="profile-avatar">{{ $adminInitial }}</span>
        <div>
            <div class="profile-name">{{ $adminUsername }}</div>
            <span class="profile-role">Administrator</span>
        </div>
    </div>

    <nav aria-label="Navigasi admin">
        <div class="nav-label">Navigasi Utama</div>

        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="icon">⌂</span> Dashboard
        </a>

        <div class="nav-label">Katalog & Buku</div>

        <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <span class="icon">🏷</span> Kategori Buku
        </a>

        <a href="{{ route('admin.books.index') }}" class="{{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
            <span class="icon">▤</span> Manajemen Buku
        </a>

        <div class="nav-label">Sirkulasi & Transaksi</div>

        <a href="{{ route('admin.scan.index') }}" class="{{ request()->routeIs('admin.scan.*') ? 'active' : '' }}">
            <span class="icon">⚡</span> Scan Peminjaman (On-Site)
        </a>

        <a href="{{ route('admin.loans.index') }}" class="{{ request()->routeIs('admin.loans.*') ? 'active' : '' }}">
            <span class="icon">↔</span> Daftar Peminjaman
        </a>

        <a href="{{ route('admin.returns.index') }}" class="{{ request()->routeIs('admin.returns.*') ? 'active' : '' }}">
            <span class="icon">✓</span> Pengembalian
        </a>

        <div class="nav-label">Pengguna</div>

        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <span class="icon">♙</span> Manajemen User
        </a>

        <div class="nav-label">Alat & Laporan</div>

        <a href="{{ route('admin.qrcode.index') }}" class="{{ request()->routeIs('admin.qrcode.index') ? 'active' : '' }}">
            <span class="icon">▦</span> Cetak QR Code
        </a>

        <a href="{{ route('admin.notifications.index') }}" class="{{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
            <span class="icon">◌</span> Notifikasi WA
        </a>

        <a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <span class="icon">▥</span> Laporan Sirkulasi
        </a>

        <div class="nav-label">Pengaturan</div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit"><span class="icon">↪</span> Logout</button>
        </form>
    </nav>

    <div class="sidebar-footer">
        Panel Admin<br>Perpustakaan Digital
    </div>
</aside>
