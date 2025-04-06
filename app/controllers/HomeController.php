<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';

class HomeController {
    private $productModel;
    private $categoryModel;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->productModel = new ProductModel($db);
        $this->categoryModel = new CategoryModel($db);
    }

    public function index() {
        $search = $_GET['search'] ?? ''; // Lấy từ khóa tìm kiếm
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Lấy số trang hiện tại
        $limit = 8; // Mỗi trang sẽ hiển thị 8 sản phẩm

        // Lấy các sản phẩm nổi bật và sản phẩm mới, bao gồm tìm kiếm nếu có
        $dataFeatured = $this->productModel->getPaginatedProducts($page, $limit, null, $search);
        $dataNew = $this->productModel->getPaginatedProducts($page, $limit, null, $search);

        $featuredProducts = $dataFeatured['products']; // Sản phẩm nổi bật
        $newProducts = $dataNew['products']; // Sản phẩm mới
        $totalFeatured = $dataFeatured['total']; // Tổng sản phẩm nổi bật
        $totalNew = $dataNew['total']; // Tổng sản phẩm mới

        // Tính số trang cho sản phẩm nổi bật và mới
        $totalPagesFeatured = ceil($totalFeatured / $limit);
        $totalPagesNew = ceil($totalNew / $limit);

        $categories = $this->categoryModel->getCategoriesWithCount();

        $viewPath = __DIR__ . '/../views/home/index.php';
        require_once $viewPath;
    }

    public function searchSuggestions() {
        $query = $_GET['query'] ?? '';
        if (strlen($query) > 2) {
            $products = $this->productModel->searchProducts($query);
            echo json_encode($products);
        }
    }
}
