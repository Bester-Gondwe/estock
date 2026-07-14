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
        $query = "INSERT INTO product_images (file_name, product_id, is_primary)
                  VALUES (:file_name, :product_id, :is_primary)";
        $params = [
            'file_name' => $filename,
            'product_id' => $product_id,
            'is_primary' => $isPrimary,
        ];
        if ($this->executeQuery($query, $params)) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function deleteProductImageById($image_id)
    {
        $query = "DELETE FROM product_images WHERE image_id = :image_id";
        $this->executeQuery($query, ['image_id' => $image_id]);
    }

    public function updateProductImage($product_id, $filename, $image_id)
    {
        $query = "UPDATE product_images
                  SET file_name = :file_name, product_id = :product_id
                  WHERE image_id = :image_id";
        return $this->executeQuery($query, [
            'file_name' => $filename,
            'product_id' => $product_id,
            'image_id' => $image_id,
        ]);
    }

    public function getProductImagesByProductId($product_id)
    {
        $query = "SELECT * FROM product_images WHERE product_id = :product_id ORDER BY is_primary DESC, image_id ASC";
        $stmt = $this->executeQuery($query, ['product_id' => $product_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductImageById($image_id)
    {
        $query = "SELECT * FROM product_images WHERE image_id = :image_id";
        $stmt = $this->executeQuery($query, ['image_id' => $image_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function changePrimaryImg($productId, $imageId)
    {
        $this->executeQuery(
            "UPDATE product_images SET is_primary = 0 WHERE product_id = :product_id",
            ['product_id' => $productId]
        );
        $this->executeQuery(
            "UPDATE product_images SET is_primary = 1 WHERE image_id = :image_id",
            ['image_id' => $imageId]
        );
    }
}
