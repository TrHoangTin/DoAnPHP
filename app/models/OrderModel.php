<?php
class OrderModel {
    private $conn;
    private $orderTable = 'orders';
    private $orderDetailTable = 'order_details';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createOrder($account_id, $name, $phone, $address, $payment_method, $total, $items) {
        try {
            $this->conn->beginTransaction();

            // Tạo đơn hàng
            $query = "INSERT INTO {$this->orderTable} 
                     (account_id, name, phone, address, payment_method, total)
                     VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$account_id, $name, $phone, $address, $payment_method, $total]);
            $order_id = $this->conn->lastInsertId();

            // Thêm chi tiết đơn hàng
            foreach ($items as $item) {
                $query = "INSERT INTO {$this->orderDetailTable} 
                         (order_id, product_id, quantity, price)
                         VALUES (?, ?, ?, ?)";
                $stmt = $this->conn->prepare($query);
                $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
            }

            $this->conn->commit();
            return $order_id;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    public function getOrdersByAccount($account_id) {
        $query = "SELECT * FROM {$this->orderTable} 
                 WHERE account_id = ?
                 ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$account_id]);
        return $stmt->fetchAll();
    }

    public function getOrderDetails($order_id) {
        $query = "SELECT od.*, p.name as product_name, p.image 
                 FROM {$this->orderDetailTable} od
                 JOIN product p ON od.product_id = p.id
                 WHERE od.order_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$order_id]);
        return $stmt->fetchAll();
    }

    public function updateOrderStatus($order_id, $status) {
        $query = "UPDATE {$this->orderTable} 
                 SET status = ?, updated_at = NOW()
                 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$status, $order_id]);
    }

    public function getOrderById($order_id) {
        $query = "SELECT * FROM {$this->orderTable} WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$order_id]);
        return $stmt->fetch();
    }

    // Add these methods to your existing OrderModel class

public function getOrderCount() {
    $query = "SELECT COUNT(*) as count FROM {$this->orderTable}";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetch()->count;
}

public function getRevenueStats() {
    $query = "SELECT 
                SUM(total) as total_revenue,
                COUNT(*) as total_orders,
                AVG(total) as avg_order_value
              FROM {$this->orderTable}
              WHERE status = 'completed'";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetch();
}

public function getRecentOrders($limit = 5) {
    $query = "SELECT o.*, a.username 
              FROM {$this->orderTable} o
              JOIN account a ON o.account_id = a.id
              ORDER BY o.created_at DESC 
              LIMIT ?";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

public function getAllOrders() {
    $query = "SELECT o.*, a.username 
              FROM orders o
              LEFT JOIN account a ON o.account_id = a.id
              ORDER BY o.created_at DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
}

public function getRevenueReport($range = 'month') {
    switch ($range) {
        case 'day':
            $format = '%Y-%m-%d';
            break;
        case 'year':
            $format = '%Y';
            break;
        default:
            $format = '%Y-%m';
    }
    
    $query = "SELECT 
                DATE_FORMAT(created_at, ?) as period,
                COUNT(*) as order_count,
                SUM(total) as total_revenue
              FROM {$this->orderTable}
              WHERE status = 'completed'
              GROUP BY period
              ORDER BY period";
    
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$format]);
    return $stmt->fetchAll();
}
}   