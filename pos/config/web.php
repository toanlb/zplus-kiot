<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/../../common/config/db.php';

$config = [
    'id' => 'pos',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'pos\controllers',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'secret_key_for_pos_module',
            'csrfParam' => '_csrf-pos',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-pos', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'pos-session',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'login' => 'site/login',
                'logout' => 'site/logout',
                'pos' => 'pos/index',
                'payment' => 'pos/payment',
                
                // API endpoints
                'api/products' => 'product/list',
                'api/products/<id:\d+>' => 'product/view',
                'api/products/search' => 'product/search',
                'api/products/categories' => 'product/categories',
                
                'api/customers' => 'customer/list',
                'api/customers/<id:\d+>' => 'customer/view',
                'api/customers/search' => 'customer/search',
                
                'api/orders' => 'order/list',
                'api/orders/<id:\d+>' => 'order/view',
                'api/orders/pending' => 'order/pending',
                
                // Các rule khác
                '<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
            ],
        ],
        'db' => $db,
        'assetManager' => [
            'appendTimestamp' => true, // Thêm timestamp cho asset URLs để tránh caching
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [
                        YII_ENV_DEV ? 'jquery.js' : 'jquery.min.js'
                    ]
                ],
                'yii\bootstrap4\BootstrapAsset' => [
                    'css' => [
                        YII_ENV_DEV ? 'css/bootstrap.css' : 'css/bootstrap.min.css',
                    ]
                ],
                'yii\bootstrap4\BootstrapPluginAsset' => [
                    'js' => [
                        YII_ENV_DEV ? 'js/bootstrap.bundle.js' : 'js/bootstrap.bundle.min.js',
                    ]
                ],
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'php:d/m/Y',
            'datetimeFormat' => 'php:d/m/Y H:i',
            'timeFormat' => 'php:H:i',
            'decimalSeparator' => ',',
            'thousandSeparator' => '.',
            'currencyCode' => 'VND',
            'defaultTimeZone' => 'Asia/Ho_Chi_Minh',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;