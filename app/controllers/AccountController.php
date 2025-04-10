<?php
require_once __DIR__ . '/../models/AccountModel.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';
require_once __DIR__ . '/../utils/JWTHandler.php';
require_once __DIR__ . '/../helpers/EmailHelper.php';
date_default_timezone_set('Asia/Ho_Chi_Minh'); 
class AccountController {
    private $accountModel;
    private $jwtHandler;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($db);
        $this->jwtHandler = new JWTHandler();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $account = $this->accountModel->authenticate($username, $password);

            if ($account) {
                SessionHelper::setUser($account);
                
                // Tạo JWT token
                $token = $this->jwtHandler->encode([
                    'id' => $account->id,
                    'username' => $account->username,
                    'role' => $account->role
                ]);
                
                $_SESSION['jwt_token'] = $token;

                SessionHelper::setFlash('success_message', 'Đăng nhập thành công');
                header('Location: /webbanhang/');
                exit();
            } else {
                SessionHelper::setFlash('error_message', 'Tên đăng nhập hoặc mật khẩu không đúng');
                header('Location: /webbanhang/account/login');
                exit();
            }
        }

        require_once __DIR__ . '/../views/account/login.php';
    }
   

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $fullname = $_POST['fullname'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';

            // Validate
            $errors = [];
            if (empty($username)) $errors['username'] = 'Vui lòng nhập tên đăng nhập';
            if (empty($password)) $errors['password'] = 'Vui lòng nhập mật khẩu';
            if ($password !== $confirm_password) $errors['confirm_password'] = 'Mật khẩu không khớp';
            if (empty($fullname)) $errors['fullname'] = 'Vui lòng nhập họ tên';

            // Kiểm tra username tồn tại
            if ($this->accountModel->getAccountByUsername($username)) {
                $errors['username'] = 'Tên đăng nhập đã được sử dụng';
            }

            if (empty($errors)) {
                $result = $this->accountModel->createAccount(
                    $username, 
                    $password, 
                    $fullname, 
                    $email, 
                    $phone
                );

                if ($result) {
                    SessionHelper::setFlash('success_message', 'Đăng ký thành công. Vui lòng đăng nhập');
                    header('Location: /webbanhang/account/login');
                    exit();
                } else {
                    $errors['system'] = 'Đăng ký không thành công. Vui lòng thử lại';
                }
            }

            $_SESSION['register_errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            header('Location: /webbanhang/account/register');
            exit();
        }

        require_once __DIR__ . '/../views/account/register.php';
    }

    public function logout() {
        SessionHelper::destroySession();
        SessionHelper::setFlash('success_message', 'Đăng xuất thành công');
        header('Location: /webbanhang/');
        exit();
    }

    public function profile() {
        if (!SessionHelper::isLoggedIn()) {
            header('Location: /webbanhang/account/login');
            exit();
        }

        $userId = SessionHelper::getUserId();
        $account = $this->accountModel->getAccountById($userId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = $_POST['fullname'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';

            $result = $this->accountModel->updateAccount($userId, [
                'fullname' => $fullname,
                'email' => $email,
                'phone' => $phone,
                'address' => $address
            ]);

            if ($result) {
                SessionHelper::setFlash('success_message', 'Cập nhật thông tin thành công');
                header('Location: /webbanhang/account/profile');
                exit();
            } else {
                SessionHelper::setFlash('error_message', 'Cập nhật thông tin không thành công');
            }
        }

        require_once __DIR__ . '/../views/account/profile.php';
    }

    public function orders() {
        if (!SessionHelper::isLoggedIn()) {
            header('Location: /webbanhang/account/login');
            exit();
        }

        $orderModel = new OrderModel((new Database())->getConnection());
        $orders = $orderModel->getOrdersByAccount(SessionHelper::getUserId());

        require_once __DIR__ . '/../views/account/orders.php';
    }
    
    public function orderDetail($order_id) {
        if (!SessionHelper::isLoggedIn()) {
            header('Location: /webbanhang/account/login');
            exit();
        }
    
        $orderModel = new OrderModel((new Database())->getConnection());
        
        // Lấy thông tin đơn hàng
        $order = $orderModel->getOrderById($order_id);
        
        // Kiểm tra đơn hàng có thuộc về người dùng hiện tại không
        if (!$order || $order->account_id != SessionHelper::getUserId()) {
            SessionHelper::setFlash('error_message', 'Đơn hàng không tồn tại hoặc không thuộc về bạn');
            header('Location: /webbanhang/account/orders');
            exit();
        }
    
        // Lấy chi tiết đơn hàng
        $orderDetails = $orderModel->getOrderDetails($order_id);
    
        require_once __DIR__ . '/../views/account/orderdetail.php';
    }

    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            
            if (empty($email)) {
                SessionHelper::setFlash('error_message', 'Vui lòng nhập email');
                header('Location: /webbanhang/account/forgot-password');
                exit();
            }
            
            $account = $this->accountModel->getAccountByEmail($email);
            
            if (!$account) {
                SessionHelper::setFlash('error_message', 'Email không tồn tại trong hệ thống');
                header('Location: /webbanhang/account/forgot-password');
                exit();
            }
            
            // Tạo mã OTP 6 chữ số
            $otp = rand(100000, 999999);
            $expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));
            
            // Lưu OTP vào database
            $this->accountModel->createPasswordResetToken($email, $otp, $expiry);
            
            // Gửi email
            $emailHelper = new EmailHelper();
            if ($emailHelper->sendOTP($email, $otp)) {
                SessionHelper::setFlash('success_message', 'Mã OTP đã được gửi đến email của bạn');
                $_SESSION['reset_email'] = $email;
                header('Location: /webbanhang/account/verify-otp');
                exit();
            } else {
                SessionHelper::setFlash('error_message', 'Gửi email thất bại. Vui lòng thử lại');
                header('Location: /webbanhang/account/forgot-password');
                exit();
            }
        }
        
        require_once __DIR__ . '/../views/account/forgot-password.php';
    }

    public function verifyOtp() {
        if (!isset($_SESSION['reset_email'])) {
            SessionHelper::setFlash('error_message', 'Vui lòng yêu cầu mã OTP trước');
            header('Location: /webbanhang/account/forgot-password');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $otp = $_POST['otp'] ?? '';
            $email = $_SESSION['reset_email'];
            
            $account = $this->accountModel->getAccountByResetToken($otp);
            
            if ($account && $account->email === $email) {
                $_SESSION['reset_token'] = $otp;
                header('Location: /webbanhang/account/reset-password');
                exit();
            } else {
                SessionHelper::setFlash('error_message', 'Mã OTP không hợp lệ hoặc đã hết hạn');
                header('Location: /webbanhang/account/verify-otp');
                exit();
            }
        }
        
        require_once __DIR__ . '/../views/account/verify-otp.php';
    }
    
    public function resetPassword() {
        if (!isset($_SESSION['reset_token'])) {
            SessionHelper::setFlash('error_message', 'Vui lòng xác thực OTP trước');
            header('Location: /webbanhang/account/forgot-password');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            if (empty($new_password) || empty($confirm_password)) {
                SessionHelper::setFlash('error_message', 'Vui lòng nhập đầy đủ thông tin');
                header('Location: /webbanhang/account/reset-password');
                exit();
            }
            
            if ($new_password !== $confirm_password) {
                SessionHelper::setFlash('error_message', 'Mật khẩu không khớp');
                header('Location: /webbanhang/account/reset-password');
                exit();
            }
            
            $token = $_SESSION['reset_token'];
            if ($this->accountModel->resetPassword($token, $new_password)) {
                unset($_SESSION['reset_token']);
                unset($_SESSION['reset_email']);
                SessionHelper::setFlash('success_message', 'Đặt lại mật khẩu thành công. Vui lòng đăng nhập');
                header('Location: /webbanhang/account/login');
                exit();
            } else {
                SessionHelper::setFlash('error_message', 'Đặt lại mật khẩu thất bại. Vui lòng thử lại');
                header('Location: /webbanhang/account/reset-password');
                exit();
            }
        }
        
        require_once __DIR__ . '/../views/account/reset-password.php';
    }
}