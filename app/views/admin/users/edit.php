<?php require_once __DIR__ . '/../../../shares/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php require_once __DIR__ . '/../sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Chỉnh sửa người dùng</h1>
            </div>

            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Tên đăng nhập</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?= htmlspecialchars($user->username) ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" 
                                   value="<?= htmlspecialchars($user->fullname) ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= htmlspecialchars($user->email) ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?= htmlspecialchars($user->phone) ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">Vai trò</label>
                            <select class="form-select" id="role" name="role">
                                <option value="user" <?= $user->role === 'user' ? 'selected' : '' ?>>Người dùng</option>
                                <option value="admin" <?= $user->role === 'admin' ? 'selected' : '' ?>>Quản trị viên</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-select" id="status" name="status">
                                <option value="active" <?= $user->status === 'active' ? 'selected' : '' ?>>Hoạt động</option>
                                <option value="inactive" <?= $user->status === 'inactive' ? 'selected' : '' ?>>Không hoạt động</option>
                                <option value="banned" <?= $user->status === 'banned' ? 'selected' : '' ?>>Bị cấm</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        <a href="/webbanhang/admin/users" class="btn btn-secondary">Quay lại</a>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../../../shares/footer.php'; ?>