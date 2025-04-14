<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use pos\models\PosSession;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel pos\models\PosSessionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý ca làm việc';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pos-session-index">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            <div class="card-tools">
                <?= Html::a('<i class="fas fa-plus"></i> Tạo ca làm việc', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
            </div>
        </div>
        <div class="card-body">
            <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    [
                        'attribute' => 'user_id',
                        'value' => function ($model) {
                            return $model->user ? $model->user->username : 'N/A';
                        },
                        'filter' => ArrayHelper::map(User::find()->all(), 'id', 'username'),
                    ],
                    [
                        'attribute' => 'start_time',
                        'value' => function ($model) {
                            return Yii::$app->formatter->asDatetime($model->start_time);
                        },
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'date_from',
                            'dateFormat' => 'php:Y-m-d',
                            'options' => ['class' => 'form-control', 'placeholder' => 'Từ ngày'],
                        ]),
                    ],
                    [
                        'attribute' => 'end_time',
                        'value' => function ($model) {
                            return $model->end_time ? Yii::$app->formatter->asDatetime($model->end_time) : 'Đang mở';
                        },
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'date_to',
                            'dateFormat' => 'php:Y-m-d',
                            'options' => ['class' => 'form-control', 'placeholder' => 'Đến ngày'],
                        ]),
                    ],
                    [
                        'attribute' => 'start_amount',
                        'value' => function ($model) {
                            return PosSession::formatCurrency($model->start_amount);
                        },
                    ],
                    [
                        'attribute' => 'total_sales',
                        'value' => function ($model) {
                            return PosSession::formatCurrency($model->total_sales);
                        },
                    ],
                    [
                        'attribute' => 'status',
                        'value' => function ($model) {
                            return $model->status == PosSession::STATUS_ACTIVE ? 
                                   '<span class="badge badge-success">Đang mở</span>' : 
                                   '<span class="badge badge-secondary">Đã đóng</span>';
                        },
                        'format' => 'raw',
                        'filter' => [
                            PosSession::STATUS_ACTIVE => 'Đang mở',
                            PosSession::STATUS_CLOSED => 'Đã đóng',
                        ],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                if ($model->status == PosSession::STATUS_ACTIVE && $model->end_time === null) {
                                    return '';
                                }
                                return Html::a('<i class="fas fa-pencil-alt"></i>', $url, [
                                    'title' => 'Cập nhật',
                                    'data-toggle' => 'tooltip',
                                ]);
                            },
                            'delete' => function ($url, $model, $key) {
                                if ($model->status == PosSession::STATUS_ACTIVE && $model->end_time === null) {
                                    return '';
                                }
                                return Html::a('<i class="fas fa-trash"></i>', $url, [
                                    'title' => 'Xóa',
                                    'data-toggle' => 'tooltip',
                                    'data-confirm' => 'Bạn có chắc chắn muốn xóa ca làm việc này?',
                                    'data-method' => 'post',
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