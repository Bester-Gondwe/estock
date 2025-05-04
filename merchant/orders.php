<?php

require_once "../models/Order.php";

$orders = new Order();

// Get the total number of records from our table "orders" for a specific Merchant.
$total_pages = $orders->countOrderForMerchant($_SESSION['user_id']);

// Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

// Number of results to show on each page.
$num_results_on_page = 8;

// Calculate the page to get the results we need from our table.
$calc_page = ($page - 1) * $num_results_on_page;

$customerOrders = $orders->getOrdersPaginated($_SESSION['user_id'], $calc_page, $num_results_on_page);

?>

<div class="sub-header px-4 py-2 bg-gray-100">
    <div>
        <p class="text-lg font-semibold">Orders List</p>
        <p><a href="./index.php?p=home" class="text-blue-500">Home</a> > <a href="./index.php?p=orders" class="text-blue-500">Orders List</a></p>
    </div>
</div>

<section class="orders px-4 py-6">
    <div class="orders-table__wrapper overflow-x-auto bg-white shadow-md rounded-lg">
        <h4 class="table-title text-xl font-bold mb-4">Recent Purchases</h4>
        <table class="orders-table min-w-full table-auto">
            <thead>
                <tr class="border-b">
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
                foreach ($customerOrders as $customerOrder) {
                    $date = new DateTime($customerOrder['order_date']);
                    $formattedDate = $date->format('M jS, Y'); // Format the date
                    echo "<tr class='order-table__row hover:bg-gray-100 cursor-pointer' id='row-1' data-id='{$customerOrder['order_id']}'>
                         <td class='px-4 py-2'>{$customerOrder['product_name']}</td>
                         <td class='px-4 py-2'>#{$customerOrder['order_id']}</td>
                         <td class='px-4 py-2'>{$formattedDate}</td>
                         <td class='px-4 py-2'>{$customerOrder['customer_name']}</td>
                         <td class='px-4 py-2'><span class='dot derivered inline-block w-2.5 h-2.5 mr-2 bg-green-500 rounded-full'></span>{$customerOrder['order_status']}</td>
                         <td class='px-4 py-2'>MWK200000</td> </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
        <ul class="pagination mt-6 flex justify-center space-x-2">
            <?php if ($page > 1): ?>
                <li><a href="./index.php?p=orders&page=<?php echo $page - 1 ?>" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Prev</a></li>
            <?php endif; ?>

            <?php if ($page > 3): ?>
                <li><a href="./index.php?p=orders&page=1" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">1</a></li>
                <li><span class="px-4 py-2 text-gray-500">...</span></li>
            <?php endif; ?>

            <?php if ($page - 2 > 0): ?><li><a href="./index.php?p=orders&page=<?php echo $page - 2 ?>" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700"><?php echo $page - 2 ?></a></li><?php endif; ?>
            <?php if ($page - 1 > 0): ?><li><a href="./index.php?p=orders&page=<?php echo $page - 1 ?>" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700"><?php echo $page - 1 ?></a></li><?php endif; ?>

            <li><a href="./index.php?p=orders&page=<?php echo $page ?>" class="px-4 py-2 bg-blue-500 text-white rounded"><?php echo $page ?></a></li>

            <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?><li><a href="./index.php?p=orders&page=<?php echo $page + 1 ?>" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700"><?php echo $page + 1 ?></a></li><?php endif; ?>
            <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?><li><a href="./index.php?p=orders&page=<?php echo $page + 2 ?>" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700"><?php echo $page + 2 ?></a></li><?php endif; ?>

            <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                <li><span class="px-4 py-2 text-gray-500">...</span></li>
                <li><a href="./index.php?p=orders&page=<?php echo ceil($total_pages / $num_results_on_page) ?>" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700"><?php echo ceil($total_pages / $num_results_on_page) ?></a></li>
            <?php endif; ?>

            <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                <li><a href="./index.php?p=orders&page=<?php echo $page + 1 ?>" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Next</a></li>
            <?php endif; ?>
        </ul>
    <?php endif; ?>

</section>

<script>
    const ordersRows = document.querySelectorAll(".order-table__row");
    ordersRows.forEach((order, _) => {
        order.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = `index.php?p=order_details&orderID=${this.dataset.id}`;
        });
    });
</script>
