<?php
namespace backend\assets;

use yii\web\AssetBundle;

class AdminLteAsset extends AssetBundle
{
    public $sourcePath = '@vendor/hail812/yii2-adminlte3/src/web';
    
    public $css = [
        'css/adminlte.min.css',
    ];
    
    public $js = [
        'js/adminlte.min.js',
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
    ];
}