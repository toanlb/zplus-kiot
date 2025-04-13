<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý Khách hàng';
?>
<div class="customer-index">

    <p>
        <?= Html::a('<i class="fas fa-plus"></i> Thêm Khách hàng mới', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Danh sách khách hàng</h3>
        </div>
        <div class="card-body">
            <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'code',
                    'full_name',
                    'phone',
                    'email:email',
                    [
                        'attribute' => 'customer_group',
                        'filter' => [
                            'VIP' => 'VIP',
                            'Thường xuyên' => 'Thường xuyên',
                            'Mới' => 'Mới',
                        ],
                    ],
                    [
                        'attribute' => 'current_points',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<span class="badge badge-primary">' . $model->current_points . '</span>';
                        },
                    ],
                    [
                        'attribute' => 'total_sales',
                        'format' => ['decimal', 0],
                    ],
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'filter' => [
                            1 => 'Kích hoạt',
                            0 => 'Vô hiệu hóa',
                        ],
                        'value' => function ($model) {
                            return $model->status ? '<span class="badge badge-success">Kích hoạt</span>' : '<span class="badge badge-danger">Vô hiệu hóa</span>';
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
                                        'confirm' => 'Bạn có chắc chắn muốn xóa khách hàng này?',
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