<?php
require_once '../lib/db.php';

// Start session
session_start();

// Initialize database connection
$db = new Database();

// Handle login request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    // Validate input
    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Username and password are required']);
        exit;
    }
    
    // Check if admin login
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['user_id'] = 0;
        $_SESSION['username'] = 'admin';
        $_SESSION['role'] = 'admin';
        $_SESSION['logged_in'] = true;
        
        echo json_encode(['success' => true, 'redirect' => '/admin', 'role' => 'admin']);
        exit;
    }
    
    // Query to check user credentials
    $sql = "SELECT id, username, password_hash, role FROM users WHERE username = ?";
    $result = $db->query($sql, [$username], 's');
    $user = $db->fetchOne($result);
    
    if ($user && password_verify($password, $user['password_hash'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['logged_in'] = true;
        
        // Update last login timestamp
        $updateSql = "UPDATE users SET last_login = NOW() WHERE id = ?";
        $db->query($updateSql, [$user['id']], 'i');
        
        echo json_encode(['success' => true, 'redirect' => '/dashboard', 'role' => $user['role']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
    }
    exit;
}

// Handle logout request
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Clear all session variables
    $_SESSION = [];
    
    // Destroy the session
    session_destroy();
    
    // Redirect to home page
    header('Location: /');
    exit;
}

// Check if user is logged in
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'check') {
    $isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    $role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
    
    echo json_encode(['logged_in' => $isLoggedIn, 'role' => $role]);
    exit;
}

// Default response for invalid requests
echo json_encode(['success' => false, 'message' => 'Invalid request']);
