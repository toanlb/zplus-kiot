<?php
// Tham số cấu hình local, ghi đè các tham số mặc định trong params.php
return [
    // Cấu hình đặc thù cho môi trường cục bộ
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    
    // Các cấu hình đặc thù cho môi trường local
    'pos' => [
        // Ghi đè cấu hình trong params.php nếu cần
    ],
];