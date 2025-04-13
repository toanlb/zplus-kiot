<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView; // Thêm dòng này
use yii\widgets\Pjax; // Thêm dòng này nếu bạn sử dụng Pjax
/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Quản lý Sản phẩm', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">

    <p>
        <?= Html::a('Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Xóa', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Bạn có chắc chắn muốn xóa sản phẩm này?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Thông tin chi tiết sản phẩm</h3>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'code',
                    'barcode',
                    'name',
                    'brand',
                    [
                        'attribute' => 'category_id',
                        'value' => $model->category->name ?? 'Không có',
                    ],
                    [
                        'attribute' => 'primary_unit_id',
                        'value' => $model->primaryUnit->name ?? 'Không có',
                    ],
                    [
                        'attribute' => 'selling_price',
                        'format' => ['decimal', 0],
                    ],
                    [
                        'attribute' => 'cost_price',
                        'format' => ['decimal', 0],
                    ],
					
                    'current_stock',
                    'min_stock',
                    'max_stock',
                    [
                        'attribute' => 'is_active',
                        'format' => 'raw',
                        'value' => $model->is_active ? '<span class="badge badge-success">Kích hoạt</span>' : '<span class="badge badge-danger">Vô hiệu hóa</span>',
                    ],
                    'description:ntext',
                    'note:ntext',
                    [
                        'attribute' => 'created_at',
                        'format' => ['date', 'php:d/m/Y H:i'],
                    ],
                    [
                        'attribute' => 'updated_at',
                        'format' => ['date', 'php:d/m/Y H:i'],
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
