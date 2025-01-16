<?php
require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../models/Category.php';


class Product extends Database
{
    private $products_table = "products";


    public function __construct()
    {
        parent::__construct();
    }

    // add product
    public function addProduct($product_name, $product_description, $product_price, $user_id, $quantity, $categoryName)
    {
        $category = new Category();
        $ctgry =  $category->getCategoryByName($categoryName);
        if ($ctgry) {
            $query = "INSERT INTO $this->products_table (product_name,product_description,product_price,user_id,quantity,category_id,created_at) VALUES (:product_name,:product_description,:product_price,:user_id,:quantity,:category_id,NOW())";
            $params = ['product_name' => $product_name, 'product_description' => $product_description, 'product_price' => $product_price, 'user_id' => $user_id, 'quantity' => $quantity, 'category_id' => $ctgry['category_id']];
            if ($this->executeQuery($query, $params))
                return  $this->conn->lastInsertId();
            return false;
        }
        return false;
    }

    // update product
    public function updateProduct($product_id, $product_name, $product_description, $product_price, $quantity, $categoryName)
    {
        $category = new Category();
        $ctgry =  $category->getCategoryByName($categoryName);
        if ($ctgry) {
            $query = "UPDATE $this->products_table SET product_name=:product_name,product_description=:product_description,product_price=:product_price,quantity=:quantity,category_id=:category_id,created_at=NOW() WHERE product_id=:product_id";
            $params = ['product_name' => $product_name, 'product_description' => $product_description, 'product_price' => $product_price, 'quantity' => $quantity, 'category_id' => $ctgry['category_id'], 'product_id' => $product_id];
            return   $this->executeQuery($query, $params);
        }
        return false;
    }

    // get product by id
    public function getProductById($product_id)
    {
        $query = "SELECT  products.product_id,
        products.product_name, 
        products.product_price, 
        products.product_description,
        categories.category_name,
        product_images.file_name AS primary_image 
        FROM products JOIN categories ON $this->products_table.category_id=categories.category_id
        LEFT JOIN product_images ON products.product_id = product_images.product_id 
        AND product_images.is_primary=1 WHERE products.product_id=:product_id";
        $params = ['product_id' => $product_id];
        $stmt = $this->executeQuery($query, $params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // delete product by id
    public function deleteProduct($product_id)
    {
        $query = "DELETE FROM $this->products_table WHERE product_id=:product_id";
        return $this->executeQuery($query, ['product_id' => $product_id]);
    }


    // get all products
    public function getAllProducts()
    {

        $query = "SELECT 
        products.product_id,
        products.product_name, 
        products.product_price, 
        products.product_description,
        categories.category_name,
        product_images.file_name AS primary_image 
        FROM products JOIN categories ON products.category_id = categories.category_id
        LEFT JOIN product_images ON products.product_id = product_images.product_id 
        AND product_images.is_primary=1 ORDER BY products.created_at DESC LIMIT 10";

        $stmt = $this->executeQuery($query, null);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // get all products placed the the merchant
    public function getAllProductByUserId($user_id)
    {
        $query = "SELECT 
        products.product_id,
        products.product_name, 
        products.product_price, 
        products.product_description,
        categories.category_name,
        product_images.file_name AS primary_image 
        FROM products JOIN users ON products.user_id = users.user_id
        JOIN categories ON products.category_id = categories.category_id
        LEFT JOIN product_images ON products.product_id = product_images.product_id 
        AND product_images.is_primary=1
        WHERE users.user_id = :user_id ORDER BY products.created_at DESC";

        $params = ['user_id' => $user_id];
        $stmt = $this->executeQuery($query, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductsGroupedByUser()
    {
        $query = "SELECT user_id, COUNT(*) AS product_count 
              FROM {$this->products_table} 
              GROUP BY user_id";
        $stmt = $this->executeQuery($query, null);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductsByPriceRange($min_price, $max_price)
    {
        $query = "SELECT * FROM {$this->products_table} 
              WHERE product_price BETWEEN :min_price AND :max_price";
        $params = ['min_price' => $min_price, 'max_price' => $max_price];
        $stmt = $this->executeQuery($query, $params);
        return $stmt->fetchAll();
    }

    public function getRecentProducts($limit = 5)
    {
        $query = "SELECT * FROM {$this->products_table} 
              ORDER BY created_at DESC 
              LIMIT :limit";
        $stmt = $this->executeQuery($query, ['limit' => $limit]);
        return $stmt->fetchAll();
    }

    public function getProductsPaginated($offset, $limit)
    {
        $query = "SELECT * FROM {$this->products_table} 
              ORDER BY created_at ASC 
              LIMIT :offset, :limit";
        $stmt = $this->executeQuery($query, ['offset' => $offset, 'limit' => $limit]);
        return $stmt->fetchAll();
    }

    public function searchProducts($keyword)
    {
        $query = "SELECT * FROM {$this->products_table} 
              WHERE product_name LIKE :keyword 
                 OR product_description LIKE :keyword";
        $params = ['keyword' => '%' . $keyword . '%'];
        $stmt = $this->executeQuery($query, $params);
        return $stmt->fetchAll();
    }

    public function countAllProducts()
    {
        $query = "SELECT COUNT(*) AS total FROM {$this->products_table}";
        $stmt = $this->executeQuery($query, null);
        return $stmt->fetch()['total'];
    }

    public function getProductsByCategory($categoryName)
    {

        $query = "SELECT 
        products.product_id,
        products.product_name, 
        products.product_price, 
        products.product_description,
        categories.category_name,
        product_images.file_name AS primary_image 
        FROM products JOIN categories ON products.category_id = categories.category_id
        LEFT JOIN product_images ON products.product_id = product_images.product_id 
        AND product_images.is_primary=1
        WHERE categories.category_name =:category_name ORDER BY products.created_at DESC";

        $stmt = $this->executeQuery($query, ['category_name' => $categoryName]);
        return $stmt->fetchAll();
    }
}
