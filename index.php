<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'header.php'; ?>
</head>

<body class="bg-gray-100">

  <?php include "navbar.php"; ?>

  <div class="bg-gold-500 text-white h-screen flex items-center justify-center">
    <div class="text-center p-8 max-w-lg mx-auto">
      <h1 class="text-4xl font-bold mb-4">WelcomE to the world of e-digital marketing</h1>
      <p class="text-lg mb-6">Discover amazing products and services designed for you. Feel free to explore!!!</p>
      <a href="category.php" class="bg-yellow-500 text-black px-6 py-3 text-xl rounded-lg hover:bg-yellow-400 transition duration-300">Shop Now</a>
    </div>
  </div>

  <script src="js/main.js"></script>

</body>

</html>
