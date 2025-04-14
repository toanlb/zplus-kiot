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
            [['user_id', 'status'], 'required'],
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
        try {
            // Khởi tạo kết quả mặc định
            $stats = [
                'cash_sales' => 0,
                'card_sales' => 0,
                'bank_transfer_sales' => 0,
                'other_sales' => 0,
                'total_sales' => 0,
                'orders_count' => 0,
            ];
            
            // Kiểm tra các lớp model có tồn tại không
            if (!class_exists('common\models\Order') || !class_exists('common\models\OrderPayment')) {
                // Thử thay thế bằng truy vấn SQL trực tiếp
                return self::calculateStatsDirectSQL($sessionId);
            }
            
            // Tìm tất cả đơn hàng trong phiên, không kiểm tra trạng thái
            $orders = Order::find()
                ->where(['pos_session_id' => $sessionId])
                ->all();
            
            $stats['orders_count'] = count($orders);
            
            foreach ($orders as $order) {
                // Tổng doanh số, giả định đã có trường total hoặc final_amount
                $orderTotal = property_exists($order, 'total') ? $order->total : 
                            (property_exists($order, 'final_amount') ? $order->final_amount : 0);
                
                $stats['total_sales'] += $orderTotal;
                
                // Phân loại theo phương thức thanh toán
                $payments = OrderPayment::find()
                    ->where(['order_id' => $order->id])
                    ->all();
                
                if (empty($payments)) {
                    // Nếu không có thông tin thanh toán, mặc định coi là tiền mặt
                    $stats['cash_sales'] += $orderTotal;
                    continue;
                }
                
                foreach ($payments as $payment) {
                    // Kiểm tra trường payment_method tồn tại
                    $paymentMethod = property_exists($payment, 'payment_method') ? $payment->payment_method : 'cash';
                    // Kiểm tra trường amount tồn tại
                    $amount = property_exists($payment, 'amount') ? $payment->amount : $orderTotal;
                    
                    switch ($paymentMethod) {
                        case 'cash':
                            $stats['cash_sales'] += $amount;
                            break;
                        case 'card':
                            $stats['card_sales'] += $amount;
                            break;
                        case 'bank_transfer':
                            $stats['bank_transfer_sales'] += $amount;
                            break;
                        default:
                            $stats['other_sales'] += $amount;
                            break;
                    }
                }
            }
            
            return $stats;
        } catch (\Exception $e) {
            // Ghi log lỗi
            Yii::error('Error calculating session stats: ' . $e->getMessage());
            
            // Trả về dữ liệu mặc định
            return [
                'cash_sales' => 0,
                'card_sales' => 0,
                'bank_transfer_sales' => 0,
                'other_sales' => 0,
                'total_sales' => 0,
                'orders_count' => 0,
            ];
        }
    }
    
    /**
     * Tính toán thống kê bằng SQL trực tiếp
     * 
     * @param int $sessionId
     * @return array
     */
    public static function calculateStatsDirectSQL($sessionId)
    {
        try {
            $stats = [
                'cash_sales' => 0,
                'card_sales' => 0,
                'bank_transfer_sales' => 0,
                'other_sales' => 0,
                'total_sales' => 0,
                'orders_count' => 0,
            ];
            
            // Kiểm tra các bảng có tồn tại không
            $schema = Yii::$app->db->schema;
            
            $ordersTableExists = $schema->getTableSchema('orders') !== null;
            $paymentsTableExists = $schema->getTableSchema('order_payments') !== null;
            
            if (!$ordersTableExists) {
                return $stats;
            }
            
            // Đếm số đơn hàng
            $countCommand = Yii::$app->db->createCommand("
                SELECT COUNT(*) 
                FROM orders 
                WHERE pos_session_id = :sessionId
            ", [':sessionId' => $sessionId]);
            
            $stats['orders_count'] = (int)$countCommand->queryScalar();
            
            // Tính tổng doanh số
            $totalCommand = Yii::$app->db->createCommand("
                SELECT SUM(final_amount) 
                FROM orders 
                WHERE pos_session_id = :sessionId
            ", [':sessionId' => $sessionId]);
            
            $stats['total_sales'] = (float)$totalCommand->queryScalar() ?: 0;
            
            // Nếu có bảng thanh toán thì tính theo phương thức thanh toán
            if ($paymentsTableExists) {
                // Tính doanh số tiền mặt
                $cashCommand = Yii::$app->db->createCommand("
                    SELECT SUM(op.cash_amount) 
                    FROM order_payments op
                    JOIN orders o ON op.order_id = o.id
                    WHERE o.pos_session_id = :sessionId
                ", [':sessionId' => $sessionId]);
                
                $stats['cash_sales'] = (float)$cashCommand->queryScalar() ?: 0;
                
                // Tính doanh số thẻ
                $cardCommand = Yii::$app->db->createCommand("
                    SELECT SUM(op.card_amount) 
                    FROM order_payments op
                    JOIN orders o ON op.order_id = o.id
                    WHERE o.pos_session_id = :sessionId
                ", [':sessionId' => $sessionId]);
                
                $stats['card_sales'] = (float)$cardCommand->queryScalar() ?: 0;
                
                // Tính doanh số chuyển khoản
                $bankCommand = Yii::$app->db->createCommand("
                    SELECT SUM(op.bank_transfer_amount) 
                    FROM order_payments op
                    JOIN orders o ON op.order_id = o.id
                    WHERE o.pos_session_id = :sessionId
                ", [':sessionId' => $sessionId]);
                
                $stats['bank_transfer_sales'] = (float)$bankCommand->queryScalar() ?: 0;
                
                // Tính doanh số khác (ví điện tử)
                $otherCommand = Yii::$app->db->createCommand("
                    SELECT SUM(op.ewallet_amount) 
                    FROM order_payments op
                    JOIN orders o ON op.order_id = o.id
                    WHERE o.pos_session_id = :sessionId
                ", [':sessionId' => $sessionId]);
                
                $stats['other_sales'] = (float)$otherCommand->queryScalar() ?: 0;
            } else {
                // Nếu không có bảng thanh toán, mặc định coi tất cả là tiền mặt
                $stats['cash_sales'] = $stats['total_sales'];
            }
            
            return $stats;
        } catch (\Exception $e) {
            // Ghi log lỗi
            Yii::error('Error calculating session stats with SQL: ' . $e->getMessage());
            
            return [
                'cash_sales' => 0,
                'card_sales' => 0,
                'bank_transfer_sales' => 0,
                'other_sales' => 0,
                'total_sales' => 0,
                'orders_count' => 0,
            ];
        }
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
     * Mở phiên làm việc mới
     * 
     * @param float $cashAmount Số tiền đầu ca
     * @param string $note Ghi chú
     * @return PosSession|null
     */
    public static function openSession($cashAmount, $note = '')
    {
        $session = new self();
        $session->user_id = Yii::$app->user->id;
        $session->start_time = time();
        $session->start_amount = $cashAmount;
        $session->current_amount = $cashAmount;
        $session->note = $note;
        $session->status = self::STATUS_ACTIVE;
        $session->cash_sales = 0;
        $session->card_sales = 0;
        $session->bank_transfer_sales = 0;
        $session->other_sales = 0;
        $session->total_sales = 0;
        
        return $session->save() ? $session : null;
    }
    
    /**
     * Đóng phiên làm việc
     * 
     * @param float $closingAmount Số tiền cuối ca
     * @param string $note Ghi chú đóng ca
     * @return bool
     */
    public function closeSession($closingAmount, $note = '')
    {
        try {
            // Cập nhật thống kê trước khi đóng ca
            $this->updateStats();
            
            $this->end_time = time();
            $this->end_amount = $closingAmount;
            $this->close_note = $note;
            
            // Tính toán số tiền dự kiến và chênh lệch
            $this->expected_amount = $this->start_amount + $this->cash_sales;
            $this->difference = $closingAmount - $this->expected_amount;
            
            $this->status = self::STATUS_CLOSED;
            
            return $this->save();
        } catch (\Exception $e) {
            Yii::error('Error closing session: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Cập nhật thống kê của phiên
     * 
     * @return bool
     */
    public function updateStats()
    {
        try {
            $stats = self::calculateSessionStats($this->id);
            
            $this->cash_sales = $stats['cash_sales'];
            $this->card_sales = $stats['card_sales'];
            $this->bank_transfer_sales = $stats['bank_transfer_sales'];
            $this->other_sales = $stats['other_sales'];
            $this->total_sales = $stats['total_sales'];
            $this->current_amount = $this->start_amount + $stats['cash_sales'];
            
            return $this->save(false); // Lưu mà không chạy validation
        } catch (\Exception $e) {
            Yii::error('Error updating session stats: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tính thời gian làm việc
     * 
     * @return string
     */
    public function getWorkingTime()
    {
        $startTime = $this->start_time;
        $endTime = $this->end_time ? $this->end_time : time();
        
        $duration = $endTime - $startTime;
        
        $hours = floor($duration / 3600);
        $minutes = floor(($duration % 3600) / 60);
        
        return sprintf('%02d:%02d', $hours, $minutes);
    }
    
    /**
     * Định dạng số tiền thành chuỗi tiền tệ
     * 
     * @param float $amount
     * @return string
     */
    public static function formatCurrency($amount)
    {
        return Yii::$app->formatter->asCurrency($amount);
    }
    
    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = time();
                
                if (empty($this->start_time)) {
                    $this->start_time = time();
                }
            }
            return true;
        }
        return false;
    }
}