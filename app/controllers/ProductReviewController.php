<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/ProductReviewModel.php';
require_once __DIR__ . '/../utils/JWTHandler.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';

class ProductReviewController {
    private $reviewModel;
    private $jwtHandler;

    public function __construct() {
        $this->reviewModel = new ProductReviewModel((new Database())->getConnection());
        $this->jwtHandler = new JWTHandler();
    }

    // Middleware xác thực JWT
    private function authenticate() {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';
        
        // Kiểm tra tồn tại header Authorization
        if (empty($authHeader)) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Authorization header is missing']);
            exit;
        }
    
        // Kiểm tra định dạng Bearer token
        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Token không đúng định dạng']);
            exit;
        }
    
        $token = $matches[1];
        
        try {
            $decoded = $this->jwtHandler->decode($token);
            
            if (!$decoded) {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Token không hợp lệ']);
                exit;
            }
            
            return $decoded['data']['user_id'] ?? null;
            
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode([
                'success' => false, 
                'message' => 'Token lỗi: ' . $e->getMessage()
            ]);
            exit;
        }
    }

    // Thêm đánh giá
   public function store($productId) {
    // ⚠️ Luôn set header JSON đầu tiên
    header('Content-Type: application/json');

    try {
        $userId = $this->authenticate(); // Middleware sẽ tự exit nếu lỗi

        // Đọc input
        $input = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dữ liệu JSON không hợp lệ']);
            return;
        }

        // Thêm review
        $result = $this->reviewModel->addReview([
            'product_id' => $productId,
            'account_id' => $userId,
            'rating' => (int)$input['rating'],
            'comment' => $input['comment']
        ]);

        if ($result) {
            http_response_code(201);
            echo json_encode(['success' => true, 'message' => 'Thành công']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm đánh giá']);
        }

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Lỗi server: ' . $e->getMessage()
        ]);
    }
}

    // Lấy danh sách đánh giá
    public function index($productId) {
        try {
            $reviews = $this->reviewModel->getReviewsByProduct($productId);
            $ratingInfo = $this->reviewModel->getAverageRating($productId);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => [
                    'reviews' => $reviews,
                    'average_rating' => $ratingInfo['average'],
                    'total_reviews' => $ratingInfo['count']
                ]
            ]);
        } catch(Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi server: ' . $e->getMessage()]);
        }
    }
}