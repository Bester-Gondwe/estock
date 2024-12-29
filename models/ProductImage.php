<?php
require_once __DIR__ . '/../connection.php';


class ProductImage extends Database{

    public function __construct()
    {
        parent::__construct();
    }

    public function addProductImage($product_id,$filename){
        $query = "INSERT INTO product_images (file_name,product_id) VALUES (:file_name,:product_id)";
        $params = ['file_name' => $filename,'product_id' => $product_id];
        $this->executeQuery($query,$params);
    }

    public function deleteProductImageById($image_id){
        $query = "DELETE FROM product_images WHERE image_id=:image_id";
        $params = ['image_id' => $image_id];
        $this->executeQuery($query,$params);
    }

    public function updateProductImage($product_id,$filename,$image_id){
        $query = "UPDATE  product_images SET file_name=:file_name,product_id=:product_id) WHERE image_id=:image_id";
        $params = ['file_name' => $filename,'product_id' => $product_id,'image_id' => $image_id];
        $this->executeQuery($query,$params);
    }

    public function getProductImagesByProductId($product_id){
        $query = "SELECT * FROM product_images WHERE product_id=:product_id";
        $params = ['product_id' => $product_id];
        $this->executeQuery($query,$params);
    }

    public function getProductImageById($image_id){
        $query = "SELECT * FROM product_images WHERE image_id=:image_id";
        $params = ['image_id' => $image_id];
        $this->executeQuery($query,$params);
    }
}

