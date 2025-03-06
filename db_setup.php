<?php
// Database setup script
// Run this script once to create the database and tables

// Database configuration
$db_host = 'localhost';
$db_user = 'admin';
$db_pass = 'admin123';

// Connect to MySQL server without selecting a database
$conn = new mysqli($db_host, $db_user, $db_pass);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS crypto_trading CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db("crypto_trading");

// Read and execute SQL file
$sql_file = file_get_contents('sql/database.sql');

// Split SQL file into individual statements
$statements = explode(';', $sql_file);

// Execute each statement
$success = true;
foreach ($statements as $statement) {
    $statement = trim($statement);
    if (!empty($statement)) {
        if ($conn->query($statement) !== TRUE) {
            echo "Error executing statement: " . $conn->error . "<br>";
            echo "Statement: " . $statement . "<br><br>";
            $success = false;
        }
    }
}

if ($success) {
    echo "Database setup completed successfully!<br>";
    echo "<p>The admin user has been created with the following credentials:</p>";
    echo "<ul>";
    echo "<li>Username: admin</li>";
    echo "<li>Password: admin123</li>";
    echo "</ul>";
    echo "<p>Please delete this file after setup for security reasons.</p>";
} else {
    echo "Database setup completed with errors. Please check the error messages above.";
}

// Close connection
$conn->close();
?>
