import React, { useState, useEffect } from "react";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
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
} from "@/components/ui/dialog";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Separator } from "@/components/ui/separator";
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import { Badge } from "@/components/ui/badge";
import { Progress } from "@/components/ui/progress";
import {
  Users,
  User,
  Wallet,
  BarChart3,
  DollarSign,
  Plus,
  Search,
  Edit,
  Trash2,
  LogOut,
  ArrowUpRight,
  ArrowDownRight,
  RefreshCw,
  Shield,
  AlertCircle,
} from "lucide-react";

// Mock data for demonstration
const mockUsers = [
  {
    id: 1,
    username: "john_doe",
    email: "john@example.com",
    fullName: "John Doe",
    status: "active",
    joinDate: "2023-01-15",
    wallets: {
      BTC: { balance: 0.0345, value: 1468.6 },
      ETH: { balance: 1.245, value: 2934.19 },
      USDT: { balance: 5000.0, value: 5000.0 },
    },
  },
  {
    id: 2,
    username: "jane_smith",
    email: "jane@example.com",
    fullName: "Jane Smith",
    status: "active",
    joinDate: "2023-02-20",
    wallets: {
      BTC: { balance: 0.125, value: 5321.03 },
      ETH: { balance: 3.5, value: 8248.73 },
      USDT: { balance: 12500.0, value: 12500.0 },
    },
  },
  {
    id: 3,
    username: "mike_wilson",
    email: "mike@example.com",
    fullName: "Mike Wilson",
    status: "inactive",
    joinDate: "2023-03-10",
    wallets: {
      BTC: { balance: 0.0, value: 0.0 },
      ETH: { balance: 0.75, value: 1767.59 },
      USDT: { balance: 2000.0, value: 2000.0 },
    },
  },
  {
    id: 4,
    username: "sarah_johnson",
    email: "sarah@example.com",
    fullName: "Sarah Johnson",
    status: "active",
    joinDate: "2023-04-05",
    wallets: {
      BTC: { balance: 0.22, value: 9364.98 },
      ETH: { balance: 5.0, value: 11783.9 },
      USDT: { balance: 8000.0, value: 8000.0 },
    },
  },
  {
    id: 5,
    username: "alex_brown",
    email: "alex@example.com",
    fullName: "Alex Brown",
    status: "active",
    joinDate: "2023-05-12",
    wallets: {
      BTC: { balance: 0.05, value: 2128.41 },
      ETH: { balance: 1.0, value: 2356.78 },
      USDT: { balance: 3000.0, value: 3000.0 },
    },
  },
];

const mockTransactions = [
  {
    id: 1,
    userId: 1,
    username: "john_doe",
    type: "deposit",
    coin: "BTC",
    amount: 0.0145,
    status: "completed",
    date: "2023-06-15T14:23:45Z",
  },
  {
    id: 2,
    userId: 1,
    username: "john_doe",
    type: "withdrawal",
    coin: "USDT",
    amount: 500,
    status: "completed",
    date: "2023-06-10T09:15:22Z",
  },
  {
    id: 3,
    userId: 2,
    username: "jane_smith",
    type: "deposit",
    coin: "ETH",
    amount: 0.5,
    status: "completed",
    date: "2023-06-05T18:45:30Z",
  },
  {
    id: 4,
    userId: 3,
    username: "mike_wilson",
    type: "withdrawal",
    coin: "BTC",
    amount: 0.002,
    status: "pending",
    date: "2023-06-01T11:32:15Z",
  },
  {
    id: 5,
    userId: 4,
    username: "sarah_johnson",
    type: "deposit",
    coin: "USDT",
    amount: 1000,
    status: "completed",
    date: "2023-05-28T16:20:45Z",
  },
  {
    id: 6,
    userId: 5,
    username: "alex_brown",
    type: "withdrawal",
    coin: "ETH",
    amount: 0.25,
    status: "pending",
    date: "2023-05-25T10:12:33Z",
  },
  {
    id: 7,
    userId: 2,
    username: "jane_smith",
    type: "deposit",
    coin: "BTC",
    amount: 0.075,
    status: "completed",
    date: "2023-05-20T08:45:12Z",
  },
];

