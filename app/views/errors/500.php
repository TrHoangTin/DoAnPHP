<?php include __DIR__ . '/../shares/header.php'; ?>

<div class="container text-center py-5">
    <h1 class="display-1 text-danger">500</h1>
    <p class="lead">Lỗi máy chủ nội bộ</p>
    <div class="alert alert-warning mt-3">
        <?= htmlspecialchars($message) ?>
    </div>
    <div class="mt-4">
        <a href="/webbanhang" class="btn btn-primary me-2">
            <i class="fas fa-home me-2"></i>Trang chủ
        </a>
        <button onclick="history.back()" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </button>
    </div>
</div>

<?php include __DIR__ . '/../shares/footer.php'; ?>