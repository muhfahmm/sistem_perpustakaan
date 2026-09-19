@extends('layouts.admin')

@section('title', 'Manajemen Peminjam')

@section('content')
<header style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
    <div>
        <h1 style="margin: 0; font-size: 1.5rem; color: #0f172a;">Manajemen Peminjam / Anggota</h1>
        <p style="margin: 4px 0 0 0; color: #64748b; font-size: 0.85rem;">Kelola data peminjam dan lakukan transaksi peminjaman buku.</p>
    </div>
    <button type="button" onclick="toggleLoanForm()" class="btn btn-primary" style="padding: 10px 16px; font-weight: 600;">+ Tambah Peminjam Baru</button>
</header>

<!-- FORM TRANSAKSI PEMINJAMAN BUKU -->
<section class="panel" id="loanFormSection" style="display: none; margin-bottom: 24px;">
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

            <div style="display: flex; gap: 10px; align-items: center;">
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
                <button type="submit" class="btn btn-success" style="padding: 10px 20px; font-size: 0.9rem;">Simpan Peminjaman & Cetak Nota</button>
            </div>
        </div>
    </form>
</section>

<!-- DAFTAR USER PEMINJAM -->
<section class="panel">
    <div class="panel-heading" style="display: flex; align-items: center; justify-content: space-between;">
        <h2>Daftar User Peminjam</h2>
        <form method="GET" action="{{ route('admin.users.index') }}" style="display: flex; gap: 8px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, WA..." style="padding: 6px 10px; font-size: 0.8rem; border: 1px solid #d2d6de; border-radius: 3px;">
            <button type="submit" class="btn btn-secondary">Cari</button>
        </form>
    </div>

    <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 22%;">Nama Peminjam</th>
                <th style="width: 22%;">Email</th>
                <th style="width: 16%;">No. WhatsApp</th>
                <th style="width: 9%; text-align: center;">Status</th>
                <th style="width: 13%; text-align: center;">Buku Dipinjam</th>
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
                            <span style="display: inline-block; padding: 3px 10px; background: #dcfce7; color: #15803d; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">Aktif</span>
                        @else
                            <span style="display: inline-block; padding: 3px 10px; background: #fee2e2; color: #991b1b; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">Nonaktif</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <a href="{{ route('admin.users.loans', $user) }}" class="btn btn-primary" style="padding: 4px 10px; font-size: 0.75rem; font-weight: 600;">Lihat Buku ({{ $user->active_loans_count ?? 0 }})</a>
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 6px; justify-content: center; align-items: center;">
                            <button type="button" onclick="selectUserForLoan('{{ $user->nama }}', '{{ $user->email }}', '{{ $user->telepon }}', {{ $user->id }})" class="btn btn-success" style="padding: 4px 10px; font-size: 0.75rem; font-weight: 600;" title="Pilih anggota ini untuk peminjaman">Pinjamkan</button>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-secondary" style="padding: 4px 10px; font-size: 0.75rem;">Edit</a>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline-block;" onsubmit="return confirm('Yakin ingin menghapus peminjam ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary" style="padding: 4px 10px; font-size: 0.75rem; color: #dc2626; border-color: #fecaca; background: #fff5f5;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr id="empty_users_row">
                    <td colspan="7" style="text-align: center; color: #64748b; padding: 28px;">Belum ada peminjam terdaftar. Klik "+ Tambah Peminjam Baru" untuk mendaftar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 16px;">
        {{ $users->links() }}
    </div>
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
            <button type="button" onclick="printReceiptAndClose()" class="btn btn-primary">🖨️ Cetak Nota</button>
        </div>
    </div>
</div>

