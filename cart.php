<?php
session_start();
require_once "models/Product.php";
$tableRow = '';
?>
<!DOCTYPE html>
<html lang="en">
<?php include "header.php" ?>

<body>
    <div class="wrapper cart-wrapper">
        <?php include 'navbar.php' ?>
        <div class="cart-content">
            <div class="container cart-container">
                <div class="table__wrapper">
                    <h4 class="table-title">Your Cart</h4>
                    <table>
                        <thead>
                            <th></th>
                            <th>Name</th>
                            <th>Price</th>
                            <th width="10%">Quantity</th>
                            <th>Subtotal</th>
                        </thead>
                        <tbody id="tbody">
                        </tbody>
                    </table>
                </div>
                <div class="cart-footer">
                    <?php
                    if (isset($_SESSION['user_id'])) {
                        echo "<button class='btn' id='paypal-button' onclick='proceedOrder()'>Proceed</button>";
                    } else {
                        echo "<h4>You need to <a href='login.php'>Login</a> to checkout.</h4>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script src="js/main.js"></script>
    <script>
        function proceedOrder() {
            fetch("process_order.php", {
                    method: "POST",
                }).then(response => response.text())
                .then(data => {
                    if (data === "error") {
                        alert("Failed to delete product from cart.");
                    } else {
                        console.log(data)
                    }
                })
        }

        function getDetails() {
            fetch('cart_details.php')
                .then(response => response.text())
                .then(data => {
                    document.querySelector("#tbody").innerHTML = data;
                })
        }
        getDetails();

        function deleteFromCart(productId) {
            const formData = new FormData();
            formData.append("productID", productId);
            fetch("delete_from_cart.php", {
                    method: "POST",
                    body: formData
                }).then(response => response.text())
                .then(data => {
                    if (data === "error") {
                        alert("Failed to delete product from cart.");
                    } else {
                        document.querySelector("#cartCount").innerHTML = data; // update cart
                        getDetails();
                    }

                })
        }

        document.addEventListener('change', function(event) {
            // check the input that have changed
            if (event.target.tagName.toLowerCase() === 'input') {
                if (event.target.id == "quantityField") {
                    const updateFormData = new FormData();
                    updateFormData.append('productID', event.target.dataset.id)
                    updateFormData.append('quantity', event.target.value);

                    fetch('update_cart.php', {
                        method: "POST",
                        body: updateFormData
                    }).then(response => response.text()).then(data => {
                        console.log(data);
                    })
                    getDetails();
                }
            }
        }, true); // 'true' makes this capture the event during the capturing phase
    </script>
</body>

</html>