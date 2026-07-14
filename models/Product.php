<?php
require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/Category.php';

class Product extends Database
{
    private string $products_table = 'products';

    public function __construct()
    {
        parent::__construct();
    }

    public function addProduct($product_name, $product_description, $product_price, $user_id, $quantity, $categoryName, $sku = null, $lowStock = 5)
    {
        $category = new Category();
        $ctgry = $category->getCategoryByName($categoryName);
        if (!$ctgry) {
            return false;
        }

        $query = "INSERT INTO {$this->products_table}
            (product_name, product_description, product_price, user_id, quantity, category_id, sku, low_stock_threshold, created_at)
            VALUES (:product_name, :product_description, :product_price, :user_id, :quantity, :category_id, :sku, :low_stock, NOW())";

        $params = [
            'product_name' => $product_name,
            'product_description' => $product_description,
            'product_price' => $product_price,
            'user_id' => $user_id,
            'quantity' => $quantity,
            'category_id' => $ctgry['category_id'],
            'sku' => $sku ?: null,
            'low_stock' => (int) $lowStock,
        ];

        if ($this->executeQuery($query, $params)) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function updateProduct($product_id, $product_name, $product_description, $product_price, $quantity, $categoryName, $sku = null, $lowStock = 5)
    {
        $category = new Category();
        $ctgry = $category->getCategoryByName($categoryName);
        if (!$ctgry) {
            return false;
        }

        $query = "UPDATE {$this->products_table}
            SET product_name=:product_name,
                product_description=:product_description,
                product_price=:product_price,
                quantity=:quantity,
                category_id=:category_id,
                sku=:sku,
                low_stock_threshold=:low_stock
            WHERE product_id=:product_id";

        $params = [
            'product_name' => $product_name,
            'product_description' => $product_description,
            'product_price' => $product_price,
            'quantity' => $quantity,
            'category_id' => $ctgry['category_id'],
            'sku' => $sku ?: null,
            'low_stock' => (int) $lowStock,
            'product_id' => $product_id,
        ];

        return $this->executeQuery($query, $params);
    }

    public function getProductById($product_id)
    {
        $query = "SELECT products.product_id,
            products.product_name,
            products.product_price,
            products.product_description,
            products.quantity,
            products.sku,
            products.low_stock_threshold,
            products.user_id,
            categories.category_name,
            product_images.file_name AS primary_image
            FROM products
            JOIN categories ON products.category_id = categories.category_id
            LEFT JOIN product_images ON products.product_id = product_images.product_id AND product_images.is_primary = 1
            WHERE products.product_id = :product_id";

        $stmt = $this->executeQuery($query, ['product_id' => $product_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteProduct($product_id, $user_id = null)
    {
        if ($user_id !== null) {
            $query = "DELETE FROM {$this->products_table} WHERE product_id=:product_id AND user_id=:user_id";
            return $this->executeQuery($query, ['product_id' => $product_id, 'user_id' => $user_id]);
        }
        $query = "DELETE FROM {$this->products_table} WHERE product_id=:product_id";
        return $this->executeQuery($query, ['product_id' => $product_id]);
    }

    public function ownsProduct($product_id, $user_id): bool
    {
        $query = "SELECT COUNT(*) FROM {$this->products_table} WHERE product_id=:product_id AND user_id=:user_id";
        $stmt = $this->executeQuery($query, ['product_id' => $product_id, 'user_id' => $user_id]);
        return $stmt->fetchColumn() > 0;
    }

    private function baseSelect(): string
    {
        return "SELECT products.product_id,
            products.product_name,
            products.product_price,
            products.product_description,
            products.quantity,
            products.sku,
            products.low_stock_threshold,
            categories.category_name,
            product_images.file_name AS primary_image
            FROM products
            JOIN categories ON products.category_id = categories.category_id
            LEFT JOIN product_images ON products.product_id = product_images.product_id AND product_images.is_primary = 1";
    }

    public function getAllProducts($limit = null)
    {
        $query = $this->baseSelect() . ' ORDER BY products.created_at DESC';
        if ($limit !== null) {
            $query .= ' LIMIT ' . (int) $limit;
        }
        $stmt = $this->executeQuery($query, []);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllProductByUserId($user_id)
    {
        $query = $this->baseSelect() . ' WHERE products.user_id = :user_id ORDER BY products.created_at DESC';
        $stmt = $this->executeQuery($query, ['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductsByCategory($categoryName)
    {
        $query = $this->baseSelect() . ' WHERE categories.category_name = :category_name ORDER BY products.created_at DESC';
        $stmt = $this->executeQuery($query, ['category_name' => $categoryName]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchProducts($keyword)
    {
        $query = $this->baseSelect() . "
            WHERE products.product_name LIKE :keyword
               OR products.product_description LIKE :keyword
               OR products.sku LIKE :keyword
            ORDER BY products.created_at DESC";
        $stmt = $this->executeQuery($query, ['keyword' => '%' . $keyword . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductsPaginated($offset, $limit)
    {
        $query = $this->baseSelect() . ' ORDER BY products.created_at DESC LIMIT ' . (int) $offset . ', ' . (int) $limit;
        $stmt = $this->executeQuery($query, []);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAllProducts()
    {
        $stmt = $this->executeQuery("SELECT COUNT(*) AS total FROM {$this->products_table}", []);
        return (int) $stmt->fetch()['total'];
    }

    public function getLowStockProducts($user_id)
    {
        $query = $this->baseSelect() . '
            WHERE products.user_id = :user_id
              AND products.quantity <= products.low_stock_threshold
            ORDER BY products.quantity ASC';
        $stmt = $this->executeQuery($query, ['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deductStock($product_id, $quantity): bool
    {
        $query = "UPDATE {$this->products_table}
            SET quantity = quantity - :qty
            WHERE product_id = :product_id AND quantity >= :qty2";
        $stmt = $this->executeQuery($query, [
            'qty' => $quantity,
            'qty2' => $quantity,
            'product_id' => $product_id,
        ]);
        return $stmt->rowCount() > 0;
    }

    public function recordInventoryMovement($product_id, $change_qty, $reason, $reference_id = null): void
    {
        $query = "INSERT INTO inventory_movements (product_id, change_qty, reason, reference_id)
                  VALUES (:product_id, :change_qty, :reason, :reference_id)";
        $this->executeQuery($query, [
            'product_id' => $product_id,
            'change_qty' => $change_qty,
            'reason' => $reason,
            'reference_id' => $reference_id,
        ]);
    }

    public function getRecentProducts($limit = 8)
    {
        return $this->getAllProducts((int) $limit);
    }
}
