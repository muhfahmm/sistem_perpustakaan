# 🎯 Skenario: Peminjaman On-Site dengan Nota QR

Sempurna! Ini skenario paling praktis dan paling umum di perpustakaan sekolah. Saya rancang lengkap dari **awal sampai akhir**.

---

## 🎬 ALUR LENGKAP — Step by Step

### 📍 Skenario:
> Ahmad (siswa) datang ke perpustakaan bawa buku **Laskar Pelangi** yang mau dipinjam. Admin di meja jaga scan buku + scan kartu siswa, sistem langsung cetak **nota QR**, dan data tersimpan di database untuk pengingat pengembalian otomatis.

---

## 🚶 STEP 1 — Siswa Datang ke Perpustakaan

Ahmad datang ke meja admin, bawa:
- 📚 Buku yang mau dipinjam
- 🎫 Kartu siswa (atau sebutkan NIS/Nama)

---

## 💻 STEP 2 — Admin Buka Halaman On-Site Scan

Admin buka: `/admin-panel/scan` (mode: **Peminjaman**)

Halaman siap, input auto-focus:

```
┌──────────────────────────────────────────────┐
│  📚 PEMINJAMAN ON-SITE                       │
│  ──────────────────────────────────────────  │
│                                              │
│  ┌────────────────────────────────────┐     │
│  │ 📷 Scan kartu siswa...              │     │
│  │ ▌                                   │     │
│  └────────────────────────────────────┘     │
│                                              │
│  💡 Petunjuk:                                │
│  1. Scan kartu siswa                        │
│  2. Scan barcode buku                       │
│  3. Konfirmasi → Nota tercetak otomatis    │
│                                              │
└──────────────────────────────────────────────┘
```

---

## 🎫 STEP 3 — Admin Scan Kartu Siswa

Admin ambil kartu siswa → **scan pakai EZCode**

Kartu siswa formatnya: `SIS-2024-0001` atau `NIS: 20240001`

Sistem langsung verifikasi:

```
┌──────────────────────────────────────────────┐
│  ✅ SISWA TERDETEKSI                         │
│  ──────────────────────────────────────────  │
│                                              │
│  👤 Ahmad Fauzi                             │
│  🎓 Kelas: XI IPA 2                         │
│  🆔 NIS: 20240001                           │
│  📱 WA: 62812xxxxxx                         │
│                                              │
│  📊 STATUS:                                  │
│  ✅ Aktif (tidak blacklist)                  │
│  📚 Sedang pinjam: 1 buku                    │
│  🎯 Sisa kuota: 2 buku lagi                  │
│                                              │
│  ─────────────────────────────────────────   │
│  ➡️ Sekarang scan barcode buku...            │
│                                              │
│  ┌────────────────────────────────────┐     │
│  │ 📷 ▌                                │     │
│  └────────────────────────────────────┘     │
└──────────────────────────────────────────────┘
```

**Kalau ada masalah** (misalnya siswa blacklist atau kuota habis), sistem TOLAK di langkah ini sebelum scan buku.

---

## 📖 STEP 4 — Admin Scan Barcode Buku

Admin ambil buku Laskar Pelangi → **scan barcode-nya** (`9786233100267`)

Sistem cek dalam **1 transaksi** (semua atau tidak sama sekali):

```
✓ Buku ada di database
✓ ISBN valid (checksum OK)
✓ Stok tersedia (3 → akan jadi 2)
✓ Siswa belum pinjam buku ini
✓ Kuota siswa masih ada
✓ Tidak ada pinjaman aktif duplikat
```

**Kalau semua OK** → muncul konfirmasi:

