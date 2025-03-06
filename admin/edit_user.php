<?php
$page_title = "Edit User - Admin Dashboard";
$body_class = "admin-page";

// Check if user is logged in and is an admin
require_once '../includes/admin_check.php';

// Get user ID from URL
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$user_id) {
    // Redirect to dashboard if no user ID provided
    redirect('/admin/dashboard.php');
}

// Get user data
$conn = $GLOBALS['conn'];
$stmt = $conn->prepare("SELECT id, username, email, full_name, status, role FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // User not found, redirect to dashboard
    redirect('/admin/dashboard.php');
}

$user = $result->fetch_assoc();

// Process form submission
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !verify_token($_POST['csrf_token'])) {
        $error = 'Invalid form submission. Please try again.';
    } else {
        $email = clean_input($_POST['email']);
        $full_name = clean_input($_POST['full_name']);
        $username = clean_input($_POST['username']);
        $status = clean_input($_POST['status']);
        $role = clean_input($_POST['role']);
        
        // Check if email is already used by another user
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1");
        $stmt->bind_param("si", $email, $user_id);
        $stmt->execute();
        $email_result = $stmt->get_result();
        
        if ($email_result->num_rows > 0) {
            $error = 'Email is already used by another user.';
        } else {
            // Check if username is already used by another user
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ? LIMIT 1");
            $stmt->bind_param("si", $username, $user_id);
            $stmt->execute();
            $username_result = $stmt->get_result();
            
            if ($username_result->num_rows > 0) {
                $error = 'Username is already used by another user.';
            } else {
                // Update user
                $result = update_user_details($user_id, $email, $full_name, $username, $status, $role);
                
                if ($result) {
                    $success = 'User updated successfully.';
                    
                    // Refresh user data
                    $stmt = $conn->prepare("SELECT id, username, email, full_name, status, role FROM users WHERE id = ? LIMIT 1");
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $user = $result->fetch_assoc();
                } else {
                    $error = 'Failed to update user. Please try again.';
                }
            }
        }
    }
}

require_once '../includes/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-slate-900 via-indigo-900 to-slate-800 text-white pt-24 pb-12 px-4">
    <div class="container mx-auto max-w-4xl">
        <div class="flex items-center mb-8">
            <a href="/admin/dashboard.php" class="text-indigo-400 hover:text-indigo-300 transition-colors mr-4">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <h1 class="text-2xl font-bold">Edit User</h1>
        </div>
        
        <?php if ($success): ?>
        <div class="alert alert-success mb-6">
            <?php echo $success; ?>
        </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
        <div class="alert alert-danger mb-6">
            <?php echo $error; ?>
        </div>
        <?php endif; ?>
        
        <div class="card dashboard-card bg-slate-800/80 backdrop-blur-sm border-indigo-700/50 shadow-xl shadow-indigo-900/20">
            <div class="card-header">
                <h2 class="text-xl font-bold">User Details</h2>
                <p class="text-sm text-slate-400">Edit user information</p>
            </div>
            <div class="card-body">
                <form method="POST" action="" class="space-y-6 validate">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="username" class="block text-sm font-medium">Username</label>
                            <input type="text" id="username" name="username" required 
                                class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-white" 
                                value="<?php echo htmlspecialchars($user['username']); ?>">
                        </div>
                        
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-medium">Email</label>
                            <input type="email" id="email" name="email" required 
                                class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-white" 
                                value="<?php echo htmlspecialchars($user['email']); ?>">
                        </div>
                        
                        <div class="space-y-2">
                            <label for="full_name" class="block text-sm font-medium">Full Name</label>
                            <input type="text" id="full_name" name="full_name" required 
                                class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-white" 
                                value="<?php echo htmlspecialchars($user['full_name']); ?>">
                        </div>
                        
                        <div class="space-y-2">
                            <label for="status" class="block text-sm font-medium">Status</label>
                            <select id="status" name="status" required 
                                class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-white">
                                <option value="active" <?php echo $user['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo $user['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label for="role" class="block text-sm font-medium">Role</label>
                            <select id="role" name="role" required 
                                class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-white">
                                <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="pt-4 flex justify-between">
                        <a href="/admin/dashboard.php" class="px-6 py-2 border border-slate-600 text-slate-300 rounded-md hover:bg-slate-700 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="mt-8 card dashboard-card bg-slate-800/80 backdrop-blur-sm border-indigo-700/50 shadow-xl shadow-indigo-900/20">
            <div class="card-header">
                <h2 class="text-xl font-bold">User Wallets</h2>
                <p class="text-sm text-slate-400">View user wallet information</p>
            </div>
            <div class="card-body">
                <?php 
                // Get user wallets
                $wallets = get_user_wallets($user_id);
                
                if (empty($wallets)): 
                ?>
                <p class="text-center text-slate-400 py-4">No wallets found for this user</p>
                <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <?php foreach ($wallets as $coin => $data): ?>
                    <div class="p-4 bg-slate-700/50 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center">
                                <div class="crypto-icon crypto-icon-<?php echo strtolower($coin); ?>">
                                    <?php echo substr($coin, 0, 1); ?>
                                </div>
                                <span class="font-medium"><?php echo $coin; ?></span>
                            </div>
                            <span class="text-sm text-slate-300">$<?php echo number_format($data['value'], 2); ?></span>
                        </div>
                        <div class="text-sm text-slate-400 mb-2">
                            Balance: <?php echo $data['balance']; ?> <?php echo $coin; ?>
                        </div>
                        <div class="text-xs text-slate-500 break-all">
                            Address: <?php echo $data['address']; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mt-8 card dashboard-card bg-slate-800/80 backdrop-blur-sm border-indigo-700/50 shadow-xl shadow-indigo-900/20">
            <div class="card-header">
                <h2 class="text-xl font-bold">Recent Transactions</h2>
                <p class="text-sm text-slate-400">View user's recent transactions</p>
            </div>
            <div class="card-body">
                <?php 
                // Get user transactions
                $transactions = get_user_transactions($user_id, 10);
                
                if (empty($transactions)): 
                ?>
                <p class="text-center text-slate-400 py-4">No transactions found for this user</p>
                <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($transactions as $tx): ?>
                    <div class="flex items-center justify-between p-3 bg-slate-700/50 rounded-md hover:bg-slate-700/70 transition-colors">
                        <div class="flex items-center">
                            <div class="p-2 rounded-full <?php echo $tx['type'] === 'deposit' ? 'bg-green-500/30 text-green-200' : 'bg-red-500/30 text-red-200'; ?> mr-3">
                                <i class="fas <?php echo $tx['type'] === 'deposit' ? 'fa-arrow-down' : 'fa-arrow-up'; ?>"></i>
                            </div>
                            <div>
                                <p class="font-medium"><?php echo ucfirst($tx['type']); ?></p>
                                <p class="text-xs text-slate-400"><?php echo date('M j, Y H:i', strtotime($tx['date'])); ?></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-medium <?php echo $tx['type'] === 'deposit' ? 'text-green-500' : 'text-red-500'; ?>">
                                <?php echo $tx['type'] === 'deposit' ? '+' : '-'; ?><?php echo $tx['amount']; ?> <?php echo $tx['coin']; ?>
                            </p>
                            <p class="text-xs text-slate-400"><?php echo $tx['status']; ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
