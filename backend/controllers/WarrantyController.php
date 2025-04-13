<?php
namespace backend\controllers;

use Yii;
use common\models\ProductWarranty;
use common\models\ProductWarrantySearch;
use common\models\WarrantyRepairLog;
use common\models\WarrantyRepairLogSearch;
use common\models\Product;
use common\models\Customer;
use common\models\OrderItem;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class WarrantyController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new ProductWarrantySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        $repairSearchModel = new WarrantyRepairLogSearch();
        $repairSearchModel->warranty_id = $id;
        $repairDataProvider = $repairSearchModel->search([]);
        
        return $this->render('view', [
            'model' => $model,
            'repairSearchModel' => $repairSearchModel,
            'repairDataProvider' => $repairDataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new ProductWarranty();
        $model->status = 'active';
        $model->warranty_type = 'standard';
        $model->created_at = time();
        $model->updated_at = time();
        $model->repair_count = 0;
        $model->total_repair_cost = 0;

        // Pre-fill with request parameters
        if (isset($_GET['product_id'])) {
            $model->product_id = $_GET['product_id'];
        }
        
        if (isset($_GET['customer_id'])) {
            $model->customer_id = $_GET['customer_id'];
        }
        
        if (isset($_GET['order_item_id'])) {
            $model->order_item_id = $_GET['order_item_id'];
            
            // Auto-fill from order item
            $orderItem = OrderItem::findOne($model->order_item_id);
            if ($orderItem) {
                $model->product_id = $orderItem->product_id;
                $model->original_purchase_price = $orderItem->final_price;
                
                // Get purchase date from order
                if ($orderItem->order) {
                    $model->original_purchase_date = date('Y-m-d', $orderItem->order->created_at);
                    $model->warranty_start_date = date('Y-m-d', $orderItem->order->created_at);
                    
                    // Get product warranty period
                    if ($orderItem->product && $orderItem->product->warranty_months > 0) {
                        $model->warranty_duration_months = $orderItem->product->warranty_months;
                        $model->warranty_end_date = date('Y-m-d', strtotime($model->warranty_start_date . ' + ' . $model->warranty_duration_months . ' months'));
                    }
                }
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            // Calculate end date if not set
            if (empty($model->warranty_end_date) && !empty($model->warranty_start_date) && $model->warranty_duration_months > 0) {
                $model->warranty_end_date = date('Y-m-d', strtotime($model->warranty_start_date . ' + ' . $model->warranty_duration_months . ' months'));
            }
            
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->updated_at = time();

        if ($model->load(Yii::$app->request->post())) {
            // Calculate end date if not set
            if (empty($model->warranty_end_date) && !empty($model->warranty_start_date) && $model->warranty_duration_months > 0) {
                $model->warranty_end_date = date('Y-m-d', strtotime($model->warranty_start_date . ' + ' . $model->warranty_duration_months . ' months'));
            }
            
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    public function actionCreateRepair($warranty_id)
    {
        $warranty = $this->findModel($warranty_id);
        
        $model = new WarrantyRepairLog();
        $model->warranty_id = $warranty_id;
        $model->repair_date = date('Y-m-d');
        $model->created_at = time();
        $model->status = 'pending';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Update the warranty info
            $warranty->repair_count += 1;
            $warranty->total_repair_cost += $model->repair_cost;
            $warranty->last_service_date = $model->repair_date;
            if (!empty($model->next_service_recommendation)) {
                // Set next service date if recommended
                $warranty->next_service_date = date('Y-m-d', strtotime('+3 months', strtotime($model->repair_date)));
            }
            $warranty->updated_at = time();
            $warranty->save();
            
            return $this->redirect(['view', 'id' => $warranty_id]);
        }

        return $this->render('create-repair', [
            'model' => $model,
            'warranty' => $warranty,
        ]);
    }

    public function actionUpdateStatus($id, $status)
    {
        $model = $this->findModel($id);
        $model->status = $status;
        $model->updated_at = time();
        $model->save();
        
        return $this->redirect(['view', 'id' => $id]);
    }

    protected function findModel($id)
    {
        if (($model = ProductWarranty::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Không tìm thấy thông tin bảo hành.');
    }
}