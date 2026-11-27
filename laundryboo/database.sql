-- LaundryBoo: database MySQL (Laragon). Import lewat phpMyAdmin/HeidiSQL.
CREATE DATABASE IF NOT EXISTS laundryboo_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE laundryboo_db;
DROP TABLE IF EXISTS pesanan;
DROP TABLE IF EXISTS layanan;
DROP TABLE IF EXISTS akun;

CREATE TABLE akun(
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL UNIQUE,
  telp VARCHAR(20) NOT NULL DEFAULT '',
  alamat VARCHAR(255) NOT NULL DEFAULT '',
  sandi VARCHAR(255) NOT NULL,
  peran ENUM('admin','pelanggan') NOT NULL DEFAULT 'pelanggan',
  status ENUM('Aktif','Nonaktif') NOT NULL DEFAULT 'Aktif',
  dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE layanan(
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL UNIQUE,
  harga INT NOT NULL
);
CREATE TABLE pesanan(
  id INT AUTO_INCREMENT PRIMARY KEY,
  akun_id INT NOT NULL,
  layanan_id INT NOT NULL,
  berat DECIMAL(5,1) NOT NULL,
  harga_kg INT NOT NULL,            -- snapshot harga saat dipesan
  total INT NOT NULL,
  telp VARCHAR(20) NOT NULL,
  alamat VARCHAR(255) NOT NULL,
  tgl_masuk DATE NOT NULL,
  tgl_selesai DATE NOT NULL,
  metode ENUM('Cash','QRIS') NOT NULL,
  sudah_bayar TINYINT(1) NOT NULL DEFAULT 0,
  status ENUM('Menunggu','Diproses','Selesai','Diambil') NOT NULL DEFAULT 'Menunggu',
  dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (akun_id) REFERENCES akun(id) ON DELETE CASCADE,
  FOREIGN KEY (layanan_id) REFERENCES layanan(id) ON DELETE RESTRICT
);

INSERT INTO layanan(nama,harga) VALUES ('Cuci kering',8000),('Cuci setrika',10000),('Setrika saja',6000);
-- Admin awal: nama "shinta", password "123456" (hash). Segera ganti setelah login pertama.
INSERT INTO akun(nama,sandi,peran) VALUES
('shinta','$6$LaundryBooSalt12$k9Hf4KiTtemEuEJOENIExUNEt6WXTIz9uuzRiAFnOc.CuRSoo9XRQ70Usfkuu.sIe3skt1xKxM2CLkRx/ENg0.','admin');
