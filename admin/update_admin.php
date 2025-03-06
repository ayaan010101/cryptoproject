<?php
require_once '../includes/admin_check.php';

// Process form submission
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !verify_token($_POST['csrf_token'])) {
        $error = 'Invalid form submission. Please try again.';
    } else {
        // Get form data
        $email = clean_input($_POST['admin_email']);
        $full_name = clean_input($_POST['admin_full_name']);
        $current_password = $_POST['admin_current_password']; // Don't clean password
        $new_password = $_POST['admin_new_password']; // Don't clean password
        $confirm_password = $_POST['admin_confirm_password']; // Don't clean password
        
        // Validate current password
        $conn = $GLOBALS['conn'];
        $user_id = $_SESSION['user_id'];
        
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if (!password_verify($current_password, $user['password'])) {
            $error = 'Current password is incorrect.';
        } else {
            // Check if email is already used by another user
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1");
            $stmt->bind_param("si", $email, $user_id);
            $stmt->execute();
            $email_result = $stmt->get_result();
            
            if ($email_result->num_rows > 0) {
                $error = 'Email is already used by another user.';
            } else {
                // Start transaction
                $conn->begin_transaction();
                
                try {
                    // Update email and full name
                    $stmt = $conn->prepare("UPDATE users SET email = ?, full_name = ? WHERE id = ?");
                    $stmt->bind_param("ssi", $email, $full_name, $user_id);
                    $stmt->execute();
                    
                    // Update password if provided
                    if (!empty($new_password)) {
                        if ($new_password !== $confirm_password) {
                            throw new Exception('New passwords do not match.');
                        }
                        
                        if (strlen($new_password) < 8) {
                            throw new Exception('New password must be at least 8 characters long.');
                        }
                        
                        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                        $stmt->bind_param("si", $hashed_password, $user_id);
                        $stmt->execute();
                    }
                    
                    // Commit transaction
                    $conn->commit();
                    
                    // Update session variables
                    $_SESSION['email'] = $email;
                    $_SESSION['full_name'] = $full_name;
                    
                    $_SESSION['admin_success'] = 'Admin account updated successfully.';
                } catch (Exception $e) {
                    $conn->rollback();
                    $error = 'Failed to update admin account: ' . $e->getMessage();
                }
            }
        }
    }
}

if ($error) {
    $_SESSION['admin_error'] = $error;
}

redirect('/admin/dashboard.php?tab=settings');
