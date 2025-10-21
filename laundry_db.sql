-- Laundry Database Schema

CREATE DATABASE IF NOT EXISTS laundry_db;
USE laundry_db;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    contact VARCHAR(50),
    address TEXT,
    role ENUM('user', 'admin') DEFAULT 'user',
    photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Services table
CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    capacity VARCHAR(50), -- e.g., "8 kg"
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Machines table
CREATE TABLE machines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type ENUM('washer', 'dryer') NOT NULL,
    status ENUM('available', 'in_use', 'maintenance') DEFAULT 'available',
    location VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Transactions table
CREATE TABLE transactions (
    id VARCHAR(20) PRIMARY KEY, -- e.g., T12345
    user_id INT NOT NULL,
    service_id INT NOT NULL,
    machine_id INT,
    status ENUM('pending', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    total DECIMAL(10,2) NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (service_id) REFERENCES services(id),
    FOREIGN KEY (machine_id) REFERENCES machines(id)
);

-- Notifications table
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'warning', 'success', 'error') DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Admin settings table
CREATE TABLE admin_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin user
INSERT INTO users (name, email, password, role, contact, address) VALUES
('Administrator', 'admin@laundry.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'N/A', 'N/A');

-- Insert default services
INSERT INTO services (name, description, price, capacity) VALUES
('Wash', 'Washing service only', 60.00, '8 kg'),
('Dry', 'Drying service only', 60.00, '8 kg'),
('Wash & Dry', 'Complete wash and dry service', 110.00, '8 kg');

-- Insert default machines
INSERT INTO machines (name, type, status, location) VALUES
('Washer 1', 'washer', 'available', 'Main Area'),
('Washer 2', 'washer', 'available', 'Main Area'),
('Dryer 1', 'dryer', 'available', 'Main Area'),
('Dryer 2', 'dryer', 'available', 'Main Area');

-- Insert default admin settings
INSERT INTO admin_settings (setting_key, setting_value) VALUES
('site_title', 'Thia & Nicole Laundry'),
('contact_email', 'contact@laundry.com'),
('business_hours', 'Mon-Sun: 8AM-8PM');
