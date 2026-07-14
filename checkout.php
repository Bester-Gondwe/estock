<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/models/User.php';

require_customer();

if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

$userModel = new User();
$user = $userModel->getUserById($_SESSION['user_id']);
$product = new Product();

$cartItems = [];
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $p = $product->getProductById($item['productID']);
    if (!$p) {
        continue;
    }
    $subtotal = (float) $p['product_price'] * (int) $item['quantity'];
    $total += $subtotal;
    $cartItems[] = [
        'product' => $p,
        'quantity' => (int) $item['quantity'],
        'subtotal' => $subtotal,
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'header.php'; ?>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800">
<?php include 'navbar.php'; ?>

<main class="max-w-5xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-6">Checkout</h1>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <form id="checkoutForm" class="lg:col-span-3 bg-white rounded-xl border border-slate-200 p-6 space-y-5 shadow-sm">
            <h2 class="text-lg font-semibold">Shipping & payment</h2>

            <div>
                <label class="block text-sm font-medium mb-1" for="shipping_address">Shipping address</label>
                <textarea name="shipping_address" id="shipping_address" rows="3" required
                          class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="payment_method">Payment method</label>
                <select name="payment_method" id="payment_method"
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="Cash on Delivery">Cash on Delivery</option>
                    <option value="Mobile Money">Mobile Money</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="notes">Order notes (optional)</label>
                <textarea name="notes" id="notes" rows="2"
                          class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                          placeholder="Delivery instructions, landmarks, etc."></textarea>
            </div>

            <p id="checkoutError" class="hidden text-sm text-red-600 bg-red-50 border border-red-100 rounded-lg px-3 py-2"></p>

            <button type="submit" id="placeOrderBtn"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-3 rounded-lg transition">
                Place order — <?= htmlspecialchars(format_money($total)) ?>
            </button>
        </form>

        <aside class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-6 shadow-sm h-fit">
            <h2 class="text-lg font-semibold mb-4">Order summary</h2>
            <ul class="space-y-3 mb-4">
                <?php foreach ($cartItems as $row): ?>
                    <li class="flex justify-between text-sm gap-4">
                        <span class="text-slate-600">
                            <?= htmlspecialchars($row['product']['product_name']) ?>
                            <span class="text-slate-400">×<?= $row['quantity'] ?></span>
                        </span>
                        <span class="font-medium whitespace-nowrap"><?= htmlspecialchars(format_money($row['subtotal'])) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="border-t pt-4 flex justify-between font-semibold">
                <span>Total</span>
                <span><?= htmlspecialchars(format_money($total)) ?></span>
            </div>
            <a href="cart.php" class="block text-center text-sm text-emerald-600 mt-4 hover:underline">Edit cart</a>
        </aside>
    </div>
</main>

<script>
document.getElementById('checkoutForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const btn = document.getElementById('placeOrderBtn');
    const err = document.getElementById('checkoutError');
    err.classList.add('hidden');
    btn.disabled = true;
    btn.textContent = 'Placing order...';

    const formData = new FormData(this);
    fetch('process_order.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success && data.redirect) {
                window.location.href = data.redirect;
                return;
            }
            err.textContent = data.message || 'Checkout failed.';
            err.classList.remove('hidden');
            btn.disabled = false;
            btn.textContent = 'Place order — <?= htmlspecialchars(format_money($total), ENT_QUOTES) ?>';
            if (data.redirect === 'login.php') {
                window.location.href = 'login.php';
            }
        })
        .catch(() => {
            err.textContent = 'Network error. Please try again.';
            err.classList.remove('hidden');
            btn.disabled = false;
            btn.textContent = 'Place order';
        });
});
</script>
</body>
</html>
