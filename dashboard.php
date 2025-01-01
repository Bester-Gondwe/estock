
<?php
session_start(); // Start the session if not started already

// Example dynamic data
$totalProducts = 100; // This should be fetched from the database
$pendingOrders = 25;  // Fetch from your database
$completedOrders = 75; // Fetch from your database
$totalRevenue = "MWK 500,000"; // Fetch from your database or calculations

// Example dynamic transaction data
$transactions = [
  ['date' => '2025-01-01', 'amount' => 'MWK 10,000', 'status' => 'Completed'],
  ['date' => '2025-01-02', 'amount' => 'MWK 15,000', 'status' => 'Pending'],
  ['date' => '2025-01-03', 'amount' => 'MWK 20,000', 'status' => 'Completed'],
];

// Example dynamic recent activities data
$activities = [
  ['icon' => 'fas fa-box', 'text' => 'Order #1234 placed by John Doe', 'time' => '2 minutes ago'],
  ['icon' => 'fas fa-cogs', 'text' => 'Stock updated for Widget A', 'time' => '10 minutes ago'],
  ['icon' => 'fas fa-check-circle', 'text' => 'Order #1229 marked as completed', 'time' => '1 hour ago'],
];
?>

<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    if (isset($_COOKIE['email']) && isset($_COOKIE['password'])) {
    }
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <div class="container d-gridx2">
        <div class="side-bar">
            <div class="side-bar__header">
                <a class="side-bar__header-brand" href="">
                    <p class="side-bar__brand-text">eStock</p>
                    <img class="side-bar__brand-img" src="images/shop-bag-with-handle-svgrepo-com.svg"
                        alt="eStock Logo">
                </a>
            </div>
            <div class="side-bar__nav">
                <a href="" class="side-bar__nav-btn">
                    <img class="side-bar__nav-btn-img" src="images/dashboard-svgrepo-com.svg" alt="Dashboard Icon">
                    <p class="side-bar__nav-btn-text">Dashboard</p>
                </a>
                <a href="" class="side-bar__nav-btn">
                    <img class="side-bar__nav-btn-img" src="images/album-collection-svgrepo-com.svg" alt="Products Icon">
                    <p class="side-bar__nav-btn-text">Products</p>
                </a>
                <a href="" class="side-bar__nav-btn">
                    <img class="side-bar__nav-btn-img" src="images/online-delivery-svgrepo-com.svg" alt="Orders Icon">
                    <p class="side-bar__nav-btn-text">Orders</p>
                </a>
            </div>
        </div>
        <div class="center-content">
            <div class="dashboard-header">
                <img src="images/ic_search.svg" alt="Search Icon" width="24" height="24">
                <div class="profile-btn">
                    <p class="profile-btn__text"><?php echo htmlspecialchars($_SESSION['first_name']); ?></p>
                    <img src="images/ic_down-arrow.svg" alt="Search Icon" width="10" height="6">
                </div>
                <a href=""> <?php echo "<a href='logout.php'>Logout</a>"; ?></a>
            </div>
            <div class="main">

                <!-- Welcome User -->
                <h1>Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</h1>

                <!-- Merchant Dashboard Overview -->
                <div class="dashboard-overview">
                    <div class="overview-card">
                        <h3>Total Products</h3>
                        <p><?php echo $totalProducts; ?></p>
                    </div>
                    <div class="overview-card">
                        <h3>Pending Orders</h3>
                        <p><?php echo $pendingOrders; ?></p>
                    </div>
                    <div class="overview-card">
                        <h3>Completed Orders</h3>
                        <p><?php echo $completedOrders; ?></p>
                    </div>
                    <div class="overview-card">
                        <h3>Total Revenue</h3>
                        <p><?php echo $totalRevenue; ?></p>
                    </div>
                    <div class="overview-card">
                        <h3>Total Customers</h3>
                        <p><?php echo $totalProducts; ?></p>
                    </div>

                </div>
                <!-- Recent Activities Section -->
                <h2>Recent Activities</h2>
                <div class="activity-log">
                    <?php foreach ($activities as $activity): ?>
                        <div class="activity-card">
                            <div class="activity-icon">
                                <i class="<?php echo $activity['icon']; ?>"></i>
                            </div>
                            <div class="activity-details">
                                <p><strong><?php echo $activity['text']; ?></strong></p>
                                <small><?php echo $activity['time']; ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Transaction History Section -->
                <h2>Transaction History</h2>
                <div class="transaction-history">
                    <?php foreach ($transactions as $transaction): ?>
                        <div class="transaction-card">
                            <p><strong>Date:</strong> <?php echo $transaction['date']; ?></p>
                            <p><strong>Amount:</strong> <?php echo $transaction['amount']; ?></p>
                            <p><strong>Status:</strong> <?php echo $transaction['status']; ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>



                <!-- Upcoming Features Section -->
                <h2>Upcoming Features</h2>

                <div class="upcoming-features">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i> <!-- User Icon -->
                        </div>
                        <div class="feature-details">
                            <h3>Customer Management</h3>
                            <p>Manage your customers, track their orders, and improve engagement.</p>
                            <div class="progress-bar">
                                <div class="progress" style="width: 40%"></div> <!-- 40% Progress -->
                            </div>
                            <small>40% Complete</small>
                        </div>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i> <!-- Sales Report Icon -->
                        </div>
                        <div class="feature-details">
                            <h3>Sales Reports</h3>
                            <p>Analyze your sales performance with advanced reports and analytics.</p>
                            <div class="progress-bar">
                                <div class="progress" style="width: 60%"></div> <!-- 60% Progress -->
                            </div>
                            <small>60% Complete</small>
                        </div>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-cogs"></i> <!-- Settings Icon -->
                        </div>
                        <div class="feature-details">
                            <h3>Advanced Settings</h3>
                            <p>Customize your dashboard settings to better suit your needs.</p>
                            <div class="progress-bar">
                                <div class="progress" style="width: 20%"></div> <!-- 20% Progress -->
                            </div>
                            <small>20% Complete</small>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <script>
            // Get all nav items
            const navItems = document.querySelectorAll('.side-bar__nav-btn');

            // Add a click event listener to each nav item
            navItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    // Prevent the default behavior (e.g., navigation)
                    e.preventDefault();

                    // Remove the 'active' class from all nav items
                    navItems.forEach(item => item.classList.remove('active'));

                    // Add the 'active' class to the clicked nav item
                    this.classList.add('active');
                });
            });
        </script>
</body>

</html>