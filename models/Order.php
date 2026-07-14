<?php
require_once __DIR__ . '/../connection.php';

class Order extends Database
{
    private string $ordersTable = 'orders';
    private string $orderItemsTable = 'order_details';

    public function __construct()
    {
        parent::__construct();
    }

    public function createOrder($user_id, $shipping_address = null, $payment_method = 'Cash on Delivery', $notes = null)
    {
        $query = "INSERT INTO {$this->ordersTable}
            (user_id, order_status, shipping_address, payment_method, notes)
            VALUES (:user_id, 'Pending', :shipping_address, :payment_method, :notes)";
        $this->executeQuery($query, [
            'user_id' => $user_id,
            'shipping_address' => $shipping_address,
            'payment_method' => $payment_method,
            'notes' => $notes,
        ]);
        return $this->conn->lastInsertId();
    }

    public function addProductToOrder($order_id, $product_id, $quantity, $unit_price = 0)
    {
        $query = "INSERT INTO {$this->orderItemsTable} (order_id, product_id, quantity, unit_price)
                  VALUES (:order_id, :product_id, :quantity, :unit_price)";
        $this->executeQuery($query, [
            'order_id' => $order_id,
            'product_id' => $product_id,
            'quantity' => $quantity,
            'unit_price' => $unit_price,
        ]);
    }

    public function updateOrderAmount($order_id, $amount)
    {
        $query = "UPDATE orders SET amount = :amount WHERE order_id = :order_id";
        $this->executeQuery($query, ['order_id' => $order_id, 'amount' => $amount]);
    }

    public function getOrdersByCustomer($user_id)
    {
        $query = "SELECT * FROM {$this->ordersTable} WHERE user_id = :user_id ORDER BY order_date DESC, order_id DESC";
        $stmt = $this->executeQuery($query, ['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderById($orderId)
    {
        $query = "SELECT orders.*,
            CONCAT(users.first_name, ' ', users.last_name) AS customer_name,
            users.email,
            users.phone,
            users.address AS user_address
            FROM orders
            JOIN users ON users.user_id = orders.user_id
            WHERE order_id = :order_id";
        $stmt = $this->executeQuery($query, ['order_id' => $orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrderDetails($order_id)
    {
        $query = "SELECT products.product_name,
            products.product_id,
            orders.order_status,
            order_details.order_id,
            order_details.quantity,
            order_details.unit_price,
            COALESCE(NULLIF(order_details.unit_price, 0), products.product_price) AS price,
            (order_details.quantity * COALESCE(NULLIF(order_details.unit_price, 0), products.product_price)) AS totalPrice
            FROM order_details
            JOIN orders ON order_details.order_id = orders.order_id
            JOIN products ON order_details.product_id = products.product_id
            WHERE order_details.order_id = :order_id";
        $stmt = $this->executeQuery($query, ['order_id' => $order_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateOrderStatus($order_id, $order_status)
    {
        $query = "UPDATE {$this->ordersTable} SET order_status = :order_status WHERE order_id = :order_id";
        return $this->executeQuery($query, ['order_id' => $order_id, 'order_status' => $order_status]);
    }

    public function getOrdersPaginated($userId, $offset, $limit)
    {
        $offset = (int) $offset;
        $limit = (int) $limit;
        $query = "SELECT
            orders.order_id,
            orders.user_id,
            orders.order_date,
            orders.order_status,
            orders.amount,
            orders.shipping_address,
            orders.payment_method,
            orders.notes,
            CONCAT(users.first_name, ' ', users.last_name) AS customer_name,
            GROUP_CONCAT(DISTINCT products.product_name ORDER BY products.product_name SEPARATOR ', ') AS product_name,
            COUNT(DISTINCT products.product_id) AS item_count,
            SUM(order_details.quantity) AS quantity
            FROM orders
            JOIN users ON users.user_id = orders.user_id
            JOIN order_details ON orders.order_id = order_details.order_id
            JOIN products ON order_details.product_id = products.product_id
            WHERE products.user_id = :user_id
            GROUP BY
                orders.order_id,
                orders.user_id,
                orders.order_date,
                orders.order_status,
                orders.amount,
                orders.shipping_address,
                orders.payment_method,
                orders.notes,
                users.first_name,
                users.last_name
            ORDER BY orders.order_date DESC, orders.order_id DESC
            LIMIT {$offset}, {$limit}";
        $stmt = $this->executeQuery($query, ['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function merchantOwnsOrder($merchantId, $orderId): bool
    {
        $query = "SELECT COUNT(*) FROM order_details
            JOIN products ON products.product_id = order_details.product_id
            WHERE order_details.order_id = :order_id AND products.user_id = :user_id";
        $stmt = $this->executeQuery($query, ['order_id' => $orderId, 'user_id' => $merchantId]);
        return $stmt->fetchColumn() > 0;
    }

    public function countOrdersByCustomer($user_id)
    {
        $query = "SELECT COUNT(*) AS total FROM {$this->ordersTable} WHERE user_id = :user_id";
        $stmt = $this->executeQuery($query, ['user_id' => $user_id]);
        return (int) $stmt->fetch()['total'];
    }

    public function countOrderForMerchant($user_id)
    {
        $query = "SELECT COUNT(DISTINCT orders.order_id) AS total_orders
            FROM products
            JOIN order_details ON products.product_id = order_details.product_id
            JOIN orders ON order_details.order_id = orders.order_id
            WHERE products.user_id = :user_id";
        $result = $this->executeQuery($query, ['user_id' => $user_id]);
        return (int) $result->fetchColumn();
    }

    public function countOrderByStatusForMerchant($user_id, $orderStatus)
    {
        $query = "SELECT COUNT(DISTINCT orders.order_id) AS total_orders
            FROM products
            JOIN order_details ON products.product_id = order_details.product_id
            JOIN orders ON order_details.order_id = orders.order_id
            WHERE products.user_id = :user_id AND orders.order_status = :status";
        $result = $this->executeQuery($query, ['user_id' => $user_id, 'status' => $orderStatus]);
        return (int) $result->fetchColumn();
    }

    public function sumRevenueForMerchant($user_id)
    {
        $query = "SELECT COALESCE(SUM(order_details.quantity * COALESCE(NULLIF(order_details.unit_price, 0), products.product_price)), 0) AS revenue
            FROM products
            JOIN order_details ON products.product_id = order_details.product_id
            JOIN orders ON order_details.order_id = orders.order_id
            WHERE products.user_id = :user_id AND orders.order_status != 'Cancelled'";
        $result = $this->executeQuery($query, ['user_id' => $user_id]);
        return (float) $result->fetchColumn();
    }

    public function countProductsForMerchant($user_id)
    {
        $query = "SELECT COUNT(product_id) AS total_products FROM products WHERE user_id = :user_id";
        $result = $this->executeQuery($query, ['user_id' => $user_id]);
        return (int) $result->fetchColumn();
    }
}
