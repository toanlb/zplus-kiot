<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_details".
 *
 * @property int $id
 * @property int $order_id
 * @property string|null $branch
 * @property string|null $delivery_order_code
 * @property string|null $pickup_address
 * @property string|null $reconciliation_code
 * @property float|null $delivery_fee
 * @property string|null $salesperson
 * @property string|null $sales_channel
 * @property string|null $creator
 * @property string|null $delivery_partner
 * @property string|null $receiver_name
 * @property string|null $receiver_phone
 * @property string|null $receiver_address
 * @property string|null $receiver_area
 * @property string|null $receiver_ward
 * @property string|null $service
 * @property int|null $weight_grams
 * @property float|null $length_cm
 * @property float|null $width_cm
 * @property float|null $height_cm
 * @property string|null $delivery_status_note
 * @property string|null $delivery_note
 * @property string|null $order_note
 * @property float|null $cod_remaining
 * @property int|null $delivery_time
 * @property string|null $status
 * @property string|null $delivery_status
 *
 * @property Orders $order
 */
class OrderDetail extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['branch', 'delivery_order_code', 'pickup_address', 'reconciliation_code', 'salesperson', 'sales_channel', 'creator', 'delivery_partner', 'receiver_name', 'receiver_phone', 'receiver_address', 'receiver_area', 'receiver_ward', 'service', 'weight_grams', 'length_cm', 'width_cm', 'height_cm', 'delivery_status_note', 'delivery_note', 'order_note', 'delivery_time', 'status', 'delivery_status'], 'default', 'value' => null],
            [['cod_remaining'], 'default', 'value' => 0.00],
            [['order_id'], 'required'],
            [['order_id', 'weight_grams', 'delivery_time'], 'integer'],
            [['pickup_address', 'receiver_address', 'delivery_status_note', 'delivery_note', 'order_note'], 'string'],
            [['delivery_fee', 'length_cm', 'width_cm', 'height_cm', 'cod_remaining'], 'number'],
            [['branch', 'salesperson', 'sales_channel', 'creator', 'delivery_partner', 'receiver_area', 'receiver_ward', 'service'], 'string', 'max' => 100],
            [['delivery_order_code', 'reconciliation_code', 'status', 'delivery_status'], 'string', 'max' => 50],
            [['receiver_name'], 'string', 'max' => 255],
            [['receiver_phone'], 'string', 'max' => 20],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::class, 'targetAttribute' => ['order_id' => 'id']],
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
            'branch' => 'Branch',
            'delivery_order_code' => 'Delivery Order Code',
            'pickup_address' => 'Pickup Address',
            'reconciliation_code' => 'Reconciliation Code',
            'delivery_fee' => 'Delivery Fee',
            'salesperson' => 'Salesperson',
            'sales_channel' => 'Sales Channel',
            'creator' => 'Creator',
            'delivery_partner' => 'Delivery Partner',
            'receiver_name' => 'Receiver Name',
            'receiver_phone' => 'Receiver Phone',
            'receiver_address' => 'Receiver Address',
            'receiver_area' => 'Receiver Area',
            'receiver_ward' => 'Receiver Ward',
            'service' => 'Service',
            'weight_grams' => 'Weight Grams',
            'length_cm' => 'Length Cm',
            'width_cm' => 'Width Cm',
            'height_cm' => 'Height Cm',
            'delivery_status_note' => 'Delivery Status Note',
            'delivery_note' => 'Delivery Note',
            'order_note' => 'Order Note',
            'cod_remaining' => 'Cod Remaining',
            'delivery_time' => 'Delivery Time',
            'status' => 'Status',
            'delivery_status' => 'Delivery Status',
        ];
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Orders::class, ['id' => 'order_id']);
    }

}
