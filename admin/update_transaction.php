<?php
require_once '../includes/admin_check.php';

// Get transaction ID and status from URL
$transaction_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$status = isset($_GET['status']) ? clean_input($_GET['status']) : '';

// Validate status
if ($status !== 'completed' && $status !== 'rejected') {
    $_SESSION['admin_error'] = 'Invalid status value.';
    redirect('/admin/dashboard.php');
}

// Update transaction status
$result = update_transaction_status($transaction_id, $status);

if ($result) {
    $_SESSION['admin_success'] = 'Transaction status updated successfully.';
} else {
    $_SESSION['admin_error'] = 'Failed to update transaction status.';
}

redirect('/admin/dashboard.php');
