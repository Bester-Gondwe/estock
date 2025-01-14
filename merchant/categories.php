<?php
session_start();
require_once "../models/Order.php";

$category = new Category();

// Get the total number of records from our table "orders" for a specific Merchant.
$total_pages = $category->countCotegories();

// Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

// Number of results to show on each page.
$num_results_on_page = 8;

// Calculate the page to get the results we need from our table.
$calc_page = ($page - 1) * $num_results_on_page;

$categories = $category->getPaginatedCategories($calc_page, $num_results_on_page);

?>

<div class="sub-header">
    <div>
        <p>Orders List</p>
        <p><a href="./index.php?p=home">Home</a> > <a href="./index.php?p=home">Orders List</a></p>
    </div>
</div>
<section class="orders">
    <div class="orders-table__wrapper">
        <div>
            <h4 class="table-title">Recent Purchases</h4>
        </div>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Category Name</th>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Customer Name</th>
                    <th>Status</th>
                    <th>Amount</th>
                </tr>
            <tbody>



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
    const ordersRows = document.querySelectorAll(".order-table__row");
    ordersRows.forEach((order, _) => {
        order.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = `index.php?p=order_details&orderID=${this.dataset.id}`
        })
    })
</script>