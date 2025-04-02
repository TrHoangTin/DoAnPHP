<!-- <?php include __DIR__ . '/../shares/header.php'; ?>

<div class="container mt-4">
    <a href="/WEBBANHANG/category" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
    
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3><?= htmlspecialchars($category->name) ?></h3>
        </div>
        <div class="card-body">
            <p><?= nl2br(htmlspecialchars($category->description ?? 'Không có mô tả')) ?></p>
            
            <h5 class="mt-4">Sản phẩm trong danh mục (<?= count($products) ?>)</h5>
            <?php if (!empty($products)): ?>
                <div class="row row-cols-1 row-cols-md-3 g-4 mt-3">
                    <?php foreach ($products as $product): ?>
                    <div class="col">
                        <div class="card h-100">
                            <?php if (!empty($product->image)): ?>
                                <img src="<?= strpos($product->image, '/') === 0 ? $product->image : '/uploads/'.$product->image ?>" 
                                     class="card-img-top p-2" 
                                     style="height: 180px; object-fit: contain;"
                                     alt="<?= htmlspecialchars($product->name) ?>">
                            <?php else: ?>
                                <img src="/assets/img/no-image.jpg" 
                                     class="card-img-top p-2"
                                     style="height: 180px; object-fit: contain;"
                                     alt="Không có ảnh">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($product->name) ?></h5>
                                <p class="text-danger fw-bold"><?= number_format($product->price, 0, ',', '.') ?>₫</p>
                            </div>
                            <div class="card-footer bg-white">
                                <a href="/WEBBANHANG/product/show/<?= $product->id ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info">Chưa có sản phẩm nào trong danh mục này</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../shares/footer.php'; ?> -->