```
┌──────────────────────────────────────────────┐
│  📋 KONFIRMASI PEMINJAMAN                    │
│  ──────────────────────────────────────────  │
│                                              │
│  👤 Ahmad Fauzi (XI IPA 2)                  │
│  📖 Laskar Pelangi                          │
│  🏷️ ISBN: 9786233100267                     │
│  📍 Lokasi: Rak A-3                         │
│                                              │
│  ─────────────────────────────────────────   │
│                                              │
│  📅 TANGGAL PINJAM:                         │
│  18 September 2026                          │
│                                              │
│  📅 JATUH TEMPO:                            │
│  ┌─────────────────────────────────┐        │
│  │ 25 September 2026  (7 hari)     │        │
│  └─────────────────────────────────┘        │
│                                              │
│  Quick select:                               │
│  ○ 3 hari  ● 7 hari  ○ 14 hari  ○ 30 hari  │
│                                              │
│  📝 Catatan (opsional):                      │
│  [Kondisi buku baik_______________]         │
│                                              │
│  ┌────────────────┐  ┌────────────────┐     │
│  │ ✓ KONFIRMASI   │  │ ✗ BATAL        │     │
│  │  & CETAK NOTA  │  │                │     │
│  └────────────────┘  └────────────────┘     │
│                                              │
└──────────────────────────────────────────────┘
```

Admin bisa **ubah jatuh tempo** kalau perlu (misal untuk tugas khusus 14 hari).

---

## ⚡ STEP 5 — Admin Klik [✓ Konfirmasi & Cetak Nota]

**Sistem otomatis dalam 1 detik**:

```
1. DB Transaction:
   ├─ LOCK buku (anti race condition)
   ├─ Cek ulang stok
   ├─ Kurangi stok: 3 → 2
   ├─ Insert loan (status: BORROWED)
   ├─ Insert loan_log (action: 'borrow_onsite')
   ├─ Update counter user
   └─ COMMIT

2. Generate QR Code:
   ├─ Isi QR: LN-20260918-ABC123
   ├─ Simpan: storage/private/qrcodes/
   └─ Tanda tangan HMAC (anti pemalsuan)

3. Kirim ke Printer:
   └─ Print nota otomatis (printer thermal/USB)

4. Kirim WA ke siswa (opsional):
   └─ "Buku Laskar Pelangi berhasil dipinjam.
      Jatuh tempo: 25 Sep 2026."

5. Bunyi Beep ✅ sukses

6. Reset halaman → siap scan siswa berikutnya
```

---

## 🖨️ STEP 6 — Nota Tercetak Otomatis

**Format nota ukuran kertas thermal 80mm** (seperti struk minimarket):

```
================================
   📚 PERPUSTAKAAN DIGITAL
      SMP NEGERI 1 JAKARTA
================================
   NOTA PEMINJAMAN BUKU
================================

No. Nota : LN-20260918-ABC123
Tanggal  : 18 Sep 2026, 10:23

--------------------------------
PEMINJAM
--------------------------------
Nama     : Ahmad Fauzi
Kelas    : XI IPA 2
NIS      : 20240001

--------------------------------
BUKU DIPINJAM
--------------------------------
Judul    : Laskar Pelangi
ISBN     : 9786233100267
Pengarang: Andrea Hirata
Lokasi   : Rak A-3

--------------------------------
TANGGAL
--------------------------------
Pinjam      : 18 Sep 2026
Jatuh Tempo : 25 Sep 2026
Durasi      : 7 hari

--------------------------------
        [QR CODE]
     Scan untuk pengembalian
        
     LN-20260918-ABC123

--------------------------------

⚠️  PENGINGAT:
• Kembalikan sebelum 25 Sep 2026
• Denda Rp 1.000/hari keterlambatan
• Simpan nota ini sampai
  buku dikembalikan
• Buku yang rusak/hilang
  wajib diganti

================================
 Terima kasih 🙏
================================
```

**Nota ini berisi QR Code** — kunci untuk pengembalian nanti.

---

## 📱 STEP 7 — WA Notifikasi Terkirim (Opsional)

Beberapa detik setelah scan, WA Ahmad berdering:

```
📚 Perpustakaan Digital

Halo Ahmad Fauzi,

✅ Peminjaman BERHASIL

📖 Buku: Laskar Pelangi
🏷️ Kode: LN-20260918-ABC123
📅 Pinjam: 18 Sep 2026
⏰ Jatuh Tempo: 25 Sep 2026

⚠️ Jangan lupa kembalikan
sebelum tanggal jatuh tempo ya!

📎 Simpan nota QR Anda
```

