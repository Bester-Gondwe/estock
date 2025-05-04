<?php
session_start();

$productID = (int) $_POST['productID'];
$quantity = (int) $_POST['quantity'];

foreach ($_SESSION['cart'] as $key => $item) {
    if ($item['productID'] === $productID) {
        $_SESSION['cart'][$key]['quantity'] = $quantity;
        break;
    }
}
