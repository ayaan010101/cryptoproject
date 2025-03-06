import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import TradingChart from "./TradingChart";
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Progress } from "@/components/ui/progress";
import { Separator } from "@/components/ui/separator";
import {
  ArrowUpRight,
  ArrowDownRight,
  RefreshCw,
  Wallet,
  Send,
  Download,
  Upload,
  Copy,
  QrCode,
  AlertCircle,
  Shield,
  LogOut,
  Mail,
} from "lucide-react";

// Mock data for demonstration
const mockCoinData = [
  {
    id: "bitcoin",
    name: "Bitcoin",
    symbol: "BTC",
    price: 95432.78,
    change24h: 3.45,
  },
  {
    id: "ethereum",
    name: "Ethereum",
    symbol: "ETH",
    price: 2356.78,
    change24h: -1.45,
  },
  { id: "tether", name: "Tether", symbol: "USDT", price: 1.0, change24h: 0.01 },
  {
    id: "binancecoin",
    name: "Binance Coin",
    symbol: "BNB",
    price: 567.89,
    change24h: 3.21,
  },
  {
    id: "cardano",
    name: "Cardano",
    symbol: "ADA",
    price: 0.45,
    change24h: -2.67,
  },
  {
    id: "solana",
    name: "Solana",
    symbol: "SOL",
    price: 123.45,
    change24h: 5.67,
  },
];

const mockWalletData = {
  BTC: {
    balance: 0.0345,
    value: 3292.43,
    address: "bc1q9h5yx3mvy8zj053y8zle7zn5p28mqwzx9lqnf3",
  },
  ETH: {
    balance: 1.245,
    value: 2934.19,
    address: "0x742d35Cc6634C0532925a3b844Bc454e4438f44e",
  },
  USDT: {
    balance: 5000.0,
    value: 5000.0,
    address: "0x742d35Cc6634C0532925a3b844Bc454e4438f44e",
  },
};

const mockTransactions = [
  {
    id: 1,
    type: "deposit",
    coin: "BTC",
    amount: 0.0145,
    status: "completed",
    date: "2023-06-15T14:23:45Z",
  },
  {
    id: 2,
    type: "withdrawal",
    coin: "USDT",
    amount: 500,
    status: "completed",
    date: "2023-06-10T09:15:22Z",
  },
  {
    id: 3,
    type: "deposit",
    coin: "ETH",
    amount: 0.5,
    status: "completed",
    date: "2023-06-05T18:45:30Z",
  },
  {
    id: 4,
    type: "withdrawal",
    coin: "BTC",
    amount: 0.002,
    status: "pending",
    date: "2023-06-01T11:32:15Z",
  },
  {
    id: 5,
    type: "deposit",
    coin: "USDT",
    amount: 1000,
    status: "completed",
    date: "2023-05-28T16:20:45Z",
  },
];

interface UserDashboardProps {
  username?: string;
}

