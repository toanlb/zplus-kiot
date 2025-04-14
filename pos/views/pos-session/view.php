<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use pos\models\PosSession;

/* @var $this yii\web\View */
/* @var $model pos\models\PosSession */

$this->title = 'Chi tiết ca làm việc #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Quản lý ca làm việc', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pos-session-view">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            <div class="card-tools">
                <?php
                if ($model->status == PosSession::STATUS_CLOSED) {
                    echo Html::a('<i class="fas fa-pencil-alt"></i> Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm mr-1']);
                    
                    // Kiểm tra xem ca có đơn hàng không
                    if ($model->getOrders()->count() == 0) {
                        echo Html::a('<i class="fas fa-trash"></i> Xóa', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger btn-sm',
                            'data' => [
                                'confirm' => 'Bạn có chắc chắn muốn xóa ca làm việc này?',
                                'method' => 'post',
                            ],
                        ]);
                    }
                }
                ?>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            [
                                'attribute' => 'user_id',
                                'value' => $model->user ? $model->user->username : 'N/A',
                            ],
                            [
                                'attribute' => 'start_time',
                                'value' => Yii::$app->formatter->asDatetime($model->start_time),
                            ],
                            [
                                'attribute' => 'end_time',
                                'value' => $model->end_time ? Yii::$app->formatter->asDatetime($model->end_time) : 'Đang mở',
                            ],
                            [
                                'attribute' => 'status',
                                'value' => $model->status == PosSession::STATUS_ACTIVE ? 
                                        '<span class="badge badge-success">Đang mở</span>' : 
                                        '<span class="badge badge-secondary">Đã đóng</span>',
                                'format' => 'raw',
                            ],
                            [
                                'label' => 'Thời gian làm việc',
                                'value' => $model->getWorkingTime(),
                            ],
                            [
                                'attribute' => 'note',
                                'value' => $model->note ? $model->note : 'Không có ghi chú',
                            ],
                            [
                                'attribute' => 'close_note',
                                'value' => $model->close_note ? $model->close_note : 'Không có ghi chú',
                                'visible' => $model->status == PosSession::STATUS_CLOSED,
                            ],
                        ],
                    ]) ?>
                </div>
                <div class="col-md-6">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'attribute' => 'start_amount',
                                'value' => PosSession::formatCurrency($model->start_amount),
                            ],
                            [
                                'attribute' => 'end_amount',
                                'value' => $model->end_amount ? PosSession::formatCurrency($model->end_amount) : 'Chưa đóng ca',
                                'visible' => $model->status == PosSession::STATUS_CLOSED,
                            ],
                            [
                                'attribute' => 'expected_amount',
                                'value' => $model->expected_amount ? PosSession::formatCurrency($model->expected_amount) : 'Chưa đóng ca',
                                'visible' => $model->status == PosSession::STATUS_CLOSED,
                            ],
                            [
                                'attribute' => 'difference',
                                'value' => function ($model) {
                                    if ($model->status == PosSession::STATUS_CLOSED) {
                                        if ($model->difference == 0) {
                                            return '<span class="text-success">Không có chênh lệch</span>';
                                        } elseif ($model->difference > 0) {
                                            return '<span class="text-primary">Thừa ' . PosSession::formatCurrency($model->difference) . '</span>';
                                        } else {
                                            return '<span class="text-danger">Thiếu ' . PosSession::formatCurrency(abs($model->difference)) . '</span>';
                                        }
                                    }
                                    return 'Chưa đóng ca';
                                },
                                'format' => 'raw',
                                'visible' => $model->status == PosSession::STATUS_CLOSED,
                            ],
                            [
                                'attribute' => 'cash_sales',
                                'value' => PosSession::formatCurrency($model->cash_sales),
                            ],
                            [
                                'attribute' => 'card_sales',
                                'value' => PosSession::formatCurrency($model->card_sales),
                            ],
                            [
                                'attribute' => 'bank_transfer_sales',
                                'value' => PosSession::formatCurrency($model->bank_transfer_sales),
                            ],
                            [
                                'attribute' => 'other_sales',
                                'value' => PosSession::formatCurrency($model->other_sales),
                            ],
                            [
                                'attribute' => 'total_sales',
                                'value' => PosSession::formatCurrency($model->total_sales),
                                'contentOptions' => ['class' => 'font-weight-bold'],
                            ],
                        ],
                    ]) ?>
                </div>
            </div>

            <?php
            // Hiển thị danh sách giao dịch nếu có
            if ($model->getOrders()->exists()) {
                echo '<h5 class="mt-4">Danh sách giao dịch</h5>';
                echo '<div class="table-responsive">';
                echo '<table class="table table-striped table-bordered">';
                echo '<thead><tr><th>#</th><th>Mã đơn</th><th>Khách hàng</th><th>Thời gian</th><th>Tổng tiền</th></tr></thead>';
                echo '<tbody>';
                
                foreach ($model->orders as $order) {
                    echo '<tr>';
                    echo '<td>' . $order->id . '</td>';
                    echo '<td>' . $order->code . '</td>';
                    echo '<td>' . ($order->customer_id ? $order->customer->full_name : 'Khách lẻ') . '</td>';
                    echo '<td>' . Yii::$app->formatter->asDatetime($order->created_at) . '</td>';
                    echo '<td class="text-right">' . PosSession::formatCurrency($order->final_amount) . '</td>';
                    echo '</tr>';
                }
                
                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</div>