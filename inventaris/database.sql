-- Jalankan file ini di phpMyAdmin (tab Import / SQL)
CREATE DATABASE IF NOT EXISTS inventaris_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventaris_db;

DROP TABLE IF EXISTS produk;
DROP TABLE IF EXISTS kategori;
DROP TABLE IF EXISTS supplier;

CREATE TABLE kategori (
    id_kategori   INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE supplier (
    id_supplier   INT AUTO_INCREMENT PRIMARY KEY,
    nama_supplier VARCHAR(100) NOT NULL,
    telepon       VARCHAR(20)  NOT NULL
) ENGINE=InnoDB;

CREATE TABLE produk (
    id_produk    INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk  VARCHAR(150) NOT NULL,
    harga        DECIMAL(12,2) NOT NULL,
    stok         INT NOT NULL DEFAULT 0,
    id_kategori  INT NOT NULL,
    id_supplier  INT NOT NULL,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_produk_kategori FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori),
    CONSTRAINT fk_produk_supplier FOREIGN KEY (id_supplier) REFERENCES supplier(id_supplier)
) ENGINE=InnoDB;

-- Data seed (minimal 5 per tabel)
INSERT INTO kategori (nama_kategori) VALUES
('Elektronik'),('Alat Tulis'),('Makanan'),('Minuman'),('Perabot');

INSERT INTO supplier (nama_supplier, telepon) VALUES
('PT Maju Jaya','061-1111111'),
('CV Sinar Terang','061-2222222'),
('UD Sumber Rejeki','061-3333333'),
('PT Global Makmur','061-4444444'),
('CV Berkah Abadi','061-5555555');

INSERT INTO produk (nama_produk, harga, stok, id_kategori, id_supplier) VALUES
('Mouse Wireless',      85000, 40, 1, 1),
('Pulpen Gel Hitam',     3500, 200, 2, 2),
('Biskuit Cokelat',      9000, 120, 3, 3),
('Air Mineral 600ml',    3000, 300, 4, 4),
('Kursi Kantor',       450000, 15, 5, 5),
('Keyboard Mekanik',   350000, 25, 1, 1);
