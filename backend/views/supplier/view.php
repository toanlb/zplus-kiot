<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Supplier */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Quản lý nhà cung cấp', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="supplier-view">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            <div class="card-tools">
                <?= Html::a('<i class="fas fa-pencil-alt"></i> Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
                <?= Html::a('<i class="fas fa-trash"></i> Xóa', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger btn-sm',
                    'data' => [
                        'confirm' => 'Bạn có chắc chắn muốn xóa nhà cung cấp này?',
                        'method' => 'post',
                    ],
                ]) ?>
                
                <?php
                // Nút đổi trạng thái
                if ($model->status == 1) {
                    echo Html::a('<i class="fas fa-times"></i> Vô hiệu hóa', ['update-status', 'id' => $model->id, 'status' => 0], [
                        'class' => 'btn btn-warning btn-sm',
                        'data' => [
                            'confirm' => 'Bạn có chắc chắn muốn vô hiệu hóa nhà cung cấp này?',
                            'method' => 'post',
                        ],
                    ]);
                } else {
                    echo Html::a('<i class="fas fa-check"></i> Kích hoạt', ['update-status', 'id' => $model->id, 'status' => 1], [
                        'class' => 'btn btn-success btn-sm',
                        'data' => [
                            'confirm' => 'Bạn có chắc chắn muốn kích hoạt nhà cung cấp này?',
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
                    'code',
                    'name',
                    'email:email',
                    'phone',
                    'address:ntext',
                    'area',
                    'ward',
                    'tax_code',
                    'company',
                    [
                        'attribute' => 'total_purchase',
                        'value' => number_format($model->total_purchase, 0, ',', '.') . ' VNĐ',
                    ],
                    [
                        'attribute' => 'total_purchase_net',
                        'value' => number_format($model->total_purchase_net, 0, ',', '.') . ' VNĐ',
                    ],
                    [
                        'attribute' => 'current_debt',
                        'value' => number_format($model->current_debt, 0, ',', '.') . ' VNĐ',
                    ],
                    'group',
                    [
                        'attribute' => 'status',
                        'value' => $model->status == 1 ? 
                            '<span class="badge badge-success">Đang hoạt động</span>' : 
                            '<span class="badge badge-danger">Ngừng hoạt động</span>',
                        'format' => 'raw',
                    ],
                    'creator',
                    [
                        'attribute' => 'created_at',
                        'value' => Yii::$app->formatter->asDatetime($model->created_at),
                    ],
                    'note:ntext',
                ],
            ]) ?>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Lịch sử giao dịch với nhà cung cấp</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Tính năng đang được phát triển. Các giao dịch mua hàng sẽ được hiển thị ở đây khi module quản lý nhập hàng được triển khai.
            </div>
            
            <?php if (false): // Đây là code mẫu sẽ được kích hoạt khi module nhập hàng hoàn thành ?>
            <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'dataProvider' => new \yii\data\ArrayDataProvider([
                    'allModels' => [],
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                ]),
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'code',
                    'date:date',
                    'total_amount',
                    'status',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<i class="fas fa-eye"></i>', ['#'], [
                                    'title' => 'Xem',
                                    'class' => 'btn btn-primary btn-sm',
                                ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
            <?php Pjax::end(); ?>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Thống kê</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-shopping-cart"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tổng mua hàng</span>
                            <span class="info-box-number"><?= number_format($model->total_purchase, 0, ',', '.') ?> VNĐ</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-money-bill"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tổng mua hàng ròng</span>
                            <span class="info-box-number"><?= number_format($model->total_purchase_net, 0, ',', '.') ?> VNĐ</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-credit-card"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Công nợ hiện tại</span>
                            <span class="info-box-number"><?= number_format($model->current_debt, 0, ',', '.') ?> VNĐ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Thông tin liên hệ</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="callout callout-info">
                        <h5><i class="fas fa-building"></i> Thông tin công ty</h5>
                        <p><strong>Công ty:</strong> <?= Html::encode($model->company ?: 'Chưa có thông tin') ?></p>
                        <p><strong>Mã số thuế:</strong> <?= Html::encode($model->tax_code ?: 'Chưa có thông tin') ?></p>
                        <p><strong>Địa chỉ:</strong> <?= Html::encode($model->address ?: 'Chưa có thông tin') ?></p>
                        <p><strong>Khu vực:</strong> <?= Html::encode($model->area ?: 'Chưa có thông tin') ?>, <strong>Phường/Xã:</strong> <?= Html::encode($model->ward ?: 'Chưa có thông tin') ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="callout callout-success">
                        <h5><i class="fas fa-user"></i> Thông tin liên hệ</h5>
                        <p><strong>Người liên hệ:</strong> <?= Html::encode($model->name) ?></p>
                        <p><strong>Email:</strong> <?= Html::encode($model->email ?: 'Chưa có thông tin') ?></p>
                        <p><strong>Số điện thoại:</strong> <?= Html::encode($model->phone ?: 'Chưa có thông tin') ?></p>
                        <p><strong>Nhóm:</strong> <?= Html::encode($model->group ?: 'Chưa phân loại') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>