<?php
require_once __DIR__ . '/../config/bootstrap.php';
require_merchant();

$page = isset($_GET['p']) ? basename($_GET['p']) : 'home';
$allowed = ['home', 'products', 'orders', 'order_details', 'categories'];
if (!in_array($page, $allowed, true)) {
    $page = 'home';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merchant Dashboard — eStock</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-slate-100 text-slate-800 font-sans">

    <div class="flex min-h-screen">
        <aside class="w-64 bg-white shadow-sm border-r border-slate-200 flex flex-col shrink-0">
            <div class="p-4 border-b flex items-center justify-between">
                <a href="./index.php?p=home" class="flex items-center space-x-2">
                    <p class="text-xl font-semibold text-emerald-700">eStock</p>
                    <img class="w-8 h-8" src="../images/shop-bag-with-handle-svgrepo-com.svg" alt="eStock">
                </a>
            </div>

            <nav class="flex-1 overflow-y-auto mt-4">
                <ul class="space-y-1 px-3">
                    <li>
                        <a href="./index.php?p=home" class="flex items-center space-x-2 px-3 py-2 rounded-lg text-sm <?= $page === 'home' ? 'bg-emerald-50 text-emerald-800 font-medium' : 'hover:bg-slate-50' ?>">
                            <img class="w-5 h-5" src="../images/dashboard-svgrepo-com.svg" alt="">
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="./index.php?p=products" class="flex items-center space-x-2 px-3 py-2 rounded-lg text-sm <?= $page === 'products' ? 'bg-emerald-50 text-emerald-800 font-medium' : 'hover:bg-slate-50' ?>">
                            <img class="w-5 h-5" src="../images/album-collection-svgrepo-com.svg" alt="">
                            <span>Products</span>
                        </a>
                    </li>
                    <li>
                        <a href="./index.php?p=orders" class="flex items-center space-x-2 px-3 py-2 rounded-lg text-sm <?= $page === 'orders' ? 'bg-emerald-50 text-emerald-800 font-medium' : 'hover:bg-slate-50' ?>">
                            <img class="w-5 h-5" src="../images/online-delivery-svgrepo-com.svg" alt="">
                            <span>Orders</span>
                        </a>
                    </li>
                    <li>
                        <a href="./index.php?p=categories" class="flex items-center space-x-2 px-3 py-2 rounded-lg text-sm <?= $page === 'categories' ? 'bg-emerald-50 text-emerald-800 font-medium' : 'hover:bg-slate-50' ?>">
                            <img class="w-5 h-5" src="../images/album-collection-svgrepo-com.svg" alt="">
                            <span>Categories</span>
                        </a>
                    </li>

                    <li class="pt-4 px-3 text-xs font-semibold uppercase tracking-wide text-slate-400">Quick categories</li>
                    <li>
                        <div id="categoriesMenu" class="flex items-center justify-between cursor-pointer px-3 py-2 text-sm hover:bg-slate-50 rounded-lg">
                            <p>Browse list</p>
                            <img class="w-4 h-4 transition-transform" id="categoriesArrow" src="../images/ic_down-arrow.svg" alt="">
                        </div>
                        <div id="categoriesWrapper" class="hidden pl-2">
                            <ul id="categoriesList" class="text-sm space-y-1 mt-1 max-h-48 overflow-y-auto"></ul>
                            <p id="newCategoryBtn" class="text-emerald-600 mt-2 px-3 cursor-pointer hover:underline text-sm">+ New category</p>
                        </div>
                    </li>
                </ul>
            </nav>

            <div class="p-4 border-t text-sm">
                <a href="../" class="text-slate-500 hover:text-emerald-700">← Back to store</a>
            </div>
        </aside>

        <main class="flex-1 p-6 overflow-y-auto">
            <?php include_once '../topbar.php'; ?>
            <div class="bg-white shadow-sm border border-slate-200 rounded-xl p-6 mt-4">
                <?php
                if (!file_exists(__DIR__ . '/' . $page . '.php')) {
                    include '../404.html';
                } else {
                    include $page . '.php';
                }
                ?>
            </div>
        </main>
    </div>

    <div id="categoryModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
            <div class="flex justify-between items-center border-b pb-2 mb-4">
                <h2 class="text-lg font-semibold" id="categoryModalTitle">Manage category</h2>
                <button type="button" id="categoryCloseBtn" class="text-slate-500 hover:text-red-500 text-2xl leading-none" aria-label="Close">&times;</button>
            </div>
            <form id="categoryForm" method="POST" onsubmit="return false;">
                <input type="hidden" name="categoryId" id="categoryId" value="">
                <div class="mb-4">
                    <label for="categoryName" class="block text-sm font-medium text-slate-700 mb-1">Category name</label>
                    <input type="text" name="categoryName" id="categoryName" required
                           class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                           placeholder="e.g. Electronics">
                </div>
                <p id="categoryFormError" class="hidden text-sm text-red-600 mb-3"></p>
            </form>
            <div class="flex justify-between mt-6 gap-3">
                <button type="button" id="categoryDeleteBtn" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">Delete</button>
                <button type="button" id="categoryUpdateBtn" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg ml-auto">Save</button>
            </div>
        </div>
    </div>

    <script src="js/main.js"></script>
</body>
</html>
