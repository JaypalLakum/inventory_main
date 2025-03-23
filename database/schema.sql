-- Create database
CREATE DATABASE IF NOT EXISTS vehicle_inventory;
USE vehicle_inventory;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Manufacturers table
CREATE TABLE IF NOT EXISTS manufacturers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    is_deleted TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Models table
CREATE TABLE IF NOT EXISTS models (
    id INT AUTO_INCREMENT PRIMARY KEY,
    manufacturer_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    color VARCHAR(50) NOT NULL,
    manufacturing_year YEAR NOT NULL,
    registration_number VARCHAR(20) NOT NULL UNIQUE,
    note TEXT,
    image1 VARCHAR(255),
    image2 VARCHAR(255),
    is_sold TINYINT(1) DEFAULT 0,
    is_deleted TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (manufacturer_id) REFERENCES manufacturers(id)
) ENGINE=InnoDB;

-- Insert default user
INSERT INTO users (username, password) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password: password
