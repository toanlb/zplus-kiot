<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $orderItems common\models\OrderItem[] */
/* @var $orderDetail common\models\OrderDetail */
/* @var $orderPayment common\models\OrderPayment */

$this->title = 'Hóa đơn: ' . $model->code;
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
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .invoice-header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .invoice-info {
            margin-bottom: 20px;
        }
        .invoice-info table {
            width: 100%;
        }
        .invoice-items {
            margin-bottom: 20px;
        }
        .invoice-items table {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-items th, .invoice-items td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .invoice-items th {
            background-color: #f2f2f2;
        }
        .invoice-total {
            float: right;
            width: 300px;
        }
        .invoice-total table {
            width: 100%;
        }
        .invoice-total th {
            text-align: left;
        }
        .invoice-total td {
            text-align: right;
        }
        .invoice-footer {
            margin-top: 50px;
            text-align: center;
            clear: both;
        }
        .signatures {
            margin-top: 50px;
            display: flex;
            justify-content: space-around;
        }
        .signature {
            text-align: center;
            width: 150px;
        }
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()">In hóa đơn</button>
        <button onclick="window.close()">Đóng</button>
    </div>
    
    <div class="invoice-header">
        <h1>HÓA ĐƠN BÁN HÀNG</h1>
        <p>Số hóa đơn: <?= $model->code ?></p>
        <p>Ngày: <?= date('d/m/Y H:i', $model->created_at) ?></p>
    </div>
    
    <div class="invoice-info">
        <table>
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <strong>Thông tin cửa hàng:</strong><br>
                    Tên công ty: Công ty của bạn<br>
                    Địa chỉ: Địa chỉ cửa hàng<br>
                    Số điện thoại: Số điện thoại<br>
                    Email: Email cửa hàng<br>
                </td>
                <td style="width: 50%; vertical-align: top;">
                    <strong>Thông tin khách hàng:</strong><br>
                    <?php if ($model->customer): ?>
                    Mã KH: <?= $model->customer->code ?><br>
                    Khách hàng: <?= $model->customer->full_name ?><br>
                    Số điện thoại: <?= $model->customer->phone ?><br>
                    Địa chỉ: <?= $model->customer->address ?><br>
                    <?php else: ?>
                    Khách lẻ<br>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="invoice-items">
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">STT</th>
                    <th style="width: 15%;">Mã SP</th>
                    <th style="width: 30%;">Tên sản phẩm</th>
                    <th style="width: 10%;">Đơn vị</th>
                    <th style="width: 10%;">Số lượng</th>
                    <th style="width: 10%;">Đơn giá</th>
                    <th style="width: 10%;">Giảm giá</th>
                    <th style="width: 10%;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 1;
                foreach ($orderItems as $item): 
                ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= $item->product_code ?></td>
                    <td><?= $item->product_name ?></td>
                    <td><?= $item->unit ?></td>
                    <td><?= Yii::$app->formatter->asDecimal($item->quantity, 0) ?></td>
                    <td><?= Yii::$app->formatter->asDecimal($item->unit_price, 0) ?></td>
                    <td>
                        <?php if ($item->discount_percentage > 0): ?>
                        <?= $item->discount_percentage ?>% (<?= Yii::$app->formatter->asDecimal($item->discount_amount, 0) ?>)
                        <?php else: ?>
                        -
                        <?php endif; ?>
                    </td>
                    <td><?= Yii::$app->formatter->asDecimal($item->final_price, 0) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="invoice-total">
        <table>
            <tr>
                <th>Tổng tiền hàng:</th>
                <td><?= Yii::$app->formatter->asDecimal($model->total_amount, 0) ?></td>
            </tr>
            <tr>
                <th>Giảm giá:</th>
                <td><?= Yii::$app->formatter->asDecimal($model->discount_amount, 0) ?></td>
            </tr>
            <?php if ($orderDetail && $orderDetail->delivery_fee > 0): ?>
            <tr>
                <th>Phí vận chuyển:</th>
                <td><?= Yii::$app->formatter->asDecimal($orderDetail->delivery_fee, 0) ?></td>
            </tr>
            <?php endif; ?>
            <tr>
                <th style="font-size: 14px;">Thành tiền:</th>
                <td style="font-size: 14px; font-weight: bold;"><?= Yii::$app->formatter->asDecimal($model->final_amount, 0) ?></td>
            </tr>
            <tr>
                <th>Đã thanh toán:</th>
                <td><?= Yii::$app->formatter->asDecimal($model->paid_amount, 0) ?></td>
            </tr>
            <tr>
                <th>Còn lại:</th>
                <td><?= Yii::$app->formatter->asDecimal($model->final_amount - $model->paid_amount, 0) ?></td>
            </tr>
        </table>
    </div>
    
    <div style="clear: both;"></div>
    
    <?php if ($orderPayment): ?>
    <div style="margin-top: 30px;">
        <strong>Hình thức thanh toán:</strong>
        <ul>
            <?php if ($orderPayment->cash_amount > 0): ?>
            <li>Tiền mặt: <?= Yii::$app->formatter->asDecimal($orderPayment->cash_amount, 0) ?></li>
            <?php endif; ?>
            <?php if ($orderPayment->card_amount > 0): ?>
            <li>Thẻ: <?= Yii::$app->formatter->asDecimal($orderPayment->card_amount, 0) ?></li>
            <?php endif; ?>
            <?php if ($orderPayment->bank_transfer_amount > 0): ?>
            <li>Chuyển khoản: <?= Yii::$app->formatter->asDecimal($orderPayment->bank_transfer_amount, 0) ?></li>
            <?php endif; ?>
            <?php if ($orderPayment->ewallet_amount > 0): ?>
            <li>Ví điện tử: <?= Yii::$app->formatter->asDecimal($orderPayment->ewallet_amount, 0) ?></li>
            <?php endif; ?>
            <?php if ($orderPayment->points_used > 0): ?>
            <li>Điểm tích lũy: <?= $orderPayment->points_used ?> điểm</li>
            <?php endif; ?>
            <?php if ($orderPayment->voucher_code): ?>
            <li>Voucher <?= $orderPayment->voucher_code ?>: <?= Yii::$app->formatter->asDecimal($orderPayment->voucher_amount, 0) ?></li>
            <?php endif; ?>
        </ul>
    </div>
    <?php endif; ?>
    
    <?php if ($orderDetail && $orderDetail->order_note): ?>
    <div style="margin-top: 20px;">
        <strong>Ghi chú:</strong>
        <p><?= nl2br(Html::encode($orderDetail->order_note)) ?></p>
    </div>
    <?php endif; ?>
    
    <div class="signatures">
        <div class="signature">
            <p>Người bán hàng</p>
            <p>(Ký, ghi rõ họ tên)</p>
            <br><br><br>
            <p><?= $orderDetail ? $orderDetail->salesperson : '' ?></p>
        </div>
        <div class="signature">
            <p>Người nhận hàng</p>
            <p>(Ký, ghi rõ họ tên)</p>
            <br><br><br>
            <p><?= $orderDetail ? $orderDetail->receiver_name : '' ?></p>
        </div>
    </div>
    
    <div class="invoice-footer">
        <p>Cảm ơn quý khách đã mua hàng!</p>
        <p>Hẹn gặp lại quý khách.</p>
    </div>
</body>
</html>