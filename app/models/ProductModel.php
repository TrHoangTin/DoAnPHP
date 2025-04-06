<?php
class ProductModel {
    private $conn;
    private $table = 'product';
    
    public function __construct($db) {
        $this->conn = $db;
    }

    public function addProduct($data) {
        $query = "INSERT INTO {$this->table} 
                  (name, description, price, category_id, image, created_at, updated_at) 
                  VALUES 
                  (:name, :description, :price, :category_id, :image, NOW(), NOW())";
        
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':category_id' => $data['category_id'],
            ':image' => $data['image']
        ]);
    }

    public function deleteProduct($id) {
        // Lấy thông tin sản phẩm trước để kiểm tra ảnh
        $product = $this->getProductById($id);
        if ($product && !empty($product->image)) {
            $imagePath = __DIR__ . '/../../public' . parse_url($product->image, PHP_URL_PATH);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    
        // Xóa sản phẩm từ database
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
    // Thêm vào ProductModel.php
// public function updateProduct($id, $data) {
//     $query = "UPDATE {$this->table} SET 
//                 name = :name,
//                 description = :description,
//                 price = :price,
//                 category_id = :category_id,
//                 image = :image,
//                 updated_at = NOW()
//               WHERE id = :id";
    
//     $stmt = $this->conn->prepare($query);
    
//     return $stmt->execute([
//         ':name' => $data['name'],
//         ':description' => $data['description'],
//         ':price' => $data['price'],
//         ':category_id' => $data['category_id'],
//         ':image' => $data['image'],
//         ':id' => $id
//     ]);
// }
public function updateProduct($id, $data) {
    $query = "UPDATE {$this->table} SET 
                name = :name,
                description = :description,
                price = :price,
                category_id = :category_id,
                image = :image,
                updated_at = NOW()
              WHERE id = :id";
    
    $stmt = $this->conn->prepare($query);
    
    return $stmt->execute([
        ':name' => $data['name'],
        ':description' => $data['description'],
        ':price' => $data['price'],
        ':category_id' => $data['category_id'],
        ':image' => $data['image'],
        ':id' => $id
    ]);
}

    // Phương thức lấy sản phẩm theo ID
    public function getProductById($id) {
        $query = "SELECT p.*, c.name as category_name 
                  FROM {$this->table} p
                  LEFT JOIN category c ON p.category_id = c.id
                  WHERE p.id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Thêm phân trang vào ProductModel.php
    public function getPaginatedProducts($page = 1, $limit = 10, $category_id = null, $search = null) {
        $offset = ($page - 1) * $limit;

        $query = "SELECT SQL_CALC_FOUND_ROWS p.*, c.name as category_name 
                  FROM {$this->table} p
                  LEFT JOIN category c ON p.category_id = c.id
                  WHERE 1=1";

        $params = [];

        // Lọc theo danh mục nếu có
        if ($category_id) {
            $query .= " AND p.category_id = ?";
            $params[] = $category_id;
        }

        // Lọc theo từ khóa tìm kiếm nếu có
        if ($search) {
            $query .= " AND (p.name LIKE ? OR p.description LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        // Phân trang
        $query .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        // Thực thi câu truy vấn
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        $products = $stmt->fetchAll();

        // Lấy tổng số sản phẩm
        $stmt = $this->conn->prepare("SELECT FOUND_ROWS() as total");
        $stmt->execute();
        $total = $stmt->fetch()->total;

        return [
            'products' => $products,
            'total' => $total
        ];
    }
}

