# TÀI LIỆU DỰ ÁN HỆ THỐNG QUẢN LÝ BÁN HÀNG

## 1. TỔNG QUAN DỰ ÁN

### 1.1. Giới thiệu
Hệ thống Quản lý Bán hàng là một ứng dụng web được phát triển để giúp doanh nghiệp quản lý toàn diện hoạt động kinh doanh, từ quản lý sản phẩm, khách hàng, đơn hàng đến bảo hành, nhà cung cấp, kho hàng và kế toán. Hệ thống được xây dựng trên nền tảng PHP với framework Yii2 Advanced và cơ sở dữ liệu MySQL.

### 1.2. Mục tiêu
- Xây dựng hệ thống quản lý bán hàng toàn diện
- Tự động hóa quy trình bán hàng từ đặt hàng đến giao hàng và bảo hành
- Quản lý thông tin khách hàng, sản phẩm và nhà cung cấp
- Quản lý kho hàng đa chi nhánh và chuyển kho
- Theo dõi thu chi và công nợ
- Cung cấp báo cáo và thống kê để hỗ trợ ra quyết định kinh doanh
- Phân quyền người dùng để đảm bảo an toàn dữ liệu

### 1.3. Công nghệ sử dụng
- **Ngôn ngữ lập trình**: PHP 8.0.30
- **Framework**: Yii2 Advanced
- **Cơ sở dữ liệu**: MySQL 10.4.32 (MariaDB)
- **Giao diện người dùng**: AdminLTE 3, Bootstrap 4, Font Awesome 6
- **Môi trường phát triển**: XAMPP

### 1.4. Các giai đoạn phát triển
- **Giai đoạn 1 (Đã hoàn thành)**: Phát triển các module cơ bản (sản phẩm, khách hàng, đơn hàng, POS, bảo hành, nhà cung cấp, RBAC)
- **Giai đoạn 2 (Đang lên kế hoạch)**: Phát triển nghiệp vụ kế toán và kho hàng (nhập hàng, thu chi, quản lý kho, chuyển kho, báo cáo)

## 2. TỔNG QUAN KIẾN TRÚC HỆ THỐNG

### 2.1. Mô hình MVC
Hệ thống được xây dựng theo mô hình MVC (Model-View-Controller) của Yii2, giúp tổ chức code rõ ràng và dễ bảo trì:
- **Model**: Đại diện cho dữ liệu và logic nghiệp vụ
- **View**: Hiển thị dữ liệu cho người dùng
- **Controller**: Xử lý tương tác của người dùng và điều hướng

### 2.2. Cấu trúc thư mục
```
toanlb/
├── common/                 # Mã dùng chung
│   ├── config/             # Cấu hình chung
│   ├── models/             # Các model dùng chung
│   └── components/         # Các component dùng chung
├── console/
│   ├── config/
│   └── migrations/         # Cập nhật cấu trúc cơ sở dữ liệu
├── backend/
│   ├── assets/             # Tài nguyên CSS/JS
│   ├── config/
│   ├── controllers/        # Controllers
│   ├── views/              # View templates
│   └── web/                # Public web root
├── frontend/               # (Chưa phát triển)
└── vendor/                 # Thư viện bên thứ ba
```

### 2.3. Cơ sở dữ liệu
Hệ thống sử dụng cơ sở dữ liệu quan hệ MySQL với các nhóm bảng chính:
- **Quản lý sản phẩm**: products, product_categories, product_units
- **Quản lý khách hàng**: customers
- **Quản lý đơn hàng**: orders, order_items, order_payments, order_details
- **Quản lý bảo hành**: product_warranties, warranty_repair_logs
- **Quản lý nhà cung cấp**: suppliers
- **Phân quyền**: auth_assignment, auth_item, auth_item_child, auth_rule, user
- **Quản lý kho** *(Giai đoạn 2)*: warehouses, warehouse_stock, stock_adjustments
- **Nhập hàng** *(Giai đoạn 2)*: purchase_orders, purchase_order_items, purchase_order_payments
- **Chuyển kho** *(Giai đoạn 2)*: stock_transfers, stock_transfer_items
- **Quản lý thu chi** *(Giai đoạn 2)*: financial_transactions, financial_categories

