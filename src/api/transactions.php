<?php
require_once '../lib/db.php';

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Initialize database connection
$db = new Database();

// Get all transactions (admin only)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['id']) && $_SESSION['role'] === 'admin') {
    $sql = "SELECT t.id, t.user_id, u.username, t.type, t.coin, t.amount, t.status, t.created_at as date 
           FROM transactions t 
           JOIN users u ON t.user_id = u.id 
           ORDER BY t.created_at DESC";
    $result = $db->query($sql);
    $transactions = $db->fetchAll($result);
    
    echo json_encode(['success' => true, 'transactions' => $transactions]);
    exit;
}

// Get user's transactions
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SESSION['role'] === 'user') {
    $userId = $_SESSION['user_id'];
    
    $sql = "SELECT id, type, coin, amount, status, created_at as date 
           FROM transactions 
           WHERE user_id = ? 
           ORDER BY created_at DESC";
    $result = $db->query($sql, [$userId], 'i');
    $transactions = $db->fetchAll($result);
    
    echo json_encode(['success' => true, 'transactions' => $transactions]);
    exit;
}

// Get a specific transaction
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $txId = (int)$_GET['id'];
    $userId = $_SESSION['user_id'];
    
    // Admin can view any transaction, users can only view their own
    $sql = $_SESSION['role'] === 'admin' 
        ? "SELECT t.*, u.username FROM transactions t JOIN users u ON t.user_id = u.id WHERE t.id = ?" 
        : "SELECT * FROM transactions WHERE id = ? AND user_id = ?";
    
    $params = $_SESSION['role'] === 'admin' ? [$txId] : [$txId, $userId];
    $types = $_SESSION['role'] === 'admin' ? 'i' : 'ii';
    
    $result = $db->query($sql, $params, $types);
    $transaction = $db->fetchOne($result);
    
    if ($transaction) {
        echo json_encode(['success' => true, 'transaction' => $transaction]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Transaction not found']);
    }
    exit;
}

// Create a new transaction (deposit/withdrawal)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['role'] === 'user') {
    $userId = $_SESSION['user_id'];
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || !isset($data['type']) || !isset($data['coin']) || !isset($data['amount']) || (float)$data['amount'] <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        exit;
    }
    
    $type = $data['type'];
    $coin = $data['coin'];
    $amount = (float)$data['amount'];
    
    // Validate transaction type
    if ($type !== 'deposit' && $type !== 'withdrawal') {
        echo json_encode(['success' => false, 'message' => 'Invalid transaction type']);
        exit;
    }
    
    // Start transaction
    $db->getConnection()->begin_transaction();
    
    try {
        // Get current balance
        $balanceSql = "SELECT balance FROM wallets WHERE user_id = ? AND coin = ?";
        $balanceResult = $db->query($balanceSql, [$userId, $coin], 'is');
        $wallet = $db->fetchOne($balanceResult);
        
        if (!$wallet && $type === 'withdrawal') {
            throw new Exception('Insufficient balance');
        }
        
        $currentBalance = $wallet ? (float)$wallet['balance'] : 0;
        
        // For withdrawals, check if user has enough balance
        if ($type === 'withdrawal' && $currentBalance < $amount) {
            throw new Exception('Insufficient balance');
        }
        
        // Calculate new balance
        $newBalance = $type === 'deposit' ? $currentBalance + $amount : $currentBalance - $amount;
        
        // For deposits, create wallet if it doesn't exist
        if (!$wallet && $type === 'deposit') {
            $createSql = "INSERT INTO wallets (user_id, coin, balance) VALUES (?, ?, ?)";
            $db->query($createSql, [$userId, $coin, $amount], 'isd');
        } else {
            // Update wallet balance
            $updateSql = "UPDATE wallets SET balance = ? WHERE user_id = ? AND coin = ?";
            $db->query($updateSql, [$newBalance, $userId, $coin], 'dis');
        }
        
        // Create transaction record
        // For deposits, mark as completed immediately
        // For withdrawals, mark as pending (admin needs to approve)
        $status = $type === 'deposit' ? 'completed' : 'pending';
        $txSql = "INSERT INTO transactions (user_id, type, coin, amount, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
        $db->query($txSql, [$userId, $type, $coin, $amount, $status], 'issds');
        
        $txId = $db->getLastInsertId();
        
        // Commit transaction
        $db->getConnection()->commit();
        
        echo json_encode([
            'success' => true, 
            'message' => ucfirst($type) . ' processed successfully',
            'transactionId' => $txId,
            'status' => $status,
            'newBalance' => $newBalance
        ]);
    } catch (Exception $e) {
        // Rollback transaction on error
        $db->getConnection()->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

// Update transaction status (admin only)
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['id']) && $_SESSION['role'] === 'admin') {
    $txId = (int)$_GET['id'];
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || !isset($data['status']) || ($data['status'] !== 'completed' && $data['status'] !== 'rejected')) {
        echo json_encode(['success' => false, 'message' => 'Invalid status']);
        exit;
    }
    
    $status = $data['status'];
    
    // Start transaction
    $db->getConnection()->begin_transaction();
    
    try {
        // Get transaction details
        $txSql = "SELECT user_id, type, coin, amount, status FROM transactions WHERE id = ?";
        $txResult = $db->query($txSql, [$txId], 'i');
        $transaction = $db->fetchOne($txResult);
        
        if (!$transaction) {
            throw new Exception('Transaction not found');
        }
        
        // Only pending transactions can be updated
        if ($transaction['status'] !== 'pending') {
            throw new Exception('Only pending transactions can be updated');
        }
        
        // Update transaction status
        $updateSql = "UPDATE transactions SET status = ? WHERE id = ?";
        $db->query($updateSql, [$status, $txId], 'si');
        
        // If rejecting a withdrawal, refund the amount to the user's wallet
        if ($status === 'rejected' && $transaction['type'] === 'withdrawal') {
            $userId = $transaction['user_id'];
            $coin = $transaction['coin'];
            $amount = (float)$transaction['amount'];
            
            // Get current balance
            $balanceSql = "SELECT balance FROM wallets WHERE user_id = ? AND coin = ?";
            $balanceResult = $db->query($balanceSql, [$userId, $coin], 'is');
            $wallet = $db->fetchOne($balanceResult);
            
            if ($wallet) {
                $currentBalance = (float)$wallet['balance'];
                $newBalance = $currentBalance + $amount;
                
                // Update wallet balance
                $updateWalletSql = "UPDATE wallets SET balance = ? WHERE user_id = ? AND coin = ?";
                $db->query($updateWalletSql, [$newBalance, $userId, $coin], 'dis');
            }
        }
        
        // Commit transaction
        $db->getConnection()->commit();
        
        echo json_encode(['success' => true, 'message' => 'Transaction status updated successfully']);
    } catch (Exception $e) {
        // Rollback transaction on error
        $db->getConnection()->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

// Default response for invalid requests
echo json_encode(['success' => false, 'message' => 'Invalid request']);
