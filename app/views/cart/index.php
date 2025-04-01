<?php include(__DIR__ . '/../shares/header.php'); ?>

<div class="container mt-4">
    <h1 class="mb-4">🛒 Giỏ hàng của bạn</h1>

    <?php if (SessionHelper::getFlash('error_message')): ?>
        <div class="alert alert-danger">
            <?= SessionHelper::getFlash('error_message') ?>
        </div>
    <?php endif; ?>

    <?php if (SessionHelper::getFlash('success_message')): ?>
        <div class="alert alert-success">
            <?= SessionHelper::getFlash('success_message') ?>
        </div>
    <?php endif; ?>

    <?php if (empty($products)): ?>
        <div class="alert alert-info">
            Giỏ hàng của bạn đang trống. <a href="/webbanhang/product">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <form action="/webbanhang/cart/update" method="post">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 100px;">Ảnh</th>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                                <?php if (!empty($product->image)): ?>
                                    <?php 
                                    $imagePath = strpos($product->image, '/webbanhang/') === 0 
                                        ? $product->image 
                                        : '/webbanhang/' . ltrim($product->image, '/');
                                    ?>
                                    <img src="<?= $imagePath ?>" 
                                         alt="<?= htmlspecialchars($product->name) ?>"
                                         class="img-thumbnail"
                                         style="max-width: 80px; height: auto;"
                                         onerror="this.onerror=null;this.src='/webbanhang/assets/img/no-image.jpg';">
                                <?php else: ?>
                                    <img src="/webbanhang/assets/img/no-image.jpg" 
                                         alt="Không có ảnh"
                                         class="img-thumbnail"
                                         style="max-width: 80px; height: auto;">
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($product->name) ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($product->category_name ?? 'Không có danh mục') ?></small>
                            </td>
                            <td><?= number_format($product->price, 0, ',', '.') ?>₫</td>
                            <td style="width: 100px;">
                                <input type="number" name="quantity[<?= $product->id ?>]"
                                       value="<?= $product->quantity ?>"
                                       min="1" class="form-control text-center">
                            </td>
                            <td><?= number_format($product->subtotal, 0, ',', '.') ?>₫</td>
                            <td>
                                <a href="/webbanhang/cart/remove/<?= $product->id ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')">
                                    <i class="fas fa-trash"></i> Xóa
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="table-active">
                        <td colspan="3" class="text-end fw-bold">Tổng cộng:</td>
                        <td class="text-center fw-bold"><?= array_sum(array_column($products, 'quantity')) ?></td>
                        <td colspan="2" class="fw-bold"><?= number_format($total, 0, ',', '.') ?>₫</td>
                    </tr>
                </tbody>
            </table>

            <div class="d-flex justify-content-between">
                <a href="/webbanhang/product" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
                </a>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-sync-alt"></i> Cập nhật giỏ hàng
                    </button>
                    <a href="/webbanhang/cart/checkout" class="btn btn-success">
                        <i class="fas fa-credit-card"></i> Thanh toán
                    </a>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include(__DIR__ . '/../shares/footer.php'); ?>