<?php
session_start();
require_once '../models/Order.php';

if (isset($_SESSION['user_id'])) {

    $orderID = $_GET['orderID'];
    $orderStatus = $_POST['orderStatus'];

    $order = new Order();
    if ($order->updateOrderStatus($orderID, $orderStatus)) {
        echo "Order #$orderID Updated Sucessfully";
    } else {
        echo "Failed to update the order #$orderID";
    }
}
