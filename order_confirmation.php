<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/models/Order.php';

require_login();

$orderId = (int) ($_GET['id'] ?? 0);
$orderModel = new Order();
$order = $orderModel->getOrderById($orderId);

if (!$order || (int) $order['user_id'] !== (int) $_SESSION['user_id']) {
    header('Location: my_orders.php');
    exit;
}

$items = $orderModel->getOrderDetails($orderId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'header.php'; ?>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50">
<?php include 'navbar.php'; ?>

<main class="max-w-lg mx-auto px-4 py-16">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 text-center">
        <div class="mx-auto w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center mb-4">
            <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-800 mb-2">Order confirmed</h1>
        <p class="text-slate-600 mb-6">Thank you! Your order <span class="font-semibold">#<?= (int) $orderId ?></span> has been placed.</p>
        <p class="text-lg font-semibold text-emerald-700 mb-8"><?= htmlspecialchars(format_money($order['amount'])) ?></p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="order_view.php?id=<?= (int) $orderId ?>" class="px-5 py-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">View order</a>
            <a href="category.php" class="px-5 py-2.5 border border-slate-300 rounded-lg hover:bg-slate-50">Continue shopping</a>
        </div>
    </div>
</main>
</body>
</html>
