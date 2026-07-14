<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/models/Product.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$productID = (int) ($_POST['productID'] ?? 0);
$quantity = (int) ($_POST['quantity'] ?? 0);

if ($productID < 1 || $quantity < 1) {
    echo json_encode(['success' => false, 'message' => 'Invalid quantity.']);
    exit;
}

$product = new Product();
$item = $product->getProductById($productID);
if (!$item) {
    echo json_encode(['success' => false, 'message' => 'Product not found.']);
    exit;
}

if ($quantity > (int) $item['quantity']) {
    echo json_encode([
        'success' => false,
        'message' => 'Only ' . (int) $item['quantity'] . ' units available in stock.',
    ]);
    exit;
}

foreach ($_SESSION['cart'] as $key => $cartItem) {
    if ((int) $cartItem['productID'] === $productID) {
        $_SESSION['cart'][$key]['quantity'] = $quantity;
        echo json_encode(['success' => true, 'message' => 'Quantity updated.']);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Item not in cart.']);
