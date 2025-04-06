<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';

class App {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        try {
            $url = $this->parseUrl();

            // 1. Xử lý API routes trước
            if ($this->isApiRequest($url)) {
                $this->handleApiRequest($url);
                return;
            }

            // 2. Xử lý routes thông thường
            $this->handleWebRequest($url);

        } catch (Exception $e) {
            $this->handleError(500, 'Lỗi server: ' . $e->getMessage());
        }
    }

    /**
     * Phân tích URL từ request
     */
    protected function parseUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return ['home'];
    }

    /**
     * Kiểm tra có phải request API không
     */
    protected function isApiRequest($url) {
        return isset($url[0]) && $url[0] === 'api';
    }

    /**
     * Xử lý request API
     */
    protected function handleApiRequest($url) {
        header('Content-Type: application/json');

        try {
            // API Product Reviews
            if ($this->isProductReviewsApi($url)) {
                $this->processProductReviewsApi($url);
                return;
            }

            // Thêm các API endpoints khác ở đây...

            // Không tìm thấy API endpoint
            $this->sendJsonResponse(404, ['success' => false, 'message' => 'API endpoint không tồn tại']);

        } catch (Exception $e) {
            $this->sendJsonResponse(500, [
                'success' => false,
                'message' => 'Lỗi API: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Kiểm tra có phải API reviews không
     */
    protected function isProductReviewsApi($url) {
        return isset($url[1]) && $url[1] === 'products' 
            && isset($url[2]) && is_numeric($url[2])
            && isset($url[3]) && $url[3] === 'reviews';
    }

    /**
     * Xử lý API reviews
     */
    protected function processProductReviewsApi($url) {
        require_once __DIR__ . '/../controllers/ProductReviewController.php';
        $controller = new ProductReviewController();
        $productId = (int)$url[2];

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $controller->index($productId);
                break;
            case 'POST':
                $controller->store($productId);
                break;
            default:
                $this->sendJsonResponse(405, [
                    'success' => false,
                    'message' => 'Phương thức không được hỗ trợ'
                ]);
        }
    }

    /**
     * Xử lý request web thông thường
     */
    protected function handleWebRequest($url) {
        // Xác định controller
        if (isset($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';

            if (file_exists($controllerFile)) {
                $this->controller = $controllerName;
                unset($url[0]);
            } else {
                $this->handleNotFound();
                return;
            }
        }

        // Khởi tạo controller
        require_once __DIR__ . '/../controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Xác định method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            } else {
                $this->handleNotFound();
                return;
            }
        }

        // Xử lý params
        $this->params = $url ? array_values($url) : [];

        // Gọi controller method
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Trả về response JSON
     */
    protected function sendJsonResponse($statusCode, $data) {
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    /**
     * Xử lý trang 404
     */
    protected function handleNotFound() {
        require_once __DIR__ . '/../controllers/ErrorController.php';
        $controller = new ErrorController();
        $controller->notFound();
        exit;
    }

    /**
     * Xử lý lỗi chung
     */
    protected function handleError($statusCode, $message) {
        if ($this->isApiRequest($this->parseUrl())) {
            $this->sendJsonResponse($statusCode, [
                'success' => false,
                'message' => $message
            ]);
        } else {
            require_once __DIR__ . '/../controllers/ErrorController.php';
            $controller = new ErrorController();
            $controller->error($statusCode, $message);
        }
    }
}