<?php
namespace pos\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\User;
use common\models\Order;
use common\models\OrderPayment;

/**
 * PosSession model
 *
 * @property int $id
 * @property int $user_id
 * @property int $start_time
 * @property int $end_time
 * @property float $start_amount
 * @property float $end_amount
 * @property float $expected_amount
 * @property float $difference
 * @property float $cash_sales
 * @property float $card_sales
 * @property float $bank_transfer_sales
 * @property float $other_sales
 * @property float $total_sales
 * @property float $current_amount
 * @property string $note
 * @property string $close_note
 * @property int $status
 * @property int $created_at
 *
 * @property User $user
 * @property Order[] $orders
 */
class PosSession extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_CLOSED = 2;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%pos_session}}';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'start_time', 'status'], 'required'],
            [['user_id', 'start_time', 'end_time', 'status', 'created_at'], 'integer'],
            [['start_amount', 'end_amount', 'expected_amount', 'difference', 'cash_sales', 'card_sales', 'bank_transfer_sales', 'other_sales', 'total_sales', 'current_amount'], 'number'],
            [['note', 'close_note'], 'string'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Nhân viên',
            'start_time' => 'Thời gian bắt đầu',
            'end_time' => 'Thời gian kết thúc',
            'start_amount' => 'Số tiền ban đầu',
            'end_amount' => 'Số tiền kết thúc',
            'expected_amount' => 'Số tiền dự kiến',
            'difference' => 'Chênh lệch',
            'cash_sales' => 'Doanh số tiền mặt',
            'card_sales' => 'Doanh số thẻ',
            'bank_transfer_sales' => 'Doanh số chuyển khoản',
            'other_sales' => 'Doanh số khác',
            'total_sales' => 'Tổng doanh số',
            'current_amount' => 'Số tiền hiện tại',
            'note' => 'Ghi chú',
            'close_note' => 'Ghi chú đóng ca',
            'status' => 'Trạng thái',
            'created_at' => 'Thời gian tạo',
        ];
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
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::class, ['pos_session_id' => 'id']);
    }
    
    /**
     * Kiểm tra có phiên làm việc đang mở không
     *
     * @return bool
     */
    public static function hasActiveSession()
    {
        return self::find()
            ->where(['status' => self::STATUS_ACTIVE])
            ->andWhere(['user_id' => Yii::$app->user->id])
            ->exists();
    }
    
    /**
     * Lấy phiên làm việc đang mở
     *
     * @return PosSession|null
     */
    public static function getActiveSession()
    {
        return self::find()
            ->where(['status' => self::STATUS_ACTIVE])
            ->andWhere(['user_id' => Yii::$app->user->id])
            ->one();
    }
    
    /**
     * Tính toán thống kê phiên làm việc
     *
     * @param int $sessionId
     * @return array
     */
    public static function calculateSessionStats($sessionId)
    {
        // Tìm tất cả đơn hàng trong phiên
        $orders = Order::find()
            ->where(['pos_session_id' => $sessionId])
            ->andWhere(['status' => Order::STATUS_COMPLETED])
            ->all();
        
        $stats = [
            'cash_sales' => 0,
            'card_sales' => 0,
            'bank_transfer_sales' => 0,
            'other_sales' => 0,
            'total_sales' => 0,
            'orders_count' => count($orders),
        ];
        
        foreach ($orders as $order) {
            // Tổng doanh số
            $stats['total_sales'] += $order->total;
            
            // Phân loại theo phương thức thanh toán
            $payments = OrderPayment::find()
                ->where(['order_id' => $order->id])
                ->andWhere(['status' => OrderPayment::STATUS_COMPLETED])
                ->all();
            
            foreach ($payments as $payment) {
                switch ($payment->payment_method) {
                    case 'cash':
                        $stats['cash_sales'] += $payment->amount;
                        break;
                    case 'card':
                        $stats['card_sales'] += $payment->amount;
                        break;
                    case 'bank_transfer':
                        $stats['bank_transfer_sales'] += $payment->amount;
                        break;
                    default:
                        $stats['other_sales'] += $payment->amount;
                        break;
                }
            }
        }
        
        return $stats;
    }
    
    /**
     * Lấy danh sách trạng thái
     *
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE => 'Đang mở',
            self::STATUS_CLOSED => 'Đã đóng',
        ];
    }
    
    /**
     * Lấy tên trạng thái
     *
     * @return string
     */
    public function getStatusName()
    {
        $statuses = self::getStatusList();
        return isset($statuses[$this->status]) ? $statuses[$this->status] : 'Không xác định';
    }
    
    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = time();
            }
            return true;
        }
        return false;
    }
}