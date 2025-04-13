-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 13, 2025 lúc 02:28 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `toanlb`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `auth_assignment`
--

CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `auth_item`
--

CREATE TABLE `auth_item` (
  `name` varchar(64) NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text DEFAULT NULL,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `auth_item_child`
--

CREATE TABLE `auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `auth_rule`
--

CREATE TABLE `auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `branch_created` varchar(100) DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `delivery_area` varchar(100) DEFAULT NULL,
  `ward` varchar(100) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `tax_code` varchar(50) DEFAULT NULL,
  `id_card` varchar(50) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT 'male',
  `email` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `customer_group` varchar(100) DEFAULT NULL,
  `current_points` int(11) DEFAULT 0,
  `total_points` int(11) DEFAULT 0,
  `creator` varchar(100) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `last_transaction_date` int(11) DEFAULT NULL,
  `current_debt` decimal(15,2) DEFAULT 0.00,
  `total_sales` decimal(15,2) DEFAULT 0.00,
  `total_sales_net` decimal(15,2) DEFAULT 0.00,
  `status` tinyint(3) DEFAULT 1,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `return_code` varchar(50) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `discount_amount` decimal(15,2) DEFAULT 0.00,
  `final_amount` decimal(15,2) NOT NULL,
  `paid_amount` decimal(15,2) DEFAULT 0.00,
  `created_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `branch` varchar(100) DEFAULT NULL,
  `delivery_order_code` varchar(50) DEFAULT NULL,
  `pickup_address` text DEFAULT NULL,
  `reconciliation_code` varchar(50) DEFAULT NULL,
  `delivery_fee` decimal(10,2) DEFAULT 0.00,
  `salesperson` varchar(100) DEFAULT NULL,
  `sales_channel` varchar(100) DEFAULT NULL,
  `creator` varchar(100) DEFAULT NULL,
  `delivery_partner` varchar(100) DEFAULT NULL,
  `receiver_name` varchar(255) DEFAULT NULL,
  `receiver_phone` varchar(20) DEFAULT NULL,
  `receiver_address` text DEFAULT NULL,
  `receiver_area` varchar(100) DEFAULT NULL,
  `receiver_ward` varchar(100) DEFAULT NULL,
  `service` varchar(100) DEFAULT NULL,
  `weight_grams` int(11) DEFAULT NULL,
  `length_cm` decimal(10,2) DEFAULT NULL,
  `width_cm` decimal(10,2) DEFAULT NULL,
  `height_cm` decimal(10,2) DEFAULT NULL,
  `delivery_status_note` text DEFAULT NULL,
  `delivery_note` text DEFAULT NULL,
  `order_note` text DEFAULT NULL,
  `cod_remaining` decimal(15,2) DEFAULT 0.00,
  `delivery_time` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `delivery_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` varchar(50) NOT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `discount_percentage` decimal(5,2) DEFAULT 0.00,
  `discount_amount` decimal(15,2) DEFAULT 0.00,
  `final_price` decimal(15,2) NOT NULL,
  `warranty_note` text DEFAULT NULL,
  `maintenance_note` text DEFAULT NULL,
  `product_note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_payments`
--

CREATE TABLE `order_payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `cash_amount` decimal(15,2) DEFAULT 0.00,
  `card_amount` decimal(15,2) DEFAULT 0.00,
  `ewallet_amount` decimal(15,2) DEFAULT 0.00,
  `bank_transfer_amount` decimal(15,2) DEFAULT 0.00,
  `points_used` int(11) DEFAULT 0,
  `voucher_code` varchar(50) DEFAULT NULL,
  `voucher_amount` decimal(15,2) DEFAULT 0.00,
  `additional_fee` decimal(15,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `current_stock` int(11) DEFAULT 0,
  `min_stock` int(11) DEFAULT 0,
  `max_stock` int(11) DEFAULT 0,
  `primary_unit_id` int(11) NOT NULL,
  `related_product_code` varchar(50) DEFAULT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(3) DEFAULT 1,
  `is_direct_sale` tinyint(3) DEFAULT 1,
  `description` text DEFAULT NULL,
  `note` text DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `is_component` tinyint(3) DEFAULT 0,
  `warranty_months` int(11) DEFAULT 0,
  `maintenance_period_months` int(11) DEFAULT 0,
  `point_earn` int(11) DEFAULT 0,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `level` tinyint(3) DEFAULT 1,
  `description` text DEFAULT NULL,
  `status` tinyint(3) DEFAULT 1,
  `created_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_url` varchar(500) NOT NULL,
  `is_primary` tinyint(3) DEFAULT 0,
  `created_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_units`
--

CREATE TABLE `product_units` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `base_unit_id` int(11) DEFAULT NULL,
  `conversion_rate` decimal(10,2) DEFAULT 1.00,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_warranties`
--

CREATE TABLE `product_warranties` (
  `id` int(11) NOT NULL,
  `order_item_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `serial_number` varchar(100) NOT NULL,
  `warranty_start_date` date NOT NULL,
  `warranty_end_date` date NOT NULL,
  `warranty_type` varchar(20) DEFAULT 'standard',
  `warranty_duration_months` int(11) NOT NULL,
  `status` varchar(20) DEFAULT 'active',
  `original_purchase_date` date NOT NULL,
  `original_purchase_price` decimal(15,2) NOT NULL,
  `last_service_date` date DEFAULT NULL,
  `next_service_date` date DEFAULT NULL,
  `repair_count` int(11) DEFAULT 0,
  `total_repair_cost` decimal(15,2) DEFAULT 0.00,
  `warranty_terms` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `area` varchar(100) DEFAULT NULL,
  `ward` varchar(100) DEFAULT NULL,
  `tax_code` varchar(50) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `total_purchase` decimal(15,2) DEFAULT 0.00,
  `current_debt` decimal(15,2) DEFAULT 0.00,
  `group` varchar(100) DEFAULT NULL,
  `status` tinyint(3) DEFAULT 1,
  `total_purchase_net` decimal(15,2) DEFAULT 0.00,
  `creator` varchar(100) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT 10,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `verification_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `warranty_repair_logs`
--

CREATE TABLE `warranty_repair_logs` (
  `id` int(11) NOT NULL,
  `warranty_id` int(11) NOT NULL,
  `repair_date` date NOT NULL,
  `technician` varchar(255) DEFAULT NULL,
  `repair_location` varchar(255) DEFAULT NULL,
  `issue_description` text NOT NULL,
  `repair_description` text DEFAULT NULL,
  `parts_replaced` text DEFAULT NULL,
  `repair_cost` decimal(15,2) DEFAULT 0.00,
  `status` varchar(20) DEFAULT 'pending',
  `next_service_recommendation` text DEFAULT NULL,
  `created_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD PRIMARY KEY (`item_name`,`user_id`);

--
-- Chỉ mục cho bảng `auth_item`
--
ALTER TABLE `auth_item`
  ADD PRIMARY KEY (`name`),
  ADD KEY `fk_auth_item_rule` (`rule_name`);

--
-- Chỉ mục cho bảng `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);

--
-- Chỉ mục cho bảng `auth_rule`
--
ALTER TABLE `auth_rule`
  ADD PRIMARY KEY (`name`);

--
-- Chỉ mục cho bảng `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `idx_customer_phone` (`phone`),
  ADD KEY `idx_customer_code` (`code`);

--
-- Chỉ mục cho bảng `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `idx_order_code` (`code`),
  ADD KEY `idx_order_customer` (`customer_id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk-order_details-order_id` (`order_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk-order_items-order_id` (`order_id`),
  ADD KEY `fk-order_items-product_id` (`product_id`);

--
-- Chỉ mục cho bảng `order_payments`
--
ALTER TABLE `order_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk-order_payments-order_id` (`order_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `fk-products-category_id` (`category_id`),
  ADD KEY `fk-products-primary_unit_id` (`primary_unit_id`),
  ADD KEY `idx_product_code` (`code`),
  ADD KEY `idx_product_barcode` (`barcode`);

--
-- Chỉ mục cho bảng `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk-product_categories-parent_id` (`parent_id`);

--
-- Chỉ mục cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk-product_images-product_id` (`product_id`);

--
-- Chỉ mục cho bảng `product_units`
--
ALTER TABLE `product_units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `fk-product_units-base_unit_id` (`base_unit_id`);

--
-- Chỉ mục cho bảng `product_warranties`
--
ALTER TABLE `product_warranties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `serial_number` (`serial_number`),
  ADD KEY `fk-product_warranties-order_item_id` (`order_item_id`),
  ADD KEY `idx_warranty_serial` (`serial_number`),
  ADD KEY `idx_warranty_product` (`product_id`),
  ADD KEY `idx_warranty_customer` (`customer_id`),
  ADD KEY `idx_warranty_status` (`status`);

--
-- Chỉ mục cho bảng `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `idx_supplier_code` (`code`);

--
-- Chỉ mục cho bảng `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- Chỉ mục cho bảng `warranty_repair_logs`
--
ALTER TABLE `warranty_repair_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_repair_log_warranty` (`warranty_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `order_payments`
--
ALTER TABLE `order_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `product_units`
--
ALTER TABLE `product_units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `product_warranties`
--
ALTER TABLE `product_warranties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `warranty_repair_logs`
--
ALTER TABLE `warranty_repair_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `fk_auth_item_rule` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk-orders-customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk-order_details-order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk-order_items-order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-order_items-product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Các ràng buộc cho bảng `order_payments`
--
ALTER TABLE `order_payments`
  ADD CONSTRAINT `fk-order_payments-order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk-products-category_id` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`),
  ADD CONSTRAINT `fk-products-primary_unit_id` FOREIGN KEY (`primary_unit_id`) REFERENCES `product_units` (`id`);

--
-- Các ràng buộc cho bảng `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `fk-product_categories-parent_id` FOREIGN KEY (`parent_id`) REFERENCES `product_categories` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk-product_images-product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `product_units`
--
ALTER TABLE `product_units`
  ADD CONSTRAINT `fk-product_units-base_unit_id` FOREIGN KEY (`base_unit_id`) REFERENCES `product_units` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `product_warranties`
--
ALTER TABLE `product_warranties`
  ADD CONSTRAINT `fk-product_warranties-customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `fk-product_warranties-order_item_id` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-product_warranties-product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Các ràng buộc cho bảng `warranty_repair_logs`
--
ALTER TABLE `warranty_repair_logs`
  ADD CONSTRAINT `fk-warranty_repair_logs-warranty_id` FOREIGN KEY (`warranty_id`) REFERENCES `product_warranties` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
