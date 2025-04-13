# TÀI LIỆU HỆ THỐNG QUẢN LÝ BÁN HÀNG

## 1. TỔNG QUAN DỰ ÁN

### 1.1. Giới thiệu
Hệ thống Quản lý Bán hàng là một ứng dụng web được phát triển để giúp doanh nghiệp quản lý toàn diện hoạt động kinh doanh, từ quản lý sản phẩm, khách hàng, đơn hàng, bảo hành đến kho hàng, nhà cung cấp, và kế toán. Hệ thống được xây dựng trên nền tảng PHP với framework Yii2 Advanced và cơ sở dữ liệu MySQL.

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
- **Giai đoạn 2 (Đang triển khai)**: Phát triển module người dùng, kho hàng và kế toán (nhập hàng, thu chi, quản lý kho, chuyển kho, báo cáo)
- **Giai đoạn 3 (Đề xuất)**: Phát triển các tính năng mở rộng và tích hợp (mobile app, API, tích hợp thanh toán)

## 2. CẤU TRÚC HỆ THỐNG

### 2.1. Kiến trúc MVC
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
│   ├── components/         # Các component dùng chung
│   └── rbac/               # Các rule RBAC
├── console/
│   ├── config/
│   ├── controllers/
│   └── migrations/         # Cập nhật cấu trúc cơ sở dữ liệu
├── backend/
│   ├── assets/             # Tài nguyên CSS/JS
│   ├── config/
│   ├── controllers/        # Controllers
│   │   ├── SiteController.php
│   │   ├── ProductController.php
│   │   ├── CustomerController.php
│   │   ├── OrderController.php
│   │   ├── PosController.php
│   │   ├── WarrantyController.php
│   │   ├── SupplierController.php
│   │   ├── UserController.php
│   │   └── ...
│   ├── models/
│   ├── views/              # View templates
│   │   ├── layouts/
│   │   ├── site/
│   │   ├── product/
│   │   ├── customer/
│   │   ├── order/
│   │   ├── pos/
│   │   ├── warranty/
│   │   ├── supplier/
│   │   ├── user/
│   │   └── ...
│   └── web/                # Public web root
│       ├── css/
│       ├── js/
│       ├── images/
│       └── uploads/        # Thư mục upload
├── frontend/               # (Chưa phát triển)
└── vendor/                 # Thư viện bên thứ ba
```

### 2.3. Cấu trúc Database
Hệ thống sử dụng cơ sở dữ liệu quan hệ MySQL với các nhóm bảng chính:

#### 2.3.1. Nhóm quản lý sản phẩm
- **products**: Thông tin sản phẩm
- **product_categories**: Danh mục sản phẩm
- **product_units**: Đơn vị tính
- **product_images**: Hình ảnh sản phẩm

#### 2.3.2. Nhóm quản lý khách hàng
- **customers**: Thông tin khách hàng

#### 2.3.3. Nhóm quản lý đơn hàng
- **orders**: Thông tin đơn hàng
- **order_items**: Chi tiết sản phẩm trong đơn hàng
- **order_payments**: Thanh toán đơn hàng
- **order_details**: Thông tin chi tiết đơn hàng

#### 2.3.4. Nhóm quản lý bảo hành
- **product_warranties**: Thông tin bảo hành sản phẩm
- **warranty_repair_logs**: Lịch sử sửa chữa

#### 2.3.5. Nhóm quản lý nhà cung cấp
- **suppliers**: Thông tin nhà cung cấp

#### 2.3.6. Nhóm phân quyền
- **user**: Thông tin người dùng
- **user_profile**: Thông tin chi tiết người dùng
- **user_login_history**: Lịch sử đăng nhập
- **auth_assignment**: Gán vai trò cho người dùng
- **auth_item**: Vai trò và quyền
- **auth_item_child**: Quan hệ cha-con của vai trò và quyền
- **auth_rule**: Quy tắc kiểm tra quyền

#### 2.3.7. Nhóm quản lý kho (Giai đoạn 2)
- **warehouses**: Thông tin kho hàng
- **warehouse_stock**: Tồn kho theo kho
- **stock_adjustments**: Điều chỉnh tồn kho
- **stock_adjustment_items**: Chi tiết điều chỉnh tồn kho
- **stock_transfers**: Chuyển kho
- **stock_transfer_items**: Chi tiết chuyển kho

#### 2.3.8. Nhóm nhập hàng (Giai đoạn 2)
- **purchase_orders**: Đơn đặt hàng
- **purchase_order_items**: Chi tiết đơn đặt hàng
- **purchase_order_payments**: Thanh toán đơn đặt hàng

#### 2.3.9. Nhóm quản lý tài chính (Giai đoạn 2)
- **financial_transactions**: Phiếu thu chi
- **financial_categories**: Danh mục thu chi

## 3. CHỨC NĂNG HỆ THỐNG

### 3.1. Chức năng đã hoàn thành (Giai đoạn 1)

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

### 3.2. Chức năng đang phát triển (Giai đoạn 2)

#### 3.2.1. Quản lý Người dùng
- **Danh sách người dùng**: Hiển thị, tìm kiếm và lọc
- **Thêm/sửa/xóa người dùng**: Quản lý tài khoản người dùng
- **Phân quyền người dùng**: Gán vai trò cho người dùng
- **Quản lý hồ sơ cá nhân**: Thông tin chi tiết của người dùng
- **Đổi mật khẩu**: Cho phép người dùng đổi mật khẩu
- **Theo dõi đăng nhập**: Ghi nhận lịch sử đăng nhập

#### 3.2.2. Quản lý Nhập hàng
- **Danh sách đơn nhập hàng**: Hiển thị, tìm kiếm, lọc đơn nhập từ nhà cung cấp
- **Tạo đơn nhập hàng**: Chọn nhà cung cấp, kho, thêm sản phẩm, giá nhập, thuế, chiết khấu
- **Quản lý trạng thái đơn nhập hàng**: Theo dõi trạng thái đơn
- **Quản lý thanh toán nhà cung cấp**: Theo dõi trạng thái thanh toán
- **In phiếu nhập kho**: Tạo và in phiếu nhập kho

#### 3.2.3. Quản lý Thu chi
- **Phiếu thu**: Tạo phiếu thu từ khách hàng
- **Phiếu chi**: Tạo phiếu chi cho nhà cung cấp hoặc chi phí hoạt động
- **Quản lý danh mục thu chi**: Phân loại các khoản thu/chi theo danh mục
- **Báo cáo thu chi**: Báo cáo dòng tiền, theo danh mục, theo đối tượng
- **Quản lý công nợ**: Theo dõi công nợ khách hàng, nhà cung cấp

#### 3.2.4. Quản lý Kho hàng
- **Quản lý nhiều kho**: Thêm/sửa/xóa kho hàng, chỉ định kho mặc định
- **Quản lý tồn kho theo kho**: Xem tồn kho theo từng kho, lọc sản phẩm
- **Điều chỉnh tồn kho**: Tăng/giảm số lượng sản phẩm trong kho, ghi nhận lý do
- **Báo cáo kho**: Báo cáo tồn kho, sản phẩm sắp hết, tồn đọng, giá trị tồn kho

#### 3.2.5. Quản lý Chuyển kho
- **Tạo phiếu chuyển kho**: Chọn kho nguồn, kho đích, sản phẩm, số lượng
- **Quản lý trạng thái chuyển kho**: Theo dõi trạng thái
- **Nhận hàng chuyển kho**: Xác nhận số lượng nhận thực tế, ghi nhận chênh lệch
- **Báo cáo chuyển kho**: Báo cáo số lượng chuyển kho theo thời gian, sản phẩm, kho

#### 3.2.6. Báo cáo và Thống kê nâng cao
- **Báo cáo doanh thu**: Theo thời gian, sản phẩm, danh mục, khách hàng
- **Báo cáo lợi nhuận**: Doanh thu, chi phí, lợi nhuận gộp/ròng
- **Báo cáo nhập xuất tồn**: Số lượng nhập/xuất/tồn theo thời gian, sản phẩm
- **Báo cáo công nợ**: Công nợ khách hàng, nhà cung cấp, quá hạn
- **Biểu đồ và trực quan hóa dữ liệu**: Hiển thị dữ liệu dưới dạng biểu đồ

### 3.3. Chức năng đề xuất mở rộng (Giai đoạn 3)

#### 3.3.1. Ứng dụng di động
- **App bán hàng**: Dành cho nhân viên bán hàng
- **App quản lý kho**: Dành cho nhân viên kho
- **App khách hàng**: Dành cho khách hàng để theo dõi đơn hàng và bảo hành

#### 3.3.2. Tích hợp hệ thống
- **Tích hợp thanh toán trực tuyến**: Kết nối với cổng thanh toán
- **Tích hợp vận chuyển**: Kết nối với đơn vị vận chuyển
- **Tích hợp kế toán**: Kết nối với phần mềm kế toán

#### 3.3.3. Hệ thống thông minh
- **Dự báo bán hàng**: Dự đoán doanh số dựa trên dữ liệu lịch sử
- **Đề xuất sản phẩm**: Gợi ý sản phẩm liên quan cho khách hàng
- **Quản lý chiến dịch tiếp thị**: Theo dõi hiệu quả các chiến dịch

#### 3.3.4. Bán hàng đa kênh
- **Cửa hàng trực tuyến**: Website bán hàng cho khách hàng
- **Tích hợp sàn thương mại điện tử**: Kết nối với các sàn như Shopee, Lazada
- **Quản lý đơn hàng đa kênh**: Quản lý đơn hàng từ nhiều nguồn

#### 3.3.5. Trung tâm hỗ trợ khách hàng
- **Ticket hỗ trợ**: Quản lý yêu cầu hỗ trợ từ khách hàng
- **Live chat**: Hỗ trợ khách hàng trực tuyến
- **Khảo sát khách hàng**: Đánh giá mức độ hài lòng

## 4. PHÂN QUYỀN HỆ THỐNG

### 4.1. Vai trò (Roles)

| Vai trò | Mô tả |
|---------|-------|
| **admin** | Quản trị viên, có toàn quyền trên hệ thống |
| **manager** | Quản lý, có quyền quản lý hầu hết chức năng, trừ một số chức năng quản trị |
| **staff** | Nhân viên, có quyền sử dụng các chức năng cơ bản |
| **cashier** | Thu ngân, chủ yếu sử dụng POS và quản lý đơn hàng |
| **accountant** | Kế toán, quản lý thu chi, báo cáo tài chính |
| **storekeeper** | Thủ kho, quản lý kho hàng và chuyển kho |

### 4.2. Quyền (Permissions)

| Nhóm | Quyền | Mô tả |
|------|------|-------|
| **Sản phẩm** | viewProduct | Xem sản phẩm |
|  | createProduct | Thêm sản phẩm |
|  | updateProduct | Cập nhật sản phẩm |
|  | deleteProduct | Xóa sản phẩm |
| **Danh mục** | viewCategory | Xem danh mục |
|  | createCategory | Thêm danh mục |
|  | updateCategory | Cập nhật danh mục |
|  | deleteCategory | Xóa danh mục |
| **Khách hàng** | viewCustomer | Xem khách hàng |
|  | createCustomer | Thêm khách hàng |
|  | updateCustomer | Cập nhật khách hàng |
|  | deleteCustomer | Xóa khách hàng |
| **Đơn hàng** | viewOrder | Xem đơn hàng |
|  | createOrder | Tạo đơn hàng |
|  | updateOrder | Cập nhật đơn hàng |
|  | deleteOrder | Xóa đơn hàng |
|  | accessPos | Truy cập POS |
| **Bảo hành** | viewWarranty | Xem bảo hành |
|  | createWarranty | Thêm bảo hành |
|  | updateWarranty | Cập nhật bảo hành |
|  | deleteWarranty | Xóa bảo hành |
| **Nhà cung cấp** | viewSupplier | Xem nhà cung cấp |
|  | createSupplier | Thêm nhà cung cấp |
|  | updateSupplier | Cập nhật nhà cung cấp |
|  | deleteSupplier | Xóa nhà cung cấp |
| **Người dùng** | viewUser | Xem người dùng |
|  | createUser | Thêm người dùng |
|  | updateUser | Cập nhật người dùng |
|  | deleteUser | Xóa người dùng |
| **Nhập hàng** | viewPurchase | Xem đơn nhập hàng |
|  | createPurchase | Tạo đơn nhập hàng |
|  | approvePurchase | Duyệt đơn nhập hàng |
| **Thu chi** | viewFinance | Xem phiếu thu chi |
|  | createReceipt | Tạo phiếu thu |
|  | createPayment | Tạo phiếu chi |
|  | approvePayment | Duyệt phiếu chi |
| **Kho hàng** | viewWarehouse | Xem kho hàng |
|  | manageWarehouse | Quản lý kho hàng |
|  | adjustStock | Điều chỉnh tồn kho |
| **Chuyển kho** | viewTransfer | Xem phiếu chuyển kho |
|  | createTransfer | Tạo phiếu chuyển kho |
|  | approveTransfer | Duyệt phiếu chuyển kho |
|  | receiveTransfer | Nhận hàng chuyển kho |
| **Báo cáo** | viewReport | Xem báo cáo |
| **Phân quyền** | manageRbac | Quản lý phân quyền |

## 5. HƯỚNG DẪN CÀI ĐẶT

### 5.1. Yêu cầu hệ thống
- PHP >= 8.0
- MySQL >= 5.7 hoặc MariaDB >= 10.4
- Composer
- Web server (Apache/Nginx)
- Các extension PHP cần thiết: PDO, GD, Intl, Mbstring, JSON

### 5.2. Các bước cài đặt
1. **Chuẩn bị môi trường**:
   - Cài đặt XAMPP hoặc môi trường tương tự
   - Tạo cơ sở dữ liệu MySQL mới

2. **Cài đặt ứng dụng**:
   ```bash
   # Sao chép mã nguồn
   git clone [repository_url] toanlb
   cd toanlb

   # Cài đặt các phụ thuộc
   composer install

   # Cấu hình môi trường
   cp .env.example .env
   # Chỉnh sửa file .env để cấu hình kết nối CSDL

   # Khởi tạo ứng dụng
   php init --env=Development --overwrite=All

   # Tạo cơ sở dữ liệu
   php yii migrate
   ```

3. **Cấu hình web server**:
   - Trỏ document root đến thư mục `backend/web`
   - Đảm bảo mod_rewrite đã được bật (Apache)

4. **Tạo tài khoản admin**:
   ```bash
   php yii user/create-admin
   ```

5. **Truy cập hệ thống**:
   - Truy cập backend: `http://localhost/backend/web/`
   - Đăng nhập với tài khoản admin vừa tạo

