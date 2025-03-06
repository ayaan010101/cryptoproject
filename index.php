<?php
$page_title = "CryptoTrade - Next-Gen Crypto Trading Platform";
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="w-full py-20 px-4 md:px-8 lg:px-16 bg-gradient-to-br from-slate-900 to-slate-800 text-white">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-12">
        <div class="flex-1 space-y-8">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight">
                Next-Gen Crypto Trading Platform
            </h1>
            <p class="text-lg md:text-xl text-slate-300 max-w-2xl">
                Trade cryptocurrencies with confidence using our secure, fast, and intuitive platform. Real-time updates, advanced security, and seamless transactions.
            </p>

            <div class="flex flex-wrap gap-4 pt-4">
                <a href="/signup.php" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors">
                    Get Started
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <a href="#features" class="inline-flex items-center justify-center px-6 py-3 border border-white text-white hover:bg-white/10 font-medium rounded-md transition-colors">
                    Learn More
                </a>
            </div>

            <div class="flex flex-wrap gap-8 pt-8">
                <div class="flex items-center gap-2">
                    <div class="p-2 rounded-full bg-blue-600/20">
                        <i class="fas fa-chart-line text-blue-500"></i>
                    </div>
                    <span class="text-sm md:text-base">Real-time Updates</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="p-2 rounded-full bg-blue-600/20">
                        <i class="fas fa-shield-alt text-blue-500"></i>
                    </div>
                    <span class="text-sm md:text-base">Advanced Security</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="p-2 rounded-full bg-blue-600/20">
                        <i class="fas fa-wallet text-blue-500"></i>
                    </div>
                    <span class="text-sm md:text-base">Multi-currency Support</span>
                </div>
            </div>
        </div>

        <div class="flex-1 relative">
            <div class="relative z-10 bg-slate-800 p-4 rounded-xl shadow-2xl border border-slate-700 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1639762681057-408e52192e55?w=800&q=80" alt="Crypto trading dashboard" class="rounded-lg w-full h-auto">
                <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-t from-slate-900/80 to-transparent rounded-lg flex items-end p-6">
                    <div class="text-white">
                        <p class="font-medium">Live Trading Dashboard</p>
                        <p class="text-sm text-slate-300">Real-time updates every 30 seconds</p>
                    </div>
                </div>
            </div>

            <div class="absolute -top-6 -right-6 w-32 h-32 bg-blue-600/30 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl"></div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-16 px-4 bg-gray-50">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">Platform Features</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Everything you need to trade cryptocurrencies securely and efficiently</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="flex flex-col items-center p-6 bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="p-3 bg-blue-100 rounded-full mb-4">
                    <i class="fab fa-bitcoin text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2 text-center">Multi-Currency Support</h3>
                <p class="text-gray-500 text-center text-sm">Trade Bitcoin, Ethereum, and USDT with seamless wallet integration and real-time balance updates.</p>
            </div>

            <!-- Feature 2 -->
            <div class="flex flex-col items-center p-6 bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="p-3 bg-blue-100 rounded-full mb-4">
                    <i class="fas fa-shield-alt text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2 text-center">Secure Authentication</h3>
                <p class="text-gray-500 text-center text-sm">Advanced security with unique recovery keys and multi-factor authentication to protect your assets.</p>
            </div>

            <!-- Feature 3 -->
            <div class="flex flex-col items-center p-6 bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="p-3 bg-blue-100 rounded-full mb-4">
                    <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2 text-center">Real-Time Trading</h3>
                <p class="text-gray-500 text-center text-sm">Live market data with 30-second updates and advanced charting tools for informed trading decisions.</p>
            </div>

            <!-- Feature 4 -->
            <div class="flex flex-col items-center p-6 bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="p-3 bg-blue-100 rounded-full mb-4">
                    <i class="fas fa-wallet text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2 text-center">Integrated Wallets</h3>
                <p class="text-gray-500 text-center text-sm">Manage all your cryptocurrency assets in one place with easy deposit and withdrawal functionality.</p>
            </div>

            <!-- Feature 5 -->
            <div class="flex flex-col items-center p-6 bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="p-3 bg-blue-100 rounded-full mb-4">
                    <i class="fas fa-clock text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2 text-center">24/7 Market Access</h3>
                <p class="text-gray-500 text-center text-sm">Trade anytime with continuous market access and instant transaction processing.</p>
            </div>

            <!-- Feature 6 -->
            <div class="flex flex-col items-center p-6 bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="p-3 bg-blue-100 rounded-full mb-4">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2 text-center">User-Friendly Interface</h3>
                <p class="text-gray-500 text-center text-sm">Intuitive platform design suitable for both beginners and experienced traders.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 px-4 bg-blue-600 text-white">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Start Trading?</h2>
        <p class="text-xl mb-8">Join thousands of traders on our platform and experience the future of cryptocurrency trading.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="/signup.php" class="px-8 py-3 bg-white text-blue-600 font-medium rounded-md hover:bg-gray-100 transition-colors">Create Account</a>
            <a href="/login.php" class="px-8 py-3 border border-white text-white font-medium rounded-md hover:bg-white/10 transition-colors">Sign In</a>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-16 px-4 bg-gray-100">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">What Our Users Say</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Hear from traders who have experienced our platform</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Testimonial 1 -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                        <span class="text-blue-600 font-bold">JD</span>
                    </div>
                    <div>
                        <h4 class="font-semibold">John Doe</h4>
                        <p class="text-sm text-gray-500">Trader since 2021</p>
                    </div>
                </div>
                <p class="text-gray-600">"The real-time updates and intuitive interface make trading on this platform a breeze. I've tried many platforms, but this one stands out for its security features."</p>
                <div class="mt-4 flex text-yellow-400">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                        <span class="text-blue-600 font-bold">JS</span>
                    </div>
                    <div>
                        <h4 class="font-semibold">Jane Smith</h4>
                        <p class="text-sm text-gray-500">Investor since 2020</p>
                    </div>
                </div>
                <p class="text-gray-600">"The security key recovery system gives me peace of mind. I know my investments are safe, and the platform's performance is consistently reliable."</p>
                <div class="mt-4 flex text-yellow-400">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
            </div>

            <!-- Testimonial 3 -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                        <span class="text-blue-600 font-bold">MW</span>
                    </div>
                    <div>
                        <h4 class="font-semibold">Mike Wilson</h4>
                        <p class="text-sm text-gray-500">Day Trader</p>
                    </div>
                </div>
                <p class="text-gray-600">"As a day trader, I need reliable tools and fast execution. This platform delivers on both fronts, with excellent charting capabilities and quick transactions."</p>
                <div class="mt-4 flex text-yellow-400">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
