<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/models/Product.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$productId = (int) ($_POST['productID'] ?? 0);
if ($productId < 1) {
    echo json_encode(['success' => false, 'message' => 'Invalid product.']);
    exit;
}

$product = new Product();
$item = $product->getProductById($productId);

if (!$item) {
    echo json_encode(['success' => false, 'message' => 'Product not found.']);
    exit;
}

if ((int) $item['quantity'] < 1) {
    echo json_encode(['success' => false, 'message' => 'This product is out of stock.']);
    exit;
}

foreach ($_SESSION['cart'] as &$cartItem) {
    if ((int) $cartItem['productID'] === $productId) {
        $newQty = (int) $cartItem['quantity'] + 1;
        if ($newQty > (int) $item['quantity']) {
            echo json_encode([
                'success' => false,
                'message' => 'Not enough stock available.',
                'count' => count($_SESSION['cart']),
            ]);
            exit;
        }
        $cartItem['quantity'] = $newQty;
        echo json_encode([
            'success' => true,
            'message' => 'Cart updated.',
            'count' => count($_SESSION['cart']),
        ]);
        exit;
    }
}
unset($cartItem);

$_SESSION['cart'][] = ['productID' => $productId, 'quantity' => 1];
echo json_encode([
    'success' => true,
    'message' => 'Item added to cart.',
    'count' => count($_SESSION['cart']),
]);