## 3. CHỨC NĂNG HỆ THỐNG

### 3.1. Chức năng đã phát triển (Giai đoạn 1)

#### 3.1.1. Quản lý Sản phẩm
- **Danh sách sản phẩm**: Hiển thị, tìm kiếm, lọc và phân trang
- **Thêm/sửa/xóa sản phẩm**: Quản lý thông tin sản phẩm
- **Quản lý danh mục**: Phân loại sản phẩm theo danh mục
- **Quản lý đơn vị tính**: Đơn vị tính cho sản phẩm

#### 3.1.2. Quản lý Khách hàng
- **Danh sách khách hàng**: Hiển thị, tìm kiếm, lọc và phân trang
- **Thêm/sửa/xóa khách hàng**: Quản lý thông tin khách hàng
- **Lịch sử đơn hàng**: Xem lịch sử mua hàng của khách
- **Điểm tích lũy và công nợ**: Theo dõi điểm thưởng và nợ của khách hàng

#### 3.1.3. Quản lý Đơn hàng
- **Danh sách đơn hàng**: Hiển thị, tìm kiếm và lọc
- **Tạo đơn hàng mới**: Thêm nhiều sản phẩm vào đơn hàng
- **Thanh toán đa phương thức**: Hỗ trợ nhiều hình thức thanh toán
- **In hóa đơn**: Tạo và in hóa đơn bán hàng

#### 3.1.4. Bán hàng (POS)
- **Giao diện bán hàng**: Màn hình POS trực quan
- **Tìm kiếm sản phẩm**: Tìm kiếm nhanh chóng
- **Quản lý giỏ hàng**: Thêm, sửa, xóa sản phẩm trong giỏ hàng
- **Áp dụng giảm giá**: Áp dụng các loại giảm giá
- **Thanh toán**: Hỗ trợ nhiều phương thức thanh toán
- **In hóa đơn**: In hóa đơn bán hàng

#### 3.1.5. Quản lý Bảo hành
- **Danh sách bảo hành**: Theo dõi tình trạng bảo hành
- **Thêm/sửa/xóa bảo hành**: Quản lý thông tin bảo hành
- **Lịch sử sửa chữa**: Theo dõi các lần sửa chữa
- **Thông báo hết hạn**: Cảnh báo bảo hành sắp hết hạn

#### 3.1.6. Quản lý Nhà cung cấp
- **Danh sách nhà cung cấp**: Hiển thị, tìm kiếm và lọc
- **Thêm/sửa/xóa nhà cung cấp**: Quản lý thông tin nhà cung cấp
- **Thống kê giao dịch**: Theo dõi hoạt động mua hàng từ nhà cung cấp

#### 3.1.7. Phân quyền RBAC
- **Quản lý vai trò**: Tạo và quản lý các vai trò người dùng
- **Quản lý quyền**: Gán quyền cho từng vai trò
- **Gán vai trò**: Gán vai trò cho người dùng
- **Kiểm soát truy cập**: Hạn chế quyền truy cập vào các chức năng

### 3.2. Chức năng sẽ phát triển (Giai đoạn 2)

#### 3.2.1. Quản lý Nhập hàng
- **Danh sách đơn nhập hàng**: Hiển thị, tìm kiếm, lọc đơn nhập từ nhà cung cấp
- **Tạo đơn nhập hàng**: Chọn nhà cung cấp, kho, thêm sản phẩm, giá nhập, thuế, chiết khấu
- **Quản lý trạng thái đơn nhập hàng**: Theo dõi trạng thái đơn: Chờ xử lý, Đã duyệt, Đã nhận một phần, Đã nhận đủ, Đã hủy
- **Quản lý thanh toán nhà cung cấp**: Theo dõi trạng thái thanh toán: Chưa thanh toán, Thanh toán một phần, Đã thanh toán, Quá hạn thanh toán
- **In phiếu nhập kho**: Tạo và in phiếu nhập kho với đầy đủ thông tin

#### 3.2.2. Quản lý Thu chi
- **Phiếu thu**: Tạo phiếu thu từ khách hàng (liên kết với đơn hàng hoặc độc lập)
- **Phiếu chi**: Tạo phiếu chi cho nhà cung cấp hoặc chi phí hoạt động
- **Quản lý danh mục thu chi**: Phân loại các khoản thu/chi theo danh mục, phân cấp
- **Báo cáo thu chi**: Báo cáo dòng tiền, theo danh mục, theo đối tượng
- **Quản lý công nợ**: Theo dõi công nợ khách hàng, nhà cung cấp

