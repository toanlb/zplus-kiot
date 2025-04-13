<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'companyName' => 'POS Bán Hàng',
    'companyAddress' => '123 Đường ABC, Quận XYZ, TP.HCM',
    'companyPhone' => '0123 456 789',
    'companyTaxCode' => '0123456789',
    
    // Cấu hình cho POS
    'pos' => [
        // Cấu hình chung
        'name' => 'POS Bán Hàng',
        'version' => '1.0.0',
        'logoPath' => '/images/logo.png',
        'defaultCurrency' => 'VND',
        'defaultLanguage' => 'vi-VN',
        
        // Cấu hình giao diện
        'itemsPerPage' => 24, // Số sản phẩm hiển thị trên một trang
        'defaultView' => 'grid', // Chế độ xem mặc định: 'grid' hoặc 'list'
        'defaultTheme' => 'light', // Chủ đề mặc định: 'light' hoặc 'dark'
        
        // Cấu hình thanh toán
        'paymentMethods' => [
            'cash' => [
                'name' => 'Tiền mặt',
                'icon' => 'fas fa-money-bill-wave',
                'enabled' => true,
            ],
            'bank_transfer' => [
                'name' => 'Chuyển khoản',
                'icon' => 'fas fa-university',
                'enabled' => true,
                'bankInfo' => [
                    'bankName' => 'Vietcombank',
                    'accountNumber' => '1234567890',
                    'accountName' => 'CÔNG TY TNHH ABC',
                ]
            ],
            'card' => [
                'name' => 'Thẻ',
                'icon' => 'fas fa-credit-card',
                'enabled' => true,
            ],
            'momo' => [
                'name' => 'Ví MoMo',
                'icon' => 'fas fa-wallet',
                'enabled' => true,
            ],
            'vnpay' => [
                'name' => 'VNPay',
                'icon' => 'fas fa-qrcode',
                'enabled' => true,
            ],
            'credit' => [
                'name' => 'Công nợ',
                'icon' => 'fas fa-handshake',
                'enabled' => true,
                'requireCustomer' => true, // Yêu cầu chọn khách hàng khi thanh toán công nợ
            ],
        ],
        
        // Cấu hình in ấn
        'printing' => [
            'enabled' => true,
            'defaultPrinter' => 'default',
            'paperSize' => '80mm',
            'autoPrint' => true, // Tự động in sau khi thanh toán
        ],
        
        // Cấu hình offline
        'offline' => [
            'enabled' => true,
            'syncInterval' => 60, // Thời gian đồng bộ dữ liệu (giây) khi có kết nối
            'maxOfflineOrders' => 100, // Số lượng đơn hàng tối đa có thể lưu khi offline
        ],
        
        // Cấu hình bảo mật
        'security' => [
            'sessionTimeout' => 3600, // Thời gian hết hạn phiên (giây)
            'requirePinForDiscount' => true, // Yêu cầu PIN khi áp dụng giảm giá
            'requirePinForVoid' => true, // Yêu cầu PIN khi hủy đơn hàng
            'requirePinForRefund' => true, // Yêu cầu PIN khi hoàn tiền
        ],
    ],
];