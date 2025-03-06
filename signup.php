<?php
$page_title = "Sign Up - CryptoTrade Platform";
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Check if user is already logged in
if (is_logged_in()) {
    // Redirect to dashboard
    if (is_admin()) {
        redirect('/admin/dashboard.php');
    } else {
        redirect('/dashboard.php');
    }
}

// Initialize variables
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$error = '';
$success = '';
$security_key = '';

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !verify_token($_POST['csrf_token'])) {
        $error = 'Invalid form submission. Please try again.';
    } else {
        // Handle different steps
        switch ($step) {
            case 1: // Personal details
                // Store personal details in session
                $_SESSION['signup_data'] = [
                    'first_name' => clean_input($_POST['first_name']),
                    'last_name' => clean_input($_POST['last_name']),
                    'email' => clean_input($_POST['email']),
                    'phone' => clean_input($_POST['phone'])
                ];
                
                // Validate required fields
                if (empty($_SESSION['signup_data']['first_name']) || 
                    empty($_SESSION['signup_data']['last_name']) || 
                    empty($_SESSION['signup_data']['email']) || 
                    empty($_SESSION['signup_data']['phone'])) {
                    $error = 'All fields are required.';
                } else {
                    // Proceed to next step
                    redirect('/signup.php?step=2');
                }
                break;
                
            case 2: // Credentials
                // Store credentials in session
                $_SESSION['signup_data']['username'] = clean_input($_POST['username']);
                $_SESSION['signup_data']['password'] = $_POST['password']; // Don't clean password
                $_SESSION['signup_data']['confirm_password'] = $_POST['confirm_password']; // Don't clean password
                
                // Validate required fields
                if (empty($_SESSION['signup_data']['username']) || 
                    empty($_SESSION['signup_data']['password']) || 
                    empty($_SESSION['signup_data']['confirm_password'])) {
                    $error = 'All fields are required.';
                } 
                // Check if passwords match
                elseif ($_SESSION['signup_data']['password'] !== $_SESSION['signup_data']['confirm_password']) {
                    $error = 'Passwords do not match.';
                }
                // Check password strength
                elseif (strlen($_SESSION['signup_data']['password']) < 8) {
                    $error = 'Password must be at least 8 characters long.';
                } else {
                    // Generate security key
                    $security_key = bin2hex(random_bytes(16));
                    $_SESSION['signup_data']['security_key'] = $security_key;
                    
                    // Proceed to next step
                    redirect('/signup.php?step=3');
                }
                break;
                
            case 3: // Complete registration
                if (!isset($_POST['accept_terms']) || $_POST['accept_terms'] !== '1') {
                    $error = 'You must accept the terms and conditions to continue.';
                } else {
                    // Register the user
                    $result = register_user(
                        $_SESSION['signup_data']['username'],
                        $_SESSION['signup_data']['email'],
                        $_SESSION['signup_data']['password'],
                        $_SESSION['signup_data']['first_name'] . ' ' . $_SESSION['signup_data']['last_name'],
                        $_SESSION['signup_data']['phone']
                    );
                    
                    if ($result['success']) {
                        // Clear signup data
                        unset($_SESSION['signup_data']);
                        
                        // Set success message
                        $success = 'Your account has been created successfully! You can now log in.';
                        
                        // Redirect to login page after a delay
                        header('Refresh: 3; URL=/login.php');
                    } else {
                        $error = $result['message'];
                    }
                }
                break;
        }
    }
}

// Get security key from session if on step 3
if ($step === 3 && isset($_SESSION['signup_data']['security_key'])) {
    $security_key = $_SESSION['signup_data']['security_key'];
}

require_once 'includes/header.php';
?>

