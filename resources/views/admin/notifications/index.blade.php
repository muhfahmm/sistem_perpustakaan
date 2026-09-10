@extends('layouts.admin')

@section('title', 'Notifikasi WhatsApp')

@section('content')
<header style="margin-bottom: 20px;">
    <h1>Log Notifikasi WhatsApp</h1>
</header>

<section class="panel">
    <div class="panel-heading">
        <h2>Riwayat Pengiriman Pesan WA</h2>
        <a href="{{ route('admin.notifications.settings') }}" class="btn btn-secondary">Pengaturan Gateway WA</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Penerima</th>
                <th>Tipe Notifikasi</th>
                <th>Pesan WA</th>
                <th>Status Pengiriman</th>
                <th>Waktu Kirim</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($notifications as $notif)
                <tr>
                    <td>
                        <strong style="color: #222;">{{ $notif->user_name ?? '-' }}</strong>
                        <span style="display: block; font-size: 0.75rem; color: #68777d;">{{ $notif->phone }}</span>
                    </td>
                    <td><code>{{ $notif->type }}</code></td>
                    <td style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $notif->message }}</td>
                    <td>
                        @if ($notif->status === 'sent')
                            <span style="display: inline-block; padding: 2px 8px; background: #d4edda; color: #155724; border-radius: 3px; font-size: 0.72rem; font-weight: 600;">Terkirim</span>
                        @elseif ($notif->status === 'failed')
                            <span style="display: inline-block; padding: 2px 8px; background: #f8d7da; color: #721c24; border-radius: 3px; font-size: 0.72rem; font-weight: 600;">Gagal</span>
                        @else
                            <span style="display: inline-block; padding: 2px 8px; background: #fff2d9; color: #966313; border-radius: 3px; font-size: 0.72rem; font-weight: 600;">Pending</span>
                        @endif
                    </td>
                    <td>{{ $notif->created_at ? date('d M Y H:i', strtotime($notif->created_at)) : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #68777d; padding: 24px;">Belum ada log notifikasi WhatsApp.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 16px;">
        {{ $notifications->links() }}
    </div>
</section>
@endsection
