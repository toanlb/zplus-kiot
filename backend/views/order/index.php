<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use common\models\Customer;

/* @var $this yii\web\View */
/* @var $searchModel common\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý Đơn hàng';
?>
<div class="order-index">

    <p>
        <?= Html::a('<i class="fas fa-plus"></i> Tạo Đơn hàng mới', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Danh sách đơn hàng</h3>
        </div>
        <div class="card-body">
            <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'code',
                    [
                        'attribute' => 'customer_id',
                        'value' => function ($model) {
                            return $model->customer ? $model->customer->full_name : 'Khách lẻ';
                        },
                        'filter' => ArrayHelper::map(Customer::find()->all(), 'id', 'full_name'),
                    ],
                    [
                        'attribute' => 'total_amount',
                        'format' => ['decimal', 0],
                    ],
                    [
                        'attribute' => 'discount_amount',
                        'format' => ['decimal', 0],
                    ],
                    [
                        'attribute' => 'final_amount',
                        'format' => ['decimal', 0],
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => ['date', 'php:d/m/Y H:i'],
                        'filter' => false,
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update} {delete} {print}',
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
                                        'confirm' => 'Bạn có chắc chắn muốn xóa đơn hàng này?',
                                        'method' => 'post',
                                    ],
                                ]);
                            },
                            'print' => function ($url, $model) {
                                return Html::a('<i class="fas fa-print"></i>', ['print', 'id' => $model->id], [
                                    'title' => 'In hóa đơn',
                                    'class' => 'btn btn-sm btn-secondary',
                                    'target' => '_blank',
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