<?php
session_start();

// Example product data (same as cart.php)
$products = [
    1 => ["id" => 1, "img" => "images/img/card1.png", "brand" => "Brand A", "price" => 40],
    2 => ["id" => 2, "img" => "images/img/card2.png", "brand" => "Brand B", "price" => 50],
    3 => ["id" => 3, "img" => "images/img/card3.png", "brand" => "Brand C", "price" => 60],
];

// Check if the user is logged in, if not, redirect to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get cart items from session
$cartItems = $_SESSION['cart'];

// Calculate total price
$totalPrice = 0;
foreach ($cartItems as $item) {
    $totalPrice += $item['price'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="css/checkout.css">
</head>
<body>
    <h1>Checkout</h1>
    <?php if (empty($cartItems)): ?>
        <p>Your cart is empty. <a href="index.php">Continue Shopping</a></p>
    <?php else: ?>
        <h2>Cart Items</h2>
        <div class="container">
    <!-- Cart Items Section -->
    <div class="cart-items">
        <h2>Your Cart</h2>&nbsp;&nbsp;
        <ul>
            <li>Product: Brand A | Price: K40</li>
            <li>Product: Brand B | Price: K50</li>
        </ul>
        <h2>Total: K90</h2>
    
    </div>

    <!-- Checkout Form Section -->
    <form action="process_checkout.php" method="POST">
        <label for="address">Shipping Address:</label>
        <textarea name="address" id="address" required></textarea><br><br>

        <label for="payment_method">Payment Method:</label>
        <select name="payment_method" id="payment_method" required>
            <option value="credit_card">Credit Card</option>
            <option value="paypal">PayPal</option>
            <option value="tnm_mpamba">Tnm Mpamba</option>
            <option value="airtel_money">Airtel Money</option>
            <option value="national_bank">National Bank</option>
            <option value="standard_bank">Standard Bank</option>
            <option value="fdh_bank">FDH Bank</option>
        </select><br><br>

        <button type="submit">Complete Checkout</button>
    </form>
</div>

    <?php endif; ?>
</body>
</html>
