<?php
require_once __DIR__ . '/../connection.php';

class Order extends Database
{

    private $ordersTable = 'orders';
    private $orderItemsTable = 'order_items';

    public function __construct()
    {
        parent::__construct();
    }

    // Create a new order
    public function createOrder($user_id)
    {
        $query = "INSERT INTO $this->ordersTable (user_id) VALUES (:user_id)";
        $this->executeQuery($query, ['user_id' => $user_id]);
        return $this->conn->lastInsertId();
    }

    // Add products to an order
    public function addProductToOrder($order_id, $product_id)
    {
        $query = "INSERT INTO $this->orderItemsTable (order_id,product_id) VALUES (:order_id,:product_id)";
        $this->executeQuery($query, ['order_id' => $order_id, 'product_id' => $product_id]);
    }

    // Fetch orders by customer
    public function getOrdersByCustomer($user_id)
    {
        $query = "SELECT * FROM $this->ordersTable WHERE user_id=:user_id";
        $this->executeQuery($query, ['user_id' => $user_id]);
    }

    // Fetch order details (products within an order)
    public function getOrderDetails($order_id)
    {
        $query = "SELECT oi.product_id, p.name, oi.quantity, oi.price
                  FROM {$this->orderItemsTable} oi
                  JOIN products p ON oi.product_id = p.id
                  WHERE oi.order_id = :order_id";
        $stmt = $this->executeQuery($query, ['order_id' => $order_id]);
        return $stmt->fetchAll();
    }

    // Update order status
    public function updateOrderStatus($order_id, $order_status)
    {
        $query = "UPDATE $this->ordersTable SET order_status =:order_status WHERE order_id =:order_id";
        $this->executeQuery($query, ['order_id' => $order_id, 'order_status' => $order_status]);
    }

    // Cancel order
    public function cancelOrder($order_id)
    {
        $query = "UPDATE {$this->ordersTable} SET order_status = 'cancelled' WHERE order_id = :order_id";
        $this->executeQuery($query, ['order_id' => $order_id]);
    }

    // Get all orders
    public function getAllOrders()
    {
        $query = "SELECT * FROM {$this->ordersTable} ORDER BY created_at DESC";
        $stmt = $this->executeQuery($query, null);
        return $stmt->fetchAll();
    }

    // Get paginated orders
    public function getOrdersPaginated($offset, $limit)
    {
        $query = "SELECT * FROM {$this->ordersTable} ORDER BY created_at DESC LIMIT :offset, :limit";
        $stmt = $this->executeQuery($query, ['offset' => $offset, 'limit' => $limit]);
        return $stmt->fetchAll();
    }

    // Count orders by customer
    public function countOrdersByCustomer($user_id)
    {
        $query = "SELECT COUNT(*) AS total FROM {$this->ordersTable} WHERE user_id = :user_id";
        $params = ['user_id' => $user_id];
        $stmt = $this->executeQuery($query, $params);
        return $stmt->fetch()['total'];
    }

    // Calculate order total
    public function calculateOrderTotal($order_id)
    {
        $query = "SELECT SUM(oi.quantity * oi.price) AS total 
              FROM {$this->orderItemsTable} oi 
              WHERE oi.order_id = :order_id";
        $stmt = $this->executeQuery($query, ['order_id' => $order_id]);
        return $stmt->fetch()['total'];
    }

    // Update order item
    public function updateOrderItem($order_id, $product_id, $quantity, $price)
    {
        $query = "UPDATE {$this->orderItemsTable} 
              SET quantity = :quantity, price = :price 
              WHERE order_id = :order_id AND product_id = :product_id";
        $params = [
            'order_id' => $order_id,
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price' => $price
        ];
        $this->executeQuery($query, $params);
    }

    // Get recent orders
    public function getRecentOrders($limit = 10)
{
    $query = "SELECT o.order_id, 
                     CONCAT(u.first_name, ' ', u.last_name) AS customer_name, 
                     o.total_amount, 
                     o.status, 
                     o.order_date 
              FROM $this->ordersTable o
              INNER JOIN users u ON o.user_id = u.user_id
              ORDER BY o.order_date DESC 
              LIMIT $limit";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
}

    
}
