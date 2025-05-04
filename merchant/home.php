<?php
require_once '../models/Order.php';
$order = new Order();

// get the first 8 recent orders
$recentOrders = $order->getOrdersPaginated($_SESSION['user_id'], 0, 8);
?>

<!-- Merchant Dashboard Overview -->
<div class="flex space-x-4 mb-6">
    <div class="bg-white shadow-md rounded-lg p-4 w-1/4">
        <h3 class="font-semibold text-lg">Total Products</h3>
        <p class="text-xl font-bold"><?php echo $order->countProductsForMerchant($_SESSION['user_id']) ?></p>
    </div>
    <div class="bg-white shadow-md rounded-lg p-4 w-1/4">
        <h3 class="font-semibold text-lg">Total Orders</h3>
        <p class="text-xl font-bold"><?php echo $order->countOrderForMerchant($_SESSION['user_id']); ?></p>
    </div>
    <div class="bg-white shadow-md rounded-lg p-4 w-1/4">
        <h3 class="font-semibold text-lg">Pending Orders</h3>
        <p class="text-xl font-bold"><?php echo $order->countOrderByStatusForMerchant($_SESSION['user_id'], 'Pending') ?></p>
    </div>
    <div class="bg-white shadow-md rounded-lg p-4 w-1/4">
        <h3 class="font-semibold text-lg">Completed Orders</h3>
        <p class="text-xl font-bold"><?php echo $order->countOrderByStatusForMerchant($_SESSION['user_id'], 'Delivered') ?></p>
    </div>
</div>

<section class="orders">
    <div class="orders-table__wrapper bg-white shadow-md rounded-lg p-4">
        <div>
            <h4 class="table-title font-semibold text-xl mb-4">Recent Purchases</h4>
        </div>
        <table class="orders-table w-full table-auto">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left">Product</th>
                    <th class="px-4 py-2 text-left">Order ID</th>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Customer Name</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($recentOrders as $recentOrder) {
                    $date = new DateTime($recentOrder['order_date']);
                    $formattedDate = $date->format('M jS, Y'); // Format the date
                    echo "<tr class='order-table__row cursor-pointer hover:bg-gray-100' id='row-1' data-id='{$recentOrder['order_id']}'>
                        <td class='px-4 py-2'>{$recentOrder['product_name']}</td>
                        <td class='px-4 py-2'>#{$recentOrder['order_id']}</td>
                        <td class='px-4 py-2'>{$formattedDate}</td>
                        <td class='px-4 py-2'>{$recentOrder['customer_name']}</td>
                        <td class='px-4 py-2'>
                            <span class='dot bg-green-500 inline-block w-2.5 h-2.5 rounded-full'></span>
                            {$recentOrder['order_status']}
                        </td>
                        <td class='px-4 py-2'>MWK200000</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<script>
    const ordersRows = document.querySelectorAll(".order-table__row");
    ordersRows.forEach((order, _) => {
        order.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = `index.php?p=order_details&orderID=${this.dataset.id}`
        })
    })
</script>
