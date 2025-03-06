<?php
require_once '../lib/db.php';

// Start session
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Initialize database connection
$db = new Database();

// Get all users
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['id'])) {
    $sql = "SELECT id, username, email, full_name, status, join_date FROM users WHERE role != 'admin'";
    $result = $db->query($sql);
    $users = $db->fetchAll($result);
    
    // Get wallet balances for each user
    foreach ($users as &$user) {
        $walletSql = "SELECT coin, balance FROM wallets WHERE user_id = ?";
        $walletResult = $db->query($walletSql, [$user['id']], 'i');
        $wallets = $db->fetchAll($walletResult);
        
        $user['wallets'] = [];
        foreach ($wallets as $wallet) {
            $user['wallets'][$wallet['coin']] = [
                'balance' => (float)$wallet['balance'],
                'value' => calculateValue($wallet['coin'], (float)$wallet['balance'])
            ];
        }
    }
    
    echo json_encode(['success' => true, 'users' => $users]);
    exit;
}

// Get a specific user
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $userId = (int)$_GET['id'];
    
    $sql = "SELECT id, username, email, full_name, status, join_date FROM users WHERE id = ?";
    $result = $db->query($sql, [$userId], 'i');
    $user = $db->fetchOne($result);
    
    if ($user) {
        // Get wallet balances
        $walletSql = "SELECT coin, balance FROM wallets WHERE user_id = ?";
        $walletResult = $db->query($walletSql, [$userId], 'i');
        $wallets = $db->fetchAll($walletResult);
        
        $user['wallets'] = [];
        foreach ($wallets as $wallet) {
            $user['wallets'][$wallet['coin']] = [
                'balance' => (float)$wallet['balance'],
                'value' => calculateValue($wallet['coin'], (float)$wallet['balance'])
            ];
        }
        
        echo json_encode(['success' => true, 'user' => $user]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
    exit;
}

// Create a new user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON data from request body
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || !isset($data['username']) || !isset($data['email']) || !isset($data['fullName']) || !isset($data['password'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    // Check if username already exists
    $checkSql = "SELECT id FROM users WHERE username = ?";
    $checkResult = $db->query($checkSql, [$data['username']], 's');
    if ($checkResult->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Username already exists']);
        exit;
    }
    
    // Hash password
    $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
    
    // Insert new user
    $sql = "INSERT INTO users (username, email, full_name, password_hash, role, status, join_date) VALUES (?, ?, ?, ?, 'user', 'active', NOW())";
    $result = $db->query($sql, [$data['username'], $data['email'], $data['fullName'], $passwordHash], 'ssss');
    
    if ($result) {
        $userId = $db->getLastInsertId();
        
        // Create empty wallets for the new user
        $coins = ['BTC', 'ETH', 'USDT'];
        foreach ($coins as $coin) {
            $walletSql = "INSERT INTO wallets (user_id, coin, balance) VALUES (?, ?, 0)";
            $db->query($walletSql, [$userId, $coin], 'is');
        }
        
        echo json_encode(['success' => true, 'message' => 'User created successfully', 'userId' => $userId]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create user']);
    }
    exit;
}

// Update a user
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['id'])) {
    $userId = (int)$_GET['id'];
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        exit;
    }
    
    // Build update query based on provided fields
    $updateFields = [];
    $params = [];
    $types = '';
    
    if (isset($data['username'])) {
        $updateFields[] = 'username = ?';
        $params[] = $data['username'];
        $types .= 's';
    }
    
    if (isset($data['email'])) {
        $updateFields[] = 'email = ?';
        $params[] = $data['email'];
        $types .= 's';
    }
    
    if (isset($data['fullName'])) {
        $updateFields[] = 'full_name = ?';
        $params[] = $data['fullName'];
        $types .= 's';
    }
    
    if (isset($data['status'])) {
        $updateFields[] = 'status = ?';
        $params[] = $data['status'];
        $types .= 's';
    }
    
    if (empty($updateFields)) {
        echo json_encode(['success' => false, 'message' => 'No fields to update']);
        exit;
    }
    
    // Add user ID to params
    $params[] = $userId;
    $types .= 'i';
    
    $sql = "UPDATE users SET " . implode(', ', $updateFields) . " WHERE id = ?";
    $result = $db->query($sql, $params, $types);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'User updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update user']);
    }
    exit;
}

