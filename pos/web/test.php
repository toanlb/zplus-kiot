<?php
// File kiểm tra cấu trúc thư mục và cài đặt

// Thông tin phiên bản PHP
echo '<h2>Thông tin PHP</h2>';
echo 'PHP Version: ' . phpversion() . '<br>';

// Đường dẫn thư mục
echo '<h2>Đường dẫn thư mục</h2>';
echo 'Document Root: ' . $_SERVER['DOCUMENT_ROOT'] . '<br>';
echo 'Script Filename: ' . $_SERVER['SCRIPT_FILENAME'] . '<br>';
echo 'Current directory: ' . getcwd() . '<br>';
echo 'Parent directory: ' . dirname(getcwd()) . '<br>';

// Kiểm tra sự tồn tại của các thư mục quan trọng
echo '<h2>Kiểm tra thư mục</h2>';
$baseDir = dirname(__DIR__); // Thư mục gốc của module pos
echo 'Base Directory: ' . $baseDir . '<br>';

$dirs = [
    'controllers',
    'models',
    'views',
    'views/site',
    'views/layouts',
    'config'
];

foreach ($dirs as $dir) {
    $fullPath = $baseDir . '/' . $dir;
    echo $dir . ': ' . (is_dir($fullPath) ? 'Tồn tại' : 'Không tồn tại') . ' - ' . $fullPath . '<br>';
}

// Kiểm tra sự tồn tại của các file quan trọng
echo '<h2>Kiểm tra file</h2>';
$files = [
    'controllers/SiteController.php',
    'controllers/PosController.php',
    'config/main.php',
    'config/web.php',
    'views/site/index.php',
    'views/site/error.php',
    'views/layouts/main.php'
];

foreach ($files as $file) {
    $fullPath = $baseDir . '/' . $file;
    echo $file . ': ' . (file_exists($fullPath) ? 'Tồn tại' : 'Không tồn tại') . ' - ' . $fullPath . '<br>';
    
    // Hiển thị nội dung 10 dòng đầu của file SiteController
    if ($file == 'controllers/SiteController.php' && file_exists($fullPath)) {
        echo '<pre>';
        $lines = file($fullPath);
        for ($i = 0; $i < min(10, count($lines)); $i++) {
            echo htmlspecialchars($lines[$i]);
        }
        echo '</pre>';
    }
}

// Kiểm tra autoloader
echo '<h2>Kiểm tra Autoloader</h2>';
try {
    $reflector = new ReflectionClass('pos\controllers\SiteController');
    echo 'SiteController path: ' . $reflector->getFileName() . '<br>';
} catch (Exception $e) {
    echo 'Error loading SiteController: ' . $e->getMessage() . '<br>';
}

// Hiển thị include path
echo '<h2>Include Path</h2>';
echo get_include_path() . '<br>';

// Hiển thị thông tin Yii
echo '<h2>Yii Information</h2>';
if (class_exists('Yii')) {
    echo 'Yii Version: ' . Yii::getVersion() . '<br>';
} else {
    echo 'Yii class not found<br>';
}