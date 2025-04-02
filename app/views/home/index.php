<?php include __DIR__ . '/../shares/header.php'; ?>

<div class="container">
    <h1 class="my-4">Trang chủ</h1>
    
    <?php if (!empty($featuredProducts)): ?>
    <section class="mb-5">
        <h2 class="mb-4">⭐ Sản phẩm nổi bật</h2>
        <div class="row g-4">
            <?php foreach ($featuredProducts as $product): ?>
            <div class="col-md-3">
                <div class="card h-100 shadow-sm">
                    <!-- Phần hiển thị ảnh sản phẩm -->
                    <?php if (!empty($product->image)): ?>
                        <?php 
                        $imagePath = strpos($product->image, '/webbanhang/') === 0 
                            ? $product->image 
                            : '/webbanhang/' . ltrim($product->image, '/');
                        ?>
                        <img src="<?= $imagePath ?>" 
                             class="card-img-top p-2" 
                             alt="<?= htmlspecialchars($product->name) ?>"
                             style="height: 200px; object-fit: contain;"
                             onerror="this.onerror=null;this.src='/webbanhang/assets/img/no-image.jpg';">
                    <?php else: ?>
                        <img src="/webbanhang/assets/img/no-image.jpg" 
                             class="card-img-top p-2"
                             alt="Không có ảnh"
                             style="height: 200px; object-fit: contain;">
                    <?php endif; ?>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($product->name) ?></h5>
                        <p class="text-danger fw-bold mt-auto"><?= number_format($product->price, 0, ',', '.') ?>₫</p>
                        
                        <div class="d-grid gap-2 mt-2">
                            <a href="/webbanhang/product/show/<?= $product->id ?>" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i> Xem chi tiết
                            </a>
                            <a href="/webbanhang/cart/add/<?= $product->id ?>" class="btn btn-success btn-sm">
                                <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($newProducts)): ?>
<section class="mb-5">
    <h2 class="mb-4">🆕 Sản phẩm mới</h2>
    <div class="row g-4">
        <?php foreach ($newProducts as $product): ?>
        <div class="col-md-3">
            <div class="card h-100 shadow-sm">
                <?php if (!empty($product->image)): ?>
                    <?php 
                    $imagePath = strpos($product->image, '/webbanhang/') === 0 
                        ? $product->image 
                        : '/webbanhang/' . ltrim($product->image, '/');
                    ?>
                    <img src="<?= $imagePath ?>" 
                         class="card-img-top p-2" 
                         alt="<?= htmlspecialchars($product->name) ?>"
                         style="height: 200px; object-fit: contain;"
                         onerror="this.onerror=null;this.src='/webbanhang/assets/img/no-image.jpg';">
                <?php else: ?>
                    <img src="/webbanhang/assets/img/no-image.jpg" 
                         class="card-img-top p-2"
                         alt="Không có ảnh"
                         style="height: 200px; object-fit: contain;">
                <?php endif; ?>
                
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($product->name) ?></h5>
                    <p class="text-danger fw-bold mt-auto"><?= number_format($product->price, 0, ',', '.') ?>₫</p>
                    
                    <div class="d-grid gap-2 mt-2">
                        <a href="/webbanhang/product/show/<?= $product->id ?>" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i> Xem chi tiết
                        </a>
                        <a href="/webbanhang/cart/add/<?= $product->id ?>" class="btn btn-success btn-sm">
                            <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
</div>

<?php include __DIR__ . '/../shares/footer.php'; ?>