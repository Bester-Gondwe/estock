<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/models/Order.php';
require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/models/User.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Please log in to place an order.', 'redirect' => 'login.php']);
    exit;
}

if (($_SESSION['user_role'] ?? '') === 'Merchant') {
    echo json_encode(['success' => false, 'message' => 'Merchant accounts cannot place customer orders.']);
    exit;
}

if (empty($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    echo json_encode(['success' => false, 'message' => 'Your cart is empty.']);
    exit;
}

$shippingAddress = trim($_POST['shipping_address'] ?? '');
$paymentMethod = trim($_POST['payment_method'] ?? 'Cash on Delivery');
$notes = trim($_POST['notes'] ?? '');

$userModel = new User();
$currentUser = $userModel->getUserById($_SESSION['user_id']);

if ($shippingAddress === '') {
    $shippingAddress = $currentUser['address'] ?? '';
}

if ($shippingAddress === '') {
    echo json_encode(['success' => false, 'message' => 'Please provide a shipping address.']);
    exit;
}

$allowedPayments = ['Cash on Delivery', 'Bank Transfer', 'Mobile Money'];
if (!in_array($paymentMethod, $allowedPayments, true)) {
    $paymentMethod = 'Cash on Delivery';
}

$order = new Order();
$product = new Product();

try {
    $order->beginTransaction();

    $total = 0;
    $lineItems = [];

    foreach ($_SESSION['cart'] as $item) {
        $qty = (int) ($item['quantity'] ?? 0);
        $productId = (int) ($item['productID'] ?? 0);
        if ($qty < 1 || $productId < 1) {
            continue;
        }

        $cartProduct = $product->getProductById($productId);
        if (!$cartProduct) {
            throw new RuntimeException('One or more products are no longer available.');
        }

        if ((int) $cartProduct['quantity'] < $qty) {
            throw new RuntimeException(
                "Insufficient stock for \"{$cartProduct['product_name']}\". Available: {$cartProduct['quantity']}."
            );
        }

        $lineTotal = (float) $cartProduct['product_price'] * $qty;
        $total += $lineTotal;
        $lineItems[] = [
            'product_id' => $productId,
            'quantity' => $qty,
            'unit_price' => (float) $cartProduct['product_price'],
            'name' => $cartProduct['product_name'],
        ];
    }

    if (count($lineItems) === 0) {
        throw new RuntimeException('Your cart has no valid items.');
    }

    $orderId = $order->createOrder($_SESSION['user_id'], $shippingAddress, $paymentMethod, $notes ?: null);
    if (!$orderId) {
        throw new RuntimeException('Could not create order.');
    }

    foreach ($lineItems as $line) {
        if (!$product->deductStock($line['product_id'], $line['quantity'])) {
            throw new RuntimeException("Stock changed for \"{$line['name']}\". Please review your cart.");
        }
        $product->recordInventoryMovement($line['product_id'], -$line['quantity'], 'order', $orderId);
        $order->addProductToOrder($orderId, $line['product_id'], $line['quantity'], $line['unit_price']);
    }

    $order->updateOrderAmount($orderId, $total);
    $order->commit();

    $_SESSION['cart'] = [];

    echo json_encode([
        'success' => true,
        'message' => 'Order placed successfully.',
        'order_id' => (int) $orderId,
        'total' => $total,
        'redirect' => 'order_confirmation.php?id=' . $orderId,
    ]);
} catch (Throwable $e) {
    $order->rollBack();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}
