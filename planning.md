# 🔌 Integrasi Scanner EZCode dengan Sistem Perpustakaan Laravel

Barcode yang Anda scan — `9786233100267` — adalah **EAN-13 / ISBN-13** (13 digit, diawali `978`). Ini kabar bagus: formatnya standar internasional untuk buku, jadi bisa langsung divalidasi dan dipetakan ke tabel `books.isbn`.

Scanner EZCode USB Anda bekerja sebagai **USB HID Keyboard** — begitu dicolok, OS mengenalinya sebagai keyboard. Saat scan, ia "mengetik" `9786233100267` lalu menekan **Enter** secara otomatis. Jadi secara teknis, **tidak perlu driver** dan **langsung jalan** di web app. Tapi untuk produksi, tetap ada beberapa hal yang **wajib dioptimalkan** agar tidak bug.

---

## 1️⃣ Cara Kerja EZCode di Web App

```
[Scan barcode buku]
        ↓
EZCode mengirim: "9786233100267" + [Enter]
        ↓
Browser menerima sebagai ketikan keyboard
        ↓
Input field di halaman web menangkap karakter + Enter
        ↓
JavaScript mendeteksi Enter → submit form via AJAX
        ↓
Backend Laravel proses → cek stok, cek duplikasi, simpan loan
```

Tanpa konfigurasi apa pun, scanner ini sudah "bicara" ke browser. Yang perlu Anda siapkan adalah **halaman web yang siap menerima input tersebut**.

---

## 2️⃣ Optimasi Frontend (Halaman Scanner)

### A. Auto-Focus + Auto-Submit

Masalah umum: user harus klik dulu ke input field setiap kali scan. Solusinya:

```blade
<!-- resources/views/admin/scan/index.blade.php -->
<form id="scanForm" action="{{ route('admin.scan.process') }}" method="POST">
    @csrf
    <input 
        type="text" 
        id="barcodeInput" 
        name="barcode" 
        autofocus
        autocomplete="off"
        inputmode="numeric"
        pattern="[0-9]*"
        placeholder="Scan barcode buku..."
        class="w-full text-2xl p-4 font-mono"
    >
</form>

<script>
const input = document.getElementById('barcodeInput');
const form  = document.getElementById('scanForm');

// Fokus otomatis di mana pun user klik
document.addEventListener('click', () => input.focus());
window.addEventListener('load', () => input.focus());

// Auto-submit saat scanner kirim Enter
input.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && input.value.trim() !== '') {
        e.preventDefault();
        submitBarcode(input.value.trim());
    }
});
</script>
```

### B. Deteksi Kecepatan Input (Scanner vs Manusia)

Scanner EZCode mengetik **sangat cepat** (biasanya < 30ms antar karakter), sedangkan manusia > 100ms. Ini bisa dimanfaatkan untuk membedakan input scanner dari input manual:

```javascript
let lastKeyTime = 0;
let buffer = '';
const SCANNER_SPEED = 50; // ms

input.addEventListener('keypress', (e) => {
    const now = Date.now();
    const diff = now - lastKeyTime;
    lastKeyTime = now;

    // Kalau jeda terlalu lama → berarti manusia ngetik
    if (diff > SCANNER_SPEED) buffer = '';
    buffer += e.key;

    if (e.key === 'Enter' && buffer.length > 5) {
        // Kemungkinan besar dari scanner
        console.log('Scanner detected:', buffer);
    }
});
```

### C. Anti Double-Scan (Cooldown)

Kadang scanner bisa trigger 2x dalam 1 detik. Tambahkan cooldown:

