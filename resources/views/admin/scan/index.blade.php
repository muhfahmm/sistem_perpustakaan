@extends('layouts.admin')

@section('title', 'Peminjaman On-Site (Scan / Select)')

@section('content')
<header style="margin-bottom: 20px;">
    <h1>Peminjaman Buku On-Site</h1>
</header>

<section class="panel" style="max-width: 750px;">
    <div class="panel-heading" style="border-bottom: 2px solid #00a65a;">
        <h2>Form Transaksi Peminjaman Buku (On-Site)</h2>
    </div>

    <form id="onsiteLoanForm" onsubmit="processOnsiteLoan(event)">
        <!-- DATA SISWA / PEMINJAM -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: #0f172a;">1. Scan / Input Data Siswa Peminjam <span style="color:#dd4b39;">*</span></label>
            <div style="display: flex; align-items: center; gap: 10px;">
                <input type="text" id="user_code_input" autofocus placeholder="Scan kartu siswa / ketik nomor telepon / nama..." required style="flex: 1; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.88rem; font-weight: 600;">
                <button type="button" onclick="lookupUserByCode(document.getElementById('user_code_input').value.trim())" class="btn btn-primary" style="padding: 10px 16px; font-size: 0.85rem;">Cek Data Siswa</button>
            </div>
            <div id="user_status" style="margin-top: 6px; font-size: 0.78rem; font-weight: 600;"></div>

            <div id="user_card" style="display: none; background: #e0f2fe; border: 1px solid #bae6fd; border-radius: 4px; padding: 12px; margin-top: 10px;">
                <div style="font-weight: 700; color: #0369a1; font-size: 0.95rem;" id="card_user_name">-</div>
                <div style="font-size: 0.8rem; color: #0c4a6e; margin-top: 2px;" id="card_user_detail">-</div>
                <div style="margin-top: 6px; font-size: 0.8rem; font-weight: 700; color: #0284c7;" id="card_user_quota">-</div>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;">

        <!-- DATA BUKU DIPINJAM -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: #0f172a;">2. Pilih Buku yang Dipinjam <span style="color:#dd4b39;">*</span></label>
            <select id="book_select" name="book_id" required onchange="onBookSelectChange()" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.88rem;">
                <option value="">-- Pilih Buku --</option>
                @foreach($books as $b)
                    <option value="{{ $b->id }}" data-isbn="{{ $b->isbn ?? '-' }}" data-stok="{{ $b->stok }}" data-tersedia="{{ $b->tersedia }}">{{ $b->judul }} (ISBN: {{ $b->isbn ?? '-' }} | Tersedia: {{ $b->tersedia }})</option>
                @endforeach
            </select>

            <div style="display: flex; align-items: center; gap: 10px; margin-top: 8px;">
                <input type="text" id="book_code_input" placeholder="Scan ISBN / ketik barcode buku..." style="flex: 1; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.82rem; font-family: monospace;">
                <button type="button" onclick="lookupBookByCode(document.getElementById('book_code_input').value.trim())" class="btn btn-secondary" style="font-size: 0.8rem; padding: 8px 12px;">Cek Buku</button>
            </div>
            <div id="book_status" style="margin-top: 4px; font-size: 0.78rem; font-weight: 600;"></div>

            <div id="book_card" style="display: none; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 10px 12px; margin-top: 8px;">
                <div style="font-weight: 700; color: #0f172a; font-size: 0.9rem;" id="card_book_title">-</div>
                <div style="font-size: 0.78rem; color: #64748b;" id="card_book_isbn">-</div>
                <div style="margin-top: 4px; font-size: 0.78rem; font-weight: 600; color: #16a34a;" id="card_book_stock">-</div>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;">

        <!-- TANGGAL & CATATAN -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Jatuh Tempo <span style="color:#dd4b39;">*</span></label>
                <input type="date" id="due_date_input" name="due_date" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 4px; font-size: 0.88rem;">
            </div>
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Catatan (Opsional)</label>
                <input type="text" name="catatan" placeholder="Contoh: Kondisi buku baik" style="width: 100%; padding: 8px 12px; border: 1px solid #d2d6de; border-radius: 4px; font-size: 0.88rem;">
            </div>
        </div>

        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button type="button" onclick="resetForm()" class="btn btn-secondary">Reset Form</button>
            <button type="submit" class="btn btn-success" style="padding: 10px 20px; font-size: 0.9rem;">✓ Simpan Peminjaman & Cetak Nota</button>
        </div>
    </form>
</section>

