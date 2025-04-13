<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ProductUnit */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Đơn vị tính', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-unit-view">

    <p>
        <?= Html::a('Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Xóa', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Bạn có chắc chắn muốn xóa đơn vị tính này?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Thông tin chi tiết đơn vị tính</h3>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'code',
                    'name',
                    [
                        'attribute' => 'base_unit_id',
                        'value' => $model->baseUnit ? $model->baseUnit->name : 'Đơn vị cơ bản',
                    ],
                    'conversion_rate',
                    'description:ntext',
                ],
            ]) ?>
        </div>
    </div>
</div>