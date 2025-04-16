<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\TransactionHistory;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TransactionHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lịch Sử Giao Dịch';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-history-index">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-history"></i> <?= Html::encode($this->title) ?>
            </h3>
            <div class="card-tools">
                <?= Html::a('<i class="fas fa-chart-bar"></i> Báo Cáo', ['report'], ['class' => 'btn btn-info btn-sm']) ?>
                <?= Html::a('<i class="fas fa-file-export"></i> Xuất Excel', ['export'] + Yii::$app->request->queryParams, ['class' => 'btn btn-success btn-sm']) ?>
            </div>
        </div>
        
        <div class="card-body">
            <?php Pjax::begin(); ?>
            
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="accordion" id="searchAccordion">
                        <div class="card">
                            <div class="card-header bg-light" id="headingSearch">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSearch" aria-expanded="true" aria-controls="collapseSearch">
                                        <i class="fas fa-search"></i> Tìm Kiếm Nâng Cao
                                    </button>
                                </h2>
                            </div>

                            <div id="collapseSearch" class="collapse" aria-labelledby="headingSearch" data-parent="#searchAccordion">
                                <div class="card-body">
                                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'transaction_code',
                        [
                            'attribute' => 'order_code',
                            'value' => function ($model) {
                                return $model->order ? $model->order->code : 'N/A';
                            },
                        ],
                        [
                            'attribute' => 'customer_name',
                            'value' => function ($model) {
                                return $model->customer ? $model->customer->full_name : 'Khách lẻ';
                            },
                        ],
                        [
                            'attribute' => 'user_name',
                            'value' => function ($model) {
                                return $model->user ? $model->user->username : 'N/A';
                            },
                        ],
                        [
                            'attribute' => 'final_amount',
                            'format' => 'currency',
                            'contentOptions' => ['class' => 'text-right'],
                            'headerOptions' => ['class' => 'text-right'],
                        ],
                        [
                            'attribute' => 'paid_amount',
                            'format' => 'currency',
                            'contentOptions' => ['class' => 'text-right'],
                            'headerOptions' => ['class' => 'text-right'],
                        ],
                        [
                            'attribute' => 'payment_status',
                            'filter' => TransactionHistory::getPaymentStatuses(),
                            'format' => 'raw',
                            'value' => function ($model) {
                                $badges = [
                                    TransactionHistory::STATUS_PAID => '<span class="badge badge-success">Đã thanh toán</span>',
                                    TransactionHistory::STATUS_PARTIAL => '<span class="badge badge-warning">Thanh toán một phần</span>',
                                    TransactionHistory::STATUS_PENDING => '<span class="badge badge-danger">Chưa thanh toán</span>',
                                ];
                                return $badges[$model->payment_status] ?? '<span class="badge badge-secondary">Không xác định</span>';
                            },
                        ],
                        [
                            'attribute' => 'transaction_type',
                            'filter' => TransactionHistory::getTransactionTypes(),
                            'format' => 'raw',
                            'value' => function ($model) {
                                $badges = [
                                    TransactionHistory::TYPE_SALE => '<span class="badge badge-primary">Bán hàng</span>',
                                    TransactionHistory::TYPE_RETURN => '<span class="badge badge-info">Hoàn trả</span>',
                                    TransactionHistory::TYPE_VOID => '<span class="badge badge-dark">Hủy giao dịch</span>',
                                    TransactionHistory::TYPE_CREDIT => '<span class="badge badge-secondary">Công nợ</span>',
                                ];
                                return $badges[$model->transaction_type] ?? '<span class="badge badge-secondary">Không xác định</span>';
                            },
                        ],
                        [
                            'attribute' => 'created_at',
                            'format' => 'datetime',
                            'filter' => false,
                        ],

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {print}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return Html::a('<i class="fas fa-eye"></i>', $url, [
                                        'title' => 'Xem chi tiết',
                                        'class' => 'btn btn-primary btn-sm',
                                        'data-pjax' => '0',
                                    ]);
                                },
                                'print' => function ($url, $model, $key) {
                                    return Html::a('<i class="fas fa-print"></i>', $url, [
                                        'title' => 'In hóa đơn',
                                        'class' => 'btn btn-info btn-sm ml-1',
                                        'target' => '_blank',
                                        'data-pjax' => '0',
                                    ]);
                                },
                            ],
                            'urlCreator' => function ($action, $model, $key, $index) {
                                if ($action === 'view') {
                                    return Url::to(['view', 'id' => $model->id]);
                                }
                                if ($action === 'print') {
                                    return Url::to(['print', 'id' => $model->id]);
                                }
                            }
                        ],
                    ],
                ]); ?>
            </div>
            
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>