---

## 💾 STEP 8 — Data Tersimpan di Database

Semua tercatat rapi:

### Tabel `loans`:
| id | loan_code | user_id | book_id | loan_date | due_date | return_date | status | qr_code_path |
|----|-----------|---------|---------|-----------|----------|-------------|--------|--------------|
| 15 | LN-20260918-ABC123 | 42 | 7 | 2026-09-18 | 2026-09-25 | NULL | borrowed | qrcodes/abc123.png |

### Tabel `books` (stok otomatis berkurang):
| id | title | stock | available |
|----|-------|-------|-----------|
| 7 | Laskar Pelangi | 3 | **2** ← berkurang |

### Tabel `loan_logs`:
| id | loan_id | action | actor_id | created_at |
|----|---------|--------|----------|------------|
| 45 | 15 | borrow_onsite | 1 (admin) | 2026-09-18 10:23 |

### Tabel `notifications` (untuk reminder):
| id | loan_id | type | scheduled_at | status |
|----|---------|------|--------------|--------|
| 78 | 15 | reminder_h1 | 2026-09-24 08:00 | pending |
| 79 | 15 | overdue | 2026-09-26 08:00 | pending |

---

## ⏰ STEP 9 — Pengingat Otomatis Berjalan

Sistem cron jalan otomatis setiap hari:

### 🌅 H-1 Sebelum Jatuh Tempo (24 Sep 2026, 08:00)

```
Cron cek: ada loan yang due_date = besok?
→ Ketemu: loan#15 (Ahmad Fauzi, Laskar Pelangi)

Sistem kirim WA:
┌────────────────────────────────────┐
│ 📚 Perpustakaan Digital            │
│                                     │
│ Halo Ahmad Fauzi,                  │
│                                     │
│ ⏰ PENGINGAT                        │
│                                     │
│ Buku "Laskar Pelangi" jatuh        │
│ tempo BESOK (25 Sep 2026).         │
│                                     │
│ Mohon kembalikan tepat waktu ya.   │
│                                     │
│ 📎 Bawa nota QR Anda               │
└────────────────────────────────────┘
```

### 🔴 Hari Jatuh Tempo (25 Sep 2026, 08:00)

```
Cron cek: ada yang due_date = hari ini?
→ Kirim WA kedua:

"⏰ HARI INI jatuh tempo buku Laskar Pelangi.
Mohon dikembalikan hari ini sebelum perpus tutup."
```

### ⚠️ Lewat Jatuh Tempo (26 Sep 2026, 08:00)

```
Cron cek: ada yang due_date < hari ini & belum kembali?
→ Update status: borrowed → OVERDUE
→ Kirim WA:

"🔴 Buku Laskar Pelangi TERLAMBAT 1 hari.
Denda: Rp 1.000/hari.
Total saat ini: Rp 1.000.
Mohon segera kembalikan."
```

WA ini **berulang setiap hari** sampai buku dikembalikan (dengan dedup key agar tidak spam).

---

## 🔄 STEP 10 — Siswa Kembalikan Buku

Beberapa hari kemudian, Ahmad datang bawa:
- 📚 Buku Laskar Pelangi
- 🎫 Nota QR (atau sebutkan kode)

### Admin buka mode **Pengembalian**:

```
┌──────────────────────────────────────────────┐
│  📚 PENGEMBALIAN BUKU                        │
│  ──────────────────────────────────────────  │
│                                              │
│  ┌────────────────────────────────────┐     │
│  │ 📷 Scan QR nota / barcode buku...   │     │
│  │ ▌                                   │     │
│  └────────────────────────────────────┘     │
│                                              │
└──────────────────────────────────────────────┘
```

### Admin scan QR di nota → sistem cek:

