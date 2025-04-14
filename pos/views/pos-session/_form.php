<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model pos\models\PosSession */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pos-session-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if (!$model->isNewRecord): ?>
        <div class="alert alert-info">
            <strong>Lưu ý:</strong> Cập nhật ca làm việc này sẽ không ảnh hưởng đến các giao dịch đã liên kết.
        </div>
    <?php endif; ?>

    <?= $form->field($model, 'user_id')->dropDownList(
        ArrayHelper::map(User::find()->all(), 'id', 'username'),
        ['prompt' => 'Chọn nhân viên...']
    ) ?>

    <?= $form->field($model, 'start_amount')->textInput(['maxlength' => true, 'type' => 'number']) ?>

    <?php if (!$model->isNewRecord && $model->status == 0): ?>
        <?= $form->field($model, 'end_amount')->textInput(['maxlength' => true, 'type' => 'number']) ?>
    <?php endif; ?>

    <?= $form->field($model, 'note')->textarea(['rows' => 3]) ?>

    <?php if (!$model->isNewRecord && $model->status == 0): ?>
        <?= $form->field($model, 'close_note')->textarea(['rows' => 3]) ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Lưu', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Hủy', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>