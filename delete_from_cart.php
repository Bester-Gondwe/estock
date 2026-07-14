<?php
require_once __DIR__ . '/config/bootstrap.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$productId = (int) ($_POST['productID'] ?? 0);
if ($productId < 1) {
    echo json_encode(['success' => false, 'message' => 'Invalid product.', 'count' => count($_SESSION['cart'])]);
    exit;
}

foreach ($_SESSION['cart'] as $index => $item) {
    if ((int) $item['productID'] === $productId) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        echo json_encode(['success' => true, 'count' => count($_SESSION['cart'])]);
        exit;
    }
}

echo json_encode(['success' => true, 'count' => count($_SESSION['cart'])]);
