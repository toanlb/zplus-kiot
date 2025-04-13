<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Product;
use common\models\Customer;
use common\models\OrderItem;

?>

<div class="product-warranty-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'serial_number')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'product_id')->dropDownList(
                ArrayHelper::map(Product::find()->all(), 'id', 'name'),
                ['prompt' => 'Chọn sản phẩm...']
            ) ?>

            <?= $form->field($model, 'customer_id')->dropDownList(
                ArrayHelper::map(Customer::find()->all(), 'id', 'full_name'),
                ['prompt' => 'Chọn khách hàng...']
            ) ?>

            <?= $form->field($model, 'order_item_id')->dropDownList(
                ArrayHelper::map(OrderItem::find()->all(), 'id', function($model) {
                    return $model->order->code . ' - ' . $model->product_name;
                }),
                ['prompt' => 'Chọn chi tiết đơn hàng...']
            ) ?>

            <?= $form->field($model, 'warranty_type')->dropDownList([
                'standard' => 'Tiêu chuẩn',
                'extended' => 'Mở rộng',
                'premium' => 'Cao cấp',
            ], ['prompt' => 'Chọn loại bảo hành...']) ?>

            <?= $form->field($model, 'warranty_duration_months')->textInput(['type' => 'number']) ?>

            <?= $form->field($model, 'status')->dropDownList([
                'active' => 'Còn hiệu lực',
                'expired' => 'Hết hạn',
                'voided' => 'Đã hủy',
            ], ['prompt' => 'Chọn trạng thái...']) ?>
        </div>
        
        <div class="col-md-6">
            <?= $form->field($model, 'warranty_start_date')->textInput(['type' => 'date']) ?>

            <?= $form->field($model, 'warranty_end_date')->textInput(['type' => 'date']) ?>

            <?= $form->field($model, 'original_purchase_date')->textInput(['type' => 'date']) ?>

            <?= $form->field($model, 'original_purchase_price')->textInput(['type' => 'number', 'step' => '0.01']) ?>

            <?= $form->field($model, 'next_service_date')->textInput(['type' => 'date']) ?>

            <?= $form->field($model, 'warranty_terms')->textarea(['rows' => 3]) ?>

            <?= $form->field($model, 'notes')->textarea(['rows' => 3]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('<i class="fas fa-save"></i> Lưu', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>