const AdminDashboard = () => {
  const [activeTab, setActiveTab] = useState("overview");
  const [users, setUsers] = useState(mockUsers);
  const [transactions, setTransactions] = useState(mockTransactions);
  const [searchTerm, setSearchTerm] = useState("");
  const [showAddUserDialog, setShowAddUserDialog] = useState(false);
  const [showEditUserDialog, setShowEditUserDialog] = useState(false);
  const [showDeleteUserDialog, setShowDeleteUserDialog] = useState(false);
  const [showManageFundsDialog, setShowManageFundsDialog] = useState(false);
  const [selectedUser, setSelectedUser] = useState<any>(null);
  const [newUser, setNewUser] = useState({
    username: "",
    email: "",
    fullName: "",
    password: "",
  });
  const [fundAction, setFundAction] = useState<"add" | "remove">("add");
  const [fundAmount, setFundAmount] = useState("");
  const [fundCoin, setFundCoin] = useState("BTC");
  const [isRefreshing, setIsRefreshing] = useState(false);

  const filteredUsers = users.filter(
    (user) =>
      user.username.toLowerCase().includes(searchTerm.toLowerCase()) ||
      user.email.toLowerCase().includes(searchTerm.toLowerCase()) ||
      user.fullName.toLowerCase().includes(searchTerm.toLowerCase()),
  );

  const handleRefresh = () => {
    setIsRefreshing(true);
    setTimeout(() => {
      setIsRefreshing(false);
    }, 1000);
  };

  const handleAddUser = () => {
    const newId = Math.max(...users.map((user) => user.id)) + 1;
    const newUserData = {
      id: newId,
      username: newUser.username,
      email: newUser.email,
      fullName: newUser.fullName,
      status: "active",
      joinDate: new Date().toISOString().split("T")[0],
      wallets: {
        BTC: { balance: 0.0, value: 0.0 },
        ETH: { balance: 0.0, value: 0.0 },
        USDT: { balance: 0.0, value: 0.0 },
      },
    };

    setUsers([...users, newUserData]);
    setNewUser({ username: "", email: "", fullName: "", password: "" });
    setShowAddUserDialog(false);
    alert("User added successfully!");
  };

  const handleEditUser = () => {
    if (!selectedUser) return;

    const updatedUsers = users.map((user) =>
      user.id === selectedUser.id
        ? {
            ...user,
            username: selectedUser.username,
            email: selectedUser.email,
            fullName: selectedUser.fullName,
            status: selectedUser.status,
          }
        : user,
    );

    setUsers(updatedUsers);
    setShowEditUserDialog(false);
    alert("User updated successfully!");
  };

  const handleDeleteUser = () => {
    if (!selectedUser) return;

    const updatedUsers = users.filter((user) => user.id !== selectedUser.id);
    const updatedTransactions = transactions.filter(
      (tx) => tx.userId !== selectedUser.id,
    );

    setUsers(updatedUsers);
    setTransactions(updatedTransactions);
    setShowDeleteUserDialog(false);
    alert("User deleted successfully!");
  };

  const handleManageFunds = () => {
    if (!selectedUser || !fundAmount || parseFloat(fundAmount) <= 0) return;

    const amount = parseFloat(fundAmount);
    const coinPrice =
      fundCoin === "BTC" ? 42568.23 : fundCoin === "ETH" ? 2356.78 : 1.0;

    // Update user's wallet
    const updatedUsers = users.map((user) => {
      if (user.id === selectedUser.id) {
        const currentBalance = user.wallets[fundCoin].balance;
        const newBalance =
          fundAction === "add"
            ? currentBalance + amount
            : Math.max(0, currentBalance - amount);

        return {
          ...user,
          wallets: {
            ...user.wallets,
            [fundCoin]: {
              balance: newBalance,
              value: newBalance * coinPrice,
            },
          },
        };
      }
      return user;
    });

    // Add transaction record
    const newTransaction = {
      id: Math.max(...transactions.map((tx) => tx.id)) + 1,
      userId: selectedUser.id,
      username: selectedUser.username,
      type: fundAction === "add" ? "deposit" : "withdrawal",
      coin: fundCoin,
      amount: amount,
      status: "completed",
      date: new Date().toISOString(),
    };

    setUsers(updatedUsers);
    setTransactions([newTransaction, ...transactions]);
    setShowManageFundsDialog(false);
    setFundAmount("");
    alert(
      `Successfully ${fundAction === "add" ? "added" : "removed"} ${amount} ${fundCoin} ${fundAction === "add" ? "to" : "from"} ${selectedUser.username}'s wallet.`,
    );
  };

  const getTotalInvestment = () => {
    return users.reduce(
      (total, user) =>
        total +
        Object.values(user.wallets).reduce(
          (userTotal, wallet) => userTotal + wallet.value,
          0,
        ),
      0,
    );
  };

  const getActiveTradingVolume = () => {
    // Calculate last 30 days trading volume
    const thirtyDaysAgo = new Date();
    thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);

    return transactions
      .filter((tx) => new Date(tx.date) >= thirtyDaysAgo)
      .reduce((total, tx) => {
        const coinPrice =
          tx.coin === "BTC" ? 42568.23 : tx.coin === "ETH" ? 2356.78 : 1.0;
        return total + tx.amount * coinPrice;
      }, 0);
  };

  const getActiveUsers = () => {
    return users.filter((user) => user.status === "active").length;
  };

  const getPendingTransactions = () => {
    return transactions.filter((tx) => tx.status === "pending").length;
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-900 via-indigo-900 to-slate-800 text-white bg-[url('https://images.unsplash.com/photo-1639762681485-074b7f938ba0?w=1600&q=80')] bg-cover bg-blend-overlay bg-opacity-90">
      {/* Header */}
      <header className="w-full h-20 border-b border-slate-700/50 backdrop-blur-sm bg-slate-900/70 flex items-center justify-between px-6 sticky top-0 z-50">
        <div className="flex items-center space-x-4">
          <Shield className="h-8 w-8 text-indigo-400" />
          <h1 className="text-xl font-bold">CryptoTrade Admin Panel</h1>
        </div>
        <div className="flex items-center space-x-4">
          <div className="text-right">
            <p className="text-sm text-slate-300">Logged in as</p>
            <p className="font-medium text-indigo-300">Administrator</p>
          </div>
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
          <TabsList className="grid w-full max-w-4xl mx-auto grid-cols-4 mb-8 bg-slate-800/80 backdrop-blur-sm border border-slate-700/50">
            <TabsTrigger value="overview">Overview</TabsTrigger>
            <TabsTrigger value="users">Users</TabsTrigger>
            <TabsTrigger value="transactions">Transactions</TabsTrigger>
            <TabsTrigger value="settings">Settings</TabsTrigger>
          </TabsList>

          {/* Overview Tab */}
          <TabsContent value="overview" className="space-y-6">
            <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
              {/* Stats Cards */}
              <Card className="bg-slate-800/80 backdrop-blur-sm border-slate-700/50 shadow-xl shadow-indigo-900/20">
                <CardContent className="p-6">
                  <div className="flex items-center justify-between">
                    <div>
                      <p className="text-sm text-slate-400">Total Users</p>
                      <p className="text-3xl font-bold text-white">
                        {users.length}
                      </p>
                    </div>
                    <div className="p-3 bg-indigo-500/20 rounded-full">
                      <Users className="h-6 w-6 text-indigo-400" />
                    </div>
                  </div>
                  <div className="mt-4">
                    <p className="text-sm text-indigo-300">
                      <span className="font-medium">{getActiveUsers()}</span>{" "}
                      active users
                    </p>
                  </div>
                </CardContent>
              </Card>

              <Card className="bg-slate-800/80 backdrop-blur-sm border-slate-700/50 shadow-xl shadow-indigo-900/20">
                <CardContent className="p-6">
                  <div className="flex items-center justify-between">
                    <div>
                      <p className="text-sm text-slate-400">Total Investment</p>
                      <p className="text-3xl font-bold text-white">
                        ${getTotalInvestment().toLocaleString()}
                      </p>
                    </div>
                    <div className="p-3 bg-green-500/20 rounded-full">
                      <DollarSign className="h-6 w-6 text-green-400" />
                    </div>
                  </div>
                  <div className="mt-4">
                    <p className="text-sm text-green-300">
                      <span className="font-medium">+12.5%</span> from last
                      month
                    </p>
                  </div>
                </CardContent>
              </Card>

              <Card className="bg-slate-800/80 backdrop-blur-sm border-slate-700/50 shadow-xl shadow-indigo-900/20">
                <CardContent className="p-6">
                  <div className="flex items-center justify-between">
                    <div>
                      <p className="text-sm text-slate-400">
                        Trading Volume (30d)
                      </p>
                      <p className="text-3xl font-bold text-white">
                        ${getActiveTradingVolume().toLocaleString()}
                      </p>
                    </div>
                    <div className="p-3 bg-blue-500/20 rounded-full">
                      <BarChart3 className="h-6 w-6 text-blue-400" />
                    </div>
                  </div>
                  <div className="mt-4">
                    <p className="text-sm text-blue-300">
                      <span className="font-medium">{transactions.length}</span>{" "}
                      total transactions
                    </p>
                  </div>
                </CardContent>
              </Card>

              <Card className="bg-slate-800/80 backdrop-blur-sm border-slate-700/50 shadow-xl shadow-indigo-900/20">
                <CardContent className="p-6">
                  <div className="flex items-center justify-between">
                    <div>
                      <p className="text-sm text-slate-400">
                        Pending Transactions
                      </p>
                      <p className="text-3xl font-bold text-white">
                        {getPendingTransactions()}
                      </p>
                    </div>
                    <div className="p-3 bg-yellow-500/20 rounded-full">
                      <AlertCircle className="h-6 w-6 text-yellow-400" />
                    </div>
                  </div>
                  <div className="mt-4">
                    <p className="text-sm text-yellow-300">
                      <span className="font-medium">
                        {getPendingTransactions()}
                      </span>{" "}
                      require approval
                    </p>
                  </div>
                </CardContent>
              </Card>
            </div>

            {/* Recent Activity */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
              <Card className="bg-slate-800/80 backdrop-blur-sm border-slate-700/50 col-span-2 shadow-xl shadow-indigo-900/20">
                <CardHeader>
                  <div className="flex justify-between items-center">
                    <CardTitle>Recent Transactions</CardTitle>
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
                  </div>
                  <CardDescription className="text-slate-400">
                    Latest activity across all users
                  </CardDescription>
                </CardHeader>
                <CardContent>
                  <div className="space-y-4">
                    {transactions.slice(0, 5).map((tx) => (
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
                              {tx.username} -{" "}
                              {tx.type === "deposit" ? "Deposit" : "Withdrawal"}
                            </p>
                            <p className="text-xs text-slate-400">
                              {new Date(tx.date).toLocaleString()}
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
                          <Badge
                            variant="outline"
                            className={`${tx.status === "completed" ? "border-green-500 text-green-400" : "border-yellow-500 text-yellow-400"}`}
                          >
                            {tx.status}
                          </Badge>
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

              <Card className="bg-slate-800/80 backdrop-blur-sm border-slate-700/50 shadow-xl shadow-indigo-900/20">
                <CardHeader>
                  <CardTitle>Top Users</CardTitle>
                  <CardDescription className="text-slate-400">
                    Users with highest investment
                  </CardDescription>
                </CardHeader>
                <CardContent>
                  <div className="space-y-4">
                    {users
                      .sort(
                        (a, b) =>
                          Object.values(b.wallets).reduce(
                            (total, wallet) => total + wallet.value,
                            0,
                          ) -
                          Object.values(a.wallets).reduce(
                            (total, wallet) => total + wallet.value,
                            0,
                          ),
                      )
                      .slice(0, 5)
                      .map((user, index) => {
                        const totalValue = Object.values(user.wallets).reduce(
                          (total, wallet) => total + wallet.value,
                          0,
                        );
                        const percentage =
                          (totalValue / getTotalInvestment()) * 100;

                        return (
                          <div key={user.id} className="space-y-2">
                            <div className="flex justify-between items-center">
                              <div className="flex items-center">
                                <div className="w-8 h-8 rounded-full bg-indigo-500/30 text-indigo-200 flex items-center justify-center mr-2">
                                  {index + 1}
                                </div>
                                <div>
                                  <p className="font-medium">{user.fullName}</p>
                                  <p className="text-xs text-slate-400">
                                    {user.username}
                                  </p>
                                </div>
                              </div>
                              <p className="font-medium">
                                ${totalValue.toLocaleString()}
                              </p>
                            </div>
                            <div className="w-full bg-slate-700 rounded-full h-1.5">
                              <div
                                className="bg-indigo-500 h-1.5 rounded-full"
                                style={{ width: `${percentage}%` }}
                              ></div>
                            </div>
                          </div>
                        );
                      })}
                  </div>
                  <div className="mt-4 text-center">
                    <Button
                      variant="link"
                      onClick={() => setActiveTab("users")}
                      className="text-indigo-400 hover:text-indigo-300"
                    >
                      View all users
                    </Button>
                  </div>
                </CardContent>
              </Card>
            </div>
          </TabsContent>

          {/* Users Tab */}
          <TabsContent value="users" className="space-y-6">
            <Card className="bg-slate-800/80 backdrop-blur-sm border-slate-700/50 shadow-xl shadow-indigo-900/20">
              <CardHeader>
                <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                  <CardTitle>User Management</CardTitle>
                  <div className="flex flex-col sm:flex-row gap-3">
                    <div className="relative">
                      <Search className="absolute left-3 top-2.5 h-4 w-4 text-slate-400" />
                      <Input
                        placeholder="Search users..."
                        className="pl-10 bg-slate-900/50 border-slate-700"
                        value={searchTerm}
                        onChange={(e) => setSearchTerm(e.target.value)}
                      />
                    </div>
                    <Button
                      className="bg-indigo-600 hover:bg-indigo-700 flex items-center"
                      onClick={() => setShowAddUserDialog(true)}
                    >
                      <Plus className="mr-2 h-4 w-4" />
                      Add User
                    </Button>
                  </div>
                </div>
                <CardDescription className="text-slate-400">
                  Manage user accounts and balances
                </CardDescription>
              </CardHeader>
              <CardContent>
                <div className="rounded-md border border-slate-700 overflow-hidden">
                  <Table>
                    <TableHeader className="bg-slate-900/50">
                      <TableRow>
                        <TableHead className="text-slate-400">User</TableHead>
                        <TableHead className="text-slate-400">Status</TableHead>
                        <TableHead className="text-slate-400">BTC</TableHead>
                        <TableHead className="text-slate-400">ETH</TableHead>
                        <TableHead className="text-slate-400">USDT</TableHead>
                        <TableHead className="text-slate-400 text-right">
                          Total Value
                        </TableHead>
                        <TableHead className="text-slate-400 text-right">
                          Actions
                        </TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {filteredUsers.length === 0 ? (
                        <TableRow>
                          <TableCell
                            colSpan={7}
                            className="text-center py-6 text-slate-400"
                          >
                            No users found
                          </TableCell>
                        </TableRow>
                      ) : (
                        filteredUsers.map((user) => {
                          const totalValue = Object.values(user.wallets).reduce(
                            (total, wallet) => total + wallet.value,
                            0,
                          );

                          return (
                            <TableRow
                              key={user.id}
                              className="hover:bg-slate-700/30 border-slate-700"
                            >
                              <TableCell>
                                <div>
                                  <p className="font-medium">{user.fullName}</p>
                                  <p className="text-xs text-slate-400">
                                    {user.email}
                                  </p>
                                </div>
                              </TableCell>
                              <TableCell>
                                <Badge
                                  variant="outline"
                                  className={`${user.status === "active" ? "border-green-500 text-green-400" : "border-red-500 text-red-400"}`}
                                >
                                  {user.status}
                                </Badge>
                              </TableCell>
                              <TableCell>
                                <div>
                                  <p className="font-medium">
                                    {user.wallets.BTC.balance} BTC
                                  </p>
                                  <p className="text-xs text-slate-400">
                                    ${user.wallets.BTC.value.toLocaleString()}
                                  </p>
                                </div>
                              </TableCell>
                              <TableCell>
                                <div>
                                  <p className="font-medium">
                                    {user.wallets.ETH.balance} ETH
                                  </p>
                                  <p className="text-xs text-slate-400">
                                    ${user.wallets.ETH.value.toLocaleString()}
                                  </p>
                                </div>
                              </TableCell>
                              <TableCell>
                                <div>
                                  <p className="font-medium">
                                    {user.wallets.USDT.balance} USDT
                                  </p>
                                  <p className="text-xs text-slate-400">
                                    ${user.wallets.USDT.value.toLocaleString()}
                                  </p>
                                </div>
                              </TableCell>
                              <TableCell className="text-right">
                                <p className="font-medium text-indigo-300">
                                  ${totalValue.toLocaleString()}
                                </p>
                              </TableCell>
                              <TableCell className="text-right">
                                <div className="flex justify-end space-x-2">
                                  <Button
                                    variant="outline"
                                    size="sm"
                                    className="h-8 border-indigo-600 text-indigo-400 hover:bg-indigo-900/30"
                                    onClick={() => {
                                      setSelectedUser(user);
                                      setShowManageFundsDialog(true);
                                    }}
                                  >
                                    Funds
                                  </Button>
                                  <Button
                                    variant="outline"
                                    size="sm"
                                    className="h-8 border-blue-600 text-blue-400 hover:bg-blue-900/30"
                                    onClick={() => {
                                      setSelectedUser({ ...user });
                                      setShowEditUserDialog(true);
                                    }}
                                  >
                                    <Edit className="h-3.5 w-3.5" />
                                  </Button>
                                  <Button
                                    variant="outline"
                                    size="sm"
                                    className="h-8 border-red-600 text-red-400 hover:bg-red-900/30"
                                    onClick={() => {
                                      setSelectedUser(user);
                                      setShowDeleteUserDialog(true);
                                    }}
                                  >
                                    <Trash2 className="h-3.5 w-3.5" />
                                  </Button>
                                </div>
                              </TableCell>
                            </TableRow>
                          );
                        })
                      )}
                    </TableBody>
                  </Table>
                </div>
              </CardContent>
            </Card>
          </TabsContent>

          {/* Transactions Tab */}
          <TabsContent value="transactions" className="space-y-6">
            <Card className="bg-slate-800/80 backdrop-blur-sm border-slate-700/50 shadow-xl shadow-indigo-900/20">
              <CardHeader>
                <div className="flex justify-between items-center">
                  <CardTitle>Transaction History</CardTitle>
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
                </div>
                <CardDescription className="text-slate-400">
                  All deposits and withdrawals across the platform
                </CardDescription>
              </CardHeader>
              <CardContent>
                <div className="rounded-md border border-slate-700 overflow-hidden">
                  <Table>
                    <TableHeader className="bg-slate-900/50">
                      <TableRow>
                        <TableHead className="text-slate-400">User</TableHead>
                        <TableHead className="text-slate-400">Type</TableHead>
                        <TableHead className="text-slate-400">Amount</TableHead>
                        <TableHead className="text-slate-400">Status</TableHead>
                        <TableHead className="text-slate-400">Date</TableHead>
                        <TableHead className="text-slate-400 text-right">
                          Actions
                        </TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {transactions.map((tx) => (
                        <TableRow
                          key={tx.id}
                          className="hover:bg-slate-700/30 border-slate-700"
                        >
                          <TableCell>{tx.username}</TableCell>
                          <TableCell>
                            <div className="flex items-center">
                              <div
                                className={`p-1.5 rounded-full ${tx.type === "deposit" ? "bg-green-500/30" : "bg-red-500/30"} mr-2`}
                              >
                                {tx.type === "deposit" ? (
                                  <ArrowUpRight className="h-3 w-3 text-green-500" />
                                ) : (
                                  <ArrowDownRight className="h-3 w-3 text-red-500" />
                                )}
                              </div>
                              <span
                                className={`${tx.type === "deposit" ? "text-green-400" : "text-red-400"}`}
                              >
                                {tx.type === "deposit"
                                  ? "Deposit"
                                  : "Withdrawal"}
                              </span>
                            </div>
                          </TableCell>
                          <TableCell>
                            <div>
                              <p className="font-medium">
                                {tx.amount} {tx.coin}
                              </p>
                              <p className="text-xs text-slate-400">
                                $
                                {(
                                  tx.amount *
                                  (tx.coin === "BTC"
                                    ? 42568.23
                                    : tx.coin === "ETH"
                                      ? 2356.78
                                      : 1.0)
                                ).toLocaleString()}
                              </p>
                            </div>
                          </TableCell>
                          <TableCell>
                            <Badge
                              variant="outline"
                              className={`${tx.status === "completed" ? "border-green-500 text-green-400" : "border-yellow-500 text-yellow-400"}`}
                            >
                              {tx.status}
                            </Badge>
                          </TableCell>
                          <TableCell>
                            {new Date(tx.date).toLocaleString()}
                          </TableCell>
                          <TableCell className="text-right">
                            {tx.status === "pending" && (
                              <div className="flex justify-end space-x-2">
                                <Button
                                  variant="outline"
                                  size="sm"
                                  className="h-8 border-green-600 text-green-400 hover:bg-green-900/30"
                                  onClick={() => {
                                    const updatedTransactions =
                                      transactions.map((t) =>
                                        t.id === tx.id
                                          ? { ...t, status: "completed" }
                                          : t,
                                      );
                                    setTransactions(updatedTransactions);
                                    alert("Transaction approved!");
                                  }}
                                >
                                  Approve
                                </Button>
                                <Button
                                  variant="outline"
                                  size="sm"
                                  className="h-8 border-red-600 text-red-400 hover:bg-red-900/30"
                                  onClick={() => {
                                    const updatedTransactions =
                                      transactions.filter(
                                        (t) => t.id !== tx.id,
                                      );
                                    setTransactions(updatedTransactions);
                                    alert("Transaction rejected!");
                                  }}
                                >
                                  Reject
                                </Button>
                              </div>
                            )}
                            {tx.status === "completed" && (
                              <Button
                                variant="outline"
                                size="sm"
                                className="h-8 border-slate-600 text-slate-400 hover:bg-slate-700/50"
                                onClick={() => {
                                  alert("Transaction details viewed");
                                }}
                              >
                                View
                              </Button>
                            )}
                          </TableCell>
                        </TableRow>
                      ))}
                    </TableBody>
                  </Table>
                </div>
              </CardContent>
            </Card>
          </TabsContent>

          {/* Settings Tab */}
          <TabsContent value="settings" className="space-y-6">
            <Card className="bg-slate-800/80 backdrop-blur-sm border-slate-700/50 shadow-xl shadow-indigo-900/20">
              <CardHeader>
                <CardTitle>Platform Settings</CardTitle>
                <CardDescription className="text-slate-400">
                  Configure system-wide settings
                </CardDescription>
              </CardHeader>
              <CardContent className="space-y-6">
                <div className="space-y-4">
                  <h3 className="text-lg font-medium">Security</h3>
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div className="space-y-2">
                      <label className="text-sm font-medium">
                        Minimum Withdrawal Amount
                      </label>
                      <div className="flex">
                        <Input
                          type="number"
                          placeholder="0.001"
                          defaultValue="0.001"
                          className="bg-slate-900/50 border-slate-700"
                        />
                        <Button className="ml-2 bg-indigo-600 hover:bg-indigo-700">
                          Save
                        </Button>
                      </div>
                    </div>
                    <div className="space-y-2">
                      <label className="text-sm font-medium">
                        Maximum Withdrawal Amount
                      </label>
                      <div className="flex">
                        <Input
                          type="number"
                          placeholder="10.0"
                          defaultValue="10.0"
                          className="bg-slate-900/50 border-slate-700"
                        />
                        <Button className="ml-2 bg-indigo-600 hover:bg-indigo-700">
                          Save
                        </Button>
                      </div>
                    </div>
                  </div>
                </div>

                <Separator className="bg-slate-700" />

                <div className="space-y-4">
                  <h3 className="text-lg font-medium">Fees</h3>
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div className="space-y-2">
                      <label className="text-sm font-medium">
                        Withdrawal Fee (%)
                      </label>
                      <div className="flex">
                        <Input
                          type="number"
                          placeholder="1.5"
                          defaultValue="1.5"
                          className="bg-slate-900/50 border-slate-700"
                        />
                        <Button className="ml-2 bg-indigo-600 hover:bg-indigo-700">
                          Save
                        </Button>
                      </div>
                    </div>
                    <div className="space-y-2">
                      <label className="text-sm font-medium">
                        Trading Fee (%)
                      </label>
                      <div className="flex">
                        <Input
                          type="number"
                          placeholder="0.5"
                          defaultValue="0.5"
                          className="bg-slate-900/50 border-slate-700"
                        />
                        <Button className="ml-2 bg-indigo-600 hover:bg-indigo-700">
                          Save
                        </Button>
                      </div>
                    </div>
                  </div>
                </div>

                <Separator className="bg-slate-700" />

                <div className="space-y-4">
                  <h3 className="text-lg font-medium">Admin Account</h3>
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div className="space-y-2">
                      <label className="text-sm font-medium">
                        Change Password
                      </label>
                      <div className="space-y-2">
                        <Input
                          type="password"
                          placeholder="Current Password"
                          className="bg-slate-900/50 border-slate-700"
                        />
                        <Input
                          type="password"
                          placeholder="New Password"
                          className="bg-slate-900/50 border-slate-700"
                        />
                        <Input
                          type="password"
                          placeholder="Confirm New Password"
                          className="bg-slate-900/50 border-slate-700"
                        />
                        <Button className="w-full bg-indigo-600 hover:bg-indigo-700">
                          Update Password
                        </Button>
                      </div>
                    </div>
                    <div className="space-y-2">
                      <label className="text-sm font-medium">
                        Two-Factor Authentication
                      </label>
                      <div className="p-4 border border-slate-700 rounded-md bg-slate-900/50">
                        <p className="text-sm text-slate-400 mb-4">
                          Enhance your account security with two-factor
                          authentication
                        </p>
                        <Button className="w-full bg-indigo-600 hover:bg-indigo-700">
                          Enable 2FA
                        </Button>
                      </div>
                    </div>
                  </div>
                </div>
              </CardContent>
            </Card>
          </TabsContent>
        </Tabs>
      </div>

      {/* Add User Dialog */}
      <Dialog open={showAddUserDialog} onOpenChange={setShowAddUserDialog}>
        <DialogContent className="bg-slate-800/95 backdrop-blur-md text-white border-slate-700/50 shadow-2xl">
          <DialogHeader>
            <DialogTitle>Add New User</DialogTitle>
            <DialogDescription className="text-slate-400">
              Create a new user account
            </DialogDescription>
          </DialogHeader>

          <div className="space-y-4 py-4">
            <div className="space-y-2">
              <label className="text-sm font-medium">Full Name</label>
              <Input
                placeholder="John Doe"
                className="bg-slate-900 border-slate-700"
                value={newUser.fullName}
                onChange={(e) =>
                  setNewUser({ ...newUser, fullName: e.target.value })
                }
              />
            </div>

            <div className="space-y-2">
              <label className="text-sm font-medium">Username</label>
              <Input
                placeholder="johndoe"
                className="bg-slate-900 border-slate-700"
                value={newUser.username}
                onChange={(e) =>
                  setNewUser({ ...newUser, username: e.target.value })
                }
              />
            </div>

            <div className="space-y-2">
              <label className="text-sm font-medium">Email</label>
              <Input
                type="email"
                placeholder="john@example.com"
                className="bg-slate-900 border-slate-700"
                value={newUser.email}
                onChange={(e) =>
                  setNewUser({ ...newUser, email: e.target.value })
                }
              />
            </div>

            <div className="space-y-2">
              <label className="text-sm font-medium">Password</label>
              <Input
                type="password"
                placeholder="••••••••"
                className="bg-slate-900 border-slate-700"
                value={newUser.password}
                onChange={(e) =>
                  setNewUser({ ...newUser, password: e.target.value })
                }
              />
            </div>

            <Alert className="bg-blue-900/30 border-blue-900/50 shadow-inner">
              <AlertCircle className="h-4 w-4 text-blue-500" />
              <AlertTitle className="text-blue-500">Note</AlertTitle>
              <AlertDescription className="text-blue-200/70">
                New users will start with zero balance in all wallets.
              </AlertDescription>
            </Alert>
          </div>

          <DialogFooter>
            <Button
              variant="outline"
              onClick={() => setShowAddUserDialog(false)}
              className="border-slate-600 hover:bg-slate-700"
            >
              Cancel
            </Button>
            <Button
              onClick={handleAddUser}
              disabled={
                !newUser.username ||
                !newUser.email ||
                !newUser.fullName ||
                !newUser.password
              }
              className="bg-indigo-600 hover:bg-indigo-700"
            >
              Add User
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {/* Edit User Dialog */}
      <Dialog open={showEditUserDialog} onOpenChange={setShowEditUserDialog}>
        <DialogContent className="bg-slate-800/95 backdrop-blur-md text-white border-slate-700/50 shadow-2xl">
          <DialogHeader>
            <DialogTitle>Edit User</DialogTitle>
            <DialogDescription className="text-slate-400">
              Update user account details
            </DialogDescription>
          </DialogHeader>

          {selectedUser && (
            <div className="space-y-4 py-4">
              <div className="space-y-2">
                <label className="text-sm font-medium">Full Name</label>
                <Input
                  placeholder="John Doe"
                  className="bg-slate-900 border-slate-700"
                  value={selectedUser.fullName}
                  onChange={(e) =>
                    setSelectedUser({
                      ...selectedUser,
                      fullName: e.target.value,
                    })
                  }
                />
              </div>

              <div className="space-y-2">
                <label className="text-sm font-medium">Username</label>
                <Input
                  placeholder="johndoe"
                  className="bg-slate-900 border-slate-700"
                  value={selectedUser.username}
                  onChange={(e) =>
                    setSelectedUser({
                      ...selectedUser,
                      username: e.target.value,
                    })
                  }
                />
              </div>

              <div className="space-y-2">
                <label className="text-sm font-medium">Email</label>
                <Input
                  type="email"
                  placeholder="john@example.com"
                  className="bg-slate-900 border-slate-700"
                  value={selectedUser.email}
                  onChange={(e) =>
                    setSelectedUser({ ...selectedUser, email: e.target.value })
                  }
                />
              </div>

              <div className="space-y-2">
                <label className="text-sm font-medium">Status</label>
                <div className="flex space-x-4">
                  <Button
                    variant={
                      selectedUser.status === "active" ? "default" : "outline"
                    }
                    className={
                      selectedUser.status === "active"
                        ? "bg-green-600 hover:bg-green-700"
                        : "border-slate-600 hover:bg-slate-700"
                    }
                    onClick={() =>
                      setSelectedUser({ ...selectedUser, status: "active" })
                    }
                  >
                    Active
                  </Button>
                  <Button
                    variant={
                      selectedUser.status === "inactive" ? "default" : "outline"
                    }
                    className={
                      selectedUser.status === "inactive"
                        ? "bg-red-600 hover:bg-red-700"
                        : "border-slate-600 hover:bg-slate-700"
                    }
                    onClick={() =>
                      setSelectedUser({ ...selectedUser, status: "inactive" })
                    }
                  >
                    Inactive
                  </Button>
                </div>
              </div>
            </div>
          )}

          <DialogFooter>
            <Button
              variant="outline"
              onClick={() => setShowEditUserDialog(false)}
              className="border-slate-600 hover:bg-slate-700"
            >
              Cancel
            </Button>
            <Button
              onClick={handleEditUser}
              className="bg-indigo-600 hover:bg-indigo-700"
            >
              Save Changes
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {/* Delete User Dialog */}
      <Dialog
        open={showDeleteUserDialog}
        onOpenChange={setShowDeleteUserDialog}
      >
        <DialogContent className="bg-slate-800/95 backdrop-blur-md text-white border-slate-700/50 shadow-2xl">
          <DialogHeader>
            <DialogTitle>Delete User</DialogTitle>
            <DialogDescription className="text-slate-400">
              Are you sure you want to delete this user?
            </DialogDescription>
          </DialogHeader>

          {selectedUser && (
            <div className="py-4">
              <Alert className="bg-red-900/30 border-red-900/50 shadow-inner">
                <AlertCircle className="h-4 w-4 text-red-500" />
                <AlertTitle className="text-red-500">Warning</AlertTitle>
                <AlertDescription className="text-red-200/70">
                  This action cannot be undone. This will permanently delete the
                  user account and remove all associated data.
                </AlertDescription>
              </Alert>

              <div className="mt-4 p-4 border border-slate-700 rounded-md bg-slate-900/50">
                <p className="text-sm font-medium mb-1">
                  User: {selectedUser.fullName}
                </p>
                <p className="text-sm text-slate-400 mb-1">
                  Username: {selectedUser.username}
                </p>
                <p className="text-sm text-slate-400">
                  Email: {selectedUser.email}
                </p>
              </div>
            </div>
          )}

          <DialogFooter>
            <Button
              variant="outline"
              onClick={() => setShowDeleteUserDialog(false)}
              className="border-slate-600 hover:bg-slate-700"
            >
              Cancel
            </Button>
            <Button
              onClick={handleDeleteUser}
              className="bg-red-600 hover:bg-red-700"
            >
              Delete User
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {/* Manage Funds Dialog */}
      <Dialog
        open={showManageFundsDialog}
        onOpenChange={setShowManageFundsDialog}
      >
        <DialogContent className="bg-slate-800/95 backdrop-blur-md text-white border-slate-700/50 shadow-2xl">
          <DialogHeader>
            <DialogTitle>Manage User Funds</DialogTitle>
            <DialogDescription className="text-slate-400">
              {selectedUser && `Modify balance for ${selectedUser.username}`}
            </DialogDescription>
          </DialogHeader>

          {selectedUser && (
            <div className="space-y-4 py-4">
              <div className="p-4 border border-slate-700 rounded-md bg-slate-900/50">
                <div className="grid grid-cols-3 gap-4">
                  <div>
                    <p className="text-xs text-slate-400">BTC Balance</p>
                    <p className="font-medium">
                      {selectedUser.wallets.BTC.balance} BTC
                    </p>
                    <p className="text-xs text-slate-400">
                      ${selectedUser.wallets.BTC.value.toLocaleString()}
                    </p>
                  </div>
                  <div>
                    <p className="text-xs text-slate-400">ETH Balance</p>
                    <p className="font-medium">
                      {selectedUser.wallets.ETH.balance} ETH
                    </p>
                    <p className="text-xs text-slate-400">
                      ${selectedUser.wallets.ETH.value.toLocaleString()}
                    </p>
                  </div>
                  <div>
                    <p className="text-xs text-slate-400">USDT Balance</p>
                    <p className="font-medium">
                      {selectedUser.wallets.USDT.balance} USDT
                    </p>
                    <p className="text-xs text-slate-400">
                      ${selectedUser.wallets.USDT.value.toLocaleString()}
                    </p>
                  </div>
                </div>
              </div>

              <div className="space-y-2">
                <label className="text-sm font-medium">Action</label>
                <div className="flex space-x-4">
                  <Button
                    variant={fundAction === "add" ? "default" : "outline"}
                    className={
                      fundAction === "add"
                        ? "bg-green-600 hover:bg-green-700"
                        : "border-slate-600 hover:bg-slate-700"
                    }
                    onClick={() => setFundAction("add")}
                  >
                    Add Funds
                  </Button>
                  <Button
                    variant={fundAction === "remove" ? "default" : "outline"}
                    className={
                      fundAction === "remove"
                        ? "bg-red-600 hover:bg-red-700"
                        : "border-slate-600 hover:bg-slate-700"
                    }
                    onClick={() => setFundAction("remove")}
                  >
                    Remove Funds
                  </Button>
                </div>
              </div>

              <div className="space-y-2">
                <label className="text-sm font-medium">Cryptocurrency</label>
                <div className="flex space-x-4">
                  <Button
                    variant={fundCoin === "BTC" ? "default" : "outline"}
                    className={
                      fundCoin === "BTC"
                        ? "bg-orange-600 hover:bg-orange-700"
                        : "border-slate-600 hover:bg-slate-700"
                    }
                    onClick={() => setFundCoin("BTC")}
                  >
                    Bitcoin (BTC)
                  </Button>
                  <Button
                    variant={fundCoin === "ETH" ? "default" : "outline"}
                    className={
                      fundCoin === "ETH"
                        ? "bg-blue-600 hover:bg-blue-700"
                        : "border-slate-600 hover:bg-slate-700"
                    }
                    onClick={() => setFundCoin("ETH")}
                  >
                    Ethereum (ETH)
                  </Button>
                  <Button
                    variant={fundCoin === "USDT" ? "default" : "outline"}
                    className={
                      fundCoin === "USDT"
                        ? "bg-green-600 hover:bg-green-700"
                        : "border-slate-600 hover:bg-slate-700"
                    }
                    onClick={() => setFundCoin("USDT")}
                  >
                    Tether (USDT)
                  </Button>
                </div>
              </div>

              <div className="space-y-2">
                <label className="text-sm font-medium">Amount</label>
                <Input
                  type="number"
                  placeholder="0.00"
                  className="bg-slate-900 border-slate-700"
                  value={fundAmount}
                  onChange={(e) => setFundAmount(e.target.value)}
                />
              </div>

              <Alert
                className={`${fundAction === "add" ? "bg-green-900/30 border-green-900/50" : "bg-red-900/30 border-red-900/50"} shadow-inner`}
              >
                <AlertCircle
                  className={`h-4 w-4 ${fundAction === "add" ? "text-green-500" : "text-red-500"}`}
                />
                <AlertTitle
                  className={`${fundAction === "add" ? "text-green-500" : "text-red-500"}`}
                >
                  {fundAction === "add" ? "Adding Funds" : "Removing Funds"}
                </AlertTitle>
                <AlertDescription
                  className={`${fundAction === "add" ? "text-green-200/70" : "text-red-200/70"}`}
                >
                  {fundAction === "add"
                    ? `This will add the specified amount to the user's ${fundCoin} balance.`
                    : `This will remove the specified amount from the user's ${fundCoin} balance.`}
                </AlertDescription>
              </Alert>
            </div>
          )}

          <DialogFooter>
            <Button
              variant="outline"
              onClick={() => setShowManageFundsDialog(false)}
              className="border-slate-600 hover:bg-slate-700"
            >
              Cancel
            </Button>
            <Button
              onClick={handleManageFunds}
              disabled={!fundAmount || parseFloat(fundAmount) <= 0}
              className={`${fundAction === "add" ? "bg-green-600 hover:bg-green-700" : "bg-red-600 hover:bg-red-700"}`}
            >
              {fundAction === "add" ? "Add Funds" : "Remove Funds"}
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>
  );
};

export default AdminDashboard;
