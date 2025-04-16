<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use common\models\TransactionHistory;

/* @var $this yii\web\View */
/* @var $model common\models\TransactionHistorySearch */
/* @var $form yii\bootstrap4\ActiveForm */

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css');

$js = <<<JS
$(document).ready(function(){
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });
});
JS;
$this->registerJs($js);
?>

<div class="transaction-history-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'transaction_code')->textInput(['placeholder' => 'Nhập mã giao dịch...']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'order_code')->textInput(['placeholder' => 'Nhập mã đơn hàng...']) ?>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?= $form->field($model, 'date_from')->textInput([
                    'class' => 'form-control datepicker',
                    'placeholder' => 'Từ ngày...'
                ]) ?>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'payment_status')->dropDownList(
                TransactionHistory::getPaymentStatuses(),
                ['prompt' => '-- Chọn trạng thái --']
            ) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'transaction_type')->dropDownList(
                TransactionHistory::getTransactionTypes(),
                ['prompt' => '-- Chọn loại giao dịch --']
            ) ?>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?= $form->field($model, 'date_to')->textInput([
                    'class' => 'form-control datepicker',
                    'placeholder' => 'Đến ngày...'
                ]) ?>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'customer_name')->textInput(['placeholder' => 'Nhập tên khách hàng...']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'user_name')->textInput(['placeholder' => 'Nhập tên nhân viên...']) ?>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>&nbsp;</label>
                <div class="input-group">
                    <?= Html::submitButton('<i class="fas fa-search"></i> Tìm kiếm', ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('<i class="fas fa-redo"></i> Đặt lại', ['index'], ['class' => 'btn btn-outline-secondary ml-1']) ?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>