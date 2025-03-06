<?php
require_once 'config.php';

// User authentication functions
function register_user($username, $email, $password, $full_name, $phone) {
    global $conn;
    
    // Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return ["success" => false, "message" => "Username already exists"];
    }
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return ["success" => false, "message" => "Email already exists"];
    }
    
    // Generate security key
    $security_key = bin2hex(random_bytes(16));
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, phone, security_key, status, role, join_date) VALUES (?, ?, ?, ?, ?, ?, 'active', 'user', NOW())");
    $stmt->bind_param("ssssss", $username, $email, $hashed_password, $full_name, $phone, $security_key);
    
    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;
        
        // Create wallets for user
        create_wallet($user_id, 'BTC', 0);
        create_wallet($user_id, 'ETH', 0);
        create_wallet($user_id, 'USDT', 0);
        
        return ["success" => true, "message" => "Registration successful", "security_key" => $security_key];
    } else {
        return ["success" => false, "message" => "Registration failed: " . $stmt->error];
    }
}

function login_user($username, $password) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT id, username, password, full_name, email, role, status FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if ($user['status'] !== 'active') {
            return ["success" => false, "message" => "Account is inactive"];
        }
        
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            
            return ["success" => true, "message" => "Login successful", "role" => $user['role']];
        } else {
            return ["success" => false, "message" => "Invalid password"];
        }
    } else {
        return ["success" => false, "message" => "User not found"];
    }
}

function recover_security_key($email) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        return ["success" => true, "message" => "Verification email sent", "user_id" => $user['id']];
    } else {
        return ["success" => false, "message" => "Email not found"];
    }
}

function update_security_key($user_id, $payment_amount = 300) {
    global $conn;
    
    // Generate new security key
    $new_security_key = bin2hex(random_bytes(16));
    
    // Update user's security key
    $stmt = $conn->prepare("UPDATE users SET security_key = ? WHERE id = ?");
    $stmt->bind_param("si", $new_security_key, $user_id);
    
    if ($stmt->execute()) {
        // Record payment transaction
        $stmt = $conn->prepare("INSERT INTO transactions (user_id, type, coin, amount, status, date) VALUES (?, 'payment', 'USDT', ?, 'completed', NOW())");
        $stmt->bind_param("id", $user_id, $payment_amount);
        $stmt->execute();
        
        return ["success" => true, "message" => "Security key updated successfully", "security_key" => $new_security_key];
    } else {
        return ["success" => false, "message" => "Failed to update security key"];
    }
}

function logout_user() {
    // Unset all session variables
    $_SESSION = [];
    
    // Destroy the session
    session_destroy();
    
    return ["success" => true, "message" => "Logout successful"];
}

// Wallet functions
function create_wallet($user_id, $coin, $initial_balance = 0) {
    global $conn;
    
    // Generate wallet address
    $wallet_address = strtolower($coin) . '-' . bin2hex(random_bytes(16));
    
    $stmt = $conn->prepare("INSERT INTO wallets (user_id, coin, balance, address) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isds", $user_id, $coin, $initial_balance, $wallet_address);
    
    return $stmt->execute();
}

function get_user_wallets($user_id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT coin, balance, address FROM wallets WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $wallets = [];
    while ($row = $result->fetch_assoc()) {
        // Get current price
        $price = get_coin_price($row['coin']);
        $value = $row['balance'] * $price;
        
        $wallets[$row['coin']] = [
            'balance' => $row['balance'],
            'value' => $value,
            'address' => $row['address']
        ];
    }
    
    return $wallets;
}

function update_wallet_balance($user_id, $coin, $amount, $type) {
    global $conn;
    
    // Get current balance
    $stmt = $conn->prepare("SELECT balance FROM wallets WHERE user_id = ? AND coin = ?");
    $stmt->bind_param("is", $user_id, $coin);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return ["success" => false, "message" => "Wallet not found"];
    }
    
    $wallet = $result->fetch_assoc();
    $current_balance = $wallet['balance'];
    
    // Calculate new balance
    $new_balance = ($type === 'deposit') ? $current_balance + $amount : $current_balance - $amount;
    
    // Check if withdrawal is possible
    if ($type === 'withdrawal' && $new_balance < 0) {
        return ["success" => false, "message" => "Insufficient balance"];
    }
    
    // Update balance
    $stmt = $conn->prepare("UPDATE wallets SET balance = ? WHERE user_id = ? AND coin = ?");
    $stmt->bind_param("dis", $new_balance, $user_id, $coin);
    
    if ($stmt->execute()) {
        // Record transaction
        $stmt = $conn->prepare("INSERT INTO transactions (user_id, type, coin, amount, status, date) VALUES (?, ?, ?, ?, 'completed', NOW())");
        $stmt->bind_param("issd", $user_id, $type, $coin, $amount);
        $stmt->execute();
        
        return ["success" => true, "message" => ucfirst($type) . " successful", "new_balance" => $new_balance];
    } else {
        return ["success" => false, "message" => "Transaction failed"];
    }
}

// Transaction functions
function get_user_transactions($user_id, $limit = 10) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT id, type, coin, amount, status, date FROM transactions WHERE user_id = ? ORDER BY date DESC LIMIT ?");
    $stmt->bind_param("ii", $user_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $transactions = [];
    while ($row = $result->fetch_assoc()) {
        $transactions[] = $row;
    }
    
    return $transactions;
}

