<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\User;

$this->title = 'Quản lý người dùng';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            <div class="card-tools">
                <?php if (Yii::$app->user->can('createUser')): ?>
                    <?= Html::a('<i class="fas fa-plus"></i> Thêm mới', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'username',
                    'full_name',
                    'email:email',
                    'phone',
                    'position',
                    [
                        'attribute' => 'status',
                        'value' => function ($model) {
                            $statuses = [
                                User::STATUS_ACTIVE => '<span class="badge badge-success">Đang hoạt động</span>',
                                User::STATUS_INACTIVE => '<span class="badge badge-warning">Chưa kích hoạt</span>',
                                User::STATUS_DELETED => '<span class="badge badge-danger">Đã vô hiệu</span>',
                            ];
                            return $statuses[$model->status] ?? $model->status;
                        },
                        'format' => 'raw',
                        'filter' => [
                            User::STATUS_ACTIVE => 'Đang hoạt động',
                            User::STATUS_INACTIVE => 'Chưa kích hoạt',
                            User::STATUS_DELETED => 'Đã vô hiệu',
                        ],
                    ],
                    [
                        'attribute' => 'created_at',
                        'value' => function ($model) {
                            return Yii::$app->formatter->asDate($model->created_at);
                        },
                        'filter' => Html::activeTextInput($searchModel, 'created_at', [
                            'class' => 'form-control',
                            'type' => 'date',
                        ]),
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Yii::$app->user->can('viewUser') ? Html::a('<i class="fas fa-eye"></i>', $url, [
                                    'title' => 'Xem',
                                    'class' => 'btn btn-primary btn-sm',
                                ]) : '';
                            },
                            'update' => function ($url, $model) {
                                return Yii::$app->user->can('updateUser') ? Html::a('<i class="fas fa-edit"></i>', $url, [
                                    'title' => 'Cập nhật',
                                    'class' => 'btn btn-info btn-sm',
                                ]) : '';
                            },
                            'delete' => function ($url, $model) {
                                // Không cho phép xóa tài khoản của chính mình
                                if ($model->id == Yii::$app->user->id) {
                                    return '';
                                }
                                return Yii::$app->user->can('deleteUser') ? Html::a('<i class="fas fa-trash"></i>', $url, [
                                    'title' => 'Xóa',
                                    'class' => 'btn btn-danger btn-sm',
                                    'data' => [
                                        'confirm' => 'Bạn có chắc chắn muốn xóa người dùng này?',
                                        'method' => 'post',
                                    ],
                                ]) : '';
                            },
                        ],
                    ],
                ],
            ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>