<?php
require_once __DIR__ . '/../config/bootstrap.php';
require_merchant();

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/ProductImage.php';
require_once __DIR__ . '/../models/FileUpload.php';

$product = new Product();
$productImage = new ProductImage();
$fileUpload = new FileUpload();
$method = $_SERVER['REQUEST_METHOD'];
$userId = $_SESSION['user_id'];

if ($method === 'GET') {
    header('Content-Type: application/json');
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];
        if (!$product->ownsProduct($id, $userId)) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }
        $productDetails = $product->getProductById($id);
        $images = [];
        foreach ($productImage->getProductImagesByProductId($id) as $index => $image) {
            $images[$index] = [
                'image_id' => $image['image_id'],
                'file_name' => '../uploads/' . $image['file_name'],
                'is_primary' => $image['is_primary'],
            ];
        }
        $productDetails['images'] = $images;
        echo json_encode($productDetails);
    } else {
        echo json_encode($product->getAllProductByUserId($userId));
    }
    exit;
}

if ($method === 'POST') {
    $action = $_POST['action'] ?? null;
    $name = trim($_POST['productName'] ?? '');
    $description = trim($_POST['productDescription'] ?? '');
    $categoryName = trim($_POST['categoryName'] ?? '');
    $stockQuantity = (int) ($_POST['stockQuantity'] ?? 0);
    $price = (float) ($_POST['productPrice'] ?? 0);
    $sku = trim($_POST['sku'] ?? '') ?: null;
    $lowStock = (int) ($_POST['lowStockThreshold'] ?? 5);
    $uploadedFiles = $_FILES['productImages'] ?? null;
    $primaryImg = $_POST['primaryImg'] ?? null;

    if ($name === '' || $categoryName === '') {
        echo 'Product name and category are required.';
        exit;
    }

    if (!$action) {
        $productId = $product->addProduct($name, $description, $price, $userId, $stockQuantity, $categoryName, $sku, $lowStock);
        if ($productId > 0) {
            if ($uploadedFiles) {
                $fileUpload->uploadImages($uploadedFiles, $productId, $primaryImg);
            }
            echo 'Product created successfully';
        } else {
            echo 'Product creation failed.';
        }
        exit;
    }

    $productId = (int) ($_POST['productId'] ?? 0);
    if (!$productId || !$product->ownsProduct($productId, $userId)) {
        echo 'You cannot update this product.';
        exit;
    }

    $deletedImages = json_decode($_POST['removedImgs'] ?? '[]', true) ?: [];
    if ($uploadedFiles) {
        $fileUpload->uploadImages($uploadedFiles, $productId, $primaryImg);
    }

    foreach ($deletedImages as $deletedImage) {
        $image = $productImage->getProductImageById($deletedImage);
        if ($image) {
            $filePath = __DIR__ . '/../uploads/' . $image['file_name'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $productImage->deleteProductImageById($deletedImage);
        }
    }

    if ($product->updateProduct($productId, $name, $description, $price, $stockQuantity, $categoryName, $sku, $lowStock)) {
        echo 'Product updated successfully.';
    } else {
        echo 'Product update failed.';
    }
    exit;
}

if ($method === 'DELETE') {
    $productId = (int) ($_GET['productId'] ?? 0);
    if (!$productId || !$product->ownsProduct($productId, $userId)) {
        echo 'You cannot delete this product.';
        exit;
    }

    foreach ($productImage->getProductImagesByProductId($productId) as $image) {
        $filePath = __DIR__ . '/../uploads/' . $image['file_name'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    if ($product->deleteProduct($productId, $userId)) {
        echo 'Product deleted successfully.';
    } else {
        echo 'Product deletion failed.';
    }
    exit;
}

http_response_code(405);
echo 'Method not allowed';
