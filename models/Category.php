<?php
require_once __DIR__ . '/../connection.php';

class Category extends Database
{
    private $category_table = "categories";

    public function __construct()
    {
        parent::__construct();
    }

    // Get category by ID
    public function getCategoryById($categoryId)
    {
        $query = "SELECT * FROM $this->category_table WHERE category_id = :category_id";
        $stmt = $this->executeQuery($query, ['category_id' => $categoryId]);

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return null; // Return null if no category found
        }
    }

    // Add a new category
    public function addCategory($categoryName)
    {
        if (empty($categoryName)) {
            return ["error" => "Category name is required"];
        }

        $query = "INSERT INTO $this->category_table (category_name) VALUES (:category_name)";
        $params = ['category_name' => $categoryName];
        $stmt = $this->executeQuery($query, $params);

        if ($stmt->rowCount() > 0) {
            return ["message" => "Category created successfully"];
        } else {
            return ["error" => "Category creation failed"];
        }
    }

    // Get category by name
    public function getCategoryByName($categoryName)
    {
        $query = "SELECT * FROM $this->category_table WHERE category_name = :category_name";
        $stmt = $this->executeQuery($query, ['category_name' => $categoryName]);

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return null; // Return null if no category found
        }
    }

    // Get all categories
    public function getAllCategories()
    {
        $query = "SELECT * FROM $this->category_table ORDER BY category_name ASC";
        $stmt = $this->executeQuery($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Count categories for a merchant
    public function countMerchantCategories($userID)
    {
        $query = "
            SELECT 
                c.category_id, 
                c.category_name, 
                COUNT(p.product_id) AS numberOfProducts
            FROM $this->category_table c
            LEFT JOIN products p 
                ON p.category_id = c.category_id AND p.user_id = :user_id
            GROUP BY c.category_id, c.category_name
            ORDER BY c.category_name ASC
        ";
        $stmt = $this->executeQuery($query, ['user_id' => $userID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get paginated categories
 // Get paginated categories
public function getPaginatedCategories($offset, $limit)
{
    $query = "SELECT * FROM $this->category_table ORDER BY category_name ASC LIMIT :limit OFFSET :offset";
    $stmt = $this->prepare($query);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    // Count total categories
    public function countCategories()
    {
        $query = "SELECT COUNT(*) FROM $this->category_table";
        $stmt = $this->executeQuery($query);
        return $stmt->fetchColumn();
    }

    // Delete category by ID
    public function deleteCategory($categoryId)
    {
        $query = "DELETE FROM $this->category_table WHERE category_id = :category_id";
        $stmt = $this->executeQuery($query, ['category_id' => $categoryId]);

        if ($stmt->rowCount() > 0) {
            return ["message" => "Category deleted successfully"];
        } else {
            return ["error" => "Category deletion failed"];
        }
    }

    // Update category by ID
    public function updateCategory($categoryId, $categoryName)
    {
        if (empty($categoryName)) {
            return ["error" => "Category name is required"];
        }

        $query = "UPDATE $this->category_table 
                  SET category_name = :category_name 
                  WHERE category_id = :category_id";
        $stmt = $this->executeQuery($query, [
            'category_name' => $categoryName,
            'category_id' => $categoryId
        ]);

        if ($stmt->rowCount() > 0) {
            return ["message" => "Category updated successfully"];
        } else {
            return ["error" => "Category update failed"];
        }
    }
}
