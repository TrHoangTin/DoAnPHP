</main>

<footer class="footer py-5 bg-dark text-light" style="background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);">
    <div class="container">
        <div class="row gy-4">
            <!-- Brand Info -->
            <div class="col-lg-4 col-md-6">
                <h4 class="text-white fw-bold mb-3">TechShop</h4>
                <p class="text-muted">
                    Cửa hàng công nghệ hàng đầu Việt Nam với các sản phẩm chất lượng cao và giá cả hợp lý.
                </p>
                <div class="social-icons">
                    <a href="https://www.facebook.com/trhoangtin" class="text-white me-3 hover-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white me-3 hover-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white me-3 hover-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white hover-link"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h5 class="text-white fw-bold mb-3">Liên kết</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="/webbanhang/" class="text-decoration-none text-muted hover-link">Trang chủ</a>
                    </li>
                    <li class="mb-2">
                        <a href="/webbanhang/product" class="text-decoration-none text-muted hover-link">Sản phẩm</a>
                    </li>
                    <li class="mb-2">
                        <a href="/webbanhang/category" class="text-decoration-none text-muted hover-link">Danh mục</a>
                    </li>
                    <li class="mb-2">
                        <a href="/webbanhang/about" class="text-decoration-none text-muted hover-link">Giới thiệu</a>
                    </li>
                </ul>
            </div>

            <!-- Support -->
            <div class="col-lg-3 col-md-6">
                <h5 class="text-white fw-bold mb-3">Hỗ trợ</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="#" class="text-decoration-none text-muted hover-link">Trung tâm hỗ trợ</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-decoration-none text-muted hover-link">Chính sách bảo mật</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-decoration-none text-muted hover-link">Điều khoản dịch vụ</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-decoration-none text-muted hover-link">Chính sách đổi trả</a>
                    </li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="col-lg-3 col-md-6">
                <h5 class="text-white fw-bold mb-3">Liên hệ</h5>
                <ul class="list-unstyled text-muted">
                    <li class="mb-2">
                        <i class="fas fa-map-marker-alt me-2 text-white"></i> Khu CNC, Quận 9, TP.Thủ Đức
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-phone me-2 text-white"></i> +84 123 456 789
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-envelope me-2 text-white"></i> 
                        <a href="mailto:info@techshop.vn" class="text-decoration-none text-muted hover-link">info@techshop.vn</a>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-clock me-2 text-white"></i> 8:00 - 22:00 hàng ngày
                    </li>
                </ul>
            </div>
        </div>

        <!-- Divider -->
        <hr class="my-4 bg-secondary" style="border-color: rgba(255, 255, 255, 0.1);">
        <!-- Copyright -->
        <!-- <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 small text-muted">&copy; <?= date('Y') ?> TechShop. All rights reserved.</p>
            </div>
            
        </div> -->
    </div>
</footer>

<style>
    .hover-link {
        transition: color 0.3s ease;
    }
    
    .hover-link:hover {
        color: #ffffff !important;
    }
    
    .text-muted {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    
    @media (max-width: 768px) {
        .footer {
            text-align: center;
        }
        
        .social-icons {
            justify-content: center;
        }
    }
    .footer {
        background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);
        transition: all 0.3s ease;
    }

    .hover-link {
        transition: color 0.3s ease;
    }

    .hover-link:hover {
        color: #ffffff !important;
    }

    hr.bg-secondary {
        border-color: rgba(255, 255, 255, 0.1);
    }

    .text-muted {
        color: rgba(255, 255, 255, 0.7) !important;
    }

    @media (max-width: 768px) {
        .footer {
            text-align: center;
        }
    }
</style>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Enable tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Confirm before delete
    document.querySelectorAll('.confirm-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    });

    // Handle AJAX requests
    function handleAjaxResponse(response, successCallback, errorCallback) {
        if (response.success) {
            if (typeof successCallback === 'function') {
                successCallback(response);
            }
        } else {
            if (typeof errorCallback === 'function') {
                errorCallback(response);
            } else {
                alert(response.message || 'An error occurred');
            }
        }
    }
</script>
</body>
</html>