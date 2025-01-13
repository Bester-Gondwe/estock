<?php
// edit_product.php

// Include database connection
include 'connection.php';

// Get product ID from the query string
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get updated product details from the form submission
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];

    // Update the product in the database
    $query = "UPDATE products SET product_name = ?, product_price = ? WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sdi', $product_name, $product_price, $product_id);

    if ($stmt->execute()) {
        echo "Product updated successfully.";
    } else {
        echo "Error updating product: " . $conn->error;
    }

    $stmt->close();
    $conn->close();

    // Redirect to the product list or another page
    header('Location: product_list.php');
    exit();
}

// Fetch product details for editing
$query = "SELECT * FROM products WHERE product_id = ?";
$stmt = $sql->runquery($query);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "Product not found.";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
</head>
<body>
    <h1>Edit Product</h1>
    <form method="POST" action="">
        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
        <br>
        <label for="product_price">Product Price:</label>
        <input type="number" id="product_price" name="product_price" value="<?php echo htmlspecialchars($product['product_price']); ?>" required step="0.01">
        <br>
        <button type="submit">Update Product</button>
    </form>
</body>
</html>