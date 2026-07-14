<?php
require_once __DIR__ . '/../connection.php';

class Category extends Database
{
    private string $category_table = 'categories';

    public function __construct()
    {
        parent::__construct();
    }

    public function getCategoryById($categoryId)
    {
        $query = "SELECT * FROM {$this->category_table} WHERE category_id = :category_id";
        $stmt = $this->executeQuery($query, ['category_id' => $categoryId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function addCategory($categoryName)
    {
        $categoryName = trim((string) $categoryName);
        if ($categoryName === '') {
            return ['error' => 'Category name is required'];
        }

        if ($this->getCategoryByName($categoryName)) {
            return ['error' => 'Category already exists'];
        }

        try {
            $query = "INSERT INTO {$this->category_table} (category_name) VALUES (:category_name)";
            $stmt = $this->executeQuery($query, ['category_name' => $categoryName]);
            if ($stmt->rowCount() > 0) {
                return [
                    'message' => 'Category created successfully',
                    'category_id' => (int) $this->conn->lastInsertId(),
                ];
            }
            return ['error' => 'Category creation failed'];
        } catch (PDOException $e) {
            return ['error' => 'Category creation failed'];
        }
    }

    public function getCategoryByName($categoryName)
    {
        $query = "SELECT * FROM {$this->category_table} WHERE category_name = :category_name";
        $stmt = $this->executeQuery($query, ['category_name' => $categoryName]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getAllCategories()
    {
        $query = "SELECT * FROM {$this->category_table} ORDER BY category_name ASC";
        $stmt = $this->executeQuery($query, []);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countMerchantCategories($userID)
    {
        $query = "SELECT
                c.category_id,
                c.category_name,
                COUNT(p.product_id) AS numberOfProducts
            FROM {$this->category_table} c
            LEFT JOIN products p
                ON p.category_id = c.category_id AND p.user_id = :user_id
            GROUP BY c.category_id, c.category_name
            ORDER BY c.category_name ASC";
        $stmt = $this->executeQuery($query, ['user_id' => $userID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaginatedCategories($offset, $limit)
    {
        $offset = (int) $offset;
        $limit = (int) $limit;
        $query = "SELECT * FROM {$this->category_table} ORDER BY category_name ASC LIMIT {$offset}, {$limit}";
        $stmt = $this->executeQuery($query, []);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countCategories()
    {
        $query = "SELECT COUNT(*) FROM {$this->category_table}";
        $stmt = $this->executeQuery($query, []);
        return (int) $stmt->fetchColumn();
    }

    public function deleteCategory($categoryId)
    {
        $categoryId = (int) $categoryId;
        if ($categoryId < 1) {
            return ['error' => 'Invalid category'];
        }

        // Prevent delete when products still use the category
        $check = $this->executeQuery(
            'SELECT COUNT(*) FROM products WHERE category_id = :category_id',
            ['category_id' => $categoryId]
        );
        if ((int) $check->fetchColumn() > 0) {
            return ['error' => 'Cannot delete category while products are assigned to it'];
        }

        try {
            $query = "DELETE FROM {$this->category_table} WHERE category_id = :category_id";
            $stmt = $this->executeQuery($query, ['category_id' => $categoryId]);
            if ($stmt->rowCount() > 0) {
                return ['message' => 'Category deleted successfully'];
            }
            return ['error' => 'Category not found'];
        } catch (PDOException $e) {
            return ['error' => 'Category deletion failed'];
        }
    }

    public function updateCategory($categoryId, $categoryName)
    {
        $categoryId = (int) $categoryId;
        $categoryName = trim((string) $categoryName);

        if ($categoryId < 1) {
            return ['error' => 'Invalid category'];
        }
        if ($categoryName === '') {
            return ['error' => 'Category name is required'];
        }

        $existing = $this->getCategoryByName($categoryName);
        if ($existing && (int) $existing['category_id'] !== $categoryId) {
            return ['error' => 'Another category already uses this name'];
        }

        try {
            $query = "UPDATE {$this->category_table}
                      SET category_name = :category_name
                      WHERE category_id = :category_id";
            $this->executeQuery($query, [
                'category_name' => $categoryName,
                'category_id' => $categoryId,
            ]);
            return ['message' => 'Category updated successfully'];
        } catch (PDOException $e) {
            return ['error' => 'Category update failed'];
        }
    }
}
