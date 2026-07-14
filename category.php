<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/models/Product.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$filteredProducts = [];
$categorySlug = null;
$searchQuery = trim($_GET['q'] ?? '');
$product = new Product();

if ($searchQuery !== '') {
    $filteredProducts = $product->searchProducts($searchQuery);
} elseif (isset($_GET['category']) && $_GET['category'] !== '') {
    $categorySlug = $_GET['category'];
    $filteredProducts = $product->getProductsByCategory($categorySlug);
} else {
    $filteredProducts = $product->getAllProducts();
}

$pageTitle = $searchQuery !== ''
    ? 'Search: ' . $searchQuery
    : ($categorySlug === null ? 'All Products' : $categorySlug);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> — eStock</title>
    <?php include 'header.php'; ?>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-slate-50 text-slate-800">
    <div class="min-h-screen flex flex-col">
        <?php include 'navbar.php'; ?>

        <main class="flex-grow max-w-6xl mx-auto w-full px-4 py-8">
            <h1 class="text-2xl font-bold border-b border-slate-200 pb-3 mb-6">
                <?= htmlspecialchars($pageTitle) ?>
                <span class="text-slate-400 text-base font-normal ml-2">(<?= count($filteredProducts) ?>)</span>
            </h1>

            <?php if (empty($filteredProducts)): ?>
                <div class="text-center py-16 text-slate-500">
                    <p class="mb-4">No products found.</p>
                    <a href="category.php" class="text-emerald-600 hover:underline">Browse all products</a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php foreach ($filteredProducts as $filteredProduct): ?>
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-md transition">
                            <a href="product.php?id=<?= (int) $filteredProduct['product_id'] ?>">
                                <img
                                    class="w-full h-48 object-cover bg-slate-100"
                                    src="uploads/<?= htmlspecialchars($filteredProduct['primary_image'] ?? '') ?>"
                                    alt="<?= htmlspecialchars($filteredProduct['product_name']) ?>"
                                    onerror="this.onerror=null;this.src='assets/default-image.svg';"
                                >
                            </a>
                            <div class="p-4 flex flex-col space-y-2">
                                <a href="product.php?id=<?= (int) $filteredProduct['product_id'] ?>"
                                   class="text-lg font-semibold text-slate-800 hover:text-emerald-700 truncate"
                                   title="<?= htmlspecialchars($filteredProduct['product_name']) ?>">
                                    <?= htmlspecialchars($filteredProduct['product_name']) ?>
                                </a>
                                <div class="flex justify-between items-center">
                                    <span class="text-emerald-700 font-medium"><?= htmlspecialchars(format_money($filteredProduct['product_price'])) ?></span>
                                    <span class="text-xs text-slate-400"><?= (int) $filteredProduct['quantity'] ?> left</span>
                                </div>
                                <?php if ((int) $filteredProduct['quantity'] > 0): ?>
                                    <button type="button" onclick="addToCart(this, <?= (int) $filteredProduct['product_id'] ?>)"
                                            class="bg-emerald-600 text-white py-2 px-4 rounded-lg hover:bg-emerald-700 transition flex items-center justify-center gap-2 text-sm font-medium">
                                        <span class="cart-btn-text">Add to cart</span>
                                        <svg class="hidden animate-spin w-4 h-4 text-white cart-spinner" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a10 10 0 00-10 10h4z"></path>
                                        </svg>
                                    </button>
                                <?php else: ?>
                                    <button type="button" disabled class="bg-slate-200 text-slate-500 py-2 px-4 rounded-lg text-sm cursor-not-allowed">Out of stock</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <div id="toast" class="hidden fixed bottom-4 right-4 bg-emerald-600 text-white px-4 py-2 rounded-lg shadow-lg z-50 toast"></div>

    <script>
        function addToCart(button, productId) {
            const formData = new FormData();
            formData.append('productID', productId);
            const spinner = button.querySelector('.cart-spinner');
            const text = button.querySelector('.cart-btn-text');
            spinner.classList.remove('hidden');
            text.textContent = 'Adding...';
            button.disabled = true;

            fetch('add_to_cart.php', { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    spinner.classList.add('hidden');
                    text.textContent = 'Add to cart';
                    button.disabled = false;
                    if (!data.success) {
                        showToast(data.message || 'Could not add to cart', true);
                        return;
                    }
                    document.querySelector('#cartCount').textContent = data.count;
                    showToast(data.message || 'Item added to cart');
                })
                .catch(() => {
                    spinner.classList.add('hidden');
                    text.textContent = 'Add to cart';
                    button.disabled = false;
                    showToast('Network error', true);
                });
        }

        function showToast(message, isError) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.classList.toggle('bg-red-600', !!isError);
            toast.classList.toggle('bg-emerald-600', !isError);
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 3000);
        }
    </script>
</body>
</html>
