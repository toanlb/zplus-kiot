<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    file_exists(__DIR__ . '/../../common/config/params-local.php') ? require __DIR__ . '/../../common/config/params-local.php' : [],
    require __DIR__ . '/params.php',
    file_exists(__DIR__ . '/params-local.php') ? require __DIR__ . '/params-local.php' : []
);

return [
    'id' => 'app-pos',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'pos\controllers',
    'aliases' => [
        '@pos' => dirname(__DIR__),
    ],
    'name' => 'POS Bán Hàng',
    'language' => 'vi-VN',
    'timeZone' => 'Asia/Ho_Chi_Minh',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-pos',
            'cookieValidationKey' => 'your-secret-key-here',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-pos', 'httpOnly' => true],
        ],
        'session' => [
            'name' => 'pos-session',
            'cookieParams' => [
                'httpOnly' => true,
            ],
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
                'pos/payment' => 'pos/payment',
                'api/products' => 'product/list',
                'api/products/<id:\d+>' => 'product/view',
                'api/customers' => 'customer/list',
                'api/customers/<id:\d+>' => 'customer/view',
                'api/orders' => 'order/list',
                'api/orders/<id:\d+>' => 'order/view',
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
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
		'assetManager' => [
			'basePath' => '@webroot/assets',
			'baseUrl' => '@web/assets',
			'appendTimestamp' => true,
			'bundles' => [
				'yii\bootstrap4\BootstrapAsset' => [
					'css' => [],
				],
			],
		],
        'i18n' => [
            'translations' => [
                'pos*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'pos' => 'pos.php',
                        'pos/error' => 'error.php',
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
    'defaultRoute' => 'site/index',
];