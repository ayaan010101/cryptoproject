<?php
$page_title = "Admin Dashboard - CryptoTrade Platform";
$body_class = "admin-dashboard-page";

// Check if user is logged in and is an admin
require_once '../includes/admin_check.php';

// Get admin data
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$full_name = $_SESSION['full_name'];

// Get all users
$users = get_all_users();

// Get all transactions
$transactions = get_all_transactions();

// Get platform statistics
$total_investment = get_total_investment();
$active_users_count = get_active_users_count();
$pending_transactions_count = get_pending_transactions_count();
$trading_volume = get_trading_volume(30); // Last 30 days

require_once '../includes/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-slate-900 via-indigo-900 to-slate-800 text-white pt-24 pb-12 px-4">
    <!-- Header with admin info -->
    <header class="container mx-auto mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold">Admin Dashboard</h1>
                <p class="text-slate-300">Welcome, <?php echo htmlspecialchars($full_name); ?></p>
            </div>
            <div class="mt-4 md:mt-0 flex items-center space-x-4">
                <button id="refresh-data" class="p-2 rounded-full bg-slate-800/50 hover:bg-slate-700/50 transition-colors">
                    <i class="fas fa-sync-alt"></i>
                </button>
                <div class="text-right">
                    <p class="text-sm text-slate-300">Total Platform Value</p>
                    <p class="font-bold text-xl">$<?php echo number_format($total_investment, 2); ?></p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main content -->
    <main class="container mx-auto">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Active Users -->
            <div class="card dashboard-card bg-slate-800/80 backdrop-blur-sm border-indigo-700/50 shadow-xl shadow-indigo-900/20 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-lg">Active Users</h3>
                    <div class="p-2 rounded-full bg-blue-500/20 text-blue-400">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold"><?php echo $active_users_count; ?></p>
                <p class="text-slate-400 text-sm mt-2">Total active accounts</p>
            </div>

            <!-- Pending Transactions -->
            <div class="card dashboard-card bg-slate-800/80 backdrop-blur-sm border-indigo-700/50 shadow-xl shadow-indigo-900/20 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-lg">Pending Transactions</h3>
                    <div class="p-2 rounded-full bg-yellow-500/20 text-yellow-400">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold"><?php echo $pending_transactions_count; ?></p>
                <p class="text-slate-400 text-sm mt-2">Awaiting approval</p>
            </div>

            <!-- Trading Volume -->
            <div class="card dashboard-card bg-slate-800/80 backdrop-blur-sm border-indigo-700/50 shadow-xl shadow-indigo-900/20 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-lg">Trading Volume (30d)</h3>
                    <div class="p-2 rounded-full bg-green-500/20 text-green-400">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold">$<?php echo number_format($trading_volume, 2); ?></p>
                <p class="text-slate-400 text-sm mt-2">Last 30 days</p>
            </div>

            <!-- Total Investment -->
            <div class="card dashboard-card bg-slate-800/80 backdrop-blur-sm border-indigo-700/50 shadow-xl shadow-indigo-900/20 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-lg">Total Investment</h3>
                    <div class="p-2 rounded-full bg-purple-500/20 text-purple-400">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold">$<?php echo number_format($total_investment, 2); ?></p>
                <p class="text-slate-400 text-sm mt-2">All user assets</p>
            </div>
        </div>

        <!-- Tabs navigation -->
        <div class="tabs-container mb-8">
            <div class="flex border-b border-slate-700">
                <button class="tab-trigger active-tab px-6 py-3 font-medium text-white border-b-2 border-blue-500" data-tab="users">Users</button>
                <button class="tab-trigger px-6 py-3 font-medium text-slate-300 hover:text-white" data-tab="transactions">Transactions</button>
                <button class="tab-trigger px-6 py-3 font-medium text-slate-300 hover:text-white" data-tab="settings">Settings</button>
            </div>
        </div>

        <!-- Users Tab -->
        <div class="tab-content" data-tab="users">
            <div class="card dashboard-card bg-slate-800/80 backdrop-blur-sm border-indigo-700/50 shadow-xl shadow-indigo-900/20">
                <div class="card-header flex flex-row items-center justify-between pb-2">
                    <div>
                        <h2 class="text-xl font-bold">User Management</h2>
                        <p class="text-sm text-slate-400">Manage platform users</p>
                    </div>
                    <a href="add_user.php" class="btn btn-primary">
                        <i class="fas fa-user-plus mr-2"></i> Add User
                    </a>
                </div>
                <div class="card-body overflow-x-auto">
                    <table class="w-full min-w-full">
                        <thead class="bg-slate-700/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">User</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Role</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Join Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Balance</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="7" class="px-4 py-4 text-center text-slate-400">No users found</td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($users as $user): ?>
                                <tr class="hover:bg-slate-700/30 transition-colors">
                                    <td class="px-4 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-slate-700 flex items-center justify-center">
                                                <span class="font-medium text-slate-300"><?php echo substr($user['username'], 0, 2); ?></span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium"><?php echo htmlspecialchars($user['username']); ?></div>
                                                <div class="text-sm text-slate-400"><?php echo htmlspecialchars($user['full_name']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm"><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td class="px-4 py-4 text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $user['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                            <?php echo ucfirst($user['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $user['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'; ?>">
                                            <?php echo ucfirst($user['role']); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm"><?php echo date('M j, Y', strtotime($user['join_date'])); ?></td>
                                    <td class="px-4 py-4 text-sm">
                                        <?php 
                                        $total_balance = 0;
                                        foreach ($user['wallets'] as $wallet) {
                                            $total_balance += $wallet['value'];
                                        }
                                        echo '$' . number_format($total_balance, 2);
                                        ?>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-right space-x-2">
                                        <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="text-indigo-400 hover:text-indigo-300 transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($user['id'] !== $user_id): // Don't allow admin to change their own status ?>
                                        <a href="toggle_user_status.php?id=<?php echo $user['id']; ?>&status=<?php echo $user['status'] === 'active' ? 'inactive' : 'active'; ?>" class="text-yellow-400 hover:text-yellow-300 transition-colors">
                                            <i class="fas <?php echo $user['status'] === 'active' ? 'fa-ban' : 'fa-check-circle'; ?>"></i>
                                        </a>
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

        <!-- Transactions Tab -->
        <div class="tab-content hidden" data-tab="transactions">
            <div class="card dashboard-card bg-slate-800/80 backdrop-blur-sm border-indigo-700/50 shadow-xl shadow-indigo-900/20">
                <div class="card-header flex flex-row items-center justify-between pb-2">
                    <div>
                        <h2 class="text-xl font-bold">Transaction Management</h2>
                        <p class="text-sm text-slate-400">Monitor and manage all transactions</p>
                    </div>
                    <div class="flex space-x-2">
                        <select id="transaction-filter" class="bg-slate-700 border-slate-600 text-white rounded-md text-sm">
                            <option value="all">All Types</option>
                            <option value="deposit">Deposits</option>
                            <option value="withdrawal">Withdrawals</option>
                            <option value="payment">Payments</option>
                        </select>
                        <select id="status-filter" class="bg-slate-700 border-slate-600 text-white rounded-md text-sm">
                            <option value="all">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                </div>
                <div class="card-body overflow-x-auto">
                    <table class="w-full min-w-full">
                        <thead class="bg-slate-700/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">User</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Coin</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            <?php if (empty($transactions)): ?>
                            <tr>
                                <td colspan="8" class="px-4 py-4 text-center text-slate-400">No transactions found</td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($transactions as $tx): ?>
                                <tr class="hover:bg-slate-700/30 transition-colors transaction-row" data-type="<?php echo $tx['type']; ?>" data-status="<?php echo $tx['status']; ?>">
                                    <td class="px-4 py-4 text-sm"><?php echo $tx['id']; ?></td>
                                    <td class="px-4 py-4 text-sm"><?php echo htmlspecialchars($tx['username']); ?></td>
                                    <td class="px-4 py-4 text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $tx['type'] === 'deposit' ? 'bg-green-100 text-green-800' : ($tx['type'] === 'withdrawal' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'); ?>">
                                            <?php echo ucfirst($tx['type']); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm"><?php echo $tx['coin']; ?></td>
                                    <td class="px-4 py-4 text-sm"><?php echo $tx['amount']; ?> <?php echo $tx['coin']; ?></td>
                                    <td class="px-4 py-4 text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $tx['status'] === 'completed' ? 'bg-green-100 text-green-800' : ($tx['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'); ?>">
                                            <?php echo ucfirst($tx['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm"><?php echo date('M j, Y H:i', strtotime($tx['date'])); ?></td>
                                    <td class="px-4 py-4 text-sm text-right space-x-2">
                                        <?php if ($tx['status'] === 'pending'): ?>
                                        <a href="update_transaction.php?id=<?php echo $tx['id']; ?>&status=completed" class="text-green-400 hover:text-green-300 transition-colors">
                                            <i class="fas fa-check"></i>
                                        </a>
                                        <a href="update_transaction.php?id=<?php echo $tx['id']; ?>&status=rejected" class="text-red-400 hover:text-red-300 transition-colors">
                                            <i class="fas fa-times"></i>
                                        </a>
                                        <?php else: ?>
                                        <span class="text-slate-500"><i class="fas fa-lock"></i></span>
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

        <!-- Settings Tab -->
        <div class="tab-content hidden" data-tab="settings">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Platform Settings -->
                <div class="card dashboard-card bg-slate-800/80 backdrop-blur-sm border-indigo-700/50 shadow-xl shadow-indigo-900/20">
                    <div class="card-header">
                        <h2 class="text-xl font-bold">Platform Settings</h2>
                        <p class="text-sm text-slate-400">Configure platform parameters</p>
                    </div>
                    <div class="card-body">
                        <form action="update_settings.php" method="post" class="space-y-6 validate">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            
                            <div class="space-y-4">
                                <h3 class="font-medium text-indigo-400">Withdrawal Fees</h3>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label for="withdrawal_fee_btc" class="block text-sm">BTC Fee</label>
                                        <input type="text" id="withdrawal_fee_btc" name="withdrawal_fee_btc" value="0.0005" class="bg-slate-700 border-slate-600 text-white rounded-md w-full">
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label for="withdrawal_fee_eth" class="block text-sm">ETH Fee</label>
                                        <input type="text" id="withdrawal_fee_eth" name="withdrawal_fee_eth" value="0.005" class="bg-slate-700 border-slate-600 text-white rounded-md w-full">
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label for="withdrawal_fee_usdt" class="block text-sm">USDT Fee</label>
                                        <input type="text" id="withdrawal_fee_usdt" name="withdrawal_fee_usdt" value="1.0" class="bg-slate-700 border-slate-600 text-white rounded-md w-full">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <h3 class="font-medium text-indigo-400">Withdrawal Limits</h3>
                                
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="space-y-2">
                                        <label for="min_withdrawal_btc" class="block text-sm">Min BTC</label>
                                        <input type="text" id="min_withdrawal_btc" name="min_withdrawal_btc" value="0.001" class="bg-slate-700 border-slate-600 text-white rounded-md w-full">
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label for="min_withdrawal_eth" class="block text-sm">Min ETH</label>
                                        <input type="text" id="min_withdrawal_eth" name="min_withdrawal_eth" value="0.01" class="bg-slate-700 border-slate-600 text-white rounded-md w-full">
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label for="min_withdrawal_usdt" class="block text-sm">Min USDT</label>
                                        <input type="text" id="min_withdrawal_usdt" name="min_withdrawal_usdt" value="10" class="bg-slate-700 border-slate-600 text-white rounded-md w-full">
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label for="max_withdrawal_btc" class="block text-sm">Max BTC</label>
                                        <input type="text" id="max_withdrawal_btc" name="max_withdrawal_btc" value="1" class="bg-slate-700 border-slate-600 text-white rounded-md w-full">
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label for="max_withdrawal_eth" class="block text-sm">Max ETH</label>
                                        <input type="text" id="max_withdrawal_eth" name="max_withdrawal_eth" value="10" class="bg-slate-700 border-slate-600 text-white rounded-md w-full">
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label for="max_withdrawal_usdt" class="block text-sm">Max USDT</label>
                                        <input type="text" id="max_withdrawal_usdt" name="max_withdrawal_usdt" value="10000" class="bg-slate-700 border-slate-600 text-white rounded-md w-full">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <h3 class="font-medium text-indigo-400">Security Settings</h3>
                                
                                <div class="space-y-2">
                                    <label for="security_key_recovery_fee" class="block text-sm">Security Key Recovery Fee (USDT)</label>
                                    <input type="text" id="security_key_recovery_fee" name="security_key_recovery_fee" value="300" class="bg-slate-700 border-slate-600 text-white rounded-md w-full">
                                </div>
                            </div>
                            
                            <div class="pt-4">
                                <button type="submit" class="btn btn-primary w-full">Save Settings</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Admin Account -->
                <div class="card dashboard-card bg-slate-800/80 backdrop-blur-sm border-indigo-700/50 shadow-xl shadow-indigo-900/20">
                    <div class="card-header">
                        <h2 class="text-xl font-bold">Admin Account</h2>
                        <p class="text-sm text-slate-400">Manage your admin account</p>
                    </div>
                    <div class="card-body">
                        <form action="update_admin.php" method="post" class="space-y-6 validate">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            
                            <div class="space-y-2">
                                <label for="admin_username" class="block text-sm">Username</label>
                                <input type="text" id="admin_username" name="admin_username" value="<?php echo htmlspecialchars($username); ?>" class="bg-slate-700 border-slate-600 text-white rounded-md w-full" readonly>
                                <p class="text-xs text-slate-400">Username cannot be changed</p>
                            </div>
                            
                            <div class="space-y-2">
                                <label for="admin_email" class="block text-sm">Email</label>
                                <input type="email" id="admin_email" name="admin_email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" class="bg-slate-700 border-slate-600 text-white rounded-md w-full">
                            </div>
                            
                            <div class="space-y-2">
                                <label for="admin_full_name" class="block text-sm">Full Name</label>
                                <input type="text" id="admin_full_name" name="admin_full_name" value="<?php echo htmlspecialchars($full_name); ?>" class="bg-slate-700 border-slate-600 text-white rounded-md w-full">
                            </div>
                            
                            <div class="space-y-2">
                                <label for="admin_current_password" class="block text-sm">Current Password</label>
                                <input type="password" id="admin_current_password" name="admin_current_password" class="bg-slate-700 border-slate-600 text-white rounded-md w-full">
                            </div>
                            
                            <div class="space-y-2">
                                <label for="admin_new_password" class="block text-sm">New Password (leave blank to keep current)</label>
                                <input type="password" id="admin_new_password" name="admin_new_password" class="bg-slate-700 border-slate-600 text-white rounded-md w-full">
                            </div>
                            
                            <div class="space-y-2">
                                <label for="admin_confirm_password" class="block text-sm">Confirm New Password</label>
                                <input type="password" id="admin_confirm_password" name="admin_confirm_password" class="bg-slate-700 border-slate-600 text-white rounded-md w-full">
                            </div>
                            
                            <div class="pt-4">
                                <button type="submit" class="btn btn-primary w-full">Update Account</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    // Transaction filtering
    document.addEventListener('DOMContentLoaded', function() {
        const typeFilter = document.getElementById('transaction-filter');
        const statusFilter = document.getElementById('status-filter');
        const rows = document.querySelectorAll('.transaction-row');
        
        function filterTransactions() {
            const typeValue = typeFilter.value;
            const statusValue = statusFilter.value;
            
            rows.forEach(row => {
                const rowType = row.getAttribute('data-type');
                const rowStatus = row.getAttribute('data-status');
                
                const typeMatch = typeValue === 'all' || rowType === typeValue;
                const statusMatch = statusValue === 'all' || rowStatus === statusValue;
                
                if (typeMatch && statusMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        if (typeFilter && statusFilter) {
            typeFilter.addEventListener('change', filterTransactions);
            statusFilter.addEventListener('change', filterTransactions);
        }
    });
</script>

<?php require_once '../includes/footer.php'; ?>
