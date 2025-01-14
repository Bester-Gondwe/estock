<?php
session_start();

// Initialize cart session if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if the user is logged in
$userLoggedIn = isset($_SESSION['user_id']);

// Example categories
$categories = [
  "Clothes" => [1, 2, 3],
  "Accessories" => [4, 5, 6],
  "Electronics" => [7, 8, 9],
  "Food" => [10], // No associated products yet
  "Furniture" => [11], // No associated products yet
  "Vehicles" => [12] // No associated products yet
];

// Updated product list with corrected categories and unique product details
$products = [
  1 => ["img" => "images/img/card1.png", "brand" => "Brand A", "description" => "Stylish Shirt", "price" => 40, "category" => "Clothes"],
  2 => ["img" => "images/img/card2.png", "brand" => "Brand B", "description" => "Cool Jacket", "price" => 50, "category" => "Clothes"],
  3 => ["img" => "images/img/card3.png", "brand" => "Brand C", "description" => "Trendy Shoes", "price" => 60, "category" => "Clothes"],
  4 => ["img" => "images/img/card4.png", "brand" => "Brand D", "description" => "Elegant Watch", "price" => 120, "category" => "Accessories"],
  5 => ["img" => "images/img/card5.png", "brand" => "Brand E", "description" => "Smart Sunglasses", "price" => 35, "category" => "Accessories"],
  6 => ["img" => "images/img/card6.png", "brand" => "Brand F", "description" => "Leather Wallet", "price" => 25, "category" => "Accessories"],
  7 => ["img" => "images/img/card7.png", "brand" => "Brand G", "description" => "Designer Handbag", "price" => 200, "category" => "Electronics"],
  8 => ["img" => "images/img/card8.png", "brand" => "Brand H", "description" => "Gaming Headset", "price" => 80, "category" => "Electronics"],
  9 => ["img" => "images/img/card9.png", "brand" => "Brand K", "description" => "GamePad", "price" => 300, "category" => "Electronics"],
  10 => ["img" => "images/img/card10.png", "brand" => "Brand I", "description" => "Bluetooth Speaker", "price" => 100, "category" => "Electronics"],
  11 => ["img" => "images/img/card9.png", "brand" => "Brand K", "description" => "GamePad", "price" => 300, "category" => "Electronics"],
  12 => ["img" => "images/img/card10.png", "brand" => "Brand I", "description" => "Bluetooth Speaker", "price" => 100, "category" => "Electronics"]
];


// Filter products by category
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : null;
$filteredProducts = $selectedCategory
    ? array_filter($products, fn($p) => $p['category'] === $selectedCategory)
    : $products;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-store</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/index.css">
    <style>
       
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div>
            <img src="images/img/linkedin_banner_image_1.png" class="brand-logo" alt="E-store Logo">
        </div>
        <div class="search-container">
    <input type="text" id="searchBox" class="search-box" placeholder="Search...">
    
</div>

<div class="nav-items">
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
           
            <div class="cart-icon" onclick="goToCart()">
                <img src="images/img/cart.png" alt="Shopping Cart">
                <span id="cart-count"><?= count($_SESSION['cart']) ?></span>
            </div>
        </div>

<script>
    function searchProducts() {
        const query = document.getElementById('searchBox').value.trim();
        if (query) {
            window.location.href = `search.php?query=${encodeURIComponent(query)}`;
        }
    }
</script>

       
    </nav>

    <div class="categories">
    <h3>Shop by Category</h3>
    <select id="categoryDropdown" onchange="filterByCategory()">
        <option value="">All Categories</option>
        <?php foreach ($categories as $category => $ids): ?>
            <option value="<?= $category ?>" <?= $selectedCategory === $category ? 'selected' : '' ?>><?= $category ?></option>
        <?php endforeach; ?>
    </select>
</div>



<script>
    function filterByCategory() {
        const selectedCategory = document.getElementById('categoryDropdown').value;
        window.location.href = selectedCategory ? `?category=${selectedCategory}` : '?';
    }
</script>



    <!-- Products Section -->
    <div class="product-section">
        <h2><?= $selectedCategory ? $selectedCategory : "All Products" ?></h2>
        <div class="product-container">
            <?php if (empty($filteredProducts)): ?>
                <p>No products found in this category.</p>
            <?php else: ?>
                <?php foreach ($filteredProducts as $id => $product): ?>
                    <div class="product-card">
                        <img src="<?= $product['img'] ?>" alt="<?= $product['brand'] ?>">
                        <div class="product-info">
                            <h2 class="product-brand"><?= $product['brand'] ?></h2>
                            <p class="product-short-des"><?= $product['description'] ?></p>
                            <span class="price">K<?= $product['price'] ?></span>
                            <button class="card-btn" onclick="addToCart(<?= $id ?>, <?= $product['price'] ?>)">Add to Cart</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="cart-icon" onclick="goToCart()">
    <img src="images/img/cart.png" alt="Shopping Cart">
    <span id="cart-count"><?= count($_SESSION['cart']) ?></span>
</div>
<!-- Add Signup link inside the cart popup -->
<div id="cartPopup" style="display: none; position: absolute; background: #fff; border: 1px solid #ccc; padding: 10px; width: 200px; top: 60px; right: 20px;">
    <p><a href="login.php">Sign up to continue</a></p>
</div>

<script>
    function goToCart() {
        <?php if (!$userLoggedIn): ?>
            document.getElementById('cartPopup').style.display = 'block';
        <?php else: ?>
            window.location.href = 'cart.php';
        <?php endif; ?>
    }
</script>

    <!-- JavaScript -->
    <script>
        function addToCart(productId, price) {
            $.ajax({
                url: 'add_to_cart.php',
                type: 'POST',
                data: { product_id: productId, price: price },
                success: function (response) {
                    if (response === "error") {
                        alert("Failed to add product to cart.");
                    } else {
                        $("#cart-count").text(response); // Update cart count
                        alert("Product added to cart!");
                    }
                },
                error: function () {
                    alert("Error occurred while adding product to cart.");
                }
            });
        }

        function requireLogin(event) {
            event.preventDefault();
            alert("You must log in to proceed to checkout.");
            window.location.href = 'login.php';
        }

        function goToCart() {
            window.location.href = 'cart.php';
        }
    </script>
</body>

</html>