const UserDashboard = ({ username = "User" }: UserDashboardProps) => {
  const [activeTab, setActiveTab] = useState("overview");
  const [coinData, setCoinData] = useState(mockCoinData);
  const [walletData, setWalletData] = useState(mockWalletData);
  const [transactions, setTransactions] = useState(mockTransactions);
  const [isRefreshing, setIsRefreshing] = useState(false);
  const [showSecurityKeyDialog, setShowSecurityKeyDialog] = useState(false);
  const [showSendDialog, setShowSendDialog] = useState(false);
  const [sendAmount, setSendAmount] = useState("");
  const [sendCoin, setSendCoin] = useState("BTC");
  const [sendAddress, setSendAddress] = useState("");
  const [sendSecurityKey, setSendSecurityKey] = useState("");
  const [securityKey, setSecurityKey] = useState("");
  const [withdrawAmount, setWithdrawAmount] = useState("");
  const [withdrawCoin, setWithdrawCoin] = useState("BTC");
  const [withdrawAddress, setWithdrawAddress] = useState("");
  const [showRecoveryDialog, setShowRecoveryDialog] = useState(false);
  const [recoveryEmail, setRecoveryEmail] = useState("");
  const [recoveryStep, setRecoveryStep] = useState(1);
  const [showQrDialog, setShowQrDialog] = useState(false);
  const [selectedWallet, setSelectedWallet] = useState("BTC");
  const [showSettingsDialog, setShowSettingsDialog] = useState(false);
  const [userSettings, setUserSettings] = useState({
    fullName: username,
    email: "user@example.com",
    phone: "+1 (555) 123-4567",
    twoFactorEnabled: false,
    notificationsEnabled: true,
  });

  // Simulate real-time updates every 30 seconds
  useEffect(() => {
    const updateInterval = setInterval(() => {
      // Update coin prices with small random changes
      setCoinData((prevData) =>
        prevData.map((coin) => ({
          ...coin,
          price: parseFloat(
            (coin.price * (1 + (Math.random() * 0.02 - 0.01))).toFixed(2),
          ),
          change24h: parseFloat(
            (coin.change24h + (Math.random() * 0.5 - 0.25)).toFixed(2),
          ),
        })),
      );
    }, 30000); // 30 seconds

    return () => clearInterval(updateInterval);
  }, []);

  const handleRefresh = () => {
    setIsRefreshing(true);

    // Simulate API call
    setTimeout(() => {
      setCoinData((prevData) =>
        prevData.map((coin) => ({
          ...coin,
          price: parseFloat(
            (coin.price * (1 + (Math.random() * 0.02 - 0.01))).toFixed(2),
          ),
          change24h: parseFloat(
            (coin.change24h + (Math.random() * 0.5 - 0.25)).toFixed(2),
          ),
        })),
      );
      setIsRefreshing(false);
    }, 1000);
  };

  const handleWithdraw = () => {
    // Validate security key - in a real app this would check against a stored hash
    const validSecurityKey = "secure123"; // This would be retrieved from database

    if (securityKey === validSecurityKey) {
      // Process withdrawal
      const newTransaction = {
        id: transactions.length + 1,
        type: "withdrawal",
        coin: withdrawCoin,
        amount: parseFloat(withdrawAmount),
        status: "pending",
        date: new Date().toISOString(),
      };

      setTransactions([newTransaction, ...transactions]);
      setShowSecurityKeyDialog(false);
      setSecurityKey("");
      setWithdrawAmount("");
      setWithdrawAddress("");

      // Show success notification
      alert(
        `Withdrawal of ${withdrawAmount} ${withdrawCoin} initiated successfully!`,
      );
    } else {
      // Show invalid key error with recovery option
      if (
        confirm(
          "You entered an invalid security key. Click OK to recover your security key.",
        )
      ) {
        setShowSecurityKeyDialog(false);
        setShowRecoveryDialog(true);
      }
    }
  };

  const handleRecoveryNext = () => {
    if (recoveryStep === 1 && recoveryEmail) {
      setRecoveryStep(2);
    } else if (recoveryStep === 2) {
      // In a real app, this would verify payment and send the key to email
      setTimeout(() => {
        setRecoveryStep(3);
      }, 2000);
    } else if (recoveryStep === 3) {
      setShowRecoveryDialog(false);
      setRecoveryStep(1);
      setRecoveryEmail("");
    }
  };

  const handleShowQr = (wallet: string) => {
    setSelectedWallet(wallet);
    setShowQrDialog(true);
  };

  const getTotalBalance = () => {
    return Object.values(walletData).reduce(
      (total, wallet) => total + wallet.value,
      0,
    );
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-900 via-indigo-900 to-purple-900 text-white bg-[url('https://images.unsplash.com/photo-1639762681057-408e52192e55?w=1600&q=80')] bg-cover bg-blend-overlay bg-opacity-90">
      {/* Header */}
      <header className="w-full h-20 border-b border-indigo-700/50 backdrop-blur-sm bg-slate-900/70 flex items-center justify-between px-6 sticky top-0 z-50">
        <div className="flex items-center space-x-4">
          <Wallet className="h-8 w-8 text-indigo-400" />
          <h1 className="text-xl font-bold text-white">
            CryptoTrade Dashboard
          </h1>
        </div>
        <div className="flex items-center space-x-4">
          <div className="text-right">
            <p className="text-sm text-slate-300">Welcome back,</p>
            <p className="font-medium">{username}</p>
          </div>
          <Button
            variant="ghost"
            size="icon"
            className="text-indigo-400 hover:text-indigo-300 hover:bg-indigo-900/30"
            onClick={() => setShowSettingsDialog(true)}
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="20"
              height="20"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth="2"
              strokeLinecap="round"
              strokeLinejoin="round"
              className="lucide lucide-settings"
            >
              <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
              <circle cx="12" cy="12" r="3"></circle>
            </svg>
          </Button>
          <Button
            variant="ghost"
            size="icon"
            className="text-red-400 hover:text-red-300 hover:bg-red-900/30"
            onClick={() => (window.location.href = "/")}
          >
            <LogOut className="h-5 w-5" />
          </Button>
        </div>
      </header>

      {/* Main Content */}
      <div className="container mx-auto px-4 py-8">
        <Tabs value={activeTab} onValueChange={setActiveTab} className="w-full">
          <TabsList className="grid w-full max-w-3xl mx-auto grid-cols-4 mb-8 bg-slate-800/80 backdrop-blur-sm border border-indigo-700/50">
            <TabsTrigger value="overview">Overview</TabsTrigger>
            <TabsTrigger value="wallet">Wallet</TabsTrigger>
            <TabsTrigger value="market">Market</TabsTrigger>
            <TabsTrigger value="transactions">Transactions</TabsTrigger>
          </TabsList>

          {/* Overview Tab */}
          <TabsContent value="overview" className="space-y-6">
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
              <Card className="bg-slate-800/80 backdrop-blur-sm border-indigo-700/50 col-span-3 md:col-span-2 shadow-xl shadow-indigo-900/20">
                <CardHeader className="flex flex-row items-center justify-between pb-2">
                  <div>
                    <CardTitle>Market Overview</CardTitle>
                    <CardDescription className="text-slate-400">
                      Real-time price updates every 30 seconds
                    </CardDescription>
                  </div>
                  <Button
                    variant="ghost"
                    size="icon"
                    onClick={handleRefresh}
                    disabled={isRefreshing}
                    className="text-indigo-400 hover:text-indigo-300"
                  >
                    <RefreshCw
                      className={`h-5 w-5 ${isRefreshing ? "animate-spin" : ""}`}
                    />
                  </Button>
                </CardHeader>
                <CardContent>
                  <div className="h-[300px] w-full bg-slate-900 rounded-md p-4 relative overflow-hidden">
                    <img
                      src="https://images.unsplash.com/photo-1642790551116-18e150f248e5?w=800&q=80"
                      alt="Trading Chart Background"
                      className="absolute inset-0 w-full h-full object-cover opacity-20"
                    />
                    <div className="relative z-10 h-full w-full flex flex-col">
                      <div className="flex justify-between mb-4">
                        <div>
                          <h3 className="font-bold text-lg">BTC/USD</h3>
                          <p className="text-green-500 font-medium">
                            $
                            {coinData
                              .find((c) => c.symbol === "BTC")
                              ?.price.toLocaleString()}
                            <span className="text-xs ml-1">
                              +
                              {
                                coinData.find((c) => c.symbol === "BTC")
                                  ?.change24h
                              }
                              %
                            </span>
                          </p>
                        </div>
                        <div className="flex space-x-2">
                          <Button
                            size="sm"
                            variant="outline"
                            className="h-8 text-indigo-300 border-indigo-800 hover:bg-indigo-900/30"
                          >
                            1H
                          </Button>
                          <Button
                            size="sm"
                            variant="outline"
                            className="h-8 bg-indigo-900/40 text-indigo-300 border-indigo-800"
                          >
                            1D
                          </Button>
                          <Button
                            size="sm"
                            variant="outline"
                            className="h-8 text-indigo-300 border-indigo-800 hover:bg-indigo-900/30"
                          >
                            1W
                          </Button>
                          <Button
                            size="sm"
                            variant="outline"
                            className="h-8 text-indigo-300 border-indigo-800 hover:bg-indigo-900/30"
                          >
                            1M
                          </Button>
                        </div>
                      </div>
                      <div className="flex-1 relative">
                        <div className="w-full h-full bg-slate-900 rounded">
                          <TradingChart
                            width={800}
                            height={200}
                            color="#22c55e"
                            backgroundColor="transparent"
                          />
                        </div>
                        <div className="absolute bottom-0 left-0 right-0 h-1/3 bg-gradient-to-t from-slate-900 to-transparent"></div>
                      </div>
                      <div className="mt-2 flex justify-between text-xs text-slate-400">
                        <span>09:00</span>
                        <span>12:00</span>
                        <span>15:00</span>
                        <span>18:00</span>
                        <span>21:00</span>
                      </div>
                    </div>
                  </div>
                </CardContent>
              </Card>

              <Card className="bg-slate-800/80 backdrop-blur-sm border-indigo-700/50 shadow-xl shadow-indigo-900/20">
                <CardHeader>
                  <CardTitle>Portfolio Balance</CardTitle>
                  <CardDescription className="text-slate-400">
                    Your current holdings
                  </CardDescription>
                </CardHeader>
                <CardContent className="space-y-6">
                  <div>
                    <div className="flex justify-between items-center mb-2">
                      <span className="text-slate-400">Total Balance</span>
                      <span className="text-2xl font-bold text-indigo-300">
                        ${getTotalBalance().toFixed(2)}
                      </span>
                    </div>
                    <Progress value={100} className="h-2 bg-slate-700" />
                  </div>

                  <div className="space-y-4">
                    {Object.entries(walletData).map(([coin, data]) => (
                      <div
                        key={coin}
                        className="flex justify-between items-center"
                      >
                        <div className="flex items-center">
                          <div
                            className={`w-8 h-8 rounded-full mr-2 flex items-center justify-center ${coin === "BTC" ? "bg-orange-500/30 text-orange-200" : coin === "ETH" ? "bg-blue-500/30 text-blue-200" : "bg-green-500/30 text-green-200"}`}
                          >
                            {coin === "BTC" ? "B" : coin === "ETH" ? "E" : "U"}
                          </div>
                          <div>
                            <p className="font-medium">{coin}</p>
                            <p className="text-xs text-slate-400">
                              {data.balance} {coin}
                            </p>
                          </div>
                        </div>
                        <p className="font-medium">${data.value.toFixed(2)}</p>
                      </div>
                    ))}
                  </div>
                </CardContent>
              </Card>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
              <Card className="bg-slate-800/80 backdrop-blur-sm border-indigo-700/50 shadow-xl shadow-indigo-900/20">
                <CardHeader>
                  <CardTitle>Quick Actions</CardTitle>
                </CardHeader>
                <CardContent className="space-y-4">
                  <Button
                    className="w-full flex items-center justify-center bg-indigo-600 hover:bg-indigo-700"
                    onClick={() => setShowQrDialog(true)}
                  >
                    <Upload className="mr-2 h-4 w-4" />
                    Add Funds
                  </Button>
                  <Button
                    variant="outline"
                    className="w-full flex items-center justify-center border-indigo-600 text-indigo-400 hover:bg-indigo-900/30"
                    onClick={() => {
                      setSendCoin("BTC");
                      setShowSendDialog(true);
                    }}
                  >
                    <Send className="mr-2 h-4 w-4" />
                    Send Funds
                  </Button>
                  <Button
                    variant="secondary"
                    className="w-full flex items-center justify-center bg-slate-700 hover:bg-slate-600"
                    onClick={() => setShowSecurityKeyDialog(true)}
                  >
                    <Download className="mr-2 h-4 w-4" />
                    Withdraw
                  </Button>
                </CardContent>
              </Card>

              <Card className="bg-slate-800/80 backdrop-blur-sm border-indigo-700/50 col-span-2 shadow-xl shadow-indigo-900/20">
                <CardHeader>
                  <CardTitle>Recent Transactions</CardTitle>
                </CardHeader>
                <CardContent>
                  <div className="space-y-4">
                    {transactions.slice(0, 3).map((tx) => (
                      <div
                        key={tx.id}
                        className="flex items-center justify-between p-3 bg-slate-700/50 rounded-md hover:bg-slate-700/70 transition-colors"
                      >
                        <div className="flex items-center">
                          <div
                            className={`p-2 rounded-full ${tx.type === "deposit" ? "bg-green-500/30 text-green-200" : "bg-red-500/30 text-red-200"} mr-3`}
                          >
                            {tx.type === "deposit" ? (
                              <ArrowUpRight className="h-4 w-4 text-green-500" />
                            ) : (
                              <ArrowDownRight className="h-4 w-4 text-red-500" />
                            )}
                          </div>
                          <div>
                            <p className="font-medium">
                              {tx.type === "deposit" ? "Deposit" : "Withdrawal"}
                            </p>
                            <p className="text-xs text-slate-400">
                              {new Date(tx.date).toLocaleDateString()}
                            </p>
                          </div>
                        </div>
                        <div className="text-right">
                          <p
                            className={`font-medium ${tx.type === "deposit" ? "text-green-500" : "text-red-500"}`}
                          >
                            {tx.type === "deposit" ? "+" : "-"}
                            {tx.amount} {tx.coin}
                          </p>
                          <p className="text-xs text-slate-400">{tx.status}</p>
                        </div>
                      </div>
                    ))}
                  </div>
                  <div className="mt-4 text-center">
                    <Button
                      variant="link"
                      onClick={() => setActiveTab("transactions")}
                      className="text-indigo-400 hover:text-indigo-300"
                    >
                      View all transactions
                    </Button>
                  </div>
                </CardContent>
              </Card>
            </div>
          </TabsContent>

          {/* Wallet Tab */}
          <TabsContent value="wallet" className="space-y-6">
            <Card className="bg-slate-800/80 backdrop-blur-sm border-indigo-700/50 shadow-xl shadow-indigo-900/20">
              <CardHeader>
                <CardTitle>Your Wallets</CardTitle>
                <CardDescription className="text-slate-400">
                  Manage your cryptocurrency wallets
                </CardDescription>
              </CardHeader>
              <CardContent className="space-y-6">
                {Object.entries(walletData).map(([coin, data]) => (
                  <div
                    key={coin}
                    className="p-4 bg-slate-700/50 rounded-lg hover:bg-slate-700/70 transition-colors"
                  >
                    <div className="flex justify-between items-center mb-4">
                      <div className="flex items-center">
                        <div
                          className={`w-10 h-10 rounded-full mr-3 flex items-center justify-center ${coin === "BTC" ? "bg-orange-500/30 text-orange-200" : coin === "ETH" ? "bg-blue-500/30 text-blue-200" : "bg-green-500/30 text-green-200"}`}
                        >
                          {coin === "BTC" ? "B" : coin === "ETH" ? "E" : "U"}
                        </div>
                        <div>
                          <h3 className="font-bold">
                            {coin === "BTC"
                              ? "Bitcoin"
                              : coin === "ETH"
                                ? "Ethereum"
                                : "Tether"}
                          </h3>
                          <p className="text-sm text-slate-400">{coin}</p>
                        </div>
                      </div>
                      <div className="text-right">
                        <p className="text-xl font-bold">
                          {data.balance} {coin}
                        </p>
                        <p className="text-sm text-slate-400">
                          ${data.value.toFixed(2)}
                        </p>
                      </div>
                    </div>

                    <div className="flex flex-col space-y-2">
                      <div className="flex items-center justify-between bg-slate-800 p-3 rounded">
                        <span className="text-sm text-slate-400">
                          Wallet Address
                        </span>
                        <div className="flex items-center">
                          <span className="text-sm mr-2 font-mono text-indigo-300">
                            {data.address.substring(0, 6)}...
                            {data.address.substring(data.address.length - 6)}
                          </span>
                          <Button
                            variant="ghost"
                            size="icon"
                            className="h-8 w-8 text-indigo-400 hover:text-indigo-300 hover:bg-indigo-900/30"
                            onClick={() => {
                              navigator.clipboard.writeText(data.address);
                              alert("Address copied to clipboard!");
                            }}
                          >
                            <Copy className="h-4 w-4" />
                          </Button>
                          <Button
                            variant="ghost"
                            size="icon"
                            className="h-8 w-8 text-indigo-400 hover:text-indigo-300 hover:bg-indigo-900/30"
                            onClick={() => handleShowQr(coin)}
                          >
                            <QrCode className="h-4 w-4" />
                          </Button>
                        </div>
                      </div>
                    </div>

                    <div className="grid grid-cols-3 gap-3 mt-4">
                      <Button
                        className="flex items-center justify-center bg-indigo-600 hover:bg-indigo-700"
                        onClick={() => handleShowQr(coin)}
                      >
                        <Upload className="mr-2 h-4 w-4" />
                        Deposit
                      </Button>
                      <Button
                        variant="outline"
                        className="flex items-center justify-center border-indigo-600 text-indigo-400 hover:bg-indigo-900/30"
                        onClick={() => {
                          setSendCoin(coin);
                          setShowSendDialog(true);
                        }}
                      >
                        <Send className="mr-2 h-4 w-4" />
                        Send
                      </Button>
                      <Button
                        variant="secondary"
                        className="flex items-center justify-center bg-slate-700 hover:bg-slate-600"
                        onClick={() => {
                          setWithdrawCoin(coin);
                          setShowSecurityKeyDialog(true);
                        }}
                      >
                        <Download className="mr-2 h-4 w-4" />
                        Withdraw
                      </Button>
                    </div>
                  </div>
                ))}
              </CardContent>
            </Card>
          </TabsContent>

          {/* Market Tab */}
          <TabsContent value="market" className="space-y-6">
            <Card className="bg-slate-800/80 backdrop-blur-sm border-indigo-700/50 shadow-xl shadow-indigo-900/20">
              <CardHeader className="flex flex-row items-center justify-between pb-2">
                <div>
                  <CardTitle>Market Prices</CardTitle>
                  <CardDescription className="text-slate-400">
                    Real-time cryptocurrency prices
                  </CardDescription>
                </div>
                <Button
                  variant="ghost"
                  size="icon"
                  onClick={handleRefresh}
                  disabled={isRefreshing}
                  className="text-indigo-400 hover:text-indigo-300"
                >
                  <RefreshCw
                    className={`h-5 w-5 ${isRefreshing ? "animate-spin" : ""}`}
                  />
                </Button>
              </CardHeader>
              <CardContent>
                <div className="space-y-4">
                  <div className="grid grid-cols-12 text-sm font-medium text-slate-400 p-2">
                    <div className="col-span-5">Coin</div>
                    <div className="col-span-3 text-right">Price</div>
                    <div className="col-span-2 text-right">24h Change</div>
                    <div className="col-span-2 text-right">Action</div>
                  </div>
                  <Separator className="bg-slate-700" />
                  {coinData.map((coin) => (
                    <div
                      key={coin.id}
                      className="grid grid-cols-12 items-center py-3 hover:bg-slate-700/30 rounded-md px-2 transition-colors"
                    >
                      <div className="col-span-5 flex items-center">
                        <div
                          className={`w-8 h-8 rounded-full mr-2 flex items-center justify-center ${coin.symbol === "BTC" ? "bg-orange-500/30 text-orange-200" : coin.symbol === "ETH" ? "bg-blue-500/30 text-blue-200" : coin.symbol === "USDT" ? "bg-green-500/30 text-green-200" : "bg-purple-500/30 text-purple-200"}`}
                        >
                          {coin.symbol.charAt(0)}
                        </div>
                        <div>
                          <p className="font-medium">{coin.name}</p>
                          <p className="text-xs text-slate-400">
                            {coin.symbol}
                          </p>
                        </div>
                      </div>
                      <div className="col-span-3 text-right">
                        <p className="font-medium">
                          ${coin.price.toLocaleString()}
                        </p>
                      </div>
                      <div className="col-span-2 text-right">
                        <p
                          className={`font-medium ${coin.change24h >= 0 ? "text-green-500" : "text-red-500"}`}
                        >
                          {coin.change24h >= 0 ? "+" : ""}
                          {coin.change24h}%
                        </p>
                      </div>
                      <div className="col-span-2 text-right">
                        <Button
                          variant="outline"
                          size="sm"
                          className="border-indigo-600 text-indigo-400 hover:bg-indigo-900/30"
                          onClick={() => {
                            alert(
                              `Trading functionality for ${coin.name} would be implemented in a production version.`,
                            );
                          }}
                        >
                          Trade
                        </Button>
                      </div>
                    </div>
                  ))}
                </div>
              </CardContent>
            </Card>

            <Card className="bg-slate-800/80 backdrop-blur-sm border-indigo-700/50 shadow-xl shadow-indigo-900/20">
              <CardHeader>
                <CardTitle>Market Chart</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="h-[400px] w-full bg-slate-900 rounded-md p-4 relative overflow-hidden">
                  <img
                    src="https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&q=80"
                    alt="Trading Chart Background"
                    className="absolute inset-0 w-full h-full object-cover opacity-20"
                  />
                  <div className="relative z-10 h-full w-full flex flex-col">
                    <div className="flex justify-between mb-4">
                      <div className="flex space-x-4">
                        {coinData.slice(0, 3).map((coin) => (
                          <Button
                            key={coin.id}
                            variant={
                              coin.symbol === "BTC" ? "default" : "outline"
                            }
                            size="sm"
                            className={
                              coin.symbol === "BTC"
                                ? "bg-indigo-600 hover:bg-indigo-700"
                                : "border-indigo-600 text-indigo-400 hover:bg-indigo-900/30"
                            }
                          >
                            {coin.symbol}/USD
                          </Button>
                        ))}
                      </div>
                      <div className="flex space-x-2">
                        <Button
                          size="sm"
                          variant="outline"
                          className="h-8 text-indigo-300 border-indigo-800 hover:bg-indigo-900/30"
                        >
                          1D
                        </Button>
                        <Button
                          size="sm"
                          variant="outline"
                          className="h-8 bg-indigo-900/40 text-indigo-300 border-indigo-800"
                        >
                          1W
                        </Button>
                        <Button
                          size="sm"
                          variant="outline"
                          className="h-8 text-indigo-300 border-indigo-800 hover:bg-indigo-900/30"
                        >
                          1M
                        </Button>
                        <Button
                          size="sm"
                          variant="outline"
                          className="h-8 text-indigo-300 border-indigo-800 hover:bg-indigo-900/30"
                        >
                          1Y
                        </Button>
                      </div>
                    </div>
                    <div className="flex-1 relative">
                      <div className="w-full h-full bg-slate-900 rounded">
                        <TradingChart
                          width={800}
                          height={300}
                          color="#3b82f6"
                          backgroundColor="transparent"
                        />
                      </div>
                      <div className="absolute top-4 left-4 bg-slate-800/90 backdrop-blur-sm p-3 rounded-md border border-slate-700/50 shadow-lg text-white">
                        <h3 className="font-bold text-lg">BTC/USD</h3>
                        <p className="text-green-500 font-medium">
                          $
                          {coinData
                            .find((c) => c.symbol === "BTC")
                            ?.price.toLocaleString()}
                          <span className="text-xs ml-1">
                            +
                            {
                              coinData.find((c) => c.symbol === "BTC")
                                ?.change24h
                            }
                            %
                          </span>
                        </p>
                        <div className="mt-2 grid grid-cols-2 gap-2 text-xs text-white">
                          <div>
                            <p className="text-slate-400">24h High</p>
                            <p className="font-medium text-indigo-300">
                              $
                              {(
                                coinData.find((c) => c.symbol === "BTC")
                                  ?.price * 1.05
                              ).toFixed(2)}
                            </p>
                          </div>
                          <div>
                            <p className="text-slate-400">24h Low</p>
                            <p className="font-medium text-indigo-300">
                              $
                              {(
                                coinData.find((c) => c.symbol === "BTC")
                                  ?.price * 0.95
                              ).toFixed(2)}
                            </p>
                          </div>
                          <div>
                            <p className="text-slate-400">24h Volume</p>
                            <p className="font-medium text-indigo-300">$1.2B</p>
                          </div>
                          <div>
                            <p className="text-slate-400">Market Cap</p>
                            <p className="font-medium text-indigo-300">
                              $825.4B
                            </p>
                          </div>
                        </div>
                      </div>
                      <div className="absolute bottom-0 left-0 right-0 h-1/3 bg-gradient-to-t from-slate-900 to-transparent"></div>
                    </div>
                    <div className="mt-2 flex justify-between text-xs text-slate-400">
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
              </CardContent>
            </Card>
          </TabsContent>

          {/* Transactions Tab */}
          <TabsContent value="transactions" className="space-y-6">
            <Card className="bg-slate-800/80 backdrop-blur-sm border-indigo-700/50 shadow-xl shadow-indigo-900/20">
              <CardHeader>
                <CardTitle>Transaction History</CardTitle>
                <CardDescription className="text-slate-400">
                  View all your deposits and withdrawals
                </CardDescription>
              </CardHeader>
              <CardContent>
                <div className="space-y-4">
                  {transactions.map((tx) => (
                    <div
                      key={tx.id}
                      className="flex items-center justify-between p-4 bg-slate-700/50 rounded-md hover:bg-slate-700/70 transition-colors"
                    >
                      <div className="flex items-center">
                        <div
                          className={`p-3 rounded-full ${tx.type === "deposit" ? "bg-green-500/30 text-green-200" : "bg-red-500/30 text-red-200"} mr-4`}
                        >
                          {tx.type === "deposit" ? (
                            <ArrowUpRight className="h-5 w-5 text-green-500" />
                          ) : (
                            <ArrowDownRight className="h-5 w-5 text-red-500" />
                          )}
                        </div>
                        <div>
                          <p className="font-medium text-lg">
                            {tx.type === "deposit" ? "Deposit" : "Withdrawal"}
                          </p>
                          <p className="text-sm text-slate-400">
                            {new Date(tx.date).toLocaleString()}
                          </p>
                        </div>
                      </div>
                      <div className="text-right">
                        <p
                          className={`font-medium text-lg ${tx.type === "deposit" ? "text-green-500" : "text-red-500"}`}
                        >
                          {tx.type === "deposit" ? "+" : "-"}
                          {tx.amount} {tx.coin}
                        </p>
                        <p className="text-sm text-slate-400">
                          Status:{" "}
                          <span
                            className={`font-medium ${tx.status === "completed" ? "text-green-500" : "text-yellow-500"}`}
                          >
                            {tx.status}
                          </span>
                        </p>
                      </div>
                    </div>
                  ))}
                </div>
              </CardContent>
            </Card>
          </TabsContent>
        </Tabs>
      </div>

      {/* Security Key Dialog for Withdrawals */}
      <Dialog
        open={showSecurityKeyDialog}
        onOpenChange={setShowSecurityKeyDialog}
      >
        <DialogContent className="bg-slate-800/95 backdrop-blur-md text-white border-slate-700/50 shadow-2xl">
          <DialogHeader>
            <DialogTitle>Withdraw Funds</DialogTitle>
            <DialogDescription className="text-slate-400">
              Enter your security key to authorize this withdrawal
            </DialogDescription>
          </DialogHeader>

          <div className="space-y-4 py-4">
            <div className="space-y-2">
              <label className="text-sm font-medium">Withdrawal Amount</label>
              <Input
                type="number"
                placeholder="0.00"
                className="bg-slate-900 border-slate-700"
                value={withdrawAmount}
                onChange={(e) => setWithdrawAmount(e.target.value)}
              />
            </div>

            <div className="space-y-2">
              <label className="text-sm font-medium">Destination Address</label>
              <Input
                placeholder="Enter wallet address"
                className="bg-slate-900 border-slate-700"
                value={withdrawAddress}
                onChange={(e) => setWithdrawAddress(e.target.value)}
              />
            </div>

            <div className="space-y-2">
              <label className="text-sm font-medium">Withdrawal Details</label>
              <div className="p-3 bg-slate-900 border border-slate-700 rounded-md">
                <div className="flex justify-between items-center mb-2">
                  <span className="text-sm text-slate-400">Currency</span>
                  <span className="font-medium text-white">USDT</span>
                </div>
                <div className="flex justify-between items-center mb-2">
                  <span className="text-sm text-slate-400">Network</span>
                  <span className="font-medium text-white">TRC20</span>
                </div>
                <div className="flex justify-between items-center">
                  <span className="text-sm text-slate-400">Fee</span>
                  <span className="font-medium text-white">1.00 USDT</span>
                </div>
              </div>
            </div>

            <div className="space-y-2">
              <div className="flex justify-between">
                <label className="text-sm font-medium">Security Key</label>
                <Button
                  variant="link"
                  className="text-xs p-0 h-auto text-indigo-400 hover:text-indigo-300"
                  onClick={() => {
                    setShowSecurityKeyDialog(false);
                    setShowRecoveryDialog(true);
                  }}
                >
                  Recover your security key
                </Button>
              </div>
              <Input
                type="password"
                placeholder="Enter your security key"
                className="bg-slate-900 border-slate-700"
                value={securityKey}
                onChange={(e) => setSecurityKey(e.target.value)}
              />
            </div>

            <Alert className="bg-yellow-900/30 border-yellow-900/50 shadow-inner">
              <AlertCircle className="h-4 w-4 text-yellow-500" />
              <AlertTitle className="text-yellow-500">
                Security Notice
              </AlertTitle>
              <AlertDescription className="text-yellow-200/70">
                Never share your security key with anyone. Our team will never
                ask for it.
              </AlertDescription>
            </Alert>
          </div>

          <DialogFooter>
            <Button
              variant="outline"
              onClick={() => setShowSecurityKeyDialog(false)}
              className="border-slate-600 hover:bg-slate-700"
            >
              Cancel
            </Button>
            <Button
              onClick={handleWithdraw}
              disabled={!securityKey || !withdrawAmount || !withdrawAddress}
              className="bg-indigo-600 hover:bg-indigo-700"
            >
              Confirm Withdrawal
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {/* Security Key Recovery Dialog */}
      <Dialog open={showRecoveryDialog} onOpenChange={setShowRecoveryDialog}>
        <DialogContent className="bg-slate-800/95 backdrop-blur-md text-white border-slate-700/50 shadow-2xl">
          <DialogHeader>
            <DialogTitle>Security Key Recovery</DialogTitle>
            <DialogDescription className="text-slate-400">
              Recover your security key by completing the verification process
            </DialogDescription>
          </DialogHeader>

          <div className="py-4">
            <div className="mb-6">
              <Progress
                value={(recoveryStep / 3) * 100}
                className="h-2 bg-slate-700"
              />
              <div className="flex justify-between mt-1 text-xs text-slate-400">
                <span>Email</span>
                <span>Payment</span>
                <span>Complete</span>
              </div>
            </div>

            {recoveryStep === 1 && (
              <div className="space-y-4">
                <div className="space-y-2">
                  <label className="text-sm font-medium">Email Address</label>
                  <Input
                    type="email"
                    placeholder="your.email@example.com"
                    className="bg-slate-900 border-slate-700"
                    value={recoveryEmail}
                    onChange={(e) => setRecoveryEmail(e.target.value)}
                  />
                </div>
                <Alert className="bg-blue-900/30 border-blue-900/50 shadow-inner">
                  <AlertCircle className="h-4 w-4 text-blue-500" />
                  <AlertTitle className="text-blue-500">
                    Verification Required
                  </AlertTitle>
                  <AlertDescription className="text-blue-200/70">
                    We'll send your new security key to this email after payment
                    verification.
                  </AlertDescription>
                </Alert>

                <div className="flex flex-col space-y-2 mt-4">
                  <p className="text-sm text-slate-400">
                    Need help? Contact us:
                  </p>
                  <div className="flex space-x-4">
                    <Button
                      variant="outline"
                      className="flex-1 border-blue-600 text-blue-400 hover:bg-blue-900/30"
                    >
                      <Mail className="mr-2 h-4 w-4" />
                      Email Support
                    </Button>
                    <Button
                      variant="outline"
                      className="flex-1 border-green-600 text-green-400 hover:bg-green-900/30"
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        strokeWidth="2"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        className="mr-2"
                      >
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                      </svg>
                      WhatsApp
                    </Button>
                  </div>
                </div>
              </div>
            )}

            {recoveryStep === 2 && (
              <div className="space-y-4">
                <Alert className="bg-yellow-900/30 border-yellow-900/50 shadow-inner">
                  <AlertCircle className="h-4 w-4 text-yellow-500" />
                  <AlertTitle className="text-yellow-500">
                    Payment Required
                  </AlertTitle>
                  <AlertDescription className="text-yellow-200/70">
                    To recover your security key, please send 300 USDT to the
                    address below.
                  </AlertDescription>
                </Alert>

                <div className="p-4 bg-slate-900 rounded-md">
                  <p className="text-sm font-medium mb-2">Send 300 USDT to:</p>
                  <div className="flex items-center justify-between bg-slate-800 p-3 rounded mb-4">
                    <span className="text-sm font-mono text-indigo-300">
                      0xd8dA6BF26964aF9D7eEd9e03E53415D37aA96045
                    </span>
                    <Button
                      variant="ghost"
                      size="icon"
                      className="h-8 w-8 text-indigo-400 hover:text-indigo-300 hover:bg-indigo-900/30"
                      onClick={() => {
                        navigator.clipboard.writeText(
                          "0xd8dA6BF26964aF9D7eEd9e03E53415D37aA96045",
                        );
                        alert("Address copied to clipboard!");
                      }}
                    >
                      <Copy className="h-4 w-4" />
                    </Button>
                  </div>

                  <div className="flex justify-center mb-4">
                    <div className="p-2 bg-white rounded">
                      <img
                        src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=0xd8dA6BF26964aF9D7eEd9e03E53415D37aA96045"
                        alt="QR Code"
                        className="h-32 w-32"
                      />
                    </div>
                  </div>

                  <p className="text-sm text-slate-400 text-center">
                    After sending the payment, click "Verify Payment" below.
                  </p>
                </div>

                <div className="flex flex-col space-y-2 mt-4">
                  <p className="text-sm text-slate-400">
                    Need help with payment? Contact us:
                  </p>
                  <div className="flex space-x-4">
                    <Button
                      variant="outline"
                      className="flex-1 border-blue-600 text-blue-400 hover:bg-blue-900/30"
                    >
                      <Mail className="mr-2 h-4 w-4" />
                      Email Support
                    </Button>
                    <Button
                      variant="outline"
                      className="flex-1 border-green-600 text-green-400 hover:bg-green-900/30"
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        strokeWidth="2"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        className="mr-2"
                      >
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                      </svg>
                      WhatsApp
                    </Button>
                  </div>
                </div>
              </div>
            )}

            {recoveryStep === 3 && (
              <div className="space-y-4 text-center">
                <div className="p-4 rounded-full bg-green-500/30 mx-auto w-16 h-16 flex items-center justify-center shadow-lg shadow-green-900/30">
                  <Shield className="h-8 w-8 text-green-500" />
                </div>
                <h3 className="text-lg font-medium">Recovery Successful!</h3>
                <p className="text-slate-400">
                  Your new security key has been generated and sent to your
                  email address.
                </p>
              </div>
            )}
          </div>

          <DialogFooter>
            {recoveryStep < 3 ? (
              <>
                <Button
                  variant="outline"
                  onClick={() => setShowRecoveryDialog(false)}
                  className="border-slate-600 hover:bg-slate-700"
                >
                  Cancel
                </Button>
                <Button
                  onClick={handleRecoveryNext}
                  disabled={recoveryStep === 1 && !recoveryEmail}
                  className="bg-indigo-600 hover:bg-indigo-700"
                >
                  {recoveryStep === 1
                    ? "Continue"
                    : recoveryStep === 2
                      ? "Verify Payment"
                      : ""}
                </Button>
              </>
            ) : (
              <Button
                onClick={() => setShowRecoveryDialog(false)}
                className="bg-indigo-600 hover:bg-indigo-700"
              >
                Close
              </Button>
            )}
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {/* QR Code Dialog for Deposits */}
      <Dialog open={showQrDialog} onOpenChange={setShowQrDialog}>
        <DialogContent className="bg-slate-800/95 backdrop-blur-md text-white border-slate-700/50 shadow-2xl">
          <DialogHeader>
            <DialogTitle>Deposit {selectedWallet}</DialogTitle>
            <DialogDescription className="text-slate-400">
              Scan this QR code or copy the address to deposit funds
            </DialogDescription>
          </DialogHeader>

          <div className="py-4 space-y-4">
            <div className="flex justify-center mb-4">
              <div className="p-4 bg-white rounded">
                <img
                  src={`https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${walletData[selectedWallet as keyof typeof walletData]?.address || ""}`}
                  alt="QR Code"
                  className="h-40 w-40"
                />
              </div>
            </div>

            <div className="space-y-2">
              <label className="text-sm font-medium">Wallet Address</label>
              <div className="flex items-center">
                <Input
                  readOnly
                  value={
                    walletData[selectedWallet as keyof typeof walletData]
                      ?.address || ""
                  }
                  className="bg-slate-900 border-slate-700 font-mono text-indigo-300"
                />
                <Button
                  variant="ghost"
                  size="icon"
                  className="ml-2 text-indigo-400 hover:text-indigo-300 hover:bg-indigo-900/30"
                  onClick={() => {
                    navigator.clipboard.writeText(
                      walletData[selectedWallet as keyof typeof walletData]
                        ?.address || "",
                    );
                    alert("Address copied to clipboard!");
                  }}
                >
                  <Copy className="h-4 w-4" />
                </Button>
              </div>
            </div>

            <Alert className="bg-blue-900/30 border-blue-900/50 shadow-inner">
              <AlertCircle className="h-4 w-4 text-blue-500" />
              <AlertTitle className="text-blue-500">Important</AlertTitle>
              <AlertDescription className="text-blue-200/70">
                Only send {selectedWallet} to this address. Sending any other
                cryptocurrency may result in permanent loss.
              </AlertDescription>
            </Alert>
          </div>

          <DialogFooter>
            <Button
              onClick={() => {
                setShowQrDialog(false);
                // Simulate deposit for demo purposes
                setTimeout(() => {
                  const newTransaction = {
                    id: transactions.length + 1,
                    type: "deposit",
                    coin: selectedWallet,
                    amount:
                      selectedWallet === "BTC"
                        ? 0.01
                        : selectedWallet === "ETH"
                          ? 0.5
                          : 100,
                    status: "completed",
                    date: new Date().toISOString(),
                  };
                  setTransactions([newTransaction, ...transactions]);

                  // Update wallet balance
                  setWalletData((prev) => ({
                    ...prev,
                    [selectedWallet]: {
                      ...prev[selectedWallet as keyof typeof prev],
                      balance:
                        prev[selectedWallet as keyof typeof prev].balance +
                        newTransaction.amount,
                      value:
                        prev[selectedWallet as keyof typeof prev].value +
                        (selectedWallet === "BTC"
                          ? newTransaction.amount * 95432.78
                          : selectedWallet === "ETH"
                            ? newTransaction.amount * 2356.78
                            : newTransaction.amount),
                    },
                  }));

                  alert(
                    `Deposit of ${newTransaction.amount} ${selectedWallet} completed successfully!`,
                  );
                }, 1000);
              }}
              className="bg-indigo-600 hover:bg-indigo-700"
            >
              Simulate Deposit (Demo)
            </Button>
            <Button
              onClick={() => setShowQrDialog(false)}
              className="border-slate-600 hover:bg-slate-700"
            >
              Close
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {/* Send Funds Dialog */}
      <Dialog open={showSendDialog} onOpenChange={setShowSendDialog}>
        <DialogContent className="bg-slate-800/95 backdrop-blur-md text-white border-slate-700/50 shadow-2xl">
          <DialogHeader>
            <DialogTitle>Send {sendCoin}</DialogTitle>
            <DialogDescription className="text-slate-400">
              Send cryptocurrency to another wallet address
            </DialogDescription>
          </DialogHeader>

          <div className="space-y-4 py-4">
            <div className="space-y-2">
              <label className="text-sm font-medium">Available Balance</label>
              <div className="p-3 bg-slate-900 border border-slate-700 rounded-md flex justify-between items-center">
                <span>
                  {walletData[sendCoin as keyof typeof walletData]?.balance ||
                    0}{" "}
                  {sendCoin}
                </span>
                <span className="text-slate-400">
                  $
                  {walletData[
                    sendCoin as keyof typeof walletData
                  ]?.value.toFixed(2) || 0}
                </span>
              </div>
            </div>

            <div className="space-y-2">
              <label className="text-sm font-medium">Amount to Send</label>
              <Input
                type="number"
                placeholder="0.00"
                className="bg-slate-900 border-slate-700"
                value={sendAmount}
                onChange={(e) => setSendAmount(e.target.value)}
              />
            </div>

            <div className="space-y-2">
              <label className="text-sm font-medium">Recipient Address</label>
              <Input
                placeholder="Enter wallet address"
                className="bg-slate-900 border-slate-700"
                value={sendAddress}
                onChange={(e) => setSendAddress(e.target.value)}
              />
            </div>

            <div className="space-y-2">
              <div className="flex justify-between">
                <label className="text-sm font-medium">Security Key</label>
                <Button
                  variant="link"
                  className="text-xs p-0 h-auto text-indigo-400 hover:text-indigo-300"
                  onClick={() => {
                    setShowSendDialog(false);
                    setShowRecoveryDialog(true);
                  }}
                >
                  Recover your security key
                </Button>
              </div>
              <Input
                type="password"
                placeholder="Enter your security key"
                className="bg-slate-900 border-slate-700"
                value={sendSecurityKey}
                onChange={(e) => setSendSecurityKey(e.target.value)}
              />
            </div>

            <Alert className="bg-yellow-900/30 border-yellow-900/50 shadow-inner">
              <AlertCircle className="h-4 w-4 text-yellow-500" />
              <AlertTitle className="text-yellow-500">
                Security Notice
              </AlertTitle>
              <AlertDescription className="text-yellow-200/70">
                Double-check the recipient address. Cryptocurrency transactions
                cannot be reversed.
              </AlertDescription>
            </Alert>
          </div>

          <DialogFooter>
            <Button
              variant="outline"
              onClick={() => setShowSendDialog(false)}
              className="border-slate-600 hover:bg-slate-700"
            >
              Cancel
            </Button>
            <Button
              onClick={() => {
                if (!sendSecurityKey) {
                  alert(
                    "Please enter your security key to authorize this transaction.",
                  );
                  return;
                }

                if (!sendAmount || parseFloat(sendAmount) <= 0) {
                  alert("Please enter a valid amount to send.");
                  return;
                }

                if (!sendAddress) {
                  alert("Please enter a recipient address.");
                  return;
                }

                const amount = parseFloat(sendAmount);
                const balance =
                  walletData[sendCoin as keyof typeof walletData]?.balance || 0;

                if (amount > balance) {
                  alert(
                    `Insufficient balance. You only have ${balance} ${sendCoin} available.`,
                  );
                  return;
                }

                // Process send transaction
                const newTransaction = {
                  id: transactions.length + 1,
                  type: "withdrawal",
                  coin: sendCoin,
                  amount: amount,
                  status: "completed",
                  date: new Date().toISOString(),
                };

                setTransactions([newTransaction, ...transactions]);

                // Update wallet balance
                setWalletData((prev) => ({
                  ...prev,
                  [sendCoin]: {
                    ...prev[sendCoin as keyof typeof prev],
                    balance:
                      prev[sendCoin as keyof typeof prev].balance - amount,
                    value:
                      prev[sendCoin as keyof typeof prev].value -
                      (sendCoin === "BTC"
                        ? amount * 95432.78
                        : sendCoin === "ETH"
                          ? amount * 2356.78
                          : amount),
                  },
                }));

                setShowSendDialog(false);
                setSendAmount("");
                setSendAddress("");
                setSendSecurityKey("");

                alert(
                  `Successfully sent ${amount} ${sendCoin} to ${sendAddress.substring(0, 6)}...${sendAddress.substring(sendAddress.length - 6)}`,
                );
              }}
              disabled={!sendSecurityKey || !sendAmount || !sendAddress}
              className="bg-indigo-600 hover:bg-indigo-700"
            >
              Send {sendCoin}
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {/* Settings Dialog */}
      <Dialog open={showSettingsDialog} onOpenChange={setShowSettingsDialog}>
        <DialogContent className="bg-slate-800/95 backdrop-blur-md text-white border-slate-700/50 shadow-2xl">
          <DialogHeader>
            <DialogTitle>Account Settings</DialogTitle>
            <DialogDescription className="text-slate-400">
              Manage your personal information and preferences
            </DialogDescription>
          </DialogHeader>

          <div className="py-4 space-y-6">
            <div className="space-y-4">
              <h3 className="text-sm font-medium text-indigo-400">
                Personal Information
              </h3>
              <div className="space-y-3">
                <div className="space-y-1">
                  <label className="text-sm font-medium">Full Name</label>
                  <Input
                    value={userSettings.fullName}
                    onChange={(e) =>
                      setUserSettings({
                        ...userSettings,
                        fullName: e.target.value,
                      })
                    }
                    className="bg-slate-900 border-slate-700"
                  />
                </div>
                <div className="space-y-1">
                  <label className="text-sm font-medium">Email Address</label>
                  <Input
                    type="email"
                    value={userSettings.email}
                    onChange={(e) =>
                      setUserSettings({
                        ...userSettings,
                        email: e.target.value,
                      })
                    }
                    className="bg-slate-900 border-slate-700"
                  />
                </div>
                <div className="space-y-1">
                  <label className="text-sm font-medium">Phone Number</label>
                  <Input
                    value={userSettings.phone}
                    onChange={(e) =>
                      setUserSettings({
                        ...userSettings,
                        phone: e.target.value,
                      })
                    }
                    className="bg-slate-900 border-slate-700"
                  />
                </div>
              </div>
            </div>

            <Separator className="bg-slate-700" />

            <div className="space-y-4">
              <h3 className="text-sm font-medium text-indigo-400">Security</h3>
              <div className="flex items-center justify-between">
                <div>
                  <p className="font-medium">Two-Factor Authentication</p>
                  <p className="text-sm text-slate-400">
                    Add an extra layer of security to your account
                  </p>
                </div>
                <Button
                  variant={
                    userSettings.twoFactorEnabled ? "default" : "outline"
                  }
                  onClick={() =>
                    setUserSettings({
                      ...userSettings,
                      twoFactorEnabled: !userSettings.twoFactorEnabled,
                    })
                  }
                  className={
                    userSettings.twoFactorEnabled
                      ? "bg-indigo-600 hover:bg-indigo-700"
                      : "border-indigo-600 text-indigo-400 hover:bg-indigo-900/30"
                  }
                >
                  {userSettings.twoFactorEnabled ? "Enabled" : "Disabled"}
                </Button>
              </div>
              <div className="flex items-center justify-between">
                <div>
                  <p className="font-medium">Change Security Key</p>
                  <p className="text-sm text-slate-400">
                    Update your account recovery key
                  </p>
                </div>
                <Button
                  variant="outline"
                  className="border-indigo-600 text-indigo-400 hover:bg-indigo-900/30"
                >
                  Change Key
                </Button>
              </div>
            </div>

            <Separator className="bg-slate-700" />

            <div className="space-y-4">
              <h3 className="text-sm font-medium text-indigo-400">
                Preferences
              </h3>
              <div className="flex items-center justify-between">
                <div>
                  <p className="font-medium">Email Notifications</p>
                  <p className="text-sm text-slate-400">
                    Receive alerts for transactions and security events
                  </p>
                </div>
                <Button
                  variant={
                    userSettings.notificationsEnabled ? "default" : "outline"
                  }
                  onClick={() =>
                    setUserSettings({
                      ...userSettings,
                      notificationsEnabled: !userSettings.notificationsEnabled,
                    })
                  }
                  className={
                    userSettings.notificationsEnabled
                      ? "bg-indigo-600 hover:bg-indigo-700"
                      : "border-indigo-600 text-indigo-400 hover:bg-indigo-900/30"
                  }
                >
                  {userSettings.notificationsEnabled ? "Enabled" : "Disabled"}
                </Button>
              </div>
            </div>
          </div>

          <DialogFooter>
            <Button
              variant="outline"
              onClick={() => setShowSettingsDialog(false)}
              className="border-slate-600 hover:bg-slate-700"
            >
              Cancel
            </Button>
            <Button
              onClick={() => {
                setShowSettingsDialog(false);
                alert("Settings saved successfully!");
              }}
              className="bg-indigo-600 hover:bg-indigo-700"
            >
              Save Changes
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>
  );
};

export default UserDashboard;
