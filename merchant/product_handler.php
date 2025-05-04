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
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            $productDetails = $product->getProductById($id);
            $images = [];
            foreach ($productImage->getProductImagesByProductId($id) as $index => $image) {
                $images[$index] = [
                    "image_id" => $image['image_id'],
                    "file_name" => "../uploads/" . $image['file_name'],
                    "is_primary" => $image['is_primary']
                ];
            }

            $productDetails['images'] = $images;
            echo json_encode($productDetails);
        } else {
            echo json_encode($product->getAllProductByUserId($_SESSION['user_id']));
        }

    } elseif ($method === 'POST') {
        $action = $_POST['action'] ?? null;

        $name = $_POST['productName'] ?? '';
        $description = $_POST['productDescription'] ?? '';
        $categoryName = $_POST['categoryName'] ?? '';
        $stockQuantity = $_POST['stockQuantity'] ?? 0;
        $price = $_POST['productPrice'] ?? 0;
        $uploadedFiles = $_FILES['productImages'] ?? null;
        $primaryImg = $_POST['primaryImg'] ?? null;

        if (!$action) {
            $productId = $product->addProduct($name, $description, $price, $_SESSION['user_id'], $stockQuantity, $categoryName);

            if ($productId > 0) {
                $fileUpload->uploadImages($uploadedFiles, $productId, $primaryImg);
                echo "Product created successfully";
            } else {
                echo "Product creation failed.";
            }
        } else {
            $productId = $_POST['productId'] ?? null;
            $deletedImages = json_decode($_POST['removedImgs'] ?? '[]', true);

            if ($productId) {
                $fileUpload->uploadImages($uploadedFiles, $productId, $primaryImg);

                foreach ($deletedImages as $deletedImage) {
                    $image = $productImage->getProductImageById($deletedImage);
                    $filePath = "../uploads/" . $image['file_name'];
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
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
        $productId = $_GET['productId'] ?? null;

        if ($productId) {
            foreach ($productImage->getProductImagesByProductId($productId) as $image) {
                $filePath = "../uploads/" . $image['file_name'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            if ($product->deleteProduct($productId)) {
                echo "Product deleted successfully.";
            } else {
                echo "Product deletion failed.";
            }
        } else {
            echo "Product ID missing for deletion.";
        }
    }
} else {
    header("Location: ../login.php");
    exit;
}
