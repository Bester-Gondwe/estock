<?php
require_once "../models/Order.php";

$order = new Order();

$customerOder = $order->getOrderById($_GET['orderID']);

$orderProducts = $order->getOrderDetails($_GET['orderID']);

?>

<div class="sub-header">
    <div>
        <p>Orders List</p>
        <p>Home > Orders Details</p>
    </div>
</div>
<div class="order-details">
    <div class="card">
        <div class="order-details-header">
            <p class="order-details-header__title">Order ID:#<?php echo $_GET['orderID'] ?></p>
            <p class="order-details-header__status"><?php echo $customerOder['order_status'] ?></p>
        </div>
        <div class="order-details-sub-header">
            <div class="order-deatils__calender">
                <img class="order-deatils__calender-icon" src="" alt="">
                <p class="order-deatils__calender-text"><?php
                                                        $date = new DateTime($customerOder['order_date']);
                                                        $formattedDate = $date->format('M jS, Y');
                                                        echo $formattedDate ?> </p>
            </div>

            <div class="order-details__actions">
                <select class="order-details__status-action" name="orderStatus" id="orderStatus" >
                    <option>Derivered</option>
                    <option>Cancled</option>
                </select>
                <button class="btn" data-id="<?php echo $_GET['orderID'] ?>" id="saveBtn">save</button>
            </div>
        </div>
        <div class="orders-details__info-wrapper">
            <div class="orders-details__info-card orders-details-shipping">
                <img class="orders-details__info-icon" src="../images/online-delivery-svgrepo-com.svg" alt="Orders Icon">
                <div class="orders-details__info">
                    <p class="orders-details__info-title">Customer</p>
                    <p class="rders-details__info-text">Full Name: <?php echo $customerOder['customer_name'] ?></p>
                    <p class="rders-details__info-text">Email: <?php echo $customerOder['email'] ?></p>
                    <p class="rders-details__info-text">Phone: +23893843974</p>
                </div>
            </div>
            <div class="orders-details__info-card orders-details-shipping">
                <img class="orders-details__info-icon" src="../images/online-delivery-svgrepo-com.svg" alt="Orders Icon">
                <div class="orders-details__info">
                    <p class="orders-details__info-title">Order Info</p>
                    <p class="rders-details__info-text">Shipping : Express</p>
                    <p class="rders-details__info-text">Payment Method : PayPal</p>
                    <p class="rders-details__info-text">Status : <?php echo $customerOder['order_status'] ?></p>
                </div>
            </div>

            <div class="orders-details__info-card orders-details-shipping">
                <img class="orders-details__info-icon" src="../images/online-delivery-svgrepo-com.svg" alt="Orders Icon">

                <div class="orders-details__info">
                    <p class="orders-details__info-title">Derivery To</p>
                    <p class="rders-details__info-text">Address : </p>
                </div>
            </div>
        </div>
    </div>




    <div class="card order-details__products">
        <h4 class="order-details__products-title">Products</h4>
        <table class="order-details__products-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Order ID</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($orderProducts as $orderProduct) {
                    echo " <tr id='row-1'>
                    <td>{$orderProduct['product_name']}</td>
                    <td>#{$orderProduct['order_id']}</td>
                    <td>{$orderProduct['quantity']}</td>
                    <td>MWK{$orderProduct['totalPrice']}</td>
                </tr>
                ";
                }
                ?>
            </tbody>
            <tfoot>
                <tfoot>
                    <tr>
                        <td colspan="3">Total</td>
                        <td> $180</td>
                    </tr>
                </tfoot>
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