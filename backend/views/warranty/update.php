<?php
// File: update.php
use yii\helpers\Html;

$this->title = 'Cập nhật bảo hành: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Quản lý bảo hành', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Cập nhật';
?>
<div class="product-warranty-update">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>