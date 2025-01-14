<?php
session_start();

if (isset($_SESSION['user_id'])) {
    require_once "../models/Product.php";
    require_once "../models/ProductImage.php";
    require_once "../models/FileUpload.php";
   

    $product = new Product();
    $productImage = new ProductImage();
    $fileUpload = new FileUpload();

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        // Fetch all or a single product
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            $product = $product->getProductById($id);
            $images = [];
            $imageCount = 0;
            foreach ($productImage->getProductImagesByProductId($id) as $image) {
                $images[$imageCount] = array("image_id" =>  $image_id = $image['image_id'], "file_name" =>  $file_name = "../uploads/" . $image['file_name'], 'is_primary' => $image['is_primary']);
                $imageCount++;
            }
            $product['images'] = $images;
            echo json_encode($product);
        } else {
            echo json_encode($product->getAllProductByUserId($_SESSION['user_id']));
        }
        
    } elseif ($method === 'POST') {
        $action = $_POST['action'] ?? null;

        $name = $_POST['productName'];
        $description = $_POST['productDescription'];
        $categoryName = $_POST['categoryName'];
        $stockQuantity = $_POST['stockQuantity'];
        $price = $_POST['productPrice'];
        $uploadedFiles = $_FILES['productImages'];

        $primaryImg = $_POST['primaryImg'];

        if ($action == null) {
            $productId = $product->addProduct($name, $description, $price, $_SESSION['user_id'], $stockQuantity, $categoryName);
            if ($productId > 0) {
                $response = $fileUpload->uploadImages($uploadedFiles, $productId, $primaryImg);
                echo "Product created successfully";
            } else {
                echo "Product created failed.";
            }
        } else {

            $productId = $_POST['productId'] ?? null;
            $deletedImages = json_decode($_POST['removedImgs'], true);

            if ($productId != null) {

                $response = $fileUpload->uploadImages($uploadedFiles, $productId, $primaryImg);

                foreach ($deletedImages as $deletedImage) {
                    $image = $productImage->getProductImageById($deletedImage);
                    unlink("../uploads/" . $image['file_name']);
                    $productImage->deleteProductImageById($deletedImage);
                }

                if ($product->updateProduct($productId, $name, $description, $price, $stockQuantity, $categoryName)) {
                    echo "Product updated successfully.";
                } else {
                    echo "Product update failed.";
                }
            } else {
                echo "No Product Id was provided for update";
            }
        }
    } elseif ($method === 'DELETE') {
        $productId = $_GET['productId'];

        foreach ($productImage->getProductImagesByProductId($productId) as $image) {
            unlink("../uploads/" . $image['file_name']);
        }

        if ($product->deleteProduct($productId)) {
            echo "Product deleted successfully.";
        } else {
            echo "Product deletion failed.";
        }
    }
} else {
    header("Location: ../login.php");
    exit;
}
