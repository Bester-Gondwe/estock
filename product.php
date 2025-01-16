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

<body>
	<div class="wrapper">
		<?php include 'navbar.php'; ?>


		<div class="container">

			<!-- Main content -->
			<section class="product-details">
				<div class="product-details-wrapper">
					<div class="product-images">
						<div class="product-images__secondary-images">
							<?php foreach ($productImages as $productImg) { ?>
								<div class="product-images__secondary-img-wrapper">
									<img class="product-images__secondary-img" src="uploads/<?php echo $productImg['file_name'] ?>" alt="">
								</div>
							<?php } ?>
						</div>
						<div class="product-images__primary-img-container">
							<div class="product-images__primary-img-wrapper">
								<img id="productPrimaryImg" class="product-images__primary-img" src="uploads/<?php echo $product['primary_image']  ?>" alt="">
							</div>
							<button class="product-card__btn btn-100" onclick="addToCart(<?php echo $product['product_id'] ?>)">Add to Cart</button>
						</div>

					</div>
					<div class="product-details_info">
						<h1 class="products-details__product-name"><?php echo $product['product_name'] ?></h1>
						<h3 class="products-details__product-price">MWK <?php echo $product['product_price'] ?></h3>
						<p class="products-details__product_category">Category: <a href="category.php?category=<?php echo $product['category_name'] ?>"><?php echo $product['category_name'] ?></a></p>
						<p class="products-details__product-description"><?php echo $product['product_description'] ?></p>
					</div>
				</div>

			</section>
		</div>
	</div>
	<script src="js/main.js"></script>
	<script>
		const productImages = document.querySelectorAll('.product-images__secondary-img');
		productImages.forEach((productImage, _) => {
			productImage.addEventListener('click', function() {
				document.querySelector('#productPrimaryImg').src = productImage.src;

			})
		})

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
</body>

</html>