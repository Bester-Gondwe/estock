<?php
session_start();

$productID = $_POST['productID'];
$quantity = $_POST['quantity'];

foreach ($_SESSION['cart'] as $key => $item) {
    if ($item['productID'] == $productID) {
        $_SESSION['cart'][$key]['quantity'] = $quantity;
    }
}
