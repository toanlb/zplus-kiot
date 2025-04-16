<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_items".
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property string $product_code
 * @property string|null $barcode
 * @property string $product_name
 * @property string|null $brand
 * @property string|null $unit
 * @property float $quantity
 * @property float $unit_price
 * @property float|null $discount_percentage
 * @property float|null $discount_amount
 * @property float $final_price
 * @property string|null $warranty_note
 * @property string|null $maintenance_note
 * @property string|null $product_note
 *
 * @property Order $order
 * @property Product $product
 * @property ProductWarranty[] $productWarranties
 */
class OrderItem extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['barcode', 'brand', 'unit', 'warranty_note', 'maintenance_note', 'product_note'], 'default', 'value' => null],
            [['discount_amount'], 'default', 'value' => 0.00],
            [['order_id', 'product_id', 'product_code', 'product_name', 'quantity', 'unit_price', 'final_price'], 'required'],
            [['order_id', 'product_id'], 'integer'],
            [['quantity', 'unit_price', 'discount_percentage', 'discount_amount', 'final_price'], 'number'],
            [['warranty_note', 'maintenance_note', 'product_note'], 'string'],
            [['product_code', 'unit'], 'string', 'max' => 50],
            [['barcode'], 'string', 'max' => 100],
            [['product_name', 'brand'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::class, 'targetAttribute' => ['order_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'product_id' => 'Product ID',
            'product_code' => 'Product Code',
            'barcode' => 'Barcode',
            'product_name' => 'Product Name',
            'brand' => 'Brand',
            'unit' => 'Unit',
            'quantity' => 'Quantity',
            'unit_price' => 'Unit Price',
            'discount_percentage' => 'Discount Percentage',
            'discount_amount' => 'Discount Amount',
            'final_price' => 'Final Price',
            'warranty_note' => 'Warranty Note',
            'maintenance_note' => 'Maintenance Note',
            'product_note' => 'Product Note',
        ];
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * Gets query for [[ProductWarranties]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductWarranty()
    {
        return $this->hasMany(ProductWarranty::class, ['order_item_id' => 'id']);
    }

}
