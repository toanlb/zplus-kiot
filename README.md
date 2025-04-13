# TÀI LIỆU DỰ ÁN HỆ THỐNG QUẢN LÝ BÁN HÀNG

## 1. TỔNG QUAN DỰ ÁN

### 1.1. Giới thiệu
Hệ thống Quản lý Bán hàng là một ứng dụng web được phát triển để giúp doanh nghiệp quản lý hoạt động bán hàng, từ quản lý sản phẩm, khách hàng, đơn hàng đến bảo hành và nhà cung cấp. Hệ thống được xây dựng trên nền tảng PHP với framework Yii2 Advanced và cơ sở dữ liệu MySQL.

### 1.2. Mục tiêu
- Xây dựng hệ thống quản lý bán hàng toàn diện
- Tự động hóa quy trình bán hàng từ đặt hàng đến giao hàng và bảo hành
- Theo dõi thông tin khách hàng, sản phẩm và nhà cung cấp
- Cung cấp các báo cáo và thống kê để hỗ trợ ra quyết định kinh doanh
- Phân quyền người dùng để đảm bảo an toàn dữ liệu

### 1.3. Công nghệ sử dụng
- **Ngôn ngữ lập trình**: PHP 8.0.30
- **Framework**: Yii2 Advanced
- **Cơ sở dữ liệu**: MySQL 10.4.32 (MariaDB)
- **Giao diện người dùng**: AdminLTE 3, Bootstrap 4, Font Awesome 6
- **Môi trường phát triển**: XAMPP

## 2. KIẾN TRÚC HỆ THỐNG

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
Hệ thống sử dụng cơ sở dữ liệu quan hệ MySQL với các bảng chính:
- **products**: Quản lý thông tin sản phẩm
- **product_categories**: Quản lý danh mục sản phẩm
- **product_units**: Quản lý đơn vị tính
- **customers**: Quản lý thông tin khách hàng
- **orders**: Quản lý đơn hàng
- **order_items**: Chi tiết sản phẩm trong đơn hàng
- **order_payments**: Thông tin thanh toán
- **product_warranties**: Quản lý bảo hành sản phẩm
- **warranty_repair_logs**: Lịch sử sửa chữa bảo hành
- **suppliers**: Quản lý nhà cung cấp
- **auth_***: Các bảng phân quyền RBAC

## 3. CHỨC NĂNG HỆ THỐNG

### 3.1. Quản lý Sản phẩm
- **Danh sách sản phẩm**: Hiển thị, tìm kiếm, lọc và phân trang
- **Thêm/sửa/xóa sản phẩm**: Quản lý thông tin sản phẩm
- **Quản lý danh mục**: Phân loại sản phẩm theo danh mục
- **Quản lý đơn vị tính**: Đơn vị tính cho sản phẩm

### 3.2. Quản lý Khách hàng
- **Danh sách khách hàng**: Hiển thị, tìm kiếm, lọc và phân trang
- **Thêm/sửa/xóa khách hàng**: Quản lý thông tin khách hàng
- **Lịch sử đơn hàng**: Xem lịch sử mua hàng của khách
- **Điểm tích lũy và công nợ**: Theo dõi điểm thưởng và nợ của khách hàng

### 3.3. Quản lý Đơn hàng
- **Danh sách đơn hàng**: Hiển thị, tìm kiếm và lọc
- **Tạo đơn hàng mới**: Thêm nhiều sản phẩm vào đơn hàng
- **Thanh toán đa phương thức**: Hỗ trợ nhiều hình thức thanh toán
- **In hóa đơn**: Tạo và in hóa đơn bán hàng

### 3.4. Bán hàng (POS)
- **Giao diện bán hàng**: Màn hình POS trực quan
- **Tìm kiếm sản phẩm**: Tìm kiếm nhanh chóng
- **Quản lý giỏ hàng**: Thêm, sửa, xóa sản phẩm trong giỏ hàng
- **Áp dụng giảm giá**: Áp dụng các loại giảm giá
- **Thanh toán**: Hỗ trợ nhiều phương thức thanh toán
- **In hóa đơn**: In hóa đơn bán hàng

