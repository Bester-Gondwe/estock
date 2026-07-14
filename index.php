<!DOCTYPE html>
<html lang="en">
<head>
  <?php
  require_once __DIR__ . '/config/bootstrap.php';
  require_once __DIR__ . '/models/Product.php';
  $featured = (new Product())->getRecentProducts(8);
  include 'header.php';
  ?>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800">
  <?php include 'navbar.php'; ?>

  <section class="relative overflow-hidden bg-gradient-to-br from-emerald-800 via-emerald-700 to-teal-600 text-white">
    <div class="absolute inset-0 opacity-20" style="background-image:radial-gradient(circle at 20% 20%, #fff 0, transparent 40%), radial-gradient(circle at 80% 0%, #a7f3d0 0, transparent 35%);"></div>
    <div class="relative max-w-6xl mx-auto px-4 py-24 md:py-32">
      <p class="text-emerald-200 text-sm font-semibold tracking-widest uppercase mb-3">Online stock marketplace</p>
      <h1 class="text-4xl md:text-5xl font-bold max-w-2xl leading-tight mb-4">eStock</h1>
      <p class="text-lg text-emerald-50 max-w-xl mb-8">Buy and sell inventory with live stock control, secure checkout, and a merchant dashboard built for growing businesses.</p>
      <div class="flex flex-wrap gap-3">
        <a href="category.php" class="inline-flex items-center bg-white text-emerald-800 font-semibold px-6 py-3 rounded-lg hover:bg-emerald-50 transition">Shop products</a>
        <a href="register.php" class="inline-flex items-center border border-white/40 text-white font-medium px-6 py-3 rounded-lg hover:bg-white/10 transition">Sell on eStock</a>
      </div>
    </div>
  </section>

  <section class="max-w-6xl mx-auto px-4 py-14">
    <div class="flex items-end justify-between mb-8 gap-4">
      <div>
        <h2 class="text-2xl font-bold">Featured products</h2>
        <p class="text-slate-500 text-sm mt-1">Fresh stock from merchants on the platform</p>
      </div>
      <a href="category.php" class="text-emerald-700 font-medium hover:underline text-sm">View all</a>
    </div>

    <?php if (empty($featured)): ?>
      <p class="text-slate-500 text-center py-12">No products yet. Merchants can add inventory from the dashboard.</p>
    <?php else: ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php foreach ($featured as $item): ?>
          <a href="product.php?id=<?= (int) $item['product_id'] ?>" class="group bg-white border border-slate-200 rounded-xl overflow-hidden hover:shadow-md transition">
            <div class="aspect-[4/3] bg-slate-100 overflow-hidden">
              <img
                src="uploads/<?= htmlspecialchars($item['primary_image'] ?? '') ?>"
                alt="<?= htmlspecialchars($item['product_name']) ?>"
                class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                onerror="this.onerror=null;this.src='assets/default-image.svg';"
              >
            </div>
            <div class="p-4">
              <p class="text-xs text-slate-400 mb-1"><?= htmlspecialchars($item['category_name']) ?></p>
              <h3 class="font-semibold truncate group-hover:text-emerald-700"><?= htmlspecialchars($item['product_name']) ?></h3>
              <div class="flex items-center justify-between mt-2">
                <span class="text-emerald-700 font-semibold"><?= htmlspecialchars(format_money($item['product_price'])) ?></span>
                <?php if ((int) $item['quantity'] > 0): ?>
                  <span class="text-xs text-slate-500"><?= (int) $item['quantity'] ?> in stock</span>
                <?php else: ?>
                  <span class="text-xs text-red-500">Out of stock</span>
                <?php endif; ?>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>

  <section class="bg-white border-y border-slate-200">
    <div class="max-w-6xl mx-auto px-4 py-14 grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
      <div>
        <h3 class="font-semibold text-lg mb-2">Live inventory</h3>
        <p class="text-slate-500 text-sm">Stock levels update automatically when customers place orders.</p>
      </div>
      <div>
        <h3 class="font-semibold text-lg mb-2">Merchant tools</h3>
        <p class="text-slate-500 text-sm">Manage products, categories, and order fulfilment from one dashboard.</p>
      </div>
      <div>
        <h3 class="font-semibold text-lg mb-2">Secure checkout</h3>
        <p class="text-slate-500 text-sm">Customers check out with shipping details and clear order tracking.</p>
      </div>
    </div>
  </section>

  <footer class="bg-slate-900 text-slate-400 text-sm py-8">
    <div class="max-w-6xl mx-auto px-4 flex flex-col sm:flex-row justify-between gap-4">
      <p>&copy; <?= date('Y') ?> eStock. Online stock marketplace.</p>
      <div class="flex gap-4">
        <a href="category.php" class="hover:text-white">Shop</a>
        <a href="login.php" class="hover:text-white">Login</a>
        <a href="register.php" class="hover:text-white">Register</a>
      </div>
    </div>
  </footer>
</body>
</html>