```
┌──────────────────────────────────────────────┐
│  ✅ DATA PEMINJAMAN DITEMUKAN                │
│  ──────────────────────────────────────────  │
│                                              │
│  👤 Ahmad Fauzi (XI IPA 2)                  │
│  📖 Laskar Pelangi                          │
│  🏷️ Kode: LN-20260918-ABC123               │
│                                              │
│  📅 Pinjam: 18 Sep 2026                     │
│  📅 Due: 25 Sep 2026                        │
│  📅 Hari ini: 23 Sep 2026                   │
│                                              │
│  ✅ TEPAT WAKTU (2 hari lebih awal)         │
│  💰 Denda: Rp 0                             │
│                                              │
│  Kondisi buku:                              │
│  ○ Baik  ○ Rusak ringan  ○ Rusak berat     │
│                                              │
│  [✓ Konfirmasi Pengembalian]                │
└──────────────────────────────────────────────┘
```

### Admin klik Konfirmasi → sistem:

```
1. Update loan: status = RETURNED, return_date = today
2. Stok buku bertambah: 2 → 3
3. Counter user berkurang
4. Insert loan_log: action = 'returned'
5. Cancel semua notifikasi reminder yang pending
6. Bunyi beep sukses
7. (Opsional) Cetak struk pengembalian
```

---

## 🧾 STEP 11 — Nota Pengembalian (Opsional)

Kalau perlu bukti, printer cetak struk kedua:

```
================================
   📚 PERPUSTAKAAN DIGITAL
================================
   BUKTI PENGEMBALIAN
================================

No. Pinjam: LN-20260918-ABC123
Tanggal   : 23 Sep 2026, 14:15

--------------------------------
PEMINJAM
--------------------------------
Nama  : Ahmad Fauzi
Kelas : XI IPA 2
NIS   : 20240001

--------------------------------
BUKU
--------------------------------
Judul : Laskar Pelangi
ISBN  : 9786233100267

--------------------------------
STATUS
--------------------------------
Pinjam     : 18 Sep 2026
Dikembalikan: 23 Sep 2026
Durasi     : 5 hari (tepat waktu)

Denda      : Rp 0

--------------------------------
Terima kasih sudah mengembalikan
tepat waktu! 🌟
================================
```

---

## 📊 RINGKASAN ALUR

```
┌──────────────────────────────────────────────┐
│  SISWA DATANG KE PERPUSTAKAAN                │
└──────────────────┬───────────────────────────┘
                   ▼
┌──────────────────────────────────────────────┐
│  1. Admin scan kartu siswa                   │
│     → Sistem verifikasi (aktif, kuota OK)    │
└──────────────────┬───────────────────────────┘
                   ▼
┌──────────────────────────────────────────────┐
│  2. Admin scan barcode buku                  │
│     → Sistem cek stok + duplikasi            │
└──────────────────┬───────────────────────────┘
                   ▼
┌──────────────────────────────────────────────┐
│  3. Admin set due date + konfirmasi          │
└──────────────────┬───────────────────────────┘
                   ▼
┌──────────────────────────────────────────────┐
│  4. SISTEM OTOMATIS:                         │
│     ✓ Simpan ke database                     │
│     ✓ Kurangi stok                           │
│     ✓ Generate QR                            │
│     ✓ Cetak nota (printer)                   │
│     ✓ Kirim WA ke siswa                      │
└──────────────────┬───────────────────────────┘
                   ▼
┌──────────────────────────────────────────────┐
│  5. Nota QR diserahkan ke siswa              │
│     Buku diserahkan ke siswa                 │
└──────────────────┬───────────────────────────┘
                   ▼
┌──────────────────────────────────────────────┐
│  6. CRON HARIAN OTOMATIS:                    │
│     • H-1 → WA reminder                      │
│     • Hari H → WA jatuh tempo                │
│     • H+1+ → WA overdue + denda              │
└──────────────────┬───────────────────────────┘
                   ▼
┌──────────────────────────────────────────────┐
│  7. Siswa kembali → scan nota QR             │
│     → Update status + kembalikan stok        │
│     → Cancel reminder yang pending           │
└──────────────────────────────────────────────┘
```

---

## 💡 POIN PENTING Skenario Ini