#### 3.2.3. Quản lý Kho hàng
- **Quản lý nhiều kho**: Thêm/sửa/xóa kho hàng, chỉ định kho mặc định
- **Quản lý tồn kho theo kho**: Xem tồn kho theo từng kho, lọc sản phẩm
- **Điều chỉnh tồn kho**: Tăng/giảm số lượng sản phẩm trong kho, ghi nhận lý do
- **Báo cáo kho**: Báo cáo tồn kho, sản phẩm sắp hết, tồn đọng, giá trị tồn kho

#### 3.2.4. Quản lý Chuyển kho
- **Tạo phiếu chuyển kho**: Chọn kho nguồn, kho đích, sản phẩm, số lượng
- **Quản lý trạng thái chuyển kho**: Theo dõi trạng thái: Chờ duyệt, Đã duyệt, Đang vận chuyển, Đã nhận một phần, Đã nhận đủ, Đã hủy
- **Nhận hàng chuyển kho**: Xác nhận số lượng nhận thực tế, ghi nhận chênh lệch
- **Báo cáo chuyển kho**: Báo cáo số lượng chuyển kho theo thời gian, sản phẩm, kho

#### 3.2.5. Báo cáo và Thống kê nâng cao
- **Báo cáo doanh thu**: Theo thời gian, sản phẩm, danh mục, khách hàng
- **Báo cáo lợi nhuận**: Doanh thu, chi phí, lợi nhuận gộp/ròng
- **Báo cáo nhập xuất tồn**: Số lượng nhập/xuất/tồn theo thời gian, sản phẩm
- **Báo cáo công nợ**: Công nợ khách hàng, nhà cung cấp, quá hạn
- **Biểu đồ và trực quan hóa dữ liệu**: Hiển thị dữ liệu dưới dạng biểu đồ

## 4. THIẾT KẾ CƠ SỞ DỮ LIỆU (GIAI ĐOẠN 2)

### 4.1. Quản lý Nhập hàng

```sql
-- Phiếu nhập kho (đơn nhập hàng)
CREATE TABLE `purchase_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `discount_amount` decimal(15,2) DEFAULT 0.00,
  `tax_amount` decimal(15,2) DEFAULT 0.00,
  `final_amount` decimal(15,2) NOT NULL,
  `paid_amount` decimal(15,2) DEFAULT 0.00,
  `status` varchar(30) DEFAULT 'pending',
  `payment_status` varchar(30) DEFAULT 'unpaid',
  `payment_due_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `creator` varchar(100) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `fk-purchase_orders-supplier_id` (`supplier_id`),
  KEY `fk-purchase_orders-warehouse_id` (`warehouse_id`),
  CONSTRAINT `fk-purchase_orders-supplier_id` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  CONSTRAINT `fk-purchase_orders-warehouse_id` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Chi tiết phiếu nhập
CREATE TABLE `purchase_order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` varchar(50) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `discount_percentage` decimal(5,2) DEFAULT 0.00,
  `discount_amount` decimal(15,2) DEFAULT 0.00,
  `tax_percentage` decimal(5,2) DEFAULT 0.00,
  `tax_amount` decimal(15,2) DEFAULT 0.00,
  `final_price` decimal(15,2) NOT NULL,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk-purchase_order_items-purchase_order_id` (`purchase_order_id`),
  KEY `fk-purchase_order_items-product_id` (`product_id`),
  CONSTRAINT `fk-purchase_order_items-purchase_order_id` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-purchase_order_items-product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Thanh toán phiếu nhập
CREATE TABLE `purchase_order_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_order_id` int(11) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_date` date NOT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `creator` varchar(100) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk-purchase_order_payments-purchase_order_id` (`purchase_order_id`),
  CONSTRAINT `fk-purchase_order_payments-purchase_order_id` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### 4.2. Quản lý Thu chi

