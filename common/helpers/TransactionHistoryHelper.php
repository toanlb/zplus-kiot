<?php

namespace common\helpers;

use Yii;
use common\models\Order;
use common\models\OrderPayment;
use common\models\TransactionHistory;

/**
 * Helper class để hỗ trợ các thao tác với lịch sử giao dịch
 */
class TransactionHistoryHelper
{
    /**
     * Tạo bản ghi lịch sử giao dịch từ đơn hàng và thanh toán
     * 
     * @param Order|array $order Đối tượng Order hoặc dữ liệu đơn hàng
     * @param OrderPayment|array $payment Đối tượng OrderPayment hoặc dữ liệu thanh toán
     * @param string $type Loại giao dịch (sale, return, void, credit)
     * @param string $notes Ghi chú cho giao dịch
     * @return TransactionHistory|null
     */
    public static function createTransactionRecord($order, $payment, $type = 'sale', $notes = '')
    {
        try {
            // Khởi tạo transaction
            $transaction = new TransactionHistory();
            $transaction->transaction_code = 'TRX' . time() . rand(1000, 9999);
            
            // Xử lý thông tin đơn hàng
            if ($order instanceof Order) {
                $transaction->order_id = $order->id;
                $transaction->customer_id = $order->customer_id;
                $transaction->total_amount = $order->total_amount;
                $transaction->discount_amount = property_exists($order, 'discount_amount') ? $order->discount_amount : 0;
                $transaction->final_amount = property_exists($order, 'final_amount') 
                    ? $order->final_amount 
                    : $order->total_amount - $transaction->discount_amount;
                $transaction->pos_session_id = property_exists($order, 'pos_session_id') ? $order->pos_session_id : null;
            } elseif (is_array($order)) {
                $transaction->order_id = $order['id'];
                $transaction->customer_id = $order['customer_id'] ?? null;
                $transaction->total_amount = $order['total_amount'];
                $transaction->discount_amount = $order['discount_amount'] ?? 0;
                $transaction->final_amount = $order['final_amount'] ?? ($order['total_amount'] - ($order['discount_amount'] ?? 0));
                $transaction->pos_session_id = $order['pos_session_id'] ?? null;
            } else {
                throw new \Exception('Invalid order data');
            }
            
            // Xử lý thông tin thanh toán
            if ($payment instanceof OrderPayment) {
                $paidAmount = 0;
                $transaction->cash_amount = property_exists($payment, 'cash_amount') ? $payment->cash_amount : 0;
                $transaction->card_amount = property_exists($payment, 'card_amount') ? $payment->card_amount : 0;
                $transaction->ewallet_amount = property_exists($payment, 'ewallet_amount') ? $payment->ewallet_amount : 0;
                $transaction->bank_transfer_amount = property_exists($payment, 'bank_transfer_amount') ? $payment->bank_transfer_amount : 0;
                $paidAmount = $transaction->cash_amount + $transaction->card_amount + 
                              $transaction->ewallet_amount + $transaction->bank_transfer_amount;
                $transaction->paid_amount = $paidAmount;
            } elseif (is_array($payment)) {
                $paidAmount = 0;
                $transaction->cash_amount = $payment['cash_amount'] ?? 0;
                $transaction->card_amount = $payment['card_amount'] ?? 0;
                $transaction->ewallet_amount = $payment['ewallet_amount'] ?? 0;
                $transaction->bank_transfer_amount = $payment['bank_transfer_amount'] ?? 0;
                $paidAmount = $transaction->cash_amount + $transaction->card_amount + 
                              $transaction->ewallet_amount + $transaction->bank_transfer_amount;
                $transaction->paid_amount = $paidAmount;
            } else {
                throw new \Exception('Invalid payment data');
            }
            
            // Thiết lập thông tin giao dịch
            $transaction->user_id = Yii::$app->user->id;
            $transaction->transaction_type = $type;
            $transaction->notes = $notes;
            
            // Xác định trạng thái thanh toán
            if ($transaction->paid_amount >= $transaction->final_amount) {
                $transaction->payment_status = TransactionHistory::STATUS_PAID;
            } elseif ($transaction->paid_amount > 0) {
                $transaction->payment_status = TransactionHistory::STATUS_PARTIAL;
            } else {
                $transaction->payment_status = TransactionHistory::STATUS_PENDING;
            }
            
            // Lưu bản ghi
            if ($transaction->save()) {
                return $transaction;
            } else {
                Yii::error('Failed to save transaction history: ' . json_encode($transaction->errors));
                return null;
            }
            
        } catch (\Exception $e) {
            Yii::error('Error creating transaction history: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Cập nhật tổng doanh số theo khoảng thời gian
     * 
     * @param string $dateFrom Ngày bắt đầu (Y-m-d)
     * @param string $dateTo Ngày kết thúc (Y-m-d)
     * @return array Dữ liệu tổng hợp
     */
    public static function getSalesStatistics($dateFrom = null, $dateTo = null)
    {
        // Nếu không có ngày bắt đầu, lấy ngày đầu tháng hiện tại
        if (!$dateFrom) {
            $dateFrom = date('Y-m-01');
        }
        
        // Nếu không có ngày kết thúc, lấy ngày hiện tại
        if (!$dateTo) {
            $dateTo = date('Y-m-d');
        }
        
        $startTime = strtotime($dateFrom . ' 00:00:00');
        $endTime = strtotime($dateTo . ' 23:59:59');
        
        // Truy vấn dữ liệu
        $query = TransactionHistory::find()
            ->where(['>=', 'created_at', $startTime])
            ->andWhere(['<=', 'created_at', $endTime]);
            
        $totalSales = $query->sum('final_amount');
        $totalCashSales = $query->sum('cash_amount');
        $totalCardSales = $query->sum('card_amount');
        $totalEwalletSales = $query->sum('ewallet_amount');
        $totalBankSales = $query->sum('bank_transfer_amount');
        $totalTransactions = $query->count();
        
        // Lấy danh sách các ngày trong khoảng thời gian
        $dailyStats = (new \yii\db\Query())
            ->select([
                'date' => 'FROM_UNIXTIME(created_at, "%Y-%m-%d")',
                'total_sales' => 'SUM(final_amount)',
                'cash_sales' => 'SUM(cash_amount)',
                'card_sales' => 'SUM(card_amount)',
                'ewallet_sales' => 'SUM(ewallet_amount)',
                'bank_sales' => 'SUM(bank_transfer_amount)',
                'transaction_count' => 'COUNT(id)',
            ])
            ->from('{{%transaction_history}}')
            ->where(['>=', 'created_at', $startTime])
            ->andWhere(['<=', 'created_at', $endTime])
            ->groupBy(['FROM_UNIXTIME(created_at, "%Y-%m-%d")'])
            ->orderBy(['FROM_UNIXTIME(created_at, "%Y-%m-%d")' => SORT_ASC])
            ->all();
        
        return [
            'totalSales' => $totalSales ?: 0,
            'totalCashSales' => $totalCashSales ?: 0,
            'totalCardSales' => $totalCardSales ?: 0,
            'totalEwalletSales' => $totalEwalletSales ?: 0,
            'totalBankSales' => $totalBankSales ?: 0,
            'totalTransactions' => $totalTransactions ?: 0,
            'dailyStats' => $dailyStats,
        ];
    }
    
    /**
     * Tạo mã giao dịch mới
     * 
     * @param string $prefix Tiền tố (mặc định: TRX)
     * @return string
     */
    public static function generateTransactionCode($prefix = 'TRX')
    {
        $timestamp = time();
        $random = mt_rand(1000, 9999);
        
        return $prefix . $timestamp . $random;
    }
}