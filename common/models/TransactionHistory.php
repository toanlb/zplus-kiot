<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Model cho bảng "transaction_history"
 *
 * @property int $id
 * @property string $transaction_code Mã giao dịch
 * @property int $order_id ID của đơn hàng
 * @property int $user_id Người thực hiện giao dịch
 * @property int $pos_session_id Phiên POS
 * @property int $customer_id Khách hàng (nếu có)
 * @property float $total_amount Tổng tiền giao dịch
 * @property float $discount_amount Tổng tiền giảm giá
 * @property float $final_amount Tổng tiền thanh toán
 * @property float $paid_amount Số tiền đã thanh toán
 * @property float $cash_amount Tiền mặt
 * @property float $card_amount Thanh toán thẻ
 * @property float $ewallet_amount Thanh toán ví điện tử
 * @property float $bank_transfer_amount Chuyển khoản
 * @property string $payment_status Trạng thái thanh toán (paid, pending, partial)
 * @property string $transaction_type Loại giao dịch (sale, return, etc.)
 * @property string $notes Ghi chú
 * @property int $created_at Thời gian tạo
 * @property int $updated_at Thời gian cập nhật
 * 
 * @property Order $order
 * @property User $user
 * @property Customer $customer
 */
class TransactionHistory extends ActiveRecord
{
    const STATUS_PAID = 'paid';
    const STATUS_PENDING = 'pending';
    const STATUS_PARTIAL = 'partial';
    
    const TYPE_SALE = 'sale';
    const TYPE_RETURN = 'return';
    const TYPE_VOID = 'void';
    const TYPE_CREDIT = 'credit';
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction_history';
    }
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'user_id', 'total_amount', 'final_amount', 'transaction_type'], 'required'],
            [['order_id', 'user_id', 'pos_session_id', 'customer_id', 'created_at', 'updated_at'], 'integer'],
            [['total_amount', 'discount_amount', 'final_amount', 'paid_amount', 'cash_amount', 'card_amount', 'ewallet_amount', 'bank_transfer_amount'], 'number'],
            [['transaction_code'], 'string', 'max' => 50],
            [['payment_status', 'transaction_type'], 'string', 'max' => 20],
            [['notes'], 'string'],
            [['transaction_code'], 'unique'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::class, 'targetAttribute' => ['order_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'transaction_code' => 'Mã giao dịch',
            'order_id' => 'Mã đơn hàng',
            'user_id' => 'Nhân viên',
            'pos_session_id' => 'Phiên làm việc',
            'customer_id' => 'Khách hàng',
            'total_amount' => 'Tổng tiền',
            'discount_amount' => 'Giảm giá',
            'final_amount' => 'Thành tiền',
            'paid_amount' => 'Đã thanh toán',
            'cash_amount' => 'Tiền mặt',
            'card_amount' => 'Thẻ',
            'ewallet_amount' => 'Ví điện tử',
            'bank_transfer_amount' => 'Chuyển khoản',
            'payment_status' => 'Trạng thái thanh toán',
            'transaction_type' => 'Loại giao dịch',
            'notes' => 'Ghi chú',
            'created_at' => 'Thời gian tạo',
            'updated_at' => 'Cập nhật lúc',
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
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
     * Tạo transaction record từ order
     * 
     * @param Order $order
     * @param OrderPayment $payment
     * @return TransactionHistory
     */
    public static function createFromOrder($order, $payment)
    {
        $transaction = new self();
        $transaction->transaction_code = 'TRX' . time() . rand(1000, 9999);
        $transaction->order_id = $order->id;
        $transaction->user_id = Yii::$app->user->id;
        $transaction->pos_session_id = $order->pos_session_id ?? null;
        $transaction->customer_id = $order->customer_id;
        $transaction->total_amount = $order->total_amount;
        $transaction->discount_amount = $order->discount_amount ?? 0;
        $transaction->final_amount = $order->final_amount;
        $transaction->paid_amount = $payment->cash_amount + $payment->card_amount + 
                                   $payment->ewallet_amount + $payment->bank_transfer_amount;
        $transaction->cash_amount = $payment->cash_amount ?? 0;
        $transaction->card_amount = $payment->card_amount ?? 0;
        $transaction->ewallet_amount = $payment->ewallet_amount ?? 0;
        $transaction->bank_transfer_amount = $payment->bank_transfer_amount ?? 0;
        
        // Xác định trạng thái thanh toán
        if ($transaction->paid_amount >= $transaction->final_amount) {
            $transaction->payment_status = self::STATUS_PAID;
        } elseif ($transaction->paid_amount > 0) {
            $transaction->payment_status = self::STATUS_PARTIAL;
        } else {
            $transaction->payment_status = self::STATUS_PENDING;
        }
        
        $transaction->transaction_type = self::TYPE_SALE;
        
        return $transaction;
    }
    
    /**
     * Lấy danh sách các loại giao dịch
     * 
     * @return array
     */
    public static function getTransactionTypes()
    {
        return [
            self::TYPE_SALE => 'Bán hàng',
            self::TYPE_RETURN => 'Hoàn trả',
            self::TYPE_VOID => 'Hủy giao dịch',
            self::TYPE_CREDIT => 'Công nợ',
        ];
    }
    
    /**
     * Lấy danh sách trạng thái thanh toán
     * 
     * @return array
     */
    public static function getPaymentStatuses()
    {
        return [
            self::STATUS_PAID => 'Đã thanh toán',
            self::STATUS_PARTIAL => 'Thanh toán một phần',
            self::STATUS_PENDING => 'Chưa thanh toán',
        ];
    }
    
    /**
     * Lấy tên loại giao dịch
     * 
     * @return string
     */
    public function getTransactionTypeName()
    {
        $types = self::getTransactionTypes();
        return $types[$this->transaction_type] ?? 'Không xác định';
    }
    
    /**
     * Lấy tên trạng thái thanh toán
     * 
     * @return string
     */
    public function getPaymentStatusName()
    {
        $statuses = self::getPaymentStatuses();
        return $statuses[$this->payment_status] ?? 'Không xác định';
    }
}