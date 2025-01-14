<?php
session_start();

if (isset($_SESSION['user_id'])) {
    require_once "../models/Category.php";

    $category = new Category();

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {

        // Fetch all or a single product
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            // echo json_encode();
            echo "getting category by id";
        } else {
            echo json_encode($category->getAllCategories($_SESSION['user_id']));
        }
    } elseif ($method === 'POST') {
        $action = $_POST['action'] ?? null;
        $categoryName = $_POST['categoryName'];
        $categoryId = $_POST['categoryId'];

        if ($action == null) {
            if ($category->addCategory($categoryName)) {
                echo "Category created successfully";
            } else {
                echo "Category creation failed.";
            }
        } else {
            if ($categoryId != null && $categoryName != null) {
                if ($category->updateCategory($categoryId, $categoryName)) {
                    echo "Category updated successfully";
                } else {
                    echo "Category update failed.";
                }
            } else {
                echo "No Category Id was provided for update";
            }
        }
    } elseif ($method === 'DELETE') {
        $categoryID = $_GET['categoryID'];
        if ($category->deleteCategory($categoryID)) {
            echo "Category deleted successfully.";
        } else {
            echo "Category deletion failed.";
        }
    }
} else {
    header("Location: ../login.php");
    exit;
}
