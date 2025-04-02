<?php include __DIR__ . '/../shares/header.php'; ?>

<div class="container mt-4">
    <a href="/webbanhang/product" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Quay lại danh sách
    </a>
    
    <div class="card mb-4">
        <div class="row g-0">
            <!-- Phần hiển thị ảnh -->
            <?php if (!empty($product->image)): ?>
                <?php 
                // Xử lý đường dẫn ảnh
                $imagePath = $product->image;
                // Thêm /webbanhang nếu chưa có ở đầu đường dẫn
                if (strpos($imagePath, '/webbanhang/') !== 0) {
                    $imagePath = '/webbanhang' . (strpos($imagePath, '/') === 0 ? $imagePath : '/' . $imagePath);
                }
                ?>
                <div class="col-md-5">
                    <img src="<?= $imagePath ?>" 
                         class="img-fluid rounded-start" 
                         alt="<?= htmlspecialchars($product->name) ?>"
                         style="max-height: 500px; object-fit: contain;"
                         onerror="this.onerror=null;this.src='/webbanhang/assets/img/no-image.jpg';">
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
                            <h5 class="text-muted" >Mô tả sản phẩm</h5>
                            <p class="card-text"><?= nl2br(htmlspecialchars($product->description)) ?></p>
                        </div>
                        
                        <!-- <div class="d-flex flex-wrap gap-2 mb-4">
                            <div class="border p-2 rounded">
                                <small class="text-muted">Danh mục</small>
                                <div><?= htmlspecialchars($product->category_name ?? 'Không có') ?></div>
                            </div>
                            <div class="border p-2 rounded">
                                <small class="text-muted">Mã sản phẩm</small>
                                <div>#<?= $product->id ?></div>
                            </div>
                        </div> -->
                        
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
</div>

<?php include __DIR__ . '/../shares/footer.php'; ?>