### 3.5. Quản lý Bảo hành
- **Danh sách bảo hành**: Theo dõi tình trạng bảo hành
- **Thêm/sửa/xóa bảo hành**: Quản lý thông tin bảo hành
- **Lịch sử sửa chữa**: Theo dõi các lần sửa chữa
- **Thông báo hết hạn**: Cảnh báo bảo hành sắp hết hạn

### 3.6. Quản lý Nhà cung cấp
- **Danh sách nhà cung cấp**: Hiển thị, tìm kiếm và lọc
- **Thêm/sửa/xóa nhà cung cấp**: Quản lý thông tin nhà cung cấp
- **Thống kê giao dịch**: Theo dõi hoạt động mua hàng từ nhà cung cấp

### 3.7. Phân quyền RBAC
- **Quản lý vai trò**: Tạo và quản lý các vai trò người dùng
- **Quản lý quyền**: Gán quyền cho từng vai trò
- **Gán vai trò**: Gán vai trò cho người dùng
- **Kiểm soát truy cập**: Hạn chế quyền truy cập vào các chức năng

## 4. HƯỚNG DẪN CÀI ĐẶT

### 4.1. Yêu cầu hệ thống
- PHP >= 8.0
- MySQL >= 5.7 hoặc MariaDB >= 10.4
- Composer
- Web server (Apache/Nginx)
- Các extension PHP cần thiết: PDO, GD, Intl, Mbstring, JSON

### 4.2. Cài đặt
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

4. **Truy cập hệ thống**:
   - Truy cập backend: `http://localhost/toanlb/backend/web/`
   - Đăng nhập với tài khoản mặc định:
     - Username: admin
     - Password: admin123

### 4.3. Cấu hình
- **Cơ sở dữ liệu**: Cấu hình trong `common/config/main-local.php`
- **URL**: Cấu hình trong `backend/config/main.php`
- **Ngôn ngữ và múi giờ**: Cấu hình trong `common/config/main.php`

## 5. HƯỚNG DẪN SỬ DỤNG

### 5.1. Đăng nhập và Trang chủ
1. Truy cập vào hệ thống qua URL: `http://localhost/toanlb/backend/web/`
2. Đăng nhập bằng tài khoản được cung cấp
3. Trang chủ hiển thị bảng điều khiển với các thông số tổng quan

### 5.2. Quản lý Sản phẩm
1. **Xem danh sách sản phẩm**:
   - Truy cập menu "Quản lý sản phẩm" > "Danh sách sản phẩm"
   - Có thể tìm kiếm, lọc và sắp xếp

2. **Thêm sản phẩm mới**:
   - Nhấn nút "Thêm mới" trên trang danh sách
   - Điền thông tin sản phẩm và lưu

3. **Quản lý danh mục**:
   - Truy cập menu "Quản lý sản phẩm" > "Danh mục sản phẩm"
   - Thêm, sửa, xóa danh mục

### 5.3. Quản lý Khách hàng
1. **Xem danh sách khách hàng**:
   - Truy cập menu "Quản lý khách hàng"
   - Tìm kiếm, lọc theo nhiều tiêu chí

2. **Thêm khách hàng mới**:
   - Nhấn nút "Thêm mới"
   - Điền thông tin và lưu

3. **Xem lịch sử đơn hàng của khách hàng**:
   - Vào chi tiết khách hàng
   - Xem tab "Lịch sử đơn hàng"

### 5.4. Quản lý Đơn hàng
1. **Xem danh sách đơn hàng**:
   - Truy cập menu "Quản lý đơn hàng"
   - Tìm kiếm, lọc theo trạng thái, thời gian

2. **Tạo đơn hàng mới**:
   - Nhấn nút "Tạo đơn hàng"
   - Chọn khách hàng
   - Thêm sản phẩm vào đơn hàng
   - Chọn phương thức thanh toán và hoàn tất

3. **Xem chi tiết đơn hàng**:
   - Nhấn vào mã đơn hàng
   - Xem thông tin chi tiết
   - In hóa đơn nếu cần

### 5.5. Sử dụng POS
1. **Truy cập màn hình POS**:
   - Truy cập menu "Bán hàng (POS)"

