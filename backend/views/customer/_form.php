<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Customer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'gender')->dropDownList([
                'male' => 'Nam',
                'female' => 'Nữ',
                'other' => 'Khác',
            ], ['prompt' => 'Chọn giới tính']) ?>

            <?= $form->field($model, 'birthday')->input('date') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'type')->dropDownList([
                'Cá nhân' => 'Cá nhân',
                'Doanh nghiệp' => 'Doanh nghiệp',
            ], ['prompt' => 'Chọn loại khách hàng']) ?>

            <?= $form->field($model, 'address')->textarea(['rows' => 3]) ?>

            <?= $form->field($model, 'delivery_area')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'ward')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'company')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'tax_code')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'customer_group')->dropDownList([
                'VIP' => 'VIP',
                'Thường xuyên' => 'Thường xuyên',
                'Mới' => 'Mới',
            ], ['prompt' => 'Chọn nhóm khách hàng']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'status')->dropDownList([
                1 => 'Kích hoạt',
                0 => 'Vô hiệu hóa',
            ]) ?>
        </div>
    </div>

    <?= $form->field($model, 'note')->textarea(['rows' => 3]) ?>

    <div class="form-group">
        <?= Html::submitButton('Lưu', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>