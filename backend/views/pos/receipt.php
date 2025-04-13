<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $order common\models\Order */
/* @var $orderItems common\models\OrderItem[] */
/* @var $orderDetail common\models\OrderDetail */
/* @var $orderPayment common\models\OrderPayment */

$this->title = 'Hóa đơn: ' . $order->code;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $this->title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            width: 80mm;
            margin: 0 auto;
            padding: 5mm;
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 10px;
        }
        .receipt-header h1 {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
        }
        .receipt-info {
            margin-bottom: 10px;
        }
        .receipt-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .receipt-items th, .receipt-items td {
            text-align: left;
            padding: 3px 0;
        }
        .receipt-items tr {
            border-bottom: 1px dashed #ddd;
        }
        .receipt-total {
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .receipt-total table {
            width: 100%;
        }
        .receipt-total th {
            text-align: left;
        }
        .receipt-total td {
            text-align: right;
        }
        .receipt-footer {
            text-align: center;
            margin-top: 20px;
            border-top: 1px dashed #ddd;
            padding-top: 10px;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()">In hóa đơn</button>
        <button onclick="window.close()">Đóng</button>
    </div>
    
    <div class="receipt-header">
        <h1>HÓA ĐƠN BÁN HÀNG</h1>
        <p>Công ty của bạn</p>
        <p>Địa chỉ: [Địa chỉ cửa hàng]</p>
        <p>SĐT: [Số điện thoại]</p>
    </div>
    
    <div class="receipt-info">
        <div>Số HĐ: <?= $order->code ?></div>
        <div>Ngày: <?= date('d/m/Y H:i', $order->created_at) ?></div>
        <div>
            Khách hàng: 
            <?= $order->customer ? $order->customer->full_name : 'Khách lẻ' ?>
        </div>
        <?php if ($order->customer && $order->customer->phone): ?>
        <div>SĐT: <?= $order->customer->phone ?></div>
        <?php endif; ?>
        <?php if ($orderDetail && $orderDetail->salesperson): ?>
        <div>NV bán hàng: <?= $orderDetail->salesperson ?></div>
        <?php endif; ?>
    </div>
    
    <table class="receipt-items">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>SL</th>
                <th>Đơn giá</th>
                <th>T.Tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orderItems as $item): ?>
            <tr>
                <td><?= $item->product_name ?></td>
                <td><?= $item->quantity ?></td>
                <td><?= Yii::$app->formatter->asDecimal($item->unit_price, 0) ?></td>
                <td><?= Yii::$app->formatter->asDecimal($item->final_price, 0) ?></td>
            </tr>
            <?php if ($item->discount_percentage > 0): ?>
            <tr>
                <td colspan="4" style="text-align: right;">
                    Giảm giá <?= $item->discount_percentage ?>%: <?= Yii::$app->formatter->asDecimal($item->discount_amount, 0) ?>
                </td>
            </tr>
            <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="receipt-total">
        <table>
            <tr>
                <th>Tổng tiền hàng:</th>
                <td><?= Yii::$app->formatter->asDecimal($order->total_amount, 0) ?></td>
            </tr>
            <?php if ($order->discount_amount > 0): ?>
            <tr>
                <th>Giảm giá:</th>
                <td><?= Yii::$app->formatter->asDecimal($order->discount_amount, 0) ?></td>
            </tr>
            <?php endif; ?>
            <tr>
                <th style="font-size: 14px; font-weight: bold;">Thanh toán:</th>
                <td style="font-size: 14px; font-weight: bold;"><?= Yii::$app->formatter->asDecimal($order->final_amount, 0) ?></td>
            </tr>
        </table>
    </div>
    
    <?php if ($orderPayment): ?>
    <div class="receipt-payment">
        <?php if ($orderPayment->cash_amount > 0): ?>
        <div>Tiền mặt: <?= Yii::$app->formatter->asDecimal($orderPayment->cash_amount, 0) ?></div>
        <?php endif; ?>
        <?php if ($orderPayment->card_amount > 0): ?>
        <div>Thẻ: <?= Yii::$app->formatter->asDecimal($orderPayment->card_amount, 0) ?></div>
        <?php endif; ?>
        <?php if ($orderPayment->bank_transfer_amount > 0): ?>
        <div>Chuyển khoản: <?= Yii::$app->formatter->asDecimal($orderPayment->bank_transfer_amount, 0) ?></div>
        <?php endif; ?>
        <?php if ($orderPayment->ewallet_amount > 0): ?>
        <div>Ví điện tử: <?= Yii::$app->formatter->asDecimal($orderPayment->ewallet_amount, 0) ?></div>
        <?php endif; ?>
        <?php if ($orderPayment->points_used > 0): ?>
        <div>Điểm tích lũy: <?= $orderPayment->points_used ?> điểm</div>
        <?php endif; ?>
        <?php if ($orderPayment->voucher_code): ?>
        <div>Mã giảm giá: <?= $orderPayment->voucher_code ?></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <?php if ($orderDetail && $orderDetail->order_note): ?>
    <div style="margin-top: 10px;">
        <strong>Ghi chú:</strong> <?= $orderDetail->order_note ?>
    </div>
    <?php endif; ?>
    
    <div class="receipt-footer">
        <p>Cảm ơn quý khách đã mua hàng!</p>
        <p>Hẹn gặp lại quý khách.</p>
    </div>
</body>
</html>