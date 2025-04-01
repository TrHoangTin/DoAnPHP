<?php include __DIR__ . '/../shares/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between mb-4">
        <h1>Danh sách sản phẩm</h1>
        <?php if (SessionHelper::isAdmin()): ?>
            <a href="/webbanhang/product/add" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm sản phẩm
            </a>
        <?php endif; ?>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success_message'] ?></div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Hình ảnh</th>
                <th>Danh mục</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?= $product->id ?></td>
                <td><?= htmlspecialchars($product->name) ?></td>
                <td><?= number_format($product->price, 0, ',', '.') ?>₫</td>
                <td class="text-center">
                    <?php if ($product->image): ?>
                    <img src="<?= $product->image ?>" style="max-height: 70px; display: block; margin: auto;">
                <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($product->category_name ?? 'Không có') ?></td>
                <td>
                    <!-- Thêm nút Xem chi tiết -->
                    <a href="/webbanhang/product/show/<?= $product->id ?>" class="btn btn-sm btn-info" title="Xem chi tiết">
                        <i class="fas fa-eye"></i>
                    </a>
                    
                    <?php if (SessionHelper::isAdmin()): ?>
                        <a href="/webbanhang/product/edit/<?= $product->id ?>" class="btn btn-sm btn-warning" title="Sửa">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="/webbanhang/product/delete/<?= $product->id ?>" class="btn btn-sm btn-danger" 
                           onclick="return confirm('Bạn chắc chắn muốn xóa?')" title="Xóa">
                            <i class="fas fa-trash"></i>
                        </a>
                    <?php else: ?>
                        <a href="/webbanhang/cart/add/<?= $product->id ?>" class="btn btn-sm btn-success" title="Thêm vào giỏ">
                            <i class="fas fa-cart-plus"></i>
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../shares/footer.php'; ?>