```javascript
let lastScanned = null;
let lastScannedTime = 0;
const COOLDOWN = 2000; // 2 detik

function submitBarcode(code) {
    const now = Date.now();
    
    // Abaikan kalau scan sama dalam 2 detik terakhir
    if (code === lastScanned && now - lastScannedTime < COOLDOWN) {
        console.log('Duplicate scan ignored');
        return;
    }
    
    lastScanned = code;
    lastScannedTime = now;
    
    // Kirim ke backend via AJAX
    fetch(form.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
        },
        body: JSON.stringify({ barcode: code }),
    })
    .then(res => res.json())
    .then(data => {
        showResult(data);
        input.value = ''; // reset untuk scan berikutnya
        input.focus();
    });
}
```

### D. Feedback Suara & Visual

Admin sering scan sambil berdiri, tidak selalu lihat layar. Tambahkan feedback:

```javascript
function playBeep(success) {
    const audio = new Audio(success 
        ? '/sounds/beep-success.mp3' 
        : '/sounds/beep-error.mp3');
    audio.volume = 0.5;
    audio.play();
}

function showResult(data) {
    playBeep(data.success);
    
    const banner = document.getElementById('resultBanner');
    banner.className = data.success 
        ? 'bg-green-500 text-white p-6 text-2xl rounded'
        : 'bg-red-500 text-white p-6 text-2xl rounded';
    banner.textContent = data.message;
    banner.classList.remove('hidden');
    
    setTimeout(() => banner.classList.add('hidden'), 3000);
}
```

---

## 3️⃣ Optimasi Backend (Laravel)

### A. Validasi Format ISBN-13

Barcode `9786233100267` adalah ISBN-13. Validasi **checksum**-nya agar tidak sembarang angka masuk:

```php
// app/Http/Requests/ScanBarcodeRequest.php
public function rules(): array
{
    return [
        'barcode' => ['required', 'string', 'regex:/^\d{13}$/'],
    ];
}

// app/Services/BarcodeService.php
public function isValidIsbn13(string $code): bool
{
    if (!preg_match('/^\d{13}$/', $code)) return false;
    
    // Cek prefix ISBN (978 atau 979)
    if (!in_array(substr($code, 0, 3), ['978', '979'])) return false;
    
    // Cek checksum EAN-13
    $sum = 0;
    for ($i = 0; $i < 12; $i++) {
        $sum += (int)$code[$i] * ($i % 2 === 0 ? 1 : 3);
    }
    $checkDigit = (10 - ($sum % 10)) % 10;
    
    return $checkDigit === (int)$code[12];
}
```

**Untuk `9786233100267`:**
- Prefix `978` ✅ (ISBN)
- Checksum: dihitung, digit terakhir `7` harus cocok ✅

### B. Identifikasi Tipe Barcode

Sistem perpustakaan Anda mungkin punya **3 jenis barcode**:

| Prefix | Tipe | Contoh |
|--------|------|--------|
| `978` / `979` | Buku (ISBN) | `9786233100267` |
| `AGT-` | Kartu Anggota | `AGT-2024001` |
| `LN-` | Kode Peminjaman | `LN-20260918-ABC123` |

```php
public function identify(string $code): array
{
    if (preg_match('/^(978|979)\d{10}$/', $code)) {
        return ['type' => 'book', 'code' => $code];
    }
    
    if (str_starts_with($code, 'AGT-')) {
        return ['type' => 'member', 'code' => $code];
    }
    
    if (str_starts_with($code, 'LN-')) {
        return ['type' => 'loan', 'code' => $code];
    }
    
    return ['type' => 'unknown', 'code' => $code];
}
```

### C. Lock Anti Race Condition

Kalau ada 2 admin scan barcode yang sama bersamaan, harus dipastikan hanya 1 yang sukses:

