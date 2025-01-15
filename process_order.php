<?php
session_start();
require_once "models/Order.php";
require_once "models/Product.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_SESSION['user_id']) {
        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            $total = 0;
            $order = new Order();
            $product = new Product();

            $orderId =  $order->createOrder($_SESSION['user_id']);
            if ($orderId > 0) {
                foreach ($_SESSION['cart'] as $index => $item) {
                    if ($item['quantity'] > 0) {
                        $cartProduct = $product->getProductById($item['productID']);
                        $total += $cartProduct['product_price'] * $item['quantity'];
                        $order->addProductToOrder($orderId, $item['productID'], $item['quantity']);
                    }
                }
                $_SESSION['cart'] = [];
                echo "Order placed";
            } else {
                echo "error occured";
            }
        } else {
            echo "Cart is empty";
        }
    } else {
        echo "Not logged in";
    }
}
