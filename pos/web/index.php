<?php

// Comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../config/bootstrap.php'; // Include POS bootstrap

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/main.php',
    file_exists(__DIR__ . '/../../common/config/main-local.php') ? require __DIR__ . '/../../common/config/main-local.php' : [],
    require __DIR__ . '/../config/main.php',
    file_exists(__DIR__ . '/../config/main-local.php') ? require __DIR__ . '/../config/main-local.php' : []
);

// Debugging
// print_r($config);
// exit;

// Khởi tạo ứng dụng
(new yii\web\Application($config))->run();