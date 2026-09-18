@extends('layouts.admin')

@section('title', 'Hardware Barcode Scanner Hub')

@section('content')
<header style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
    <div>
        <h1>🔌 Scanner Barcode Fast-Track</h1>
        <p style="margin: 4px 0 0; font-size: 0.82rem; color: #68777d;">
            Pusat pemrosesan cepat menggunakan Scanner Barcode USB / Bluetooth (EZCode USB HID).
        </p>
    </div>
    <div>
        <a href="{{ route('admin.returns.index') }}" class="btn btn-secondary" style="font-size: 0.8rem;">📋 Menu Pengembalian</a>
    </div>
</header>

<!-- SCANNER INPUT CARD -->
<section class="panel" style="margin-bottom: 20px; border-top: 4px solid #00a65a;">
    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
        <span style="display: inline-block; width: 12px; height: 12px; border-radius: 50%; background: #00a65a; box-shadow: 0 0 8px #00a65a; animation: pulse 1.5s infinite;"></span>
        <h2 style="font-size: 1rem; margin: 0; color: #1e5e3a;">Scanner Siap Menerima Barcode</h2>
    </div>

    <form id="scanForm" onsubmit="handleManualSubmit(event)">
        <div style="display: flex; gap: 10px;">
            <input 
                type="text" 
                id="barcodeInput" 
                name="barcode" 
                autofocus 
                autocomplete="off"
                placeholder="Scan barcode anggota, ISBN buku, atau kode pinjam LN-..." 
                style="flex: 1; padding: 12px 16px; border: 2px solid #00a65a; border-radius: 4px; font-size: 1.1rem; font-family: monospace; font-weight: 700; background: #fdfdfd; box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);"
            >
            <button type="submit" class="btn btn-success" style="padding: 0 20px; font-size: 0.9rem;">Proses</button>
        </div>
    </form>
    <div id="scanNotice" style="margin-top: 8px; font-size: 0.78rem; color: #68777d;">
        💡 Klik di mana saja pada layar untuk menjaga kursor tetap aktif di kolom scanner.
    </div>
</section>

<!-- FEEDBACK BANNER -->
<div id="resultBanner" style="display: none; padding: 14px 18px; border-radius: 4px; margin-bottom: 20px; font-size: 0.95rem; font-weight: 600;"></div>

<!-- ACTIVE WORKFLOW SESSION GRID -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <!-- LEFT CARD: ANGGOTA / PEMINJAM -->
    <section class="panel" id="memberCard" style="border-top: 3px solid #3c8dbc;">
        <div class="panel-heading">
            <h2>👤 1. Data Anggota (Peminjam)</h2>
            <button type="button" onclick="clearMember()" id="btnClearMember" style="display: none; background: none; border: none; color: #dd4b39; cursor: pointer; font-size: 0.78rem;">[Ganti Anggota]</button>
        </div>

        <div id="memberEmpty" style="padding: 24px 10px; text-align: center; color: #888; font-size: 0.85rem;">
            Belum ada anggota discan.<br>
            <span style="font-size: 0.78rem; color: #aaa;">Scan ID Anggota / Nomor WA / Email untuk memulai transaksi.</span>
        </div>

        <div id="memberContent" style="display: none;">
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 12px; background: #e8f0f8; padding: 12px; border-radius: 4px;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: #3c8dbc; color: #fff; display: grid; place-items: center; font-size: 1.2rem; font-weight: 700;" id="memberAvatar">A</div>
                <div>
                    <h3 id="memberName" style="margin: 0; font-size: 1rem; color: #222;">-</h3>
                    <div id="memberMeta" style="font-size: 0.78rem; color: #555; margin-top: 2px;">-</div>
                </div>
            </div>
            <div style="font-size: 0.8rem; color: #444; display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid #eee;">
                <span>Status Pinjaman Aktif:</span>
                <strong id="memberLoansCount">0 / 3</strong>
            </div>
        </div>
    </section>

    <!-- RIGHT CARD: BUKU YANG DISCAN -->
    <section class="panel" id="bookCard" style="border-top: 3px solid #f39c12;">
        <div class="panel-heading">
            <h2>📖 2. Buku Terpilih</h2>
            <button type="button" onclick="clearBook()" id="btnClearBook" style="display: none; background: none; border: none; color: #dd4b39; cursor: pointer; font-size: 0.78rem;">[Ganti Buku]</button>
        </div>

        <div id="bookEmpty" style="padding: 24px 10px; text-align: center; color: #888; font-size: 0.85rem;">
            Belum ada buku discan.<br>
            <span style="font-size: 0.78rem; color: #aaa;">Scan Barcode ISBN pada sampul belakang buku.</span>
        </div>

        <div id="bookContent" style="display: none;">
            <div style="margin-bottom: 12px; background: #fff2d9; padding: 12px; border-radius: 4px;">
                <h3 id="bookTitle" style="margin: 0; font-size: 1rem; color: #222;">-</h3>
                <div id="bookAuthor" style="font-size: 0.8rem; color: #555; margin-top: 4px;">-</div>
                <div id="bookIsbn" style="font-size: 0.75rem; color: #777; font-family: monospace; margin-top: 2px;">-</div>
            </div>
            <div style="font-size: 0.8rem; color: #444; display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid #eee;">
                <span>Ketersediaan Stok:</span>
                <strong id="bookStockStatus">-</strong>
            </div>
        </div>
    </section>
