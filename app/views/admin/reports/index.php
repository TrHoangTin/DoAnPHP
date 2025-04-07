<?php require_once __DIR__.'/../../shares/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php require_once __DIR__.'/../sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><i class="fas fa-chart-line me-2"></i>Báo cáo & Thống kê</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary">Xuất Excel</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary">In báo cáo</button>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Doanh thu theo tháng</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="revenueChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Sản phẩm bán chạy</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="topProductsChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#dailyReport">Theo ngày</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#monthlyReport">Theo tháng</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#yearlyReport">Theo năm</a>
                        </li>
                    </ul>
                </div>
                
                <div class="card-body">
                <div class="tab-content">
    <div class="tab-pane fade show active" id="dailyReport">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Ngày</th>
                        <th>Số đơn</th>
                        <th>Doanh thu</th>
                        <th>Lợi nhuận</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($dailyReports)): ?>
                        <?php foreach ($dailyReports as $report): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($report->date)) ?></td>
                            <td><?= $report->order_count ?></td>
                            <td><?= number_format($report->revenue) ?>đ</td>
                            <td><?= number_format($report->profit) ?>đ</td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Không có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="tab-pane fade" id="monthlyReport">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tháng</th>
                        <th>Số đơn</th>
                        <th>Doanh thu</th>
                        <th>Lợi nhuận</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($monthlyReports)): ?>
                        <?php foreach ($monthlyReports as $report): ?>
                        <tr>
                            <td>Tháng <?= $report->month ?>/<?= $report->year ?></td>
                            <td><?= $report->order_count ?></td>
                            <td><?= number_format($report->revenue) ?>đ</td>
                            <td><?= number_format($report->profit) ?>đ</td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Không có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="tab-pane fade" id="yearlyReport">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Năm</th>
                        <th>Số đơn</th>
                        <th>Doanh thu</th>
                        <th>Lợi nhuận</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($yearlyReports)): ?>
                        <?php foreach ($yearlyReports as $report): ?>
                        <tr>
                            <td>Năm <?= $report->year ?></td>
                            <td><?= $report->order_count ?></td>
                            <td><?= number_format($report->revenue) ?>đ</td>
                            <td><?= number_format($report->profit) ?>đ</td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Không có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Kiểm tra dữ liệu trước khi vẽ biểu đồ
document.addEventListener('DOMContentLoaded', function() {
    // Biểu đồ doanh thu
    if (document.getElementById('revenueChart')) {
        const revenueData = <?= json_encode($monthlyRevenue ?? []) ?>;
        const revenueLabels = revenueData.map(item => item.month);
        const revenueValues = revenueData.map(item => item.amount);

        new Chart(
            document.getElementById('revenueChart').getContext('2d'),
            {
                type: 'line',
                data: {
                    labels: revenueLabels,
                    datasets: [{
                        label: 'Doanh thu',
                        data: revenueValues,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            }
        );
    }

    // Biểu đồ sản phẩm bán chạy
    if (document.getElementById('topProductsChart')) {
        const topProductsData = <?= json_encode($topProducts ?? []) ?>;
        const productNames = topProductsData.map(item => item.name);
        const productQuantities = topProductsData.map(item => item.sold_quantity);

        new Chart(
            document.getElementById('topProductsChart').getContext('2d'),
            {
                type: 'bar',
                data: {
                    labels: productNames,
                    datasets: [{
                        label: 'Số lượng bán',
                        data: productQuantities,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            }
        );
    }
});
</script>

