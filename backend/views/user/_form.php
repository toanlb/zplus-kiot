<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;

?>

<div class="user-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thông tin tài khoản</h3>
                </div>
                <div class="card-body">
                    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'position')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'status')->dropDownList([
                        User::STATUS_ACTIVE => 'Đang hoạt động',
                        User::STATUS_INACTIVE => 'Chưa kích hoạt',
                        User::STATUS_DELETED => 'Đã vô hiệu',
                    ]) ?>

                    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
                    <?php if (!$model->isNewRecord): ?>
                        <div class="form-hint">Để trống nếu không muốn thay đổi mật khẩu</div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Vai trò</label>
                        <select name="role" class="form-control">
                            <option value="">-- Chọn vai trò --</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= Html::encode($role->name) ?>" <?= isset($currentRole) && $currentRole === $role->name ? 'selected' : '' ?>>
                                    <?= Html::encode($role->description ? $role->description : $role->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thông tin cá nhân</h3>
                </div>
                <div class="card-body">
                    <?= $form->field($profile, 'address')->textarea(['rows' => 3]) ?>

                    <?= $form->field($profile, 'birthday')->textInput(['type' => 'date']) ?>

                    <?= $form->field($profile, 'gender')->dropDownList([
                        'Nam' => 'Nam',
                        'Nữ' => 'Nữ',
                        'Khác' => 'Khác',
                    ], ['prompt' => '-- Chọn giới tính --']) ?>

                    <?= $form->field($profile, 'id_card')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($profile, 'department')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($profile, 'hire_date')->textInput(['type' => 'date']) ?>

                    <?= $form->field($profile, 'notes')->textarea(['rows' => 3]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('<i class="fas fa-save"></i> Lưu', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>