</div>

<!-- ACTION & CONFIRMATION BOX -->
<section class="panel" id="actionPanel" style="margin-top: 20px; text-align: center; display: none;">
    <div style="max-width: 500px; margin: 0 auto; padding: 10px 0;">
        <h3 style="margin-top: 0; font-size: 1.1rem; color: #222;">Konfirmasi Peminjaman Fast-Track</h3>
        <p style="font-size: 0.85rem; color: #555; margin-bottom: 16px;">
            Konfirmasi peminjaman buku untuk anggota terpilih di atas.
        </p>

        <div style="margin-bottom: 16px; text-align: left;">
            <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 4px;">Tanggal Jatuh Tempo (Due Date):</label>
            <input type="date" id="dueDateInput" value="{{ date('Y-m-d', strtotime('+7 days')) }}" style="width: 100%; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 0.85rem;">
        </div>

        <button type="button" onclick="submitQuickLoan()" id="btnSubmitLoan" class="btn btn-success" style="width: 100%; padding: 12px; font-size: 1rem; justify-content: center;">
            ✓ Proses Peminjaman Sekarang
        </button>
    </div>
</section>

<!-- MODAL RETURN / LOAN DETAIL RESULT -->
<div id="loanModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #fff; width: 100%; max-width: 480px; border-radius: 6px; padding: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.3);">
        <h3 id="modalLoanTitle" style="margin-top: 0; font-size: 1.1rem; color: #222;">Detail Barcode Peminjaman</h3>
        <div id="modalLoanBody" style="font-size: 0.85rem; margin: 14px 0;"></div>
        <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 16px;">
            <button type="button" id="btnProcessReturn" onclick="processReturnFromModal()" class="btn btn-success" style="display: none;">Proses Pengembalian Buku</button>
            <button type="button" onclick="closeLoanModal()" class="btn btn-secondary">Tutup</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
const barcodeInput = document.getElementById('barcodeInput');
const resultBanner = document.getElementById('resultBanner');

let activeMember = null;
let activeBook = null;
let activeLoanCode = null;

let lastScannedCode = null;
let lastScannedTime = 0;
const COOLDOWN_MS = 1500;

// Universal Auto-Focus: Keeping the scanner input focused
document.addEventListener('click', (e) => {
    if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'BUTTON' && e.target.tagName !== 'A' && e.target.tagName !== 'SELECT') {
        barcodeInput.focus();
    }
});
window.addEventListener('load', () => barcodeInput.focus());

