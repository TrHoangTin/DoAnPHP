<?php include __DIR__ . '/../shares/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Chỉnh sửa danh mục</h3>
                        <a href="/WEBBANHANG/category" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <?php if (SessionHelper::getFlash('error_message')): ?>
                        <div class="alert alert-danger">
                            <?= SessionHelper::getFlash('error_message') ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="name" name="name" 
                                   value="<?= htmlspecialchars($_POST['name'] ?? $category->name) ?>" 
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control" 
                                      id="description" name="description" 
                                      rows="3"><?= htmlspecialchars($_POST['description'] ?? $category->description) ?></textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Cập nhật danh mục
                            </button>
                            <a href="/WEBBANHANG/category" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Hủy bỏ
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../shares/footer.php'; ?>
