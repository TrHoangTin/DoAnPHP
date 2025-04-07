<?php require_once __DIR__ . '/../../shares/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php require_once __DIR__ . '/../sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Quản lý Đơn hàng</h1>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Phương thức</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?= $order->id ?></td>
                            <td><?= htmlspecialchars($order->username) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($order->created_at)) ?></td>
                            <td><?= number_format($order->total) ?>đ</td>
                            <td><?= strtoupper($order->payment_method) ?></td>
                            <td>
                                <span class="badge bg-<?= 
                                    $order->status === 'completed' ? 'success' : 
                                    ($order->status === 'cancelled' ? 'danger' : 'warning') 
                                ?>">
                                    <?= $order->status ?>
                                </span>
                            </td>
                            <td>
                                <a href="/webbanhang/admin/orders/<?= $order->id ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

