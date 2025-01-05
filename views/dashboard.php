<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard with Graph</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../script/script.js">
    <link rel="stylesheet" href="../style/dashboard.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>FinDash</h2>
        <ul>
            <li class="active">Dashboard</li>
            <li>Transactions</li>
            <li>Analytics</li>
            <li>Customers</li>
            <li>Settings</li>
        </ul>
    </div>

    <!-- Main Dashboard Content -->
    <div class="main-content">
        <h1>Dashboard</h1>

        <!-- Card Section -->
        <div class="cards">
            <div class="card">
                <p>Total Balance</p>
                <h2>$84,254.58</h2>
                <p class="positive">+1.5% vs last month</p>
            </div>
            <div class="card">
                <p>Total Revenue</p>
                <h2>$24,836.00</h2>
                <p class="positive">+8.2% vs last month</p>
            </div>
            <div class="card">
                <p>Total Expenses</p>
                <h2>$14,125.00</h2>
                <p class="negative">-2.3% vs last month</p>
            </div>
        </div>

        <!-- Graph Section -->
        <div class="graph-container">
            <canvas id="myChart"></canvas>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