<!-- MODAL POPUP NOTA PEMINJAMAN -->
<div id="receiptModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #fff; width: 100%; max-width: 380px; border-radius: 8px; padding: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
        <div id="receiptPrintArea" style="font-family: monospace; font-size: 0.82rem; color: #000; line-height: 1.4;">
            <div style="text-align: center; font-weight: bold; font-size: 1.05rem;">📚 PERPUSTAKAAN DIGITAL</div>
            <div style="text-align: center; font-size: 0.75rem; margin-bottom: 6px;">NOTA PEMINJAMAN BUKU</div>
            <div style="border-bottom: 1px dashed #000; margin: 8px 0;"></div>

            <div>Kode Pinjam : <strong id="rec_code">-</strong></div>
            <div>Tgl Pinjam  : <span id="rec_date">-</span></div>
            <div style="border-bottom: 1px dashed #000; margin: 8px 0;"></div>

            <div style="font-weight: bold;">PEMINJAM (tb_user_peminjam)</div>
            <div>Nama  : <span id="rec_user">-</span></div>
            <div>Telp  : <span id="rec_phone">-</span></div>
            <div style="border-bottom: 1px dashed #000; margin: 8px 0;"></div>

            <div style="font-weight: bold;">BUKU (tb_data_buku)</div>
            <div>Judul : <span id="rec_title">-</span></div>
            <div>ISBN  : <span id="rec_isbn">-</span></div>
            <div style="border-bottom: 1px dashed #000; margin: 8px 0;"></div>

            <div>Jatuh Tempo : <strong id="rec_due">-</strong></div>
            <div style="border-bottom: 1px dashed #000; margin: 8px 0;"></div>

            <div style="text-align: center; margin: 12px 0;">
                <div style="display: inline-block; padding: 6px; border: 2px solid #000; font-weight: bold; font-size: 0.9rem;" id="rec_qr_box">
                    [ QR: <span id="rec_qr_code">-</span> ]
                </div>
                <div style="font-size: 0.7rem; margin-top: 4px;">Scan kode ini saat pengembalian</div>
            </div>

            <div style="border-bottom: 1px dashed #000; margin: 8px 0;"></div>
            <div style="text-align: center; font-size: 0.72rem;">Data telah tersimpan di sistem perpustakaan. Terima kasih! 🙏</div>
        </div>

        <div style="display: flex; gap: 8px; margin-top: 18px; justify-content: flex-end;">
            <button type="button" onclick="closeReceiptModal()" class="btn btn-secondary">Tutup</button>
            <button type="button" onclick="window.print()" class="btn btn-primary">🖨️ Cetak Nota</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function playBeep(success = true) {
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.type = 'sine';
        osc.frequency.value = success ? 880 : 330;
        gain.gain.value = 0.2;
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.start();
        osc.stop(ctx.currentTime + (success ? 0.15 : 0.3));
    } catch (e) {}
}

const bookSelect = document.getElementById('book_select');
const userCodeInput = document.getElementById('user_code_input');
const bookCodeInput = document.getElementById('book_code_input');
const userStatus = document.getElementById('user_status');
const bookStatus = document.getElementById('book_status');

let selectedUser = null;
let selectedBook = null;

function onBookSelectChange() {
    const val = bookSelect.value;
    if (!val) {
        selectedBook = null;
        document.getElementById('book_card').style.display = 'none';
        return;
    }
    const opt = bookSelect.options[bookSelect.selectedIndex];
    selectedBook = {
        id: val,
        judul: opt.text.split(' (ISBN:')[0],
        isbn: opt.getAttribute('data-isbn'),
        stok: opt.getAttribute('data-stok'),
        tersedia: opt.getAttribute('data-tersedia')
    };

    playBeep(true);
    bookStatus.style.color = '#16a34a';
    bookStatus.innerText = '✓ Buku dipilih dari daftar.';
    document.getElementById('card_book_title').innerText = selectedBook.judul;
    document.getElementById('card_book_isbn').innerText = `ISBN: ${selectedBook.isbn}`;
    document.getElementById('card_book_stock').innerText = `Tersedia: ${selectedBook.tersedia} dari ${selectedBook.stok} unit`;
    document.getElementById('book_card').style.display = 'block';
}

userCodeInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        lookupUserByCode(userCodeInput.value.trim());
    }
});

bookCodeInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        lookupBookByCode(bookCodeInput.value.trim());
    }
});