| Aspek | Detail |
|-------|--------|
| **Trigger** | Admin scan manual (bukan self-service) |
| **Kecepatan** | ~30 detik per siswa |
| **Output** | Nota QR tercetak + tersimpan di DB |
| **Reminder** | Otomatis via WA (H-1, H, H+1, dst) |
| **Bukti fisik** | Nota QR dipegang siswa |
| **Pengembalian** | Scan QR dari nota |
| **Anti-bug** | Idempotency + lock + state machine |

---

## 🎯 KEUNGGULAN SKENARIO INI

1. ✅ **Cepat** — 30 detik per siswa, tidak perlu user login dulu
2. ✅ **Simpel** — siswa tidak perlu paham web
3. ✅ **Bukti fisik** — nota QR jadi pegangan siswa
4. ✅ **Otomatis** — reminder WA mengurangi beban admin
5. ✅ **Akuntabel** — semua tercatat di database
6. ✅ **Anti-bug** — kalau scan 2x, sistem tetap aman

---

## 🛠️ YANG PERLU DISIAPKAN

### Hardware:
| Item | Fungsi |
|------|--------|
| 💻 1 Komputer/Laptop | Admin station |
| 📷 Scanner EZCode (USB) | Scan barcode + QR |
| 🖨️ Printer thermal 80mm | Cetak nota |
| 🖨️ Printer label (opsional) | Cetak QR sticker untuk buku |

### Software:
| Item | Fungsi |
|------|--------|
| Laravel 11 | Backend |
| MySQL | Database |
| WA Gateway (Fonnte/Wablas) | Kirim reminder |
| simple-qrcode | Generate QR |
| dompdf | Kalau ada cetak PDF |
| Cron / Task Scheduler | Reminder otomatis |

### Konfigurasi:
```env
# .env
LIBRARY_DEFAULT_LOAN_DAYS=7
LIBRARY_MAX_ACTIVE_LOANS=3
LIBRARY_FINE_PER_DAY=1000
FONNTE_TOKEN=xxxxx
PRINTER_NAME=Thermal_80mm
```

---

## 🔔 JADWAL REMINDER OTOMATIS

| Kapan | Trigger | WA yang dikirim |
|-------|---------|-----------------|
| H-1 (jam 08:00) | `due_date = besok` | "Pengingat, besok jatuh tempo" |
| H (jam 08:00) | `due_date = hari ini` | "Hari ini jatuh tempo!" |
| H+1 (jam 08:00) | `due_date < hari ini` | "Terlambat 1 hari, denda Rp X" |
| H+2 dst | Setiap hari sampai kembali | Update total denda |
| Setelah kembali | Return dikonfirmasi | "Terima kasih!" |

---

## ❓ FAQ Skenario Ini

**Q: Kalau siswa lupa bawa nota QR saat mengembalikan?**
A: Admin bisa cari manual berdasarkan nama/NIS di menu pengembalian.

**Q: Kalau siswa hilang nota QR?**
A: Admin bisa regenerate dari menu **Kartu QR → Regenerate**. Kode lama tetap valid.

**Q: Kalau printer thermal mati?**
A: Sistem tetap simpan ke DB. Nota bisa dicetak ulang dari menu **Kartu QR**.

**Q: Kalau 2 admin scan buku yang sama bersamaan?**
A: Sistem pakai `Cache::lock` — yang kedua dapat pesan "sedang diproses".

**Q: Kalau siswa pinjam 2 buku sekaligus?**
A: Ulangi proses scan untuk buku kedua. Bisa gabung dalam 1 nota (opsional).

**Q: Kalau WA siswa tidak aktif?**
A: Sistem log pengiriman gagal. Admin bisa kirim manual atau hubungi orang tua.

---

Mau saya lanjutkan ke:
- 📝 **Kode `ScanController` + `LoanService`** untuk skenario ini
- 🎨 **Halaman Blade scan on-site** lengkap dengan JavaScript
- 🖨️ **Template nota thermal** (HTML/CSS untuk printer 80mm)
- 🔔 **Cron job reminder WA** lengkap dengan dedup