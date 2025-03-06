<?php
$page_title = "Add User - Admin Dashboard";
$body_class = "admin-page";

// Check if user is logged in and is an admin
require_once '../includes/admin_check.php';

// Process form submission
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !verify_token($_POST['csrf_token'])) {
        $error = 'Invalid form submission. Please try again.';
    } else {
        $username = clean_input($_POST['username']);
        $email = clean_input($_POST['email']);
        $password = $_POST['password']; // Don't clean password
        $confirm_password = $_POST['confirm_password']; // Don't clean password
        $full_name = clean_input($_POST['full_name']);
        $phone = clean_input($_POST['phone']);
        $role = clean_input($_POST['role']);
        
        // Validate passwords match
        if ($password !== $confirm_password) {
            $error = 'Passwords do not match.';
        } 
        // Check password length
        elseif (strlen($password) < 8) {
            $error = 'Password must be at least 8 characters long.';
        } else {
            // Register the user
            $result = register_user($username, $email, $password, $full_name, $phone);
            
            if ($result['success']) {
                // If role is admin, update the user role
                if ($role === 'admin') {
                    $conn = $GLOBALS['conn'];
                    $stmt = $conn->prepare("UPDATE users SET role = 'admin' WHERE username = ?");
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                }
                
                $success = 'User created successfully!';
            } else {
                $error = $result['message'];
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
            <h1 class="text-2xl font-bold">Add New User</h1>
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
                <h2 class="text-xl font-bold">User Information</h2>
                <p class="text-sm text-slate-400">Create a new user account</p>
            </div>
            <div class="card-body">
                <form method="POST" action="" class="space-y-6 validate">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="username" class="block text-sm font-medium">Username</label>
                            <input type="text" id="username" name="username" required 
                                class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-white" 
                                placeholder="johndoe">
                        </div>
                        
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-medium">Email</label>
                            <input type="email" id="email" name="email" required 
                                class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-white" 
                                placeholder="john.doe@example.com">
                        </div>
                        
                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-medium">Password</label>
                            <input type="password" id="password" name="password" required 
                                class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-white" 
                                placeholder="••••••••">
                            <p class="text-xs text-slate-400">Must be at least 8 characters</p>
                        </div>
                        
                        <div class="space-y-2">
                            <label for="confirm_password" class="block text-sm font-medium">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" required 
                                class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-white" 
                                placeholder="••••••••">
                        </div>
                        
                        <div class="space-y-2">
                            <label for="full_name" class="block text-sm font-medium">Full Name</label>
                            <input type="text" id="full_name" name="full_name" required 
                                class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-white" 
                                placeholder="John Doe">
                        </div>
                        
                        <div class="space-y-2">
                            <label for="phone" class="block text-sm font-medium">Phone Number</label>
                            <input type="tel" id="phone" name="phone" 
                                class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-white" 
                                placeholder="+1 (555) 123-4567">
                        </div>
                        
                        <div class="space-y-2">
                            <label for="role" class="block text-sm font-medium">Role</label>
                            <select id="role" name="role" required 
                                class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-white">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="pt-4 flex justify-between">
                        <a href="/admin/dashboard.php" class="px-6 py-2 border border-slate-600 text-slate-300 rounded-md hover:bg-slate-700 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
