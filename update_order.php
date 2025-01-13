<?php
// delete_product.php

// Include database connection
include 'connection.php';

// Get product ID from the query string
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Delete the product from the database
$query = "DELETE FROM products WHERE product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $product_id);

if ($stmt->execute()) {
    echo "Product deleted successfully.";
} else {
    echo "Error deleting product: " . $conn->error;
}

$stmt->close();
$conn->close();

// Redirect to the product list or another page
header('Location: product_list.php');
exit();
?>