### 5.3. Cấu hình
- **Cơ sở dữ liệu**: Cấu hình trong `common/config/main-local.php`
- **URL**: Cấu hình trong `backend/config/main.php`
- **Ngôn ngữ và múi giờ**: Cấu hình trong `common/config/main.php`

## 6. HƯỚNG DẪN SỬ DỤNG

### 6.1. Đăng nhập và Quản lý tài khoản
- **Đăng nhập**: Nhập username và password để đăng nhập vào hệ thống
- **Thông tin cá nhân**: Cập nhật thông tin cá nhân, đổi mật khẩu
- **Đăng xuất**: Kết thúc phiên làm việc

### 6.2. Quản lý sản phẩm
- **Xem danh sách sản phẩm**: Truy cập menu "Quản lý sản phẩm" > "Danh sách sản phẩm"
- **Thêm sản phẩm mới**: Nhấn "Thêm mới" và điền thông tin sản phẩm
- **Cập nhật sản phẩm**: Nhấn biểu tượng chỉnh sửa và cập nhật thông tin
- **Xóa sản phẩm**: Nhấn biểu tượng xóa và xác nhận
- **Quản lý danh mục**: Truy cập menu "Quản lý sản phẩm" > "Danh mục sản phẩm"
- **Quản lý đơn vị tính**: Truy cập menu "Quản lý sản phẩm" > "Đơn vị tính"

