<?php include __DIR__ . '/../shares/header.php'; ?>

<div class="container mt-4">
    <h1>Thêm sản phẩm mới</h1>
    
    <!-- Hiển thị thông báo lỗi -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" 
                   value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>" 
                   required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control" rows="3"><?= 
                isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' 
            ?></textarea>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Giá</label>
            <input type="number" name="price" class="form-control" 
                   value="<?= isset($_POST['price']) ? htmlspecialchars($_POST['price']) : '' ?>" 
                   min="0" step="1000" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Danh mục</label>
            <select name="category_id" class="form-select">
                <option value="">-- Chọn danh mục --</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category->id ?>"
                        <?= (isset($_POST['category_id']) && $_POST['category_id'] == $category->id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Hình ảnh</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            <small class="text-muted">Chỉ chấp nhận file ảnh (JPG, PNG, GIF)</small>
        </div>
        
        <button type="submit" class="btn btn-primary">Lưu sản phẩm</button>
        <a href="/webbanhang/product" class="btn btn-secondary">Quay lại</a>
    </form>
</div>

<?php include __DIR__ . '/../shares/footer.php'; ?>