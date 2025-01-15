<?php
require_once 'models/Product.php';

if (isset($_GET['category'])) {
	$categorySlug = $_GET['category'];
	// $product = new Product();
	// $filteredProducts = $product->getProductsByCategory($categorySlug);
} else {
	header('Location: /');
	exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>

<body>
	<div class="wrapper">
		<div class="content-wrapper">
			<div class="container">
				<!-- Main content -->
				<section class="content">
					<div class="row">
						<div class="col-sm-9">
							<h1 class="page-header"><?php echo 'ct name' ?></h1>

						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
</body>

</html>