```sql
-- Phiếu thu/chi
CREATE TABLE `financial_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `type` enum('receipt','payment') NOT NULL COMMENT 'receipt: phiếu thu, payment: phiếu chi',
  `amount` decimal(15,2) NOT NULL,
  `transaction_date` date NOT NULL,
  `category_id` int(11) NOT NULL,
  `related_id` int(11) DEFAULT NULL COMMENT 'ID của đơn hàng, nhập hàng hoặc đối tác liên quan',
  `related_type` varchar(50) DEFAULT NULL COMMENT 'order, purchase_order, customer, supplier, etc.',
  `payment_method` varchar(50) NOT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `creator` varchar(100) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `fk-financial_transactions-category_id` (`category_id`),
  CONSTRAINT `fk-financial_transactions-category_id` FOREIGN KEY (`category_id`) REFERENCES `financial_categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Danh mục thu chi
CREATE TABLE `financial_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` enum('receipt','payment','both') NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk-financial_categories-parent_id` (`parent_id`),
  CONSTRAINT `fk-financial_categories-parent_id` FOREIGN KEY (`parent_id`) REFERENCES `financial_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### 4.3. Quản lý Kho hàng

```sql
-- Kho hàng
CREATE TABLE `warehouses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` text DEFAULT NULL,
  `manager` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tồn kho theo kho
CREATE TABLE `warehouse_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `warehouse_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `warehouse_product` (`warehouse_id`,`product_id`),
  KEY `fk-warehouse_stock-product_id` (`product_id`),
  CONSTRAINT `fk-warehouse_stock-product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `fk-warehouse_stock-warehouse_id` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Điều chỉnh tồn kho
CREATE TABLE `stock_adjustments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `creator` varchar(100) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `fk-stock_adjustments-warehouse_id` (`warehouse_id`),
  CONSTRAINT `fk-stock_adjustments-warehouse_id` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Chi tiết điều chỉnh tồn kho
CREATE TABLE `stock_adjustment_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adjustment_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity_before` decimal(10,2) NOT NULL,
  `adjusted_quantity` decimal(10,2) NOT NULL,
  `quantity_after` decimal(10,2) NOT NULL,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk-stock_adjustment_items-adjustment_id` (`adjustment_id`),
  KEY `fk-stock_adjustment_items-product_id` (`product_id`),
  CONSTRAINT `fk-stock_adjustment_items-adjustment_id` FOREIGN KEY (`adjustment_id`) REFERENCES `stock_adjustments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-stock_adjustment_items-product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### 4.4. Quản lý Chuyển kho

```sql
-- Phiếu chuyển kho
CREATE TABLE `stock_transfers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `source_warehouse_id` int(11) NOT NULL,
  `target_warehouse_id` int(11) NOT NULL,
  `status` varchar(30) DEFAULT 'pending',
  `transfer_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `creator` varchar(100) DEFAULT NULL,
  `approver` varchar(100) DEFAULT NULL,
  `receiver` varchar(100) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `approved_at` int(11) DEFAULT NULL,
  `received_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `fk-stock_transfers-source_warehouse_id` (`source_warehouse_id`),
  KEY `fk-stock_transfers-target_warehouse_id` (`target_warehouse_id`),
  CONSTRAINT `fk-stock_transfers-source_warehouse_id` FOREIGN KEY (`source_warehouse_id`) REFERENCES `warehouses` (`id`),
  CONSTRAINT `fk-stock_transfers-target_warehouse_id` FOREIGN KEY (`target_warehouse_id`) REFERENCES `warehouses` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Chi tiết phiếu chuyển kho
CREATE TABLE `stock_transfer_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transfer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `received_quantity` decimal(10,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk-stock_transfer_items-transfer_id` (`transfer_id`),
  KEY `fk-stock_transfer_items-product_id` (`product_id`),
  CONSTRAINT `fk-stock_transfer_items-product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `fk-stock_transfer_items-transfer_id` FOREIGN KEY (`transfer_id`) REFERENCES `stock_transfers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### 4.5. Cập nhật bảng Orders

```sql
-- Thêm trường warehouse_id vào bảng orders
ALTER TABLE `orders` ADD `warehouse_id` INT(11) NOT NULL AFTER `customer_id`;
ALTER TABLE `orders` ADD CONSTRAINT `fk-orders-warehouse_id` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);
```

