<?php
session_start();
require_once "models/Category.php";
$category = new Category();
$categories = $category->getAllCategories();
$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] : '';
?>

<header class="bg-white shadow-sm">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <a href="./" class="text-xl font-bold text-blue-600">eStore</a>

        <nav class="flex space-x-6 items-center">
            <a href="./" class="text-gray-700 hover:text-blue-600">Home</a>

            <!-- Category Dropdown -->
            <div class="relative group">
                <button class="text-gray-700 hover:text-blue-600 focus:outline-none">
                    Category
                </button>
                <div class="absolute left-0 mt-2 w-48 bg-white shadow-lg rounded hidden group-hover:block z-10">
                    <ul class="py-2">
                        <?php foreach ($categories as $catg) : ?>
                            <li>
                                <a href="category.php?category=<?= $catg['category_name'] ?>"
                                   class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <?= htmlspecialchars($catg['category_name']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Right Menu -->
        <div class="flex items-center space-x-6">
            <!-- Cart -->
            <div class="relative group">
                <button class="relative focus:outline-none">
                    <img src="images/cart.png" alt="Cart" class="w-6 h-6">
                    <span id="cartCount"
                          class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full px-1">
                        <?= $cartCount ?>
                    </span>
                </button>
                <div class="absolute right-0 mt-2 w-56 bg-white shadow-lg rounded hidden group-hover:block z-10">
                    <ul class="py-2" id="cart_menu">
                        <!-- JS will populate this -->
                    </ul>
                    <div class="border-t px-4 py-2 text-center">
                        <a href="cart.php" class="text-blue-600 hover:underline">Go to Cart</a>
                    </div>
                </div>
            </div>

            <!-- Auth Links -->
            <?php if ($isLoggedIn): ?>
                <div class="relative group">
                    <button class="text-gray-700 hover:text-blue-600 focus:outline-none">
                        <?= htmlspecialchars($userName) ?>
                    </button>
                    <div class="absolute right-0 mt-2 w-40 bg-white shadow-lg rounded hidden group-hover:block z-10">
                        <ul class="py-2">
                            <li>
                                <a href="logout.php"
                                   class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    Sign out
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            <?php else: ?>
                <a href="login.php" class="text-gray-700 hover:text-blue-600">Login</a>
                <a href="register.php" class="text-gray-700 hover:text-blue-600">Sign up</a>
            <?php endif; ?>
        </div>
    </div>
</header>