<div class="min-h-screen bg-gray-100 py-20 px-4">
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6 sm:p-8">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">Create Your Account</h1>
                    <p class="text-gray-600">Join our crypto trading platform</p>
                </div>
                
                <!-- Progress Indicator -->
                <div class="mb-8">
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium">Step <?php echo $step; ?> of 3</span>
                        <span class="text-sm font-medium">
                            <?php 
                            echo $step === 1 ? 'Personal Details' : 
                                 ($step === 2 ? 'Create Credentials' : 'Security Key'); 
                            ?>
                        </span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-blue-600 rounded-full" style="width: <?php echo ($step / 3) * 100; ?>%"></div>
                    </div>
                    <div class="flex justify-between mt-2">
                        <div class="flex items-center <?php echo $step >= 1 ? 'text-blue-600' : 'text-gray-400'; ?>">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center <?php echo $step >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500'; ?>">
                                <?php echo $step > 1 ? '<i class="fas fa-check"></i>' : '<i class="fas fa-user"></i>'; ?>
                            </div>
                            <span class="ml-2 text-xs">Personal Details</span>
                        </div>
                        <div class="flex items-center <?php echo $step >= 2 ? 'text-blue-600' : 'text-gray-400'; ?>">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center <?php echo $step >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500'; ?>">
                                <?php echo $step > 2 ? '<i class="fas fa-check"></i>' : '<i class="fas fa-lock"></i>'; ?>
                            </div>
                            <span class="ml-2 text-xs">Credentials</span>
                        </div>
                        <div class="flex items-center <?php echo $step >= 3 ? 'text-blue-600' : 'text-gray-400'; ?>">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center <?php echo $step >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500'; ?>">
                                <i class="fas fa-key"></i>
                            </div>
                            <span class="ml-2 text-xs">Security Key</span>
                        </div>
                    </div>
                </div>
                
                <?php if ($error): ?>
                <div class="alert alert-danger mb-6">
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                <div class="alert alert-success mb-6">
                    <?php echo $success; ?>
                </div>
                <?php endif; ?>
                
                <!-- Step 1: Personal Details -->
                <?php if ($step === 1): ?>
                <form method="POST" action="" class="space-y-6 validate">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <div class="space-y-2">
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" id="first_name" name="first_name" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            placeholder="John"
                            value="<?php echo isset($_SESSION['signup_data']['first_name']) ? htmlspecialchars($_SESSION['signup_data']['first_name']) : ''; ?>">
                    </div>
                    
                    <div class="space-y-2">
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" id="last_name" name="last_name" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            placeholder="Doe"
                            value="<?php echo isset($_SESSION['signup_data']['last_name']) ? htmlspecialchars($_SESSION['signup_data']['last_name']) : ''; ?>">
                    </div>
                    
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            placeholder="john.doe@example.com"
                            value="<?php echo isset($_SESSION['signup_data']['email']) ? htmlspecialchars($_SESSION['signup_data']['email']) : ''; ?>">
                    </div>
                    
                    <div class="space-y-2">
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="tel" id="phone" name="phone" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            placeholder="+1 (555) 123-4567"
                            value="<?php echo isset($_SESSION['signup_data']['phone']) ? htmlspecialchars($_SESSION['signup_data']['phone']) : ''; ?>">
                    </div>
                    
                    <button type="submit" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors flex items-center justify-center">
                        Continue <i class="fas fa-chevron-right ml-2"></i>
                    </button>
                </form>
                <?php endif; ?>
                
                <!-- Step 2: Credentials -->
                <?php if ($step === 2): ?>
                <form method="POST" action="" class="space-y-6 validate">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <div class="space-y-2">
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" id="username" name="username" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            placeholder="johndoe"
                            value="<?php echo isset($_SESSION['signup_data']['username']) ? htmlspecialchars($_SESSION['signup_data']['username']) : ''; ?>">
                        <p class="text-xs text-gray-500 mt-1">This will be your login identifier</p>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="••••••••">
                            <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Must be at least 8 characters</p>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <div class="relative">
                            <input type="password" id="confirm_password" name="confirm_password" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="••••••••">
                            <button type="button" id="toggle-confirm-password" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex justify-between pt-2">
                        <a href="/signup.php?step=1" class="py-2 px-4 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 transition-colors">
                            Back
                        </a>
                        <button type="submit" class="py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors flex items-center">
                            Continue <i class="fas fa-chevron-right ml-2"></i>
                        </button>
                    </div>
                </form>
                <?php endif; ?>
                
                <!-- Step 3: Security Key -->
                <?php if ($step === 3): ?>
                <form method="POST" action="" class="space-y-6 validate">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <div class="p-4 border rounded-md bg-gray-100">
                        <h3 class="font-medium mb-2 flex items-center">
                            <i class="fas fa-key mr-2"></i> Your Security Key
                        </h3>
                        <p class="text-sm mb-4">
                            Please save this key in a secure location. You will need it for account recovery.
                        </p>
                        <div class="p-3 bg-blue-50 rounded border border-blue-200 font-mono text-sm break-all">
                            <?php echo $security_key; ?>
                        </div>
                    </div>
                    
                    <div class="p-4 border rounded-md bg-yellow-50 text-yellow-800 flex items-start">
                        <i class="fas fa-exclamation-circle mr-2 mt-0.5 flex-shrink-0"></i>
                        <div class="text-sm">
                            <p class="font-medium">Important:</p>
                            <p>
                                If you lose this security key, you will need to pay a fee in USDT to recover your account.
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3 space-y-0 rounded-md border p-4">
                        <input type="checkbox" id="accept_terms" name="accept_terms" value="1" class="h-4 w-4 mt-1">
                        <div class="space-y-1 leading-none">
                            <label for="accept_terms" class="text-sm font-medium text-gray-700">
                                I have saved my security key and accept the <a href="/terms.php" class="text-blue-600 hover:underline">terms and conditions</a>
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex justify-between pt-2">
                        <a href="/signup.php?step=2" class="py-2 px-4 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 transition-colors">
                            Back
                        </a>
                        <button type="submit" class="py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors">
                            Complete Registration
                        </button>
                    </div>
                </form>
                <?php endif; ?>
                
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account? 
                        <a href="login.php" class="text-blue-600 hover:underline font-medium">Sign in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle password visibility
    document.getElementById('toggle-password')?.addEventListener('click', function() {
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
    
    document.getElementById('toggle-confirm-password')?.addEventListener('click', function() {
        const passwordInput = document.getElementById('confirm_password');
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
