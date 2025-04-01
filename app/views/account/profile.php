<?php include __DIR__ . '/../shares/header.php'; ?>

<div class="container mt-4">
    <h2>👤 Thông tin tài khoản</h2>

    <?php if (SessionHelper::getFlash('success_message')): ?>
        <div class="alert alert-success"><?= SessionHelper::getFlash('success_message') ?></div>
    <?php endif; ?>

    <?php if (SessionHelper::getFlash('error_message')): ?>
        <div class="alert alert-danger"><?= SessionHelper::getFlash('error_message') ?></div>
    <?php endif; ?>

    <form method="post" action="/webbanhang/account/profile">
        <div class="mb-3">
            <label class="form-label">Tên đăng nhập</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($account->username) ?>" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Họ tên</label>
            <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($account->fullname) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($account->email) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Số điện thoại</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($account->phone) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <textarea name="address" class="form-control" rows="3"><?= htmlspecialchars($account->address) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>

<?php include __DIR__ . '/../shares/footer.php'; ?>