### 6.3. Quản lý khách hàng
- **Xem danh sách khách hàng**: Truy cập menu "Quản lý khách hàng"
- **Thêm khách hàng mới**: Nhấn "Thêm mới" và điền thông tin khách hàng
- **Cập nhật khách hàng**: Nhấn biểu tượng chỉnh sửa và cập nhật thông tin
- **Xóa khách hàng**: Nhấn biểu tượng xóa và xác nhận
- **Xem lịch sử đơn hàng**: Trong trang chi tiết khách hàng, xem tab "Lịch sử đơn hàng"

### 6.4. Quản lý đơn hàng
- **Xem danh sách đơn hàng**: Truy cập menu "Quản lý đơn hàng"
- **Tạo đơn hàng mới**: Nhấn "Tạo đơn hàng", chọn khách hàng và thêm sản phẩm
- **Xem chi tiết đơn hàng**: Nhấn vào mã đơn hàng để xem chi tiết
- **In hóa đơn**: Trong trang chi tiết đơn hàng, nhấn "In hóa đơn"

### 6.5. Sử dụng POS (Bán hàng)
- **Truy cập màn hình POS**: Truy cập menu "Bán hàng (POS)"
- **Thêm sản phẩm vào giỏ hàng**: Tìm kiếm sản phẩm và thêm vào giỏ
- **Điều chỉnh số lượng**: Thay đổi số lượng sản phẩm trong giỏ hàng
- **Áp dụng giảm giá**: Chọn loại giảm giá và nhập giá trị
- **Thanh toán**: Chọn phương thức thanh toán và hoàn tất
- **In hóa đơn**: Sau khi thanh toán, in hóa đơn cho khách hàng