```php
// app/Http/Controllers/Admin/ScanController.php
public function process(Request $request, LoanService $loanService)
{
    $barcode = $request->validated()['barcode'];
    
    // Lock berdasarkan barcode — cegah proses paralel
    $lock = Cache::lock("scan:{$barcode}", 5);
    
    if (!$lock->get()) {
        return response()->json([
            'success' => false,
            'message' => 'Barcode sedang diproses, tunggu sebentar.',
        ], 429);
    }
    
    try {
        $identified = app(BarcodeService::class)->identify($barcode);
        
        return match ($identified['type']) {
            'book'   => $this->handleBookScan($identified['code']),
            'member' => $this->handleMemberScan($identified['code']),
            'loan'   => $this->handleLoanScan($identified['code'], $loanService),
            default  => response()->json([
                'success' => false,
                'message' => 'Barcode tidak dikenali.',
            ], 422),
        };
    } finally {
        $lock->release();
    }
}
```

### D. Rate Limiting

Cegah spam request (misal scanner rusak, kirim data terus-menerus):

```php
// RouteServiceProvider.php
RateLimiter::for('scan', function (Request $request) {
    return Limit::perMinute(120)->by($request->user()->id);
    // 2 scan/detik max — cukup untuk scan cepat
});
```

---

## 4️⃣ Konfigurasi Fisik Scanner EZCode

Meskipun EZCode "plug and play", ada beberapa setting yang **sebaiknya dicek** via buku manual (scan barcode konfigurasi):

| Setting | Rekomendasi | Kenapa |
|---------|-------------|--------|
| **Interface Mode** | USB HID Keyboard | Default, sudah benar |
| **Suffix** | Enter/CR | Agar auto-submit form |
| **Prefix** | (kosong) | Tidak perlu |
| **Scan Mode** | Single scan | Bukan continuous |
| **Beep** | ON | Feedback suara |
| **Keyboard Layout** | US QWERTY | Standar |
| **Symbology** | EAN-13, Code 128, QR | Sesuai kebutuhan |

> 💡 **Kalau Enter tidak terkirim** setelah scan: cek manual scanner Anda, cari barcode "Suffix Setting" → "Enter/CR". Setiap scanner EZCode biasanya punya buku manual dengan barcode konfigurasi.

---

## 5️⃣ Database Tambahan untuk Audit

Tambahkan tabel `scan_logs` untuk mencatat semua scan:

```sql
CREATE TABLE scan_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_id BIGINT UNSIGNED NOT NULL,
    barcode VARCHAR(50) NOT NULL,
    type ENUM('book','member','loan','unknown') NOT NULL,
    action VARCHAR(50) NULL,
    result ENUM('success','failed') NOT NULL,
    loan_id BIGINT UNSIGNED NULL,
    message VARCHAR(255) NULL,
    scanned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id),
    FOREIGN KEY (loan_id) REFERENCES loans(id) ON DELETE SET NULL,
    INDEX idx_barcode (barcode),
    INDEX idx_scanned_at (scanned_at)
) ENGINE=InnoDB;
```

Simpan setiap scan — berguna untuk debugging kalau ada masalah "kenapa buku ini tidak terdeteksi?".

---

## 6️⃣ Flow Lengkap Peminjaman via Scanner EZCode

```
[Admin buka /admin-panel/scan?mode=borrow]
                │
                ▼
┌───────────────────────────────────────┐
│ Input auto-focus, siap menerima scan  │
└───────────────┬───────────────────────┘
                │
       [Scan kartu anggota]
                │
                ▼
┌───────────────────────────────────────┐
│ BarcodeService::identify()            │
│ → type: 'member'                      │
│ → tampilkan data user: Ahmad Fauzi    │
└───────────────┬───────────────────────┘
                │
       [Scan barcode buku: 9786233100267]
                │
                ▼
┌───────────────────────────────────────┐
│ BarcodeService::identify()            │
│ → type: 'book'                        │
│ → cari di tabel books WHERE isbn=...  │
│ → cek stok, cek duplikasi             │
└───────────────┬───────────────────────┘
                │
                ▼
┌───────────────────────────────────────┐
│ Tampilkan konfirmasi:                 │
│ • Buku: Laskar Pelangi                │
│ • Peminjam: Ahmad Fauzi               │
│ • Due date: [date picker]             │
│                                       │
│ [✓ Konfirmasi] [✗ Batal]              │
└───────────────┬───────────────────────┘
                │
                ▼
┌───────────────────────────────────────┐
│ LoanService::createWithApproval()     │
│ • DB transaction                      │
│ • lockForUpdate() buku                │
│ • Generate loan_code + QR             │
│ • Insert loan_logs + scan_logs        │
│ • Commit                              │
└───────────────┬───────────────────────┘
                │
                ▼
┌───────────────────────────────────────┐
│ ✅ Sukses — bunyi beep                │
│ Cetak kartu QR? [Ya] [Tidak]          │
│ Reset untuk scan berikutnya           │
└───────────────────────────────────────┘
```

