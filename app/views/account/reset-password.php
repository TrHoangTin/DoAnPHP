<?php include(__DIR__ . '/../../views/shares/header.php'); ?>

<div class="login-container">
    <div class="background-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <div class="login-card">
        <div class="card-header">
            <h3>Đặt lại Mật Khẩu</h3>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_SESSION['error_message'], ENT_QUOTES, 'UTF-8') ?>
                    <?php unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>

            <form action="/webbanhang/account/reset-password" method="post">
                <div class="form-group">
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                    <label for="new_password" class="form-label">Mật khẩu mới</label>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-login">Đặt lại mật khẩu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include(__DIR__ . '/../../views/shares/footer.php'); ?>