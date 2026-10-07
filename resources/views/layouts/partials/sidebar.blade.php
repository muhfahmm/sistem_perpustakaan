<aside class="admin-sidebar p-3">
    <a class="admin-brand d-flex align-items-center gap-2 px-2 mb-3" href="{{ route('admin.dashboard') }}" aria-label="Perpustakaan SMK Al-Islam Surakarta">
        <img class="admin-brand-logo" src="{{ asset('images/logo-sekolah.png') }}" alt="Logo SMK Al-Islam Surakarta">
        <span>Perpustakaan SMK Al-Islam Surakarta</span>
    </a>

    @include('layouts.partials.sidebar-navigation')

    <div class="mt-auto border-top pt-3 px-2">
        @include('layouts.partials.sidebar-logout')
    </div>
</aside>
