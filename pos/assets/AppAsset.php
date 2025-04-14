<?php
namespace pos\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css',
    ];
    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js',
        'js/main.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
    ];
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        // Add dark mode CSS if user has dark mode preference
        // This could be stored in user preferences or detected from system
        if (isset($_COOKIE['dark_mode']) && $_COOKIE['dark_mode'] === 'on') {
            $this->css[] = 'css/dark-mode.css';
        }
        
        // Different assets in development and production
        if (YII_ENV_DEV) {
            // Development specific assets
            $this->js[] = 'js/dev-helpers.js';
        } else {
            // Production specific assets - minified versions if available
            $this->js = array_map(function($item) {
                if (strpos($item, '.js') !== false && strpos($item, '.min.js') === false) {
                    return str_replace('.js', '.min.js', $item);
                }
                return $item;
            }, $this->js);
        }
    }
}