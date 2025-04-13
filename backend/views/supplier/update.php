<?php
// File: create.php
use yii\helpers\Html;

$this->title = 'Thêm mới nhà cung cấp';
$this->params['breadcrumbs'][] = ['label' => 'Quản lý nhà cung cấp', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>