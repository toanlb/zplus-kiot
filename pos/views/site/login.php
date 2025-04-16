<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \pos\models\LoginForm */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Url;

$this->title = 'Đăng nhập';
?>

<div class="login-logo">
    <div class="text-center" style="margin-top: 5px;">
        <img src="<?= Url::to(['/images/logo.png']) ?>" alt="Logo">
        <h1><b>POS</b> Bán Hàng</h1>
    </div>
</div>

<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">Đăng nhập để bắt đầu ca làm việc</p>
        <!-- Form đăng nhập thông thường -->
        <div id="standardLoginForm">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'loginMode')->hiddenInput(['value' => 'standard'])->label(false) ?>

            <?= $form->field($model, 'username', [
                'options' => ['class' => 'form-group'],
                'inputOptions' => [
                    'class' => 'form-control',
                    'placeholder' => 'Tên đăng nhập'
                ],
                'template' => '<div class="input-group mb-3">
                                {input}
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                               </div>
                               {error}{hint}'
            ])->label(false) ?>

            <?= $form->field($model, 'password', [
                'options' => ['class' => 'form-group'],
                'inputOptions' => [
                    'class' => 'form-control',
                    'placeholder' => 'Mật khẩu'
                ],
                'template' => '<div class="input-group mb-3">
                                {input}
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                               </div>
                               {error}{hint}'
            ])->passwordInput()->label(false) ?>

            <div class="row">
                <div class="col-7">
                    <?= $form->field($model, 'rememberMe')->checkbox() ?>
                </div>
                <div class="col-5">
                    <?= Html::submitButton('Đăng nhập', ['class' => 'btn btn-primary btn-block btn-login', 'name' => 'login-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
