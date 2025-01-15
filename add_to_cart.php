<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (isset($_POST['productID'])) {
        $productId = (int) $_POST['productID'];

        // Check if the product is already in the cart
        $productExists = false;
        foreach ($_SESSION['cart'] as $item) {
            if ($item['productID'] === $productId) {
                $productExists = true;
                break;
            }
        }

        if (!$productExists) {
            // Add product to the cart
            $_SESSION['cart'][] = ['productID' => $productId, 'quantity' => 1];
            echo count($_SESSION['cart']);
        } else {
            echo count($_SESSION['cart']);
        }
    } else {
        echo "error";
    }
}
