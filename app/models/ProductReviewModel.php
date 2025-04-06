<?php
class ProductReviewModel {
    private $conn;
    private $table = 'product_reviews';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addReview($data) {
        $query = "INSERT INTO {$this->table} 
                 (product_id, account_id, rating, comment) 
                 VALUES (:product_id, :account_id, :rating, :comment)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':product_id', (int)$data['product_id'], PDO::PARAM_INT);
        $stmt->bindValue(':account_id', (int)$data['account_id'], PDO::PARAM_INT);
        $stmt->bindValue(':rating', (int)$data['rating'], PDO::PARAM_INT);
        $stmt->bindValue(':comment', $data['comment'], PDO::PARAM_STR);
        
        return $stmt->execute();
    }

    public function getReviewsByProduct($productId) {
        $query = "SELECT r.*, a.username, a.fullname 
                 FROM {$this->table} r
                 JOIN account a ON r.account_id = a.id
                 WHERE r.product_id = :product_id
                 ORDER BY r.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':product_id', (int)$productId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAverageRating($productId) {
        $query = "SELECT 
                    AVG(rating) as average_rating,
                    COUNT(*) as total_reviews
                  FROM {$this->table}
                  WHERE product_id = :product_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':product_id', (int)$productId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        
        return [
            'average' => $result->average_rating ? round($result->average_rating, 1) : 0,
            'count' => $result->total_reviews
        ];
    }

    public function getUserReview($productId, $accountId) {
        $query = "SELECT r.*, a.username, a.fullname 
                 FROM {$this->table} r
                 JOIN account a ON r.account_id = a.id
                 WHERE r.product_id = :product_id 
                 AND r.account_id = :account_id
                 LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':product_id', (int)$productId, PDO::PARAM_INT);
        $stmt->bindValue(':account_id', (int)$accountId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}