<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    if(isset($_COOKIE['email']) && isset($_COOKIE['password'])){
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
                    <img class="side-bar__nav-btn-img"  src="images/dashboard-svgrepo-com.svg" alt="Dashboard Icon">
                    <p class="side-bar__nav-btn-text">Dashboard</p>
                </a>
                <a href="" class="side-bar__nav-btn">
                    <img class="side-bar__nav-btn-img" src="images/album-collection-svgrepo-com.svg" alt="Products Icon">
                    <p class="side-bar__nav-btn-text">Products</p>
                </a>
                <a href=""  class="side-bar__nav-btn">
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

            </div>
        </div>
    </div>
    <script>
        // Get all nav items
const navItems = document.querySelectorAll('.side-bar__nav-btn');

// Add a click event listener to each nav item
navItems.forEach(item => {
  item.addEventListener('click', function (e) {
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
