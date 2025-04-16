<?php

namespace pos\modules\transactionhistory;

/**
 * Module cho chức năng lịch sử giao dịch
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'pos\modules\transactionhistory\controllers';
    public $layout = '@pos/views/layouts/main';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        
        // Tùy chỉnh các thành phần của module
        $this->setComponents([
            'formatter' => [
                'class' => 'yii\i18n\Formatter',
                'currencyCode' => 'VND',
                'thousandSeparator' => '.',
                'decimalSeparator' => ',',
            ],
        ]);
        
        // Cấu hình thêm
    }
    
    /**
     * Kiểm tra quyền truy cập vào module
     * 
     * @return boolean
     */
    public static function checkAccess()
    {
        // Nếu người dùng chưa đăng nhập
        if (\Yii::$app->user->isGuest) {
            return false;
        }
        
        // Nếu đã cài đặt RBAC và có quyền viewTransactionHistory
        if (\Yii::$app->has('authManager')) {
            return \Yii::$app->authManager->checkAccess(\Yii::$app->user->id, 'viewTransactionHistory');
        }
        
        // Mặc định cho phép đăng nhập
        return true;
    }
}