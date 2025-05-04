<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    if (isset($_COOKIE['email']) && isset($_COOKIE['password'])) {
    }
    header("Location: ../login.php");
    exit;
}
// Determine the current page based on the 'p' query parameter
$page = isset($_GET['p']) ? basename($_GET['p']) : 'home';
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'; ?>
<script src="https://cdn.tailwindcss.com"></script>

<body class="bg-gray-100 text-gray-800 font-sans">

    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md flex flex-col">
            <div class="p-4 border-b flex items-center justify-between">
                <a href="#" class="flex items-center space-x-2">
                    <p class="text-xl font-semibold text-blue-600">eStock</p>
                    <img class="w-8 h-8" src="../images/shop-bag-with-handle-svgrepo-com.svg" alt="eStock Logo">
                </a>
            </div>

            <nav class="flex-1 overflow-y-auto mt-4">
                <ul class="space-y-2 px-4">
                    <li class="<?= $page === 'home' ? 'bg-blue-50 rounded-lg' : '' ?>">
                        <a href="./index.php?p=home" class="flex items-center space-x-2 px-3 py-2 hover:bg-blue-100 rounded-lg">
                            <img class="w-5 h-5" src="../images/dashboard-svgrepo-com.svg" alt="Dashboard Icon">
                            <span class="text-sm">Dashboard</span>
                        </a>
                    </li>

                    <li class="<?= $page === 'products' ? 'bg-blue-50 rounded-lg' : '' ?>">
                        <a href="./index.php?p=products" class="flex items-center space-x-2 px-3 py-2 hover:bg-blue-100 rounded-lg">
                            <img class="w-5 h-5" src="../images/album-collection-svgrepo-com.svg" alt="Products Icon">
                            <span class="text-sm">Products</span>
                        </a>
                    </li>

                    <li class="<?= $page === 'orders' ? 'bg-blue-50 rounded-lg' : '' ?>">
                        <a href="./index.php?p=orders" class="flex items-center space-x-2 px-3 py-2 hover:bg-blue-100 rounded-lg">
                            <img class="w-5 h-5" src="../images/online-delivery-svgrepo-com.svg" alt="Orders Icon">
                            <span class="text-sm">Orders</span>
                        </a>
                    </li>

                    <div class="pt-4 font-semibold text-sm text-gray-600">Categories</div>
                    <div id="categoriesMenu" class="flex items-center justify-between cursor-pointer px-3 py-2 text-sm hover:bg-gray-100 rounded-lg">
                        <p>Categories</p>
                        <img class="w-4 h-4" src="../images/ic_down-arrow.svg" alt="Toggle">
                    </div>

                    <div id="categoriesWrapper" class="hidden">
                        <ul id="categoriesList" class="text-sm pl-4 space-y-2 mt-2"></ul>
                        <p id="newCategoryBtn" class="text-blue-500 mt-3 pl-4 cursor-pointer hover:underline">+ New Category</p>
                    </div>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-y-auto">
            <?php include_once "../topbar.php"; ?>
            <div class="bg-white shadow-md rounded-lg p-6">
                <?php
                if (!file_exists($page . ".php")) {
                    include '../404.html';
                } else {
                    include $page . '.php';
                }
                ?>
            </div>
        </main>
    </div>

    <!-- Category Modal -->
    <div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <div class="flex justify-between items-center border-b pb-2 mb-4">
                <h2 class="text-lg font-semibold">Manage Category</h2>
                <button id="close-btn" class="text-gray-500 hover:text-red-500 text-2xl">&times;</button>
            </div>

            <form id="categoryForm" method="POST">
                <input type="hidden" name="categoryId" id="categoryId">
                <div class="mb-4">
                    <label for="categoryName" class="block text-sm font-medium text-gray-700 mb-1">Category Name</label>
                    <input type="text" name="categoryName" id="categoryName" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </form>

            <div class="flex justify-between mt-6">
                <button id="categoryDeleteBtn" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">DELETE</button>
                <button id="categoryUpdateBtn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">SAVE</button>
            </div>
        </div>
    </div>

    <script src="js/main.js"></script>
</body>
</html>
