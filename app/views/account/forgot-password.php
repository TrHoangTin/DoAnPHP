<?php include(__DIR__ . '/../../views/shares/header.php'); ?>

<div class="login-container">
    <div class="background-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <div class="login-card">
        <div class="card-header">
            <h3>Quên Mật Khẩu</h3>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_SESSION['error_message'], ENT_QUOTES, 'UTF-8') ?>
                    <?php unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['success_message'], ENT_QUOTES, 'UTF-8') ?>
                    <?php unset($_SESSION['success_message']); ?>
                </div>
            <?php endif; ?>

            <form action="/webbanhang/account/forgot-password" method="post">
                <div class="form-group">
                    <input type="email" class="form-control" id="email" name="email" required>
                    <label for="email" class="form-label">Email đăng ký</label>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-login">Gửi mã OTP</button>
                </div>
            </form>

            <div class="mt-4 text-center">
                <p>Nhớ mật khẩu? 
                    <a href="/webbanhang/account/login" class="register-link">Đăng nhập ngay</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include(__DIR__ . '/../../views/shares/footer.php'); ?>