## 5. QUY TRÌNH NGHIỆP VỤ (GIAI ĐOẠN 2)

### 5.1. Quy trình Nhập hàng

1. **Tạo đơn đặt hàng**:
   - Người dùng tạo đơn đặt hàng với nhà cung cấp
   - Chọn sản phẩm, số lượng và giá
   - Lưu đơn ở trạng thái "Chờ xử lý"

2. **Phê duyệt đơn đặt hàng**:
   - Người có quyền duyệt xem xét và phê duyệt đơn
   - Đơn chuyển sang trạng thái "Đã duyệt"

3. **Nhận hàng**:
   - Khi hàng về, người dùng xác nhận nhận hàng
   - Nhập số lượng nhận thực tế
   - Hệ thống tự động cập nhật tồn kho
   - Đơn chuyển sang trạng thái "Đã nhận"

4. **Thanh toán**:
   - Tạo phiếu chi cho nhà cung cấp
   - Liên kết phiếu chi với đơn nhập hàng
   - Cập nhật trạng thái thanh toán

### 5.2. Quy trình Thu/Chi

1. **Tạo phiếu thu**:
   - Khi khách hàng thanh toán, tạo phiếu thu
   - Chọn danh mục thu phù hợp
   - Liên kết với đơn hàng (nếu có)

2. **Tạo phiếu chi**:
   - Khi thanh toán cho nhà cung cấp, tạo phiếu chi
   - Khi chi tiêu khác, tạo phiếu chi với danh mục tương ứng
   - Đính kèm chứng từ (nếu có)

3. **Phê duyệt phiếu chi** (tùy chọn):
   - Với các khoản chi lớn, có thể yêu cầu phê duyệt trước
   - Người có quyền xem xét và duyệt phiếu chi

4. **Báo cáo định kỳ**:
   - Tổng hợp thu chi theo ngày/tuần/tháng
   - Đối chiếu với sổ quỹ thực tế

### 5.3. Quy trình Chuyển kho

1. **Tạo phiếu chuyển kho**:
   - Người dùng tạo phiếu chuyển kho
   - Chọn kho nguồn, kho đích và sản phẩm
   - Nhập số lượng cần chuyển

2. **Phê duyệt chuyển kho** (tùy chọn):
   - Người có quyền xem xét và phê duyệt
   - Phiếu chuyển sang trạng thái "Đã duyệt"

3. **Xuất hàng**:
   - Nhân viên kho nguồn xuất hàng
   - Hệ thống giảm số lượng tồn kho tại kho nguồn
   - Phiếu chuyển sang trạng thái "Đang vận chuyển"

4. **Nhận hàng**:
   - Nhân viên kho đích xác nhận nhận hàng
   - Nhập số lượng nhận thực tế
   - Hệ thống tăng số lượng tồn kho tại kho đích
   - Phiếu chuyển sang trạng thái "Đã nhận"

## 6. PHÂN QUYỀN BỔ SUNG (GIAI ĐOẠN 2)

Các quyền mới cần thêm vào hệ thống RBAC:

```php
// Nhập hàng
$viewPurchase = $auth->createPermission('viewPurchase');
$viewPurchase->description = 'Xem đơn nhập hàng';
$auth->add($viewPurchase);

$createPurchase = $auth->createPermission('createPurchase');
$createPurchase->description = 'Tạo đơn nhập hàng';
$auth->add($createPurchase);

$approvePurchase = $auth->createPermission('approvePurchase');
$approvePurchase->description = 'Duyệt đơn nhập hàng';
$auth->add($approvePurchase);

// Thu chi
$viewFinance = $auth->createPermission('viewFinance');
$viewFinance->description = 'Xem phiếu thu chi';
$auth->add($viewFinance);

$createReceipt = $auth->createPermission('createReceipt');
$createReceipt->description = 'Tạo phiếu thu';
$auth->add($createReceipt);

$createPayment = $auth->createPermission('createPayment');
$createPayment->description = 'Tạo phiếu chi';
$auth->add($createPayment);

$approvePayment = $auth->createPermission('approvePayment');
$approvePayment->description = 'Duyệt phiếu chi';
$auth->add($approvePayment);

// Kho hàng
$viewWarehouse = $auth->createPermission('viewWarehouse');
$viewWarehouse->description = 'Xem kho hàng';
$auth->add($viewWarehouse);

$manageWarehouse = $auth->createPermission('manageWarehouse');
$manageWarehouse->description = 'Quản lý kho hàng';
$auth->add($manageWarehouse);

$adjustStock = $auth->createPermission('adjustStock');
$adjustStock->description = 'Điều chỉnh tồn kho';
$auth->add($adjustStock);

// Chuyển kho
$viewTransfer = $auth->createPermission('viewTransfer');
$viewTransfer->description = 'Xem phiếu chuyển kho';
$auth->add($viewTransfer);

$createTransfer = $auth->createPermission('createTransfer');
$createTransfer->description = 'Tạo phiếu chuyển kho';
$auth->add($createTransfer);

$approveTransfer = $auth->createPermission('approveTransfer');
$approveTransfer->description = 'Duyệt phiếu chuyển kho';
$auth->add($approveTransfer);

$receiveTransfer = $auth->createPermission('receiveTransfer');
$receiveTransfer->description = 'Nhận hàng chuyển kho';
$auth->add($receiveTransfer);
```

