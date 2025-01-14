<?php
session_start();
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

<div class="sub-header">
    <div>
        <p>Orders List</p>
        <p><a href="./index.php?p=home">Home</a> > <a href="./index.php?p=orders">Orders List</a></p>
    </div>

    <select name="orderStatusFilter" id="orderStatusFilter">
        <option value selected>All</option>
        <option value="pending">Pending</option>
        <option value="cancled">Cancled</option>
        <option value="derivered">Derivered</option>
    </select>

</div>
<section class="orders">
    <div class="orders-table__wrapper">
        <div>
            <h4 class="table-title">Recent Purchases</h4>
        </div>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Customer Name</th>
                    <th>Status</th>
                    <th>Amount</th>
                </tr>
            <tbody>

                <?php
                foreach ($customerOrders as $customerOrder) {
                    $date = new DateTime($customerOrder['order_date']);
                    $formattedDate = $date->format('M jS, Y'); // Format the date
                    echo "<tr class='order-table__row' id='row-1' data-id='{$customerOrder['order_id']}'>
                         <td>{$customerOrder['product_name']}</td>
                         <td>#{$customerOrder['order_id']}</td>
                         <td>{$formattedDate}</td>
                         <td>{$customerOrder['customer_name']}</td>
                         <td><span class='dot derivered'></span>{$customerOrder['order_status']}</td>
                         <td>MWK200000</td> </tr>";
                } ?>

            </tbody>
            </thead>
        </table>
    </div>

    <?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
        <ul class="pagination">
            <?php if ($page > 1): ?>
                <li class="prev">orders.php?page=<?php echo $page - 1 ?>">Prev</a></li>
            <?php endif; ?>

            <?php if ($page > 3): ?>
                <li class="start"><a href="./index.php?p=orders?page=1">1</a></li>
                <li class="dots">...</li>
            <?php endif; ?>

            <?php if ($page - 2 > 0): ?><li class="page"><a href="./index.php?p=orders?page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a></li><?php endif; ?>
            <?php if ($page - 1 > 0): ?><li class="page"><a href="./index.php?p=orders?page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a></li><?php endif; ?>

            <li class="currentpage"><a href="./index.php?p=orders?page=<?php echo $page ?>"><?php echo $page ?></a></li>

            <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?><li class="page"><a href="./index.php?p=orders?page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a></li><?php endif; ?>
            <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?><li class="page"><a href="./index.php?p=orders?page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a></li><?php endif; ?>

            <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                <li class="dots">...</li>
                <li class="end"><a href="./index.php?p=orders?page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a></li>
            <?php endif; ?>

            <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                <li class="next"><a href="./index.php?p=orders?page=<?php echo $page + 1 ?>">Next</a></li>
            <?php endif; ?>
        </ul>
    <?php endif; ?>

</section>
<script>
    document.querySelector("#orderStatusFilter").addEventListener('change',function() {
        console.log(this.value)
    })

    const ordersRows = document.querySelectorAll(".order-table__row");
    ordersRows.forEach((order, _) => {
        order.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = `index.php?p=order_details&orderID=${this.dataset.id}`
        })
    })
</script>