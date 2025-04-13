<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ProductCategory */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Danh mục Sản phẩm', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-category-view">

    <p>
        <?= Html::a('Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Xóa', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Bạn có chắc chắn muốn xóa danh mục này?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Thông tin chi tiết danh mục</h3>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'name',
                    [
                        'attribute' => 'parent_id',
                        'value' => $model->parent ? $model->parent->name : 'Không có',
                    ],
                    'level',
                    'description:ntext',
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => $model->status ? '<span class="badge badge-success">Kích hoạt</span>' : '<span class="badge badge-danger">Vô hiệu hóa</span>',
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => ['date', 'php:d/m/Y H:i'],
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>