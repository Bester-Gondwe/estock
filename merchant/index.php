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
<?php include 'header.php' ?>

<body class="font-sans bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-md">
            <div class="sidebar__header p-4 border-b">
                <a href="" class="flex items-center space-x-2">
                    <p class="text-xl font-semibold text-blue-600">eStock</p>
                    <img class="w-8 h-8" src="../images/shop-bag-with-handle-svgrepo-com.svg" alt="eStock Logo">
                </a>
            </div>
            <nav class="sidebar-nav mt-4">
                <ul class="space-y-4">
                    <li class="sidebar-nav__item <?= $page === 'home' ? 'bg-blue-50' : '' ?>">
                        <a href="./index.php?p=home" class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:bg-blue-100 rounded-lg">
                            <img class="w-5 h-5" src="../images/dashboard-svgrepo-com.svg" alt="Dashboard Icon">
                            <p class="text-sm">Dashboard</p>
                        </a>
                    </li>
                    <li class="sidebar-nav__item <?= $page === 'products' ? 'bg-blue-50' : '' ?>">
                        <a href="./index.php?p=products" class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:bg-blue-100 rounded-lg">
                            <img class="w-5 h-5" src="../images/album-collection-svgrepo-com.svg" alt="Products Icon">
                            <p class="text-sm">Products</p>
                        </a>
                    </li>
                    <li class="sidebar-nav__item <?= $page === 'orders' ? 'bg-blue-50' : '' ?>">
                        <a href="./index.php?p=orders" class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:bg-blue-100 rounded-lg">
                            <img class="w-5 h-5" src="../images/online-delivery-svgrepo-com.svg" alt="Orders Icon">
                            <p class="text-sm">Orders</p>
                        </a>
                    </li>
                    <div class="px-4 py-2 text-gray-700 font-semibold">Categories</div>
                    <div class="categories-menu px-4 py-2 cursor-pointer text-sm" id="categoriesMenu">
                        <p>Categories</p>
                        <img src="../images/ic_down-arrow.svg" alt="Arrow" class="inline ml-2 w-3 h-3">
                    </div>
                    <div id="categoriesWrapper" class="categories-wrapper hidden">
                        <ul class="categories_list space-y-2 px-4 py-2" id="categoriesList"></ul>
                        <p class='text-blue-500 cursor-pointer mt-4' id='newCategoryBtn'>new category</p>
                    </div>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <?php include_once "../topbar.php"; ?>
            <div class="content bg-white shadow-md rounded-lg p-6">
                <?php
                if (!file_exists($page . ".php")) {
                    include '../404.html';
                } else {
                    include $page . '.php';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Category Modal -->
    <div id="categoryModal" class="modal fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="modal-dialog bg-white rounded-lg shadow-xl w-1/3">
            <div class="modal__content">
                <div class="modal__header p-4 flex justify-between items-center">
                    <span id="close-btn" class="text-gray-500 text-2xl cursor-pointer">&times;</span>
                    <h3 class="text-lg font-semibold">Category</h3>
                </div>
                <div class="modal__body p-4">
                    <form class="category-form" id="categoryForm" method="POST">
                        <input type="hidden" name="categoryId" id="categoryId">
                        <div class="input-box mb-4">
                            <label class="block text-sm font-medium text-gray-700" for="categoryName">Category Name</label>
                            <input class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" type="text" name="categoryName" id="categoryName">
                        </div>
                    </form>
                </div>
                <div class="modal-footer p-4 bg-gray-100 flex justify-between items-center">
                    <button class="bg-red-500 text-white py-2 px-4 rounded-md" id="categoryDeleteBtn">DELETE</button>
                    <button class="bg-blue-500 text-white py-2 px-4 rounded-md" id="categoryUpdateBtn">SAVE</button>
                </div>
            </div>
        </div>
    </div>

    <script src="js/main.js"></script>
</body>

</html>
