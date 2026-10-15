<aside class="admin-sidebar p-3">
    <a class="admin-brand d-flex align-items-center gap-2 px-2 mb-3" href="{{ route('admin.dashboard') }}" aria-label="Perpustakaan SMK Al-Islam Surakarta">
        <img class="admin-brand-logo" src="{{ asset('images/logo-sekolah.png') }}" alt="Logo SMK Al-Islam Surakarta">
        <div class="d-flex flex-column leading-tight">
            <span class="fw-bold" style="font-size: 0.95rem; color: #166e4a; line-height: 1.2;">Perpustakaan Digital</span>
            <span class="text-muted" style="font-size: 0.75rem; line-height: 1.2;">SMK Al-Islam Surakarta</span>
        </div>
    </a>

    @include('layouts.partials.sidebar-navigation')

    <div class="mt-auto border-top pt-3 px-2">
        @include('layouts.partials.sidebar-logout')
    </div>
</aside>
