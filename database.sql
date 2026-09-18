-- =========================================
-- DATABASE: db_perpus
-- =========================================

CREATE DATABASE IF NOT EXISTS db_perpus
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_perpus;

-- ============ TB_ADMIN ============
CREATE TABLE tb_admin (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(150) UNIQUE NULL,
    password VARCHAR(255) NOT NULL,
    status ENUM('active', 'suspended', 'banned') DEFAULT 'active',
    last_login_at TIMESTAMP NULL,
    last_login_ip VARCHAR(45) NULL,
    failed_login_attempts INT UNSIGNED DEFAULT 0,
    locked_until TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username)
) ENGINE=InnoDB;

-- ============ TB_USER (PEMINJAM BUKU) ============
CREATE TABLE tb_user (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    telepon VARCHAR(20) NOT NULL,          -- nomor WA (format 62xxx)
    password VARCHAR(255) NOT NULL,
    status_aktif TINYINT(1) DEFAULT 1,
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL,
    INDEX idx_telepon (telepon)
) ENGINE=InnoDB;

-- ============ TB_KATEGORI ============
CREATE TABLE tb_kategori (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kategori VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

-- ============ TB_DATA_BUKU ============
CREATE TABLE tb_data_buku (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kategori_id BIGINT UNSIGNED NULL,
    judul VARCHAR(200) NOT NULL,
    penulis VARCHAR(150) NOT NULL,
    isbn VARCHAR(20) UNIQUE NULL,
    stok INT UNSIGNED DEFAULT 1,           -- total stok
    tersedia INT UNSIGNED DEFAULT 1,       -- tersedia saat ini
    FOREIGN KEY (kategori_id) REFERENCES tb_kategori(id) ON DELETE SET NULL,
    INDEX idx_judul (judul),
    INDEX idx_tersedia (tersedia)
) ENGINE=InnoDB;

-- ============ TB_PINJAMAN ============
CREATE TABLE tb_pinjaman (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_pinjam VARCHAR(30) UNIQUE NOT NULL,     -- kode unik di QR (misal: LN-xxx)
    user_id BIGINT UNSIGNED NOT NULL,
    buku_id BIGINT UNSIGNED NOT NULL,
    disetujui_oleh BIGINT UNSIGNED NULL,          -- admin yang approve
    tanggal_pinjam DATE NOT NULL,
    jatuh_tempo DATE NOT NULL,                    -- ditentukan admin
    tanggal_kembali DATE NULL,
    status ENUM('pending','approved','rejected','borrowed','returned','overdue','lost')
        DEFAULT 'pending',
    qr_code_path VARCHAR(255) NULL,
    catatan TEXT NULL,

    FOREIGN KEY (user_id) REFERENCES tb_user(id) ON DELETE CASCADE,
    FOREIGN KEY (buku_id) REFERENCES tb_data_buku(id) ON DELETE RESTRICT,
    FOREIGN KEY (disetujui_oleh) REFERENCES tb_admin(id) ON DELETE SET NULL,

    -- 🔒 KUNCI ANTI DOUBLE: user tidak boleh pinjam buku sama yang masih aktif
    UNIQUE KEY uniq_active_loan (user_id, buku_id, status),
    
    INDEX idx_jatuh_tempo (jatuh_tempo),
    INDEX idx_status (status),
    INDEX idx_user (user_id)
) ENGINE=InnoDB;

-- ============ TB_LOG_PINJAMAN (Audit trail) ============
CREATE TABLE tb_log_pinjaman (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pinjaman_id BIGINT UNSIGNED NOT NULL,
    aksi VARCHAR(50) NOT NULL,            -- created, approved, rejected, returned, overdue_notif
    aktor_id BIGINT UNSIGNED NULL,
    keterangan TEXT NULL,
    waktu TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pinjaman_id) REFERENCES tb_pinjaman(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============ TB_NOTIFIKASI (WA Log) ============
CREATE TABLE tb_notifikasi (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pinjaman_id BIGINT UNSIGNED NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    saluran ENUM('wa','email','system') DEFAULT 'wa',
    tipe ENUM('reminder_h1','reminder_h','overdue','approved','returned') NOT NULL,
    telepon VARCHAR(20) NOT NULL,
    pesan TEXT NOT NULL,
    status ENUM('pending','sent','failed') DEFAULT 'pending',
    respon TEXT NULL,                     -- response dari WA gateway
    sent_at TIMESTAMP NULL,
    FOREIGN KEY (pinjaman_id) REFERENCES tb_pinjaman(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES tb_user(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_sent_at (sent_at)
) ENGINE=InnoDB;