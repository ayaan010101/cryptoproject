<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: index.php');
    exit;
}

// Get user data
$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Get wallet data
$walletData = getUserWallets($userId);

// Get transaction history
$transactions = getUserTransactions($userId, 10);

// Get coin data
$coinData = getCoinData();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CryptoTrade</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="dashboard-body">
    <!-- Header -->
    <header class="dashboard-header">
        <div class="container">
            <div class="header-logo">
                <i class="fas fa-wallet"></i>
                <h1>CryptoTrade Dashboard</h1>
            </div>
            <div class="header-user">
                <div class="user-info">
                    <p>Welcome back,</p>
                    <p class="username"><?php echo htmlspecialchars($username); ?></p>
                </div>
                <div class="user-actions">
                    <button class="icon-button" id="settings-button">
                        <i class="fas fa-cog"></i>
                    </button>
                    <a href="includes/logout.php" class="icon-button logout-button">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="container">
            <!-- Dashboard Tabs -->
            <div class="dashboard-tabs">
                <button class="tab-button active" data-tab="overview">Overview</button>
                <button class="tab-button" data-tab="wallet">Wallet</button>
                <button class="tab-button" data-tab="market">Market</button>
                <button class="tab-button" data-tab="transactions">Transactions</button>
            </div>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Overview Tab -->
                <div class="tab-pane active" id="overview">
                    <div class="dashboard-grid">
                        <!-- Market Overview Card -->
                        <div class="dashboard-card large-card">
                            <div class="card-header">
                                <div>
                                    <h2>Market Overview</h2>
                                    <p class="card-subtitle">Real-time price updates every 30 seconds</p>
                                </div>
                                <button class="refresh-button" id="refresh-market">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <div class="chart-header">
                                        <div>
                                            <h3>BTC/USD</h3>
                                            <p class="price-up">$<?php echo number_format($coinData[0]['price'], 2); ?> <span>+<?php echo $coinData[0]['change24h']; ?>%</span></p>
                                        </div>
                                        <div class="chart-timeframes">
                                            <button class="timeframe-button">1H</button>
                                            <button class="timeframe-button active">1D</button>
                                            <button class="timeframe-button">1W</button>
                                            <button class="timeframe-button">1M</button>
                                        </div>
                                    </div>
                                    <div class="chart" id="overview-chart"></div>
                                    <div class="chart-timeline">
                                        <span>09:00</span>
                                        <span>12:00</span>
                                        <span>15:00</span>
                                        <span>18:00</span>
                                        <span>21:00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Portfolio Balance Card -->
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h2>Portfolio Balance</h2>
                                <p class="card-subtitle">Your current holdings</p>
                            </div>
                            <div class="card-body">
                                <div class="balance-overview">
                                    <div class="balance-header">
                                        <span>Total Balance</span>
                                        <span class="total-balance">$<?php echo number_format(calculateTotalBalance($walletData), 2); ?></span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: 100%;"></div>
                                    </div>
                                </div>

                                <div class="wallet-list">
                                    <?php foreach ($walletData as $coin => $data): ?>
                                    <div class="wallet-item">
                                        <div class="wallet-info">
                                            <div class="coin-icon <?php echo strtolower($coin); ?>">
                                                <?php echo substr($coin, 0, 1); ?>
                                            </div>
                                            <div>
                                                <p class="coin-name"><?php echo $coin; ?></p>
                                                <p class="coin-amount"><?php echo $data['balance']; ?> <?php echo $coin; ?></p>
                                            </div>
                                        </div>
                                        <p class="coin-value">$<?php echo number_format($data['value'], 2); ?></p>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions Card -->
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h2>Quick Actions</h2>
                            </div>
                            <div class="card-body">
                                <div class="action-buttons">
                                    <button class="btn btn-primary btn-block" id="deposit-btn">
                                        <i class="fas fa-arrow-down"></i> Add Funds
                                    </button>
                                    <button class="btn btn-outline btn-block" id="send-btn">
                                        <i class="fas fa-paper-plane"></i> Send Funds
                                    </button>
                                    <button class="btn btn-secondary btn-block" id="withdraw-btn">
                                        <i class="fas fa-arrow-up"></i> Withdraw
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Transactions Card -->
                        <div class="dashboard-card large-card">
                            <div class="card-header">
                                <h2>Recent Transactions</h2>
                            </div>
                            <div class="card-body">
                                <div class="transactions-list">
                                    <?php if (empty($transactions)): ?>
                                    <p class="no-data">No transactions found</p>
                                    <?php else: ?>
                                        <?php foreach (array_slice($transactions, 0, 3) as $tx): ?>
                                        <div class="transaction-item">
                                            <div class="transaction-info">
                                                <div class="transaction-icon <?php echo $tx['type']; ?>">
                                                    <i class="fas fa-<?php echo $tx['type'] === 'deposit' ? 'arrow-down' : 'arrow-up'; ?>"></i>
                                                </div>
                                                <div>
                                                    <p class="transaction-type"><?php echo ucfirst($tx['type']); ?></p>
                                                    <p class="transaction-date"><?php echo date('M d, Y', strtotime($tx['date'])); ?></p>
                                                </div>
                                            </div>
                                            <div class="transaction-details">
                                                <p class="transaction-amount <?php echo $tx['type']; ?>">
                                                    <?php echo $tx['type'] === 'deposit' ? '+' : '-'; ?><?php echo $tx['amount']; ?> <?php echo $tx['coin']; ?>
                                                </p>
                                                <p class="transaction-status"><?php echo $tx['status']; ?></p>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="view-all">
                                    <button class="btn-link" id="view-all-transactions">View all transactions</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wallet Tab -->
                <div class="tab-pane" id="wallet">
                    <div class="dashboard-card full-width">
                        <div class="card-header">
                            <h2>Your Wallets</h2>
                            <p class="card-subtitle">Manage your cryptocurrency wallets</p>
                        </div>
                        <div class="card-body">
                            <?php foreach ($walletData as $coin => $data): ?>
                            <div class="wallet-card">
                                <div class="wallet-header">
                                    <div class="wallet-title">
                                        <div class="coin-icon large <?php echo strtolower($coin); ?>">
                                            <?php echo substr($coin, 0, 1); ?>
                                        </div>
                                        <div>
                                            <h3><?php echo getCoinFullName($coin); ?></h3>
                                            <p><?php echo $coin; ?></p>
                                        </div>
                                    </div>
                                    <div class="wallet-balance">
                                        <p class="balance-amount"><?php echo $data['balance']; ?> <?php echo $coin; ?></p>
                                        <p class="balance-value">$<?php echo number_format($data['value'], 2); ?></p>
                                    </div>
                                </div>

                                <div class="wallet-address">
                                    <span class="address-label">Wallet Address</span>
                                    <div class="address-value">
                                        <span class="address"><?php echo substr($data['address'], 0, 6) . '...' . substr($data['address'], -6); ?></span>
                                        <button class="icon-button copy-address" data-address="<?php echo $data['address']; ?>">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        <button class="icon-button show-qr" data-coin="<?php echo $coin; ?>" data-address="<?php echo $data['address']; ?>">
                                            <i class="fas fa-qrcode"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="wallet-actions">
                                    <button class="btn btn-primary deposit-btn" data-coin="<?php echo $coin; ?>">
                                        <i class="fas fa-arrow-down"></i> Deposit
                                    </button>
                                    <button class="btn btn-outline send-btn" data-coin="<?php echo $coin; ?>">
                                        <i class="fas fa-paper-plane"></i> Send
                                    </button>
                                    <button class="btn btn-secondary withdraw-btn" data-coin="<?php echo $coin; ?>">
                                        <i class="fas fa-arrow-up"></i> Withdraw
                                    </button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Market Tab -->
                <div class="tab-pane" id="market">
                    <div class="dashboard-card full-width">
                        <div class="card-header">
                            <div>
                                <h2>Market Prices</h2>
                                <p class="card-subtitle">Real-time cryptocurrency prices</p>
                            </div>
                            <button class="refresh-button" id="refresh-prices">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="market-table">
                                <div class="market-table-header">
                                    <div class="market-col coin-col">Coin</div>
                                    <div class="market-col price-col">Price</div>
                                    <div class="market-col change-col">24h Change</div>
                                    <div class="market-col action-col">Action</div>
                                </div>
                                <div class="market-table-body">
                                    <?php foreach ($coinData as $coin): ?>
                                    <div class="market-row">
                                        <div class="market-col coin-col">
                                            <div class="coin-info">
                                                <div class="coin-icon <?php echo strtolower($coin['symbol']); ?>">
                                                    <?php echo substr($coin['symbol'], 0, 1); ?>
                                                </div>
                                                <div>
                                                    <p class="coin-name"><?php echo $coin['name']; ?></p>
                                                    <p class="coin-symbol"><?php echo $coin['symbol']; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="market-col price-col">
                                            <p class="coin-price">$<?php echo number_format($coin['price'], 2); ?></p>
                                        </div>
                                        <div class="market-col change-col">
                                            <p class="coin-change <?php echo $coin['change24h'] >= 0 ? 'positive' : 'negative'; ?>">
                                                <?php echo $coin['change24h'] >= 0 ? '+' : ''; ?><?php echo $coin['change24h']; ?>%
                                            </p>
                                        </div>
                                        <div class="market-col action-col">
                                            <button class="btn btn-outline btn-sm trade-btn" data-coin="<?php echo $coin['symbol']; ?>">
                                                Trade
                                            </button>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-card full-width">
                        <div class="card-header">
                            <h2>Market Chart</h2>
                        </div>
                        <div class="card-body">
                            <div class="market-chart-container">
                                <div class="chart-controls">
                                    <div class="coin-selector">
                                        <?php foreach (array_slice($coinData, 0, 3) as $index => $coin): ?>
                                        <button class="coin-button <?php echo $index === 0 ? 'active' : ''; ?>" data-coin="<?php echo $coin['symbol']; ?>">
                                            <?php echo $coin['symbol']; ?>/USD
                                        </button>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="timeframe-selector">
                                        <button class="timeframe-button">1D</button>
                                        <button class="timeframe-button active">1W</button>
                                        <button class="timeframe-button">1M</button>
                                        <button class="timeframe-button">1Y</button>
                                    </div>
                                </div>
                                <div class="market-chart" id="market-chart"></div>
                                <div class="chart-info-overlay">
                                    <h3>BTC/USD</h3>
                                    <p class="price-up">$<?php echo number_format($coinData[0]['price'], 2); ?> <span>+<?php echo $coinData[0]['change24h']; ?>%</span></p>
                                    <div class="chart-stats">
                                        <div>
                                            <p class="stat-label">24h High</p>
                                            <p class="stat-value">$<?php echo number_format($coinData[0]['price'] * 1.05, 2); ?></p>
                                        </div>
                                        <div>
                                            <p class="stat-label">24h Low</p>
                                            <p class="stat-value">$<?php echo number_format($coinData[0]['price'] * 0.95, 2); ?></p>
                                        </div>
                                        <div>
                                            <p class="stat-label">24h Volume</p>
                                            <p class="stat-value">$1.2B</p>
                                        </div>
                                        <div>
                                            <p class="stat-label">Market Cap</p>
                                            <p class="stat-value">$825.4B</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="chart-timeline weekly">
                                    <span>Mon</span>
                                    <span>Tue</span>
                                    <span>Wed</span>
                                    <span>Thu</span>
                                    <span>Fri</span>
                                    <span>Sat</span>
                                    <span>Sun</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transactions Tab -->
                <div class="tab-pane" id="transactions">
                    <div class="dashboard-card full-width">
                        <div class="card-header">
                            <h2>Transaction History</h2>
                            <p class="card-subtitle">View all your deposits and withdrawals</p>
                        </div>
                        <div class="card-body">
                            <div class="transactions-list full">
                                <?php if (empty($transactions)): ?>
                                <p class="no-data">No transactions found</p>
                                <?php else: ?>
                                    <?php foreach ($transactions as $tx): ?>
                                    <div class="transaction-item large">
                                        <div class="transaction-info">
                                            <div class="transaction-icon large <?php echo $tx['type']; ?>">
                                                <i class="fas fa-<?php echo $tx['type'] === 'deposit' ? 'arrow-down' : 'arrow-up'; ?>"></i>
                                            </div>
                                            <div>
                                                <p class="transaction-type large"><?php echo ucfirst($tx['type']); ?></p>
                                                <p class="transaction-date"><?php echo date('M d, Y H:i', strtotime($tx['date'])); ?></p>
                                            </div>
                                        </div>
                                        <div class="transaction-details">
                                            <p class="transaction-amount large <?php echo $tx['type']; ?>">
                                                <?php echo $tx['type'] === 'deposit' ? '+' : '-'; ?><?php echo $tx['amount']; ?> <?php echo $tx['coin']; ?>
                                            </p>
                                            <p class="transaction-status <?php echo $tx['status']; ?>">
                                                Status: <span><?php echo ucfirst($tx['status']); ?></span>
                                            </p>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modals -->
    <!-- QR Code Modal -->
    <div class="modal" id="qr-modal">
        <div class="modal-content">
            <button class="modal-close">
                <i class="fas fa-times"></i>
            </button>
            <h2>Deposit <span id="qr-coin">BTC</span></h2>
            <p class="modal-subtitle">Scan this QR code or copy the address to deposit funds</p>
            <div class="qr-container">
                <div id="qr-code"></div>
            </div>
            <div class="form-group">
                <label>Wallet Address</label>
                <div class="copy-input">
                    <input type="text" id="wallet-address" readonly>
                    <button class="copy-button" id="copy-address">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <div>
                    <p class="alert-title">Important</p>
                    <p>Only send <span id="alert-coin">BTC</span> to this address. Sending any other cryptocurrency may result in permanent loss.</p>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn btn-primary" id="simulate-deposit">Simulate Deposit (Demo)</button>
                <button class="btn btn-outline modal-close-btn">Close</button>
            </div>
        </div>
    </div>

    <!-- Send Funds Modal -->
    <div class="modal" id="send-modal">
        <div class="modal-content">
            <button class="modal-close">
                <i class="fas fa-times"></i>
            </button>
            <h2>Send <span id="send-coin">BTC</span></h2>
            <p class="modal-subtitle">Send cryptocurrency to another wallet address</p>
            <div class="form-group">
                <label>Available Balance</label>
                <div class="balance-display">
                    <span id="available-balance">0.0345 BTC</span>
                    <span id="available-value">$1,468.60</span>
                </div>
            </div>
            <form id="send-form">
                <div class="form-group">
                    <label for="send-amount">Amount to Send</label>
                    <input type="number" id="send-amount" placeholder="0.00" step="0.0001" required>
                </div>
                <div class="form-group">
                    <label for="recipient-address">Recipient Address</label>
                    <input type="text" id="recipient-address" placeholder="Enter wallet address" required>
                </div>
                <div class="form-group">
                    <div class="label-with-link">
                        <label for="security-key">Security Key</label>
                        <a href="#" id="recover-key-link">Recover your security key</a>
                    </div>
                    <input type="password" id="security-key" placeholder="Enter your security key" required>
                </div>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <p class="alert-title">Security Notice</p>
                        <p>Double-check the recipient address. Cryptocurrency transactions cannot be reversed.</p>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-outline modal-close-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send <span id="send-btn-coin">BTC</span></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Withdraw Funds Modal -->
    <div class="modal" id="withdraw-modal">
        <div class="modal-content">
            <button class="modal-close">
                <i class="fas fa-times"></i>
            </button>
            <h2>Withdraw Funds</h2>
            <p class="modal-subtitle">Enter your security key to authorize this withdrawal</p>
            <form id="withdraw-form">
                <div class="form-group">
                    <label for="withdraw-amount">Withdrawal Amount</label>
                    <input type="number" id="withdraw-amount" placeholder="0.00" step="0.0001" required>
                </div>
                <div class="form-group">
                    <label for="withdraw-address">Destination Address</label>
                    <input type="text" id="withdraw-address" placeholder="Enter wallet address" required>
                </div>
                <div class="form-group">
                    <div class="label-with-link">
                        <label for="withdraw-key">Security Key</label>
                        <a href="#" id="withdraw-recover-link">Recover your security key</a>
                    </div>
                    <input type="password" id="withdraw-key" placeholder="Enter your security key" required>
                </div>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <p class="alert-title">Security Notice</p>
                        <p>Never share your security key with anyone. Our team will never ask for it.</p>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-outline modal-close-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary">Confirm Withdrawal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Security Key Recovery Modal -->
    <div class="modal" id="recovery-modal">
        <div class="modal-content">
            <button class="modal-close">
                <i class="fas fa-times"></i>
            </button>
            <h2>Security Key Recovery</h2>
            <p class="modal-subtitle">Recover your security key by completing the verification process</p>
            <div class="recovery-progress">
                <div class="progress-bar">
                    <div class="progress-fill" id="recovery-progress" style="width: 33.33%;"></div>
                </div>
                <div class="progress-labels">
                    <span>Email</span>
                    <span>Payment</span>
                    <span>Complete</span>
                </div>
            </div>
            <div class="recovery-steps">
                <!-- Step 1: Email -->
                <div class="recovery-step active" id="recovery-step-1">
                    <div class="form-group">
                        <label for="recovery-email">Email Address</label>
                        <input type="email" id="recovery-email" placeholder="your.email@example.com" required>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <p class="alert-title">Verification Required</p>
                            <p>We'll send your new security key to this email after payment verification.</p>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-block" id="recovery-next-1">Continue <i class="fas fa-arrow-right"></i></button>
                </div>
                
                <!-- Step 2: Payment -->
                <div class="recovery-step" id="recovery-step-2">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <p class="alert-title">Payment Required</p>
                            <p>To recover your security key, please send 300 USDT to the address below.</p>
                        </div>
                    </div>
                    <div class="payment-info">
                        <p class="payment-label">Send 300 USDT to:</p>
                        <div class="copy-input">
                            <input type="text" value="0xd8dA6BF26964aF9D7eEd9e03E53415D37aA96045" readonly>
                            <button class="copy-button" id="copy-payment-address">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <div class="payment-qr">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=0xd8dA6BF26964aF9D7eEd9e03E53415D37aA96045" alt="Payment QR Code">
                        </div>
                        <p class="payment-note">After sending the payment, click "Verify Payment" below.</p>
                    </div>
                    <button class="btn btn-primary btn-block" id="recovery-next-2">Verify Payment</button>
                </div>
                
                <!-- Step 3: Complete -->
                <div class="recovery-step" id="recovery-step-3">
                    <div class="recovery-success">
                        <div class="success-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>Recovery Successful!</h3>
                        <p>Your new security key has been generated and sent to your email address.</p>
                    </div>
                    <button class="btn btn-primary btn-block modal-close-btn">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Modal -->
    <div class="modal" id="settings-modal">
        <div class="modal-content">
            <button class="modal-close">
                <i class="fas fa-times"></i>
            </button>
            <h2>Account Settings</h2>
            <p class="modal-subtitle">Manage your personal information and preferences</p>
            <form id="settings-form">
                <div class="settings-section">
                    <h3>Personal Information</h3>
                    <div class="form-group">
                        <label for="full-name">Full Name</label>
                        <input type="text" id="full-name" value="<?php echo htmlspecialchars($username); ?>">
                    </div>
                    <div class="form-group">
                        <label for="email-address">Email Address</label>
                        <input type="email" id="email-address" value="user@example.com">
                    </div>
                    <div class="form-group">
                        <label for="phone-number">Phone Number</label>
                        <input type="tel" id="phone-number" value="+1 (555) 123-4567">
                    </div>
                </div>
                
                <div class="settings-divider"></div>
                
                <div class="settings-section">
                    <h3>Security</h3>
                    <div class="setting-item">
                        <div class="setting-info">
                            <p class="setting-name">Two-Factor Authentication</p>
                            <p class="setting-description">Add an extra layer of security to your account</p>
                        </div>
                        <div class="setting-control">
                            <button type="button" class="btn btn-outline" id="tfa-toggle">Disabled</button>
                        </div>
                    </div>
                    <div class="setting-item">
                        <div class="setting-info">
                            <p class="setting-name">Change Security Key</p>
                            <p class="setting-description">Update your account recovery key</p>
                        </div>
                        <div class="setting-control">
                            <button type="button" class="btn btn-outline">Change Key</button>
                        </div>
                    </div>
                </div>
                
                <div class="settings-divider"></div>
                
                <div class="settings-section">
                    <h3>Preferences</h3>
                    <div class="setting-item">
                        <div class="setting-info">
                            <p class="setting-name">Email Notifications</p>
                            <p class="setting-description">Receive alerts for transactions and security events</p>
                        </div>
                        <div class="setting-control">
                            <button type="button" class="btn btn-primary" id="notifications-toggle">Enabled</button>
                        </div>
                    </div>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-outline modal-close-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.0/build/qrcode.min.js"></script>
    <script src="assets/js/dashboard.js"></script>
</body>
</html>