<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
		'formatter' => [
			'class' => 'yii\i18n\Formatter',
			'currencyCode' => 'VND', // Hoặc mã tiền tệ khác (USD, EUR, v.v.)
			'thousandSeparator' => ',',
			'decimalSeparator' => '.',
		],
		'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@vendor/almasaeed2010/adminlte/src/html'
                ],
            ],
        ],
		'assetManager' => [
            'bundles' => [
                'yii\bootstrap5\BootstrapAsset' => [
                    'css' => [],  // Tắt CSS mặc định của Bootstrap, dùng CSS của AdminLTE 4
                ],
            ],
        ],
		'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],
    ],
];
