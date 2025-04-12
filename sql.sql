-- --------------------------------------------------------
-- Máy chủ:                      127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Phiên bản:           12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for webbanhang
CREATE DATABASE IF NOT EXISTS `webbanhang` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `webbanhang`;

-- Dumping structure for table webbanhang.account
CREATE TABLE IF NOT EXISTS `account` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `status` enum('active','inactive','banned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `reset_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `provider` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanhang.account: ~7 rows (approximately)
INSERT INTO `account` (`id`, `username`, `password`, `fullname`, `role`, `status`, `email`, `phone`, `address`, `created_at`, `updated_at`, `avatar`, `last_login`, `reset_token`, `reset_token_expiry`, `provider`, `provider_id`) VALUES
	(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Quản Trị Viên', 'admin', 'active', 'admin@webbanhang.com', '0987654321', '123 Đường ABC, Quận 1, TP.HCM', '2025-03-31 05:18:37', '2025-03-31 05:18:37', NULL, NULL, NULL, NULL, NULL, NULL),
	(3, 'user2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Người Dùng 2', 'user', 'active', 'user2@example.com', '0909123456', '789 Đường DEF, Quận 3, TP.HCM', '2025-03-31 05:18:37', '2025-03-31 05:18:37', NULL, NULL, NULL, NULL, NULL, NULL),
	(14, 'long', '$2y$10$XvWNGBcXDGpTFEA6FX6f/.m.8kj0uBlyj3eQEawXV5GZeJRZt5aOO', 'philong', 'user', 'active', 'trantin065@gmail.com', '', NULL, '2025-03-31 18:22:08', '2025-03-31 18:22:08', NULL, NULL, NULL, NULL, NULL, NULL),
	(15, 'user', '$2y$10$0zQ7uMN4xVcZPG7rF6c16Oqo8XcAkBYNRkXUAa413ukuGKAN3uQpW', 'username', 'user', 'active', 'username@gmail.com', '0123456789', NULL, '2025-04-02 16:48:13', '2025-04-02 16:48:13', NULL, NULL, NULL, NULL, NULL, NULL),
	(16, 'htin', '$2y$10$nwcM4rmcA9HFsu/oHw3Pp.heWK5a1DgsO3gYFMysJHWOc34z.TS8a', 'tin', 'user', 'active', 'trantin123@gmail.com', '0334526838', '136 Ấp 3', '2025-04-05 14:18:16', '2025-04-06 20:12:04', NULL, NULL, NULL, NULL, NULL, NULL),
	(17, 'tin', '$2y$10$34dw9Pq4lr1VOBEEr6w.uehc.MZptjch8FMHyiuF4SgxCqXvM2JVu', 'Hoàng Tín', 'admin', 'active', 'trantin064@gmail.com', '0334526837', 'TP.Hồ Chí Minh', '2025-04-05 14:20:02', '2025-04-10 16:54:01', NULL, NULL, NULL, NULL, NULL, NULL),
	(19, 'hoangtin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'HoangTin', 'admin', 'active', 'hoangtin@example.com', '0334526837', 'TP.Hồ Chí Minh', '2025-04-07 00:24:39', '2025-04-07 00:25:28', NULL, NULL, NULL, NULL, NULL, NULL);

-- Dumping structure for table webbanhang.admin_logs
CREATE TABLE IF NOT EXISTS `admin_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `account_id` int NOT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`),
  CONSTRAINT `admin_logs_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanhang.admin_logs: ~0 rows (approximately)

-- Dumping structure for table webbanhang.category
CREATE TABLE IF NOT EXISTS `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanhang.category: ~6 rows (approximately)
INSERT INTO `category` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'Điện thoại', 'Các dòng điện thoại thông minh', '2025-03-31 05:18:37', '2025-03-31 05:18:37'),
	(2, 'Laptop', 'Máy tính xách tay các hãng', '2025-03-31 05:18:37', '2025-03-31 05:18:37'),
	(3, 'Phụ kiện', 'Phụ kiện điện thoại và laptop', '2025-03-31 05:18:37', '2025-03-31 05:18:37'),
	(4, 'Đồ gia dụng', 'Thiết bị điện gia dụng', '2025-03-31 05:18:37', '2025-03-31 05:18:37'),
	(8, 'máy giặt', 'Tiết kiệm điện', '2025-04-07 04:01:58', '2025-04-07 05:44:28'),
	(9, 'Loa', 'Nghe nhạc chill', '2025-04-07 05:31:18', '2025-04-07 05:31:18');

-- Dumping structure for table webbanhang.discount_vouchers
CREATE TABLE IF NOT EXISTS `discount_vouchers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `min_order_amount` decimal(10,2) DEFAULT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table webbanhang.discount_vouchers: ~0 rows (approximately)

-- Dumping structure for table webbanhang.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `account_id` int DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` enum('cod','bank','momo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cod',
  `status` enum('pending','processing','shipped','delivered','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `total` decimal(10,2) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanhang.orders: ~13 rows (approximately)
INSERT INTO `orders` (`id`, `account_id`, `name`, `phone`, `address`, `payment_method`, `status`, `total`, `notes`, `created_at`, `updated_at`, `shipping_fee`, `discount`) VALUES
	(1, NULL, 'Người Dùng 1', '0912345678', '456 Đường XYZ, Quận 2, TP.HCM', 'cod', 'delivered', 30980000.00, NULL, '2025-03-31 05:18:37', '2025-03-31 05:18:37', 0.00, 0.00),
	(2, 3, 'Người Dùng 2', '0909123456', '789 Đường DEF, Quận 3, TP.HCM', 'bank', 'shipped', 5990000.00, NULL, '2025-03-31 05:18:37', '2025-03-31 05:18:37', 0.00, 0.00),
	(3, 14, 'Trần Tín', '1634526837', '136 Ấp 3', 'bank', 'pending', 29990000.00, NULL, '2025-04-04 17:10:45', '2025-04-04 17:10:45', 0.00, 0.00),
	(4, 14, 'Trần Tín', '1634526837', '136 Ấp 3', 'bank', 'pending', 29990000.00, NULL, '2025-04-04 17:11:40', '2025-04-04 17:11:40', 0.00, 0.00),
	(5, 14, 'Trần Tín', '1634526837', '136 Ấp 3', 'momo', 'pending', 54980000.00, NULL, '2025-04-04 17:24:45', '2025-04-04 17:24:45', 0.00, 0.00),
	(6, 14, 'Trần Tín', '1634526837', '136 Ấp 3', 'momo', 'pending', 97970000.00, NULL, '2025-04-05 13:57:36', '2025-04-05 13:57:36', 0.00, 0.00),
	(7, 16, 'Trần Tín', '1634526837', '136 Ấp 3', 'momo', 'pending', 29990000.00, NULL, '2025-04-05 14:18:38', '2025-04-05 14:18:38', 0.00, 0.00),
	(8, 14, 'Trần Tín', '1634526837', '136 Ấp 3', 'cod', 'pending', 24990000.00, NULL, '2025-04-05 15:48:36', '2025-04-05 15:48:36', 0.00, 0.00),
	(9, 14, 'aa', '03333', 'aaa', 'cod', 'pending', 54980000.00, NULL, '2025-04-06 18:38:51', '2025-04-06 18:38:51', 0.00, 0.00),
	(10, 14, 'aa', '03333', 'aaaa', 'cod', 'pending', 29990000.00, NULL, '2025-04-06 19:04:16', '2025-04-06 19:04:16', 0.00, 0.00),
	(11, 14, 'aa', '03333', '111111', 'cod', 'pending', 29990000.00, NULL, '2025-04-06 19:05:05', '2025-04-06 19:05:05', 0.00, 0.00),
	(12, 16, 'aa', '03333', 'aaa', 'cod', 'pending', 29990000.00, NULL, '2025-04-06 19:22:23', '2025-04-06 19:22:23', 0.00, 0.00),
	(13, 16, 'aa', '03333', '11111', 'cod', 'pending', 24990000.00, NULL, '2025-04-06 19:22:54', '2025-04-06 19:22:54', 0.00, 0.00),
	(14, 14, 'PiLong', '0334656879', 'da lat', 'cod', 'pending', 250000.00, NULL, '2025-04-07 02:30:47', '2025-04-07 02:30:47', 0.00, 0.00),
	(15, 16, 'tinnn', '0334656878', 'âđấ', 'cod', 'pending', 29990000.00, NULL, '2025-04-07 04:01:15', '2025-04-07 04:01:15', 0.00, 0.00),
	(16, 16, 'Trần Hoàng Tín', '0334526837', 'TP.HCM', 'cod', 'pending', 29990000.00, NULL, '2025-04-07 05:50:13', '2025-04-07 05:50:13', 0.00, 0.00),
	(17, 16, 'Trần Tín', '1634526837', '136 Ấp 3', 'cod', 'pending', 29990000.00, NULL, '2025-04-11 11:48:22', '2025-04-11 11:48:22', 0.00, 0.00),
	(18, 16, 'Trần Tín', '1634526837', '136 Ấp 3', 'cod', 'pending', 72980000.00, NULL, '2025-04-11 15:44:40', '2025-04-11 15:44:40', 0.00, 0.00);

-- Dumping structure for table webbanhang.order_details
CREATE TABLE IF NOT EXISTS `order_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanhang.order_details: ~18 rows (approximately)
INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`) VALUES
	(1, 1, 1, 1, 29990000.00, '2025-03-31 05:18:37'),
	(2, 1, 5, 1, 5990000.00, '2025-03-31 05:18:37'),
	(3, 1, 6, 2, 250000.00, '2025-03-31 05:18:37'),
	(4, 2, 5, 1, 5990000.00, '2025-03-31 05:18:37'),
	(5, 3, 1, 1, 29990000.00, '2025-04-04 17:10:45'),
	(6, 4, 1, 1, 29990000.00, '2025-04-04 17:11:40'),
	(7, 5, 1, 1, 29990000.00, '2025-04-04 17:24:45'),
	(8, 5, 2, 1, 24990000.00, '2025-04-04 17:24:45'),
	(9, 6, 2, 1, 24990000.00, '2025-04-05 13:57:36'),
	(10, 6, 1, 1, 29990000.00, '2025-04-05 13:57:36'),
	(11, 6, 3, 1, 42990000.00, '2025-04-05 13:57:36'),
	(12, 7, 1, 1, 29990000.00, '2025-04-05 14:18:38'),
	(13, 8, 2, 1, 24990000.00, '2025-04-05 15:48:36'),
	(14, 9, 1, 1, 29990000.00, '2025-04-06 18:38:51'),
	(15, 9, 2, 1, 24990000.00, '2025-04-06 18:38:51'),
	(16, 10, 1, 1, 29990000.00, '2025-04-06 19:04:16'),
	(17, 11, 1, 1, 29990000.00, '2025-04-06 19:05:05'),
	(18, 12, 1, 1, 29990000.00, '2025-04-06 19:22:23'),
	(19, 13, 2, 1, 24990000.00, '2025-04-06 19:22:54'),
	(20, 14, 6, 1, 250000.00, '2025-04-07 02:30:47'),
	(21, 15, 1, 1, 29990000.00, '2025-04-07 04:01:15'),
	(22, 16, 1, 1, 29990000.00, '2025-04-07 05:50:13'),
	(23, 17, 1, 1, 29990000.00, '2025-04-11 11:48:22'),
	(24, 18, 1, 1, 29990000.00, '2025-04-11 15:44:40'),
	(25, 18, 3, 1, 42990000.00, '2025-04-11 15:44:40');

-- Dumping structure for table webbanhang.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `account_id` int NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`),
  CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table webbanhang.password_resets: ~0 rows (approximately)

-- Dumping structure for table webbanhang.product
CREATE TABLE IF NOT EXISTS `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sku` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock_quantity` int NOT NULL DEFAULT '0',
  `weight` decimal(10,2) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `views` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanhang.product: ~8 rows (approximately)
INSERT INTO `product` (`id`, `name`, `description`, `price`, `image`, `category_id`, `created_at`, `updated_at`, `sku`, `stock_quantity`, `weight`, `is_featured`, `views`) VALUES
	(1, 'iPhone 15 Pro Max', 'Điện thoại iPhone 14 Pro Max 128GB', 29990000.00, 'uploads/iphone14.jpg', 1, '2025-03-31 05:18:37', '2025-04-11 15:45:46', NULL, 0, NULL, 0, 0),
	(2, 'Samsung Galaxy S23 Ultra', 'Điện thoại Samsung Galaxy S23 Ultra 256GB', 24990000.00, 'uploads/s23ultra.jpg', 1, '2025-03-31 05:18:37', '2025-03-31 05:18:37', NULL, 0, NULL, 0, 0),
	(3, 'MacBook Pro M2 2023', 'Laptop MacBook Pro 14 inch M2 16GB/512GB', 42990000.00, 'uploads/macbookpro.jpg', 2, '2025-03-31 05:18:37', '2025-03-31 05:18:37', NULL, 0, NULL, 0, 0),
	(4, 'Dell XPS 15', 'Laptop Dell XPS 15 9520 i7/16GB/512GB', 35990000.00, 'uploads/dellxps.jpg', 2, '2025-03-31 05:18:37', '2025-03-31 05:18:37', NULL, 0, NULL, 0, 0),
	(5, 'Tai nghe AirPods Pro 2', 'Tai nghe không dây Apple AirPods Pro 2', 5990000.00, 'uploads/airpods.jpg', 3, '2025-03-31 05:18:37', '2025-03-31 05:18:37', NULL, 0, NULL, 0, 0),
	(6, 'Ốp lưng iPhone 14', 'Ốp lưng trong suốt cho iPhone 14 series', 250000.00, 'uploads/oplung.jpg', 3, '2025-03-31 05:18:37', '2025-03-31 05:18:37', NULL, 0, NULL, 0, 0),
	(13, 'loa-bluetooth-den-led-l30', 'Sang', 300000.00, '/webbanhang/uploads/67f36369bdb74_loa.jpg', 9, '2025-04-07 05:32:25', '2025-04-07 05:32:25', NULL, 0, NULL, 0, 0),
	(14, 'Ipad Pro', 'Mới nhất', 20000000.00, '/webbanhang/uploads/67f363bc6d4ef_th.jpg', 1, '2025-04-07 05:33:48', '2025-04-07 05:33:48', NULL, 0, NULL, 0, 0);

-- Dumping structure for table webbanhang.product_promotions
CREATE TABLE IF NOT EXISTS `product_promotions` (
  `product_id` int NOT NULL,
  `promotion_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`,`promotion_id`),
  KEY `promotion_id` (`promotion_id`),
  CONSTRAINT `product_promotions_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_promotions_ibfk_2` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanhang.product_promotions: ~0 rows (approximately)

-- Dumping structure for table webbanhang.product_reviews
CREATE TABLE IF NOT EXISTS `product_reviews` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `account_id` int NOT NULL,
  `rating` tinyint(1) NOT NULL,
  `comment` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `account_id` (`account_id`),
  CONSTRAINT `product_reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_reviews_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table webbanhang.product_reviews: ~7 rows (approximately)
INSERT INTO `product_reviews` (`id`, `product_id`, `account_id`, `rating`, `comment`, `created_at`) VALUES
	(1, 1, 16, 5, 'aaa', '2025-04-06 21:21:18'),
	(2, 2, 16, 5, 'Đẹp ', '2025-04-06 21:37:51'),
	(3, 3, 16, 5, 'Xịn sò', '2025-04-06 23:10:29'),
	(4, 5, 16, 5, 'Nghe ổn', '2025-04-07 02:12:51'),
	(6, 6, 16, 5, 'dep', '2025-04-07 02:26:34'),
	(7, 1, 19, 5, 'dep qua', '2025-04-07 02:53:38'),
	(9, 2, 17, 1, '1 sao vi không có tiền mua', '2025-04-07 03:34:54'),
	(10, 1, 17, 5, 'hi', '2025-04-07 04:00:31'),
	(11, 4, 17, 5, 'dep', '2025-04-11 15:47:02');

-- Dumping structure for table webbanhang.promotions
CREATE TABLE IF NOT EXISTS `promotions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `discount_type` enum('percentage','fixed_amount') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `promotions_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `account` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanhang.promotions: ~0 rows (approximately)

-- Dumping structure for view webbanhang.sales_report
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `sales_report` (
	`sale_date` DATE NULL,
	`total_orders` BIGINT(19) NOT NULL,
	`total_revenue` DECIMAL(32,2) NULL,
	`avg_order_value` DECIMAL(14,6) NULL,
	`unique_customers` BIGINT(19) NOT NULL
) ENGINE=MyISAM;

-- Dumping structure for table webbanhang.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires` int NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanhang.sessions: ~0 rows (approximately)

-- Dumping structure for table webbanhang.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanhang.users: ~0 rows (approximately)

-- Dumping structure for view webbanhang.sales_report
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `sales_report`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `sales_report` AS select cast(`o`.`created_at` as date) AS `sale_date`,count(`o`.`id`) AS `total_orders`,sum(`o`.`total`) AS `total_revenue`,avg(`o`.`total`) AS `avg_order_value`,count(distinct `o`.`account_id`) AS `unique_customers` from `orders` `o` where (`o`.`status` = 'delivered') group by cast(`o`.`created_at` as date);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
