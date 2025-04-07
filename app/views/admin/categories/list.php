<?php require_once __DIR__.'/../../shares/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php require_once __DIR__.'/../sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><i class="fas fa-list me-2"></i>Quản lý Danh mục</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="/webbanhang/admin/categories/create" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> Thêm danh mục
                    </a>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">ID</th>
                                    <th>Tên danh mục</th>
                                    <th>Số sản phẩm</th>
                                    <th>Mô tả</th>
                                    <th width="100">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?= $category->id ?></td>
                                    <td><?= htmlspecialchars($category->name) ?></td>
                                    <td>
                                        <span class="badge bg-primary"><?= $category->product_count ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($category->description) ?></td>
                                    <td class="text-nowrap">
                                        <a href="/webbanhang/admin/categories/edit/<?= $category->id ?>" class="btn btn-sm btn-warning" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/webbanhang/admin/categories/delete/<?= $category->id ?>" class="btn btn-sm btn-danger" title="Xóa" onclick="return confirm('Bạn chắc chắn muốn xóa?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end mt-3">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Trước</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Sau</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </main>
    </div>
</div>

