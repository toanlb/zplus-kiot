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
    <div class="text-center">
        <img src="<?= Url::to(['/images/logo.png']) ?>" alt="Logo">
        <h1><b>POS</b> Bán Hàng</h1>
    </div>
</div>

<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">Đăng nhập để bắt đầu ca làm việc</p>

        <div class="login-mode-switcher">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-primary active" id="btnStandardLogin">Đăng nhập thông thường</button>
                <button type="button" class="btn btn-sm btn-light" id="btnPinLogin">Đăng nhập nhanh (PIN)</button>
            </div>
        </div>

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
                <div class="col-8">
                    <?= $form->field($model, 'rememberMe')->checkbox([
                        'template' => '<div class="icheck-primary">{input}{label}</div>',
                        'labelOptions' => [
                            'class' => ''
                        ],
                    ]) ?>
                </div>
                <div class="col-4">
                    <?= Html::submitButton('Đăng nhập', ['class' => 'btn btn-primary btn-block btn-login', 'name' => 'login-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

        <!-- Form đăng nhập bằng PIN -->
        <div id="pinLoginForm" style="display: none;">
            <?php $form = ActiveForm::begin(['id' => 'pin-login-form']); ?>

            <?= $form->field($model, 'loginMode')->hiddenInput(['value' => 'pin'])->label(false) ?>
            <?= $form->field($model, 'pin')->hiddenInput()->label(false) ?>

            <div class="pin-display" id="pinDisplay">●●●●</div>

            <div class="keypad">
                <div class="keypad-btn" data-value="1">1</div>
                <div class="keypad-btn" data-value="2">2</div>
                <div class="keypad-btn" data-value="3">3</div>
                <div class="keypad-btn" data-value="4">4</div>
                <div class="keypad-btn" data-value="5">5</div>
                <div class="keypad-btn" data-value="6">6</div>
                <div class="keypad-btn" data-value="7">7</div>
                <div class="keypad-btn" data-value="8">8</div>
                <div class="keypad-btn" data-value="9">9</div>
                <div class="keypad-btn" data-value="0">0</div>
                <div class="keypad-btn" id="btnClearPin"><i class="fas fa-backspace"></i></div>
                <div class="keypad-btn" id="btnSubmitPin"><i class="fas fa-arrow-right"></i></div>
            </div>

            <div class="text-center">
                <?= Html::submitButton('Đăng nhập', ['class' => 'btn btn-primary btn-block btn-login', 'id' => 'pinSubmitButton', 'style' => 'display: none;']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

    </div>
</div>

<?php
$js = <<<JS
// Chuyển đổi giữa các chế độ đăng nhập
$('#btnStandardLogin').click(function() {
    $(this).addClass('btn-primary').removeClass('btn-light');
    $('#btnPinLogin').addClass('btn-light').removeClass('btn-primary');
    $('#standardLoginForm').show();
    $('#pinLoginForm').hide();
    $('#login-form-loginmode').val('standard');
});

$('#btnPinLogin').click(function() {
    $(this).addClass('btn-primary').removeClass('btn-light');
    $('#btnStandardLogin').addClass('btn-light').removeClass('btn-primary');
    $('#standardLoginForm').hide();
    $('#pinLoginForm').show();
    $('#pin-login-form-loginmode').val('pin');
});

// Xử lý nhập PIN
let pin = '';
const maxPinLength = 6;

$('.keypad-btn').click(function() {
    const value = $(this).data('value');
    if (value !== undefined && pin.length < maxPinLength) {
        pin += value;
        updatePinDisplay();
    }
});

$('#btnClearPin').click(function() {
    pin = '';
    updatePinDisplay();
});

$('#btnSubmitPin').click(function() {
    if (pin.length >= 4) {
        submitPin();
    }
});

function updatePinDisplay() {
    let displayText = '';
    for (let i = 0; i < maxPinLength; i++) {
        if (i < pin.length) {
            displayText += '●';
        } else {
            displayText += '○';
        }
    }
    $('#pinDisplay').text(displayText);
    $('#pin-login-form-pin').val(pin);
    
    // Enable submit button if PIN is at least 4 digits
    if (pin.length >= 4) {
        $('#btnSubmitPin').addClass('bg-primary text-white');
    } else {
        $('#btnSubmitPin').removeClass('bg-primary text-white');
    }
}

function submitPin() {
    $('#pin-login-form-pin').val(pin);
    $('#pinSubmitButton').click();
}

// Keyboard support for PIN input
$(document).on('keydown', function(e) {
    if ($('#pinLoginForm').is(':visible')) {
        // Numbers 0-9
        if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105)) {
            let num;
            if (e.keyCode >= 48 && e.keyCode <= 57) {
                num = e.keyCode - 48;
            } else {
                num = e.keyCode - 96;
            }
            if (pin.length < maxPinLength) {
                pin += num;
                updatePinDisplay();
            }
        }
        // Backspace
        else if (e.keyCode === 8) {
            e.preventDefault();
            pin = pin.slice(0, -1);
            updatePinDisplay();
        }
        // Enter
        else if (e.keyCode === 13 && pin.length >= 4) {
            e.preventDefault();
            submitPin();
        }
    }
});

// Initialize
updatePinDisplay();
JS;

$this->registerJs($js);
?>