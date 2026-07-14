<?php
require_once __DIR__ . '/../config/bootstrap.php';
require_merchant();

header('Content-Type: application/json');

require_once __DIR__ . '/../models/Category.php';
$category = new Category();
$userId = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = (int) $_GET['id'];
            echo json_encode($category->getCategoryById($id));
        } else {
            echo json_encode($category->countMerchantCategories($userId));
        }
        break;

    case 'POST':
        $categoryName = trim($_POST['categoryName'] ?? '');
        $categoryId = $_POST['categoryId'] ?? null;

        if ($categoryName === '') {
            echo json_encode(['error' => 'Category name is required']);
            exit;
        }

        if ($categoryId) {
            $result = $category->updateCategory($categoryId, $categoryName);
            echo json_encode(isset($result['message'])
                ? ['message' => $result['message']]
                : ['error' => $result['error'] ?? 'Category update failed']);
        } else {
            $result = $category->addCategory($categoryName);
            echo json_encode(isset($result['message'])
                ? ['message' => $result['message']]
                : ['error' => $result['error'] ?? 'Category creation failed']);
        }
        break;

    case 'DELETE':
        parse_str(file_get_contents('php://input'), $deleteData);
        $categoryID = $deleteData['categoryID'] ?? $_GET['categoryID'] ?? null;

        if (!$categoryID) {
            echo json_encode(['error' => 'Category ID required']);
            exit;
        }

        $result = $category->deleteCategory($categoryID);
        if (isset($result['message'])) {
            echo json_encode(['message' => $result['message']]);
        } else {
            echo json_encode(['error' => $result['error'] ?? 'Category deletion failed']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
