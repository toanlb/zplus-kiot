<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property string $code
 * @property string|null $return_code
 * @property int|null $customer_id
 * @property float $total_amount
 * @property float|null $discount_amount
 * @property float $final_amount
 * @property float|null $paid_amount
 * @property int $created_at
 *
 * @property Customer $customer
 * @property OrderDetail[] $orderDetails
 * @property OrderItem[] $orderItems
 * @property OrderPayment[] $orderPayments
 */
class Order extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['return_code', 'customer_id'], 'default', 'value' => null],
            [['paid_amount'], 'default', 'value' => 0.00],
            [['code', 'total_amount', 'final_amount', 'created_at'], 'required'],
            [['customer_id', 'created_at'], 'integer'],
            [['total_amount', 'discount_amount', 'final_amount', 'paid_amount'], 'number'],
            [['code', 'return_code'], 'string', 'max' => 50],
            [['code'], 'unique'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::class, 'targetAttribute' => ['customer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'return_code' => 'Return Code',
            'customer_id' => 'Customer ID',
            'total_amount' => 'Total Amount',
            'discount_amount' => 'Discount Amount',
            'final_amount' => 'Final Amount',
            'paid_amount' => 'Paid Amount',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[OrderDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderDetails()
    {
        return $this->hasMany(OrderDetail::class, ['order_id' => 'id']);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }

    /**
     * Gets query for [[OrderPayments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderPayments()
    {
        return $this->hasMany(OrderPayment::class, ['order_id' => 'id']);
    }

}
