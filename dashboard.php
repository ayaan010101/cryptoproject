<?php
$page_title = "Dashboard - CryptoTrade Platform";
$body_class = "dashboard-page";

// Check if user is logged in
require_once 'includes/auth_check.php';

// Get user data
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$full_name = $_SESSION['full_name'];

// Get user wallets
$wallets = get_user_wallets($user_id);

// Get recent transactions
$transactions = get_user_transactions($user_id, 5);

// Get coin data
$coins = get_all_coins();

require_once 'includes/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-slate-900 via-indigo-900 to-purple-900 text-white bg-opacity-90 pt-24 pb-12 px-4">
    <!-- Header with user info -->
    <header class="container mx-auto mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold">Welcome, <?php echo htmlspecialchars($full_name); ?></h1>
                <p class="text-slate-300">Your personal dashboard</p>
            </div>
            <div class="mt-4 md:mt-0 flex items-center space-x-4">
                <button id="refresh-data" class="p-2 rounded-full bg-slate-800/50 hover:bg-slate-700/50 transition-colors">
                    <i class="fas fa-sync-alt"></i>
                </button>
                <div class="text-right">
                    <p class="text-sm text-slate-300">Total Balance</p>
                    <p class="font-bold text-xl">
                        $<?php 
                            $total = 0;
                            foreach ($wallets as $wallet) {
                                $total += $wallet['value'];
                            }
                            echo number_format($total, 2);
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main content -->
    <main class="container mx-auto">
        <!-- Tabs navigation -->
        <div class="tabs-container mb-8">
            <div class="flex border-b border-slate-700">
                <button class="tab-trigger active-tab px-6 py-3 font-medium text-white border-b-2 border-blue-500" data-tab="overview">Overview</button>
                <button class="tab-trigger px-6 py-3 font-medium text-slate-300 hover:text-white" data-tab="wallet">Wallet</button>
                <button class="tab-trigger px-6 py-3 font-medium text-slate-300 hover:text-white" data-tab="market">Market</button>
                <button class="tab-trigger px-6 py-3 font-medium text-slate-300 hover:text-white" data-tab="transactions">Transactions</button>
            </div>
        </div>

        <!-- Overview Tab -->
        <div class="tab-content" data-tab="overview">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Market Overview Card -->
                <div class="card dashboard-card bg-slate-800/80 backdrop-blur-sm border-indigo-700/50 col-span-1 md:col-span-2 shadow-xl shadow-indigo-900/20">
                    <div class="card-header flex flex-row items-center justify-between pb-2">
                        <div>
                            <h2 class="text-xl font-bold">Market Overview</h2>
                            <p class="text-sm text-slate-400">Real-time price updates every 30 seconds</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="h-[300px] w-full bg-slate-900 rounded-md p-4 relative overflow-hidden">
                            <div class="absolute inset-0 w-full h-full opacity-20">
                                <img src="https://images.unsplash.com/photo-1642790551116-18e150f248e5?w=800&q=80" alt="Trading Chart Background" class="w-full h-full object-cover">
                            </div>
                            <div class="relative z-10 h-full w-full flex flex-col">
                                <div class="flex justify-between mb-4">
                                    <div>
                                        <h3 class="font-bold text-lg">BTC/USD</h3>
                                        <p class="text-green-500 font-medium">
                                            $<?php echo number_format($coins[0]['price'], 2); ?>
                                            <span class="text-xs ml-1">+<?php echo $coins[0]['change24h']; ?>%</span>
                                        </p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button class="px-3 py-1 text-sm border border-indigo-800 rounded-md text-indigo-300 hover:bg-indigo-900/30 transition-colors">1H</button>
                                        <button class="px-3 py-1 text-sm border border-indigo-800 rounded-md bg-indigo-900/40 text-indigo-300">1D</button>
                                        <button class="px-3 py-1 text-sm border border-indigo-800 rounded-md text-indigo-300 hover:bg-indigo-900/30 transition-colors">1W</button>
                                        <button class="px-3 py-1 text-sm border border-indigo-800 rounded-md text-indigo-300 hover:bg-indigo-900/30 transition-colors">1M</button>
                                    </div>
                                </div>
                                <div class="flex-1 relative">
                                    <canvas id="trading-chart" width="800" height="200" class="w-full h-full"></canvas>
                                    <div class="absolute bottom-0 left-0 right-0 h-1/3 bg-gradient-to-t from-slate-900 to-transparent"></div>
                                </div>
                                <div class="mt-2 flex justify-between text-xs text-slate-400">
                                    <span>09:00</span>
                                    <span>12:00</span>
                                    <span>15:00</span>
                                    <span>18:00</span>
                                    <span>21:00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Portfolio Balance Card -->
                <div class="card dashboard-card bg-slate-800/80 backdrop-blur-sm border-indigo-700/50 shadow-xl shadow-indigo-900/20">
                    <div class="card-header">
                        <h2 class="text-xl font-bold">Portfolio Balance</h2>
                        <p class="text-sm text-slate-400">Your current holdings</p>
                    </div>
                    <div class="card-body space-y-6">
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-slate-400">Total Balance</span>
                                <span class="text-2xl font-bold text-indigo-300">$<?php echo number_format($total, 2); ?></span>
                            </div>
                            <div class="h-2 bg-slate-700 rounded-full">
                                <div class="h-2 bg-blue-600 rounded-full" style="width: 100%"></div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <?php foreach ($wallets as $coin => $data): ?>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="crypto-icon crypto-icon-<?php echo strtolower($coin); ?>">
                                        <?php echo substr($coin, 0, 1); ?>
                                    </div>
                                    <div>
                                        <p class="font-medium"><?php echo $coin; ?></p>
                                        <p class="text-xs text-slate-400"><?php echo $data['balance']; ?> <?php echo $coin; ?></p>
                                    </div>
                                </div>
                                <p class="font-medium">$<?php echo number_format($data['value'], 2); ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <!-- Quick Actions Card -->
                <div class="card dashboard-card bg-slate-800/80 backdrop-blur-sm border-indigo-700/50 shadow-xl shadow-indigo-900/20">
                    <div class="card-header">
                        <h2 class="text-xl font-bold">Quick Actions</h2>
                    </div>
                    <div class="card-body space-y-4">
                        <a href="deposit.php" class="btn btn-primary w-full flex items-center justify-center">
                            <i class="fas fa-arrow-down mr-2"></i> Add Funds
                        </a>
                        <a href="send.php" class="btn btn-secondary w-full flex items-center justify-center">
                            <i class="fas fa-paper-plane mr-2"></i> Send Funds
                        </a>
                        <a href="withdraw.php" class="btn w-full flex items-center justify-center bg-slate-700 text-white hover:bg-slate-600 transition-colors">
                            <i class="fas fa-arrow-up mr-2"></i> Withdraw
                        </a>
                    </div>
                </div>

                <!-- Recent Transactions Card -->
                <div class="card dashboard-card bg-slate-800/80 backdrop-blur-sm border-indigo-700/50 col-span-2 shadow-xl shadow-indigo-900/20">
                    <div class="card-header">
                        <h2 class="text-xl font-bold">Recent Transactions</h2>
                    </div>
                    <div class="card-body">
                        <div class="space-y-4">
                            <?php if (empty($transactions)): ?>
                            <p class="text-center text-slate-400 py-4">No transactions yet</p>
                            <?php else: ?>
                                <?php foreach ($transactions as $tx): ?>
                                <div class="flex items-center justify-between p-3 bg-slate-700/50 rounded-md hover:bg-slate-700/70 transition-colors">
                                    <div class="flex items-center">
                                        <div class="p-2 rounded-full <?php echo $tx['type'] === 'deposit' ? 'bg-green-500/30 text-green-200' : 'bg-red-500/30 text-red-200'; ?> mr-3">
                                            <i class="fas <?php echo $tx['type'] === 'deposit' ? 'fa-arrow-down' : 'fa-arrow-up'; ?>"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium"><?php echo ucfirst($tx['type']); ?></p>
                                            <p class="text-xs text-slate-400"><?php echo date('M j, Y', strtotime($tx['date'])); ?></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium <?php echo $tx['type'] === 'deposit' ? 'text-green-500' : 'text-red-500'; ?>">
                                            <?php echo $tx['type'] === 'deposit' ? '+' : '-'; ?><?php echo $tx['amount']; ?> <?php echo $tx['coin']; ?>
                                        </p>
                                        <p class="text-xs text-slate-400"><?php echo $tx['status']; ?></p>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="mt-4 text-center">
                            <button class="tab-link text-indigo-400 hover:text-indigo-300 transition-colors" data-tab="transactions">
                                View all transactions
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wallet Tab -->
        <div class="tab-content hidden" data-tab="wallet">
            <div class="card dashboard-card bg-slate-800/80 backdrop-blur-sm border-indigo-700/50 shadow-xl shadow-indigo-900/20">
                <div class="card-header">
                    <h2 class="text-xl font-bold">Your Wallets</h2>
                    <p class="text-sm text-slate-400">Manage your cryptocurrency wallets</p>
                </div>
                <div class="card-body space-y-6">
                    <?php foreach ($wallets as $coin => $data): ?>
                    <div class="p-4 bg-slate-700/50 rounded-lg hover:bg-slate-700/70 transition-colors">
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center">
                                <div class="crypto-icon crypto-icon-<?php echo strtolower($coin); ?> w-10 h-10">
                                    <?php echo substr($coin, 0, 1); ?>
                                </div>
                                <div>
                                    <h3 class="font-bold">
                                        <?php 
                                        echo $coin === 'BTC' ? 'Bitcoin' : 
                                             ($coin === 'ETH' ? 'Ethereum' : 'Tether');
                                        ?>
                                    </h3>
                                    <p class="text-sm text-slate-400"><?php echo $coin; ?></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-bold"><?php echo $data['balance']; ?> <?php echo $coin; ?></p>
                                <p class="text-sm text-slate-400">$<?php echo number_format($data['value'], 2); ?></p>
                            </div>
                        </div>

                        <div class="flex flex-col space-y-2">
                            <div class="flex items-center justify-between bg-slate-800 p-3 rounded">
                                <span class="text-sm text-slate-400">Wallet Address</span>
                                <div class="flex items-center">
                                    <span class="text-sm mr-2 font-mono text-indigo-300">
                                        <?php 
                                        $address = $data['address'];
                                        echo substr($address, 0, 6) . '...' . substr($address, -6);
                                        ?>
                                    </span>
                                    <button class="copy-to-clipboard p-2 text-indigo-400 hover:text-indigo-300 hover:bg-indigo-900/30 rounded-md transition-colors" data-clipboard-text="<?php echo $data['address']; ?>">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                    <a href="qr.php?coin=<?php echo $coin; ?>" class="p-2 text-indigo-400 hover:text-indigo-300 hover:bg-indigo-900/30 rounded-md transition-colors">
                                        <i class="fas fa-qrcode"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-3 mt-4">
                            <a href="deposit.php?coin=<?php echo $coin; ?>" class="btn btn-primary flex items-center justify-center">
                                <i class="fas fa-arrow-down mr-2"></i> Deposit
                            </a>
                            <a href="send.php?coin=<?php echo $coin; ?>" class="btn btn-secondary flex items-center justify-center">
                                <i class="fas fa-paper-plane mr-2"></i> Send
                            </a>
                            <a href="withdraw.php?coin=<?php echo $coin; ?>" class="btn flex items-center justify-center bg-slate-700 text-white hover:bg-slate-600 transition-colors">
                                <i class="fas fa-arrow-up mr-2"></i> Withdraw
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Market Tab -->
        <div class="tab-content hidden" data-tab="market">
            <div class="card dashboard-card bg-slate-800/80 backdrop-blur-sm border-indigo-700/50 shadow-xl shadow-indigo-900/20">
                <div class="card-header flex flex-row items-center justify-between pb-2">
                    <div>
                        <h2 class="text-xl font-bold">Market Prices</h2>
                        <p class="text-sm text-slate-400">Real-time cryptocurrency prices</p>
                    </div>
                    <button id="refresh-market" class="p-2 rounded-full bg-slate-800/50 hover:bg-slate-700/50 transition-colors">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="grid grid-cols-12 text-sm font-medium text-slate-400 p-2">
                            <div class="col-span-5">Coin</div>
                            <div class="col-span-3 text-right">Price</div>
                            <div class="col-span-2 text-right">24h Change</div>
                            <div class="col-span-2 text-right">Action</div>
                        </div>
                        <hr class="border-slate-700">
                        <?php foreach ($coins as $coin): ?>
                        <div class="grid grid-cols-12 items-center py-3 hover:bg-slate-700/30 rounded-md px-2 transition-colors">
                            <div class="col-span-5 flex items-center">
                                <div class="crypto-icon crypto-icon-<?php echo strtolower($coin['symbol']); ?>">
                                    <?php echo substr($coin['symbol'], 0, 1); ?>
                                </div>
                                <div>
                                    <p class="font-medium"><?php echo $coin['name']; ?></p>
                                    <p class="text-xs text-slate-400"><?php echo $coin['symbol']; ?></p>
                                </div>
                            </div>
                            <div class="col-span-3 text-right">
                                <p class="font-medium">$<?php echo number_format($coin['price'], 2); ?></p>
                            </div>
                            <div class="col-span-2 text-right">
                                <p class="font-medium <?php echo $coin['change24h'] >= 0 ? 'text-green-500' : 'text-red-500'; ?>">
                                    <?php echo $coin['change24h'] >= 0 ? '+' : ''; ?><?php echo $coin['change24h']; ?>%
                                </p>
                            </div>
                            <div class="col-span-2 text-right">
                                <a href="trade.php?coin=<?php echo $coin['symbol']; ?>" class="px-3 py-1 border border-indigo-600 text-indigo-400 hover:bg-indigo-900/30 rounded-md transition-colors inline-block text-sm">
                                    Trade
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
