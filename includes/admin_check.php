<?php
require_once 'config.php';
require_once 'functions.php';

// Check if user is logged in and is an admin
if (!is_logged_in() || !is_admin()) {
    // Redirect to login page
    redirect('/login.php');
}
?>