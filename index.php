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
</body>

</html>