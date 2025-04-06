<?php 
$title = "Chi tiết sản phẩm";
include __DIR__ . '/../shares/header.php'; 

// Khởi tạo kết nối database và model
$db = (new Database())->getConnection();
$productModel = new ProductModel($db);
$reviewModel = new ProductReviewModel($db);

// Lấy thông tin sản phẩm
$product = $productModel->getProductById($id);
if (!$product) {
    SessionHelper::setFlash('error_message', 'Sản phẩm không tồn tại');
    header('Location: /webbanhang/product');
    exit();
}

// Lấy thông tin đánh giá
$ratingInfo = $reviewModel->getAverageRating($product->id);
$averageRating = $ratingInfo['average'] ?? 0;
$reviewCount = $ratingInfo['count'] ?? 0;
$reviews = $reviewModel->getReviewsByProduct($product->id);

// Kiểm tra nếu người dùng đã đăng nhập
$userReview = null;
if (SessionHelper::isLoggedIn()) {
    $userReview = $reviewModel->getUserReview($product->id, SessionHelper::getUserId());
}
?>

<div class="container mt-4">
    <!-- Nút quay lại -->
    <a href="/webbanhang/product" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Quay lại danh sách
    </a>
    
    <!-- Thông tin sản phẩm -->
    <div class="card mb-4">
        <div class="row g-0">
            <?php if (!empty($product->image)): ?>
                <div class="col-md-5">
                    <img src="<?= strpos($product->image, '/webbanhang/') === 0 ? $product->image : '/webbanhang/' . ltrim($product->image, '/') ?>" 
                         class="img-fluid rounded-start" 
                         alt="<?= htmlspecialchars($product->name) ?>"
                         style="max-height: 500px; object-fit: contain;"
                         onerror="this.onerror=null;this.src='/webbanhang/public/images/no-image.jpg';">
                </div>
                <div class="col-md-7">
            <?php else: ?>
                <div class="col-md-12">
            <?php endif; ?>
                    <div class="card-body">
                        <h1 class="card-title"><?= htmlspecialchars($product->name) ?></h1>
                        
                        <div class="d-flex align-items-center mb-3">
                            <span class="text-danger fw-bold fs-3 me-3">
                                <?= number_format($product->price, 0, ',', '.') ?>₫
                            </span>
                            <?php if ($product->price > 5000000): ?>
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-star"></i> Nổi bật
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-muted">Mô tả sản phẩm</h5>
                            <p class="card-text"><?= nl2br(htmlspecialchars($product->description)) ?></p>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <?php if (SessionHelper::isAdmin()): ?>
                                <a href="/webbanhang/product/edit/<?= $product->id ?>" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Chỉnh sửa
                                </a>
                                <a href="/webbanhang/product/delete/<?= $product->id ?>" 
                                   class="btn btn-danger"
                                   onclick="return confirm('Bạn chắc chắn muốn xóa sản phẩm này?')">
                                    <i class="fas fa-trash"></i> Xóa
                                </a>
                            <?php else: ?>
                                <a href="/webbanhang/cart/add/<?= $product->id ?>" class="btn btn-success btn-lg">
                                    <i class="fas fa-cart-plus"></i> Thêm vào giỏ hàng
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    
    <!-- Phần đánh giá sản phẩm -->
    <div class="mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
            <h3>Đánh giá sản phẩm</h3>
            <div class="d-flex align-items-center">
                <div class="text-warning fs-4 me-2">
                    <?= str_repeat('<i class="fas fa-star"></i>', round($averageRating)) ?>
                    <?= str_repeat('<i class="far fa-star"></i>', 5 - round($averageRating)) ?>
                </div>
                <span class="text-dark fs-6">
                    <?= number_format($averageRating, 1) ?> (<?= $reviewCount ?> đánh giá)
                </span>
            </div>
        </div>
        
        <!-- Form đánh giá hoặc thông báo -->
        <div class="mb-3">
            <?php if(SessionHelper::isLoggedIn()): ?>
                <?php if ($userReview): ?>
                    <div class="alert alert-success py-2 mb-3">
                        <i class="fas fa-check-circle me-2"></i>
                        Bạn đã đánh giá sản phẩm này <?= $userReview->rating ?> sao
                        <a href="/webbanhang/product/<?= $product->id ?>/reviews" class="float-end">
                            Xem chi tiết <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                <?php else: ?>
                    <form method="POST" action="/webbanhang/product/show/<?= $product->id ?>">
                        <div class="mb-3">
                            <label class="form-label">Đánh giá của bạn</label>
                            <div class="rating-stars">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" <?= $i == 5 ? 'checked' : '' ?>>
                                    <label for="star<?= $i ?>"><i class="fas fa-star"></i></label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="comment" class="form-label">Nhận xét</label>
                            <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                    </form>
                <?php endif; ?>
            <?php else: ?>
                <a href="/webbanhang/account/login?return=<?= urlencode($_SERVER['REQUEST_URI']) ?>" 
                   class="btn btn-primary">
                    <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập để đánh giá
                </a>
            <?php endif; ?>
                
            <a href="/webbanhang/product/<?= $product->id ?>/reviews" class="btn btn-outline-primary mt-2">
                <i class="fas fa-list me-2"></i>Xem tất cả đánh giá
            </a>
        </div>
        
        <!-- Danh sách đánh giá -->
        <?php if (!empty($reviews)): ?>
            <div class="row g-3">
                <?php foreach (array_slice($reviews, 0, 3) as $review): ?>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <?php if (isset($review->avatar) && !empty($review->avatar)): ?>
                                        <img src="<?= $review->avatar ?>" 
                                             class="rounded-circle me-2" 
                                             width="40" 
                                             alt="<?= htmlspecialchars($review->fullname ?? $review->username) ?>">
                                    <?php else: ?>
                                        <div class="avatar-placeholder rounded-circle me-2" style="width:40px;height:40px;background:#ccc;display:flex;align-items:center;justify-content:center">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <strong><?= htmlspecialchars($review->fullname ?? $review->username) ?></strong>
                                        <div class="text-warning">
                                            <?= str_repeat('<i class="fas fa-star"></i>', $review->rating) ?>
                                            <?= str_repeat('<i class="far fa-star"></i>', 5 - $review->rating) ?>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-2"><?= nl2br(htmlspecialchars($review->comment)) ?></p>
                                <small class="text-muted">
                                    <?= date('d/m/Y H:i', strtotime($review->created_at)) ?>
                                </small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">Chưa có đánh giá nào cho sản phẩm này</div>
        <?php endif; ?>
    </div>
