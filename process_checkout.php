<?php
session_start();
require_once 'models/Database.php'; // Assuming you have a Database model to handle DB queries

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database query to get cart items
$userId = $_SESSION['user_id'];
// Example: Get cart items from the database
$sql = "SELECT p.id, p.img, p.brand, p.price, c.quantity FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$userId]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = $_POST['address'];
    $paymentMethod = $_POST['payment_method'];

    // Process the payment here and save the order in the database
    // Example: Save the order in the database (simplified)
    $orderQuery = "INSERT INTO orders (user_id, address, payment_method) VALUES (?, ?, ?)";
    $stmt = $db->prepare($orderQuery);
    $stmt->execute([$userId, $address, $paymentMethod]);

    // Get the last inserted order ID
    $orderId = $db->lastInsertId();

    // Insert order items
    foreach ($products as $product) {
        $orderItemQuery = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($orderItemQuery);
        $stmt->execute([$orderId, $product['id'], $product['quantity'], $product['price']]);
    }

    // Clear the cart after processing the order
    $clearCartQuery = "DELETE FROM cart WHERE user_id = ?";
    $stmt = $db->prepare($clearCartQuery);
    $stmt->execute([$userId]);

    // Order confirmation HTML output
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Order Confirmation</title>
        <link rel='stylesheet' href='css/style.css'>
        <script src='https://cdn.tailwindcss.com'></script>
    </head>
    <body class='bg-gray-100'>
        <div class='max-w-4xl mx-auto p-6 bg-white shadow-md rounded-md mt-12'>
            <h1 class='text-2xl font-semibold text-center text-green-600'>Thank you for your order!</h1>
            <h3 class='text-center text-lg mt-2'>Your order has been placed successfully.</h3>

            <div class='mt-6'>
                <h4 class='text-lg font-semibold'>Order Summary</h4>
                <div class='mt-4'>
                    <p class='text-sm text-gray-600'><span class='font-semibold'>Shipping Address:</span> " . htmlspecialchars($address) . "</p>
                    <p class='text-sm text-gray-600'><span class='font-semibold'>Payment Method:</span> " . htmlspecialchars($paymentMethod) . "</p>
                </div>
            </div>

            <div class='mt-6'>
                <h4 class='text-lg font-semibold'>Your Products</h4>
                <div class='mt-4'>
                    <ul class='space-y-4'>
                        ";
                        foreach ($products as $product) {
                            echo "<li class='flex items-center justify-between'>
                                    <div class='flex items-center'>
                                        <img src='" . htmlspecialchars($product['img']) . "' alt='" . htmlspecialchars($product['brand']) . "' class='w-16 h-16 object-cover rounded-md'>
                                        <div class='ml-4'>
                                            <p class='font-semibold'>" . htmlspecialchars($product['brand']) . "</p>
                                            <p class='text-sm text-gray-500'>Quantity: " . htmlspecialchars($product['quantity']) . "</p>
                                            <p class='text-sm text-gray-500'>Price: $" . htmlspecialchars($product['price']) . "</p>
                                        </div>
                                    </div>
                                </li>";
                        }
                        echo "
                    </ul>
                </div>
            </div>

            <div class='mt-6 text-center'>
                <a href='index.php' class='inline-block px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700'>Continue Shopping</a>
            </div>
        </div>
    </body>
    </html>";
}
?>