// Audio Synthesizer Beep Feedback
function playAudioBeep(type = 'success') {
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.type = 'sine';

        if (type === 'success') {
            osc.frequency.value = 880; // High tone
            gain.gain.value = 0.2;
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.15);
        } else if (type === 'warning') {
            osc.frequency.value = 587;
            gain.gain.value = 0.2;
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.2);
        } else {
            osc.frequency.value = 293; // Low tone error
            gain.gain.value = 0.3;
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.35);
        }
    } catch (e) {}
}

function showBanner(message, type = 'success') {
    resultBanner.style.display = 'block';
    if (type === 'success') {
        resultBanner.style.background = '#d4edda';
        resultBanner.style.color = '#155724';
        resultBanner.style.border = '1px solid #c3e6cb';
    } else if (type === 'warning') {
        resultBanner.style.background = '#fff3cd';
        resultBanner.style.color = '#856404';
        resultBanner.style.border = '1px solid #ffeeba';
    } else {
        resultBanner.style.background = '#f8d7da';
        resultBanner.style.color = '#721c24';
        resultBanner.style.border = '1px solid #f5c6cb';
    }
    resultBanner.innerHTML = message;
    playAudioBeep(type);

    setTimeout(() => {
        resultBanner.style.display = 'none';
    }, 4000);
}

function handleManualSubmit(e) {
    e.preventDefault();
    const code = barcodeInput.value.trim();
    if (!code) return;
    processBarcode(code);
}

async function processBarcode(code) {
    const now = Date.now();
    if (code === lastScannedCode && (now - lastScannedTime) < COOLDOWN_MS) {
        showBanner('⚠️ Scan duplikat terabaikan (cooldown 1.5 detik).', 'warning');
        barcodeInput.value = '';
        barcodeInput.focus();
        return;
    }

    lastScannedCode = code;
    lastScannedTime = now;

    barcodeInput.value = '';
    barcodeInput.focus();

    try {
        const response = await fetch('{{ route("admin.qrcode.scanner.process") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ code: code })
        });

        const res = await response.json();

        if (response.ok && res.success) {
            if (res.type === 'member') {
                setMember(res.data);
                showBanner(`✓ ${res.message}`, 'success');
            } else if (res.type === 'book') {
                setBook(res.data);
                showBanner(`✓ ${res.message}`, 'success');
            } else if (res.type === 'loan') {
                showLoanModal(res.data);
                showBanner(`✓ ${res.message}`, 'success');
            }
        } else {
            showBanner(`✕ ${res.message || 'Barcode tidak dikenali.'}`, 'danger');
        }
    } catch (err) {
        showBanner('✕ Terjadi kesalahan koneksi saat memproses barcode.', 'danger');
    }
}

function setMember(data) {
    activeMember = data;
    document.getElementById('memberEmpty').style.display = 'none';
    document.getElementById('memberContent').style.display = 'block';
    document.getElementById('btnClearMember').style.display = 'inline-block';

    document.getElementById('memberAvatar').innerText = (data.name || 'A').charAt(0).toUpperCase();
    document.getElementById('memberName').innerText = data.name;
    document.getElementById('memberMeta').innerText = `Email: ${data.email} | WA: ${data.phone}`;
    document.getElementById('memberLoansCount').innerText = `${data.active_loans_count} / ${data.max_loans}`;

    checkActionPanel();
}

function clearMember() {
    activeMember = null;
    document.getElementById('memberEmpty').style.display = 'block';
    document.getElementById('memberContent').style.display = 'none';
    document.getElementById('btnClearMember').style.display = 'none';
    checkActionPanel();
}

