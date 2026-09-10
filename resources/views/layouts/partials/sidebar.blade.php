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
        <div class="nav-label">Main navigation</div>

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="icon">⌂</span> Dashboard
        </a>

        <!-- 1. Manajemen Buku -->
        <a href="{{ route('admin.books.index') }}" class="{{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
            <span class="icon">▤</span> Manajemen Buku
        </a>

        <!-- 2. Manajemen User -->
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <span class="icon">♙</span> Manajemen User
        </a>

        <!-- 3. Peminjaman -->
        <a href="{{ route('admin.loans.index') }}" class="{{ request()->routeIs('admin.loans.*') ? 'active' : '' }}">
            <span class="icon">↔</span> Peminjaman
        </a>

        <!-- 4. Pengembalian -->
        <a href="{{ route('admin.returns.index') }}" class="{{ request()->routeIs('admin.returns.*') ? 'active' : '' }}">
            <span class="icon">✓</span> Pengembalian
        </a>

        <!-- 5. QR Code -->
        <a href="{{ route('admin.qrcode.index') }}" class="{{ request()->routeIs('admin.qrcode.*') ? 'active' : '' }}">
            <span class="icon">▦</span> QR Code
        </a>

        <!-- 6. Notifikasi WA -->
        <a href="{{ route('admin.notifications.index') }}" class="{{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
            <span class="icon">◌</span> Notifikasi WA
        </a>

        <!-- 7. Laporan -->
        <a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <span class="icon">▥</span> Laporan
        </a>

        <div class="nav-label">Setting</div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit"><span class="icon">↪</span> Logout</button>
        </form>
    </nav>

    <div class="sidebar-footer">
        Panel admin<br>Perpustakaan digital
    </div>
</aside>
