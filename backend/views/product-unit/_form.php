<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\ProductUnit;

/* @var $this yii\web\View */
/* @var $model common\models\ProductUnit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-unit-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'base_unit_id')->dropDownList(
        ArrayHelper::map(
            ProductUnit::find()->where(['<>', 'id', $model->isNewRecord ? 0 : $model->id])->all(), 
            'id', 
            'name'
        ),
        ['prompt' => 'Đơn vị cơ bản']
    ) ?>

    <?= $form->field($model, 'conversion_rate')->textInput(['maxlength' => true, 'type' => 'number', 'step' => '0.01']) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

    <div class="form-group">
        <?= Html::submitButton('Lưu', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>