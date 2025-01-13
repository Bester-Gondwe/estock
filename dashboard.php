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
  <link rel="stylesheet" href="css/orders.css" />
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
        <a href="products.php" class="side-bar__nav-btn">
          <img class="side-bar__nav-btn-img" src="images/album-collection-svgrepo-com.svg" alt="Products Icon">
          <p class="side-bar__nav-btn-text">Products</p>
        </a>
        <a href="orders.php" class="side-bar__nav-btn">
          <img class="side-bar__nav-btn-img" src="images/online-delivery-svgrepo-com.svg" alt="Orders Icon">
          <p class="side-bar__nav-btn-text">Orders</p>

        </a>


        <a href="category.php" class="side-bar__nav-btn">
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

    

      <div class="main-content">

        <!-- Welcome User -->
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?></h1>

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

        <!-- Recent Orders Section -->
<h2>Recent Orders</h2>
<div class="order-log">
  <?php
  // Fetch recent orders from the database
  require_once 'models/Order.php';

  $order = new Order();
  $recentOrders = $order->getRecentOrders(); // Fetch recent orders using a method

  if (!empty($recentOrders)): ?>
    <?php foreach ($recentOrders as $order): ?>
      <div class="order-card">
        <div class="order-details">
          <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['order_id']); ?></p>
          <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
          <p><strong>Total Amount:</strong> $<?php echo htmlspecialchars(number_format($order['total_amount'], 2)); ?></p>
          <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
          
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p>No recent orders available.</p>
  <?php endif; ?>
</div>

<section class="orders">
    <div class="orders-table__wrapper">
        <div>
            <h4 class="table-title">Recent Purchases</h4>
        </div>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Customer Name</th>
                    <th>Status</th>
                    <th>Amount</th>
                </tr>
            <tbody>
                <tr class="order-table__row" id="row-1" data-id="1">
                    <td>Lorem Ipsum</td>
                    <td>#256789</td>
                    <td>Nov 6th,2025</td>
                    <td>Admin</td>
                    <td><span class="dot derivered"></span>
                        Cancled</td>
                    <td>MWK200000</td>
                </tr>
                <tr class="order-table__row">
                    <td>Lorem Ipsum</td>
                    <td>#256789</td>
                    <td>Nov 6th,2025</td>
                    <td>Admin</td>
                    <td><span class="dot cancled"></span>
                        Cancled</td>
                    <td>MWK200000</td>
                </tr>
                <tr class="order-table__row">
                    <td>Lorem Ipsum</td>
                    <td>#256789</td>
                    <td>Nov 6th,2025</td>
                    <td>Admin</td>
                    <td>Cancled</td>
                    <td>MWK200000</td>
                </tr>
                <tr class="order-table__row">
                    <td>Lorem Ipsum</td>
                    <td>#256789</td>
                    <td>Nov 6th,2025</td>
                    <td>Admin</td>
                    <td>Cancled</td>
                    <td>MWK200000</td>
                </tr>
                <tr class="order-table__row">
                    <td>Lorem Ipsum</td>
                    <td>#256789</td>
                    <td>Nov 6th,2025</td>
                    <td>Admin</td>
                    <td>Cancled</td>
                    <td>MWK200000</td>
                </tr>
                <tr class="order-table__row">
                    <td>Lorem Ipsum</td>
                    <td>#256789</td>
                    <td>Nov 6th,2025</td>
                    <td>Admin</td>
                    <td>Cancled</td>
                    <td>MWK200000</td>
                </tr>
                <tr class="order-table__row">
                    <td>Lorem Ipsum</td>
                    <td>#256789</td>
                    <td>Nov 6th,2025</td>
                    <td>Admin</td>
                    <td>Cancled</td>
                    <td>MWK200000</td>
                </tr>
                <tr class="order-table__row">
                    <td>Lorem Ipsum</td>
                    <td>#256789</td>
                    <td>Nov 6th,2025</td>
                    <td>Admin</td>
                    <td>Cancled</td>
                    <td>MWK200000</td>
                </tr>
            </tbody>
            </thead>
        </table>
    </div>
</section>


        <!-- <script>
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
    </script> -->
</body>

</html>