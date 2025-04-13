<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý Sản phẩm';
?>
<div class="product-index">

    <p>
        <?= Html::a('<i class="fas fa-plus"></i> Thêm Sản phẩm mới', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Danh sách sản phẩm</h3>
        </div>
        <div class="card-body">
            <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'code',
                    'barcode',
                    'name',
                    'brand',
                    [
                        'attribute' => 'selling_price',
                        'format' => ['decimal', 0],
                    ],
                    [
                        'attribute' => 'current_stock',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if ($model->current_stock <= $model->min_stock) {
                                return '<span class="text-danger">' . $model->current_stock . '</span>';
                            }
                            return $model->current_stock;
                        },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<i class="fas fa-eye"></i>', $url, [
                                    'title' => 'Xem',
                                    'class' => 'btn btn-sm btn-primary',
                                ]);
                            },
                            'update' => function ($url, $model) {
                                return Html::a('<i class="fas fa-pencil-alt"></i>', $url, [
                                    'title' => 'Cập nhật',
                                    'class' => 'btn btn-sm btn-info',
                                ]);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a('<i class="fas fa-trash"></i>', $url, [
                                    'title' => 'Xóa',
                                    'class' => 'btn btn-sm btn-danger',
                                    'data' => [
                                        'confirm' => 'Bạn có chắc chắn muốn xóa sản phẩm này?',
                                        'method' => 'post',
                                    ],
                                ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>