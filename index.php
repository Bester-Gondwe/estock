<?php
session_start();
require_once "models/Category.php";
$category = new Category();
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'header.php' ?>

<body>
    <?php include "navbar.php" ?>
 
  <script src="js/main.js"></script>
    <div class="container1">
        <h1>Welcome to Our Store</h1>
        <p>Discover amazing products and services designed for you.</p>
        <div class="cta-button">
          <a href="#" class="cta-button">Shop Now</a>
        </div>
        
    </div>

   
  
</body>

</html>