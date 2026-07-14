<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/models/Order.php';

require_customer();

$orderModel = new Order();
$orders = $orderModel->getOrdersByCustomer($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'header.php'; ?>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800">
<?php include 'navbar.php'; ?>

<main class="max-w-4xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-6">My orders</h1>

    <?php if (empty($orders)): ?>
        <div class="bg-white border border-slate-200 rounded-xl p-10 text-center text-slate-500">
            <p class="mb-4">You have not placed any orders yet.</p>
            <a href="category.php" class="text-emerald-600 hover:underline font-medium">Browse products</a>
        </div>
    <?php else: ?>
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-100 text-slate-600 uppercase text-xs">
                    <tr>
                        <th class="text-left px-4 py-3">Order</th>
                        <th class="text-left px-4 py-3">Date</th>
                        <th class="text-left px-4 py-3">Status</th>
                        <th class="text-left px-4 py-3">Amount</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($orders as $o): ?>
                        <?php
                        $date = new DateTime($o['order_date']);
                        $statusClass = match ($o['order_status']) {
                            'Delivered' => 'bg-emerald-100 text-emerald-800',
                            'Cancelled' => 'bg-red-100 text-red-800',
                            'Processing', 'Shipped' => 'bg-blue-100 text-blue-800',
                            default => 'bg-amber-100 text-amber-800',
                        };
                        ?>
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-medium">#<?= (int) $o['order_id'] ?></td>
                            <td class="px-4 py-3"><?= $date->format('M j, Y') ?></td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium <?= $statusClass ?>">
                                    <?= htmlspecialchars($o['order_status']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3"><?= htmlspecialchars(format_money($o['amount'])) ?></td>
                            <td class="px-4 py-3 text-right">
                                <a href="order_view.php?id=<?= (int) $o['order_id'] ?>" class="text-emerald-600 hover:underline">Details</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>
</body>
</html>
