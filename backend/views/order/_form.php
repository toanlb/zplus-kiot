<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Customer;
use common\models\Product;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $orderDetail common\models\OrderDetail */
/* @var $orderPayment common\models\OrderPayment */
/* @var $orderItems common\models\OrderItem[] */
/* @var $form yii\widgets\ActiveForm */

$js = '
jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper .panel-title-item").each(function(index) {
        jQuery(this).html("Sản phẩm: " + (index + 1))
    });
});

jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper .panel-title-item").each(function(index) {
        jQuery(this).html("Sản phẩm: " + (index + 1))
    });
    calculateTotals();
});

// Xử lý khi chọn sản phẩm
$(".dynamicform_wrapper").on("change", ".product-select", function() {
    var $row = $(this).closest(".item");
    var productId = $(this).val();
    if (productId) {
        $.get("' . Url::to(['get-product-info']) . '", {id: productId}, function(data) {
            data = JSON.parse(data);
            $row.find(".product-code").val(data.code);
            $row.find(".product-name").val(data.name);
            $row.find(".product-price").val(data.price);
            $row.find(".product-unit").val(data.unit);
            $row.find(".product-quantity").val(1);
            $row.find(".product-discount").val(0);
            
            calculateItemTotal($row);
            calculateTotals();
        });
    }
});

// Xử lý khi thay đổi giá, số lượng, giảm giá
$(".dynamicform_wrapper").on("change keyup", ".product-price, .product-quantity, .product-discount", function() {
    var $row = $(this).closest(".item");
    calculateItemTotal($row);
    calculateTotals();
});

// Tính toán thành tiền cho từng mục
function calculateItemTotal($row) {
    var price = parseFloat($row.find(".product-price").val()) || 0;
    var quantity = parseFloat($row.find(".product-quantity").val()) || 0;
    var discount = parseFloat($row.find(".product-discount").val()) || 0;
    
    var subtotal = price * quantity;
    var discountAmount = subtotal * discount / 100;
    var total = subtotal - discountAmount;
    
    $row.find(".product-subtotal").val(subtotal.toFixed(0));
    $row.find(".product-discount-amount").val(discountAmount.toFixed(0));
    $row.find(".product-total").val(total.toFixed(0));
}

// Tính tổng đơn hàng
function calculateTotals() {
    var totalAmount = 0;
    var totalDiscount = 0;
    
    $(".product-total").each(function() {
        totalAmount += parseFloat($(this).val()) || 0;
    });
    
    $(".product-discount-amount").each(function() {
        totalDiscount += parseFloat($(this).val()) || 0;
    });
    
    $("#order-total_amount").val(totalAmount.toFixed(0));
    $("#order-discount_amount").val(totalDiscount.toFixed(0));
    $("#order-final_amount").val(totalAmount.toFixed(0));
    
    // Cập nhật các trường khác nếu cần
}

// Xử lý khi chọn khách hàng
$("#customer-select").on("change", function() {
    var customerId = $(this).val();
    if (customerId) {
        $.get("' . Url::to(['get-customer-info']) . '", {id: customerId}, function(data) {
            data = JSON.parse(data);
            $("#customer-info-name").text(data.name);
            $("#customer-info-phone").text(data.phone);
            $("#customer-info-address").text(data.address);
            $("#customer-info-points").text(data.points);
        });
    } else {
        $("#customer-info-name").text("");
        $("#customer-info-phone").text("");
        $("#customer-info-address").text("");
        $("#customer-info-points").text("");
    }
});

// Khởi tạo
$(document).ready(function() {
    calculateTotals();
});
';

