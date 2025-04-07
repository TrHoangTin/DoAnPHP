<?php
require_once __DIR__ . '/../models/AccountModel.php';
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';

class AdminController {
    private $accountModel;
    private $orderModel;
    private $productModel;
    private $categoryModel;

    public function __construct() {
            try {
                $db = (new Database())->getConnection();
                // Test query
                $stmt = $db->query("SELECT 1");
                if (!$stmt) throw new Exception("Database connection failed");
            } catch (Exception $e) {
                die("DB Error: " . $e->getMessage());
            }
        if (!SessionHelper::isAdmin()) {
            SessionHelper::setFlash('error_message', 'Bạn không có quyền truy cập trang quản trị');
            header('Location: /webbanhang/');
            exit();
        }

        $db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($db);
        $this->orderModel = new OrderModel($db);
        $this->productModel = new ProductModel($db);
        $this->categoryModel = new CategoryModel($db);  
    }

    public function dashboard() {
        // Get statistics
        $stats = [
            'users' => $this->accountModel->getUserCount(),
            'orders' => $this->orderModel->getOrderCount(),
            'products' => $this->productModel->getProductCount(),
            'categories' => $this->categoryModel->getCategoryCount(),
            'revenue' => $this->orderModel->getRevenueStats()
        ];

        // Get recent orders
        $recentOrders = $this->orderModel->getRecentOrders(5);

        // Get recent users
        $recentUsers = $this->accountModel->getRecentUsers(5);

        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    public function users() {
        $users = $this->accountModel->getAllUsers();
        require_once __DIR__ . '/../views/admin/users/list.php';
    }

    public function userDetail($id) {
        $user = $this->accountModel->getAccountById($id);
        if (!$user) {
            SessionHelper::setFlash('error_message', 'Người dùng không tồn tại');
            header('Location: /webbanhang/admin/users');
            exit();
        }

        $userOrders = $this->orderModel->getOrdersByAccount($id);
        require_once __DIR__ . '/../views/admin/users/detail.php';
    }

    public function editUser($id) {
        $user = $this->accountModel->getAccountById($id);
        if (!$user) {
            SessionHelper::setFlash('error_message', 'Người dùng không tồn tại');
            header('Location: /webbanhang/admin/users');
            exit();
        }

//     Trong AdminController.php
// public function editUser($id) {
//     $user = $this->accountModel->getUserById($id);
//     if (!$user) {
//         SessionHelper::setFlash('error_message', 'Người dùng không tồn tại');
//         header('Location: /webbanhang/admin/users');
//         exit();
//     }

//     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//         $data = [
//             'username' => trim($_POST['username'] ?? ''),
//             'fullname' => trim($_POST['fullname'] ?? ''),
//             'email' => trim($_POST['email'] ?? ''),
//             'phone' => trim($_POST['phone'] ?? ''),
//             'role' => $_POST['role'] ?? 'user',
//             'status' => $_POST['status'] ?? 'active'
//         ];

//         if ($this->accountModel->updateUser($id, $data)) {
//             SessionHelper::setFlash('success_message', 'Cập nhật người dùng thành công');
//             header('Location: /webbanhang/admin/users');
//             exit();
//         } else {
//             SessionHelper::setFlash('error_message', 'Cập nhật người dùng không thành công');
//         }
//     }

//     require_once __DIR__ . '/../views/admin/users/edit.php';
// }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role = $_POST['role'] ?? $user->role;
            $status = $_POST['status'] ?? $user->status;

            if ($this->accountModel->updateUserRole($id, $role, $status)) {
                SessionHelper::setFlash('success_message', 'Cập nhật người dùng thành công');
                header('Location: /webbanhang/admin/users');
                exit();
            } else {
                SessionHelper::setFlash('error_message', 'Cập nhật người dùng không thành công');
            }
        }

        require_once __DIR__ . '/../views/admin/users/edit.php';
    }

    public function orders() {
        $orders = $this->orderModel->getAllOrders();
        require_once __DIR__ . '/../views/admin/orders/list.php';
    }

    public function orderDetail($id) {
        $order = $this->orderModel->getOrderById($id);
        if (!$order) {
            SessionHelper::setFlash('error_message', 'Đơn hàng không tồn tại');
            header('Location: /webbanhang/admin/orders');
            exit();
        }

        $orderDetails = $this->orderModel->getOrderDetails($id);
        $user = $this->accountModel->getAccountById($order->account_id);

        require_once __DIR__ . '/../views/admin/orders/detail.php';
    }

    public function updateOrderStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status'] ?? null;
            if ($this->orderModel->updateOrderStatus($id, $status)) {
                SessionHelper::setFlash('success_message', 'Cập nhật trạng thái đơn hàng thành công');
            } else {
                SessionHelper::setFlash('error_message', 'Cập nhật trạng thái đơn hàng không thành công');
            }
        }
        header('Location: /webbanhang/admin/orders/' . $id);
        exit();
    }

    public function products() {
        $products = $this->productModel->getAllProducts();
        require_once __DIR__ . '/../views/admin/products/list.php';
    }

    public function categories() {
        $categoryModel = new CategoryModel((new Database())->getConnection());
        $categories = $categoryModel->getCategoriesWithCount();
        
        require_once __DIR__.'/../views/admin/categories/list.php';
    }

    public function reports() {
        $reportModel = new ReportModel((new Database())->getConnection());
        
        // Kiểm tra và khởi tạo các biến với mảng rỗng nếu không có dữ liệu
        $data = [
            'dailyReports' => $reportModel->getDailyReports() ?? [],
            'monthlyReports' => $reportModel->getMonthlyReports() ?? [],
            'yearlyReports' => $reportModel->getYearlyReports() ?? [],
            'monthlyRevenue' => $reportModel->getMonthlyRevenue() ?? [],
            'topProducts' => $reportModel->getTopProducts(5) ?? []
        ];
        
        // Truyền dữ liệu vào view
        extract($data);
        require_once __DIR__.'/../views/admin/reports/index.php';
    }
    // public function reports() {
    //     $timeRange = $_GET['range'] ?? 'month';
    //     $revenueData = $this->orderModel->getRevenueReport($timeRange);
    //     require_once __DIR__ . '/../views/admin/reports/index.php';
    // }
    
    // public function categories() {
    //     $categories = $this->categoryModel->getCategoriesWithCount();
    //     require_once __DIR__ . '/../views/admin/categories/list.php';
    // }

    // public function products() {
    //     $productModel = new ProductModel((new Database())->getConnection());
    //     $products = $productModel->getAllProductsWithCategory();
    //     require_once __DIR__.'/../views/admin/products/list.php';
    // }

    // public function reports() {
    //     $reportModel = new ReportModel((new Database())->getConnection());     
    //     $data = [
    //         'dailyReports' => $reportModel->getDailyReports(),
    //         'monthlyReports' => $reportModel->getMonthlyReports(),
    //         'yearlyReports' => $reportModel->getYearlyReports(),
    //         'monthlyRevenue' => $reportModel->getMonthlyRevenue(),
    //         'topProducts' => $reportModel->getTopProducts(5)
    //     ];    
    //     require_once __DIR__.'/../views/admin/reports/index.php';
    // }
    //
}