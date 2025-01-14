<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_POST['product_id']) && isset($_POST['price'])) {
        $productId = (int) $_POST['product_id'];
        $price = (float) $_POST['price'];

        // Add product to the cart
        $_SESSION['cart'][] = ['product_id' => $productId, 'price' => $price];

        // Return the updated cart count
        echo count($_SESSION['cart']);
    } else {
        echo "error";
    }
} else {
    echo "Invalid request";
}
