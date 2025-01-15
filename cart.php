<?php
session_start();
require_once "models/Product.php";
$tableRow = '';
?>
<!DOCTYPE html>
<html lang="en">
<?php include "header.php" ?>

<body>
    <div class="wrapper">
        <?php include 'navbar.php' ?>
        <div class="content-wrapper">
            <div class="container">
                <h1>Your Cart</h1>
                <table>
                    <thead>
                        <th></th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th width="10%">Quantity</th>
                        <th>Subtotal</th>
                    </thead>
                    <tbody id="tbody">
                    </tbody>
                </table>
                <?php
                if (isset($_SESSION['user_id'])) {
                    echo "<button id='paypal-button'>Proceed</button>";
                } else {
                    echo "<h4>You need to <a href='login.php'>Login</a> to checkout.</h4>";
                }
                ?>
            </div>
        </div>
    </div>
    <script src="js/main.js"></script>
    <script>
        function getDetails() {
            fetch('cart_details.php')
                .then(response => response.text())
                .then(data => {
                    document.querySelector("#tbody").innerHTML = data;
                })
        }
        getDetails();

        // document.querySelector("#quantityField").addEventListener('change', function() {
        //     console.log('change');
        // })

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
                    }
                    getDetails();
                })
        }
    </script>
</body>

</html>