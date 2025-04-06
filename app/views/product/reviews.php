<?php 
$title = "Đánh giá sản phẩm: " . htmlspecialchars($product->name);
include __DIR__ . '/../shares/header.php'; 
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/webbanhang">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="/webbanhang/product">Sản phẩm</a></li>
                    <li class="breadcrumb-item"><a href="/webbanhang/product/show/<?= $product->id ?>"><?= htmlspecialchars($product->name) ?></a></li>
                    <li class="breadcrumb-item active">Đánh giá</li>
                </ol>
            </nav>

            <!-- Thông tin sản phẩm -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <img src="<?= !empty($product->image) ? '/webbanhang/' . ltrim($product->image, '/') : '/webbanhang/public/images/no-image.jpg' ?>" 
                                 width="80" 
                                 alt="<?= htmlspecialchars($product->name) ?>"
                                 class="rounded border">
                        </div>
                        <div>
                            <h5 class="mb-1"><?= htmlspecialchars($product->name) ?></h5>
                            <!-- <div class="text-warning mb-1">
                                <?= str_repeat('<i class="fas fa-star"></i>', round($ratingInfo['average'])) ?>
                                <?= str_repeat('<i class="far fa-star"></i>', 5 - round($ratingInfo['average'])) ?>
                                <span class="ms-2 text-dark">(<?= $ratingInfo['count'] ?> đánh giá)</span>
                            </div> -->
                            <div class="text-warning mb-1">
    <?php if(isset($ratingInfo)): ?>
        <?= str_repeat('<i class="fas fa-star"></i>', round($ratingInfo['average'])) ?>
        <?= str_repeat('<i class="far fa-star"></i>', 5 - round($ratingInfo['average'])) ?>
        <span class="ms-2 text-dark">(<?= $ratingInfo['count'] ?> đánh giá)</span>
    <?php else: ?>
        <span class="text-dark">Chưa có đánh giá</span>
    <?php endif; ?>
</div>
                            <p class="text-danger fw-bold mb-0"><?= number_format($product->price, 0, ',', '.') ?>₫</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form đánh giá -->
            <?php if (SessionHelper::isLoggedIn() && !$userReview): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Viết đánh giá của bạn</h5>
                        <form id="reviewForm" method="POST" action="/webbanhang/api/products/<?= $product->id ?>/reviews">
                            <div class="mb-3">
                                <label class="form-label">Đánh giá</label>
                                <div class="rating-stars">
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" <?= $i == 5 ? 'checked' : '' ?>>
                                        <label for="star<?= $i ?>"><i class="fas fa-star"></i></label>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="comment" class="form-label">Nhận xét chi tiết</label>
                                <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                        </form>
                    </div>
                </div>
            <?php elseif ($userReview): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    Bạn đã đánh giá sản phẩm này <?= $userReview->rating ?> sao
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <a href="/webbanhang/account/login?return=<?= urlencode($_SERVER['REQUEST_URI']) ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập để đánh giá
                    </a>
                </div>
            <?php endif; ?>

            <!-- Danh sách đánh giá -->
            <div class="reviews-list">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">Tất cả đánh giá</h4>
                    <div class="text-warning">
                        <?= str_repeat('<i class="fas fa-star"></i>', round($ratingInfo['average'])) ?>
                        <?= str_repeat('<i class="far fa-star"></i>', 5 - round($ratingInfo['average'])) ?>
                        <span class="text-dark ms-2"><?= number_format($ratingInfo['average'], 1) ?> trên 5 (<?= $ratingInfo['count'] ?> đánh giá)</span>
                    </div>
                </div>
                
                <?php if (empty($reviews)): ?>
                    <div class="alert alert-warning">Chưa có đánh giá nào cho sản phẩm này</div>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <strong><?= htmlspecialchars($review->fullname ?? $review->username) ?></strong>
                                        <div class="text-warning">
                                            <?= str_repeat('<i class="fas fa-star"></i>', $review->rating) ?>
                                            <?= str_repeat('<i class="far fa-star"></i>', 5 - $review->rating) ?>
                                        </div>
                                    </div>
                                    <small class="text-muted"><?= date('d/m/Y H:i', strtotime($review->created_at)) ?></small>
                                </div>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($review->comment)) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Xử lý form đánh giá bằng AJAX
    $('#reviewForm').submit(function(e) {
        e.preventDefault();
        
        const formData = {
            rating: $('input[name="rating"]:checked').val(),
            comment: $('#comment').val()
        };
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message || 'Có lỗi xảy ra');
                }
            },
            error: function() {
                alert('Có lỗi xảy ra khi gửi đánh giá');
            }
        });
    });
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
</style>

<?php include __DIR__ . '/../shares/footer.php'; ?>