async function lookupUserByCode(code) {
    if (!code) {
        userStatus.style.color = '#dc2626';
        userStatus.innerText = '✕ Harap masukkan kode / telp / nama siswa.';
        return;
    }

    userStatus.style.color = '#0284c7';
    userStatus.innerText = '⏳ Memeriksa data siswa...';

    try {
        const res = await fetch('{{ route("admin.scan.lookup_user") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ code })
        });
        const data = await res.json();

        if (res.ok && data.success) {
            playBeep(true);
            selectedUser = data.user;
            userStatus.style.color = '#16a34a';
            userStatus.innerText = '✓ ' + data.message;

            document.getElementById('card_user_name').innerText = data.user.nama;
            document.getElementById('card_user_detail').innerText = `Telp: ${data.user.telepon} | Email: ${data.user.email}`;
            document.getElementById('card_user_quota').innerText = `Aktif Dipinjam: ${data.user.active_loans_count} | Sisa Kuota: ${data.user.remaining_quota} buku`;
            document.getElementById('user_card').style.display = 'block';
        } else {
            playBeep(false);
            userStatus.style.color = '#dc2626';
            userStatus.innerText = '✕ ' + (data.message || 'Siswa tidak ditemukan.');
            document.getElementById('user_card').style.display = 'none';
            selectedUser = null;
        }
    } catch (err) {
        playBeep(false);
        userStatus.style.color = '#dc2626';
        userStatus.innerText = '✕ Terjadi kesalahan sistem.';
    }
}

async function lookupBookByCode(code) {
    if (!code) return;

    bookStatus.style.color = '#16a34a';
    bookStatus.innerText = '⏳ Memeriksa data buku...';

    try {
        const res = await fetch('{{ route("admin.scan.lookup_book") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ code })
        });
        const data = await res.json();

        if (res.ok && data.success) {
            playBeep(true);
            selectedBook = data.book;
            bookSelect.value = data.book.id;
            bookStatus.style.color = '#16a34a';
            bookStatus.innerText = '✓ ' + data.message;

            document.getElementById('card_book_title').innerText = data.book.judul;
            document.getElementById('card_book_isbn').innerText = `ISBN: ${data.book.isbn}`;
            document.getElementById('card_book_stock').innerText = `Tersedia: ${data.book.tersedia} dari ${data.book.stok} unit`;
            document.getElementById('book_card').style.display = 'block';
        } else {
            playBeep(false);
            bookStatus.style.color = '#dc2626';
            bookStatus.innerText = '✕ ' + (data.message || 'Buku tidak ditemukan.');
            document.getElementById('book_card').style.display = 'none';
            selectedBook = null;
        }
    } catch (err) {
        playBeep(false);
        bookStatus.style.color = '#dc2626';
        bookStatus.innerText = '✕ Terjadi kesalahan sistem.';
    }
}

async function processOnsiteLoan(e) {
    e.preventDefault();
    const userId = selectedUser ? selectedUser.id : null;
    const bookId = bookSelect.value || (selectedBook ? selectedBook.id : null);

    if (!userId) {
        alert('Harap masukkan / cek data Siswa terlebih dahulu.');
        userCodeInput.focus();
        return;
    }

    if (!bookId) {
        alert('Harap pilih atau scan Barcode Buku terlebih dahulu.');
        return;
    }

    const dueDate = document.getElementById('due_date_input').value;
    const catatan = e.target.querySelector('input[name="catatan"]').value;

    try {
        const res = await fetch('{{ route("admin.scan.store_onsite") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                user_id: userId,
                book_id: bookId,
                due_date: dueDate,
                catatan: catatan
            })
        });

        const data = await res.json();

        if (res.ok && data.success) {
            playBeep(true);
            showReceiptModal(data.loan);
            resetForm();
        } else {
            playBeep(false);
            alert('Gagal: ' + (data.message || 'Terjadi kesalahan.'));
        }
    } catch (err) {
        playBeep(false);
        alert('Terjadi kesalahan koneksi.');
    }
}

function showReceiptModal(loan) {
    document.getElementById('rec_code').innerText = loan.kode_pinjam;
    document.getElementById('rec_date').innerText = loan.tanggal_pinjam;
    document.getElementById('rec_user').innerText = loan.user_nama;
    document.getElementById('rec_phone').innerText = loan.user_telepon;
    document.getElementById('rec_title').innerText = loan.book_judul;
    document.getElementById('rec_isbn').innerText = loan.book_isbn;
    document.getElementById('rec_due').innerText = loan.jatuh_tempo;
    document.getElementById('rec_qr_code').innerText = loan.kode_pinjam;

    document.getElementById('receiptModal').style.display = 'flex';
}

function closeReceiptModal() {
    document.getElementById('receiptModal').style.display = 'none';
}

function resetForm() {
    selectedUser = null;
    selectedBook = null;
    bookSelect.value = '';
    userCodeInput.value = '';
    bookCodeInput.value = '';
    userStatus.innerText = '';
    bookStatus.innerText = '';
    document.getElementById('user_card').style.display = 'none';
    document.getElementById('book_card').style.display = 'none';
    userCodeInput.focus();
}
</script>
@endpush
@endsection
