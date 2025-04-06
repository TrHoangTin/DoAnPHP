<?php include __DIR__ . '/../shares/header.php'; ?>

<div class="container mt-4">
    <h2>📋 Chi tiết đơn hàng #<?= $order->id ?></h2>
    
    <div class="card mb-4">
        <div class="card-header">
            Thông tin đơn hàng
        </div>
        <div class="card-body">
            <p><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($order->created_at)) ?></p>
            <p><strong>Trạng thái:</strong> <?= htmlspecialchars($order->status) ?></p>
            <p><strong>Tổng tiền:</strong> <?= number_format($order->total, 0, ',', '.') ?>₫</p>
            <p><strong>Phương thức thanh toán:</strong> <?= htmlspecialchars($order->payment_method) ?></p>
        </div>
    </div>

    <h4>Danh sách sản phẩm</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Đơn giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orderDetails as $item): ?>
            <tr>
                <td>
                    <?= htmlspecialchars($item->product_name) ?>
                    <?php if (!empty($item->image)): ?>
                        <?php
                        // Xử lý đường dẫn ảnh
                        $imagePath = (strpos($item->image, '/webbanhang/') === 0) 
                                   ? $item->image 
                                   : '/webbanhang/' . ltrim($item->image, '/');
                        ?>
                        <div>
                            <img src="<?= $imagePath ?>" 
                                 class="img-fluid" 
                                 width="50" 
                                 alt="<?= htmlspecialchars($item->product_name) ?>"
                                 style="max-height: 50px; object-fit: contain;"
                                 onerror="this.onerror=null;this.src='/webbanhang/public/images/no-image.jpg';">
                        </div>
                    <?php endif; ?>
                </td>
                <td><?= number_format($item->price, 0, ',', '.') ?>₫</td>
                <td><?= $item->quantity ?></td>
                <td><?= number_format($item->price * $item->quantity, 0, ',', '.') ?>₫</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="/webbanhang/account/orders" class="btn btn-secondary">Quay lại</a>
</div>

<style>
    .table img {
        max-width: 50px;
        height: auto;
        display: block;
    }
</style>

<?php include __DIR__ . '/../shares/footer.php'; ?>