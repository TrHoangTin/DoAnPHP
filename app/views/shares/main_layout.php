<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'WEBBANHANG - Cửa hàng trực tuyến' ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS tùy chỉnh -->
    <link href="/webbanhang/public/css/style.css" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" href="/webbanhang/public/favicon.ico">
</head>
<body>
    <!-- Header -->
    <?php include __DIR__ . '/header.php'; ?>
    
    <!-- Main Content -->
    <main class="container my-4">
        <?= $this->content() ?>
    </main>
    
    <!-- Footer -->
    <?php include __DIR__ . '/footer.php'; ?>

    <!-- Bootstrap JS + Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JS tùy chỉnh -->
    <script src="/webbanhang/public/js/main.js"></script>
</body>
</html>