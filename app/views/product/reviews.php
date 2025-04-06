<?php 
$title = "Đánh giá sản phẩm";
include __DIR__ . '/../shares/header.php'; 
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Đánh giá sản phẩm</h2>
                <a href="/webbanhang/product/show/<?= $productId ?>" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại sản phẩm
                </a>
            </div>
            
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <img src="<?= $product->image ? '/webbanhang/public/uploads/'.$product->image : '/webbanhang/public/images/no-image.jpg' ?>" 
                                 class="rounded" 
                                 width="80"
                                 alt="<?= htmlspecialchars($product->name) ?>">
                        </div>
                        <div>
                            <h5 class="mb-1"><?= htmlspecialchars($product->name) ?></h5>
                            <div class="text-warning mb-1">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star<?= $i <= $averageRating ? '' : '-empty' ?>"></i>
                                <?php endfor; ?>
                                <span class="ms-2 text-dark">(<?= $reviewCount ?> đánh giá)</span>
                            </div>
                            <p class="text-danger fw-bold mb-0"><?= number_format($product->price, 0, ',', '.') ?>₫</p>
                        </div>
                    </div>
                    
                    <?php if (SessionHelper::isLoggedIn()): ?>
                        <form id="reviewForm" class="mt-4">
                            <h5 class="mb-3">Viết đánh giá của bạn</h5>
                            
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
                                <label for="comment" class="form-label">Nhận xét</label>
                                <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-info">
                            Vui lòng <a href="/webbanhang/account/login">đăng nhập</a> để đánh giá sản phẩm
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="reviews-list">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">Đánh giá từ khách hàng</h4>
                    <div class="text-warning">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star<?= $i <= round($averageRating) ? '' : '-empty' ?>"></i>
                        <?php endfor; ?>
                        <span class="text-dark ms-2"><?= number_format($averageRating, 1) ?> trên 5 (<?= $reviewCount ?> đánh giá)</span>
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
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star<?= $i <= $review->rating ? '' : '-empty' ?>"></i>
                                            <?php endfor; ?>
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
    $('#reviewForm').submit(function(e) {
        e.preventDefault();
        
        const formData = {
            rating: $('input[name="rating"]:checked').val(),
            comment: $('#comment').val()
        };
        
        $.ajax({
            url: '/webbanhang/api/products/<?= $productId ?>/reviews',
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

<?php include __DIR__ . '/../shares/footer.php'; ?>