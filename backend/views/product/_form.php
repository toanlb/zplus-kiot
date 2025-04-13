<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\ProductCategory;
use common\models\ProductUnit;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'barcode')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'brand')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'category_id')->dropDownList(
                ArrayHelper::map(ProductCategory::find()->all(), 'id', 'name'),
                ['prompt' => 'Chọn danh mục']
            ) ?>

            <?= $form->field($model, 'primary_unit_id')->dropDownList(
                ArrayHelper::map(ProductUnit::find()->all(), 'id', 'name'),
                ['prompt' => 'Chọn đơn vị tính']
            ) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'selling_price')->textInput(['maxlength' => true, 'type' => 'number', 'step' => '0.01']) ?>

            <?= $form->field($model, 'cost_price')->textInput(['maxlength' => true, 'type' => 'number', 'step' => '0.01']) ?>

            <?= $form->field($model, 'current_stock')->textInput(['type' => 'number']) ?>

            <?= $form->field($model, 'min_stock')->textInput(['type' => 'number']) ?>

            <?= $form->field($model, 'max_stock')->textInput(['type' => 'number']) ?>

            <?= $form->field($model, 'is_active')->dropDownList([
                1 => 'Kích hoạt',
                0 => 'Vô hiệu hóa',
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

            <?= $form->field($model, 'note')->textarea(['rows' => 3]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Lưu', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>