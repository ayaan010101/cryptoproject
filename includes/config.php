<?php
// Database configuration
$db_host = 'localhost';
$db_user = 'your_db_username'; // Change this to your database username
$db_pass = 'your_db_password'; // Change this to your database password
$db_name = 'crypto_trading';

// Create database connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set
$conn->set_charset("utf8mb4");

// Session configuration
session_start();

// Site configuration
$site_name = "CryptoTrade Platform";
$site_url = "https://yourdomain.com"; // Change this to your domain

// Security functions
function clean_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = $conn->real_escape_string($data);
    return $data;
}

function generate_token() {
    return bin2hex(random_bytes(32));
}

function verify_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Set CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = generate_token();
}
?>