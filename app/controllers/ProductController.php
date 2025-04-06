<?php
require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';
require_once __DIR__ . '/../models/ProductReviewModel.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';

class ProductController {
    private $productModel;
    private $categoryModel;
    private $reviewModel;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->productModel = new ProductModel($db);
        $this->categoryModel = new CategoryModel($db);
        $this->reviewModel = new ProductReviewModel($db);
    }

    public function index() {
        $limit = 10;
        $page = (int)($_GET['page'] ?? 1);
        $offset = ($page - 1) * $limit;

        $search = $_GET['search'] ?? null;
        $category_id = $_GET['category'] ?? null;

        $data = $this->productModel->getPaginatedProducts($page, $limit, $category_id, $search);
        
        $products = $data['products'];
        $total = $data['total'];
        $totalPages = ceil($total / $limit);

        require_once __DIR__ . '/../views/product/list.php';
    }

    public function show($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'])) {
            if (!SessionHelper::isLoggedIn()) {
                SessionHelper::setFlash('error_message', 'Vui lòng đăng nhập để đánh giá');
                header("Location: /webbanhang/account/login?return=" . urlencode($_SERVER['REQUEST_URI']));
                exit;
            }

            $data = [
                'product_id' => $id,
                'account_id' => SessionHelper::getUserId(),
                'rating' => (int)$_POST['rating'],
                'comment' => trim($_POST['comment'] ?? '')
            ];

            if ($this->reviewModel->addReview($data)) {
                SessionHelper::setFlash('success_message', 'Đánh giá thành công!');
            } else {
                SessionHelper::setFlash('error_message', 'Lỗi khi gửi đánh giá');
            }
            header("Location: /webbanhang/product/show/$id");
            exit;
        }

        $product = $this->productModel->getProductById($id);
        if (!$product) {
            SessionHelper::setFlash('error_message', 'Sản phẩm không tồn tại');
            header('Location: /webbanhang/product');
            exit;
        }

        $reviews = $this->reviewModel->getReviewsByProduct($id);
        $averageRating = $this->reviewModel->getAverageRating($id);

        require_once __DIR__ . '/../views/product/show.php';
    }

    public function add() {
        if (!SessionHelper::isAdmin()) {
            SessionHelper::setFlash('error_message', 'Bạn không có quyền truy cập');
            header('Location: /webbanhang/');
            exit();
        }

        $categories = $this->categoryModel->getCategories();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'price' => (float)($_POST['price'] ?? 0),
                'category_id' => !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null,
                'image' => $this->handleImageUpload()
            ];

            // Validate dữ liệu
            if (empty($data['name'])) {
                $errors[] = 'Tên sản phẩm không được để trống';
            }
            
            if ($data['price'] <= 0) {
                $errors[] = 'Giá sản phẩm phải lớn hơn 0';
            }

            if (empty($errors)) {
                if ($this->productModel->addProduct($data)) {
                    SessionHelper::setFlash('success_message', 'Thêm sản phẩm thành công');
                    header('Location: /webbanhang/product');
                    exit();
                }
                $errors[] = 'Lỗi khi thêm sản phẩm';
            }
        }

        require_once __DIR__ . '/../views/product/add.php';
    }

    public function edit($id) {
        if (!SessionHelper::isAdmin()) {
            SessionHelper::setFlash('error_message', 'Bạn không có quyền truy cập');
            header('Location: /webbanhang/');
            exit();
        }

        $product = $this->productModel->getProductById($id);
        if (!$product) {
            SessionHelper::setFlash('error_message', 'Sản phẩm không tồn tại');
            header('Location: /webbanhang/product');
            exit();
        }

        $categories = $this->categoryModel->getCategories();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'price' => (float)($_POST['price'] ?? 0),
                'category_id' => !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null,
                'image' => $product->image
            ];

            if (empty($data['name'])) {
                $errors[] = 'Tên sản phẩm không được để trống';
            }

            if ($data['price'] < 0) {
                $errors[] = 'Giá sản phẩm không hợp lệ';
            }

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $data['image'] = $this->handleImageUpload();
                if ($data['image'] && !empty($product->image)) {
                    $oldImagePath = $this->getImagePath($product->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            }

            if (empty($errors) && $this->productModel->updateProduct($id, $data)) {
                SessionHelper::setFlash('success_message', 'Cập nhật sản phẩm thành công');
                header('Location: /webbanhang/product');
                exit();
            }
            $errors[] = 'Cập nhật sản phẩm không thành công';
        }

        require_once __DIR__ . '/../views/product/edit.php';
    }

    public function delete($id) {
        if (!SessionHelper::isAdmin()) {
            SessionHelper::setFlash('error_message', 'Bạn không có quyền truy cập');
            header('Location: /webbanhang/');
            exit();
        }

        $product = $this->productModel->getProductById($id);
        if (!$product) {
            SessionHelper::setFlash('error_message', 'Sản phẩm không tồn tại');
            header('Location: /webbanhang/product');
            exit();
        }

        if ($this->productModel->deleteProduct($id)) {
            if (!empty($product->image)) {
                $imagePath = $this->getImagePath($product->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            SessionHelper::setFlash('success_message', 'Xóa sản phẩm thành công');
        } else {
            SessionHelper::setFlash('error_message', 'Xóa sản phẩm không thành công');
        }
        header('Location: /webbanhang/product');
        exit();
    }

    // public function reviews($productId) {
    //     $product = $this->productModel->getProductById($productId);
    //     if (!$product) {
    //         SessionHelper::setFlash('error_message', 'Sản phẩm không tồn tại');
    //         header('Location: /webbanhang/product');
    //         exit;
    //     }

    //     $reviews = $this->reviewModel->getReviewsByProduct($productId);
    //     $averageRating = $this->reviewModel->getAverageRating($productId);
    //     $userReview = SessionHelper::isLoggedIn() 
    //         ? $this->reviewModel->getUserReview($productId, SessionHelper::getUserId())
    //         : null;

    //     require_once __DIR__ . '/../views/product/reviews.php';
    // }

    public function reviews($productId) {
        $product = $this->productModel->getProductById($productId);
        if (!$product) {
            SessionHelper::setFlash('error_message', 'Sản phẩm không tồn tại');
            header('Location: /webbanhang/product');
            exit;
        }
    
        $reviews = $this->reviewModel->getReviewsByProduct($productId);
        $ratingInfo = $this->reviewModel->getAverageRating($productId);
        $userReview = SessionHelper::isLoggedIn() 
            ? $this->reviewModel->getUserReview($productId, SessionHelper::getUserId())
            : null;
    
        // Đảm bảo truyền đúng các biến vào view
        require_once __DIR__ . '/../views/product/reviews.php';
    }
    private function handleImageUpload() {
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $targetDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $fileInfo = pathinfo($_FILES['image']['name']);
        $fileExt = strtolower($fileInfo['extension'] ?? '');
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($fileExt, $allowedTypes)) {
            SessionHelper::setFlash('error_message', 'Chỉ chấp nhận file ảnh JPG, JPEG, PNG hoặc GIF');
            return null;
        }

        if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
            SessionHelper::setFlash('error_message', 'File ảnh quá lớn (tối đa 5MB)');
            return null;
        }

        $fileName = uniqid() . '_' . $fileInfo['filename'] . '.' . $fileExt;
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            return '/webbanhang/uploads/' . $fileName;
        }

        SessionHelper::setFlash('error_message', 'Có lỗi khi upload ảnh');
        return null;
    }

    private function getImagePath($imageUrl) {
        return __DIR__ . '/../../public' . parse_url($imageUrl, PHP_URL_PATH);
    }
}