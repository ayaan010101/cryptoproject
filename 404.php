<?php
$page_title = "404 - Page Not Found";
$body_class = "error-page";
require_once 'includes/header.php';
?>

<div class="container mx-auto px-4 py-20">
    <div class="max-w-md mx-auto text-center">
        <h1 class="text-6xl font-bold text-blue-600 mb-4">404</h1>
        <h2 class="text-2xl font-semibold mb-4">Page Not Found</h2>
        <p class="text-gray-600 mb-8">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
        <a href="/" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">Go to Homepage</a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
