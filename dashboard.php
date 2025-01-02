<?php
session_start(); // Start the session if not started already
require_once 'connection.php';

// Create an instance of the Database class
$sql = new Database();

try {
  // Fetch data from the database with error handling
  $totalProductsQuery = "SELECT COUNT(*) AS total FROM products";
  $totalProducts = $sql->query($totalProductsQuery)->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

  $pendingOrdersQuery = "SELECT COUNT(*) AS total FROM orders WHERE status = 'Pending'";
  $pendingOrders = $sql->query($pendingOrdersQuery)->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

  $completedOrdersQuery = "SELECT COUNT(*) AS total FROM orders WHERE status = 'Completed'";
  $completedOrders = $sql->query($completedOrdersQuery)->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

  $totalRevenueQuery = "SELECT SUM(amount) AS total FROM transactions";
  $totalRevenue = $sql->query($totalRevenueQuery)->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;


  // Fetch Recent Activities from Database
  $recentActivitiesQuery = "SELECT icon, text, time FROM recent_activities ORDER BY time DESC LIMIT 10";
  $activities = $sql->query($recentActivitiesQuery)->fetchAll(PDO::FETCH_ASSOC);

  // Fetch Transaction History from Database
  $transactionHistoryQuery = "SELECT date, amount, status FROM transactions ORDER BY date DESC LIMIT 10";
  $transactions = $sql->query($transactionHistoryQuery)->fetchAll(PDO::FETCH_ASSOC);


  // Fetch Upcoming Features from Database
  $upcomingFeaturesQuery = "SELECT icon, title, description, progress FROM upcoming_features ORDER BY progress DESC";
  $upcomingFeatures = $sql->query($upcomingFeaturesQuery)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Database query failed: " . $e->getMessage());
}

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
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

                    <a href="" class="side-bar__nav-btn">
                    <img class="side-bar__nav-btn-img" src="images/online-delivery-svgrepo-com.svg" alt="Orders Icon">
                    <p class="side-bar__nav-btn-text">Categories</p>
                </a>
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
          <?php if (!empty($activities)): ?>
            <?php foreach ($activities as $activity): ?>
              <div class="activity-card">
                <div class="activity-icon">
                  <i class="<?php echo htmlspecialchars($activity['icon']); ?>"></i>
                </div>
                <div class="activity-details">
                  <p><strong><?php echo htmlspecialchars($activity['text']); ?></strong></p>
                  <small><?php echo htmlspecialchars($activity['time']); ?></small>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p>No recent activities available.</p>
          <?php endif; ?>
        </div>


        <!-- Transaction History Section -->
        <h2>Transaction History</h2>
        <div class="transaction-history">
          <?php if (!empty($transactions)): ?>
            <?php foreach ($transactions as $transaction): ?>
              <div class="transaction-card">
                <p><strong>Date:</strong> <?php echo htmlspecialchars($transaction['date']); ?></p>
                <p><strong>Amount:</strong> MWK <?php echo htmlspecialchars($transaction['amount']); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($transaction['status']); ?></p>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p>No transactions found.</p>
          <?php endif; ?>
        </div>

     <!-- Upcoming Features Section -->
        <h2>Upcoming Features</h2>
        <div class="upcoming-features">
          <?php if (!empty($upcomingFeatures)): ?>
            <?php foreach ($upcomingFeatures as $feature): ?>
              <div class="feature-card">
                <div class="feature-icon">
                  <i class="<?php echo htmlspecialchars($feature['icon']); ?>"></i> <!-- Feature Icon -->
                </div>
                <div class="feature-details">
                  <h3><?php echo htmlspecialchars($feature['title']); ?></h3>
                  <p><?php echo htmlspecialchars($feature['description']); ?></p>
                  <div class="progress-bar">
                    <div class="progress" style="width: <?php echo htmlspecialchars($feature['progress']); ?>%"></div>
                  </div>
                  <small><?php echo htmlspecialchars($feature['progress']); ?>% Complete</small>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p>No upcoming features available at the moment.</p>
          <?php endif; ?>
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