Gán quyền cho các vai trò hiện có:

```php
// Vai trò: Thủ kho
$storekeeper = $auth->createRole('storekeeper');
$storekeeper->description = 'Thủ kho';
$auth->add($storekeeper);
$auth->addChild($storekeeper, $viewWarehouse);
$auth->addChild($storekeeper, $viewTransfer);
$auth->addChild($storekeeper, $receiveTransfer);
$auth->addChild($storekeeper, $adjustStock);
$auth->addChild($storekeeper, $viewProduct);

// Vai trò: Kế toán
$accountant = $auth->createRole('accountant');
$accountant->description = 'Kế toán';
$auth->add($accountant);
$auth->addChild($accountant, $viewFinance);
$auth->addChild($accountant, $createReceipt);
$auth->addChild($accountant, $createPayment);
$auth->addChild($accountant, $viewPurchase);
$auth->addChild($accountant, $viewReport);

// Cập nhật vai trò Manager
$auth->addChild($manager, $createPurchase);
$auth->addChild($manager, $approvePurchase);
$auth->addChild($manager, $approvePayment);
$auth->addChild($manager, $manageWarehouse);
$auth->addChild($manager, $createTransfer);
$auth->addChild($manager, $approveTransfer);
```

## 7. KẾ HOẠCH TRIỂN KHAI GIAI ĐOẠN 2

### 7.1. Lộ trình triển khai

| Giai đoạn | Nội dung | Thời gian dự kiến |
|-----------|----------|-------------------|
| 1. Phân tích và thiết kế | - Phân tích chi tiết yêu cầu<br>- Thiết kế CSDL<br>- Thiết kế giao diện | 2 tuần |
| 2. Phát triển module Kho hàng | - Tạo migration CSDL<br>- Phát triển model, controller, view<br>- Tích hợp với hệ thống hiện tại | 3 tuần |
| 3. Phát triển module Nhập hàng | - Tạo migration CSDL<br>- Phát triển model, controller, view<br>- Tích hợp với module kho hàng | 3 tuần |
| 4. Phát triển module Thu chi | - Tạo migration CSDL<br>- Phát triển model, controller, view<br>- Tích hợp với các module khác | 3 tuần |
| 5. Phát triển module Chuyển kho | - Tạo migration CSDL<br>- Phát triển model, controller, view<br>- Tích hợp với module kho hàng | 2 tuần |
| 6. Phát triển báo cáo | - Phát triển báo cáo kho hàng<br>- Phát triển báo cáo tài chính<br>- Phát triển biểu đồ thống kê | 3 tuần |
| 7. Kiểm thử và sửa lỗi | - Kiểm thử từng module<br>- Kiểm thử tích hợp<br>- Sửa lỗi và tối ưu hóa | 2 tuần |
| 8. Triển khai và đào tạo | - Triển khai lên môi trường thực tế<br>- Đào tạo người dùng<br>- Theo dõi và hỗ trợ | 2 tuần |

**Tổng thời gian dự kiến: 20 tuần (5 tháng)**

### 7.2. Phân bổ nguồn lực

