@extends('layouts.admin')

@section('title', 'Pengaturan WA Gateway')

@section('content')
<header style="margin-bottom: 20px;">
    <h1>Pengaturan Gateway WhatsApp</h1>
</header>

<section class="panel" style="max-width: 600px;">
    <form method="POST" action="#">
        @csrf
        <div style="margin-bottom: 14px;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Provider WA Gateway</label>
            <select name="provider" style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
                <option value="fonnte">Fonnte (fonnte.com)</option>
                <option value="wablas">Wablas (wablas.com)</option>
                <option value="woowa">WooWA</option>
            </select>
        </div>

        <div style="margin-bottom: 14px;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">API Token / Key</label>
            <input type="password" name="api_token" value="****************" style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Template Pesan Pengingat Jatuh Tempo</label>
            <textarea name="reminder_template" rows="4" style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 3px; font-size: 0.85rem;">Halo {nama}, pengingat bahwa buku {judul} jatuh tempo pada {tanggal_jatuh_tempo}. Harap segera melakukan pengembalian di perpustakaan.</textarea>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
            <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</section>
@endsection
