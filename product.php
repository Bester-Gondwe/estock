<?php
require_once 'models/Product.php';
require_once 'models/ProductImage.php';
session_start();

if (isset($_GET['id'])) {
    $productID = $_GET['id'];
    $productObj = new Product();
    $product = $productObj->getProductById($productID);
    $productImage = new ProductImage();
    $productImages = $productImage->getProductImagesByProductId($productID);
} else {
    header("Location: ./");
    exit;
}
?>
<?php include 'header.php'; ?>

<body class="bg-gray-50 text-gray-800">
    <div class="min-h-screen flex flex-col">
        <?php include 'navbar.php'; ?>

        <main class="container mx-auto px-4 py-10">
            <section class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Image Gallery -->
                <div>
                    <div class="flex space-x-2 overflow-x-auto mb-4">
                        <?php foreach ($productImages as $productImg): ?>
                            <img 
                                src="uploads/<?= $productImg['file_name'] ?>" 
                                alt="Product Image"
                                class="w-20 h-20 object-cover border rounded cursor-pointer hover:ring-2 hover:ring-blue-500"
                                onclick="document.getElementById('productPrimaryImg').src=this.src"
                                onerror="this.src='images/fallback.png';"
                            >
                        <?php endforeach; ?>
                    </div>
                    <div class="border rounded overflow-hidden">
                        <img 
                            id="productPrimaryImg"
                            src="uploads/<?= $product['primary_image'] ?>" 
                            alt="<?= htmlspecialchars($product['product_name']) ?>" 
                            class="w-full h-96 object-cover"
                            onerror="this.src='images/fallback.png';"
                        >
                    </div>
                    <button 
                        onclick="addToCart(<?= $product['product_id'] ?>)" 
                        class="mt-4 w-full bg-blue-600 text-white py-3 px-6 rounded hover:bg-blue-700 transition"
                    >
                        Add to Cart
                    </button>
                </div>

                <!-- Product Info -->
                <div>
                    <h1 class="text-3xl font-bold mb-4"><?= htmlspecialchars($product['product_name']) ?></h1>
                    <h3 class="text-xl text-green-600 font-semibold mb-2">MWK <?= $product['product_price'] ?></h3>
                    <p class="text-sm text-gray-600 mb-2">
                        Category: 
                        <a href="category.php?category=<?= htmlspecialchars($product['category_name']) ?>" class="text-blue-600 hover:underline">
                            <?= htmlspecialchars($product['category_name']) ?>
                        </a>
                    </p>
                    <p class="text-gray-700 leading-relaxed"><?= nl2br(htmlspecialchars($product['product_description'])) ?></p>
                </div>
            </section>
        </main>
    </div>

    <script src="js/main.js"></script>
    <script>
        function addToCart(productId) {
            const formData = new FormData();
            formData.append("productID", productId);

            fetch("add_to_cart.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data === "error") {
                    alert("Failed to add product to cart.");
                } else {
                    document.querySelector("#cartCount").innerHTML = data;
                }
            });
        }
    </script>
</body>
</html>
