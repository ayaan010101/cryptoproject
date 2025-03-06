<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Redirect to appropriate dashboard if already logged in
if ($isLoggedIn) {
    if ($isAdmin) {
        header('Location: admin/dashboard.php');
        exit;
    } else {
        header('Location: dashboard.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CryptoTrade - Cryptocurrency Trading Platform</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-logo">
                <i class="fa-brands fa-bitcoin"></i>
                <span class="logo-text">CryptoTrade</span>
            </div>
            <div class="navbar-menu" id="navbar-menu">
                <a href="#" class="active">Home</a>
                <a href="#features">Features</a>
                <a href="#">Markets</a>
                <a href="#">About</a>
            </div>
            <div class="navbar-actions">
                <button class="btn btn-outline" id="login-btn">Login</button>
                <button class="btn btn-primary" id="signup-btn">Sign Up</button>
                <button class="navbar-toggle" id="navbar-toggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Next-Gen Crypto Trading Platform</h1>
                <p>Trade cryptocurrencies with confidence using our secure, fast, and intuitive platform. Real-time updates, advanced security, and seamless transactions.</p>
                <div class="hero-buttons">
                    <button class="btn btn-primary btn-lg" id="get-started-btn">
                        Get Started
                        <i class="fas fa-arrow-right"></i>
                    </button>
                    <button class="btn btn-outline btn-lg" id="learn-more-btn">Learn More</button>
                </div>
                <div class="hero-features">
                    <div class="hero-feature">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span>Real-time Updates</span>
                    </div>
                    <div class="hero-feature">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <span>Advanced Security</span>
                    </div>
                    <div class="hero-feature">
                        <div class="feature-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <span>Multi-currency Support</span>
                    </div>
                </div>
            </div>
            <div class="hero-image">
                <div class="image-container">
                    <img src="assets/images/dashboard-preview.jpg" alt="Crypto trading dashboard">
                    <div class="image-overlay">
                        <div class="overlay-content">
                            <p class="overlay-title">Live Trading Dashboard</p>
                            <p class="overlay-subtitle">Real-time updates every 30 seconds</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-header">
                <h2>Platform Features</h2>
                <p>Everything you need to trade cryptocurrencies securely and efficiently</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-brands fa-bitcoin"></i>
                    </div>
                    <h3>Multi-Currency Support</h3>
                    <p>Trade Bitcoin, Ethereum, and USDT with seamless wallet integration and real-time balance updates.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Secure Authentication</h3>
                    <p>Advanced security with unique recovery keys and multi-factor authentication to protect your assets.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Real-Time Trading</h3>
                    <p>Live market data with 30-second updates and advanced charting tools for informed trading decisions.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <h3>Integrated Wallets</h3>
                    <p>Manage all your cryptocurrency assets in one place with easy deposit and withdrawal functionality.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>24/7 Market Access</h3>
                    <p>Trade anytime with continuous market access and instant transaction processing.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>User-Friendly Interface</h3>
                    <p>Intuitive platform design suitable for both beginners and experienced traders.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h3>Crypto Trading Platform</h3>
                    <p>Your trusted platform for cryptocurrency trading and investment.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-github"></i></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">Market Overview</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>Resources</h3>
                    <ul>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Security</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>Contact Us</h3>
                    <div class="contact-info">
                        <p><i class="fas fa-map-marker-alt"></i> 123 Blockchain Street, Crypto City, CC 12345</p>
                        <p><i class="fas fa-phone"></i> +1 (555) 123-4567</p>
                        <p><i class="fas fa-envelope"></i> support@cryptoplatform.com</p>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="copyright">
                    <p>&copy; <?php echo date('Y'); ?> Crypto Trading Platform. All rights reserved.</p>
                </div>
                <div class="footer-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Auth Modal -->
    <div class="modal" id="auth-modal">
        <div class="modal-content">
            <button class="modal-close" id="modal-close">
                <i class="fas fa-times"></i>
            </button>
            <div class="auth-tabs">
                <button class="auth-tab active" data-tab="login">Login</button>
                <button class="auth-tab" data-tab="signup">Sign Up</button>
                <button class="auth-tab" data-tab="recovery">Recovery</button>
            </div>
            
            <!-- Login Form -->
            <div class="auth-form active" id="login-form">
                <h2>Login to Your Account</h2>
                <p class="form-subtitle">Enter your credentials to access your account</p>
                <form action="includes/auth.php" method="post">
                    <input type="hidden" name="action" value="login">
                    <div class="form-group">
                        <label for="login-username">Username</label>
                        <div class="input-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" id="login-username" name="username" placeholder="Enter your username" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="login-password">Password</label>
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="login-password" name="password" placeholder="Enter your password" required>
                            <button type="button" class="toggle-password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button type="button" class="btn-link" id="forgot-password-btn">Forgot password?</button>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                </form>
                <div class="form-footer">
                    <p>Don't have an account? <button type="button" class="btn-link switch-form" data-form="signup">Sign up</button></p>
                    <div class="admin-login-link">
                        <a href="admin/login.php" class="btn btn-outline btn-sm">Admin Login</a>
                    </div>
                </div>
            </div>
            
            <!-- Signup Form -->
            <div class="auth-form" id="signup-form">
                <h2>Create Account</h2>
                <p class="form-subtitle">Join our platform to start trading</p>
                <div class="signup-progress">
                    <div class="progress-step active" data-step="1">
                        <div class="step-number">1</div>
                        <span>Personal Details</span>
                    </div>
                    <div class="progress-step" data-step="2">
                        <div class="step-number">2</div>
                        <span>Credentials</span>
                    </div>
                    <div class="progress-step" data-step="3">
                        <div class="step-number">3</div>
                        <span>Security Key</span>
                    </div>
                </div>
                <form action="includes/auth.php" method="post" id="signup-multi-form">
                    <input type="hidden" name="action" value="signup">
                    
                    <!-- Step 1: Personal Details -->
                    <div class="signup-step active" data-step="1">
                        <div class="form-group">
                            <label for="first-name">First Name</label>
                            <input type="text" id="first-name" name="first_name" placeholder="John" required>
                        </div>
                        <div class="form-group">
                            <label for="last-name">Last Name</label>
                            <input type="text" id="last-name" name="last_name" placeholder="Doe" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="john.doe@example.com" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" placeholder="+1 (555) 123-4567" required>
                        </div>
                        <button type="button" class="btn btn-primary btn-block next-step" data-step="1">
                            Continue <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    
                    <!-- Step 2: Credentials -->
                    <div class="signup-step" data-step="2">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" placeholder="johndoe" required>
                            <small>This will be your login identifier</small>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="••••••••" required>
                            <small>Must be at least 8 characters</small>
                        </div>
                        <div class="form-group">
                            <label for="confirm-password">Confirm Password</label>
                            <input type="password" id="confirm-password" name="confirm_password" placeholder="••••••••" required>
                        </div>
                        <div class="form-buttons">
                            <button type="button" class="btn btn-outline prev-step" data-step="2">Back</button>
                            <button type="button" class="btn btn-primary next-step" data-step="2">
                                Continue <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Step 3: Security Key -->
                    <div class="signup-step" data-step="3">
                        <div class="security-key-box">
                            <h3><i class="fas fa-key"></i> Your Security Key</h3>
                            <p>Please save this key in a secure location. You will need it for account recovery.</p>
                            <div class="security-key" id="security-key"></div>
                        </div>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle"></i>
                            <div>
                                <p class="alert-title">Important:</p>
                                <p>If you lose this security key, you will need to pay a fee in USDT to recover your account.</p>
                            </div>
                        </div>
                        <div class="form-group checkbox-group">
                            <input type="checkbox" id="accept-terms" name="accept_terms" required>
                            <label for="accept-terms">I have saved my security key and accept the terms and conditions</label>
                        </div>
                        <div class="form-buttons">
                            <button type="button" class="btn btn-outline prev-step" data-step="3">Back</button>
                            <button type="submit" class="btn btn-primary">Complete Registration</button>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Recovery Form -->
            <div class="auth-form" id="recovery-form">
                <h2>Security Key Recovery</h2>
                <p class="form-subtitle">Recover your security key by completing the verification process</p>
                <div class="recovery-progress">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 25%;"></div>
                    </div>
                    <div class="progress-labels">
                        <span>Email</span>
                        <span>Verify</span>
                        <span>Payment</span>
                        <span>Complete</span>
                    </div>
                </div>
                <form action="includes/auth.php" method="post" id="recovery-multi-form">
                    <input type="hidden" name="action" value="recover">
                    
                    <!-- Step 1: Email -->
                    <div class="recovery-step active" data-step="1">
                        <div class="form-group">
                            <label for="recovery-email">Email Address</label>
                            <div class="input-icon">
                                <i class="fas fa-envelope"></i>
                                <input type="email" id="recovery-email" name="email" placeholder="your.email@example.com" required>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <div>
                                <p class="alert-title">Verification Required</p>
                                <p>We'll send a verification code to this email to confirm your identity.</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary btn-block next-recovery-step" data-step="1">
                            Continue <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                    
                    <!-- Step 2: Verification -->
                    <div class="recovery-step" data-step="2">
                        <div class="form-group">
                            <label for="verification-code">Identity Verification Code</label>
                            <div class="input-icon">
                                <i class="fas fa-shield-alt"></i>
                                <input type="text" id="verification-code" name="verification_code" placeholder="Enter verification code" required>
                            </div>
                        </div>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle"></i>
                            <div>
                                <p class="alert-title">Security Notice</p>
                                <p>For your protection, key recovery requires a USDT payment in the next step.</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary btn-block next-recovery-step" data-step="2">
                            Continue <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                    
                    <!-- Step 3: Payment -->
                    <div class="recovery-step" data-step="3">
                        <div class="form-group">
                            <label for="usdt-amount">USDT Payment Amount</label>
                            <div class="input-icon">
                                <i class="fas fa-credit-card"></i>
                                <input type="number" id="usdt-amount" name="usdt_amount" min="10" placeholder="10" value="10" required>
                            </div>
                        </div>
                        <div class="alert alert-success">
                            <i class="fas fa-info-circle"></i>
                            <div>
                                <p class="alert-title">Payment Information</p>
                                <p>A minimum payment of 10 USDT is required to generate a new security key.</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary btn-block next-recovery-step" data-step="3">
                            Make Payment <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                    
                    <!-- Step 4: Complete -->
                    <div class="recovery-step" data-step="4">
                        <div class="recovery-success">
                            <div class="success-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h3>Recovery Successful!</h3>
                            <p>Your new security key has been generated and sent to your email address.</p>
                            <button type="submit" class="btn btn-primary btn-block">Complete Recovery</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>