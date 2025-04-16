<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_payments".
 *
 * @property int $id
 * @property int $order_id
 * @property float|null $cash_amount
 * @property float|null $card_amount
 * @property float|null $ewallet_amount
 * @property float|null $bank_transfer_amount
 * @property int|null $points_used
 * @property string|null $voucher_code
 * @property float|null $voucher_amount
 * @property float|null $additional_fee
 *
 * @property Order $order
 */
class OrderPayment extends \yii\db\ActiveRecord
{
    const PAYMENT_METHOD_CASH = 1;
    const PAYMENT_METHOD_CARD = 2;
    const PAYMENT_METHOD_EWALLET = 3;
    const PAYMENT_METHOD_BANK_TRANSFER = 4;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_payments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['voucher_code'], 'default', 'value' => null],
            [['additional_fee'], 'default', 'value' => 0.00],
            [['points_used'], 'default', 'value' => 0],
            [['order_id'], 'required'],
            [['order_id', 'points_used'], 'integer'],
            [['cash_amount', 'card_amount', 'ewallet_amount', 'bank_transfer_amount', 'voucher_amount', 'additional_fee'], 'number'],
            [['voucher_code'], 'string', 'max' => 50],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::class, 'targetAttribute' => ['order_id' => 'id']],
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
            'cash_amount' => 'Cash Amount',
            'card_amount' => 'Card Amount',
            'ewallet_amount' => 'Ewallet Amount',
            'bank_transfer_amount' => 'Bank Transfer Amount',
            'points_used' => 'Points Used',
            'voucher_code' => 'Voucher Code',
            'voucher_amount' => 'Voucher Amount',
            'additional_fee' => 'Additional Fee',
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

}
