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
        $withdrawal_fee_btc = (float)$_POST['withdrawal_fee_btc'];
        $withdrawal_fee_eth = (float)$_POST['withdrawal_fee_eth'];
        $withdrawal_fee_usdt = (float)$_POST['withdrawal_fee_usdt'];
        
        $min_withdrawal_btc = (float)$_POST['min_withdrawal_btc'];
        $min_withdrawal_eth = (float)$_POST['min_withdrawal_eth'];
        $min_withdrawal_usdt = (float)$_POST['min_withdrawal_usdt'];
        
        $max_withdrawal_btc = (float)$_POST['max_withdrawal_btc'];
        $max_withdrawal_eth = (float)$_POST['max_withdrawal_eth'];
        $max_withdrawal_usdt = (float)$_POST['max_withdrawal_usdt'];
        
        $security_key_recovery_fee = (float)$_POST['security_key_recovery_fee'];
        
        // Update settings in database
        $conn = $GLOBALS['conn'];
        $success = true;
        
        // Start transaction
        $conn->begin_transaction();
        
        try {
            // Update withdrawal fees
            $stmt = $conn->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'withdrawal_fee_btc'");
            $stmt->bind_param("s", $withdrawal_fee_btc);
            $success = $success && $stmt->execute();
            
            $stmt = $conn->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'withdrawal_fee_eth'");
            $stmt->bind_param("s", $withdrawal_fee_eth);
            $success = $success && $stmt->execute();
            
            $stmt = $conn->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'withdrawal_fee_usdt'");
            $stmt->bind_param("s", $withdrawal_fee_usdt);
            $success = $success && $stmt->execute();
            
            // Update min withdrawal limits
            $stmt = $conn->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'min_withdrawal_btc'");
            $stmt->bind_param("s", $min_withdrawal_btc);
            $success = $success && $stmt->execute();
            
            $stmt = $conn->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'min_withdrawal_eth'");
            $stmt->bind_param("s", $min_withdrawal_eth);
            $success = $success && $stmt->execute();
            
            $stmt = $conn->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'min_withdrawal_usdt'");
            $stmt->bind_param("s", $min_withdrawal_usdt);
            $success = $success && $stmt->execute();
            
            // Update max withdrawal limits
            $stmt = $conn->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'max_withdrawal_btc'");
            $stmt->bind_param("s", $max_withdrawal_btc);
            $success = $success && $stmt->execute();
            
            $stmt = $conn->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'max_withdrawal_eth'");
            $stmt->bind_param("s", $max_withdrawal_eth);
            $success = $success && $stmt->execute();
            
            $stmt = $conn->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'max_withdrawal_usdt'");
            $stmt->bind_param("s", $max_withdrawal_usdt);
            $success = $success && $stmt->execute();
            
            // Update security key recovery fee
            $stmt = $conn->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'security_key_recovery_fee'");
            $stmt->bind_param("s", $security_key_recovery_fee);
            $success = $success && $stmt->execute();
            
            if ($success) {
                $conn->commit();
                $_SESSION['admin_success'] = 'Settings updated successfully.';
            } else {
                throw new Exception('Failed to update settings.');
            }
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['admin_error'] = 'Failed to update settings: ' . $e->getMessage();
        }
        
        redirect('/admin/dashboard.php?tab=settings');
    }
}

if ($error) {
    $_SESSION['admin_error'] = $error;
}

redirect('/admin/dashboard.php?tab=settings');
