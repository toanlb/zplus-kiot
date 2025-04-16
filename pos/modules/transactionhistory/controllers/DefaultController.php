<?php

namespace pos\modules\transactionhistory\controllers;

use Yii;
use common\models\TransactionHistory;
use common\models\TransactionHistorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * DefaultController quản lý các chức năng liên quan đến lịch sử giao dịch
 */
class DefaultController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'export' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Hiển thị danh sách lịch sử giao dịch
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransactionHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        // Lấy danh sách đơn hàng đã thanh toán
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Hiển thị chi tiết một giao dịch
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        // Lấy danh sách sản phẩm trong đơn hàng
        $orderItems = [];
        if ($model->order_id) {
            $orderItems = (new \yii\db\Query())
                ->select(['oi.*', 'p.code as product_code', 'p.barcode', 'p.current_stock'])
                ->from(['oi' => 'order_items'])
                ->leftJoin(['p' => 'products'], 'p.id = oi.product_id')
                ->where(['oi.order_id' => $model->order_id])
                ->all();
        }

        return $this->render('view', [
            'model' => $model,
            'orderItems' => $orderItems,
        ]);
    }

    /**
     * Xuất danh sách giao dịch ra file
     * @return mixed
     */
    public function actionExport()
    {
        $searchModel = new TransactionHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = false; // Lấy tất cả dữ liệu

        $transactions = $dataProvider->getModels();
        
        // Tạo dữ liệu xuất
        $data = [];
        $data[] = [
            'Mã giao dịch',
            'Mã đơn hàng',
            'Khách hàng',
            'Nhân viên',
            'Tổng tiền',
            'Giảm giá',
            'Thành tiền',
            'Đã thanh toán',
            'Trạng thái',
            'Loại giao dịch',
            'Thời gian'
        ];
        
        foreach ($transactions as $transaction) {
            $data[] = [
                $transaction->transaction_code,
                $transaction->order ? $transaction->order->code : 'N/A',
                $transaction->customer ? $transaction->customer->full_name : 'Khách lẻ',
                $transaction->user ? $transaction->user->username : 'N/A',
                $transaction->total_amount,
                $transaction->discount_amount,
                $transaction->final_amount,
                $transaction->paid_amount,
                $transaction->getPaymentStatusName(),
                $transaction->getTransactionTypeName(),
                Yii::$app->formatter->asDatetime($transaction->created_at)
            ];
        }
        
        // Đặt header cho file Excel
        Yii::$app->response->headers->add('Content-Type', 'application/vnd.ms-excel');
        Yii::$app->response->headers->add('Content-Disposition', 'attachment;filename="lich-su-giao-dich.xls"');
        
        $output = '';
        foreach ($data as $row) {
            $output .= implode("\t", $row) . "\n";
        }
        
        return $output;
    }
    
    /**
     * Tạo báo cáo lịch sử giao dịch
     * @return mixed
     */
    public function actionReport()
    {
        return $this->render('report', []);
    }
    
    /**
     * Lấy dữ liệu báo cáo cho biểu đồ
     * @return mixed
     */
    public function actionGetReportData()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $type = Yii::$app->request->get('type', 'daily');
        $dateFrom = Yii::$app->request->get('date_from');
        $dateTo = Yii::$app->request->get('date_to');
        
        $startDate = $dateFrom ? strtotime($dateFrom) : strtotime('-1 month');
        $endDate = $dateTo ? strtotime($dateTo . ' 23:59:59') : time();
        
        $query = TransactionHistory::find()
            ->where(['>=', 'created_at', $startDate])
            ->andWhere(['<=', 'created_at', $endDate]);
            
        // Lấy dữ liệu theo loại báo cáo
        switch ($type) {
            case 'daily':
                $result = $this->getDailyReportData($query);
                break;
            case 'weekly':
                $result = $this->getWeeklyReportData($query);
                break;
            case 'monthly':
                $result = $this->getMonthlyReportData($query);
                break;
            default:
                $result = $this->getDailyReportData($query);
                break;
        }
        
        return [
            'success' => true,
            'data' => $result,
        ];
    }
    
    /**
     * In hóa đơn giao dịch
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPrint($id)
    {
        $this->layout = 'print';
        
        $model = $this->findModel($id);
        
        // Lấy danh sách sản phẩm trong đơn hàng
        $orderItems = [];
        if ($model->order_id) {
            $orderItems = (new \yii\db\Query())
                ->select(['oi.*', 'p.code as product_code', 'p.barcode'])
                ->from(['oi' => 'order_items'])
                ->leftJoin(['p' => 'products'], 'p.id = oi.product_id')
                ->where(['oi.order_id' => $model->order_id])
                ->all();
        }

        return $this->render('print', [
            'model' => $model,
            'orderItems' => $orderItems,
        ]);
    }
    
    /**
     * Lấy dữ liệu báo cáo theo ngày
     * @param \yii\db\ActiveQuery $query
     * @return array
     */
    protected function getDailyReportData($query)
    {
        $results = $query->select([
                'date' => 'FROM_UNIXTIME(created_at, "%Y-%m-%d")',
                'total_sales' => 'SUM(final_amount)',
                'cash_sales' => 'SUM(cash_amount)',
                'card_sales' => 'SUM(card_amount)',
                'ewallet_sales' => 'SUM(ewallet_amount)',
                'bank_sales' => 'SUM(bank_transfer_amount)',
                'transaction_count' => 'COUNT(id)',
            ])
            ->groupBy(['FROM_UNIXTIME(created_at, "%Y-%m-%d")'])
            ->orderBy(['FROM_UNIXTIME(created_at, "%Y-%m-%d")' => SORT_ASC])
            ->asArray()
            ->all();
            
        return $results;
    }
    
    /**
     * Lấy dữ liệu báo cáo theo tuần
     * @param \yii\db\ActiveQuery $query
     * @return array
     */
    protected function getWeeklyReportData($query)
    {
        $results = $query->select([
                'week' => 'CONCAT(YEAR(FROM_UNIXTIME(created_at)), "-", WEEK(FROM_UNIXTIME(created_at)))',
                'start_date' => 'MIN(FROM_UNIXTIME(created_at, "%Y-%m-%d"))',
                'end_date' => 'MAX(FROM_UNIXTIME(created_at, "%Y-%m-%d"))',
                'total_sales' => 'SUM(final_amount)',
                'cash_sales' => 'SUM(cash_amount)',
                'card_sales' => 'SUM(card_amount)',
                'ewallet_sales' => 'SUM(ewallet_amount)',
                'bank_sales' => 'SUM(bank_transfer_amount)',
                'transaction_count' => 'COUNT(id)',
            ])
            ->groupBy(['YEAR(FROM_UNIXTIME(created_at))', 'WEEK(FROM_UNIXTIME(created_at))'])
            ->orderBy(['YEAR(FROM_UNIXTIME(created_at))' => SORT_ASC, 'WEEK(FROM_UNIXTIME(created_at))' => SORT_ASC])
            ->asArray()
            ->all();
            
        // Chuyển đổi định dạng kết quả
        foreach ($results as &$item) {
            $item['date'] = $item['start_date'] . ' - ' . $item['end_date'];
        }
            
        return $results;
    }
    
    /**
     * Lấy dữ liệu báo cáo theo tháng
     * @param \yii\db\ActiveQuery $query
     * @return array
     */
    protected function getMonthlyReportData($query)
    {
        $results = $query->select([
                'date' => 'FROM_UNIXTIME(created_at, "%Y-%m")',
                'month_name' => 'FROM_UNIXTIME(created_at, "%m/%Y")',
                'total_sales' => 'SUM(final_amount)',
                'cash_sales' => 'SUM(cash_amount)',
                'card_sales' => 'SUM(card_amount)',
                'ewallet_sales' => 'SUM(ewallet_amount)',
                'bank_sales' => 'SUM(bank_transfer_amount)',
                'transaction_count' => 'COUNT(id)',
            ])
            ->groupBy(['FROM_UNIXTIME(created_at, "%Y-%m")'])
            ->orderBy(['FROM_UNIXTIME(created_at, "%Y-%m")' => SORT_ASC])
            ->asArray()
            ->all();
            
        // Chuyển đổi định dạng kết quả
        foreach ($results as &$item) {
            $item['date'] = $item['month_name'];
        }
            
        return $results;
    }

    /**
     * Tìm model TransactionHistory dựa trên ID
     * @param integer $id
     * @return TransactionHistory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TransactionHistory::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Không tìm thấy giao dịch yêu cầu.');
    }
    
    /**
     * Override beforeAction để đặt layout
     * @param \yii\base\Action $action
     * @return boolean
     */
    public function beforeAction($action)
    {
        if (in_array($action->id, ['print'])) {
            $this->layout = 'print';
        }
        
        return parent::beforeAction($action);
    }
}