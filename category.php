<?php 


if (!isset($_SESSION['cart'])) {
	$_SESSION['cart'] = [];
}

require_once 'models/Product.php';

$filteredProducts = [];
$categorySlug = null;
$product = new Product();
if (isset($_GET['category'])) {
	$categorySlug = $_GET['category'];
	$filteredProducts = $product->getProductsByCategory($categorySlug);
} else {
	$filteredProducts = $product->getAllProducts();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Category</title>
	<?php include 'header.php' ?>
	<script src='https://cdn.tailwindcss.com'></script>
	<style>
		.toast {
			animation: slideIn 0.5s ease, fadeOut 0.5s ease 2.5s forwards;
		}

		@keyframes slideIn {
			from {
				transform: translateY(20px);
				opacity: 0;
			}
			to {
				transform: translateY(0);
				opacity: 1;
			}
		}

		@keyframes fadeOut {
			to {
				opacity: 0;
			}
		}
	</style>
</head>

<body class="bg-gray-50 text-gray-800 relative">
	<div class="min-h-screen flex flex-col">
		<?php include 'navbar.php' ?>

		<!-- Content Wrapper -->
		<main class="flex-grow container mx-auto px-4 py-8">
			<section class="mb-8">
				<h1 class="text-2xl font-bold border-b border-gray-200 pb-2 mb-6">
					<?= $categorySlug === null ? "All Products" : htmlspecialchars($categorySlug) ?>
				</h1>

				<!-- Products Grid -->
				<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
					<?php foreach ($filteredProducts as $filteredProduct): ?>
						<div class="bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
							<img 
								class="w-full h-48 object-cover rounded-t-lg"
								src="uploads/<?= htmlspecialchars($filteredProduct['primary_image']) ?>" 
								alt="<?= htmlspecialchars($filteredProduct['product_name']) ?>"
								onerror="this.onerror=null;this.src='assets/default-image.png';"
							>
							<div class="p-4 flex flex-col space-y-2">
								<a href="product.php?id=<?= $filteredProduct['product_id'] ?>" 
								   class="text-lg font-semibold text-blue-600 hover:underline truncate"
								   title="<?= htmlspecialchars($filteredProduct['product_name']) ?>">
									<?= htmlspecialchars($filteredProduct['product_name']) ?>
								</a>
								<span class="text-green-600 font-medium">K<?= $filteredProduct['product_price'] ?></span>
								<button onclick="addToCart(this, <?= $filteredProduct['product_id'] ?>)"
										class="bg-blue-600 text-white py-1.5 px-4 rounded hover:bg-blue-700 transition flex items-center justify-center gap-2">
									<span class="cart-btn-text">Add to Cart</span>
									<svg class="hidden animate-spin w-4 h-4 text-white cart-spinner" fill="none" viewBox="0 0 24 24">
										<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
										<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l4-4-4-4v4a12 12 0 00-8 8z"></path>
									</svg>
								</button>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</section>
		</main>
	</div>

	<!-- Toast -->
	<div id="toast" class="hidden fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50 toast">
		Item added to cart!
	</div>

	<script>
		function addToCart(button, productId) {
			const formData = new FormData();
			formData.append("productID", productId);

			const spinner = button.querySelector('.cart-spinner');
			const text = button.querySelector('.cart-btn-text');

			// Show spinner
			spinner.classList.remove("hidden");
			text.textContent = "Adding...";

			fetch("add_to_cart.php", {
				method: "POST",
				body: formData
			})
			.then(response => response.text())
			.then(data => {
				spinner.classList.add("hidden");
				text.textContent = "Add to Cart";

				if (data === "error") {
					alert("Failed to add product to cart.");
				} else {
					document.querySelector("#cartCount").innerHTML = data;
					showToast("Item added to cart!");
				}
			});
		}

		function showToast(message) {
			const toast = document.getElementById("toast");
			toast.textContent = message;
			toast.classList.remove("hidden");

			setTimeout(() => {
				toast.classList.add("hidden");
			}, 3000);
		}
	</script>

	<script src="js/main.js"></script>
</body>
</html>
