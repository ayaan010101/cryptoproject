<?php
$page_title = "Admin Login - CryptoTrade Platform";
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Check if user is already logged in as admin
if (is_logged_in() && is_admin()) {
    redirect('/admin/dashboard.php');
}

// Process login form
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !verify_token($_POST['csrf_token'])) {
        $error = 'Invalid form submission. Please try again.';
    } else {
        $username = clean_input($_POST['username']);
        $password = $_POST['password']; // Don't clean password as it might contain special characters
        
        if (empty($username) || empty($password)) {
            $error = 'Please enter both username and password.';
        } else {
            $result = login_user($username, $password);
            
            if ($result['success']) {
                // Check if user is admin
                if ($result['role'] === 'admin') {
                    redirect('/admin/dashboard.php');
                } else {
                    $error = 'You do not have admin privileges.';
                    // Log out the user since they're not an admin
                    logout_user();
                }
            } else {
                $error = $result['message'];
            }
        }
    }
}

require_once 'includes/header.php';
?>

<div class="min-h-screen bg-gray-100 py-20 px-4">
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6 sm:p-8">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">Admin Login</h1>
                    <p class="text-gray-600">Enter your credentials to access the admin panel</p>
                </div>
                
                <?php if ($error): ?>
                <div class="alert alert-danger mb-6">
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="" class="space-y-6 validate">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <div class="space-y-2">
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text" id="username" name="username" required 
                                class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="Enter admin username">
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" id="password" name="password" required 
                                class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="Enter admin password">
                            <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors">
                        Admin Sign In
                    </button>
                </form>
                
                <div class="mt-6 text-center">
                    <a href="login.php" class="text-sm text-gray-500 hover:underline">
                        Return to User Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle password visibility
    document.getElementById('toggle-password').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>

<?php require_once 'includes/footer.php'; ?>
