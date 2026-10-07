<form method="POST" action="{{ route('admin.logout') }}">
    @csrf
    <button type="submit" class="nav-link w-100 text-start text-danger">
        <i class="bi bi-box-arrow-right" aria-hidden="true"></i> Keluar
    </button>
</form>
