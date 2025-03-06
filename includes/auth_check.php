<?php
require_once 'config.php';
require_once 'functions.php';

// Check if user is logged in
if (!is_logged_in()) {
    // Store the requested URL for redirection after login
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    
    // Redirect to login page
    redirect('/login.php');
}
?>