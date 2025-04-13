<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="supplier-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'code')->textInput(['maxlength' => true, 'readonly' => !$model->isNewRecord]) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $form->field($model, 'address')->textarea(['rows' => 3]) ?>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'area')->textInput(['maxlength' => true]) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $form->field($model, 'ward')->textInput(['maxlength' => true]) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $form->field($model, 'tax_code')->textInput(['maxlength' => true]) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $form->field($model, 'company')->textInput(['maxlength' => true]) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $form->field($model, 'group')->textInput(['maxlength' => true]) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $form->field($model, 'status')->dropDownList([
                            1 => 'Đang hoạt động',
                            0 => 'Ngừng hoạt động',
                        ]) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $form->field($model, 'note')->textarea(['rows' => 3]) ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-footer">
            <?= Html::submitButton('<i class="fas fa-save"></i> Lưu', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>