- **Nhân lực**:
  - 1-2 Backend Developer (PHP/Yii2)
  - 1 Frontend Developer (Bootstrap/JavaScript)
  - 1 QA Engineer (Kiểm thử)
  - 1 Project Manager

- **Công nghệ bổ sung**:
  - Chart.js hoặc Highcharts cho biểu đồ
  - mPDF hoặc TCPDF cho xuất PDF
  - PHPExcel cho xuất Excel

### 7.3. Rủi ro và giải pháp

| Rủi ro | Mức độ | Giải pháp |
|--------|--------|-----------|
| **Độ phức tạp của quy trình kế toán và kho hàng** | Cao | - Phân tích kỹ lưỡng các quy trình nghiệp vụ<br>- Tham khảo ý kiến của chuyên gia kế toán |
| **Tích hợp với hệ thống hiện tại** | Trung bình | - Thiết kế cẩn thận các điểm tích hợp<br>- Kiểm thử kỹ lưỡng |
| **Hiệu suất hệ thống khi dữ liệu lớn** | Cao | - Tối ưu hóa truy vấn CSDL<br>- Sử dụng cache và phân trang hiệu quả |
| **Đào tạo người dùng với chức năng mới** | Trung bình | - Xây dựng tài liệu hướng dẫn chi tiết<br>- Tổ chức đào tạo kỹ lưỡng |
| **Quản lý thay đổi và xung đột yêu cầu** | Trung bình | - Quản lý phạm vi dự án chặt chẽ<br>- Giao tiếp thường xuyên với các bên liên quan |

## 8. CÔNG NGHỆ VÀ KIẾN TRÚC

### 8.1. Công nghệ bổ sung

- **Báo cáo và Biểu đồ**:
  - Chart.js: Thư viện JavaScript cho biểu đồ trực quan
  - Highcharts: Thư viện biểu đồ nâng cao (tùy chọn)

- **Xuất dữ liệu**:
  - mPDF: Tạo file PDF từ HTML
  - PHPExcel/PhpSpreadsheet: Tạo và xử lý file Excel

- **Tối ưu hóa**:
  - Yii2 Cache: Bộ nhớ đệm để tăng hiệu suất
  - AJAX: Tải dữ liệu không đồng bộ

### 8.2. Kiến trúc module

- **Cấu trúc module**:
  ```
  backend/
  ├── controllers/
  │   ├── PurchaseController.php
  │   ├── FinanceController.php
  │   ├── WarehouseController.php
  │   └── TransferController.php
  ├── views/
  │   ├── purchase/
  │   ├── finance/
  │   ├── warehouse/
  │   └── transfer/
  ```

- **Mô hình dịch vụ**:
  ```
  common/
  ├── services/
  │   ├── PurchaseService.php
  │   ├── FinanceService.php
  │   ├── WarehouseService.php
  │   └── TransferService.php
  ```

### 8.3. Tích hợp với Hệ thống hiện tại

- **Điều chỉnh bảng Orders**:
  - Thêm trường warehouse_id để liên kết với kho hàng

- **Cập nhật quy trình bán hàng**:
  - Kiểm tra tồn kho theo từng kho
  - Tự động cập nhật tồn kho khi bán hàng

- **Cập nhật giao diện**:
  - Thêm menu mới cho các module mới
  - Điều chỉnh quyền truy cập menu theo RBAC

## 9. YÊU CẦU CHỨC NĂNG CHI TIẾT

### 9.1. Module Nhập hàng

- **Danh sách đơn nhập hàng**:
  - Hiển thị: Mã đơn, nhà cung cấp, kho nhập, tổng tiền, trạng thái, ngày tạo
  - Lọc theo: Nhà cung cấp, kho, trạng thái, thời gian
  - Xuất dữ liệu: Excel, PDF

- **Tạo đơn nhập hàng**:
  - Chọn nhà cung cấp, kho nhập
  - Thêm sản phẩm: Mã/tên, số lượng, giá nhập, thuế, chiết khấu
  - Tính toán tự động: Thành tiền, thuế, chiết khấu, tổng cộng
  - Thông tin thanh toán: Phương thức, ngày thanh toán

