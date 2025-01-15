<?php
session_start();

if (!isset($_SESSION['cart'])) {
	$_SESSION['cart'] = [];
}

require_once 'models/Product.php';

$categorySlug = $_GET['category'] ?? 'Phones';
$product = new Product();

$filteredProducts = $product->getProductsByCategory($categorySlug);

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<?php include 'header.php' ?>
</head>

<body>
	<div class="wrapper">
		<?php include 'navbar.php' ?>

		<div class="content-wrapper">
			<div class="container">
				<!-- Main content -->
				<section class="products">
					<h1 class="page-header"><?php echo $categorySlug ?></h1>
					<div class="products-container">
						<?php foreach ($filteredProducts as $filteredProduct): ?>
							<div class="product-card">
								<img class="product-card__img" src="uploads/<?php echo $filteredProduct['primary_image'] ?> " alt="<?php echo $filteredProduct['product_name'] ?>">
								<div class="product-info">
									<h2 class="product-card__product-name"><?php echo $filteredProduct['product_name'] ?></h2>
									<span class="product-card__product-price">K<?php echo $filteredProduct['product_price'] ?></span>
									<button class="product-card__btn" onclick="addToCart(<?php echo $filteredProduct['product_id'] ?>)">Add to Cart</button>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</section>
			</div>
		</div>
	</div>

	<script>
		function addToCart(productId) {

			const formData = new FormData();
			formData.append("productID", productId);
			fetch("add_to_cart.php", {
					method: "POST",
					body: formData
				}).then(response => response.text())
				.then(data => {
					if (data === "error") {
						alert("Failed to add product to cart.");
					} else {
						document.querySelector("#cartCount").innerHTML = data; // update cart
					}
				})
		}
	</script>
	<script src="js/main.js"></script>
</body>

</html>