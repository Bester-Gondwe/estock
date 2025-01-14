<?php require_once __DIR__ . '/../connection.php';
class Category extends Database
{
    private $category_table = "categories";

    public function __construct()
    {
        parent::__construct();
    }

    public function getCategoryId($categoryId)
    {
        $query = "SELECT * FROM $this->category_table WHERE category_id=:category_id";
        $stmt = $this->executeQuery($query, ['category_id' => $categoryId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addCategory($categoryName)
    {
        $query = "INSERT INTO $this->category_table (category_name) VALUES (:category_name)";
        $params = ['category_name' => $categoryName];
        return $this->executeQuery($query, $params);
    }

    public function getCategoryByName($categoryName)
    {
        $query = "SELECT * FROM $this->category_table WHERE category_name=:category_name";
        $stmt = $this->executeQuery($query, ['category_name' => $categoryName]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllCategories($userID)
    {
        // $query = "SELECT * FROM $this->category_table";
        $query = "SELECT categories.category_id, categories.category_name, COUNT(products.product_id) as numberOfProducts
        FROM categories
LEFT JOIN products ON products.category_id = categories.category_id AND products.user_id=:user_id
GROUP BY categories.category_id";
        $stmt = $this->executeQuery($query, ['user_id' =>$userID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaginatedCategories($offset, $limit)
    {
        $query = "SELECT * FROM $this->category_table LIMIT $offset,$limit";
        $stmt = $this->executeQuery($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function countCotegories()
    {
        $query = "SELECT COUNT(*) FROM categories";
        $stmt =  $this->executeQuery($query);
        return $stmt->fetchColumn();
    }
    public function deleteCategory($categoryId)
    {
        $query = "DELETE FROM $this->category_table WHERE category_id=:category_id";
        return $this->executeQuery($query, ['category_id' => $categoryId]);
    }

    public function updateCategory($categoryId, $categoryName)
    {
        $query = "UPDATE  $this->category_table SET category_name=:category_name WHERE category_id=:category_id";
        return $this->executeQuery($query, ['category_name' => $categoryName, 'category_id' => $categoryId]);
    }
}
