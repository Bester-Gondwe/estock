<?php
require_once __DIR__ . '/../config/bootstrap.php';
require_merchant();
require_once __DIR__ . '/../models/Order.php';

header('Content-Type: text/plain');

$orderID = (int) ($_GET['orderID'] ?? 0);
$orderStatus = trim($_POST['orderStatus'] ?? '');
$allowed = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];

if ($orderID < 1 || !in_array($orderStatus, $allowed, true)) {
    echo 'Invalid order or status.';
    exit;
}

$order = new Order();
if (!$order->merchantOwnsOrder($_SESSION['user_id'], $orderID)) {
    echo 'You do not have permission to update this order.';
    exit;
}

if ($order->updateOrderStatus($orderID, $orderStatus)) {
    echo "Order #{$orderID} updated successfully";
} else {
    echo "Failed to update order #{$orderID}";
}
