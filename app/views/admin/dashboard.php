<?php require_once __DIR__ . '/../shares/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php require_once __DIR__ . '/sidebar.php'; ?>
        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Người dùng</h6>
                                    <h2 class="card-text"><?= $stats['users'] ?></h2>
                                </div>
                                <i class="fas fa-users fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Đơn hàng</h6>
                                    <h2 class="card-text"><?= $stats['orders'] ?></h2>
                                </div>
                                <i class="fas fa-shopping-bag fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Sản phẩm</h6>
                                    <h2 class="card-text"><?= $stats['products'] ?></h2>
                                </div>
                                <i class="fas fa-box-open fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Doanh thu</h6>
                                    <h2 class="card-text"><?= number_format($stats['revenue']->total_revenue ?? 0) ?>đ</h2>
                                </div>
                                <i class="fas fa-money-bill-wave fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Orders -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Đơn hàng gần đây</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Mã đơn</th>
                                            <th>Khách hàng</th>
                                            <th>Tổng tiền</th>
                                            <th>Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentOrders as $order): ?>
                                        <tr>
                                            <td><a href="/webbanhang/admin/orders/<?= $order->id ?>">#<?= $order->id ?></a></td>
                                            <td><?= htmlspecialchars($order->username) ?></td>
                                            <td><?= number_format($order->total) ?>đ</td>
                                            <td>
                                                <span class="badge bg-<?= 
                                                    $order->status === 'completed' ? 'success' : 
                                                    ($order->status === 'cancelled' ? 'danger' : 'warning') 
                                                ?>">
                                                    <?= $order->status ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <a href="/webbanhang/admin/orders" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                        </div>
                    </div>
                </div>

                <!-- Recent Users -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Người dùng mới</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tên đăng nhập</th>
                                            <th>Họ tên</th>
                                            <th>Vai trò</th>
                                            <th>Ngày tạo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentUsers as $user): ?>
                                        <tr>
                                            <td><a href="/webbanhang/admin/users/<?= $user->id ?>"><?= htmlspecialchars($user->username) ?></a></td>
                                            <td><?= htmlspecialchars($user->fullname) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $user->role === 'admin' ? 'danger' : 'info' ?>">
                                                    <?= $user->role ?>
                                                </span>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($user->created_at)) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <a href="/webbanhang/admin/users" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

