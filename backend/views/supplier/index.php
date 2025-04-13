<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Quản lý nhà cung cấp';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-index">
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
                    'code',
                    'name',
                    'phone',
                    'email:email',
                    'company',
                    [
                        'attribute' => 'group',
                        'value' => function ($model) {
                            return $model->group ?: 'Chưa phân loại';
                        },
                    ],
                    [
                        'attribute' => 'total_purchase',
                        'value' => function ($model) {
                            return Yii::$app->formatter->asCurrency($model->total_purchase);
                        },
                    ],
                    [
                        'attribute' => 'current_debt',
                        'value' => function ($model) {
                            return Yii::$app->formatter->asCurrency($model->current_debt);
                        },
                    ],
                    [
                        'attribute' => 'status',
                        'value' => function ($model) {
                            return $model->status == 1 ? 
                                '<span class="badge badge-success">Đang hoạt động</span>' : 
                                '<span class="badge badge-danger">Ngừng hoạt động</span>';
                        },
                        'format' => 'raw',
                        'filter' => [1 => 'Đang hoạt động', 0 => 'Ngừng hoạt động'],
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
                                        'confirm' => 'Bạn có chắc chắn muốn xóa nhà cung cấp này?',
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