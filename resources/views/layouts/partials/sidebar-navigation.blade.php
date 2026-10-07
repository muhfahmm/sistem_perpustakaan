<nav class="nav nav-pills flex-column gap-1" aria-label="Navigasi admin">
    <div class="sidebar-heading px-3 mt-2 mb-1">Utama</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-house-door" aria-hidden="true"></i> Dashboard
    </a>

    <div class="sidebar-heading px-3 mt-3 mb-1">Katalog</div>
    <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
        <i class="bi bi-tags" aria-hidden="true"></i> Kategori Buku
    </a>
    <a href="{{ route('admin.books.index') }}" class="nav-link {{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
        <i class="bi bi-journal-bookmark" aria-hidden="true"></i> Manajemen Buku
    </a>

    <div class="sidebar-heading px-3 mt-3 mb-1">Sirkulasi</div>
    <a href="{{ route('admin.loans.index') }}" class="nav-link {{ request()->routeIs('admin.loans.*') ? 'active' : '' }}">
        <i class="bi bi-arrow-left-right" aria-hidden="true"></i> Peminjaman
    </a>
    <a href="{{ route('admin.returns.index') }}" class="nav-link {{ request()->routeIs('admin.returns.*') ? 'active' : '' }}">
        <i class="bi bi-arrow-return-left" aria-hidden="true"></i> Pengembalian
    </a>

    <div class="sidebar-heading px-3 mt-3 mb-1">Anggota &amp; laporan</div>
    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="bi bi-people" aria-hidden="true"></i> Peminjam
    </a>
    <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
        <i class="bi bi-bar-chart" aria-hidden="true"></i> Laporan Sirkulasi
    </a>
</nav>
