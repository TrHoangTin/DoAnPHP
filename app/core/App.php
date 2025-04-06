<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';

class App {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];
    protected $routes = [];

    public function __construct() {
        try {
            // Bật debug trong môi trường development
            $this->setupDebugging();

            // Đăng ký các routes
            $this->registerRoutes();

            // Xử lý request
            $url = $this->parseUrl();
            $this->handleRequest($url);

        } catch (Throwable $e) {
            $this->handleError($e);
        }
    }

    /**
     * Cấu hình debug
     */
    protected function setupDebugging() {
        if (getenv('APP_ENV') === 'development') {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
        }
    }

    /**
     * Đăng ký các routes
     */
    protected function registerRoutes() {
        // API Routes
        $this->routes['api'] = [
            'products/([0-9]+)/reviews' => [
                'controller' => 'ProductReviewController',
                'methods' => [
                    'GET' => 'index',
                    'POST' => 'store'
                ]
            ]
        ];

        // Web Routes
        $this->routes['web'] = [
            'product/([0-9]+)/reviews' => [
                'controller' => 'ProductController',
                'method' => 'reviews'
            ]
        ];
    }

    /**
     * Phân tích URL
     */
    protected function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return ['home'];
    }
    

    /**
     * Xử lý request
     */
    protected function handleRequest($url) {
        // Kiểm tra API routes trước
        if ($this->handleApiRoutes($url)) {
            return;
        }

        // Xử lý web routes thông thường
        $this->handleWebRoutes($url);
    }

    /**
     * Xử lý API routes
     */
    protected function handleApiRoutes($url) {
        if (empty($url[0]) || $url[0] !== 'api') {
            return false;
        }

        header('Content-Type: application/json');
        array_shift($url); // Bỏ phần 'api'

        foreach ($this->routes['api'] as $pattern => $route) {
            if (preg_match("@^{$pattern}$@", implode('/', $url), $matches)) {
                $controllerName = $route['controller'];
                $controllerFile = __DIR__ . "/../controllers/{$controllerName}.php";

                if (!file_exists($controllerFile)) {
                    $this->sendJsonResponse(404, ['error' => 'Controller not found']);
                }

                require_once $controllerFile;
                $controller = new $controllerName();

                $method = $route['methods'][$_SERVER['REQUEST_METHOD']] ?? null;
                if (!$method || !method_exists($controller, $method)) {
                    $this->sendJsonResponse(405, ['error' => 'Method not allowed']);
                }

                array_shift($matches); // Bỏ phần match toàn bộ
                call_user_func_array([$controller, $method], $matches);
                return true;
            }
        }

        $this->sendJsonResponse(404, ['error' => 'API endpoint not found']);
        return true;
    }

    /**
     * Xử lý web routes
     */
    protected function handleWebRoutes($url) {
        // Kiểm tra routes đã đăng ký
        foreach ($this->routes['web'] as $pattern => $route) {
            if (preg_match("@^{$pattern}$@", implode('/', $url), $matches)) {
                $controllerName = $route['controller'];
                $controllerFile = __DIR__ . "/../controllers/{$controllerName}.php";

                if (!file_exists($controllerFile)) {
                    $this->handleNotFound();
                }

                require_once $controllerFile;
                $controller = new $controllerName();

                $method = $route['method'] ?? 'index';
                if (!method_exists($controller, $method)) {
                    $this->handleNotFound();
                }

                array_shift($matches); // Bỏ phần match toàn bộ
                call_user_func_array([$controller, $method], $matches);
                return;
            }
        }

        // Xử lý controller/method thông thường
        if (isset($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            $controllerFile = __DIR__ . "/../controllers/{$controllerName}.php";

            if (file_exists($controllerFile)) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }

        require_once __DIR__ . "/../controllers/{$this->controller}.php";
        $this->controller = new $this->controller;

        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Xử lý lỗi
     */
    protected function handleError(Throwable $e) {
        error_log($e->getMessage());

        $errorController = $this->getErrorController();
        if ($e instanceof NotFoundException) {
            $errorController->notFound();
        } else {
            $errorController->error(500, 'Internal Server Error');
        }
    }

    /**
     * Xử lý 404
     */
    protected function handleNotFound() {
        $this->getErrorController()->notFound();
    }

    /**
     * Lấy ErrorController
     */
    protected function getErrorController() {
        $errorControllerPath = __DIR__ . '/../controllers/ErrorController.php';
        if (!file_exists($errorControllerPath)) {
            die('System is under maintenance');
        }

        require_once $errorControllerPath;
        return new ErrorController();
    }

    /**
     * Trả về JSON response
     */
    protected function sendJsonResponse($statusCode, $data) {
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}