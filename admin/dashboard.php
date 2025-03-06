<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Check if user is logged in as admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Get all users
$users = getAllUsers();

// Get all transactions
$transactions = getAllTransactions();

// Calculate statistics
$totalUsers = count($users);
$activeUsers = count(array_filter($users, function($user) {
    return $user['status'] === 'active';
}));

$totalInvestment = calculateTotalInvestment($users);

$pendingTransactions = count(array_filter($transactions, function($tx) {
    return $tx['status'] === 'pending';
}));

$tradingVolume = calculateTradingVolume($transactions, 30);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CryptoTrade</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-body">
    <!-- Header -->
    <header class="admin-header">
        <div class="container">
            <div class="header-logo">
                <i class="fas fa-shield-alt"></i>
                <h1>CryptoTrade Admin Panel</h1>
            </div>
            <div class="header-user">
                <div class="user-info">
                    <p>Logged in as</p>
                    <p class="username">Administrator</p>
                </div>
                <a href="../includes/logout.php" class="icon-button logout-button">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="admin-main">
        <div class="container">
            <!-- Admin Tabs -->
            <div class="admin-tabs">
                <button class="tab-button active" data-tab="overview">Overview</button>
                <button class="tab-button" data-tab="users">Users</button>
                <button class="tab-button" data-tab="transactions">Transactions</button>
                <button class="tab-button" data-tab="settings">Settings</button>
            </div>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Overview Tab -->
                <div class="tab-pane active" id="overview">
                    <!-- Stats Cards -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-content">
                                <div>
                                    <p class="stat-label">Total Users</p>
                                    <p class="stat-value"><?php echo $totalUsers; ?></p>
                                </div>
                                <div class="stat-icon users">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="stat-footer">
                                <p><span><?php echo $activeUsers; ?></span> active users</p>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-content">
                                <div>
                                    <p class="stat-label">Total Investment</p>
                                    <p class="stat-value">$<?php echo number_format($totalInvestment, 2); ?></p>
                                </div>
                                <div class="stat-icon investment">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                            </div>
                            <div class="stat-footer">
                                <p><span>+12.5%</span> from last month</p>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-content">
                                <div>
                                    <p class="stat-label">Trading Volume (30d)</p>
                                    <p class="stat-value">$<?php echo number_format($tradingVolume, 2); ?></p>
                                </div>
                                <div class="stat-icon volume">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                            </div>
                            <div class="stat-footer">
                                <p><span><?php echo count($transactions); ?></span> total transactions</p>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-content">
                                <div>
                                    <p class="stat-label">Pending Transactions</p>
                                    <p class="stat-value"><?php echo $pendingTransactions; ?></p>
                                </div>
                                <div class="stat-icon pending">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                            </div>
                            <div class="stat-footer">
                                <p><span><?php echo $pendingTransactions; ?></span> require approval</p>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="activity-grid">
                        <div class="activity-card">
                            <div class="card-header">
                                <h2>Recent Transactions</h2>
                                <button class="refresh-button" id="refresh-transactions">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="transactions-list">
                                    <?php if (empty($transactions)): ?>
                                    <p class="no-data">No transactions found</p>
                                    <?php else: ?>
                                        <?php foreach (array_slice($transactions, 0, 5) as $tx): ?>
                                        <div class="transaction-item">
                                            <div class="transaction-info">
                                                <div class="transaction-icon <?php echo $tx['type']; ?>">
                                                    <i class="fas fa-<?php echo $tx['type'] === 'deposit' ? 'arrow-down' : 'arrow-up'; ?>"></i>
                                                </div>
                                                <div>
                                                    <p class="transaction-user">
                                                        <?php echo htmlspecialchars($tx['username']); ?> - 
                                                        <?php echo ucfirst($tx['type']); ?>
                                                    </p>
                                                    <p class="transaction-date"><?php echo date('M d, Y H:i', strtotime($tx['date'])); ?></p>
                                                </div>
                                            </div>
                                            <div class="transaction-details">
                                                <p class="transaction-amount <?php echo $tx['type']; ?>">
                                                    <?php echo $tx['type'] === 'deposit' ? '+' : '-'; ?><?php echo $tx['amount']; ?> <?php echo $tx['coin']; ?>
                                                </p>
                                                <span class="transaction-status <?php echo $tx['status']; ?>">
                                                    <?php echo $tx['status']; ?>
                                                </span>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="view-all">
                                    <button class="btn-link" id="view-all-transactions">View all transactions</button>
                                </div>
                            </div>
                        </div>

                        <div class="top-users-card">
                            <div class="card-header">
                                <h2>Top Users</h2>
                            </div>
                            <div class="card-body">
                                <div class="top-users-list">
                                    <?php 
                                    // Sort users by total investment
                                    usort($users, function($a, $b) {
                                        $totalA = array_sum(array_column($a['wallets'], 'value'));
                                        $totalB = array_sum(array_column($b['wallets'], 'value'));
                                        return $totalB <=> $totalA;
                                    });
                                    
                                    foreach (array_slice($users, 0, 5) as $index => $user): 
                                        $userTotal = array_sum(array_column($user['wallets'], 'value'));
                                        $percentage = ($userTotal / $totalInvestment) * 100;
                                    ?>
                                    <div class="top-user-item">
                                        <div class="user-rank-info">
                                            <div class="user-rank"><?php echo $index + 1; ?></div>
                                            <div>
                                                <p class="user-name"><?php echo htmlspecialchars($user['fullName']); ?></p>
                                                <p class="user-username"><?php echo htmlspecialchars($user['username']); ?></p>
                                            </div>
                                        </div>
                                        <p class="user-value">$<?php echo number_format($userTotal, 2); ?></p>
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: <?php echo min(100, $percentage); ?>%;"></div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="view-all">
                                    <button class="btn-link" id="view-all-users">View all users</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users Tab -->
                <div class="tab-pane" id="users">
                    <div class="admin-card">
                        <div class="card-header">
                            <div>
                                <h2>User Management</h2>
                                <p class="card-subtitle">Manage user accounts and balances</p>
                            </div>
                            <div class="header-actions">
                                <div class="search-box">
                                    <i class="fas fa-search"></i>
                                    <input type="text" id="user-search" placeholder="Search users...">
                                </div>
                                <button class="btn btn-primary" id="add-user-btn">
                                    <i class="fas fa-plus"></i> Add User
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-container">
                                <table class="admin-table">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Status</th>
                                            <th>BTC</th>
                                            <th>ETH</th>
                                            <th>USDT</th>
                                            <th class="text-right">Total Value</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="users-table-body">
                                        <?php if (empty($users)): ?>
                                        <tr>
                                            <td colspan="7" class="no-data">No users found</td>
                                        </tr>
                                        <?php else: ?>
                                            <?php foreach ($users as $user): 
                                                $totalValue = array_sum(array_column($user['wallets'], 'value'));
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="user-info">
                                                        <p class="user-name"><?php echo htmlspecialchars($user['fullName']); ?></p>
                                                        <p class="user-email"><?php echo htmlspecialchars($user['email']); ?></p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="status-badge <?php echo $user['status']; ?>">
                                                        <?php echo ucfirst($user['status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="wallet-info">
                                                        <p class="wallet-balance"><?php echo $user['wallets']['BTC']['balance']; ?> BTC</p>
                                                        <p class="wallet-value">$<?php echo number_format($user['wallets']['BTC']['value'], 2); ?></p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="wallet-info">
                                                        <p class="wallet-balance"><?php echo $user['wallets']['ETH']['balance']; ?> ETH</p>
                                                        <p class="wallet-value">$<?php echo number_format($user['wallets']['ETH']['value'], 2); ?></p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="wallet-info">
                                                        <p class="wallet-balance"><?php echo $user['wallets']['USDT']['balance']; ?> USDT</p>
                                                        <p class="wallet-value">$<?php echo number_format($user['wallets']['USDT']['value'], 2); ?></p>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <p class="total-value">$<?php echo number_format($totalValue, 2); ?></p>
                                                </td>
                                                <td class="text-right">
                                                    <div class="action-buttons">
                                                        <button class="btn btn-outline btn-sm manage-funds-btn" data-user-id="<?php echo $user['id']; ?>" data-username="<?php echo htmlspecialchars($user['username']); ?>">
                                                            Funds
                                                        </button>
                                                        <button class="btn btn-outline btn-sm edit-user-btn" data-user-id="<?php echo $user['id']; ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-outline btn-sm delete-user-btn" data-user-id="<?php echo $user['id']; ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transactions Tab -->
                <div class="tab-pane" id="transactions">
                    <div class="admin-card">
                        <div class="card-header">
                            <div>
                                <h2>Transaction History</h2>
                                <p class="card-subtitle">All deposits and withdrawals across the platform</p>
                            </div>
                            <button class="refresh-button" id="refresh-all-transactions">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-container">
                                <table class="admin-table">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="transactions-table-body">
                                        <?php if (empty($transactions)): ?>
                                        <tr>
                                            <td colspan="6" class="no-data">No transactions found</td>
                                        </tr>
                                        <?php else: ?>
                                            <?php foreach ($transactions as $tx): 
                                                $coinPrice = $tx['coin'] === 'BTC' ? 42568.23 : ($tx['coin'] === 'ETH' ? 2356.78 : 1.0);
                                                $value = $tx['amount'] * $coinPrice;
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($tx['username']); ?></td>
                                                <td>
                                                    <div class="transaction-type">
                                                        <div class="transaction-icon-small <?php echo $tx['type']; ?>">
                                                            <i class="fas fa-<?php echo $tx['type'] === 'deposit' ? 'arrow-down' : 'arrow-up'; ?>"></i>
                                                        </div>
                                                        <span class="<?php echo $tx['type']; ?>">
                                                            <?php echo ucfirst($tx['type']); ?>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="amount-info">
                                                        <p class="amount"><?php echo $tx['amount']; ?> <?php echo $tx['coin']; ?></p>
                                                        <p class="amount-value">$<?php echo number_format($value, 2); ?></p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="status-badge <?php echo $tx['status']; ?>">
                                                        <?php echo ucfirst($tx['status']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('M d, Y H:i', strtotime($tx['date'])); ?></td>
                                                <td class="text-right">
                                                    <?php if ($tx['status'] === 'pending'): ?>
                                                    <div class="action-buttons">
                                                        <button class="btn btn-outline btn-sm approve-tx-btn" data-tx-id="<?php echo $tx['id']; ?>">
                                                            Approve
                                                        </button>
                                                        <button class="btn btn-outline btn-sm reject-tx-btn" data-tx-id="<?php echo $tx['id']; ?>">
                                                            Reject
                                                        </button>
                                                    </div>
                                                    <?php else: ?>
                                                    <button class="btn btn-outline btn-sm view-tx-btn" data-tx-id="<?php echo $tx['id']; ?>">
                                                        View
                                                    </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Tab -->
                <div class="tab-pane" id="settings">
                    <div class="admin-card">
                        <div class="card-header">
                            <h2>Platform Settings</h2>
                            <p class="card-subtitle">Configure system-wide settings</p>
                        </div>
                        <div class="card-body">
                            <!-- Security Settings -->
                            <div class="settings-section">
                                <h3>Security</h3>
                                <div class="settings-grid">
                                    <div class="setting-item">
                                        <label>Minimum Withdrawal Amount</label>
                                        <div class="setting-input">
                                            <input type="number" id="min-withdrawal" value="0.001" step="0.001">
                                            <button class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <label>Maximum Withdrawal Amount</label>
                                        <div class="setting-input">
                                            <input type="number" id="max-withdrawal" value="10.0" step="0.1">
                                            <button class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="settings-divider"></div>
                            
                            <!-- Fee Settings -->
                            <div class="settings-section">
                                <h3>Fees</h3>
                                <div class="settings-grid">
                                    <div class="setting-item">
                                        <label>Withdrawal Fee (%)</label>
                                        <div class="setting-input">
                                            <input type="number" id="withdrawal-fee" value="1.5" step="0.1">
                                            <button class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <label>Trading Fee (%)</label>
                                        <div class="setting-input">
                                            <input type="number" id="trading-fee" value="0.5" step="0.1">
                                            <button class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="settings-divider"></div>
                            
                            <!-- Admin Account Settings -->
                            <div class="settings-section">
                                <h3>Admin Account</h3>
                                <div class="settings-grid">
                                    <div class="setting-item vertical">
                                        <label>Change Password</label>
                                        <div class="password-inputs">
                                            <input type="password" placeholder="Current Password" class="full-width">
                                            <input type="password" placeholder="New Password" class="full-width">
                                            <input type="password" placeholder="Confirm New Password" class="full-width">
                                            <button class="btn btn-primary full-width">Update Password</button>
                                        </div>
                                    </div>
                                    <div class="setting-item vertical">
                                        <label>Two-Factor Authentication</label>
                                        <div class="tfa-box">
                                            <p>Enhance your account security with two-factor authentication</p>
                                            <button class="btn btn-primary full-width">Enable 2FA</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modals -->
    <!-- Add User Modal -->
    <div class="modal" id="add-user-modal">
        <div class="modal-content">
            <button class="modal-close">
                <i class="fas fa-times"></i>
            </button>
            <h2>Add New User</h2>
            <p class="modal-subtitle">Create a new user account</p>
            <form id="add-user-form" action="../includes/admin_actions.php" method="post">
                <input type="hidden" name="action" value="add_user">
                <div class="form-group">
                    <label for="new-full-name">Full Name</label>
                    <input type="text" id="new-full-name" name="full_name" placeholder="John Doe" required>
                </div>
                <div class="form-group">
                    <label for="new-username">Username</label>
                    <input type="text" id="new-username" name="username" placeholder="johndoe" required>
                </div>
                <div class="form-group">
                    <label for="new-email">Email</label>
                    <input type="email" id="new-email" name="email" placeholder="john@example.com" required>
                </div>
                <div class="form-group">
                    <label for="new-password">Password</label>
                    <input type="password" id="new-password" name="password" placeholder="••••••••" required>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <p class="alert-title">Note</p>
                        <p>New users will start with zero balance in all wallets.</p>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-outline modal-close-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal" id="edit-user-modal">
        <div class="modal-content">
            <button class="modal-close">
                <i class="fas fa-times"></i>
            </button>
            <h2>Edit User</h2>
            <p class="modal-subtitle">Update user account details</p>
            <form id="edit-user-form" action="../includes/admin_actions.php" method="post">
                <input type="hidden" name="action" value="edit_user">
                <input type="hidden" name="user_id" id="edit-user-id">
                <div class="form-group">
                    <label for="edit-full-name">Full Name</label>
                    <input type="text" id="edit-full-name" name="full_name" placeholder="John Doe" required>
                </div>
                <div class="form-group">
                    <label for="edit-username">Username</label>
                    <input type="text" id="edit-username" name="username" placeholder="johndoe" required>
                </div>
                <div class="form-group">
                    <label for="edit-email">Email</label>
                    <input type="email" id="edit-email" name="email" placeholder="john@example.com" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <div class="status-buttons">
                        <button type="button" class="btn status-btn active" data-status="active">Active</button>
                        <button type="button" class="btn status-btn inactive" data-status="inactive">Inactive</button>
                        <input type="hidden" name="status" id="edit-status" value="active">
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-outline modal-close-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete User Modal -->
    <div class="modal" id="delete-user-modal">
        <div class="modal-content">
            <button class="modal-close">
                <i class="fas fa-times"></i>
            </button>
            <h2>Delete User</h2>
            <p class="modal-subtitle">Are you sure you want to delete this user?</p>
            <form id="delete-user-form" action="../includes/admin_actions.php" method="post">
                <input type="hidden" name="action" value="delete_user">
                <input type="hidden" name="user_id" id="delete-user-id">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <p class="alert-title">Warning</p>
                        <p>This action cannot be undone. This will permanently delete the user account and remove all associated data.</p>
