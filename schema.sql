-- Abigail Beauty Bar database schema
-- Import this in phpMyAdmin, or run: mysql -u root -p < schema.sql

CREATE DATABASE IF NOT EXISTS abigail_beauty_bar
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE abigail_beauty_bar;

CREATE TABLE IF NOT EXISTS services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category VARCHAR(60) NOT NULL,
  name VARCHAR(120) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  duration_minutes INT NOT NULL DEFAULT 60
);

CREATE TABLE IF NOT EXISTS bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  email VARCHAR(120) NULL,
  service_id INT NULL,
  service_name VARCHAR(120) NOT NULL,
  appointment_date DATE NOT NULL,
  appointment_time TIME NOT NULL,
  notes TEXT NULL,
  deposit_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
  deposit_paid TINYINT(1) NOT NULL DEFAULT 0,
  status ENUM('pending','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Starter menu, taken from the business's service categories.
-- Edit names/prices/durations to match Abigail's real price list.
INSERT INTO services (category, name, price, duration_minutes) VALUES
('Hair Installation', 'Sew-in weave install', 120.00, 180),
('Hair Installation', 'Lace frontal install', 160.00, 210),
('Hair Installation', 'Closure install', 100.00, 150),
('Makeup', 'Everyday makeup', 60.00, 60),
('Makeup', 'Full glam makeup', 90.00, 75),
('Bridal', 'Bridal hair styling', 150.00, 120),
('Bridal', 'Bridal trial (hair + makeup)', 180.00, 150),
('Nails', 'Classic manicure', 35.00, 45),
('Nails', 'Gel manicure', 45.00, 60),
('Nails', 'Full set acrylic', 65.00, 90);