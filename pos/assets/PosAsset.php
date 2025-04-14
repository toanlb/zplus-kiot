<?php
namespace pos\assets;

use yii\web\AssetBundle;

/**
 * POS application asset bundle.
 */
class PosAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/pos.css',
        'css/site.css',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css',
    ];
    public $js = [
        'js/pos.js',
        'js/cart.js',
        'js/product.js',
        'js/payment.js',
        'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
    ];
    
    public function init()
    {
        parent::init();
        // Điều chỉnh cụ thể cho môi trường sản xuất
        if (YII_ENV_PROD) {
            $this->js = array_merge($this->js, [
                'js/pos-offline.js', // Hỗ trợ offline mode trong môi trường production
            ]);
        }
    }
}