---

## 7️⃣ Testing Scanner EZCode Anda

Sebelum integrasi penuh, lakukan tes sederhana:

### Tes 1: Cek Scanner di Notepad
1. Buka Notepad
2. Scan barcode buku
3. **Harus muncul:** `9786233100267` lalu kursor pindah ke baris baru (Enter)

Kalau **tidak muncul Enter**, berarti suffix scanner belum diset. Scan barcode konfigurasi di manual EZCode → "Suffix Setting" → "Enter".

### Tes 2: Cek di Browser
1. Buka halaman HTML dengan `<input type="text">`
2. Klik input, scan barcode
3. **Harus muncul:** `9786233100267` di input

### Tes 3: Cek Format Barcode
```php
// Test di Laravel Tinker
php artisan tinker
>>> $code = '9786233100267';
>>> app(App\Services\BarcodeService::class)->isValidIsbn13($code);
=> true
```

---

## 8️⃣ Checklist Optimasi Scanner EZCode

| # | Item | Wajib? |
|---|------|--------|
| 1 | Auto-focus input field | ✅ Wajib |
| 2 | Auto-submit on Enter | ✅ Wajib |
| 3 | Validasi format ISBN-13 + checksum | ✅ Wajib |
| 4 | Deteksi tipe (buku/anggota/loan) | ✅ Wajib |
| 5 | Cooldown anti-double-scan (2 detik) | ✅ Wajib |
| 6 | Backend `Cache::lock` | ✅ Wajib |
| 7 | Rate limiting | ✅ Wajib |
| 8 | Log semua scan ke `scan_logs` | 🟡 Recommended |
| 9 | Feedback suara (beep) | 🟡 Recommended |
| 10 | Feedback visual (banner) | ✅ Wajib |
| 11 | Multiple mode (borrow/return/check) | ✅ Wajib |
| 12 | Konfigurasi suffix Enter di scanner | ✅ Wajib |

---

## 🎯 TL;DR untuk Scanner EZCode Anda

**Jawaban singkat:** 

Scanner EZCode USB Anda **langsung bisa dipakai** — tidak perlu driver. Tapi untuk **sistem perpustakaan yang andal**, perlu:

1. ✅ **Auto-focus + auto-submit** di halaman scan
2. ✅ **Validasi ISBN-13** (barcode `9786233100267` adalah ISBN valid)
3. ✅ **Deteksi tipe barcode** (buku vs anggota vs loan)
4. ✅ **Anti double-scan** (cooldown 2 detik + backend lock)
5. ✅ **Log semua scan** untuk audit
6. ✅ **Feedback** suara + visual
7. ✅ **Cek setting suffix** scanner (harus kirim Enter)

**Yang tidak perlu dioptimasi:** driver/sistem operasi — USB HID sudah plug-and-play.

---

Mau saya lanjutkan ke:
- 📝 **Kode lengkap `ScanController` + `BarcodeService`** siap pakai
- 🎨 **Halaman Blade scanner** lengkap dengan UI + JavaScript
- 🗄️ **Migration `scan_logs`** + integrasi ke sistem yang sudah ada
- 🖨️ **Integrasi printer thermal** untuk cetak kartu QR otomatis setelah scan

Pilih yang mana?