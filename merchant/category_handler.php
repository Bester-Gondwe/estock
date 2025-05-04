<?php
session_start();

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

require_once "../models/Category.php";
$category = new Category();
$userId = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $categoryData = $category->getCategoryById($id);
            echo json_encode($categoryData);
        } else {
            // Return all categories for logged-in merchant
            $categories = $category->countMerchantCategories($userId);
            echo json_encode($categories);
        }
        break;

    case 'POST':
        $categoryName = $_POST['categoryName'] ?? null;
        $categoryId = $_POST['categoryId'] ?? null;

        if (!$categoryName) {
            echo json_encode(["error" => "Category name is required"]);
            exit;
        }

        if ($categoryId) {
            // Update category if categoryId exists
            if ($category->updateCategory($categoryId, $categoryName)) {
                echo json_encode(["message" => "Category updated successfully"]);
            } else {
                echo json_encode(["error" => "Category update failed"]);
            }
        } else {
            // Create new category if no categoryId exists
            if ($category->addCategory($categoryName)) {
                echo json_encode(["message" => "Category created successfully"]);
            } else {
                echo json_encode(["error" => "Category creation failed"]);
            }
        }
        break;

    case 'DELETE':
        // Parse raw URL data manually
        parse_str(file_get_contents("php://input"), $deleteData);
        $categoryID = $deleteData['categoryID'] ?? $_GET['categoryID'] ?? null;

        if ($categoryID && $category->deleteCategory($categoryID)) {
            echo json_encode(["message" => "Category deleted successfully"]);
        } else {
            echo json_encode(["error" => "Category deletion failed"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
        break;
}
