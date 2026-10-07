@extends('layouts.admin')

@section('title', 'Manajemen Peminjam')

@section('content')
<header class="admin-page-heading">
    <div>
        <h1 class="admin-page-title">Manajemen Peminjam / Anggota</h1>
        <p>Kelola data peminjam dan transaksi perpustakaan.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.users.create') }}" class="btn btn-success"><i class="bi bi-person-plus me-1" aria-hidden="true"></i> Tambah peminjam</a>
    </div>
</header>

<!-- FORM TRANSAKSI PEMINJAMAN BUKU -->
<section class="panel member-loan-form" id="loanFormSection" style="display: none; margin-bottom: 24px;">
    <div class="panel-heading" style="border-bottom: 2px solid #00a65a; padding-bottom: 12px; margin-bottom: 16px;">
        <h2 style="font-size: 1.1rem; color: #0f172a; margin: 0;">Form Transaksi Peminjaman Buku</h2>
    </div>

    <form id="onsiteLoanForm" onsubmit="processOnsiteLoan(event)">
        <!-- 1. DATA PEMINJAM (DIPECAH MENJADI NAMA, EMAIL, NO WA) -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 10px; color: #0f172a;">1. Data Peminjam <span style="color:#dd4b39;">*</span></label>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-bottom: 10px;">
                <div>
                    <label style="display: block; font-size: 0.78rem; font-weight: 600; margin-bottom: 4px; color: #475569;">Nama Peminjam <span style="color:#dd4b39;">*</span></label>
                    <input type="text" id="user_name_input" placeholder="Masukkan nama peminjam..." required style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.85rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.78rem; font-weight: 600; margin-bottom: 4px; color: #475569;">Email <span style="color:#dd4b39;">*</span></label>
                    <input type="email" id="user_email_input" placeholder="contoh@domain.com" required style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.85rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.78rem; font-weight: 600; margin-bottom: 4px; color: #475569;">No. WhatsApp / Telepon <span style="color:#dd4b39;">*</span></label>
                    <div style="display: flex; align-items: center;">
                        <span style="background: #f1f5f9; border: 1px solid #cbd5e1; border-right: 0; padding: 9px 12px; border-radius: 4px 0 0 4px; font-size: 0.85rem; font-weight: 600; color: #475569;">+62</span>
                        <input type="text" id="user_phone_input" placeholder="8123456789" required style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 0 4px 4px 0; font-size: 0.85rem;">
                    </div>
                </div>
            </div>

            <div id="userLookupActions" style="display: flex; gap: 10px; align-items: center;">
                <button type="button" onclick="lookupUserFromFields()" class="btn btn-primary" style="padding: 8px 14px; font-size: 0.8rem;">Tambah Data Peminjam</button>
                <div id="user_status" style="font-size: 0.78rem; font-weight: 600;"></div>
            </div>

            <div id="user_card" style="display: none; background: #e0f2fe; border: 1px solid #bae6fd; border-radius: 4px; padding: 12px; margin-top: 10px;">
                <div style="font-weight: 700; color: #0369a1; font-size: 0.95rem;" id="card_user_name">-</div>
                <div style="font-size: 0.8rem; color: #0c4a6e; margin-top: 2px;" id="card_user_detail">-</div>
                <div style="margin-top: 6px; font-size: 0.8rem; font-weight: 700; color: #0284c7;" id="card_user_quota">-</div>
            </div>
        </div>

        <!-- CONTAINER TAHAP 2: SCAN BUKU & JATUH TEMPO (SEBELUM PEMINJAM DIPILIH, BAGIAN INI TERSEMBUNYI) -->
        <div id="loanStepBookSection" style="display: none;">
            <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;">

            <!-- 2. DATA BUKU DIPINJAM (HANYA MENGGUNAKAN INPUT SCAN BARCODE) -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 6px; color: #0f172a;">2. Scan / Input Barcode Buku <span style="color:#dd4b39;">*</span></label>
                
                <div style="display: flex; align-items: center; gap: 10px;">
                    <input type="text" id="book_code_input" placeholder="Scan ISBN / ketik barcode buku..." oninput="onBookInputScan(this.value)" style="flex: 1; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.88rem; font-family: monospace; font-weight: 600;">
                    <button type="button" onclick="lookupBookByCode(document.getElementById('book_code_input').value.trim())" class="btn btn-secondary" style="font-size: 0.85rem; padding: 10px 16px;">Cek Buku</button>
                </div>
                <div id="book_status" style="margin-top: 4px; font-size: 0.78rem; font-weight: 600;"></div>

                <div id="book_card" style="display: none; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 12px; margin-top: 8px;">
                    <div style="font-weight: 700; color: #0f172a; font-size: 0.95rem;" id="card_book_title">-</div>
                    <div style="font-size: 0.8rem; color: #64748b; margin-top: 2px;" id="card_book_isbn">-</div>
                    <div style="margin-top: 4px; font-size: 0.8rem; font-weight: 700; color: #16a34a;" id="card_book_stock">-</div>
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;">

            <!-- 3. JATUH TEMPO (DEFAULT SEMINGGU SETELAH PEMINJAMAN) -->
            <div style="margin-bottom: 20px; max-width: 350px;">
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Jatuh Tempo (Default 1 Minggu) <span style="color:#dd4b39;">*</span></label>
                <input type="date" id="due_date_input" name="due_date" value="{{ date('Y-m-d', strtotime('+7 days')) }}" style="width: 100%; padding: 9px 12px; border: 1px solid #d2d6de; border-radius: 4px; font-size: 0.88rem;">
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="resetForm()" class="btn btn-secondary">Reset Form</button>
                <button type="submit" class="btn btn-success" style="padding: 10px 20px; font-size: 0.9rem;">Pinjami Buku & Cetak Nota</button>
            </div>
        </div>
    </form>
