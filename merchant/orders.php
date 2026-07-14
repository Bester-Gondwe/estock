<?php
require_once __DIR__ . '/../models/Order.php';

$orders = new Order();
$total_pages = $orders->countOrderForMerchant($_SESSION['user_id']);
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$num_results_on_page = 8;
$calc_page = ($page - 1) * $num_results_on_page;
$customerOrders = $orders->getOrdersPaginated($_SESSION['user_id'], $calc_page, $num_results_on_page);
$totalPages = max(1, (int) ceil($total_pages / $num_results_on_page));
?>

<div class="mb-6">
    <p class="text-lg font-semibold">Orders</p>
    <p class="text-sm text-slate-500"><a href="./index.php?p=home" class="text-emerald-600 hover:underline">Home</a> &gt; Orders</p>
</div>

<section>
    <div class="overflow-x-auto border border-slate-200 rounded-xl">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500">
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
                <?php if (empty($customerOrders)): ?>
                    <tr><td colspan="6" class="px-4 py-8 text-center text-slate-400">No orders found.</td></tr>
                <?php else: ?>
                    <?php foreach ($customerOrders as $customerOrder): ?>
                        <?php $date = new DateTime($customerOrder['order_date']); ?>
                        <tr class="order-table__row hover:bg-slate-50 cursor-pointer border-t border-slate-100" data-id="<?= (int) $customerOrder['order_id'] ?>">
                            <td class="px-4 py-3"><?= htmlspecialchars($customerOrder['product_name']) ?></td>
                            <td class="px-4 py-3">#<?= (int) $customerOrder['order_id'] ?></td>
                            <td class="px-4 py-3"><?= $date->format('M j, Y') ?></td>
                            <td class="px-4 py-3"><?= htmlspecialchars($customerOrder['customer_name']) ?></td>
                            <td class="px-4 py-3"><?= htmlspecialchars($customerOrder['order_status']) ?></td>
                            <td class="px-4 py-3 font-medium"><?= htmlspecialchars(format_money($customerOrder['amount'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
        <ul class="mt-6 flex flex-wrap justify-center gap-2">
            <?php if ($page > 1): ?>
                <li><a href="./index.php?p=orders&page=<?= $page - 1 ?>" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-sm">Prev</a></li>
            <?php endif; ?>
            <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                <li>
                    <a href="./index.php?p=orders&page=<?= $i ?>"
                       class="px-3 py-1.5 rounded-lg text-sm <?= $i === $page ? 'bg-emerald-700 text-white' : 'bg-slate-200 hover:bg-slate-300' ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <li><a href="./index.php?p=orders&page=<?= $page + 1 ?>" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-sm">Next</a></li>
            <?php endif; ?>
        </ul>
    <?php endif; ?>
</section>

<script>
document.querySelectorAll('.order-table__row').forEach(row => {
    row.addEventListener('click', () => {
        window.location.href = `index.php?p=order_details&orderID=${row.dataset.id}`;
    });
});
</script>
