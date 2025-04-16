<?php

namespace common\components;

use Yii;
use yii\base\Component;
use common\models\Order;
use common\models\OrderPayment;
use common\models\TransactionHistory;

/**
 * TransactionHistoryListener tự động tạo bản ghi lịch sử giao dịch
 * khi có sự kiện liên quan đến đơn hàng
 */
class TransactionHistoryListener extends Component
{
    /**
     * Khởi tạo event listeners
     */
    public function init()
    {
        parent::init();
        
        // Đăng ký các event listeners
        Yii::$app->on('orderCompleted', [$this, 'handleOrderCompleted']);
        Yii::$app->on('orderVoided', [$this, 'handleOrderVoided']);
        Yii::$app->on('orderRefunded', [$this, 'handleOrderRefunded']);
        Yii::$app->on('orderPaymentAdded', [$this, 'handleOrderPaymentAdded']);
    }
    
    /**
     * Xử lý event khi đơn hàng được hoàn thành
     * 
     * @param \yii\base\Event $event
     */
    public function handleOrderCompleted($event)
    {
        $order = $event->data['order'];
        $payment = $event->data['payment'] ?? null;
        
        if (!$order instanceof Order) {
            Yii::error('Invalid order object in orderCompleted event');
            return;
        }
        
        // Nếu không có thông tin thanh toán, lấy thanh toán mới nhất
        if (!$payment) {
            $payment = OrderPayment::find()
                ->where(['order_id' => $order->id])
                ->orderBy(['id' => SORT_DESC])
                ->one();
        }
        
        // Tạo bản ghi lịch sử giao dịch
        try {
            $transaction = TransactionHistory::createFromOrder($order, $payment);
            $transaction->transaction_type = TransactionHistory::TYPE_SALE;
            $transaction->save();
            
            Yii::info('Created transaction history for order #' . $order->id);
        } catch (\Exception $e) {
            Yii::error('Error creating transaction history: ' . $e->getMessage());
        }
    }
    
    /**
     * Xử lý event khi đơn hàng bị hủy
     * 
     * @param \yii\base\Event $event
     */
    public function handleOrderVoided($event)
    {
        $order = $event->data['order'];
        $reason = $event->data['reason'] ?? '';
        
        if (!$order instanceof Order) {
            Yii::error('Invalid order object in orderVoided event');
            return;
        }
        
        // Lấy thanh toán mới nhất
        $payment = OrderPayment::find()
            ->where(['order_id' => $order->id])
            ->orderBy(['id' => SORT_DESC])
            ->one();
        
        // Tạo bản ghi lịch sử giao dịch
        try {
            $transaction = TransactionHistory::createFromOrder($order, $payment);
            $transaction->transaction_type = TransactionHistory::TYPE_VOID;
            $transaction->notes = 'Đơn hàng đã bị hủy. Lý do: ' . $reason;
            $transaction->save();
            
            Yii::info('Created void transaction history for order #' . $order->id);
        } catch (\Exception $e) {
            Yii::error('Error creating void transaction history: ' . $e->getMessage());
        }
    }
    
    /**
     * Xử lý event khi đơn hàng được hoàn trả
     * 
     * @param \yii\base\Event $event
     */
    public function handleOrderRefunded($event)
    {
        $order = $event->data['order'];
        $refundAmount = $event->data['amount'] ?? 0;
        $reason = $event->data['reason'] ?? '';
        
        if (!$order instanceof Order) {
            Yii::error('Invalid order object in orderRefunded event');
            return;
        }
        
        // Lấy thanh toán mới nhất
        $payment = OrderPayment::find()
            ->where(['order_id' => $order->id])
            ->orderBy(['id' => SORT_DESC])
            ->one();
        
        // Tạo bản ghi lịch sử giao dịch
        try {
            $transaction = TransactionHistory::createFromOrder($order, $payment);
            $transaction->transaction_type = TransactionHistory::TYPE_RETURN;
            $transaction->final_amount = -1 * abs($refundAmount);
            $transaction->paid_amount = -1 * abs($refundAmount);
            $transaction->notes = 'Hoàn trả. Lý do: ' . $reason;
            $transaction->save();
            
            Yii::info('Created refund transaction history for order #' . $order->id);
        } catch (\Exception $e) {
            Yii::error('Error creating refund transaction history: ' . $e->getMessage());
        }
    }
    
    /**
     * Xử lý event khi có thanh toán mới được thêm vào đơn hàng
     * 
     * @param \yii\base\Event $event
     */
    public function handleOrderPaymentAdded($event)
    {
        $order = $event->data['order'];
        $payment = $event->data['payment'];
        
        if (!$order instanceof Order || !$payment instanceof OrderPayment) {
            Yii::error('Invalid order or payment object in orderPaymentAdded event');
            return;
        }
        
        // Kiểm tra nếu là thanh toán bổ sung (không phải thanh toán đầu tiên)
        $paymentCount = OrderPayment::find()
            ->where(['order_id' => $order->id])
            ->count();
            
        if ($paymentCount <= 1) {
            // Nếu là thanh toán đầu tiên, đã được xử lý ở handleOrderCompleted
            return;
        }
        
        // Tạo bản ghi lịch sử giao dịch cho thanh toán bổ sung
        try {
            $transaction = new TransactionHistory();
            $transaction->transaction_code = 'TRX' . time() . rand(1000, 9999);
            $transaction->order_id = $order->id;
            $transaction->user_id = Yii::$app->user->id;
            $transaction->pos_session_id = $order->pos_session_id ?? null;
            $transaction->customer_id = $order->customer_id;
            $transaction->total_amount = 0; // Không tính lại tổng đơn hàng
            $transaction->discount_amount = 0;
            $transaction->final_amount = $payment->cash_amount + $payment->card_amount + 
                                        $payment->ewallet_amount + $payment->bank_transfer_amount;
            $transaction->paid_amount = $transaction->final_amount;
            $transaction->cash_amount = $payment->cash_amount ?? 0;
            $transaction->card_amount = $payment->card_amount ?? 0;
            $transaction->ewallet_amount = $payment->ewallet_amount ?? 0;
            $transaction->bank_transfer_amount = $payment->bank_transfer_amount ?? 0;
            $transaction->payment_status = TransactionHistory::STATUS_PAID;
            $transaction->transaction_type = TransactionHistory::TYPE_SALE;
            $transaction->notes = 'Thanh toán bổ sung cho đơn hàng #' . $order->code;
            $transaction->save();
            
            Yii::info('Created additional payment transaction history for order #' . $order->id);
        } catch (\Exception $e) {
            Yii::error('Error creating additional payment transaction history: ' . $e->getMessage());
        }
    }
}