2. **Thực hiện bán hàng**:
   - Tìm kiếm và thêm sản phẩm vào giỏ hàng
   - Điều chỉnh số lượng nếu cần
   - Áp dụng giảm giá (nếu có)
   - Chọn phương thức thanh toán
   - Hoàn tất và in hóa đơn

### 5.6. Quản lý Bảo hành
1. **Xem danh sách bảo hành**:
   - Truy cập menu "Quản lý bảo hành"
   - Tìm kiếm theo serial, sản phẩm, khách hàng

2. **Thêm thông tin bảo hành**:
   - Nhấn nút "Thêm mới"
   - Chọn sản phẩm, khách hàng và nhập thông tin bảo hành
   - Lưu thông tin

3. **Ghi nhận sửa chữa**:
   - Vào chi tiết bảo hành
   - Nhấn "Thêm sửa chữa"
   - Điền thông tin sửa chữa và lưu

### 5.7. Quản lý Nhà cung cấp
1. **Xem danh sách nhà cung cấp**:
   - Truy cập menu "Quản lý nhà cung cấp"
   - Tìm kiếm và lọc

2. **Thêm nhà cung cấp mới**:
   - Nhấn nút "Thêm mới"
   - Điền thông tin nhà cung cấp và lưu

### 5.8. Quản lý Phân quyền
1. **Xem danh sách vai trò và quyền**:
   - Truy cập menu "Quản lý phân quyền"

2. **Cập nhật quyền cho vai trò**:
   - Chọn vai trò cần cập nhật
   - Điều chỉnh quyền và lưu

3. **Gán vai trò cho người dùng**:
   - Nhấn "Gán vai trò"
   - Chọn người dùng và vai trò cần gán

## 6. PHÁT TRIỂN VÀ MỞ RỘNG

### 6.1. Các module đang phát triển
- **Module báo cáo và thống kê**:
  - Báo cáo doanh thu
  - Báo cáo tồn kho
  - Báo cáo lợi nhuận
  - Biểu đồ trực quan

- **Xuất dữ liệu**:
  - Xuất báo cáo PDF
  - Xuất dữ liệu Excel

### 6.2. Hướng phát triển tương lai
- **Tích hợp cổng thanh toán trực tuyến**
- **Ứng dụng di động** cho nhân viên bán hàng
- **Cổng thông tin khách hàng** để theo dõi đơn hàng và bảo hành
- **Tích hợp với các dịch vụ vận chuyển**
- **Hệ thống quản lý kho nâng cao** với nhiều kho và di chuyển hàng

### 6.3. Hướng dẫn phát triển
1. **Tạo module mới**:
   ```bash
   # Tạo controller mới
   php yii gii/controller --controllerClass=backend\\controllers\\NewController

   # Tạo model mới
   php yii gii/model --tableName=new_table --modelClass=NewModel

   # Tạo CRUD
   php yii gii/crud --modelClass=common\\models\\NewModel --controllerClass=backend\\controllers\\NewController
   ```

2. **Quy tắc phát triển**:
   - Tuân thủ mô hình MVC của Yii2
   - Sử dụng các widget có sẵn của Yii2
   - Chuẩn hóa code theo PSR-2
   - Viết comment đầy đủ

## 7. XỬ LÝ SỰ CỐ

### 7.1. Các vấn đề thường gặp
1. **Lỗi truy cập trang:** Kiểm tra cấu hình URL và mod_rewrite
2. **Lỗi CSRF:** Đảm bảo form có token CSRF hợp lệ
3. **Lỗi phân quyền:** Kiểm tra cấu hình RBAC và quyền người dùng
4. **Lỗi định dạng tiền tệ:** Cài đặt PHP Intl extension

### 7.2. Gỡ lỗi
- Bật chế độ debug trong `common/config/main-local.php`:
  ```php
  'components' => [
      'log' => [
          'traceLevel' => YII_DEBUG ? 3 : 0,
      ],
  ],
  ```
- Kiểm tra logs trong `backend/runtime/logs/`

## 8. THÔNG TIN LIÊN HỆ

- **Người phát triển:** toanlb
- **Email hỗ trợ:** toan@zplus.vn
- **Số điện thoại:** 0888.3333.58
- **Website:** https://zin100.vn

---

*© 2025 [Zin100 Authentic]. Bản quyền đã được bảo hộ.*
