<?php
class ErrorController {
    /**
     * Hiển thị trang 404 Not Found
     */
    public function notFound() {
        $this->renderErrorPage(404, 'Trang bạn yêu cầu không tồn tại');
    }

    /**
     * Hiển thị trang lỗi tổng quát
     * @param int $code Mã lỗi HTTP
     * @param string $message Thông báo lỗi
     */
    public function error($code = 500, $message = 'Đã xảy ra lỗi máy chủ') {
        $this->renderErrorPage($code, $message);
    }

    /**
     * Render trang lỗi
     */
    private function renderErrorPage($code, $message) {
        http_response_code($code);
        
        // Kiểm tra nếu request là AJAX
        if ($this->isAjaxRequest()) {
            header('Content-Type: application/json');
            die(json_encode([
                'error' => [
                    'code' => $code,
                    'message' => $message
                ]
            ]));
        }

        // Hiển thị trang lỗi HTML
        $errorFile = __DIR__ . '/../views/errors/' . $code . '.php';
        if (file_exists($errorFile)) {
            require $errorFile;
        } else {
            // Fallback nếu không có template riêng
            echo "<h1>Lỗi {$code}</h1>";
            echo "<p>{$message}</p>";
        }
        exit;
    }

    /**
     * Kiểm tra request AJAX
     */
    private function isAjaxRequest() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}