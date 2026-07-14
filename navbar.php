<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/models/Category.php';

$category = new Category();
$categories = $category->getAllCategories();
$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? ($_SESSION['first_name'] ?? '') . ' ' . ($_SESSION['last_name'] ?? '') : '';
$userRole = $_SESSION['user_role'] ?? '';
?>

<header class="bg-white border-b border-slate-200 sticky top-0 z-40">
    <div class="max-w-6xl mx-auto px-4 py-3 flex flex-wrap gap-4 justify-between items-center">
        <a href="./" class="text-xl font-bold text-emerald-700 tracking-tight">eStock</a>

        <form action="category.php" method="get" class="flex-1 max-w-md order-3 sm:order-none w-full sm:w-auto">
            <div class="relative">
                <input type="search" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
                       placeholder="Search products..."
                       class="w-full border border-slate-300 rounded-lg pl-3 pr-10 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-emerald-600" aria-label="Search">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/></svg>
                </button>
            </div>
        </form>

        <nav class="flex items-center gap-5 text-sm">
            <a href="./" class="text-slate-600 hover:text-emerald-700">Home</a>
            <a href="category.php" class="text-slate-600 hover:text-emerald-700">Shop</a>

            <div class="relative">
                <button type="button" id="categoryToggle" class="text-slate-600 hover:text-emerald-700 focus:outline-none">
                    Categories
                </button>
                <div id="categoryDropdown" class="absolute left-0 mt-2 w-48 bg-white shadow-lg rounded-lg border border-slate-100 hidden z-10">
                    <ul class="py-2 max-h-64 overflow-y-auto">
                        <?php if (empty($categories)): ?>
                            <li class="px-4 py-2 text-slate-400 text-sm">No categories</li>
                        <?php else: ?>
                            <?php foreach ($categories as $catg): ?>
                                <li>
                                    <a href="category.php?category=<?= urlencode($catg['category_name']) ?>"
                                       class="block px-4 py-2 text-slate-700 hover:bg-slate-50">
                                        <?= htmlspecialchars($catg['category_name']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <a href="cart.php" class="relative text-slate-600 hover:text-emerald-700 inline-flex items-center gap-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9h14l-2-9M10 21a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z"/></svg>
                Cart
                <span id="cartCount" class="ml-0.5 bg-emerald-600 text-white text-xs rounded-full min-w-[1.25rem] h-5 px-1 inline-flex items-center justify-center"><?= $cartCount ?></span>
            </a>

            <?php if ($isLoggedIn): ?>
                <?php if ($userRole === 'Merchant'): ?>
                    <a href="merchant/" class="text-slate-600 hover:text-emerald-700">Dashboard</a>
                <?php else: ?>
                    <a href="my_orders.php" class="text-slate-600 hover:text-emerald-700">My orders</a>
                <?php endif; ?>
                <div class="relative group">
                    <button type="button" class="text-slate-700 font-medium focus:outline-none">
                        <?= htmlspecialchars(trim($userName)) ?>
                    </button>
                    <div class="absolute right-0 mt-2 w-40 bg-white shadow-lg rounded-lg border border-slate-100 hidden group-hover:block z-10">
                        <a href="logout.php" class="block px-4 py-2 text-slate-700 hover:bg-slate-50">Sign out</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="login.php" class="text-slate-600 hover:text-emerald-700">Login</a>
                <a href="register.php" class="bg-emerald-600 text-white px-3 py-1.5 rounded-lg hover:bg-emerald-700">Sign up</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('categoryToggle');
    const dropdown = document.getElementById('categoryDropdown');
    if (!toggleBtn || !dropdown) return;
    toggleBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdown.classList.toggle('hidden');
    });
    document.addEventListener('click', (e) => {
        if (!dropdown.contains(e.target) && !toggleBtn.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
});
</script>
