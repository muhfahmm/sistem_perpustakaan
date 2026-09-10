@extends('layouts.admin')

@section('title', 'QR Code Generator')

@section('content')
<header style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
    <h1>QR Code Generator Buku</h1>
</header>

<section class="panel">
    <div class="panel-heading">
        <h2>Cetak / Generate QR Code Buku</h2>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px;">
        @forelse ($books as $book)
            <div style="border: 1px solid #dfe4e8; border-radius: 4px; padding: 16px; text-align: center; background: #fafafa;">
                <div style="margin-bottom: 10px; font-weight: 700; color: #222; font-size: 0.9rem; min-height: 40px;">{{ $book->title }}</div>
                <div style="background: #fff; padding: 10px; border: 1px solid #eee; display: inline-block; margin-bottom: 10px;">
                    <!-- QR Code Placeholder / SVG -->
                    <svg width="100" height="100" viewBox="0 0 100 100" fill="none">
                        <rect width="100" height="100" fill="#ffffff"/>
                        <rect x="10" y="10" width="30" height="30" fill="#0f172a"/>
                        <rect x="15" y="15" width="20" height="20" fill="#ffffff"/>
                        <rect x="20" y="20" width="10" height="10" fill="#0f172a"/>
                        <rect x="60" y="10" width="30" height="30" fill="#0f172a"/>
                        <rect x="65" y="15" width="20" height="20" fill="#ffffff"/>
                        <rect x="70" y="20" width="10" height="10" fill="#0f172a"/>
                        <rect x="10" y="60" width="30" height="30" fill="#0f172a"/>
                        <rect x="15" y="65" width="20" height="20" fill="#ffffff"/>
                        <rect x="20" y="70" width="10" height="10" fill="#0f172a"/>
                        <rect x="50" y="50" width="15" height="15" fill="#0f172a"/>
                        <rect x="70" y="70" width="20" height="20" fill="#0f172a"/>
                    </svg>
                </div>
                <div style="font-size: 0.75rem; color: #68777d; margin-bottom: 8px;">ISBN: {{ $book->isbn ?? 'N/A' }}</div>
                <button onclick="window.print()" class="btn btn-secondary" style="font-size: 0.75rem; width: 100%;">Cetak QR</button>
            </div>
        @empty
            <p style="color: #68777d;">Belum ada buku untuk dibuatkan QR Code.</p>
        @endforelse
    </div>

    <div style="margin-top: 20px;">
        {{ $books->links() }}
    </div>
</section>
@endsection
