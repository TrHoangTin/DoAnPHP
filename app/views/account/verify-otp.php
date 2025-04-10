<?php include(__DIR__ . '/../../views/shares/header.php'); ?>

<div class="login-container">
    <div class="background-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <div class="login-card">
        <div class="card-header">
            <h3>Xác thực OTP</h3>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_SESSION['error_message'], ENT_QUOTES, 'UTF-8') ?>
                    <?php unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>

            <p class="text-center">Mã OTP đã được gửi đến <?= htmlspecialchars($_SESSION['reset_email'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
            
            <form action="/webbanhang/account/verify-otp" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" id="otp" name="otp" required maxlength="6">
                    <label for="otp" class="form-label">Mã OTP 6 chữ số</label>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-login">Xác thực</button>
                </div>
            </form>

            <div class="mt-4 text-center">
                <p>Không nhận được mã? 
                    <a href="/webbanhang/account/forgot-password" class="register-link">Gửi lại mã OTP</a>
                </p>
            </div>
        </div>
    </div>
</div>

