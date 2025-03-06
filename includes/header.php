<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . $site_name : $site_name; ?></title>
    <link rel="icon" type="image/svg+xml" href="/assets/images/favicon.svg">
    <!-- CSS Files -->
    <link rel="stylesheet" href="/assets/css/tailwind.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom page styles -->
    <?php if (isset($page_styles)): ?>
    <style>
        <?php echo $page_styles; ?>
    </style>
    <?php endif; ?>
</head>
<body class="<?php echo isset($body_class) ? $body_class : ''; ?>">
    <?php if (!isset($hide_navbar) || !$hide_navbar): ?>
    <nav class="w-full h-20 bg-white border-b border-gray-200 shadow-sm fixed top-0 left-0 z-50">
        <div class="container mx-auto h-full px-4 flex items-center justify-between">
            <!-- Logo and Platform Name -->
            <a href="/" class="flex items-center space-x-2">
                <i class="fa-brands fa-bitcoin text-blue-600 text-2xl"></i>
                <span class="font-bold text-xl">CryptoTrade</span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <div class="flex items-center space-x-6">
                    <a href="/" class="text-gray-700 hover:text-blue-600 transition-colors">Home</a>
                    <a href="/markets.php" class="text-gray-700 hover:text-blue-600 transition-colors">Markets</a>
                    <a href="/features.php" class="text-gray-700 hover:text-blue-600 transition-colors">Features</a>
                    <a href="/about.php" class="text-gray-700 hover:text-blue-600 transition-colors">About</a>
                </div>

                <div class="flex items-center space-x-3">
                    <?php if (is_logged_in()): ?>
                        <?php if (is_admin()): ?>
                            <a href="/admin/dashboard.php" class="px-4 py-2 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 transition-colors">Admin Panel</a>
                        <?php else: ?>
                            <a href="/dashboard.php" class="px-4 py-2 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 transition-colors">Dashboard</a>
                        <?php endif; ?>
                        <a href="/logout.php" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">Logout</a>
                    <?php else: ?>
                        <a href="/login.php" class="px-4 py-2 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 transition-colors">Login</a>
                        <a href="/signup.php" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="p-2 rounded-md text-gray-700 hover:bg-gray-100">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden absolute top-20 left-0 w-full bg-white border-b border-gray-200 shadow-md py-4 px-4">
            <div class="flex flex-col space-y-4">
                <a href="/" class="text-gray-700 hover:text-blue-600 transition-colors py-2">Home</a>
                <a href="/markets.php" class="text-gray-700 hover:text-blue-600 transition-colors py-2">Markets</a>
                <a href="/features.php" class="text-gray-700 hover:text-blue-600 transition-colors py-2">Features</a>
                <a href="/about.php" class="text-gray-700 hover:text-blue-600 transition-colors py-2">About</a>
                <div class="flex flex-col space-y-2 pt-2 border-t border-gray-100">
                    <?php if (is_logged_in()): ?>
                        <?php if (is_admin()): ?>
                            <a href="/admin/dashboard.php" class="w-full py-2 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 transition-colors text-center">Admin Panel</a>
                        <?php else: ?>
                            <a href="/dashboard.php" class="w-full py-2 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 transition-colors text-center">Dashboard</a>
                        <?php endif; ?>
                        <a href="/logout.php" class="w-full py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors text-center">Logout</a>
                    <?php else: ?>
                        <a href="/login.php" class="w-full py-2 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 transition-colors text-center">Login</a>
                        <a href="/signup.php" class="w-full py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-center">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <?php endif; ?>