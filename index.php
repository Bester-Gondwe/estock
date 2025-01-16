<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'header.php' ?>
</head>

<body>
  <?php include "navbar.php" ?>
  <div class="landing-page-container">
    <div class="landing-page">
      <h1>Welcome to Our Store</h1>
      <p>Discover amazing products and services designed for you.</p>
      <a href="category.php?category=Phones" class="cta-button">Shop Now</a>
    </div>

  </div>
  <script src="js/main.js"></script>
</body>

</html>