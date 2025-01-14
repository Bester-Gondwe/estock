<?php
require_once __DIR__ . '/../connection.php';
class ProductImage extends Database
{

    public function __construct()
    {
        parent::__construct();
    }

    public function addProductImage($product_id, $filename, $isPrimary)
    {
        $query = "INSERT INTO product_images (file_name,product_id,is_primary) VALUES (:file_name,:product_id,:is_primary)";
        $params = ['file_name' => $filename, 'product_id' => $product_id, 'is_primary' => $isPrimary];
        if ($this->executeQuery($query, $params))
            return  $this->conn->lastInsertId();
        return false;
    }

    public function deleteProductImageById($image_id)
    {
        $query = "DELETE FROM product_images WHERE image_id=:image_id";
        $params = ['image_id' => $image_id];
        $this->executeQuery($query, $params);
    }

    public function updateProductImage($product_id, $filename, $image_id)
    {
        $query = "UPDATE  product_images SET file_name=:file_name,product_id=:product_id) WHERE image_id=:image_id";
        $params = ['file_name' => $filename, 'product_id' => $product_id, 'image_id' => $image_id];
        $return = $this->executeQuery($query, $params);
    }

    public function getProductImagesByProductId($product_id)
    {
        $query = "SELECT * FROM product_images JOIN products ON product_images.product_id = products.product_id WHERE products.product_id=:product_id";
        $params = ['product_id' => $product_id];
        $stmt =  $this->executeQuery($query, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductImageById($image_id)
    {
        $query = "SELECT * FROM product_images WHERE image_id=:image_id";
        $params = ['image_id' => $image_id];
        $stmt = $this->executeQuery($query, $params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function changePrimaryImg($productId, $imageId)
    {
        $query = "UPDATE product_images SET is_primary = 0 WHERE product_id = :product_id";
        $this->executeQuery($query, ['product_id' => $productId]);

        $query = "UPDATE product_images SET is_primary = 1  WHERE image_id=:image_id";
        $this->executeQuery($query, ['image_id' => $imageId]);
    }
}
