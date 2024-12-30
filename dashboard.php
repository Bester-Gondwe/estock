<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    if(isset($_COOKIE['email']) && isset($_COOKIE['password'])){
        
    }
    header("Location: login.php");
    exit;
}

echo "<h1>Welcome, " . htmlspecialchars($_SESSION['first_name']) . "!</h1>";
echo "<p>Your email: " . htmlspecialchars($_SESSION['email']) . "</p>";
echo "<a href='logout.php'>Logout</a>";
?>
