<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Bảo hành: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Quản lý bảo hành', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="product-warranty-view">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
                    <div class="card-tools">
                        <?= Html::a('<i class="fas fa-pencil-alt"></i> Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
                        <?= Html::a('<i class="fas fa-trash"></i> Xóa', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger btn-sm',
                            'data' => [
                                'confirm' => 'Bạn có chắc chắn muốn xóa bảo hành này?',
                                'method' => 'post',
                            ],
                        ]) ?>
                        
                        <?php
                        // Nút đổi trạng thái
                        if ($model->status === 'active') {
                            echo Html::a('<i class="fas fa-times"></i> Vô hiệu hóa', ['update-status', 'id' => $model->id, 'status' => 'voided'], [
                                'class' => 'btn btn-warning btn-sm',
                                'data' => [
                                    'confirm' => 'Bạn có chắc chắn muốn vô hiệu hóa bảo hành này?',
                                    'method' => 'post',
                                ],
                            ]);
                        } elseif ($model->status === 'voided') {
                            echo Html::a('<i class="fas fa-check"></i> Kích hoạt lại', ['update-status', 'id' => $model->id, 'status' => 'active'], [
                                'class' => 'btn btn-success btn-sm',
                                'data' => [
                                    'confirm' => 'Bạn có chắc chắn muốn kích hoạt lại bảo hành này?',
                                    'method' => 'post',
                                ],
                            ]);
                        }
                        ?>
                    </div>
                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'serial_number',
                            [
                                'attribute' => 'product_id',
                                'value' => $model->product->name,
                            ],
                            [
                                'attribute' => 'customer_id',
                                'value' => $model->customer->full_name,
                            ],
                            [
                                'attribute' => 'warranty_start_date',
                                'value' => Yii::$app->formatter->asDate($model->warranty_start_date),
                            ],
                            [
                                'attribute' => 'warranty_end_date',
                                'value' => Yii::$app->formatter->asDate($model->warranty_end_date),
                            ],
                            [
                                'attribute' => 'warranty_type',
                                'value' => function ($model) {
                                    $types = [
                                        'standard' => 'Tiêu chuẩn',
                                        'extended' => 'Mở rộng',
                                        'premium' => 'Cao cấp',
                                    ];
                                    return isset($types[$model->warranty_type]) ? $types[$model->warranty_type] : $model->warranty_type;
                                },
                            ],
                            'warranty_duration_months',
                            [
                                'attribute' => 'status',
                                'value' => function ($model) {
                                    $statusLabels = [
                                        'active' => '<span class="badge badge-success">Còn hiệu lực</span>',
                                        'expired' => '<span class="badge badge-danger">Hết hạn</span>',
                                        'voided' => '<span class="badge badge-secondary">Đã hủy</span>',
                                    ];
                                    return isset($statusLabels[$model->status]) ? $statusLabels[$model->status] : $model->status;
                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'original_purchase_date',
                                'value' => Yii::$app->formatter->asDate($model->original_purchase_date),
                            ],
                            [
                                'attribute' => 'original_purchase_price',
                                'value' => Yii::$app->formatter->asCurrency($model->original_purchase_price),
                            ],
                            [
                                'attribute' => 'last_service_date',
                                'value' => $model->last_service_date ? Yii::$app->formatter->asDate($model->last_service_date) : 'Chưa có',
                            ],
                            [
                                'attribute' => 'next_service_date',
                                'value' => $model->next_service_date ? Yii::$app->formatter->asDate($model->next_service_date) : 'Chưa có',
                            ],
                            'repair_count',
                            [
                                'attribute' => 'total_repair_cost',
                                'value' => Yii::$app->formatter->asCurrency($model->total_repair_cost),
                            ],
                            'warranty_terms:ntext',
                            'notes:ntext',
                            [
                                'attribute' => 'created_at',
                                'value' => Yii::$app->formatter->asDatetime($model->created_at),
                            ],
                            [
                                'attribute' => 'updated_at',
                                'value' => Yii::$app->formatter->asDatetime($model->updated_at),
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lịch sử sửa chữa</h3>
                    <div class="card-tools">
                        <?= Html::a('<i class="fas fa-plus"></i> Thêm sửa chữa', ['create-repair', 'warranty_id' => $model->id], ['class' => 'btn btn-success btn-sm']) ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php Pjax::begin(); ?>
                    <?= GridView::widget([
                        'dataProvider' => $repairDataProvider,
                        'filterModel' => $repairSearchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'repair_date',
                                'value' => function ($model) {
                                    return Yii::$app->formatter->asDate($model->repair_date);
                                },
                                'filter' => Html::activeTextInput($repairSearchModel, 'repair_date', ['class' => 'form-control', 'placeholder' => 'YYYY-MM-DD']),
                            ],
                            'technician',
                            'repair_location',
                            'issue_description:ntext',
                            [
                                'attribute' => 'repair_cost',
                                'value' => function ($model) {
                                    return Yii::$app->formatter->asCurrency($model->repair_cost);
                                },
                            ],
                            [
                                'attribute' => 'status',
                                'value' => function ($model) {
                                    $statusLabels = [
                                        'pending' => '<span class="badge badge-warning">Đang xử lý</span>',
                                        'completed' => '<span class="badge badge-success">Hoàn thành</span>',
                                        'cancelled' => '<span class="badge badge-danger">Đã hủy</span>',
                                    ];
                                    return isset($statusLabels[$model->status]) ? $statusLabels[$model->status] : $model->status;
                                },
                                'format' => 'raw',
                                'filter' => [
                                    'pending' => 'Đang xử lý',
                                    'completed' => 'Hoàn thành',
                                    'cancelled' => 'Đã hủy',
                                ],
                            ],
                        ],
                    ]); ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>