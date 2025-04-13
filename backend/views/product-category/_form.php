<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\ProductCategory;

/* @var $this yii\web\View */
/* @var $model common\models\ProductCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parent_id')->dropDownList(
        ArrayHelper::map(
            ProductCategory::find()->where(['<>', 'id', $model->isNewRecord ? 0 : $model->id])->all(), 
            'id', 
            'name'
        ),
        ['prompt' => 'Chọn danh mục cha']
    ) ?>

    <?= $form->field($model, 'level')->dropDownList([
        1 => 'Cấp 1',
        2 => 'Cấp 2',
        3 => 'Cấp 3',
    ]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'status')->dropDownList([
        1 => 'Kích hoạt',
        0 => 'Vô hiệu hóa',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Lưu', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>