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
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,           -- nomor WA (format 62xxx)
    password VARCHAR(255) NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_phone (phone)
) ENGINE=InnoDB;

-- ============ TB_CATEGORIES ============
CREATE TABLE tb_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============ TB_BOOKS ============
CREATE TABLE tb_books (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NULL,
    title VARCHAR(200) NOT NULL,
    author VARCHAR(150) NOT NULL,
    publisher VARCHAR(150) NULL,
    year YEAR NULL,
    isbn VARCHAR(20) UNIQUE NULL,
    cover VARCHAR(255) NULL,
    stock INT UNSIGNED DEFAULT 1,        -- total stok
    available INT UNSIGNED DEFAULT 1,    -- tersedia saat ini
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES tb_categories(id) ON DELETE SET NULL,
    INDEX idx_title (title),
    INDEX idx_available (available)
) ENGINE=InnoDB;

-- ============ TB_LOANS ============
CREATE TABLE tb_loans (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    loan_code VARCHAR(30) UNIQUE NOT NULL,     -- kode unik di QR
    user_id BIGINT UNSIGNED NOT NULL,
    book_id BIGINT UNSIGNED NOT NULL,
    approved_by BIGINT UNSIGNED NULL,           -- admin yang approve
    loan_date DATE NOT NULL,
    due_date DATE NOT NULL,                     -- ditentukan admin
    return_date DATE NULL,
    status ENUM('pending','approved','rejected','borrowed','returned','overdue','lost')
        DEFAULT 'pending',
    qr_code_path VARCHAR(255) NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES tb_user(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES tb_books(id) ON DELETE RESTRICT,
    FOREIGN KEY (approved_by) REFERENCES tb_admin(id) ON DELETE SET NULL,

    -- 🔒 KUNCI ANTI DOUBLE: user tidak boleh pinjam buku sama yang masih aktif
    UNIQUE KEY uniq_active_loan (user_id, book_id, status),
    
    INDEX idx_due_date (due_date),
    INDEX idx_status (status),
    INDEX idx_user (user_id)
) ENGINE=InnoDB;

-- ============ TB_LOAN_LOGS (Audit trail) ============
CREATE TABLE tb_loan_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    loan_id BIGINT UNSIGNED NOT NULL,
    action VARCHAR(50) NOT NULL,        -- created, approved, rejected, returned, overdue_notif
    actor_id BIGINT UNSIGNED NULL,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (loan_id) REFERENCES tb_loans(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============ TB_NOTIFICATIONS (WA Log) ============
CREATE TABLE tb_notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    loan_id BIGINT UNSIGNED NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    channel ENUM('wa','email','system') DEFAULT 'wa',
    type ENUM('reminder_h1','reminder_h','overdue','approved','returned') NOT NULL,
    phone VARCHAR(20) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('pending','sent','failed') DEFAULT 'pending',
    response TEXT NULL,                  -- response dari WA gateway
    sent_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (loan_id) REFERENCES tb_loans(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES tb_user(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_sent_at (sent_at)
) ENGINE=InnoDB;

-- ============ TB_ADMIN_ACTIVITY_LOGS ============
CREATE TABLE tb_admin_activity_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    action VARCHAR(100) NOT NULL,
    description TEXT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(255) NULL,
    url VARCHAR(500) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES tb_admin(id) ON DELETE SET NULL,
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB;