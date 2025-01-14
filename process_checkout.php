<?php
session_start();

// Example product data (same as cart.php)
$products = [
    1 => ["id" => 1, "img" => "images/img/card1.png", "brand" => "Brand A", "price" => 40],
    2 => ["id" => 2, "img" => "images/img/card2.png", "brand" => "Brand B", "price" => 50],
    3 => ["id" => 3, "img" => "images/img/card3.png", "brand" => "Brand C", "price" => 60],
];

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = $_POST['address'];
    $paymentMethod = $_POST['payment_method'];

    // Assuming you process the payment here and save the order in a database
    // For this example, we'll just output the data and clear the cart
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Order Confirmation</title>
        <link rel='stylesheet' href='css/process_checkout.css'>
    </head>
    <body>
        <div class='order-summary'>
            <h1>Thank you for your order!</h1>
            <h3>Your order has been placed successfully.</h3>
            <p class='detail'><span>Shipping Address:</span> " . htmlspecialchars($address) . "</p>
            <p class='detail'><span>Payment Method:</span> " . htmlspecialchars($paymentMethod) . "</p>
            <a href='index.php' class='confirmation-button'>Continue Shopping</a>
        </div>
    </body>
    </html>";
    
    // Clear the cart
    $_SESSION['cart'] = [];
} else {
    // Redirect if the form is not submitted properly
    header("Location: checkout.php");
    exit();
}
?>
