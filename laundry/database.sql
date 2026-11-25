-- PERHATIAN: skema berubah dari versi sebelumnya, tabel lama dihapus saat import.
CREATE DATABASE IF NOT EXISTS laundry_db;
USE laundry_db;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS services;
DROP TABLE IF EXISTS users;
CREATE TABLE users(
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(20) DEFAULT '',
  address VARCHAR(255) DEFAULT '',
  role ENUM('admin','user') NOT NULL DEFAULT 'user',
  status ENUM('Aktif','Nonaktif') NOT NULL DEFAULT 'Aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE services(
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  price_per_kg INT NOT NULL,
  description VARCHAR(255)
);
CREATE TABLE orders(
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  service_id INT NOT NULL,
  weight DECIMAL(5,1) NOT NULL,
  price_per_kg INT NOT NULL,
  total INT NOT NULL,
  entry_date DATE NOT NULL,
  finish_date DATE NOT NULL,
  payment_method ENUM('Cash','QRIS') NOT NULL,
  paid TINYINT(1) NOT NULL DEFAULT 0,
  notes VARCHAR(255),
  status ENUM('Menunggu','Diproses','Selesai','Diambil') NOT NULL DEFAULT 'Menunggu',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY(service_id) REFERENCES services(id) ON DELETE RESTRICT
);
-- Admin default: admin@laundry.test / password  (segera ganti setelah login pertama)
INSERT INTO users(name,email,password,role) VALUES
('Admin','admin@laundry.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin');
INSERT INTO services(name,price_per_kg,description) VALUES
('Cuci kering',8000,'Cuci + kering, tanpa setrika'),
('Cuci setrika',10000,'Cuci, kering, setrika, wangi'),
('Setrika saja',6000,'Setrika rapi');
