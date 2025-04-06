<?php
require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';

class ProductController {
    private $productModel;
    private $categoryModel;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->productModel = new ProductModel($db);
        $this->categoryModel = new CategoryModel($db);
    }

    public function index() {
        $limit = 10; // Số sản phẩm mỗi trang
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Lấy trang hiện tại từ query string
        $offset = ($page - 1) * $limit; // Tính toán offset

        // Lấy sản phẩm phân trang
        $search = $_GET['search'] ?? null;
        $category_id = $_GET['category'] ?? null;

        $data = $this->productModel->getPaginatedProducts($page, $limit, $category_id, $search);
        
        $products = $data['products']; // Sản phẩm phân trang
        $total = $data['total']; // Tổng số sản phẩm
        $totalPages = ceil($total / $limit); // Tính số trang

        require_once __DIR__ . '/../views/product/list.php';
    }

    // public function show($id) {
    //     $product = $this->productModel->getProductById($id);
    //     if (!$product) {
    //         SessionHelper::setFlash('error_message', 'Sản phẩm không tồn tại');
    //         header('Location: /webbanhang/product');
    //         exit();
    //     }
    //     require_once __DIR__ . '/../views/product/show.php';
    // }

    public function show($id) {
        $db = (new Database())->getConnection();
        $productModel = new ProductModel($db);
        $reviewModel = new ProductReviewModel($db);
    
        // Xử lý submit đánh giá
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
                'comment' => $_POST['comment'] ?? ''
            ];
    
            if ($reviewModel->addReview($data)) {
                SessionHelper::setFlash('success_message', 'Đánh giá thành công!');
            } else {
                SessionHelper::setFlash('error_message', 'Lỗi khi gửi đánh giá');
            }
    
            header("Location: /webbanhang/product/show/$id");
            exit;
        }
    
        // Lấy dữ liệu sản phẩm và đánh giá
        $product = $productModel->getProductById($id);
        if (!$product) {
            SessionHelper::setFlash('error_message', 'Sản phẩm không tồn tại');
            header('Location: /webbanhang/product');
            exit;
        }
    
        $reviews = $reviewModel->getReviewsByProduct($id);
        $averageRating = $reviewModel->getAverageRating($id);
    
        require_once __DIR__ . '/../views/product/show.php';
    }

    public function add() {
        if (!SessionHelper::isAdmin()) {
            SessionHelper::setFlash('error_message', 'Bạn không có quyền truy cập');
            header('Location: /webbanhang/');
            exit();
        }

        $categories = $this->categoryModel->getCategories();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? '',
                'price' => $_POST['price'] ?? 0,
                'category_id' => $_POST['category_id'] ?? null,
                'image' => $this->handleImageUpload()
            ];

            $result = $this->productModel->addProduct($data);

            if ($result) {
                SessionHelper::setFlash('success_message', 'Thêm sản phẩm thành công');
                header('Location: /webbanhang/product');
                exit();
            } else {
                SessionHelper::setFlash('error_message', 'Lỗi khi thêm sản phẩm');
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? '',
                'price' => $_POST['price'] ?? 0,
                'category_id' => $_POST['category_id'] ?? null,
                'image' => $this->handleImageUpload() ?? $_POST['existing_image'] ?? null
            ];

            $result = $this->productModel->updateProduct($id, $data);

            if ($result) {
                SessionHelper::setFlash('success_message', 'Cập nhật sản phẩm thành công');
                header('Location: /webbanhang/product');
                exit();
            } else {
                SessionHelper::setFlash('error_message', 'Cập nhật sản phẩm không thành công');
            }
        }

        require_once __DIR__ . '/../views/product/edit.php';
    }

    public function delete($id) {
        if (!SessionHelper::isAdmin()) {
            SessionHelper::setFlash('error_message', 'Bạn không có quyền truy cập');
            header('Location: /webbanhang/');
            exit();
        }

        $result = $this->productModel->deleteProduct($id);
        if ($result) {
            SessionHelper::setFlash('success_message', 'Xóa sản phẩm thành công');
        } else {
            SessionHelper::setFlash('error_message', 'Xóa sản phẩm không thành công');
        }
        header('Location: /webbanhang/product');
        exit();
    }

    private function handleImageUpload() {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $targetDir = __DIR__ . '/../../public/uploads/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
            $targetFile = $targetDir . $fileName;

            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $allowedTypes)) {
                SessionHelper::setFlash('error_message', 'Chỉ chấp nhận file ảnh JPG, JPEG, PNG hoặc GIF');
                return null;
            }

            if ($_FILES['image']['size'] > 5000000) {
                SessionHelper::setFlash('error_message', 'File ảnh quá lớn (tối đa 5MB)');
                return null;
            }

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                return '/webbanhang/uploads/' . $fileName;
            }
        }
        return null;
    }
}
