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
        <form action="/webbanhang/cart/update" method="post" id="cartForm">
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
                            <td class="price" data-price="<?= $product->price ?>">
                                <?= number_format($product->price, 0, ',', '.') ?>₫
                            </td>
                            <td style="width: 100px;">
                                <input type="number" name="quantity[<?= $product->id ?>]"
                                       value="<?= $product->quantity ?>"
                                       min="1" class="form-control text-center quantity-input">
                            </td>
                            <td class="subtotal">
                                <?= number_format($product->subtotal, 0, ',', '.') ?>₫
                            </td>
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
                        <td class="text-center fw-bold" id="totalQuantity">
                            <?= array_sum(array_column($products, 'quantity')) ?>
                        </td>
                        <td colspan="2" class="fw-bold" id="totalAmount">
                            <?= number_format($total, 0, ',', '.') ?>₫
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="d-flex justify-content-between">
                <a href="/webbanhang" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
                </a>
                <div class="d-flex gap-2">
                   
                    <a href="/webbanhang/cart/checkout" class="btn btn-success">
                        <i class="fas fa-credit-card"></i> Thanh toán
                    </a>
                </div>
            </div>
        </form>

        <script>
        $(document).ready(function() {
            // Tính toán lại khi số lượng thay đổi
            $('.quantity-input').on('change input', function() {
                updateCartTotals();
                $('#updateButton').show(); // Hiển thị nút cập nhật nếu có thay đổi
            });

            function updateCartTotals() {
                let totalQuantity = 0;
                let totalAmount = 0;
                
                // Duyệt qua từng dòng sản phẩm
                $('tbody tr').each(function() {
                    const $row = $(this);
                    const price = $row.find('.price').data('price');
                    const quantityInput = $row.find('.quantity-input');
                    
                    if (quantityInput.length) {
                        const quantity = parseInt(quantityInput.val()) || 0;
                        const subtotal = price * quantity;
                        
                        $row.find('.subtotal').text(subtotal.toLocaleString('vi-VN') + '₫');
                        totalQuantity += quantity;
                        totalAmount += subtotal;
                    }
                });
                
                // Cập nhật tổng
                $('#totalQuantity').text(totalQuantity);
                $('#totalAmount').text(totalAmount.toLocaleString('vi-VN') + '₫');
            }
            
            // Tự động submit form khi có thay đổi (nếu muốn)
            /*
            $('.quantity-input').on('change', function() {
                $('#cartForm').submit();
            });
            */
        });
        </script>
    <?php endif; ?>
</div>

<?php include(__DIR__ . '/../shares/footer.php'); ?>