function setBook(data) {
    activeBook = data;
    document.getElementById('bookEmpty').style.display = 'none';
    document.getElementById('bookContent').style.display = 'block';
    document.getElementById('btnClearBook').style.display = 'inline-block';

    document.getElementById('bookTitle').innerText = data.title;
    document.getElementById('bookAuthor').innerText = `Penulis: ${data.author}`;
    document.getElementById('bookIsbn').innerText = `ISBN: ${data.isbn}`;
    document.getElementById('bookStockStatus').innerText = `${data.available} / ${data.stock} unit tersedia`;
    document.getElementById('bookStockStatus').style.color = data.available > 0 ? '#00a65a' : '#dd4b39';

    checkActionPanel();
}

function clearBook() {
    activeBook = null;
    document.getElementById('bookEmpty').style.display = 'block';
    document.getElementById('bookContent').style.display = 'none';
    document.getElementById('btnClearBook').style.display = 'none';
    checkActionPanel();
}

function checkActionPanel() {
    const actionPanel = document.getElementById('actionPanel');
    if (activeMember && activeBook) {
        actionPanel.style.display = 'block';
    } else {
        actionPanel.style.display = 'none';
    }
}

async function submitQuickLoan() {
    if (!activeMember || !activeBook) return;

    if (activeBook.available < 1) {
        showBanner('✕ Stok buku ini habis, tidak dapat dipinjam.', 'danger');
        return;
    }

    const dueDate = document.getElementById('dueDateInput').value;
    const btn = document.getElementById('btnSubmitLoan');
    btn.disabled = true;
    btn.innerText = '⏳ Memproses Peminjaman...';

    try {
        const response = await fetch('{{ route("admin.qrcode.scanner.quick_loan") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                user_id: activeMember.id,
                book_id: activeBook.id,
                due_date: dueDate
            })
        });

        const res = await response.json();

        if (response.ok && res.success) {
            showBanner(`✅ ${res.message} Kode Pinjam: ${res.loan.loan_code}`, 'success');
            clearMember();
            clearBook();
        } else {
            showBanner(`✕ ${res.message || 'Gagal memproses peminjaman.'}`, 'danger');
        }
    } catch (err) {
        showBanner('✕ Terjadi kesalahan koneksi server.', 'danger');
    } finally {
        btn.disabled = false;
        btn.innerText = '✓ Proses Peminjaman Sekarang';
    }
}

function showLoanModal(loan) {
    activeLoanCode = loan.loan_code;
    document.getElementById('modalLoanTitle').innerText = `Detail Pinjaman: ${loan.loan_code}`;
    
    let html = `
        <p><strong>Peminjam:</strong> ${loan.user_name} (${loan.user_phone})</p>
        <p><strong>Buku:</strong> ${loan.book_title}</p>
        <p><strong>Status:</strong> <span class="status ${loan.status}">${loan.status}</span></p>
        <p><strong>Tanggal Pinjam:</strong> ${loan.loan_date}</p>
        <p><strong>Jatuh Tempo:</strong> ${loan.due_date}</p>
    `;

    document.getElementById('modalLoanBody').innerHTML = html;
    
    const btnReturn = document.getElementById('btnProcessReturn');
    if (loan.status === 'borrowed' || loan.status === 'approved' || loan.status === 'overdue') {
        btnReturn.style.display = 'inline-block';
    } else {
        btnReturn.style.display = 'none';
    }

    document.getElementById('loanModal').style.display = 'flex';
}

function closeLoanModal() {
    document.getElementById('loanModal').style.display = 'none';
    barcodeInput.focus();
}

async function processReturnFromModal() {
    if (!activeLoanCode) return;

    try {
        const response = await fetch('{{ route("admin.returns.scan") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ loan_code: activeLoanCode })
        });

        const res = await response.json();
        closeLoanModal();

        if (response.ok && res.success) {
            showBanner(`✅ ${res.message}`, 'success');
        } else {
            showBanner(`✕ ${res.message || 'Gagal mengembalikan buku.'}`, 'danger');
        }
    } catch (e) {
        closeLoanModal();
        showBanner('✕ Error koneksi pengembalian.', 'danger');
    }
}
</script>
@endpush
@endsection