// Delete a user
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id'])) {
    $userId = (int)$_GET['id'];
    
    // Start transaction
    $db->getConnection()->begin_transaction();
    
    try {
        // Delete user's wallets
        $walletSql = "DELETE FROM wallets WHERE user_id = ?";
        $db->query($walletSql, [$userId], 'i');
        
        // Delete user's transactions
        $txSql = "DELETE FROM transactions WHERE user_id = ?";
        $db->query($txSql, [$userId], 'i');
        
        // Delete user
        $userSql = "DELETE FROM users WHERE id = ?";
        $db->query($userSql, [$userId], 'i');
        
        // Commit transaction
        $db->getConnection()->commit();
        
        echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
    } catch (Exception $e) {
        // Rollback transaction on error
        $db->getConnection()->rollback();
        echo json_encode(['success' => false, 'message' => 'Failed to delete user: ' . $e->getMessage()]);
    }
    exit;
}

// Manage user funds
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id']) && isset($_GET['action']) && ($_GET['action'] === 'add' || $_GET['action'] === 'remove')) {
    $userId = (int)$_GET['id'];
    $action = $_GET['action'];
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || !isset($data['coin']) || !isset($data['amount']) || (float)$data['amount'] <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        exit;
    }
    
    $coin = $data['coin'];
    $amount = (float)$data['amount'];
    
    // Start transaction
    $db->getConnection()->begin_transaction();
    
    try {
        // Get current balance
        $balanceSql = "SELECT balance FROM wallets WHERE user_id = ? AND coin = ?";
        $balanceResult = $db->query($balanceSql, [$userId, $coin], 'is');
        $wallet = $db->fetchOne($balanceResult);
        
        if (!$wallet) {
            // Create wallet if it doesn't exist
            $createSql = "INSERT INTO wallets (user_id, coin, balance) VALUES (?, ?, 0)";
            $db->query($createSql, [$userId, $coin], 'is');
            $currentBalance = 0;
        } else {
            $currentBalance = (float)$wallet['balance'];
        }
        
        // Calculate new balance
        $newBalance = $action === 'add' ? $currentBalance + $amount : max(0, $currentBalance - $amount);
        
        // Update wallet balance
        $updateSql = "UPDATE wallets SET balance = ? WHERE user_id = ? AND coin = ?";
        $db->query($updateSql, [$newBalance, $userId, $coin], 'dis');
        
        // Record transaction
        $txType = $action === 'add' ? 'deposit' : 'withdrawal';
        $txSql = "INSERT INTO transactions (user_id, type, coin, amount, status, created_at) VALUES (?, ?, ?, ?, 'completed', NOW())";
        $db->query($txSql, [$userId, $txType, $coin, $amount], 'issd');
        
        // Commit transaction
        $db->getConnection()->commit();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Funds ' . ($action === 'add' ? 'added' : 'removed') . ' successfully',
            'newBalance' => $newBalance
        ]);
    } catch (Exception $e) {
        // Rollback transaction on error
        $db->getConnection()->rollback();
        echo json_encode(['success' => false, 'message' => 'Failed to manage funds: ' . $e->getMessage()]);
    }
    exit;
}

// Helper function to calculate value based on current prices
function calculateValue($coin, $balance) {
    $prices = [
        'BTC' => 42568.23,
        'ETH' => 2356.78,
        'USDT' => 1.0
    ];
    
    return $balance * ($prices[$coin] ?? 0);
}

// Default response for invalid requests
echo json_encode(['success' => false, 'message' => 'Invalid request']);
