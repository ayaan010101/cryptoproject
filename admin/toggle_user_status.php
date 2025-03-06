<?php
require_once '../includes/admin_check.php';

// Get user ID and status from URL
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$status = isset($_GET['status']) ? clean_input($_GET['status']) : '';

// Validate status
if ($status !== 'active' && $status !== 'inactive') {
    $_SESSION['admin_error'] = 'Invalid status value.';
    redirect('/admin/dashboard.php');
}

// Don't allow admin to change their own status
if ($user_id === $_SESSION['user_id']) {
    $_SESSION['admin_error'] = 'You cannot change your own status.';
    redirect('/admin/dashboard.php');
}

// Update user status
$result = update_user_status($user_id, $status);

if ($result) {
    $_SESSION['admin_success'] = 'User status updated successfully.';
} else {
    $_SESSION['admin_error'] = 'Failed to update user status.';
}

redirect('/admin/dashboard.php');
