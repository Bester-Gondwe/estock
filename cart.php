<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/models/Product.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'header.php'; ?>
</head>
<body class="bg-slate-50 text-slate-800">
    <?php include 'navbar.php'; ?>

    <div class="max-w-5xl mx-auto px-4 py-10">
        <h1 class="text-2xl font-bold mb-6">Shopping cart</h1>
        <div class="overflow-x-auto bg-white border border-slate-200 shadow-sm rounded-xl">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-slate-100 uppercase text-xs font-semibold text-slate-600">
                    <tr>
                        <th class="p-3 w-12"></th>
                        <th class="p-3">Product</th>
                        <th class="p-3">Price</th>
                        <th class="p-3">Quantity</th>
                        <th class="p-3">Subtotal</th>
                    </tr>
                </thead>
                <tbody id="tbody" class="divide-y divide-slate-100"></tbody>
            </table>
        </div>

        <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <a href="category.php" class="text-emerald-700 hover:underline text-sm">&larr; Continue shopping</a>
            <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? '') !== 'Merchant'): ?>
                <a href="checkout.php" id="checkoutBtn" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-lg shadow-sm font-medium">
                    Proceed to checkout
                </a>
            <?php elseif (isset($_SESSION['user_id'])): ?>
                <p class="text-amber-700 text-sm">Merchant accounts cannot checkout as customers.</p>
            <?php else: ?>
                <p class="text-slate-600 text-sm">Please <a href="login.php" class="text-emerald-600 underline">log in</a> to checkout.</p>
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

        function deleteFromCart(productId) {
            const formData = new FormData();
            formData.append('productID', productId);
            fetch('delete_from_cart.php', { method: 'POST', body: formData })
                .then(res => res.json ? res.json().catch(() => res.text()) : res.text())
                .then(data => {
                    const count = typeof data === 'object' ? data.count : data;
                    if (count === 'error' || (typeof data === 'object' && data.success === false)) {
                        alert('Failed to remove item.');
                        return;
                    }
                    const el = document.querySelector('#cartCount');
                    if (el) el.textContent = typeof data === 'object' ? data.count : data;
                    getDetails();
                });
        }

        document.addEventListener('change', function (e) {
            if (e.target.id === 'quantityField') {
                const formData = new FormData();
                formData.append('productID', e.target.dataset.id);
                formData.append('quantity', e.target.value);
                fetch('update_cart.php', { method: 'POST', body: formData })
                    .then(r => r.json())
                    .then(data => {
                        if (!data.success) {
                            alert(data.message || 'Could not update quantity');
                        }
                        getDetails();
                    })
                    .catch(() => getDetails());
            }
        }, true);
    </script>
</body>
</html>
