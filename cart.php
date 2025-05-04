<?php
session_start();
require_once "models/Product.php";
?>
<!DOCTYPE html>
<html lang="en">
<?php include "header.php" ?>

<body class="bg-gray-100 text-gray-800">
    <?php include 'navbar.php' ?>

    <div class="max-w-6xl mx-auto p-6 mt-8">
        <h2 class="text-2xl font-semibold mb-4">🛒 Your Shopping Cart</h2>
        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-200 uppercase text-xs font-semibold text-gray-600">
                    <tr>
                        <th class="p-3"></th>
                        <th class="p-3">Name</th>
                        <th class="p-3">Price</th>
                        <th class="p-3">Quantity</th>
                        <th class="p-3">Subtotal</th>
                    </tr>
                </thead>
                <tbody id="tbody" class="divide-y divide-gray-200"></tbody>
                <!-- Data from JS -->
            </table>
        </div>

        <div class="mt-6 text-right">
            <?php if (isset($_SESSION['user_id'])): ?>
                <button onclick="proceedOrder()" id="paypal-button" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow">
                    Proceed to Checkout
                </button>
            <?php else: ?>
                <p class="text-red-500 font-medium">You need to <a href="login.php" class="text-blue-500 underline">Login</a> to checkout.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        getDetails();

        function getDetails() {
            fetch('cart_details.php')
                .then(res => res.text())
                .then(html => {
                    document.querySelector('#tbody').innerHTML = html;
                });
        }

        function proceedOrder() {
            fetch("process_order.php", { method: "POST" })
                .then(res => res.text())
                .then(data => {
                    alert(data);
                    location.reload();
                });
        }

        function deleteFromCart(productId) {
            const formData = new FormData();
            formData.append("productID", productId);
            fetch("delete_from_cart.php", {
                method: "POST",
                body: formData
            }).then(res => res.text())
              .then(data => {
                if (data === "error") {
                    alert("Failed to delete item.");
                } else {
                    document.querySelector("#cartCount").innerText = data;
                    getDetails();
                }
            });
        }

        document.addEventListener("change", function(e) {
            if (e.target.id === "quantityField") {
                const formData = new FormData();
                formData.append("productID", e.target.dataset.id);
                formData.append("quantity", e.target.value);
                fetch("update_cart.php", {
                    method: "POST",
                    body: formData
                }).then(() => getDetails());
            }
        }, true);
    </script>
</body>
</html>
