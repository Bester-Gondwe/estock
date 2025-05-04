<?php
require_once "../models/Order.php";

$order = new Order();

$customerOder = $order->getOrderById($_GET['orderID']);
$orderProducts = $order->getOrderDetails($_GET['orderID']);
?>

<div class="bg-gray-50 p-6 rounded-lg shadow-md">
    <div class="text-xl font-semibold mb-4">
        <p>Orders List</p>
        <p class="text-sm text-gray-500">Home > Orders Details</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <p class="text-2xl font-bold text-gray-700">Order ID:#<?php echo $_GET['orderID'] ?></p>
            <p class="text-sm text-gray-500"><?php echo $customerOder['order_status'] ?></p>
        </div>

        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center space-x-2">
                <img class="h-6 w-6" src="../images/calendar.svg" alt="Calendar Icon">
                <p class="text-gray-600"><?php
                                            $date = new DateTime($customerOder['order_date']);
                                            $formattedDate = $date->format('M jS, Y');
                                            echo $formattedDate; ?> </p>
            </div>

            <div class="flex space-x-4">
                <select class="border border-gray-300 rounded-lg p-2" name="orderStatus" id="orderStatus">
                    <option>Delivered</option>
                    <option>Cancelled</option>
                </select>
                <button class="bg-blue-500 text-white px-4 py-2 rounded-lg" data-id="<?php echo $_GET['orderID'] ?>" id="saveBtn">Save</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gray-50 p-4 rounded-lg shadow-md">
                <img class="h-12 w-12 mb-4" src="../images/user.svg" alt="Customer Icon">
                <p class="font-semibold text-lg text-gray-700">Customer</p>
                <p class="text-sm text-gray-600">Full Name: <?php echo $customerOder['customer_name'] ?></p>
                <p class="text-sm text-gray-600">Email: <?php echo $customerOder['email'] ?></p>
                <p class="text-sm text-gray-600">Phone: +23893843974</p>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg shadow-md">
                <img class="h-12 w-12 mb-4" src="../images/package.svg" alt="Order Info Icon">
                <p class="font-semibold text-lg text-gray-700">Order Info</p>
                <p class="text-sm text-gray-600">Shipping: Express</p>
                <p class="text-sm text-gray-600">Payment Method: PayPal</p>
                <p class="text-sm text-gray-600">Status: <?php echo $customerOder['order_status'] ?></p>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg shadow-md">
                <img class="h-12 w-12 mb-4" src="../images/location.svg" alt="Delivery To Icon">
                <p class="font-semibold text-lg text-gray-700">Delivery To</p>
                <p class="text-sm text-gray-600">Address: </p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md mt-6">
        <h4 class="text-xl font-semibold text-gray-700 mb-4">Products</h4>
        <table class="w-full text-left table-auto">
            <thead>
                <tr class="border-b">
                    <th class="py-2 px-4 text-sm font-medium text-gray-700">Product Name</th>
                    <th class="py-2 px-4 text-sm font-medium text-gray-700">Order ID</th>
                    <th class="py-2 px-4 text-sm font-medium text-gray-700">Quantity</th>
                    <th class="py-2 px-4 text-sm font-medium text-gray-700">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($orderProducts as $orderProduct) {
                    echo "<tr class='border-b'>
                        <td class='py-2 px-4 text-sm text-gray-600'>{$orderProduct['product_name']}</td>
                        <td class='py-2 px-4 text-sm text-gray-600'>#{$orderProduct['order_id']}</td>
                        <td class='py-2 px-4 text-sm text-gray-600'>{$orderProduct['quantity']}</td>
                        <td class='py-2 px-4 text-sm text-gray-600'>MWK{$orderProduct['totalPrice']}</td>
                    </tr>";
                }
                ?>
            </tbody>
            <tfoot>
                <tr class="border-t">
                    <td colspan="3" class="py-2 px-4 text-sm font-semibold text-gray-700">Total</td>
                    <td class="py-2 px-4 text-sm text-gray-700">$180</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
    const orderStatus = document.querySelector("#orderStatus");
    document.querySelector("#saveBtn").addEventListener('click', function() {

        const formData = new FormData();
        formData.append('orderStatus', orderStatus.value);

        fetch(`order_details_handler.php?orderID=${this.dataset.id}`, {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
            })
            .catch(error => {
                console.log("error");
            });
    })
</script>
