<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_warranties".
 *
 * @property int $id
 * @property int $order_item_id
 * @property int $product_id
 * @property int $customer_id
 * @property string $serial_number
 * @property string $warranty_start_date
 * @property string $warranty_end_date
 * @property string|null $warranty_type
 * @property int $warranty_duration_months
 * @property string|null $status
 * @property string $original_purchase_date
 * @property float $original_purchase_price
 * @property string|null $last_service_date
 * @property string|null $next_service_date
 * @property int|null $repair_count
 * @property float|null $total_repair_cost
 * @property string|null $warranty_terms
 * @property string|null $notes
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Customers $customer
 * @property OrderItems $orderItem
 * @property Products $product
 * @property WarrantyRepairLogs[] $warrantyRepairLogs
 */
class ProductWarranty extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_warranties';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['last_service_date', 'next_service_date', 'warranty_terms', 'notes'], 'default', 'value' => null],
            [['warranty_type'], 'default', 'value' => 'standard'],
            [['status'], 'default', 'value' => 'active'],
            [['repair_count'], 'default', 'value' => 0],
            [['total_repair_cost'], 'default', 'value' => 0.00],
            [['order_item_id', 'product_id', 'customer_id', 'serial_number', 'warranty_start_date', 'warranty_end_date', 'warranty_duration_months', 'original_purchase_date', 'original_purchase_price', 'created_at', 'updated_at'], 'required'],
            [['order_item_id', 'product_id', 'customer_id', 'warranty_duration_months', 'repair_count', 'created_at', 'updated_at'], 'integer'],
            [['warranty_start_date', 'warranty_end_date', 'original_purchase_date', 'last_service_date', 'next_service_date'], 'safe'],
            [['original_purchase_price', 'total_repair_cost'], 'number'],
            [['warranty_terms', 'notes'], 'string'],
            [['serial_number'], 'string', 'max' => 100],
            [['warranty_type', 'status'], 'string', 'max' => 20],
            [['serial_number'], 'unique'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customers::class, 'targetAttribute' => ['customer_id' => 'id']],
            [['order_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderItems::class, 'targetAttribute' => ['order_item_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_item_id' => 'Order Item ID',
            'product_id' => 'Product ID',
            'customer_id' => 'Customer ID',
            'serial_number' => 'Serial Number',
            'warranty_start_date' => 'Warranty Start Date',
            'warranty_end_date' => 'Warranty End Date',
            'warranty_type' => 'Warranty Type',
            'warranty_duration_months' => 'Warranty Duration Months',
            'status' => 'Status',
            'original_purchase_date' => 'Original Purchase Date',
            'original_purchase_price' => 'Original Purchase Price',
            'last_service_date' => 'Last Service Date',
            'next_service_date' => 'Next Service Date',
            'repair_count' => 'Repair Count',
            'total_repair_cost' => 'Total Repair Cost',
            'warranty_terms' => 'Warranty Terms',
            'notes' => 'Notes',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
	public function getRepairLogs()
	{
		return $this->hasMany(WarrantyRepairLog::className(), ['warranty_id' => 'id']);
	}

	public function getProduct()
	{
		return $this->hasOne(Product::className(), ['id' => 'product_id']);
	}

	public function getCustomer()
	{
		return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
	}

	public function getOrderItem()
	{
		return $this->hasOne(OrderItem::className(), ['id' => 'order_item_id']);
	}

}
