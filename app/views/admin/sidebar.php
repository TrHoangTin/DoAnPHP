<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
    <div class="position-sticky pt-3">
        <!-- <div class="text-center text-white py-3 border-bottom">
            <h4><i class="fas fa-laptop-code me-2"></i> TechShop</h4>
        </div> -->
        
        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a class="nav-link text-white <?= str_contains($_SERVER['REQUEST_URI'], '/admin') && !str_contains($_SERVER['REQUEST_URI'], '/admin/users') && !str_contains($_SERVER['REQUEST_URI'], '/admin/orders') ? 'active' : '' ?>" 
                   href="/webbanhang/admin">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= str_contains($_SERVER['REQUEST_URI'], '/admin/users') ? 'active' : '' ?>" 
                   href="/webbanhang/admin/users">
                    <i class="fas fa-users me-2"></i> Quản lý người dùng
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= str_contains($_SERVER['REQUEST_URI'], '/admin/orders') ? 'active' : '' ?>" 
                   href="/webbanhang/admin/orders">
                    <i class="fas fa-shopping-bag me-2"></i> Quản lý đơn hàng
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= str_contains($_SERVER['REQUEST_URI'], '/admin/products') ? 'active' : '' ?>" 
                   href="/webbanhang/admin/products">
                    <i class="fas fa-box-open me-2"></i> Sản phẩm
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= str_contains($_SERVER['REQUEST_URI'], '/admin/categories') ? 'active' : '' ?>" 
                   href="/webbanhang/admin/categories">
                    <i class="fas fa-list me-2"></i> Danh mục
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= str_contains($_SERVER['REQUEST_URI'], '/admin/reports') ? 'active' : '' ?>" 
                   href="/webbanhang/admin/reports">
                    <i class="fas fa-chart-line me-2"></i> Báo cáo
                </a>
            </li>
        </ul>
    </div>
</nav>

<style>
#sidebar {
    min-height: calc(100vh - 56px);
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    transition: all 0.3s;
}

.sidebar .nav-link {
    border-radius: 5px;
    margin: 2px 10px;
    padding: 10px 15px;
    transition: all 0.2s;
}

.sidebar .nav-link:hover {
    background-color: rgba(255,255,255,0.1);
    transform: translateX(3px);
}

.sidebar .nav-link.active {
    background-color: #0d6efd;
    font-weight: 600;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.sidebar .nav-link i {
    width: 20px;
    text-align: center;
}
</style>