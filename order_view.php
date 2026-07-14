<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/models/Order.php';

require_login();

$orderId = (int) ($_GET['id'] ?? 0);
$orderModel = new Order();
$order = $orderModel->getOrderById($orderId);

$isOwner = $order && (int) $order['user_id'] === (int) $_SESSION['user_id'];
$isMerchant = ($_SESSION['user_role'] ?? '') === 'Merchant'
    && $order
    && $orderModel->merchantOwnsOrder($_SESSION['user_id'], $orderId);

if (!$order || (!$isOwner && !$isMerchant)) {
    http_response_code(404);
    include '404.html';
    exit;
}

$items = $orderModel->getOrderDetails($orderId);
$date = new DateTime($order['order_date']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'header.php'; ?>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800">
<?php include 'navbar.php'; ?>

<main class="max-w-3xl mx-auto px-4 py-10">
    <div class="mb-6">
        <a href="<?= $isOwner ? 'my_orders.php' : 'merchant/index.php?p=orders' ?>" class="text-sm text-emerald-600 hover:underline">&larr; Back to orders</a>
        <h1 class="text-2xl font-bold mt-2">Order #<?= (int) $orderId ?></h1>
        <p class="text-slate-500 text-sm"><?= $date->format('F j, Y \a\t g:i A') ?></p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <h2 class="font-semibold mb-2">Status</h2>
            <p><?= htmlspecialchars($order['order_status']) ?></p>
            <p class="text-sm text-slate-500 mt-2">Payment: <?= htmlspecialchars($order['payment_method'] ?? 'N/A') ?></p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <h2 class="font-semibold mb-2">Shipping</h2>
            <p class="text-sm text-slate-600"><?= nl2br(htmlspecialchars($order['shipping_address'] ?? '—')) ?></p>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-100 text-xs uppercase text-slate-600">
                <tr>
                    <th class="text-left px-4 py-3">Product</th>
                    <th class="text-left px-4 py-3">Qty</th>
                    <th class="text-left px-4 py-3">Price</th>
                    <th class="text-left px-4 py-3">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="px-4 py-3"><?= htmlspecialchars($item['product_name']) ?></td>
                        <td class="px-4 py-3"><?= (int) $item['quantity'] ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars(format_money($item['price'])) ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars(format_money($item['totalPrice'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="bg-slate-50 font-semibold">
                    <td colspan="3" class="px-4 py-3 text-right">Order total</td>
                    <td class="px-4 py-3"><?= htmlspecialchars(format_money($order['amount'])) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</main>
</body>
</html>
