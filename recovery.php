<?php
$page_title = "Account Recovery - CryptoTrade Platform";
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Initialize variables
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$error = '';
$success = '';
$user_id = isset($_SESSION['recovery_user_id']) ? $_SESSION['recovery_user_id'] : 0;

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !verify_token($_POST['csrf_token'])) {
        $error = 'Invalid form submission. Please try again.';
    } else {
        // Handle different steps
        switch ($step) {
            case 1: // Email verification
                $email = clean_input($_POST['email']);
                
                if (empty($email)) {
                    $error = 'Email address is required.';
                } else {
                    $result = recover_security_key($email);
                    
                    if ($result['success']) {
                        // Store user ID in session
                        $_SESSION['recovery_user_id'] = $result['user_id'];
                        $user_id = $result['user_id'];
                        
                        // Proceed to next step
                        redirect('/recovery.php?step=2');
                    } else {
                        $error = $result['message'];
                    }
                }
                break;
                
            case 2: // Payment verification
                $payment_amount = isset($_POST['payment_amount']) ? (float)$_POST['payment_amount'] : 0;
                
                if ($payment_amount < 10) {
                    $error = 'Payment must be at least 10 USDT.';
                } else {
                    // In a real application, you would verify the payment here
                    // For demo purposes, we'll just proceed to the next step
                    
                    // Store payment amount in session
                    $_SESSION['recovery_payment'] = $payment_amount;
                    
                    // Proceed to next step
                    redirect('/recovery.php?step=3');
                }
                break;
                
            case 3: // Complete recovery
                if (!$user_id) {
                    $error = 'Invalid recovery session. Please start over.';
                } else {
                    $payment_amount = isset($_SESSION['recovery_payment']) ? $_SESSION['recovery_payment'] : 300;
                    
                    // Generate new security key
                    $result = update_security_key($user_id, $payment_amount);
                    
                    if ($result['success']) {
                        // Clear recovery data
                        unset($_SESSION['recovery_user_id']);
                        unset($_SESSION['recovery_payment']);
                        
                        // Set success message with the new security key
                        $success = 'Your security key has been reset successfully! Your new key is: <strong>' . $result['security_key'] . '</strong><br>Please save this key in a secure location.';
                        
                        // Redirect to login page after a delay
                        header('Refresh: 10; URL=/login.php');
                    } else {
                        $error = $result['message'];
                    }
                }
                break;
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
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">Security Key Recovery</h1>
                    <p class="text-gray-600">Recover your security key by completing the verification process</p>
                </div>
                
                <!-- Progress Indicator -->
                <div class="mb-8">
                    <div class="h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-blue-600 rounded-full" style="width: <?php echo ($step / 3) * 100; ?>%"></div>
                    </div>
                    <div class="flex justify-between mt-1 text-xs text-gray-500">
                        <span>Email</span>
                        <span>Payment</span>
                        <span>Complete</span>
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
                
                <!-- Step 1: Email Verification -->
                <?php if ($step === 1 && !$success): ?>
                <form method="POST" action="" class="space-y-6 validate">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" id="email" name="email" required 
                                class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="your.email@example.com">
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            <div>
                                <h4 class="font-semibold">Verification Required</h4>
                                <p class="text-sm">We'll send a verification code to this email to confirm your identity.</p>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors flex items-center justify-center">
                        Continue <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </form>
                <?php endif; ?>
                
                <!-- Step 2: Payment -->
                <?php if ($step === 2 && !$success): ?>
                <form method="POST" action="" class="space-y-6 validate">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <div class="alert alert-warning mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <div>
                                <h4 class="font-semibold">Security Notice</h4>
                                <p class="text-sm">For your protection, key recovery requires a USDT payment.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="payment_amount" class="block text-sm font-medium text-gray-700">USDT Payment Amount</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-credit-card text-gray-400"></i>
                            </div>
                            <input type="number" id="payment_amount" name="payment_amount" required min="10" 
                                class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="10" value="300">
                        </div>
                    </div>
                    
                    <div class="p-4 border rounded-md bg-gray-100">
                        <h3 class="font-medium mb-2">Payment Instructions</h3>
                        <p class="text-sm mb-4">Send the USDT payment to the following address:</p>
                        <div class="p-3 bg-white rounded border border-gray-300 font-mono text-sm break-all mb-4">
                            0xd8dA6BF26964aF9D7eEd9e03E53415D37aA96045
                        </div>
                        <p class="text-sm text-gray-600">After sending the payment, click the button below to continue.</p>
                    </div>
                    
                    <div class="alert alert-info">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            <div>
                                <h4 class="font-semibold">Payment Information</h4>
                                <p class="text-sm">A minimum payment of 10 USDT is required to generate a new security key.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between pt-2">
                        <a href="/recovery.php?step=1" class="py-2 px-4 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 transition-colors">
                            Back
                        </a>
                        <button type="submit" class="py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors flex items-center">
                            Verify Payment <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>
                <?php endif; ?>
                
                <!-- Step 3: Complete Recovery -->
                <?php if ($step === 3 && !$success): ?>
                <form method="POST" action="" class="space-y-6 validate">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <div class="text-center space-y-4">
                        <div class="p-4 rounded-full bg-green-100 mx-auto w-16 h-16 flex items-center justify-center">
                            <i class="fas fa-shield-alt text-green-600 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium">Ready to Generate New Key</h3>
                        <p class="text-gray-500">
                            Your payment has been verified. Click the button below to generate a new security key.
                        </p>
                    </div>
                    
                    <div class="alert alert-info">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            <div>
                                <h4 class="font-semibold">Important</h4>
                                <p class="text-sm">Your new security key will be displayed on the next screen. Make sure to save it in a secure location.</p>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors">
                        Generate New Security Key
                    </button>
                </form>
                <?php endif; ?>
                
                <?php if ($success): ?>
                <div class="text-center mt-6">
                    <a href="/login.php" class="py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors inline-block">
                        Go to Login
                    </a>
                </div>
                <?php endif; ?>
                
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Need help? Contact our support team at <a href="mailto:support@cryptoplatform.com" class="text-blue-600 hover:underline">support@cryptoplatform.com</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
