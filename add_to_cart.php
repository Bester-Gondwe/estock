<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (isset($_POST['productID'])) {
        $productId = (int) $_POST['productID'];
        // Add product to the cart
        $_SESSION['cart'][] = ['product_id' => $productId];
        echo count($_SESSION['cart']);
    } else {
        echo "error";
    }
}
