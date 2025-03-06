-- CryptoTrade Platform Database Schema

-- Drop existing tables if they exist
DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS wallets;
DROP TABLE IF EXISTS users;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    security_key VARCHAR(64) NOT NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    join_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Wallets table
CREATE TABLE wallets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    coin VARCHAR(10) NOT NULL,
    balance DECIMAL(18, 8) NOT NULL DEFAULT 0,
    address VARCHAR(100) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY (user_id, coin)
);

-- Transactions table
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('deposit', 'withdrawal', 'payment') NOT NULL,
    coin VARCHAR(10) NOT NULL,
    amount DECIMAL(18, 8) NOT NULL,
    status ENUM('pending', 'completed', 'rejected') NOT NULL DEFAULT 'pending',
    date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    tx_hash VARCHAR(100),
    address VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Settings table
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin user
INSERT INTO users (username, email, password, full_name, security_key, role) VALUES
('admin', 'admin@cryptoplatform.com', '$2y$10$Hl0YKY.Vvs9TmDkMwcVkLOQEDQjgVKhI5xOu1L0YWWjbFnhWO.Ete', 'Administrator', 'admin_security_key_do_not_share', 'admin');

-- Insert default settings
INSERT INTO settings (setting_key, setting_value) VALUES
('withdrawal_fee_btc', '0.0005'),
('withdrawal_fee_eth', '0.005'),
('withdrawal_fee_usdt', '1.0'),
('min_withdrawal_btc', '0.001'),
('min_withdrawal_eth', '0.01'),
('min_withdrawal_usdt', '10'),
('max_withdrawal_btc', '1'),
('max_withdrawal_eth', '10'),
('max_withdrawal_usdt', '10000'),
('security_key_recovery_fee', '300');

-- Create wallets for admin
INSERT INTO wallets (user_id, coin, balance, address) VALUES
(1, 'BTC', 1.0, 'bc1admin_btc_wallet_address'),
(1, 'ETH', 10.0, '0xadmin_eth_wallet_address'),
(1, 'USDT', 10000.0, '0xadmin_usdt_wallet_address');
