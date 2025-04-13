<?php
namespace backend\assets;

use hail812\adminlte3\assets\BaseAsset;
use yii\bootstrap5\BootstrapAsset;
use yii\bootstrap5\BootstrapPluginAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

class AdminLte3Bootstrap5Asset extends AssetBundle
{
    public $sourcePath = '@vendor/hail812/yii2-adminlte3/src/web';

    public $css = [
        'css/adminlte.min.css',
    ];

    public $js = [
        'js/adminlte.min.js'
    ];

    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
        BootstrapPluginAsset::class,
        CustomAsset::class,
    ];
}

class CustomAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    
    public $css = [
        'css/custom.css',
    ];
}