</section>

<!-- DAFTAR USER PEMINJAM -->
<section class="panel member-list">
    <div class="panel-heading" style="display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h2>Daftar anggota</h2>
            <span class="small text-secondary">{{ number_format($users->total()) }} anggota terdaftar</span>
        </div>
        <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex gap-2">
            <label for="memberSearch" class="visually-hidden">Cari nama, email, atau nomor WhatsApp</label>
            <input id="memberSearch" type="search" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari nama, email, WA..." style="min-width: 220px;">
            <button type="submit" class="btn btn-sm btn-outline-success">Cari</button>
            @if (request('search'))
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
    <table class="table table-hover admin-table member-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 22%;">Nama Peminjam</th>
                <th style="width: 22%;">Email</th>
                <th style="width: 16%;">No. WhatsApp</th>
                <th style="width: 9%; text-align: center;">Status</th>
                <th style="width: 13%; text-align: center;">Nota Terakhir</th>
                <th style="width: 13%; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody id="usersTableBody">
            @forelse ($users as $index => $user)
                <tr id="user_row_{{ $user->id }}">
                    <td style="text-align: center; color: #64748b;">{{ $users->firstItem() + $index }}</td>
                    <td><strong style="color: #0f172a; font-size: 0.9rem;">{{ $user->nama }}</strong></td>
                    <td>{{ $user->email }}</td>
                    <td><code>{{ $user->telepon }}</code></td>
                    <td style="text-align: center;">
                        @if ($user->status_aktif)
                            <span class="badge rounded-pill text-bg-success">Aktif</span>
                        @else
                            <span class="badge rounded-pill text-bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        @if ($user->latestLoan)
                            <button type="button" class="btn btn-sm btn-outline-success" data-view-receipt="{{ route('admin.users.receipt', $user) }}">Lihat nota</button>
                        @else
                            <span class="small text-secondary">Belum ada transaksi</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 6px; justify-content: center; align-items: center;">
                            <button type="button" data-select-user data-user-id="{{ $user->id }}" data-user-name="{{ $user->nama }}" data-user-email="{{ $user->email }}" data-user-phone="{{ $user->telepon }}" class="btn btn-sm btn-success" title="Buka form peminjaman untuk peminjam ini">Pinjami</button>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-success">Edit</a>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline-block;" onsubmit="return confirm('Yakin ingin menghapus peminjam ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr id="empty_users_row">
                    <td colspan="7" class="py-5 text-center text-secondary">Belum ada anggota terdaftar. Tambahkan anggota untuk memulai.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    @if ($users->hasPages())
        <div class="d-flex justify-content-center border-top mt-3 pt-3">{{ $users->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}</div>
    @endif
</section>

@push('styles')
<style>
    .member-table { min-width: 900px; }
    .receipt-modal { display: none; position: fixed; inset: 0; z-index: 9999; align-items: center; justify-content: center; padding: 16px; background: rgba(15, 23, 42, .6); }
    .receipt-dialog { width: 100%; max-width: 420px; max-height: calc(100vh - 32px); overflow-y: auto; padding: 24px; border-radius: 12px; background: #fff; box-shadow: 0 16px 40px rgba(15, 23, 42, .24); }
    .receipt-print-area { color: #111827; font-family: 'Courier New', monospace; font-size: .82rem; line-height: 1.5; }
    .receipt-header { margin-bottom: 12px; text-align: center; }
    .receipt-header h2 { font-family: inherit; font-size: 1.1rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; }
    .receipt-header p { margin: 2px 0 0; font-size: .75rem; text-transform: uppercase; }
    .receipt-section { display: grid; gap: 3px; padding: 9px 0; border-top: 1px dashed #94a3b8; }
    .receipt-section h3 { margin: 0 0 2px; font-family: inherit; font-size: .82rem; font-weight: 700; text-transform: uppercase; }
    .receipt-section > div { display: grid; grid-template-columns: 96px minmax(0, 1fr); gap: 8px; overflow-wrap: anywhere; }
    .receipt-due-date { grid-template-columns: 96px minmax(0, 1fr); border-bottom: 1px dashed #94a3b8; }
    .receipt-member-qr { display: flex; flex-direction: column; align-items: center; gap: 5px; margin: 14px 0; text-align: center; }
    .receipt-member-qr #rec_member_qr { display: grid; min-height: 128px; place-items: center; }
    .receipt-member-qr #rec_member_qr img, .receipt-member-qr #rec_member_qr canvas { display: block; margin: 0 auto; }
    .receipt-member-code { font-size: .72rem; font-weight: 700; }
    .receipt-qr-caption { font-size: .75rem; font-weight: 700; }
    .receipt-return-note { font-size: .68rem; }
    .receipt-footer { border-top: 1px dashed #000; padding-top: 8px; text-align: center; font-size: .7rem; }
    .receipt-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 18px; }
    .member-loan-form label { color: #34483d !important; }
    .member-loan-form input, .member-loan-form select { min-height: 40px; padding: .5rem .75rem !important; border: 1px solid #d8e2dc !important; border-radius: 7px !important; font-size: .9rem !important; }
    .member-loan-form input:focus { outline: 0; border-color: #198754 !important; box-shadow: 0 0 0 .2rem rgba(25, 135, 84, .13); }
    .member-list .panel-heading { gap: 12px; flex-wrap: wrap; }
    @media (max-width: 575.98px) {
        .member-list .panel-heading form { width: 100%; }
        .member-list .panel-heading input { min-width: 0 !important; flex: 1; }
    }

    @media print {
        body * { visibility: hidden !important; }
        #receiptModal, #receiptModal * { visibility: visible !important; }
        #receiptModal { position: static !important; display: block !important; padding: 0 !important; background: #fff !important; }
        #receiptModal .receipt-dialog { width: 100% !important; max-width: 100% !important; max-height: none !important; overflow: visible !important; padding: 0 !important; box-shadow: none !important; }
        #receiptPrintArea { width: 100%; }
        .receipt-actions { display: none !important; }
    }
</style>
@endpush

<!-- MODAL POPUP NOTA PEMINJAMAN -->
<div id="receiptModal" class="receipt-modal" role="dialog" aria-modal="true" aria-labelledby="receiptTitle">
    <div class="receipt-dialog">
        <div id="receiptPrintArea" class="receipt-print-area">
            <header class="receipt-header">
                <h2 id="receiptTitle">Perpustakaan Digital</h2>
                <p>Bukti Peminjaman Buku</p>
            </header>

            <section class="receipt-section" aria-label="Informasi transaksi">
                <div>Kode pinjam <strong id="rec_code">-</strong></div>
                <div>Tanggal <span id="rec_date">-</span></div>
            </section>

            <section class="receipt-section" aria-label="Data peminjam">
                <h3>Data Peminjam</h3>
                <div>Nama <span id="rec_user">-</span></div>
                <div>Telepon <span id="rec_phone">-</span></div>
                <div>ID anggota <span id="rec_member_code">-</span></div>
            </section>

            <section class="receipt-section" aria-label="Data buku">
                <h3>Data Buku</h3>
                <div>Judul <span id="rec_title">-</span></div>
                <div>ISBN <span id="rec_isbn">-</span></div>
            </section>

            <section class="receipt-section receipt-due-date" aria-label="Tanggal jatuh tempo">
                Jatuh tempo <strong id="rec_due">-</strong>
            </section>

            <section class="receipt-member-qr" aria-label="QR code identitas peminjam">
                <div id="rec_member_qr"></div>
                <div class="receipt-member-code" id="rec_qr_fallback"></div>
                <div class="receipt-qr-caption">QR code identitas peminjam</div>
                <div class="receipt-return-note">Pengembalian diproses menggunakan kode pinjam.</div>
            </section>

            <footer class="receipt-footer">Data transaksi tersimpan di sistem perpustakaan.</footer>
        </div>

        <div class="receipt-actions">
            <button type="button" onclick="closeReceiptModal()" class="btn btn-secondary">Tutup</button>
            <button type="button" onclick="printReceiptAndClose()" class="btn btn-primary">Cetak nota</button>
        </div>
    </div>
</div>

<!-- MODAL PERINGATAN PEMINJAM SUDAH TERDAFTAR -->
<div id="userExistsModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #fff; width: 100%; max-width: 440px; border-radius: 8px; padding: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
        <div style="display: flex; align-items: center; gap: 12px; border-bottom: 2px solid #eab308; padding-bottom: 12px; margin-bottom: 16px;">
            <i class="bi bi-exclamation-triangle text-warning fs-4" aria-hidden="true"></i>
            <div>
                <h3 style="margin: 0; font-size: 1.1rem; color: #0f172a;">Data Peminjam Sudah Ada!</h3>
                <p style="margin: 2px 0 0 0; font-size: 0.78rem; color: #64748b;">Peminjam dengan nama / kontak ini sudah terdaftar di sistem.</p>
            </div>
        </div>

        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 14px; margin-bottom: 20px;">
            <div style="font-weight: 700; color: #0f172a; font-size: 0.95rem;" id="modal_user_name">-</div>
            <div style="font-size: 0.82rem; color: #475569; margin-top: 4px;" id="modal_user_email">Email: -</div>
            <div style="font-size: 0.82rem; color: #475569; margin-top: 2px;" id="modal_user_phone">WA: -</div>
            <div style="margin-top: 8px; padding-top: 8px; border-top: 1px dashed #cbd5e1; font-size: 0.8rem; font-weight: 600; color: #0284c7;" id="modal_user_quota">-</div>
        </div>

        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button type="button" onclick="closeUserExistsModal()" class="btn btn-secondary" style="padding: 8px 14px; font-size: 0.85rem;">Ubah input</button>
            <button type="button" onclick="useExistingUserFromModal()" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.85rem; font-weight: 600;">Gunakan data ini</button>
        </div>
    </div>
</div>

@push('vendor-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
@endpush

@push('scripts')
<script>
document.addEventListener('click', (event) => {
    const receiptButton = event.target.closest('[data-view-receipt]');
    if (receiptButton) {
        viewLatestReceipt(receiptButton);
        return;
    }

    const button = event.target.closest('[data-select-user]');
    if (!button) return;
    selectUserForLoan(button.dataset.userName, button.dataset.userEmail, button.dataset.userPhone, Number(button.dataset.userId));
});

const receiptUrlTemplate = @json(route('admin.users.receipt', ['user' => 'USER_ID']));

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

const userNameInput = document.getElementById('user_name_input');
const userEmailInput = document.getElementById('user_email_input');
const userPhoneInput = document.getElementById('user_phone_input');
const bookCodeInput = document.getElementById('book_code_input');
const userStatus = document.getElementById('user_status');
const bookStatus = document.getElementById('book_status');

let selectedUser = null;
let selectedBook = null;
let scanTimeout = null;

function showBookStepSection() {
    const bookStep = document.getElementById('loanStepBookSection');
    if (bookStep) {
        bookStep.style.display = 'block';
        setTimeout(() => {
            if (bookCodeInput) bookCodeInput.focus();
        }, 100);
    }
}

function hideBookStepSection() {
    const bookStep = document.getElementById('loanStepBookSection');
    if (bookStep) {
        bookStep.style.display = 'none';
    }
}

function selectUserForLoan(nama, email, telepon, id) {
    const formSection = document.getElementById('loanFormSection');
    formSection.style.display = 'block';
    
    userNameInput.value = nama;
    userEmailInput.value = email;
    userPhoneInput.value = (telepon || '').replace(/^(\+62|62|0)/, '');
    
    selectedUser = { id, nama, email, telepon };
    userStatus.innerText = '';
    document.getElementById('userLookupActions').style.display = 'none';
    
    document.getElementById('card_user_name').innerText = nama;
    document.getElementById('card_user_detail').innerText = `Telp: ${telepon} | Email: ${email}`;
    document.getElementById('card_user_quota').innerText = `Peminjam terverifikasi`;
    document.getElementById('user_card').style.display = 'block';
    
    showBookStepSection();
    formSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function onBookInputScan(val) {
    clearTimeout(scanTimeout);
    val = val.trim();
    if (val.length >= 4) {
        scanTimeout = setTimeout(() => {
            lookupBookByCode(val);
        }, 300);
    }
}

bookCodeInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        lookupBookByCode(bookCodeInput.value.trim());
    }
});

let pendingUserModal = null;

function showUserExistsModal(user) {
    pendingUserModal = user;
    document.getElementById('modal_user_name').innerText = user.nama;
    document.getElementById('modal_user_email').innerText = `Email: ${user.email}`;
    document.getElementById('modal_user_phone').innerText = `No. WA: ${user.telepon}`;
    document.getElementById('modal_user_quota').innerText = `Aktif Dipinjam: ${user.active_loans_count} | Sisa Kuota: ${user.remaining_quota} buku`;
    document.getElementById('userExistsModal').style.display = 'flex';
}

function closeUserExistsModal() {
    document.getElementById('userExistsModal').style.display = 'none';
}

function useExistingUserFromModal() {
    if (pendingUserModal) {
        selectedUser = pendingUserModal;
        userNameInput.value = pendingUserModal.nama;
        userEmailInput.value = pendingUserModal.email;
        userPhoneInput.value = (pendingUserModal.telepon || '').replace(/^(\+62|62|0)/, '');

        userStatus.innerText = '';
        document.getElementById('userLookupActions').style.display = 'none';

        document.getElementById('card_user_name').innerText = pendingUserModal.nama;
        document.getElementById('card_user_detail').innerText = `Telp: ${pendingUserModal.telepon} | Email: ${pendingUserModal.email}`;
        document.getElementById('card_user_quota').innerText = `Aktif Dipinjam: ${pendingUserModal.active_loans_count} | Sisa Kuota: ${pendingUserModal.remaining_quota} buku`;
        document.getElementById('user_card').style.display = 'block';
    }
    closeUserExistsModal();
}

async function lookupUserFromFields() {
    const name = userNameInput.value.trim();
    const email = userEmailInput.value.trim();
    const rawPhone = userPhoneInput.value.trim().replace(/^(\+62|62|0)/, '');
    const phone = rawPhone ? '62' + rawPhone : '';
    const code = phone || email || name;

    if (!code && (!name || !email || !phone)) {
        userStatus.style.color = '#dc2626';
        userStatus.innerText = 'Harap lengkapi nama, email, dan nomor WhatsApp peminjam.';
        return;
    }

    userStatus.style.color = '#0284c7';
    userStatus.innerText = 'Memeriksa dan menyimpan data peminjam...';

    try {
        const res = await fetch('{{ route("admin.scan.lookup_user") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ code, name, email, phone })
        });
        const data = await res.json();

        if (res.ok && data.success) {
            playBeep(true);
            if (data.created) {
                // New user added to database and table
                userStatus.style.color = '#16a34a';
                userStatus.innerText = data.message || 'Peminjam baru berhasil ditambahkan.';

                // Insert into table dynamically
                const tbody = document.getElementById('usersTableBody');
                const emptyRow = document.getElementById('empty_users_row');
                if (emptyRow) emptyRow.remove();

                if (tbody && !document.getElementById('user_row_' + data.user.id)) {
                    const rowCount = tbody.children.length + 1;
                    const newTr = document.createElement('tr');
                    newTr.id = 'user_row_' + data.user.id;
                    newTr.innerHTML = `
                        <td style="text-align: center; color: #64748b;">${rowCount}</td>
                        <td><strong style="color: #0f172a; font-size: 0.9rem;">${data.user.nama}</strong></td>
                        <td>${data.user.email}</td>
                        <td><code>${data.user.telepon}</code></td>
                        <td style="text-align: center;">
                            <span style="display: inline-block; padding: 3px 10px; background: #dcfce7; color: #15803d; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">Aktif</span>
                        </td>
                        <td style="text-align: center;">
                            <span style="display: block; font-size: 0.75rem; font-weight: 700; color: #0284c7; margin-bottom: 3px;">0 Buku</span>
                            <a href="/admin-panel/users/${data.user.id}/loans" class="btn btn-primary" style="padding: 3px 8px; font-size: 0.72rem; font-weight: 600;" title="Lihat buku dipinjam">Lihat buku</a>
                        </td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 6px; justify-content: center;">
                                <button type="button" data-select-user data-user-id="${data.user.id}" data-user-name="${data.user.nama}" data-user-email="${data.user.email}" data-user-phone="${data.user.telepon}" class="btn btn-success" style="padding: 4px 8px; font-size: 0.75rem;" title="Buka form peminjaman untuk peminjam ini">Pinjami</button>
                            </div>
                        </td>
                    `;
                    tbody.prepend(newTr);
                }

                // Clear input fields and keep scan section hidden until a borrower is selected.
                userNameInput.value = '';
                userEmailInput.value = '';
                userPhoneInput.value = '';
                document.getElementById('user_card').style.display = 'none';
                hideBookStepSection();
            } else {
                showUserExistsModal(data.user);
            }
        } else {
            playBeep(false);
            userStatus.style.color = '#dc2626';
            userStatus.innerText = data.message || 'Gagal memproses data.';
        }
    } catch (err) {
        playBeep(false);
        userStatus.style.color = '#dc2626';
        userStatus.innerText = 'Terjadi kesalahan koneksi.';
    }
}

async function lookupBookByCode(code) {
    if (!code) return;

    bookStatus.style.color = '#16a34a';
    bookStatus.innerText = 'Memeriksa data buku...';

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
            bookStatus.style.color = '#16a34a';
            bookStatus.innerText = 'Buku terdeteksi: ' + data.book.judul;

            document.getElementById('card_book_title').innerText = data.book.judul;
            document.getElementById('card_book_isbn').innerText = `ISBN: ${data.book.isbn}`;
            document.getElementById('card_book_stock').innerText = `Tersedia: ${data.book.tersedia} dari ${data.book.stok} unit`;
            document.getElementById('book_card').style.display = 'block';
        } else {
            playBeep(false);
            bookStatus.style.color = '#dc2626';
            bookStatus.innerText = data.message || 'Buku tidak ditemukan.';
            document.getElementById('book_card').style.display = 'none';
            selectedBook = null;
        }
    } catch (err) {
        playBeep(false);
        bookStatus.style.color = '#dc2626';
        bookStatus.innerText = 'Terjadi kesalahan sistem.';
    }
}

async function processOnsiteLoan(e) {
    e.preventDefault();
    
    const userName = userNameInput.value.trim();
    const userEmail = userEmailInput.value.trim();
    const rawPhone = userPhoneInput.value.trim().replace(/^(\+62|62|0)/, '');
    const userPhone = rawPhone ? '62' + rawPhone : '';
    const userId = selectedUser ? selectedUser.id : null;
    const bookId = selectedBook ? selectedBook.id : null;

    if (!userId && (!userName || !userEmail || !userPhone)) {
        alert('Harap lengkapi Nama, Email, dan No. WA peminjam.');
        userNameInput.focus();
        return;
    }

    if (!bookId) {
        alert('Harap scan Barcode / ISBN Buku terlebih dahulu.');
        bookCodeInput.focus();
        return;
    }

    const dueDate = document.getElementById('due_date_input').value;

    try {
        const res = await fetch('{{ route("admin.scan.store_onsite") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                user_id: userId,
                user_name: userName,
                user_email: userEmail,
                user_phone: userPhone,
                book_id: bookId,
                due_date: dueDate
            })
        });

        const data = await res.json();

        if (res.ok && data.success) {
            playBeep(true);

            // Populate table dynamically if new user created
            if (data.user) {
                const tbody = document.getElementById('usersTableBody');
                const emptyRow = document.getElementById('empty_users_row');
                if (emptyRow) {
                    emptyRow.remove();
                }

                if (tbody && !document.getElementById('user_row_' + data.user.id)) {
                    const rowCount = tbody.children.length + 1;
                    const newTr = document.createElement('tr');
                    newTr.id = 'user_row_' + data.user.id;
                    newTr.innerHTML = `
                        <td style="text-align: center; color: #64748b;">${rowCount}</td>
                        <td><strong style="color: #0f172a; font-size: 0.9rem;">${data.user.nama}</strong></td>
                        <td>${data.user.email}</td>
                        <td><code>${data.user.telepon}</code></td>
                        <td style="text-align: center;">
                            <span style="display: inline-block; padding: 3px 10px; background: #dcfce7; color: #15803d; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">Aktif</span>
                        </td>
                        <td style="text-align: center;">
                            <button type="button" class="btn btn-sm btn-outline-success" data-view-receipt="${receiptUrlTemplate.replace('USER_ID', data.user.id)}">Lihat nota</button>
                        </td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 6px; justify-content: center;">
                                <button type="button" data-select-user data-user-id="${data.user.id}" data-user-name="${data.user.nama}" data-user-email="${data.user.email}" data-user-phone="${data.user.telepon}" class="btn btn-success" style="padding: 4px 8px; font-size: 0.75rem;" title="Buka form peminjaman untuk peminjam ini">Pinjami</button>
                            </div>
                        </td>
                    `;
                    tbody.prepend(newTr);
                }
            }

            needReloadAfterReceipt = true;
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

let needReloadAfterReceipt = false;

function showReceiptModal(loan) {
    const memberCode = loan.member_code;
    const qrContainer = document.getElementById('rec_member_qr');
    const qrFallback = document.getElementById('rec_qr_fallback');

    document.getElementById('rec_code').innerText = loan.kode_pinjam;
    document.getElementById('rec_date').innerText = loan.tanggal_pinjam;
    document.getElementById('rec_user').innerText = loan.user_nama;
    document.getElementById('rec_phone').innerText = loan.user_telepon;
    document.getElementById('rec_member_code').innerText = memberCode;
    document.getElementById('rec_title').innerText = loan.book_judul;
    document.getElementById('rec_isbn').innerText = loan.book_isbn;
    document.getElementById('rec_due').innerText = loan.jatuh_tempo;
    qrContainer.replaceChildren();
    qrContainer.setAttribute('aria-label', `QR code identitas peminjam ${memberCode}`);
    qrFallback.innerText = memberCode;

    document.getElementById('receiptModal').style.display = 'flex';

    if (!memberCode) {
        qrFallback.innerText = 'Kode identitas peminjam tidak tersedia.';
        return;
    }

    if (typeof QRCode !== 'function') {
        qrFallback.innerText = `QR code tidak dapat dibuat. ID peminjam: ${memberCode}`;
        return;
    }

    new QRCode(qrContainer, {
        text: memberCode,
        width: 128,
        height: 128,
        colorDark: '#111827',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.M
    });
}

async function viewLatestReceipt(button) {
    button.disabled = true;

    try {
        const response = await fetch(button.dataset.viewReceipt, {
            headers: { 'Accept': 'application/json' }
        });
        const loan = await response.json();

        if (!response.ok) {
            throw new Error(loan.message || 'Nota peminjaman tidak dapat dimuat.');
        }

        showReceiptModal(loan);
    } catch (error) {
        alert(error.message || 'Terjadi kesalahan saat memuat nota peminjaman.');
    } finally {
        button.disabled = false;
    }
}

function printReceiptAndClose() {
    window.print();
}

window.addEventListener('afterprint', function() {
    closeReceiptModal();
});

function closeReceiptModal() {
    document.getElementById('receiptModal').style.display = 'none';
    if (needReloadAfterReceipt) {
        needReloadAfterReceipt = false;
        window.location.reload();
    }
}

function resetForm() {
    selectedUser = null;
    selectedBook = null;
    userNameInput.value = '';
    userEmailInput.value = '';
    userPhoneInput.value = '';
    bookCodeInput.value = '';
    userStatus.innerText = '';
    document.getElementById('userLookupActions').style.display = 'flex';
    bookStatus.innerText = '';
    document.getElementById('user_card').style.display = 'none';
    document.getElementById('book_card').style.display = 'none';
    hideBookStepSection();
    userNameInput.focus();
}
</script>
@endpush
@endsection
