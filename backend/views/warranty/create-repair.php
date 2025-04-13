<?php
// File: create-repair.php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Thêm bản ghi sửa chữa';
$this->params['breadcrumbs'][] = ['label' => 'Quản lý bảo hành', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $warranty->serial_number, 'url' => ['view', 'id' => $warranty->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="warranty-repair-log-create">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'repair_date')->textInput(['type' => 'date']) ?>

                    <?= $form->field($model, 'technician')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'repair_location')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'status')->dropDownList([
                        'pending' => 'Đang xử lý',
                        'completed' => 'Hoàn thành',
                        'cancelled' => 'Đã hủy',
                    ], ['prompt' => 'Chọn trạng thái...']) ?>
                </div>
                
                <div class="col-md-6">
                    <?= $form->field($model, 'issue_description')->textarea(['rows' => 3]) ?>
                    
                    <?= $form->field($model, 'repair_description')->textarea(['rows' => 3]) ?>
                    
                    <?= $form->field($model, 'parts_replaced')->textarea(['rows' => 3]) ?>
                    
                    <?= $form->field($model, 'repair_cost')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                    
                    <?= $form->field($model, 'next_service_recommendation')->textarea(['rows' => 3]) ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton('<i class="fas fa-save"></i> Lưu', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>