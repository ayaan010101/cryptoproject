-- Create database
CREATE DATABASE IF NOT EXISTS crypto_trading CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE crypto_trading;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    join_date DATETIME NOT NULL,
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Wallets table
CREATE TABLE IF NOT EXISTS wallets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    coin VARCHAR(10) NOT NULL,
    balance DECIMAL(18, 8) NOT NULL DEFAULT 0,
    address VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_coin (user_id, coin),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_coin (coin)
) ENGINE=InnoDB;

-- Transactions table
CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('deposit', 'withdrawal', 'transfer') NOT NULL,
    coin VARCHAR(10) NOT NULL,
    amount DECIMAL(18, 8) NOT NULL,
    fee DECIMAL(18, 8) DEFAULT 0,
    status ENUM('pending', 'completed', 'rejected') NOT NULL,
    tx_hash VARCHAR(100),
    created_at DATETIME NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_type (type),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB;

-- Security keys table
CREATE TABLE IF NOT EXISTS security_keys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    key_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB;

-- Create admin user
INSERT INTO users (username, email, full_name, password_hash, role, status, join_date)
VALUES ('admin', 'admin@example.com', 'System Administrator', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', NOW());

-- Create sample users
INSERT INTO users (username, email, full_name, password_hash, role, status, join_date)
VALUES 
('john_doe', 'john@example.com', 'John Doe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', '2023-01-15 00:00:00'),
('jane_smith', 'jane@example.com', 'Jane Smith', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', '2023-02-20 00:00:00'),
('mike_wilson', 'mike@example.com', 'Mike Wilson', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'inactive', '2023-03-10 00:00:00'),
('sarah_johnson', 'sarah@example.com', 'Sarah Johnson', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', '2023-04-05 00:00:00'),
('alex_brown', 'alex@example.com', 'Alex Brown', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', '2023-05-12 00:00:00');

-- Create sample wallets
INSERT INTO wallets (user_id, coin, balance, address)
VALUES
(1, 'BTC', 0.0345, 'bc1q9h5yx3mvy8zj053y8zle7zn5p28mqwzx9lqnf3'),
(1, 'ETH', 1.245, '0x742d35Cc6634C0532925a3b844Bc454e4438f44e'),
(1, 'USDT', 5000.0, '0x742d35Cc6634C0532925a3b844Bc454e4438f44e'),
(2, 'BTC', 0.125, 'bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh'),
(2, 'ETH', 3.5, '0x71C7656EC7ab88b098defB751B7401B5f6d8976F'),
(2, 'USDT', 12500.0, '0x71C7656EC7ab88b098defB751B7401B5f6d8976F'),
(3, 'ETH', 0.75, '0xC02aaA39b223FE8D0A0e5C4F27eAD9083C756Cc2'),
(3, 'USDT', 2000.0, '0xC02aaA39b223FE8D0A0e5C4F27eAD9083C756Cc2'),
(4, 'BTC', 0.22, 'bc1qm34lsc65zpw79lxes69zkqmk6ee3ewf0j77s3h'),
(4, 'ETH', 5.0, '0x4B20993Bc481177ec7E8f571ceCaE8A9e22C02db'),
(4, 'USDT', 8000.0, '0x4B20993Bc481177ec7E8f571ceCaE8A9e22C02db'),
(5, 'BTC', 0.05, 'bc1qdkwfplhcgsjxfy6j5x9kcgjkqmzevvzp9zzn2g'),
(5, 'ETH', 1.0, '0xAb5801a7D398351b8bE11C439e05C5B3259aeC9B'),
(5, 'USDT', 3000.0, '0xAb5801a7D398351b8bE11C439e05C5B3259aeC9B');

-- Create sample transactions
INSERT INTO transactions (user_id, type, coin, amount, status, created_at)
VALUES
(1, 'deposit', 'BTC', 0.0145, 'completed', '2023-06-15 14:23:45'),
(1, 'withdrawal', 'USDT', 500, 'completed', '2023-06-10 09:15:22'),
(2, 'deposit', 'ETH', 0.5, 'completed', '2023-06-05 18:45:30'),
(3, 'withdrawal', 'BTC', 0.002, 'pending', '2023-06-01 11:32:15'),
(4, 'deposit', 'USDT', 1000, 'completed', '2023-05-28 16:20:45'),
(5, 'withdrawal', 'ETH', 0.25, 'pending', '2023-05-25 10:12:33'),
(2, 'deposit', 'BTC', 0.075, 'completed', '2023-05-20 08:45:12');
