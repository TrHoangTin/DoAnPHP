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
        $search = $_GET['search'] ?? ''; 
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
        $limit = 8; 
        $dataFeatured = $this->productModel->getPaginatedProducts($page, $limit, null, $search);
        $dataNew = $this->productModel->getPaginatedProducts($page, $limit, null, $search);

        $featuredProducts = $dataFeatured['products']; 
        $newProducts = $dataNew['products'];
        $totalFeatured = $dataFeatured['total']; 
        $totalNew = $dataNew['total'];
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
