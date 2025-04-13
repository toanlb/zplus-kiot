<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\grid\GridView; // Thêm dòng này
use yii\widgets\Pjax; // Thêm dòng này nếu bạn sử dụng Pjax

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $orderItems common\models\OrderItem[] */
/* @var $orderDetail common\models\OrderDetail */
/* @var $orderPayment common\models\OrderPayment */

$this->title = 'Đơn hàng: ' . $model->code;
$this->params['breadcrumbs'][] = ['label' => 'Quản lý Đơn hàng', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$itemsDataProvider = new ArrayDataProvider([
    'allModels' => $orderItems,
    'pagination' => [
        'pageSize' => 50,
    ],
]);
?>
<div class="order-view">

    <p>
        <?= Html::a('Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Xóa', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Bạn có chắc chắn muốn xóa đơn hàng này?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('<i class="fas fa-print"></i> In hóa đơn', ['print', 'id' => $model->id], [
            'class' => 'btn btn-info',
            'target' => '_blank',
        ]) ?>
    </p>

    <div class="row">
        <div class="col-md-8">
            <!-- Thông tin đơn hàng -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thông tin đơn hàng</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    'code',
                                    [
                                        'attribute' => 'customer_id',
                                        'value' => $model->customer ? $model->customer->full_name : 'Khách lẻ',
                                    ],
                                    [
                                        'attribute' => 'created_at',
                                        'format' => ['date', 'php:d/m/Y H:i'],
                                    ],
                                ],
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
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
                                        'contentOptions' => ['class' => 'font-weight-bold'],
                                    ],
                                ],
                            ]) ?>
                        </div>
                    </div>
                    
                    <!-- Thông tin chi tiết đơn hàng -->
                    <?php if ($orderDetail): ?>
                    <div class="mt-4">
                        <h5>Thông tin chi tiết</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <?= DetailView::widget([
                                    'model' => $orderDetail,
                                    'attributes' => [
                                        'branch',
                                        'salesperson',
                                        'sales_channel',
                                        'creator',
                                        'status',
                                    ],
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= DetailView::widget([
                                    'model' => $orderDetail,
                                    'attributes' => [
                                        'order_note:ntext',
                                        'delivery_status',
                                        'delivery_status_note:ntext',
                                    ],
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Danh sách sản phẩm -->
                    <div class="mt-4">
                        <h5>Danh sách sản phẩm</h5>
                        <?= GridView::widget([
                            'dataProvider' => $itemsDataProvider,
                            'summary' => '',
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                'product_code',
                                'product_name',
                                'barcode',
                                'unit',
                                [
                                    'attribute' => 'quantity',
                                    'format' => ['decimal', 2],
                                ],
                                [
                                    'attribute' => 'unit_price',
                                    'format' => ['decimal', 0],
                                ],
                                [
                                    'attribute' => 'discount_percentage',
                                    'value' => function ($model) {
                                        return $model->discount_percentage . '%';
                                    },
                                ],
                                [
                                    'attribute' => 'discount_amount',
                                    'format' => ['decimal', 0],
                                ],
                                [
                                    'attribute' => 'final_price',
                                    'format' => ['decimal', 0],
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
            
            <!-- Thông tin giao hàng -->
            <?php if ($orderDetail): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Thông tin giao hàng</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?= DetailView::widget([
                                'model' => $orderDetail,
                                'attributes' => [
                                    'delivery_partner',
                                    'delivery_order_code',
                                    'service',
                                    [
                                        'attribute' => 'delivery_fee',
                                        'format' => ['decimal', 0],
                                    ],
                                    [
                                        'attribute' => 'delivery_time',
                                        'format' => ['date', 'php:d/m/Y H:i'],
                                        'value' => $orderDetail->delivery_time ? date('d/m/Y H:i', $orderDetail->delivery_time) : null,
                                    ],
                                ],
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= DetailView::widget([
                                'model' => $orderDetail,
                                'attributes' => [
                                    'receiver_name',
                                    'receiver_phone',
                                    'receiver_address:ntext',
                                    'receiver_area',
                                    'receiver_ward',
                                    'delivery_note:ntext',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="col-md-4">
            <!-- Thông tin thanh toán -->
            <?php if ($orderPayment): ?>
            <div class="card">
                <div class="card-header bg-success">
                    <h3 class="card-title">Thông tin thanh toán</h3>
                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $orderPayment,
                        'attributes' => [
                            [
                                'attribute' => 'cash_amount',
                                'format' => ['decimal', 0],
                                'value' => function ($model) {
                                    return $model->cash_amount > 0 ? $model->cash_amount : '-';
                                },
                            ],
                            [
                                'attribute' => 'card_amount',
                                'format' => ['decimal', 0],
                                'value' => function ($model) {
                                    return $model->card_amount > 0 ? $model->card_amount : '-';
                                },
                            ],
                            [
                                'attribute' => 'bank_transfer_amount',
                                'format' => ['decimal', 0],
                                'value' => function ($model) {
                                    return $model->bank_transfer_amount > 0 ? $model->bank_transfer_amount : '-';
                                },
                            ],
                            [
                                'attribute' => 'ewallet_amount',
                                'format' => ['decimal', 0],
                                'value' => function ($model) {
                                    return $model->ewallet_amount > 0 ? $model->ewallet_amount : '-';
                                },
                            ],
                            [
                                'attribute' => 'points_used',
                                'value' => function ($model) {
                                    return $model->points_used > 0 ? $model->points_used : '-';
                                },
                            ],
                            'voucher_code',
                            [
                                'attribute' => 'voucher_amount',
                                'format' => ['decimal', 0],
                                'value' => function ($model) {
                                    return $model->voucher_amount > 0 ? $model->voucher_amount : '-';
                                },
                            ],
                            [
                                'attribute' => 'additional_fee',
                                'format' => ['decimal', 0],
                                'value' => function ($model) {
                                    return $model->additional_fee > 0 ? $model->additional_fee : '-';
                                },
                            ],
                        ],
                    ]) ?>
                    
                    <div class="alert alert-success mt-3">
                        <h5>Đã thanh toán: <?= Yii::$app->formatter->asDecimal($model->paid_amount, 0) ?></h5>
                        <h5>Còn nợ: <?= Yii::$app->formatter->asDecimal($model->final_amount - $model->paid_amount, 0) ?></h5>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Thông tin khách hàng -->
            <?php if ($model->customer): ?>
            <div class="card mt-3">
                <div class="card-header bg-primary">
                    <h3 class="card-title">Thông tin khách hàng</h3>
                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model->customer,
                        'attributes' => [
                            'code',
                            'full_name',
                            'phone',
                            'email:email',
                            'address:ntext',
                            [
                                'attribute' => 'customer_group',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    if ($model->customer_group == 'VIP') {
                                        return '<span class="badge badge-danger">VIP</span>';
                                    } elseif ($model->customer_group == 'Thường xuyên') {
                                        return '<span class="badge badge-success">Thường xuyên</span>';
                                    } else {
                                        return $model->customer_group;
                                    }
                                },
                            ],
                            [
                                'attribute' => 'current_points',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return '<span class="badge badge-primary">' . $model->current_points . '</span>';
                                },
                            ],
                        ],
                    ]) ?>
                    
                    <?= Html::a('Xem chi tiết khách hàng', ['customer/view', 'id' => $model->customer_id], ['class' => 'btn btn-outline-primary btn-sm mt-2']) ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin bảo hành</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Serial</th>
                            <th>Ngày bắt đầu</th>
                            <th>Ngày kết thúc</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($model->orderItems as $item) {
                            $warranties = \common\models\ProductWarranty::find()
                                ->where(['order_item_id' => $item->id])
                                ->all();
                                
                            if (empty($warranties)) {
                                echo '<tr>';
                                echo '<td>' . $item->product_name . '</td>';
                                echo '<td colspan="4">Chưa có thông tin bảo hành</td>';
                                echo '<td>' . Html::a('<i class="fas fa-plus"></i> Thêm', ['/warranty/create', 
                                    'order_item_id' => $item->id,
                                    'product_id' => $item->product_id,
                                    'customer_id' => $model->customer_id,
                                ], ['class' => 'btn btn-success btn-sm']) . '</td>';
                                echo '</tr>';
                            } else {
                                foreach ($warranties as $warranty) {
                                    echo '<tr>';
                                    echo '<td>' . $item->product_name . '</td>';
                                    echo '<td>' . $warranty->serial_number . '</td>';
                                    echo '<td>' . Yii::$app->formatter->asDate($warranty->warranty_start_date) . '</td>';
                                    echo '<td>' . Yii::$app->formatter->asDate($warranty->warranty_end_date) . '</td>';
                                    
                                    $statusLabels = [
                                        'active' => '<span class="badge badge-success">Còn hiệu lực</span>',
                                        'expired' => '<span class="badge badge-danger">Hết hạn</span>',
                                        'voided' => '<span class="badge badge-secondary">Đã hủy</span>',
                                    ];
                                    $status = isset($statusLabels[$warranty->status]) ? $statusLabels[$warranty->status] : $warranty->status;
                                    echo '<td>' . $status . '</td>';
                                    
                                    echo '<td>' . Html::a('<i class="fas fa-eye"></i> Xem', ['/warranty/view', 'id' => $warranty->id], ['class' => 'btn btn-primary btn-sm']) . '</td>';
                                    echo '</tr>';
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>