<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ProductCategory */

$this->title = 'Thêm Danh mục Sản phẩm';
$this->params['breadcrumbs'][] = ['label' => 'Danh mục Sản phẩm', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-category-create">

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