<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/models/ProductImage.php';

if (!isset($_GET['id'])) {
    header('Location: ./');
    exit;
}

$productID = (int) $_GET['id'];
$productObj = new Product();
$product = $productObj->getProductById($productID);

if (!$product) {
    http_response_code(404);
    include '404.html';
    exit;
}

$productImage = new ProductImage();
$productImages = $productImage->getProductImagesByProductId($productID);
$inStock = (int) $product['quantity'] > 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'header.php'; ?>
    <title><?= htmlspecialchars($product['product_name']) ?> — eStock</title>
</head>
<body class="bg-slate-50 text-slate-800">
    <div class="min-h-screen flex flex-col">
        <?php include 'navbar.php'; ?>

        <main class="max-w-6xl mx-auto w-full px-4 py-10">
            <section class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div>
                    <?php if (!empty($productImages)): ?>
                        <div class="flex space-x-2 overflow-x-auto mb-4">
                            <?php foreach ($productImages as $productImg): ?>
                                <img
                                    src="uploads/<?= htmlspecialchars($productImg['file_name']) ?>"
                                    alt="Product thumbnail"
                                    class="w-20 h-20 object-cover border rounded-lg cursor-pointer hover:ring-2 hover:ring-emerald-500 bg-slate-100"
                                    onclick="document.getElementById('productPrimaryImg').src=this.src"
                                    onerror="this.src='assets/default-image.svg'"
                                >
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <div class="border border-slate-200 rounded-xl overflow-hidden bg-white">
                        <img
                            id="productPrimaryImg"
                            src="uploads/<?= htmlspecialchars($product['primary_image'] ?? '') ?>"
                            alt="<?= htmlspecialchars($product['product_name']) ?>"
                            class="w-full h-96 object-cover bg-slate-100"
                            onerror="this.src='assets/default-image.svg'"
                        >
                    </div>
                    <?php if ($inStock): ?>
                        <button type="button" id="addCartBtn" onclick="addToCart(<?= (int) $product['product_id'] ?>)"
                                class="mt-4 w-full bg-emerald-600 text-white py-3 px-6 rounded-lg hover:bg-emerald-700 transition font-medium">
                            Add to cart
                        </button>
                    <?php else: ?>
                        <button type="button" disabled class="mt-4 w-full bg-slate-200 text-slate-500 py-3 px-6 rounded-lg cursor-not-allowed">
                            Out of stock
                        </button>
                    <?php endif; ?>
                    <p id="productMsg" class="text-sm mt-2 text-center hidden"></p>
                </div>

                <div>
                    <p class="text-sm text-slate-400 mb-1"><?= htmlspecialchars($product['category_name']) ?></p>
                    <h1 class="text-3xl font-bold mb-3"><?= htmlspecialchars($product['product_name']) ?></h1>
                    <p class="text-2xl text-emerald-700 font-semibold mb-4"><?= htmlspecialchars(format_money($product['product_price'])) ?></p>
                    <p class="text-sm text-slate-600 mb-2">
                        Stock:
                        <span class="<?= $inStock ? 'text-emerald-600' : 'text-red-600' ?> font-medium">
                            <?= $inStock ? (int) $product['quantity'] . ' available' : 'Unavailable' ?>
                        </span>
                    </p>
                    <?php if (!empty($product['sku'])): ?>
                        <p class="text-sm text-slate-500 mb-4">SKU: <?= htmlspecialchars($product['sku']) ?></p>
                    <?php endif; ?>
                    <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed">
                        <?= nl2br(htmlspecialchars($product['product_description'])) ?>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        function addToCart(productId) {
            const formData = new FormData();
            formData.append('productID', productId);
            const msg = document.getElementById('productMsg');
            fetch('add_to_cart.php', { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    msg.classList.remove('hidden', 'text-red-600', 'text-emerald-600');
                    if (!data.success) {
                        msg.classList.add('text-red-600');
                        msg.textContent = data.message || 'Failed to add to cart';
                        return;
                    }
                    msg.classList.add('text-emerald-600');
                    msg.textContent = data.message || 'Added to cart';
                    const el = document.querySelector('#cartCount');
                    if (el) el.textContent = data.count;
                })
                .catch(() => {
                    msg.classList.remove('hidden');
                    msg.classList.add('text-red-600');
                    msg.textContent = 'Network error';
                });
        }
    </script>
</body>
</html>
