<?php
// Debug file để kiểm tra thiết lập controllers

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';

// Set alias
Yii::setAlias('@pos', dirname(__DIR__));

// Kiểm tra controller class có tồn tại không
echo "<h1>Controller Check</h1>";
$controllerClass = 'pos\controllers\SiteController';

echo "Controller Class: $controllerClass<br>";
echo "Class exists: " . (class_exists($controllerClass) ? 'Yes' : 'No') . "<br>";

// Hiển thị namespace định nghĩa trong controller file
$controllerFile = dirname(__DIR__) . '/controllers/SiteController.php';
echo "Controller File: $controllerFile<br>";
echo "File exists: " . (file_exists($controllerFile) ? 'Yes' : 'No') . "<br>";

if (file_exists($controllerFile)) {
    $content = file_get_contents($controllerFile);
    echo "<h2>Controller Content (First 20 lines)</h2>";
    echo "<pre>";
    $lines = explode("\n", $content);
    for ($i = 0; $i < min(20, count($lines)); $i++) {
        echo htmlspecialchars($lines[$i]) . "\n";
    }
    echo "</pre>";
    
    // Extract namespace
    preg_match('/namespace\s+([^;]+);/', $content, $matches);
    if (isset($matches[1])) {
        echo "Namespace found in file: " . $matches[1] . "<br>";
    } else {
        echo "No namespace found in file!<br>";
    }
}

// Check autoloader
echo "<h2>Autoloader Paths</h2>";
echo "<pre>";
try {
    $loader = require __DIR__ . '/../../vendor/autoload.php';
    var_dump($loader);
} catch (Exception $e) {
    echo "Error loading autoloader: " . $e->getMessage();
}
echo "</pre>";

// List all PHP files in controllers directory
echo "<h2>Controllers Directory</h2>";
$controllersDir = dirname(__DIR__) . '/controllers';
echo "Directory: $controllersDir<br>";
if (is_dir($controllersDir)) {
    $files = scandir($controllersDir);
    echo "Files: <br>";
    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            echo "- $file<br>";
        }
    }
} else {
    echo "Controllers directory doesn't exist!<br>";
}

// Display file permissions
echo "<h2>File Permissions</h2>";
if (file_exists($controllerFile)) {
    $perms = fileperms($controllerFile);
    echo "Permissions: " . decoct($perms & 0777) . "<br>";
    echo "Owner: " . fileowner($controllerFile) . "<br>";
    echo "Group: " . filegroup($controllerFile) . "<br>";
    echo "Readable: " . (is_readable($controllerFile) ? 'Yes' : 'No') . "<br>";
}

// Create a direct instance of the controller
echo "<h2>Manual Instance Creation</h2>";
try {
    if (file_exists($controllerFile)) {
        include_once $controllerFile;
    }
    
    if (class_exists($controllerClass)) {
        $controller = new $controllerClass('site', Yii::$app);
        echo "Successfully created controller instance<br>";
        echo "Controller ID: " . $controller->id . "<br>";
        echo "Actions: " . implode(', ', array_keys($controller->actions())) . "<br>";
    } else {
        echo "Class $controllerClass still doesn't exist after include<br>";
    }
} catch (Exception $e) {
    echo "Error creating controller: " . $e->getMessage() . "<br>";
    echo get_class($e) . "<br>";
    echo $e->getTraceAsString() . "<br>";
}