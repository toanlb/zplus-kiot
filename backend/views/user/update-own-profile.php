<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Cập nhật thông tin cá nhân';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-update-own-profile">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <?php if ($model->avatar): ?>
                            <img class="profile-user-img img-fluid img-circle" src="<?= $model->avatar ?>" alt="User profile picture">
                        <?php else: ?>
                            <img class="profile-user-img img-fluid img-circle" src="/img/default-avatar.png" alt="User profile picture">
                        <?php endif; ?>
                    </div>
                    <h3 class="profile-username text-center"><?= Html::encode($model->full_name) ?></h3>
                    <p class="text-muted text-center"><?= Html::encode($model->position) ?></p>
                    
                    <?php $formAvatar = \yii\widgets\ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], 'action' => ['upload-avatar']]); ?>
                    <?= Html::hiddenInput('id', $model->id) ?>
                    <div class="form-group">
                        <?= Html::fileInput('User[avatar]', null, ['class' => 'form-control', 'accept' => 'image/*']) ?>
                    </div>
                    <div class="form-group">
                        <?= Html::submitButton('Cập nhật ảnh đại diện', ['class' => 'btn btn-primary btn-block']) ?>
                    </div>
                    <?php \yii\widgets\ActiveForm::end(); ?>
                    
                    <hr>
                    
                    <?= Html::a('Đổi mật khẩu', ['change-password'], ['class' => 'btn btn-warning btn-block']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thông tin cá nhân</h3>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(); ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>
                            
                            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                            
                            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                            
                            <?= $form->field($profile, 'address')->textarea(['rows' => 3]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($profile, 'birthday')->textInput(['type' => 'date']) ?>
                            
                            <?= $form->field($profile, 'gender')->dropDownList([
                                'Nam' => 'Nam',
                                'Nữ' => 'Nữ',
                                'Khác' => 'Khác',
                            ], ['prompt' => '-- Chọn giới tính --']) ?>
                            
                            <?= $form->field($profile, 'id_card')->textInput(['maxlength' => true]) ?>
                            
                            <?= $form->field($profile, 'notes')->textarea(['rows' => 3]) ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= Html::submitButton('<i class="fas fa-save"></i> Lưu thay đổi', ['class' => 'btn btn-success']) ?>
                    </div>
                    
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>