@php
    /** @var \App\Models\Admin|null $currentAdmin */
    $currentAdmin = auth('admin')->user();
    $adminUsername = $currentAdmin->username ?? 'Admin';
    $adminInitial = strtoupper(substr($adminUsername, 0, 1));
@endphp

<aside class="admin-sidebar p-3">
    <a class="admin-brand d-flex align-items-center gap-2 px-2 mb-3" href="{{ route('admin.dashboard') }}" aria-label="Perpustakaan SMK Al-Islam Surakarta">
        <img class="admin-brand-logo" src="{{ asset('images/logo-sekolah.png') }}" alt="Logo SMK Al-Islam Surakarta">
        <span>Perpustakaan SMK Al-Islam Surakarta</span>
    </a>

    <div class="admin-profile d-flex align-items-center gap-3 px-2 py-3 mb-3">
        <span class="admin-avatar rounded-circle d-inline-flex align-items-center justify-content-center fw-semibold">{{ $adminInitial }}</span>
        <div class="min-w-0">
            <div class="text-body fw-semibold text-truncate">{{ $adminUsername }}</div>
            <div class="small text-secondary">Administrator</div>
        </div>
    </div>

    @include('layouts.partials.sidebar-navigation')

    <div class="mt-auto border-top pt-3 px-2">
        @include('layouts.partials.sidebar-logout')
    </div>
</aside>

<div class="offcanvas offcanvas-start admin-mobile-sidebar" tabindex="-1" id="adminMobileSidebar" aria-labelledby="adminMobileSidebarLabel">
    <div class="offcanvas-header border-bottom">
        <a class="admin-brand d-flex align-items-center gap-2" href="{{ route('admin.dashboard') }}" id="adminMobileSidebarLabel" aria-label="Perpustakaan SMK Al-Islam Surakarta">
            <img class="admin-brand-logo" src="{{ asset('images/logo-sekolah.png') }}" alt="Logo SMK Al-Islam Surakarta">
            <span>Perpustakaan SMK Al-Islam Surakarta</span>
        </a>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Tutup menu"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column p-3">
        <div class="admin-profile d-flex align-items-center gap-3 px-2 py-3 mb-3">
            <span class="admin-avatar rounded-circle d-inline-flex align-items-center justify-content-center fw-semibold">{{ $adminInitial }}</span>
            <div class="min-w-0">
                <div class="text-body fw-semibold text-truncate">{{ $adminUsername }}</div>
                <div class="small text-secondary">Administrator</div>
            </div>
        </div>
        @include('layouts.partials.sidebar-navigation')
        <div class="mt-auto border-top pt-3 px-2">
            @include('layouts.partials.sidebar-logout')
        </div>
    </div>
</div>
