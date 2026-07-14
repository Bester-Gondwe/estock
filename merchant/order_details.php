<?php
require_once __DIR__ . '/../models/Order.php';

$order = new Order();
$orderId = (int) ($_GET['orderID'] ?? 0);

if ($orderId < 1 || !$order->merchantOwnsOrder($_SESSION['user_id'], $orderId)) {
    echo '<p class="text-red-600">Order not found or you do not have access.</p>';
    return;
}

$customerOrder = $order->getOrderById($orderId);
$orderProducts = $order->getOrderDetails($orderId);
$date = new DateTime($customerOrder['order_date']);
$statuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
?>

<div class="mb-6">
    <p class="text-sm text-slate-500"><a href="./index.php?p=orders" class="text-emerald-600 hover:underline">Orders</a> &gt; Details</p>
    <div class="flex flex-wrap justify-between items-center gap-4 mt-2">
        <h2 class="text-2xl font-bold">Order #<?= $orderId ?></h2>
        <span class="text-sm px-3 py-1 rounded-full bg-slate-100"><?= htmlspecialchars($customerOrder['order_status']) ?></span>
    </div>
    <p class="text-slate-500 text-sm mt-1"><?= $date->format('M j, Y \a\t g:i A') ?></p>
</div>

<div class="flex flex-wrap gap-3 items-end mb-6">
    <div>
        <label for="orderStatus" class="block text-sm font-medium mb-1">Update status</label>
        <select class="border border-slate-300 rounded-lg p-2" name="orderStatus" id="orderStatus">
            <?php foreach ($statuses as $status): ?>
                <option value="<?= $status ?>" <?= $customerOrder['order_status'] === $status ? 'selected' : '' ?>><?= $status ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="button" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700" data-id="<?= $orderId ?>" id="saveBtn">Save status</button>
    <p id="statusMsg" class="text-sm text-emerald-600 hidden">Status updated.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
        <p class="font-semibold mb-2">Customer</p>
        <p class="text-sm text-slate-600"><?= htmlspecialchars($customerOrder['customer_name']) ?></p>
        <p class="text-sm text-slate-600"><?= htmlspecialchars($customerOrder['email']) ?></p>
        <p class="text-sm text-slate-600"><?= htmlspecialchars($customerOrder['phone'] ?? '—') ?></p>
    </div>
    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
        <p class="font-semibold mb-2">Order info</p>
        <p class="text-sm text-slate-600">Payment: <?= htmlspecialchars($customerOrder['payment_method'] ?? 'N/A') ?></p>
        <p class="text-sm text-slate-600">Status: <?= htmlspecialchars($customerOrder['order_status']) ?></p>
        <?php if (!empty($customerOrder['notes'])): ?>
            <p class="text-sm text-slate-600 mt-2">Notes: <?= htmlspecialchars($customerOrder['notes']) ?></p>
        <?php endif; ?>
    </div>
    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
        <p class="font-semibold mb-2">Delivery to</p>
        <p class="text-sm text-slate-600"><?= nl2br(htmlspecialchars($customerOrder['shipping_address'] ?? '—')) ?></p>
    </div>
</div>

<div class="border border-slate-200 rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-xs uppercase text-slate-500">
            <tr>
                <th class="py-2 px-4 text-left">Product</th>
                <th class="py-2 px-4 text-left">Qty</th>
                <th class="py-2 px-4 text-left">Unit price</th>
                <th class="py-2 px-4 text-left">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orderProducts as $orderProduct): ?>
                <tr class="border-t border-slate-100">
                    <td class="py-2 px-4"><?= htmlspecialchars($orderProduct['product_name']) ?></td>
                    <td class="py-2 px-4"><?= (int) $orderProduct['quantity'] ?></td>
                    <td class="py-2 px-4"><?= htmlspecialchars(format_money($orderProduct['price'])) ?></td>
                    <td class="py-2 px-4"><?= htmlspecialchars(format_money($orderProduct['totalPrice'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="border-t bg-slate-50 font-semibold">
                <td colspan="3" class="py-2 px-4 text-right">Order total</td>
                <td class="py-2 px-4"><?= htmlspecialchars(format_money($customerOrder['amount'])) ?></td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
document.querySelector('#saveBtn').addEventListener('click', function () {
    const formData = new FormData();
    formData.append('orderStatus', document.querySelector('#orderStatus').value);
    fetch(`order_details_handler.php?orderID=${this.dataset.id}`, { method: 'POST', body: formData })
        .then(r => r.text())
        .then(() => {
            const msg = document.querySelector('#statusMsg');
            msg.classList.remove('hidden');
            setTimeout(() => location.reload(), 600);
        })
        .catch(() => alert('Failed to update status'));
});
</script>
