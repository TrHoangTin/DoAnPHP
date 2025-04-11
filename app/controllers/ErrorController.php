<?php
class ErrorController {
   
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

    private function renderErrorPage($code, $message) {
        http_response_code($code);
        if ($this->isAjaxRequest()) {
            header('Content-Type: application/json');
            die(json_encode([
                'error' => [
                    'code' => $code,
                    'message' => $message
                ]
            ]));
        }
        $errorFile = __DIR__ . '/../views/errors/' . $code . '.php';
        if (file_exists($errorFile)) {
            require $errorFile;
        } else {
            echo "<h1>Lỗi {$code}</h1>";
            echo "<p>{$message}</p>";
        }
        exit;
    }
    private function isAjaxRequest() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}