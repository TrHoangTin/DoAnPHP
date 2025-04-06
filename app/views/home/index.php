<?php include __DIR__ . '/../shares/header.php'; ?>

<div class="container">
    <h1 class="my-4">Trang chủ</h1>

    <!-- Thanh tìm kiếm -->
    <form action="/webbanhang" method="get" class="mb-4">
        <div class="input-group">
            <input type="text" class="form-control" name="search" placeholder="Tìm kiếm sản phẩm..." value="<?= htmlspecialchars($search ?? '', ENT_QUOTES) ?>">
            <button class="btn btn-primary" type="submit">
                <i class="fas fa-search"></i> Tìm kiếm
            </button>
        </div>
    </form>

    <!-- Hiển thị sản phẩm nổi bật -->
    <?php if (!empty($featuredProducts)): ?>
    <section class="mb-5">
        <h2 class="mb-4">⭐ Sản phẩm nổi bật</h2>
        <div class="row g-4">
            <?php foreach ($featuredProducts as $product): ?>
            <div class="col-md-3">
                <div class="card h-100 shadow-sm">
                    <img src="<?= $product->image ?? '/webbanhang/assets/img/no-image.jpg' ?>" class="card-img-top p-2" alt="<?= htmlspecialchars($product->name) ?>" style="height: 200px; object-fit: contain;">
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

    <!-- Hiển thị sản phẩm mới -->
    <?php if (!empty($newProducts)): ?>
    <section class="mb-5">
        <h2 class="mb-4">🆕 Sản phẩm mới</h2>
        <div class="row g-4">
            <?php foreach ($newProducts as $product): ?>
            <div class="col-md-3">
                <div class="card h-100 shadow-sm">
                    <img src="<?= $product->image ?? '/webbanhang/assets/img/no-image.jpg' ?>" class="card-img-top p-2" alt="<?= htmlspecialchars($product->name) ?>" style="height: 200px; object-fit: contain;">
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

    <!-- Phân trang chung cho cả hai mục -->
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= max($totalPagesFeatured, $totalPagesNew); $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="/webbanhang?page=<?= $i ?>&search=<?= htmlspecialchars($search ?? '', ENT_QUOTES) ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<?php include __DIR__ . '/../shares/footer.php'; ?>
