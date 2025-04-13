<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = 'Gán vai trò cho người dùng';
$this->params['breadcrumbs'][] = ['label' => 'Quản lý phân quyền', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="rbac-assign-role">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            
            <div class="form-group">
                <label>Chọn người dùng</label>
                <select name="user_id" class="form-control" required>
                    <option value="">-- Chọn người dùng --</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user->id ?>"><?= $user->username ?> (<?= $user->email ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Chọn vai trò</label>
                <select name="role_name" class="form-control" required>
                    <option value="">-- Chọn vai trò --</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= $role->name ?>"><?= $role->name ?> (<?= $role->description ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <?= Html::submitButton('<i class="fas fa-save"></i> Gán vai trò', ['class' => 'btn btn-success']) ?>
            </div>
            
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>