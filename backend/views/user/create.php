<?php
use yii\helpers\Html;

$this->title = 'Tạo người dùng mới';
$this->params['breadcrumbs'][] = ['label' => 'Quản lý người dùng', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">
    <div class="user-form">
        <?= $this->render('_form', [
            'model' => $model,
            'profile' => $profile,
            'roles' => $roles,
        ]) ?>
    </div>
</div>