function get_all_transactions($limit = 20) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT t.id, t.user_id, u.username, t.type, t.coin, t.amount, t.status, t.date 
                           FROM transactions t 
                           JOIN users u ON t.user_id = u.id 
                           ORDER BY t.date DESC LIMIT ?");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $transactions = [];
    while ($row = $result->fetch_assoc()) {
        $transactions[] = $row;
    }
    
    return $transactions;
}

function update_transaction_status($transaction_id, $status) {
    global $conn;
    
    $stmt = $conn->prepare("UPDATE transactions SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $transaction_id);
    
    return $stmt->execute();
}

// Admin functions
function get_all_users() {
    global $conn;
    
    $stmt = $conn->prepare("SELECT id, username, email, full_name, status, join_date, role FROM users ORDER BY join_date DESC");
    $stmt->execute();
    $result = $stmt->get_result();
    
    $users = [];
    while ($row = $result->fetch_assoc()) {
        // Get user wallets
        $wallets = get_user_wallets($row['id']);
        $row['wallets'] = $wallets;
        $users[] = $row;
    }
    
    return $users;
}

function update_user_status($user_id, $status) {
    global $conn;
    
    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $user_id);
    
    return $stmt->execute();
}

function update_user_details($user_id, $email, $full_name, $username, $status) {
    global $conn;
    
    $stmt = $conn->prepare("UPDATE users SET email = ?, full_name = ?, username = ?, status = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $email, $full_name, $username, $status, $user_id);
    
    return $stmt->execute();
}

function delete_user($user_id) {
    global $conn;
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Delete user's wallets
        $stmt = $conn->prepare("DELETE FROM wallets WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        
        // Delete user's transactions
        $stmt = $conn->prepare("DELETE FROM transactions WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        
        // Delete user
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        
        // Commit transaction
        $conn->commit();
        return true;
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        return false;
    }
}

// Market data functions
function get_coin_price($coin) {
    // In a real application, this would call an external API
    // For demo purposes, we'll use static prices
    $prices = [
        'BTC' => 95432.78,
        'ETH' => 2356.78,
        'USDT' => 1.0,
        'BNB' => 567.89,
        'ADA' => 0.45,
        'SOL' => 123.45
    ];
    
    return isset($prices[$coin]) ? $prices[$coin] : 0;
}

function get_all_coins() {
    $coins = [
        ['id' => 'bitcoin', 'name' => 'Bitcoin', 'symbol' => 'BTC', 'price' => get_coin_price('BTC'), 'change24h' => 3.45],
        ['id' => 'ethereum', 'name' => 'Ethereum', 'symbol' => 'ETH', 'price' => get_coin_price('ETH'), 'change24h' => -1.45],
        ['id' => 'tether', 'name' => 'Tether', 'symbol' => 'USDT', 'price' => get_coin_price('USDT'), 'change24h' => 0.01],
        ['id' => 'binancecoin', 'name' => 'Binance Coin', 'symbol' => 'BNB', 'price' => get_coin_price('BNB'), 'change24h' => 3.21],
        ['id' => 'cardano', 'name' => 'Cardano', 'symbol' => 'ADA', 'price' => get_coin_price('ADA'), 'change24h' => -2.67],
        ['id' => 'solana', 'name' => 'Solana', 'symbol' => 'SOL', 'price' => get_coin_price('SOL'), 'change24h' => 5.67]
    ];
    
    return $coins;
}

// Helper functions
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function get_total_investment() {
    global $conn;
    
    $stmt = $conn->prepare("SELECT SUM(w.balance * p.price) as total 
                           FROM wallets w 
                           JOIN (SELECT 'BTC' as coin, ? as price UNION 
                                 SELECT 'ETH' as coin, ? as price UNION 
                                 SELECT 'USDT' as coin, ? as price) p 
                           ON w.coin = p.coin");
    
    $btc_price = get_coin_price('BTC');
    $eth_price = get_coin_price('ETH');
    $usdt_price = get_coin_price('USDT');
    
    $stmt->bind_param("ddd", $btc_price, $eth_price, $usdt_price);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['total'] ?? 0;
}

function get_active_users_count() {
    global $conn;
    
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE status = 'active'");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['count'] ?? 0;
}

function get_pending_transactions_count() {
    global $conn;
    
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM transactions WHERE status = 'pending'");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['count'] ?? 0;
}

function get_trading_volume($days = 30) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT SUM(t.amount * p.price) as volume 
                           FROM transactions t 
                           JOIN (SELECT 'BTC' as coin, ? as price UNION 
                                 SELECT 'ETH' as coin, ? as price UNION 
                                 SELECT 'USDT' as coin, ? as price) p 
                           ON t.coin = p.coin 
                           WHERE t.date >= DATE_SUB(NOW(), INTERVAL ? DAY)");
    
    $btc_price = get_coin_price('BTC');
    $eth_price = get_coin_price('ETH');
    $usdt_price = get_coin_price('USDT');
    
    $stmt->bind_param("dddi", $btc_price, $eth_price, $usdt_price, $days);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['volume'] ?? 0;
}
?>