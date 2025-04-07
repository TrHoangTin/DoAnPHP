<?php require_once __DIR__ . '/../../shares/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php require_once __DIR__ . '/../sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Quản lý Người dùng</h1>
                <a href="/webbanhang/account/register" class="btn btn-primary">Thêm người dùng</a>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên đăng nhập</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user->id ?></td>
                            <td><?= htmlspecialchars($user->username) ?></td>
                            <td><?= htmlspecialchars($user->fullname) ?></td>
                            <td><?= htmlspecialchars($user->email) ?></td>
                            <td>
                                <span class="badge bg-<?= $user->role === 'admin' ? 'danger' : 'info' ?>">
                                    <?= $user->role ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?= $user->status === 'active' ? 'success' : 'secondary' ?>">
                                    <?= $user->status ?>
                                </span>
                            </td>
                            <td>
                                <a href="/webbanhang/admin/users/<?= $user->id ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/webbanhang/admin/users/edit/<?= $user->id ?>" class="btn btn-sm btn-warning">
                                 <i class="fas fa-edit"></i>
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