### 6.6. Quản lý bảo hành
- **Xem danh sách bảo hành**: Truy cập menu "Quản lý bảo hành"
- **Thêm thông tin bảo hành**: Nhấn "Thêm mới" và điền thông tin bảo hành
- **Cập nhật bảo hành**: Nhấn biểu tượng chỉnh sửa và cập nhật thông tin
- **Xóa bảo hành**: Nhấn biểu tượng xóa và xác nhận
- **Ghi nhận sửa chữa**: Trong trang chi tiết bảo hành, nhấn "Thêm sửa chữa"

### 6.7. Quản lý nhà cung cấp
- **Xem danh sách nhà cung cấp**: Truy cập menu "Quản lý nhà cung cấp"
- **Thêm nhà cung cấp mới**: Nhấn "Thêm mới" và điền thông tin nhà cung cấp
- **Cập nhật nhà cung cấp**: Nhấn biểu tượng chỉnh sửa và cập nhật thông tin
- **Xóa nhà cung cấp**: Nhấn biểu tượng xóa và xác nhận

### 6.8. Quản lý người dùng
- **Xem danh sách người dùng**: Truy cập menu "Quản lý người dùng"
- **Thêm người dùng mới**: Nhấn "Thêm mới" và điền thông tin người dùng
- **Cập nhật người dùng**: Nhấn biểu tượng chỉnh sửa và cập nhật thông tin
- **Xóa người dùng**: Nhấn biểu tượng xóa và xác nhận
- **Quản lý vai trò**: Gán vai trò cho người dùng

