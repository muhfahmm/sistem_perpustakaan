@php
    /** @var \App\Models\Admin|null $currentAdmin */
    $currentAdmin = auth('admin')->user();
    $adminUsername = $currentAdmin->username ?? 'Admin';
    $adminInitial = strtoupper(substr($adminUsername, 0, 1));
@endphp

<div class="topbar">
    <div class="topbar-brand-sub">Panel Administrasi Perpustakaan</div>
    <div class="topbar-user">
        <span>{{ $adminUsername }}</span>
        <span class="topbar-avatar">{{ $adminInitial }}</span>
    </div>
</div>
