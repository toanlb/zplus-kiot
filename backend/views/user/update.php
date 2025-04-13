<?php
use yii\helpers\Html;

$this->title = 'Cập nhật người dùng: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Quản lý người dùng', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Cập nhật';
?>
<div class="user-update">
    <div class="user-form">
        <?= $this->render('_form', [
            'model' => $model,
            'profile' => $profile,
            'roles' => $roles,
            'currentRole' => $currentRole,
        ]) ?>
    </div>
</div>