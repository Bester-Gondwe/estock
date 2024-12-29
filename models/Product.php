<?php
require_once __DIR__ . '/../connection.php';


class Product extends Database
{
    private $products_table = "products";


    public function __construct()
    {
        parent::__construct();
    }

    // add product
    public function addProduct($product_name, $product_description, $product_price, $user_id)
    {
        $query = "INSERT INTO $this->products_table (product_name,product_description,product_price,user_id,created_at) VALUES (:product_name,:product_description,:product_price,:user_id,NOW())";
        $params = ['product_name' => $product_name, 'product_description' => $product_description, 'product_price' => $product_price, 'user_id' => $user_id];
        return  $this->executeQuery($query, $params);
    }

    // get product by id
    public function getProductById($product_id)
    {
        $query = "SELECT * FROM $this->products_table WHERE product_id=:product_id";
        $params = ['product_id' => $product_id];
        $stmt = $this->executeQuery($query, $params);
        return $stmt->fetch();
    }

    // delete product by id
    public function deleteProduct($product_id)
    {
        $query = "DELETE FROM $this->products_table WHERE product_id=:product_id";
        $params = ['product_id' => $product_id];
        $this->executeQuery($query, $params);
    }


    // get all products
    public function getAllProducts()
    {
        $query = "SELECT * FROM $this->products_table ORDER BY created_at ASC";
        $stmt = $this->executeQuery($query, null);
        return $stmt->fetchAll();
    }


    // get all products placed the the merchant
    public function getAllProductByUserId($user_id)
    {
        $query = "SELECT * FROM $this->products_table WHERE user_id=:user_id";
        $params = ['user_id' => $user_id];
        $stmt = $this->executeQuery($query, $params);
        return $stmt->fetchAll();
    }

    public function getProductsGroupedByUser()
    {
        $query = "SELECT user_id, COUNT(*) AS product_count 
              FROM {$this->products_table} 
              GROUP BY user_id";
        $stmt = $this->executeQuery($query, null);
        return $stmt->fetchAll();
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
}
