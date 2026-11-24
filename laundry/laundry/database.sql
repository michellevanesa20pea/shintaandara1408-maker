CREATE DATABASE IF NOT EXISTS laundry_db;
USE laundry_db;
CREATE TABLE users(
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user',
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
  total INT NOT NULL,
  pickup_date DATE NOT NULL,
  notes VARCHAR(255),
  status ENUM('Menunggu','Diproses','Selesai','Diambil') DEFAULT 'Menunggu',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY(service_id) REFERENCES services(id) ON DELETE RESTRICT
);
-- Admin default: admin@laundry.test / password
INSERT INTO users(name,email,password,role) VALUES
('Admin','admin@laundry.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin');
INSERT INTO services(name,price_per_kg,description) VALUES
('Cuci Kering',6000,'Cuci + kering, tanpa setrika'),
('Cuci Setrika',8000,'Cuci, kering, setrika, wangi'),
('Setrika Saja',4000,'Setrika rapi');
