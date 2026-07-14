<?php
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Product.php';

$order = new Order();
$productModel = new Product();
$userId = $_SESSION['user_id'];

$recentOrders = $order->getOrdersPaginated($userId, 0, 8);
$lowStock = $productModel->getLowStockProducts($userId);
$revenue = $order->sumRevenueForMerchant($userId);
?>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
        <h3 class="text-sm text-slate-500 font-medium">Total products</h3>
        <p class="text-2xl font-bold mt-1"><?= (int) $order->countProductsForMerchant($userId) ?></p>
    </div>
    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
        <h3 class="text-sm text-slate-500 font-medium">Total orders</h3>
        <p class="text-2xl font-bold mt-1"><?= (int) $order->countOrderForMerchant($userId) ?></p>
    </div>
    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
        <h3 class="text-sm text-slate-500 font-medium">Pending orders</h3>
        <p class="text-2xl font-bold mt-1 text-amber-600"><?= (int) $order->countOrderByStatusForMerchant($userId, 'Pending') ?></p>
    </div>
    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
        <h3 class="text-sm text-slate-500 font-medium">Revenue</h3>
        <p class="text-2xl font-bold mt-1 text-emerald-700"><?= htmlspecialchars(format_money($revenue)) ?></p>
    </div>
</div>

<?php if (!empty($lowStock)): ?>
<section class="mb-8">
    <h4 class="font-semibold text-lg mb-3 text-amber-700">Low stock alerts</h4>
    <div class="overflow-x-auto border border-amber-200 rounded-xl bg-amber-50">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left text-amber-900/70">
                    <th class="px-4 py-2">Product</th>
                    <th class="px-4 py-2">SKU</th>
                    <th class="px-4 py-2">Qty</th>
                    <th class="px-4 py-2">Threshold</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lowStock as $item): ?>
                    <tr class="border-t border-amber-100">
                        <td class="px-4 py-2 font-medium"><?= htmlspecialchars($item['product_name']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($item['sku'] ?? '—') ?></td>
                        <td class="px-4 py-2 text-red-600 font-semibold"><?= (int) $item['quantity'] ?></td>
                        <td class="px-4 py-2"><?= (int) $item['low_stock_threshold'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php endif; ?>

<section>
    <h4 class="font-semibold text-lg mb-4">Recent orders</h4>
    <div class="overflow-x-auto border border-slate-200 rounded-xl">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Product</th>
                    <th class="px-4 py-3 text-left">Order ID</th>
                    <th class="px-4 py-3 text-left">Date</th>
                    <th class="px-4 py-3 text-left">Customer</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentOrders)): ?>
                    <tr><td colspan="6" class="px-4 py-8 text-center text-slate-400">No orders yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($recentOrders as $recentOrder): ?>
                        <?php $date = new DateTime($recentOrder['order_date']); ?>
                        <tr class="order-table__row cursor-pointer hover:bg-slate-50 border-t border-slate-100" data-id="<?= (int) $recentOrder['order_id'] ?>">
                            <td class="px-4 py-3"><?= htmlspecialchars($recentOrder['product_name']) ?></td>
                            <td class="px-4 py-3">#<?= (int) $recentOrder['order_id'] ?></td>
                            <td class="px-4 py-3"><?= $date->format('M j, Y') ?></td>
                            <td class="px-4 py-3"><?= htmlspecialchars($recentOrder['customer_name']) ?></td>
                            <td class="px-4 py-3"><?= htmlspecialchars($recentOrder['order_status']) ?></td>
                            <td class="px-4 py-3 font-medium"><?= htmlspecialchars(format_money($recentOrder['amount'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<script>
document.querySelectorAll('.order-table__row').forEach(row => {
    row.addEventListener('click', () => {
        window.location.href = `index.php?p=order_details&orderID=${row.dataset.id}`;
    });
});
</script>
