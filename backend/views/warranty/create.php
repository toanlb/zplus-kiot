<?php
// File: create.php
use yii\helpers\Html;

$this->title = 'Tạo mới bảo hành';
$this->params['breadcrumbs'][] = ['label' => 'Quản lý bảo hành', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-warranty-create">
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