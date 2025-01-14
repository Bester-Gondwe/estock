<?php
session_start();

// Initialize cart session if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Example product data
$products = [
    1 => ["id" => 1, "img" => "images/img/card1.png", "brand" => "Brand A", "price" => 40],
    2 => ["id" => 2, "img" => "images/img/card2.png", "brand" => "Brand B", "price" => 50],
    3 => ["id" => 3, "img" => "images/img/card3.png", "brand" => "Brand C", "price" => 60],
];

// Handle adding product to cart (AJAX request)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['price'])) {
    $productId = $_POST['product_id'];

    if (array_key_exists($productId, $products)) {
        $_SESSION['cart'][] = $products[$productId];
        echo count($_SESSION['cart']);
    } else {
        echo "error";
    }
    exit();
}

// Handle cart display (GET request)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Display cart items
    $cartItems = $_SESSION['cart'];
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cart</title>
        <link rel="stylesheet" href="css/cart.css">
    </head>
    <body>
        <h1>Your Cart</h1>
        <?php if (empty($cartItems)): ?>
            <p>Your cart is empty. <a href="index.php">Continue Shopping</a></p>
        <?php else: ?>
            <ul>
                <?php foreach ($cartItems as $item): ?>
                    <li>
                        Product: <?= htmlspecialchars($item['brand'] ?? 'Unknown') ?> | 
                        Price: K<?= htmlspecialchars($item['price'] ?? '0') ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <button onclick="window.location.href='checkout.php'">Proceed to Checkout</button>

        
        <?php endif; ?>

        <script>
            function checkout() {
                alert("Proceeding to checkout!");
                // Redirect to a checkout page (not implemented here)

            }

            function addToCart(productId, price) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "cart.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        if (xhr.responseText === "error") {
                            alert("Failed to add product to cart.");
                        } else {
                            alert("Product added to cart!");
                        }
                    }
                };
                xhr.send(`product_id=${productId}&price=${price}`);
            }
        </script>
    </body>
    </html>
    <?php
}
?>
