<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TransactionHistory */
/* @var $orderItems array */

$this->title = 'In hóa đơn: ' . $model->transaction_code;

$this->registerCss('
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
        line-height: 1.3;
    }
    .receipt-header {
        text-align: center;
        margin-bottom: 15px;
    }
    .receipt-header h1 {
        font-size: 18px;
        margin: 5px 0;
    }
    .receipt-header p {
        margin: 5px 0;
    }
    .transaction-info {
        margin-bottom: 15px;
    }
    .transaction-info table {
        width: 100%;
    }
    .transaction-info th {
        text-align: left;
        width: 150px;
    }
    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
    }
    .items-table th, .items-table td {
        border: 1px solid #ddd;
        padding: 5px;
    }
    .items-table th {
        background-color: #f5f5f5;
    }
    .text-right {
        text-align: right;
    }
    .totals-table {
        width: 100%;
        margin-bottom: 15px;
    }
    .totals-table th {
        text-align: right;
        width: 70%;
    }
    .totals-table td {
        text-align: right;
        width: 30%;
    }
    .footer {
        text-align: center;
        margin-top: 20px;
        padding-top: 10px;
        border-top: 1px dashed #ccc;
    }
    .barcode {
        text-align: center;
        margin: 15px 0;
    }
    @media print {
        .btn-print {
            display: none;
        }
        body {
            margin: 0;
            padding: 15px;
        }
    }
');

$this->registerJs('
    // Auto print when page loads
    $(document).ready(function() {
        window.print();
    });
    
    // Button to print again
    $(document).on("click", "#btn-print", function() {
        window.print();
    });
');
?>

<div class="btn-print text-right mb-3">
    <?= Html::a('<i class="fas fa-print"></i> In lại', '#', ['id' => 'btn-print', 'class' => 'btn btn-primary']) ?>
    <?= Html::a('<i class="fas fa-arrow-left"></i> Quay lại', ['view', 'id' => $model->id], ['class' => 'btn btn-default ml-2']) ?>
</div>

<div class="receipt-container">
    <div class="receipt-header">
        <h1>CỬA HÀNG CỦA BẠN</h1>
        <p>Địa chỉ: 123 Đường ABC, Quận XYZ, TP. Hồ Chí Minh</p>
        <p>Hotline: 0123.456.789 | Email: info@yourdomain.com</p>
        <h2>HÓA ĐƠN BÁN HÀNG</h2>
    </div>
    
    <div class="transaction-info">
        <table>
            <tr>
                <th>Mã giao dịch:</th>
                <td><?= Html::encode($model->transaction_code) ?></td>
                <th>Thời gian:</th>
                <td><?= Yii::$app->formatter->asDatetime($model->created_at) ?></td>
            </tr>
            <tr>
                <th>Mã đơn hàng:</th>
                <td><?= $model->order ? Html::encode($model->order->code) : 'N/A' ?></td>
                <th>Nhân viên:</th>
                <td><?= $model->user ? Html::encode($model->user->username) : 'N/A' ?></td>
            </tr>
            <tr>
                <th>Khách hàng:</th>
                <td colspan="3">
                    <?php if ($model->customer): ?>
                        <?= Html::encode($model->customer->full_name) ?> | 
                        <?= Html::encode($model->customer->phone) ?>
                        <?php if ($model->customer->address): ?>
                            | <?= Html::encode($model->customer->address) ?>
                        <?php endif; ?>
                    <?php else: ?>
                        Khách hàng lẻ
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>
    
    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">STT</th>
                <th width="40%">Sản phẩm</th>
                <th width="15%" class="text-right">Đơn giá</th>
                <th width="10%" class="text-right">SL</th>
                <th width="15%" class="text-right">Giảm giá</th>
                <th width="15%" class="text-right">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($orderItems)): ?>
                <tr>
                    <td colspan="6" class="text-center">Không có dữ liệu sản phẩm</td>
                </tr>
            <?php else: ?>
                <?php foreach ($orderItems as $index => $item): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td>
                            <?= Html::encode($item['name']) ?>
                            <?php if (!empty($item['product_code'])): ?>
                                <br><small><?= Html::encode($item['product_code']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td class="text-right"><?= Yii::$app->formatter->asDecimal($item['price']) ?></td>
                        <td class="text-right"><?= $item['quantity'] ?></td>
                        <td class="text-right"><?= Yii::$app->formatter->asDecimal($item['discount'] * $item['quantity']) ?></td>
                        <td class="text-right"><?= Yii::$app->formatter->asDecimal($item['price'] * $item['quantity'] - ($item['discount'] * $item['quantity'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <table class="totals-table">
        <tr>
            <th>Tổng tiền hàng:</th>
            <td><?= Yii::$app->formatter->asDecimal($model->total_amount) ?></td>
        </tr>
        <tr>
            <th>Giảm giá:</th>
            <td><?= Yii::$app->formatter->asDecimal($model->discount_amount) ?></td>
        </tr>
        <tr>
            <th><strong>Thành tiền:</strong></th>
            <td><strong><?= Yii::$app->formatter->asDecimal($model->final_amount) ?></strong></td>
        </tr>
        <tr>
            <th>Tiền khách trả:</th>
            <td><?= Yii::$app->formatter->asDecimal($model->paid_amount) ?></td>
        </tr>
        <?php if ($model->paid_amount > $model->final_amount): ?>
        <tr>
            <th>Tiền thừa:</th>
            <td><?= Yii::$app->formatter->asDecimal($model->paid_amount - $model->final_amount) ?></td>
        </tr>
        <?php elseif ($model->paid_amount < $model->final_amount): ?>
        <tr>
            <th>Còn nợ:</th>
            <td><?= Yii::$app->formatter->asDecimal($model->final_amount - $model->paid_amount) ?></td>
        </tr>
        <?php endif; ?>
    </table>
    
    <div>
        <strong>Phương thức thanh toán:</strong>
        <ul>
            <?php if ($model->cash_amount > 0): ?>
                <li>Tiền mặt: <?= Yii::$app->formatter->asDecimal($model->cash_amount) ?></li>
            <?php endif; ?>
            <?php if ($model->card_amount > 0): ?>
                <li>Thẻ: <?= Yii::$app->formatter->asDecimal($model->card_amount) ?></li>
            <?php endif; ?>
            <?php if ($model->ewallet_amount > 0): ?>
                <li>Ví điện tử: <?= Yii::$app->formatter->asDecimal($model->ewallet_amount) ?></li>
            <?php endif; ?>
            <?php if ($model->bank_transfer_amount > 0): ?>
                <li>Chuyển khoản: <?= Yii::$app->formatter->asDecimal($model->bank_transfer_amount) ?></li>
            <?php endif; ?>
        </ul>
    </div>
    
    <?php if (!empty($model->notes)): ?>
    <div>
        <strong>Ghi chú:</strong>
        <p><?= Html::encode($model->notes) ?></p>
    </div>
    <?php endif; ?>
    
    <div class="barcode">
        <img src="data:image/png;base64,<?= base64_encode(\Picqer\Barcode\BarcodeGeneratorPNG::getBarcode($model->transaction_code, \Picqer\Barcode\BarcodeGeneratorPNG::TYPE_CODE_128)) ?>" alt="Barcode">
        <br>
        <span><?= $model->transaction_code ?></span>
    </div>
    
    <div class="footer">
        <p>Cảm ơn quý khách đã mua hàng!</p>
        <p>Hỗ trợ khách hàng: 0123.456.789</p>
        <p>www.yourdomain.com</p>
    </div>
</div>