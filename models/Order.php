<?php
require_once __DIR__ . '/../connection.php';

class Order extends Database
{

    private $ordersTable = 'orders';
    private $orderItemsTable = 'order_details';

    public function __construct()
    {
        parent::__construct();
    }

    // Create a new order
    public function createOrder($user_id)
    {
        $query = "INSERT INTO $this->ordersTable (user_id,order_status) VALUES (:user_id,'Pending')";
        $this->executeQuery($query, ['user_id' => $user_id]);
        return $this->conn->lastInsertId();
    }

    // Add products to an order
    public function addProductToOrder($order_id, $product_id,$quantity)
    {
        $query = "INSERT INTO $this->orderItemsTable (order_id,product_id,quantity) VALUES (:order_id,:product_id,:quantity)";
        $this->executeQuery($query, ['order_id' => $order_id, 'product_id' => $product_id,'quantity' => $quantity]);
    }

    // Fetch orders by customer
    public function getOrdersByCustomer($user_id)
    {
        $query = "SELECT * FROM $this->ordersTable WHERE user_id=:user_id";
        $this->executeQuery($query, ['user_id' => $user_id]);
    }

    // Fetch orders by customer
    public function getOrderById($orderId)
    {
        $query = "SELECT orders.*, CONCAT(users.first_name, ' ', users.last_name) AS customer_name, users.email FROM orders JOIN users ON users.user_id = orders.user_id WHERE order_id=:order_id";
        $stmt = $this->executeQuery($query, ['order_id' => $orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch order details (products within an order)
    public function getOrderDetails($order_id)
    {

        $query = "SELECT products.product_name, orders.order_status,order_details.order_id,order_details.quantity, (order_details.quantity * products.product_price) AS totalPrice

            FROM order_details 
            JOIN orders ON order_details.order_id = orders.order_id
            JOIN products ON order_details.product_id = products.product_id 
            WHERE order_details.order_id = :order_id";

        $stmt = $this->executeQuery($query, ['order_id' => $order_id]);
        return $stmt->fetchAll();
    }

    // Update order status
    public function updateOrderStatus($order_id, $order_status)
    {
        $query = "UPDATE $this->ordersTable SET order_status =:order_status WHERE order_id =:order_id";
        return  $this->executeQuery($query, ['order_id' => $order_id, 'order_status' => $order_status]);
    }

    // get paginated orders
    public function getOrdersPaginated($userId, $offset, $limit)
    {
        $query = "SELECT 
            orders.*,
            CONCAT(users.first_name, ' ', users.last_name) AS customer_name,
            products.product_name,
            order_details.quantity
            FROM 
            orders
            JOIN 
            users ON users.user_id = orders.user_id
            JOIN 
            order_details ON orders.order_id = order_details.order_id
            JOIN 
            products ON order_details.product_id = products.product_id
            WHERE 
            products.user_id = :user_id
            GROUP BY orders.order_id
            ORDER BY 
            orders.order_date,orders.order_id DESC
            LIMIT $offset, $limit";
        $stmt = $this->executeQuery($query, ['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // count orders by customer
    public function countOrdersByCustomer($user_id)
    {
        $query = "SELECT COUNT(*) AS total FROM {$this->ordersTable} WHERE user_id = :user_id";
        $params = ['user_id' => $user_id];
        $stmt = $this->executeQuery($query, $params);
        return $stmt->fetch()['total'];
    }


    // public function calculateOrderTotal($order_id)
    // {
    //     $query = "SELECT SUM(oi.quantity * oi.price) AS total 
    //           FROM {$this->orderItemsTable} oi 
    //           WHERE oi.order_id = :order_id";
    //     $stmt = $this->executeQuery($query, ['order_id' => $order_id]);
    //     return $stmt->fetch()['total'];
    // }

    // update order item
    // public function updateOrderItem($order_id, $product_id, $quantity, $price)
    // {
    //     $query = "UPDATE {$this->orderItemsTable} 
    //           SET quantity = :quantity, price = :price 
    //           WHERE order_id = :order_id AND product_id = :product_id";
    //     $params = [
    //         'order_id' => $order_id,
    //         'product_id' => $product_id,
    //         'quantity' => $quantity,
    //         'price' => $price
    //     ];
    //     $this->executeQuery($query, $params);
    // }


    public function countOrderForMerchant($user_id)
    {
        $query = "SELECT 
                COUNT(DISTINCT orders.order_id) AS total_orders
                FROM 
                users
                JOIN 
                products  ON users.user_id = products.user_id
                JOIN 
                order_details ON products.product_id = order_details.product_id
                JOIN 
      orders  ON order_details.order_id = orders.order_id
            JOIN user_roles ON users.user_id = user_roles.user_id 
            JOIN roles ON user_roles.role_id = roles.role_id
            WHERE 
          roles.role_name = 'Merchant' AND users.user_id =:user_id;
        GROUP BY 
                users.user_id, users.first_name,users.last_name;
";
        $result =  $this->executeQuery($query, ['user_id' => $user_id]);
        return $result->fetchColumn();
    }


    public function countOrderByStatusForMerchant($user_id, $orderStatus)
    {
        $query = "SELECT 
                 COUNT(DISTINCT orders.order_id) AS total_orders
                FROM 
                users
                JOIN 
                products  ON users.user_id = products.user_id
                JOIN 
                order_details ON products.product_id = order_details.product_id
                JOIN 
      orders  ON order_details.order_id = orders.order_id
            JOIN user_roles ON users.user_id = user_roles.user_id 
            JOIN roles ON user_roles.role_id = roles.role_id
            WHERE 
          roles.role_name = 'Merchant' AND users.user_id =:user_id AND orders.order_status=:status
        GROUP BY 
                users.user_id, users.first_name,users.last_name;";

        $result =  $this->executeQuery($query, ['user_id' => $user_id, 'status' => $orderStatus]);
        $orderCount = $result->fetchColumn();
        return $orderCount == false ? 0 : $orderCount;
    }


    public function countProductsForMerchant($user_id)
    {
        $query = "SELECT 
                COUNT(products.product_id) AS total_products
                FROM 
                    products
                JOIN users  ON users.user_id = products.user_id
                WHERE 
                    users.user_id =:user_id;
                GROUP BY 
                    users.user_id;";
        $result =  $this->executeQuery($query, ['user_id' => $user_id]);
        return $result->fetchColumn();
    }
}
