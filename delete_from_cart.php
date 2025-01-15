<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['productID'])) {
        $productId = (int) $_POST['productID'];
        
        // Find the product in the cart and remove it
        foreach ($_SESSION['cart'] as $index => $item) {
            if ($item['product_id'] === $productId) {
                unset($_SESSION['cart'][$index]); // Remove the item
                $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex array
                echo count($_SESSION['cart']);
                exit;
            }
        }
        echo count($_SESSION['cart']);
    } else {
        echo "error";
    }
}
