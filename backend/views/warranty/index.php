<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Quản lý bảo hành';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-warranty-index">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            <div class="card-tools">
                <?= Html::a('<i class="fas fa-plus"></i> Thêm mới', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
            </div>
        </div>
        <div class="card-body">
            <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'serial_number',
                    [
                        'attribute' => 'product_name',
                        'value' => 'product.name',
                    ],
                    [
                        'attribute' => 'customer_name',
                        'value' => 'customer.full_name',
                    ],
                    [
                        'attribute' => 'warranty_start_date',
                        'value' => function ($model) {
                            return Yii::$app->formatter->asDate($model->warranty_start_date);
                        },
                        'filter' => Html::activeTextInput($searchModel, 'warranty_start_date', ['class' => 'form-control', 'placeholder' => 'YYYY-MM-DD']),
                    ],
                    [
                        'attribute' => 'warranty_end_date',
                        'value' => function ($model) {
                            return Yii::$app->formatter->asDate($model->warranty_end_date);
                        },
                        'filter' => Html::activeTextInput($searchModel, 'warranty_end_date', ['class' => 'form-control', 'placeholder' => 'YYYY-MM-DD']),
                    ],
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
                        'filter' => [
                            'active' => 'Còn hiệu lực',
                            'expired' => 'Hết hạn',
                            'voided' => 'Đã hủy',
                        ],
                    ],
                    [
                        'attribute' => 'repair_count',
                        'value' => function ($model) {
                            return $model->repair_count ?: '0';
                        },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<i class="fas fa-eye"></i>', $url, [
                                    'title' => 'Xem',
                                    'class' => 'btn btn-primary btn-sm',
                                ]);
                            },
                            'update' => function ($url, $model) {
                                return Html::a('<i class="fas fa-edit"></i>', $url, [
                                    'title' => 'Cập nhật',
                                    'class' => 'btn btn-info btn-sm',
                                ]);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a('<i class="fas fa-trash"></i>', $url, [
                                    'title' => 'Xóa',
                                    'class' => 'btn btn-danger btn-sm',
                                    'data' => [
                                        'confirm' => 'Bạn có chắc chắn muốn xóa bảo hành này?',
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