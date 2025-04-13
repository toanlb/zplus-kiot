<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use common\models\Order;

/* @var $this yii\web\View */
/* @var $model common\models\Customer */

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Quản lý Khách hàng', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Lấy dữ liệu lịch sử đơn hàng
$orderProvider = new ActiveDataProvider([
    'query' => Order::find()->where(['customer_id' => $model->id]),
    'sort' => [
        'defaultOrder' => [
            'created_at' => SORT_DESC,
        ]
    ],
    'pagination' => [
        'pageSize' => 5,
    ],
]);
?>
<div class="customer-view">

    <p>
        <?= Html::a('Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Xóa', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Bạn có chắc chắn muốn xóa khách hàng này?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thông tin chi tiết khách hàng</h3>
                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'code',
                            'full_name',
                            'phone',
                            'email:email',
                            [
                                'attribute' => 'gender',
                                'value' => function ($model) {
                                    $genders = [
                                        'male' => 'Nam',
                                        'female' => 'Nữ',
                                        'other' => 'Khác',
                                    ];
                                    return isset($genders[$model->gender]) ? $genders[$model->gender] : $model->gender;
                                },
                            ],
                            'birthday:date',
                            'type',
                            'address:ntext',
                            'delivery_area',
                            'ward',
                            'company',
                            'tax_code',
                            'customer_group',
                            [
                                'attribute' => 'current_points',
                                'format' => 'raw',
                                'value' => '<span class="badge badge-primary">' . $model->current_points . '</span>',
                            ],
                            [
                                'attribute' => 'total_points',
                                'format' => 'raw',
                                'value' => '<span class="badge badge-info">' . $model->total_points . '</span>',
                            ],
                            [
                                'attribute' => 'current_debt',
                                'format' => ['decimal', 0],
                            ],
                            [
                                'attribute' => 'total_sales',
                                'format' => ['decimal', 0],
                            ],
                            [
                                'attribute' => 'total_sales_net',
                                'format' => ['decimal', 0],
                            ],
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'value' => $model->status ? '<span class="badge badge-success">Kích hoạt</span>' : '<span class="badge badge-danger">Vô hiệu hóa</span>',
                            ],
                            [
                                'attribute' => 'created_at',
                                'format' => ['date', 'php:d/m/Y H:i'],
                            ],
                            [
								'attribute' => 'last_transaction_date',
								'format' => 'raw',
								'value' => function ($model) {
									return $model->last_transaction_date ? Yii::$app->formatter->asDatetime($model->last_transaction_date, 'php:d/m/Y H:i') : 'Chưa có giao dịch';
								},
							],
                            'note:ntext',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <!-- Thẻ khách hàng -->
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title">Thẻ khách hàng</h3>
                </div>
                <div class="card-body text-center">
                    <h4><?= $model->full_name ?></h4>
                    <p><strong>Mã KH:</strong> <?= $model->code ?></p>
                    <p><strong>Nhóm:</strong> <?= $model->customer_group ?></p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box bg-info">
                                <div class="info-box-content">
                                    <span class="info-box-text">Điểm hiện tại</span>
                                    <span class="info-box-number"><?= $model->current_points ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-success">
                                <div class="info-box-content">
                                    <span class="info-box-text">Tổng doanh số</span>
                                    <span class="info-box-number"><?= Yii::$app->formatter->asDecimal($model->total_sales, 0) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Lịch sử đơn hàng -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lịch sử đơn hàng</h3>
                </div>
                <div class="card-body">
                    <?= GridView::widget([
                        'dataProvider' => $orderProvider,
                        'summary' => false,
                        'columns' => [
                            'code',
                            [
                                'attribute' => 'created_at',
                                'format' => ['date', 'php:d/m/Y'],
                            ],
                            [
                                'attribute' => 'final_amount',
                                'format' => ['decimal', 0],
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view}',
                                'buttons' => [
                                    'view' => function ($url, $model) {
                                        return Html::a('<i class="fas fa-eye"></i>', ['/order/view', 'id' => $model->id], [
                                            'title' => 'Xem đơn hàng',
                                            'class' => 'btn btn-sm btn-primary',
                                        ]);
                                    },
                                ],
                            ],
                        ],
                    ]); ?>
                </div>
                <div class="card-footer">
                    <?= Html::a('Xem tất cả đơn hàng', ['/order/index', 'OrderSearch[customer_id]' => $model->id], ['class' => 'btn btn-outline-primary btn-sm']) ?>
                </div>
            </div>
        </div>
    </div>
</div>