- **Chi tiết đơn nhập hàng**:
  - Thông tin chung: Mã đơn, nhà cung cấp, người tạo
  - Danh sách sản phẩm: Mã/tên, số lượng, giá, thuế, chiết khấu, thành tiền
  - Lịch sử thanh toán: Ngày, phương thức, số tiền
  - In phiếu nhập kho

### 9.2. Module Thu chi

- **Danh sách phiếu thu/chi**:
  - Hiển thị: Mã phiếu, loại (thu/chi), danh mục, số tiền, đối tượng, ngày
  - Lọc theo: Loại, danh mục, thời gian, đối tượng
  - Xuất dữ liệu: Excel, PDF

- **Tạo phiếu thu/chi**:
  - Chọn loại: Thu/Chi
  - Thông tin chung: Số tiền, danh mục, ngày, phương thức thanh toán
  - Đối tượng liên quan: Khách hàng, nhà cung cấp, đơn hàng, nhập hàng (tùy chọn)
  - Tải lên chứng từ/hình ảnh (tùy chọn)

- **Quản lý danh mục thu chi**:
  - Thêm/sửa/xóa danh mục
  - Cấu trúc phân cấp (cha/con)
  - Phân loại theo thu/chi/cả hai

### 9.3. Module Kho hàng

- **Danh sách kho hàng**:
  - Hiển thị: Mã kho, tên kho, địa chỉ, người quản lý, trạng thái
  - Thêm/sửa/xóa kho hàng
  - Chỉ định kho mặc định

- **Xem tồn kho**:
  - Hiển thị: Mã sản phẩm, tên, danh mục, số lượng tồn, giá trị tồn
  - Lọc theo: Kho, danh mục, trạng thái tồn (hết/sắp hết/còn)
  - Xuất dữ liệu: Excel, PDF

- **Điều chỉnh tồn kho**:
  - Chọn kho, sản phẩm
  - Nhập số lượng điều chỉnh (+/-)
  - Ghi nhận lý do điều chỉnh
  - Lưu lịch sử điều chỉnh

### 9.4. Module Chuyển kho

- **Danh sách phiếu chuyển kho**:
  - Hiển thị: Mã phiếu, kho nguồn, kho đích, trạng thái, ngày tạo
  - Lọc theo: Kho nguồn, kho đích, trạng thái, thời gian
  - Xuất dữ liệu: Excel, PDF

- **Tạo phiếu chuyển kho**:
  - Chọn kho nguồn, kho đích
  - Thêm sản phẩm: Mã/tên, số lượng chuyển
  - Kiểm tra tồn kho tự động
  - Ghi chú cho từng sản phẩm

- **Nhận hàng chuyển kho**:
  - Hiển thị danh sách sản phẩm cần nhận
  - Nhập số lượng nhận thực tế
  - Ghi nhận lý do chênh lệch (nếu có)
  - Cập nhật trạng thái và tồn kho tự động

### 9.5. Báo cáo nâng cao

- **Báo cáo kho hàng**:
  - Báo cáo tồn kho theo kho
  - Báo cáo nhập xuất tồn
  - Báo cáo giá trị hàng tồn kho

- **Báo cáo tài chính**:
  - Báo cáo dòng tiền
  - Báo cáo thu chi theo danh mục
  - Báo cáo công nợ

- **Biểu đồ trực quan**:
  - Biểu đồ doanh số
  - Biểu đồ tồn kho
  - Biểu đồ thu chi

## 10. TỔNG KẾT

Giai đoạn 2 của dự án Hệ thống Quản lý Bán hàng sẽ tập trung vào phát triển các tính năng nghiệp vụ kế toán và kho hàng, giúp doanh nghiệp quản lý toàn diện hoạt động kinh doanh. Các module mới bao gồm quản lý nhập hàng, quản lý thu chi, quản lý kho hàng và chuyển kho, cùng với các báo cáo nâng cao.

Với kế hoạch triển khai chi tiết và phân bổ nguồn lực hợp lý, dự án dự kiến sẽ hoàn thành trong khoảng 5 tháng. Sau khi hoàn thành giai đoạn 2, hệ thống sẽ cung cấp một giải pháp quản lý kinh doanh toàn diện, từ bán hàng, kho hàng đến kế toán, giúp doanh nghiệp tối ưu hóa hoạt động và tăng cường hiệu quả kinh doanh.
