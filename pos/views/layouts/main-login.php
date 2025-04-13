<?php

/* @var $this \yii\web\View */
/* @var $content string */

use pos\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
        .login-page {
            background-image: url('<?= Url::to(['/images/pos-background.jpg']) ?>');
            background-size: cover;
            background-position: center;
        }
        .login-box {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .login-logo {
            margin-bottom: 20px;
        }
        .login-logo img {
            height: 70px;
            margin-bottom: 10px;
        }
        .login-box-msg {
            margin-bottom: 20px;
            font-size: 18px;
            color: #333;
        }
        .btn-login {
            background-color: #007bff;
            border-color: #007bff;
            font-weight: 600;
        }
        .login-box-footer {
            padding: 10px 0;
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
        .login-mode-switcher {
            text-align: center;
            margin-bottom: 15px;
        }
        .pin-input-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .pin-input {
            width: 45px;
            height: 55px;
            margin: 0 5px;
            font-size: 24px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .pin-display {
            letter-spacing: 10px;
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
            height: 40px;
            background-color: #f4f4f4;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .keypad {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        .keypad-btn {
            padding: 15px;
            font-size: 20px;
            text-align: center;
            background-color: #f4f4f4;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .keypad-btn:hover {
            background-color: #e0e0e0;
        }
        .keypad-btn.delete {
            grid-column: span 3;
        }
    </style>
</head>
<body class="login-page">
<?php $this->beginBody() ?>

<div class="login-box">
    <?= $content ?>
    
    <div class="login-box-footer">
        &copy; <?= date('Y') ?> <strong>POS Bán Hàng</strong><br>
        <small>Phiên bản 1.0.0</small>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>