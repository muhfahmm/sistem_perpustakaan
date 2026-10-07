@php
    /** @var \App\Models\Admin|null $currentAdmin */
    $currentAdmin = auth('admin')->user();
    $adminUsername = $currentAdmin->username ?? 'Admin';
    $adminInitial = strtoupper(substr($adminUsername, 0, 1));
@endphp

<div class="topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-outline-secondary admin-menu-toggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminMobileSidebar" aria-controls="adminMobileSidebar" aria-label="Buka menu navigasi">
            <i class="bi bi-list" aria-hidden="true"></i>
        </button>
        <a class="topbar-brand d-flex align-items-center gap-2 text-decoration-none" href="{{ route('admin.dashboard') }}">
            <img class="topbar-brand-logo" src="{{ asset('images/logo-sekolah.png') }}" alt="Logo SMK Al-Islam Surakarta">
            <span class="topbar-brand-name">Perpustakaan SMK Al-Islam Surakarta</span>
        </a>
    </div>
    <div class="topbar-user">
        <span>{{ $adminUsername }}</span>
        <span class="topbar-avatar">{{ $adminInitial }}</span>
    </div>
</div>