$this->registerJs($js);
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(['id' => 'order-form']); ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Thông tin đơn hàng</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <?= $form->field($model, 'code')->textInput(['maxlength' => true, 'readonly' => true]) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'customer_id')->dropDownList(
                                ArrayHelper::map(Customer::find()->all(), 'id', 'full_name'),
                                [
                                    'prompt' => 'Chọn khách hàng',
                                    'id' => 'customer-select'
                                ]
                            ) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($orderDetail, 'salesperson')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="card-title">Thông tin khách hàng</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3"><strong>Tên:</strong> <span id="customer-info-name"></span></div>
                                        <div class="col-md-3"><strong>SĐT:</strong> <span id="customer-info-phone"></span></div>
                                        <div class="col-md-3"><strong>Địa chỉ:</strong> <span id="customer-info-address"></span></div>
                                        <div class="col-md-3"><strong>Điểm:</strong> <span id="customer-info-points"></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dynamic Form cho danh sách sản phẩm -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="card-title">Danh sách sản phẩm</h5>
                        </div>
                        <div class="card-body">
                            <?php DynamicFormWidget::begin([
                                'widgetContainer' => 'dynamicform_wrapper',
                                'widgetBody' => '.container-items',
                                'widgetItem' => '.item',
                                'limit' => 100,
                                'min' => 1,
                                'insertButton' => '.add-item',
                                'deleteButton' => '.remove-item',
                                'model' => $orderItems[0],
                                'formId' => 'order-form',
                                'formFields' => [
                                    'product_id',
                                    'product_code',
                                    'product_name',
                                    'quantity',
                                    'unit_price',
                                    'discount_percentage',
                                    'discount_amount',
                                    'final_price',
                                ],
                            ]); ?>

                            <div class="container-items">
                                <?php foreach ($orderItems as $i => $orderItem): ?>
                                <div class="item card">
                                    <div class="card-header">
                                        <h3 class="card-title panel-title-item">Sản phẩm: <?= ($i + 1) ?></h3>
                                        <div class="card-tools">
                                            <button type="button" class="remove-item btn btn-danger btn-xs" title="Xóa">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <?= $form->field($orderItem, "[{$i}]product_id")->dropDownList(
                                                    ArrayHelper::map(Product::find()->all(), 'id', 'name'),
                                                    [
                                                        'prompt' => 'Chọn sản phẩm',
                                                        'class' => 'form-control product-select'
                                                    ]
                                                ) ?>
                                            </div>
                                            <div class="col-sm-3">
                                                <?= $form->field($orderItem, "[{$i}]product_code")->textInput(['maxlength' => true, 'class' => 'form-control product-code', 'readonly' => true]) ?>
                                            </div>
                                            <div class="col-sm-3">
                                                <?= $form->field($orderItem, "[{$i}]unit")->textInput(['maxlength' => true, 'class' => 'form-control product-unit', 'readonly' => true]) ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <?= $form->field($orderItem, "[{$i}]product_name")->textInput(['maxlength' => true, 'class' => 'form-control product-name', 'readonly' => true]) ?>
                                            </div>
                                            <div class="col-sm-3">
                                                <?= $form->field($orderItem, "[{$i}]unit_price")->textInput(['type' => 'number', 'class' => 'form-control product-price']) ?>
                                            </div>
                                            <div class="col-sm-3">
                                                <?= $form->field($orderItem, "[{$i}]quantity")->textInput(['type' => 'number', 'class' => 'form-control product-quantity']) ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <?= $form->field($orderItem, "[{$i}]discount_percentage")->textInput(['type' => 'number', 'class' => 'form-control product-discount']) ?>
                                            </div>
                                            <div class="col-sm-3">
                                                <?= $form->field($orderItem, "[{$i}]subtotal")->textInput(['class' => 'form-control product-subtotal', 'readonly' => true]) ?>
                                            </div>
                                            <div class="col-sm-3">
                                                <?= $form->field($orderItem, "[{$i}]discount_amount")->textInput(['class' => 'form-control product-discount-amount', 'readonly' => true]) ?>
                                            </div>
                                            <div class="col-sm-3">
                                                <?= $form->field($orderItem, "[{$i}]final_price")->textInput(['class' => 'form-control product-total', 'readonly' => true]) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="mt-3">
                                <button type="button" class="add-item btn btn-success btn-sm"><i class="fas fa-plus"></i> Thêm sản phẩm</button>
                            </div>
                            
                            <?php DynamicFormWidget::end(); ?>
                        </div>
                    </div>
                    
                    <!-- Ghi chú đơn hàng -->
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($orderDetail, 'order_note')->textarea(['rows' => 3]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Thông tin thanh toán -->
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">Thanh toán</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Tổng tiền hàng</label>
                        <?= $form->field($model, 'total_amount')->textInput(['readonly' => true])->label(false) ?>
                    </div>
                    
                    <div class="form-group">
                        <label>Tổng giảm giá</label>
                        <?= $form->field($model, 'discount_amount')->textInput(['readonly' => true])->label(false) ?>
                    </div>
                    
                    <div class="form-group">
                        <label class="font-weight-bold">Thanh toán</label>
                        <?= $form->field($model, 'final_amount')->textInput(['readonly' => true])->label(false) ?>
                    </div>
                    
                    <hr>
                    
                    <!-- Các hình thức thanh toán -->
                    <div class="form-group">
                        <?= $form->field($orderPayment, 'cash_amount')->textInput(['type' => 'number']) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $form->field($orderPayment, 'card_amount')->textInput(['type' => 'number']) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $form->field($orderPayment, 'bank_transfer_amount')->textInput(['type' => 'number']) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $form->field($orderPayment, 'ewallet_amount')->textInput(['type' => 'number']) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $form->field($orderPayment, 'points_used')->textInput(['type' => 'number']) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $form->field($orderPayment, 'voucher_code')->textInput(['maxlength' => true]) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $form->field($orderPayment, 'voucher_amount')->textInput(['type' => 'number']) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $form->field($model, 'paid_amount')->textInput(['type' => 'number']) ?>
                    </div>
                </div>
            </div>
            
            <!-- Thông tin giao hàng -->
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">Thông tin giao hàng</h3>
                </div>
                <div class="card-body">
                    <?= $form->field($orderDetail, 'delivery_partner')->dropDownList([
                        'Giao hàng nhanh' => 'Giao hàng nhanh',
                        'Viettel Post' => 'Viettel Post',
                        'GHTK' => 'GHTK',
                        'Giao hàng nội bộ' => 'Giao hàng nội bộ',
                    ], ['prompt' => 'Chọn đơn vị vận chuyển']) ?>
                    
                    <?= $form->field($orderDetail, 'delivery_fee')->textInput(['type' => 'number']) ?>
                    
                    <?= $form->field($orderDetail, 'receiver_name')->textInput(['maxlength' => true]) ?>
                    
                    <?= $form->field($orderDetail, 'receiver_phone')->textInput(['maxlength' => true]) ?>
                    
                    <?= $form->field($orderDetail, 'receiver_address')->textarea(['rows' => 3]) ?>
                    
                    <?= $form->field($orderDetail, 'delivery_note')->textarea(['rows' => 3]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Lưu đơn hàng', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>