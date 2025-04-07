<?php
class ReportModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getDailyReports() {
        $query = "SELECT 
                    DATE(created_at) as date,
                    COUNT(id) as order_count,
                    SUM(total) as revenue,
                    SUM(total * 0.2) as profit
                  FROM orders
                  WHERE status = 'delivered'
                  GROUP BY DATE(created_at)
                  ORDER BY date DESC
                  LIMIT 30";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getMonthlyReports() {
        $query = "SELECT 
                    YEAR(created_at) as year,
                    MONTH(created_at) as month,
                    COUNT(id) as order_count,
                    SUM(total) as revenue,
                    SUM(total * 0.2) as profit
                  FROM orders
                  WHERE status = 'delivered'
                  GROUP BY YEAR(created_at), MONTH(created_at)
                  ORDER BY year DESC, month DESC
                  LIMIT 12";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getYearlyReports() {
        $query = "SELECT 
                    YEAR(created_at) as year,
                    COUNT(id) as order_count,
                    SUM(total) as revenue,
                    SUM(total * 0.2) as profit
                  FROM orders
                  WHERE status = 'delivered'
                  GROUP BY YEAR(created_at)
                  ORDER BY year DESC
                  LIMIT 5";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getMonthlyRevenue() {
        $query = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    SUM(total) as amount
                  FROM orders
                  WHERE status = 'delivered'
                  GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                  ORDER BY month DESC
                  LIMIT 12";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getTopProducts($limit = 5) {
        $query = "SELECT 
                    p.name,
                    SUM(od.quantity) as sold_quantity
                  FROM order_details od
                  JOIN product p ON od.product_id = p.id
                  JOIN orders o ON od.order_id = o.id
                  WHERE o.status = 'delivered'
                  GROUP BY p.id
                  ORDER BY sold_quantity DESC
                  LIMIT ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}