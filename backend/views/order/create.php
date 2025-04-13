<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $orderDetail common\models\OrderDetail */
/* @var $orderPayment common\models\OrderPayment */
/* @var $orderItems common\models\OrderItem[] */

$this->title = 'Tạo Đơn hàng mới';
$this->params['breadcrumbs'][] = ['label' => 'Quản lý Đơn hàng', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-create">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
                'orderDetail' => $orderDetail,
                'orderPayment' => $orderPayment,
                'orderItems' => $orderItems,
            ]) ?>
        </div>
    </div>
</div>