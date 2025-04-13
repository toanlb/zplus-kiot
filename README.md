<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Advanced Project Template</h1>
    <br>
</p>

Yii 2 Advanced Project Template is a skeleton [Yii 2](https://www.yiiframework.com/) application best for
developing complex Web applications with multiple tiers.

The template includes three tiers: front end, back end, and console, each of which
is a separate Yii application.

The template is designed to work in a team development environment. It supports
deploying the application in different environments.

Documentation is at [docs/guide/README.md](docs/guide/README.md).

[![Latest Stable Version](https://img.shields.io/packagist/v/yiisoft/yii2-app-advanced.svg)](https://packagist.org/packages/yiisoft/yii2-app-advanced)
[![Total Downloads](https://img.shields.io/packagist/dt/yiisoft/yii2-app-advanced.svg)](https://packagist.org/packages/yiisoft/yii2-app-advanced)
[![build](https://github.com/yiisoft/yii2-app-advanced/workflows/build/badge.svg)](https://github.com/yiisoft/yii2-app-advanced/actions?query=workflow%3Abuild)

DIRECTORY STRUCTURE
-------------------

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
    tests/               contains tests for common classes    
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for backend application    
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for frontend application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
```

Tóm tắt dự án Hệ thống Quản lý Bán hàng
Tổng quan dự án
Dự án là một hệ thống quản lý bán hàng sử dụng PHP, Yii2 Framework và MySQL, với giao diện AdminLTE 3. Hệ thống được phát triển để quản lý hoạt động bán hàng, sản phẩm, khách hàng, đơn hàng, nhà cung cấp và bảo hành.
Checklist các hạng mục
Đã hoàn thành

✅ Khởi tạo dự án Yii2 Advanced
✅ Cấu hình kết nối cơ sở dữ liệu MySQL
✅ Thiết lập giao diện AdminLTE 3 với Bootstrap 4 và Font Awesome 6
✅ Phát triển module quản lý sản phẩm
✅ Phát triển module quản lý danh mục sản phẩm
✅ Phát triển module quản lý đơn vị tính
✅ Phát triển module quản lý khách hàng
✅ Phát triển module quản lý đơn hàng
✅ Phát triển module bán hàng (POS)
✅ Phát triển module quản lý nhà cung cấp
✅ Phát triển module quản lý bảo hành
✅ Thiết lập hệ thống phân quyền RBAC (cơ bản)

Đang tiến hành

⏳ Hoàn thiện module quản lý bảo hành

⏳ Cải thiện tính năng theo dõi lịch sử bảo hành
⏳ Tối ưu hóa liên kết với module sản phẩm và đơn hàng


⏳ Hoàn thiện hệ thống phân quyền RBAC

⏳ Sửa lỗi không truy cập được /rbac/index (đổi tên controller)
⏳ Áp dụng kiểm tra quyền cho tất cả các controller
⏳ Cập nhật sidebar để hiển thị menu theo quyền



Chưa thực hiện

❌ Phát triển module báo cáo và thống kê

❌ Báo cáo doanh thu
❌ Báo cáo hàng tồn kho
❌ Báo cáo lợi nhuận
❌ Báo cáo theo khách hàng
❌ Biểu đồ trực quan hóa dữ liệu


❌ Tích hợp xuất dữ liệu (PDF, Excel)
❌ Tối ưu hóa hiệu suất

❌ Cải thiện truy vấn cơ sở dữ liệu
❌ Tối ưu hóa cache
❌ Tối ưu hóa tài nguyên frontend


❌ Kiểm thử và sửa lỗi
❌ Tạo tài liệu hướng dẫn sử dụng

Hướng phát triển tiếp theo

Hoàn thiện module hiện có:

Sửa lỗi còn tồn tại trong module RBAC và các module khác
Hoàn thiện giao diện người dùng, cải thiện trải nghiệm


Phát triển module báo cáo:

Xây dựng giao diện dashboard tổng quan
Phát triển các báo cáo chi tiết theo yêu cầu


Tối ưu hóa hệ thống:

Tăng tốc độ truy vấn dữ liệu
Cải thiện hiệu suất tổng thể


Kiểm thử:

Kiểm thử các chức năng
Kiểm thử hiệu suất
Kiểm thử bảo mật


Triển khai:

Chuẩn bị môi trường sản xuất
Đào tạo người dùng
Viết tài liệu hướng dẫn



Dự án đã đạt được tiến độ tốt với việc hoàn thành các module cốt lõi. Các module còn lại cần được phát triển và hoàn thiện trong thời gian tới để đưa hệ thống vào sử dụng.