<!-- MODAL PERINGATAN PEMINJAM SUDAH TERDAFTAR -->
<div id="userExistsModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #fff; width: 100%; max-width: 440px; border-radius: 8px; padding: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
        <div style="display: flex; align-items: center; gap: 12px; border-bottom: 2px solid #eab308; padding-bottom: 12px; margin-bottom: 16px;">
            <span style="font-size: 1.6rem;">⚠️</span>
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
            <button type="button" onclick="closeUserExistsModal()" class="btn btn-secondary" style="padding: 8px 14px; font-size: 0.85rem;">✕ Ubah Input</button>
            <button type="button" onclick="useExistingUserFromModal()" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.85rem; font-weight: 600;">✓ Gunakan Data Ini</button>
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

const userNameInput = document.getElementById('user_name_input');
const userEmailInput = document.getElementById('user_email_input');
const userPhoneInput = document.getElementById('user_phone_input');
const bookCodeInput = document.getElementById('book_code_input');
const userStatus = document.getElementById('user_status');
const bookStatus = document.getElementById('book_status');

let selectedUser = null;
let selectedBook = null;
let scanTimeout = null;

function toggleLoanForm() {
    const formSection = document.getElementById('loanFormSection');
    if (formSection.style.display === 'none' || !formSection.style.display) {
        formSection.style.display = 'block';
        formSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        userNameInput.focus();
    } else {
        formSection.style.display = 'none';
    }
}

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
    userStatus.style.color = '#16a34a';
    userStatus.innerText = '✓ Peminjam dipilih: ' + nama;
    
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

        userStatus.style.color = '#16a34a';
        userStatus.innerText = '✓ Peminjam dipilih: ' + pendingUserModal.nama;

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
        userStatus.innerText = '✕ Harap lengkapi Nama, Email, dan No. WA peminjam.';
        return;
    }

    userStatus.style.color = '#0284c7';
    userStatus.innerText = '⏳ Memeriksa / menyimpan data peminjam...';

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
                userStatus.innerText = '✓ ' + (data.message || 'Peminjam baru berhasil ditambahkan.');

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
                            <a href="/admin-panel/users/${data.user.id}/loans" class="btn btn-primary" style="padding: 3px 8px; font-size: 0.72rem; font-weight: 600;" title="Lihat buku dipinjam">📚 Lihat Buku</a>
                        </td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 6px; justify-content: center;">
                                <button type="button" onclick="selectUserForLoan('${data.user.nama}', '${data.user.email}', '${data.user.telepon}', ${data.user.id})" class="btn btn-success" style="padding: 4px 8px; font-size: 0.75rem;" title="Pilih anggota ini untuk peminjaman">⚡ Pinjamkan</button>
                            </div>
                        </td>
                    `;
                    tbody.prepend(newTr);
                }

                // Clear input fields and keep scan section hidden until admin clicks Pinjamkan
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
            userStatus.innerText = '✕ ' + (data.message || 'Gagal memproses data.');
        }
    } catch (err) {
        playBeep(false);
        userStatus.style.color = '#dc2626';
        userStatus.innerText = '✕ Terjadi kesalahan koneksi.';
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
            bookStatus.style.color = '#16a34a';
            bookStatus.innerText = '✓ Buku terdeteksi: ' + data.book.judul;

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
                            <span style="display: block; font-size: 0.75rem; font-weight: 700; color: #0284c7; margin-bottom: 3px;">1 Buku</span>
                            <a href="/admin-panel/users/${data.user.id}/loans" class="btn btn-primary" style="padding: 3px 8px; font-size: 0.72rem; font-weight: 600;" title="Lihat buku dipinjam">📚 Lihat Buku</a>
                        </td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 6px; justify-content: center;">
                                <button type="button" onclick="selectUserForLoan('${data.user.nama}', '${data.user.email}', '${data.user.telepon}', ${data.user.id})" class="btn btn-success" style="padding: 4px 8px; font-size: 0.75rem;" title="Pilih anggota ini untuk peminjaman">⚡ Pinjamkan</button>
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

function printReceiptAndClose() {
    window.print();
    closeReceiptModal();
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
    bookStatus.innerText = '';
    document.getElementById('user_card').style.display = 'none';
    document.getElementById('book_card').style.display = 'none';
    hideBookStepSection();
    userNameInput.focus();
}
</script>
@endpush
@endsection