</div>
<script>
$('#reviewForm').submit(async function(e) {
    e.preventDefault();
    
    try {
        const token = localStorage.getItem('auth_token');
        if (!token) {
            window.location.href = '/webbanhang/account/login?return=' + encodeURIComponent(window.location.pathname);
            return;
        }

        const response = await fetch(`/api/products/<?= $product->id ?>/reviews`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json' // Yêu cầu server trả về JSON
            },
            body: JSON.stringify({
                rating: $('input[name="rating"]:checked').val(),
                comment: $('#comment').val()
            })
        });

        // Kiểm tra content-type trước khi parse JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            throw new Error(`Server trả về không phải JSON: ${text.substring(0, 100)}...`);
        }

        const data = await response.json();

        if (!response.ok) {
            // Xử lý lỗi từ API
            if (response.status === 401) {
                localStorage.removeItem('auth_token');
                window.location.href = '/webbanhang/account/login';
                return;
            }
            throw new Error(data.message || `Lỗi HTTP: ${response.status}`);
        }

        toastr.success(data.message || 'Đánh giá thành công!');
        setTimeout(() => location.reload(), 1500);

    } catch (error) {
        console.error('Review error:', error);
        toastr.error(error.message || 'Có lỗi xảy ra khi gửi đánh giá');
    }
});

</script>
<style>
.rating-stars {
    direction: rtl;
    unicode-bidi: bidi-override;
    display: inline-block;
}
.rating-stars input {
    display: none;
}
.rating-stars label {
    font-size: 24px;
    color: #ddd;
    cursor: pointer;
    margin-right: 5px;
}
.rating-stars input:checked ~ label,
.rating-stars label:hover,
.rating-stars label:hover ~ label {
    color: #ffc107;
}
.avatar-placeholder {
    background-color: #f0f0f0;
}
</style>

<?php include __DIR__ . '/../shares/footer.php'; ?>