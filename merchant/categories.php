<?php
session_start();
require_once "../models/Order.php";
require_once "../models/Category.php";

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

<div class="bg-white shadow-md p-6 rounded-lg mb-6">
    <div class="mb-4">
        <p class="text-xl font-semibold text-gray-800">Orders List</p>
        <p class="text-gray-600 text-sm">
            <a href="./index.php?p=home" class="text-blue-600 hover:underline">Home</a> > 
            <a href="./index.php?p=orders" class="text-blue-600 hover:underline">Orders List</a>
        </p>
    </div>

    <section class="orders">
        <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
            <h4 class="text-2xl font-semibold text-gray-800 py-4 px-6">Recent Purchases</h4>
            
            <table class="min-w-full table-auto">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Category Name</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Order ID</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Date</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Customer Name</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Amount</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <?php foreach ($categories as $category): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-700"><?php echo htmlspecialchars($category['category_name']); ?></td>
                            <td class="px-6 py-4 text-gray-700"><?php echo htmlspecialchars($category['order_id']); ?></td>
                            <td class="px-6 py-4 text-gray-700"><?php echo htmlspecialchars($category['date']); ?></td>
                            <td class="px-6 py-4 text-gray-700"><?php echo htmlspecialchars($category['customer_name']); ?></td>
                            <td class="px-6 py-4 text-gray-700"><?php echo htmlspecialchars($category['status']); ?></td>
                            <td class="px-6 py-4 text-gray-700"><?php echo htmlspecialchars($category['amount']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
            <div class="mt-6 flex justify-between items-center">
                <ul class="flex space-x-2">
                    <?php if ($page > 1): ?>
                        <li>
                            <a href="./index.php?p=orders&page=<?php echo $page - 1 ?>" class="text-blue-600 hover:underline">Prev</a>
                        </li>
                    <?php endif; ?>

                    <?php if ($page > 3): ?>
                        <li>
                            <a href="./index.php?p=orders&page=1" class="text-blue-600 hover:underline">1</a>
                        </li>
                        <li class="text-gray-600">...</li>
                    <?php endif; ?>

                    <?php if ($page - 2 > 0): ?>
                        <li>
                            <a href="./index.php?p=orders&page=<?php echo $page - 2 ?>" class="text-blue-600 hover:underline"><?php echo $page - 2 ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if ($page - 1 > 0): ?>
                        <li>
                            <a href="./index.php?p=orders&page=<?php echo $page - 1 ?>" class="text-blue-600 hover:underline"><?php echo $page - 1 ?></a>
                        </li>
                    <?php endif; ?>

                    <li class="font-semibold text-blue-600">
                        <a href="./index.php?p=orders&page=<?php echo $page ?>"><?php echo $page ?></a>
                    </li>

                    <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                        <li>
                            <a href="./index.php?p=orders&page=<?php echo $page + 1 ?>" class="text-blue-600 hover:underline"><?php echo $page + 1 ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                        <li>
                            <a href="./index.php?p=orders&page=<?php echo $page + 2 ?>" class="text-blue-600 hover:underline"><?php echo $page + 2 ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                        <li class="text-gray-600">...</li>
                        <li>
                            <a href="./index.php?p=orders&page=<?php echo ceil($total_pages / $num_results_on_page) ?>" class="text-blue-600 hover:underline"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                        <li>
                            <a href="./index.php?p=orders&page=<?php echo $page + 1 ?>" class="text-blue-600 hover:underline">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endif; ?>
    </section>
</div>

<script>
    const ordersRows = document.querySelectorAll(".order-table__row");
    ordersRows.forEach((order, _) => {
        order.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = `index.php?p=order_details&orderID=${this.dataset.id}`
        })
    })
</script>
