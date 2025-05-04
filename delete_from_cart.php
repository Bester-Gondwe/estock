<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['productID'])) {
        $productId = (int) $_POST['productID'];

        foreach ($_SESSION['cart'] as $index => $item) {
            if ($item['productID'] === $productId) {
                unset($_SESSION['cart'][$index]);
                $_SESSION['cart'] = array_values($_SESSION['cart']);
                echo count($_SESSION['cart']);
                exit;
            }
        }
        echo count($_SESSION['cart']);
    } else {
        echo "error";
    }
}