## 7. PHÁT TRIỂN VÀ MỞ RỘNG

### 7.1. Hướng dẫn phát triển
- **Tạo controller mới**:
  ```bash
  php yii gii/controller --controllerClass=backend\\controllers\\NewController
  ```

- **Tạo model mới**:
  ```bash
  php yii gii/model --tableName=new_table --modelClass=NewModel
  ```

- **Tạo CRUD**:
  ```bash
  php yii gii/crud --modelClass=common\\models\\NewModel --controllerClass=backend\\controllers\\NewController
  ```

### 7.2. Quy ước và tiêu chuẩn
- **Coding Standards**: Tuân thủ PSR-2
- **Đặt tên file**: Sử dụng PascalCase cho model và controller, camelCase cho action
- **Đặt tên biến**: Sử dụng camelCase
- **Comment**: Viết comment đầy đủ và rõ ràng
- **Ghi log**: Sử dụng Yii::info(), Yii::warning(), Yii::error()

### 7.3. Môi trường
- **Development**: Môi trường phát triển, bật debug và gii
- **Testing**: Môi trường kiểm thử
- **Production**: Môi trường thực tế, tắt debug và gii

## 8. XỬ LÝ SỰ CỐ

### 8.1. Các vấn đề thường gặp
- **Lỗi truy cập trang:** Kiểm tra cấu hình URL và mod_rewrite
- **Lỗi CSRF:** Đảm bảo form có token CSRF hợp lệ
- **Lỗi phân quyền:** Kiểm tra cấu hình RBAC và quyền người dùng
- **Lỗi định dạng tiền tệ:** Cài đặt PHP Intl extension

### 8.2. Hướng dẫn gỡ lỗi
- **Bật debug mode**: Trong `common/config/main-local.php`
  ```php
  'components' => [
      'log' => [
          'traceLevel' => YII_DEBUG ? 3 : 0,
      ],
  ],
  ```
- **Kiểm tra logs**: Trong `backend/runtime/logs/`
- **Sử dụng Yii Debug Toolbar**: Xem thông tin chi tiết về request, query, memory usage

## 9. THÔNG TIN LIÊN HỆ

- **Người phát triển:** [Tên người phát triển]
- **Email hỗ trợ:** [Email hỗ trợ]
- **Số điện thoại:** [Số điện thoại hỗ trợ]
- **Website:** [Website]

---

*© 2025 [Tên công ty]. Bản quyền đã được bảo hộ.*
