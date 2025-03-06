    <footer class="w-full bg-white border-t py-12 px-6 md:px-12">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold">Crypto Trading Platform</h3>
                    <p class="text-sm text-gray-500">
                        Your trusted platform for cryptocurrency trading and investment.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-blue-600 transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-blue-600 transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-blue-600 transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-blue-600 transition-colors">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-blue-600 transition-colors">
                            <i class="fab fa-github"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold">Quick Links</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="/" class="text-sm text-gray-500 hover:text-blue-600 transition-colors">Home</a>
                        </li>
                        <li>
                            <a href="/about.php" class="text-sm text-gray-500 hover:text-blue-600 transition-colors">About Us</a>
                        </li>
                        <li>
                            <a href="/features.php" class="text-sm text-gray-500 hover:text-blue-600 transition-colors">Services</a>
                        </li>
                        <li>
                            <a href="/markets.php" class="text-sm text-gray-500 hover:text-blue-600 transition-colors">Market Overview</a>
                        </li>
                        <li>
                            <a href="/contact.php" class="text-sm text-gray-500 hover:text-blue-600 transition-colors">Contact</a>
                        </li>
                    </ul>
                </div>

                <!-- Resources -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold">Resources</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="/blog.php" class="text-sm text-gray-500 hover:text-blue-600 transition-colors">Blog</a>
                        </li>
                        <li>
                            <a href="/faq.php" class="text-sm text-gray-500 hover:text-blue-600 transition-colors">FAQ</a>
                        </li>
                        <li>
                            <a href="/security.php" class="text-sm text-gray-500 hover:text-blue-600 transition-colors">Security</a>
                        </li>
                        <li>
                            <a href="/privacy.php" class="text-sm text-gray-500 hover:text-blue-600 transition-colors">Privacy Policy</a>
                        </li>
                        <li>
                            <a href="/terms.php" class="text-sm text-gray-500 hover:text-blue-600 transition-colors">Terms of Service</a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold">Contact Us</h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt w-5 h-5 mr-2 text-gray-400"></i>
                            <span class="text-sm text-gray-500">
                                123 Blockchain Street, Crypto City, CC 12345
                            </span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone w-5 h-5 mr-2 text-gray-400"></i>
                            <span class="text-sm text-gray-500">
                                +1 (555) 123-4567
                            </span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope w-5 h-5 mr-2 text-gray-400"></i>
                            <span class="text-sm text-gray-500">
                                support@cryptoplatform.com
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-8 border-gray-200">

            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-sm text-gray-500">
                    &copy; <?php echo date('Y'); ?> Crypto Trading Platform. All rights reserved.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="/privacy.php" class="text-xs text-gray-500 hover:text-blue-600 transition-colors">Privacy Policy</a>
                    <a href="/terms.php" class="text-xs text-gray-500 hover:text-blue-600 transition-colors">Terms of Service</a>
                    <a href="/cookies.php" class="text-xs text-gray-500 hover:text-blue-600 transition-colors">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript Files -->
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/main.js"></script>
    <?php if (isset($page_scripts)): ?>
    <script>
        <?php echo $page_scripts; ?>
    </script>
    <?php endif; ?>
</body>
</html>