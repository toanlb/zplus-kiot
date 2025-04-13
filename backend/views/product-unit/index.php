<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Đơn vị tính';
?>
<div class="product-unit-index">

    <p>
        <?= Html::a('<i class="fas fa-plus"></i> Thêm Đơn vị tính mới', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Danh sách đơn vị tính</h3>
        </div>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'code',
                    'name',
                    [
                        'attribute' => 'base_unit_id',
                        'value' => function ($model) {
                            return $model->baseUnit ? $model->baseUnit->name : 'Đơn vị cơ bản';
                        },
                    ],
                    'conversion_rate',
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
                                        'confirm' => 'Bạn có chắc chắn muốn xóa đơn vị tính này?',
                                        'method' => 'post',
                                    ],
                                ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>