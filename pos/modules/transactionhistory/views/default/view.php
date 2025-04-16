<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\TransactionHistory */
/* @var $orderItems array */

$this->title = 'Giao dịch: ' . $model->transaction_code;
$this->params['breadcrumbs'][] = ['label' => 'Lịch Sử Giao Dịch', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="transaction-history-view">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-receipt"></i> <?= Html::encode($this->title) ?>
            </h3>
            <div class="card-tools">
                <?= Html::a('<i class="fas fa-print"></i> In hóa đơn', ['print', 'id' => $model->id], [
                    'class' => 'btn btn-info btn-sm',
                    'target' => '_blank',
                ]) ?>
                <?= Html::a('<i class="fas fa-arrow-left"></i> Quay lại', ['index'], ['class' => 'btn btn-default btn-sm']) ?>
            </div>
        </div>
        
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title">Thông tin giao dịch</h3>
                        </div>
                        <div class="card-body">
                            <?= DetailView::widget([
                                'model' => $model,
                                'options' => ['class' => 'table table-bordered table-striped'],
                                'attributes' => [
                                    'transaction_code',
                                    [
                                        'attribute' => 'order_id',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            if ($model->order) {
                                                return $model->order->code;
                                            }
                                            return '<span class="text-muted">N/A</span>';
                                        },
                                    ],
                                    [
                                        'attribute' => 'transaction_type',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $badges = [
                                                'sale' => '<span class="badge badge-primary">Bán hàng</span>',
                                                'return' => '<span class="badge badge-info">Hoàn trả</span>',
                                                'void' => '<span class="badge badge-dark">Hủy giao dịch</span>',
                                                'credit' => '<span class="badge badge-secondary">Công nợ</span>',
                                            ];
                                            return $badges[$model->transaction_type] ?? '<span class="badge badge-secondary">Không xác định</span>';
                                        },
                                    ],
                                    [
                                        'attribute' => 'payment_status',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $badges = [
                                                'paid' => '<span class="badge badge-success">Đã thanh toán</span>',
                                                'partial' => '<span class="badge badge-warning">Thanh toán một phần</span>',
                                                'pending' => '<span class="badge badge-danger">Chưa thanh toán</span>',
                                            ];
                                            return $badges[$model->payment_status] ?? '<span class="badge badge-secondary">Không xác định</span>';
                                        },
                                    ],
                                    [
                                        'attribute' => 'created_at',
                                        'format' => 'datetime',
                                    ],
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h3 class="card-title">Thông tin thanh toán</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 50%">Tổng tiền hàng:</th>
                                        <td class="text-right"><?= Yii::$app->formatter->asCurrency($model->total_amount) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Giảm giá:</th>
                                        <td class="text-right"><?= Yii::$app->formatter->asCurrency($model->discount_amount) ?></td>
                                    </tr>
                                    <tr class="table-primary">
                                        <th>Thành tiền:</th>
                                        <td class="text-right"><strong><?= Yii::$app->formatter->asCurrency($model->final_amount) ?></strong></td>
                                    </tr>
                                    <tr>
                                        <th>Đã thanh toán:</th>
                                        <td class="text-right"><?= Yii::$app->formatter->asCurrency($model->paid_amount) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Còn nợ:</th>
                                        <td class="text-right">
                                            <?php $remaining = max(0, $model->final_amount - $model->paid_amount); ?>
                                            <span class="<?= $remaining > 0 ? 'text-danger' : 'text-success' ?>">
                                                <?= Yii::$app->formatter->asCurrency($remaining) ?>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="mt-3">
                                <h5>Chi tiết thanh toán:</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <tr>
                                            <th>Tiền mặt:</th>
                                            <td class="text-right"><?= Yii::$app->formatter->asCurrency($model->cash_amount) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Thẻ:</th>
                                            <td class="text-right"><?= Yii::$app->formatter->asCurrency($model->card_amount) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Ví điện tử:</th>
                                            <td class="text-right"><?= Yii::$app->formatter->asCurrency($model->ewallet_amount) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Chuyển khoản:</th>
                                            <td class="text-right"><?= Yii::$app->formatter->asCurrency($model->bank_transfer_amount) ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h3 class="card-title">Chi tiết sản phẩm</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Mã sản phẩm</th>
                                            <th>Tên sản phẩm</th>
                                            <th class="text-right">Đơn giá</th>
                                            <th class="text-right">Số lượng</th>
                                            <th class="text-right">Giảm giá</th>
                                            <th class="text-right">Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($orderItems)): ?>
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">Không có dữ liệu sản phẩm</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($orderItems as $index => $item): ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td><?= Html::encode($item['product_code'] ?? $item['code']) ?></td>
                                                    <td><?= Html::encode($item['name']) ?></td>
                                                    <td class="text-right"><?= Yii::$app->formatter->asCurrency($item['price']) ?></td>
                                                    <td class="text-right"><?= $item['quantity'] ?></td>
                                                    <td class="text-right"><?= Yii::$app->formatter->asCurrency($item['discount'] * $item['quantity']) ?></td>
                                                    <td class="text-right"><?= Yii::$app->formatter->asCurrency($item['price'] * $item['quantity'] - ($item['discount'] * $item['quantity'])) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($model->notes)): ?>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-warning">
                            <h3 class="card-title">Ghi chú</h3>
                        </div>
                        <div class="card-body">
                            <?= Html::encode($model->notes) ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h3 class="card-title">Thông tin khách hàng</h3>
                        </div>
                        <div class="card-body">
                            <?php if ($model->customer): ?>
                                <table class="table table-bordered table-sm">
                                    <tr>
                                        <th style="width:30%">Tên khách hàng:</th>
                                        <td><?= Html::encode($model->customer->full_name) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Số điện thoại:</th>
                                        <td><?= Html::encode($model->customer->phone) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td><?= Html::encode($model->customer->email) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Địa chỉ:</th>
                                        <td><?= Html::encode($model->customer->address) ?></td>
                                    </tr>
                                </table>
                            <?php else: ?>
                                <p class="text-muted">Khách hàng lẻ</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h3 class="card-title">Thông tin nhân viên</h3>
                        </div>
                        <div class="card-body">
                            <?php if ($model->user): ?>
                                <table class="table table-bordered table-sm">
                                    <tr>
                                        <th style="width:30%">Tên đăng nhập:</th>
                                        <td><?= Html::encode($model->user->username) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Họ tên:</th>
                                        <td><?= Html::encode($model->user->profile ? $model->user->profile->fullname : 'N/A') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td><?= Html::encode($model->user->email) ?></td>
                                    </tr>
                                </table>
                            <?php else: ?>
                                <p class="text-muted">Không có thông tin nhân viên</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-footer">
            <div class="text-right">
                <?= Html::a('<i class="fas fa-arrow-left"></i> Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
                <?= Html::a('<i class="fas fa-print"></i> In hóa đơn', ['print', 'id' => $model->id], [
                    'class' => 'btn btn-primary ml-2',
                    'target' => '_blank',
                ]) ?>
            </div>
        </div>
    </div>
</div>