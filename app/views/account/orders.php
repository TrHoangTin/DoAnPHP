<?php include __DIR__ . '/../shares/header.php'; ?>

<div class="container mt-4">
    <h2>📦 Đơn hàng của bạn</h2>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info mt-3">
            Bạn chưa có đơn hàng nào. <a href="/webbanhang/product">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Ngày đặt</th>
                        <th>Trạng thái</th>
                        <th>Tổng tiền</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?= $order->id ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($order->created_at)) ?></td>
                            <td><?= htmlspecialchars($order->status ?? 'Đang xử lý') ?></td>
                            <td><?= number_format($order->total_amount, 0, ',', '.') ?>₫</td>
                            <td>
                                <a href="/webbanhang/account/orderdetail/<?= $order->id ?>" class="btn btn-sm btn-info">
                                    Xem chi tiết
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../shares/footer.php'; ?>
