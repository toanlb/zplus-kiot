<?php

namespace backend\controllers;

use Yii;
use common\models\Order;
use common\models\OrderSearch;
use common\models\OrderItem;
use common\models\OrderDetail;
use common\models\OrderPayment;
use common\models\Product;
use common\models\Customer;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class OrderController extends Controller
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
        ];
    }

    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $orderItems = OrderItem::find()->where(['order_id' => $id])->all();
        $orderDetail = OrderDetail::findOne(['order_id' => $id]);
        $orderPayment = OrderPayment::findOne(['order_id' => $id]);

        return $this->render('view', [
            'model' => $model,
            'orderItems' => $orderItems,
            'orderDetail' => $orderDetail,
            'orderPayment' => $orderPayment,
        ]);
    }
	
	public function actionPrint($id)
	{
		$model = $this->findModel($id);
		$orderItems = OrderItem::find()->where(['order_id' => $id])->all();
		$orderDetail = OrderDetail::findOne(['order_id' => $id]);
		$orderPayment = OrderPayment::findOne(['order_id' => $id]);

		return $this->renderPartial('print', [
			'model' => $model,
			'orderItems' => $orderItems,
			'orderDetail' => $orderDetail,
			'orderPayment' => $orderPayment,
		]);
	}

    public function actionCreate()
    {
        $model = new Order();
        $model->created_at = time();
        $model->code = $this->generateOrderCode();
        $model->total_amount = 0;
        $model->discount_amount = 0;
        $model->final_amount = 0;
        $model->paid_amount = 0;

        $orderDetail = new OrderDetail();
        $orderDetail->status = 'Mới';
        $orderDetail->delivery_status = 'Chưa giao';
        
        $orderPayment = new OrderPayment();
        
        // Mặc định có 1 sản phẩm trống
        $orderItems = [new OrderItem()];

        if ($model->load(Yii::$app->request->post()) && 
            $orderDetail->load(Yii::$app->request->post()) && 
            $orderPayment->load(Yii::$app->request->post())) {
            
            // Bắt đầu transaction
            $transaction = Yii::$app->db->beginTransaction();
            
            try {
                // Lưu đơn hàng chính
                if (!$model->save()) {
                    throw new \Exception('Không thể lưu đơn hàng');
                }
                
                // Lưu chi tiết đơn hàng
                $orderDetail->order_id = $model->id;
                if (!$orderDetail->save()) {
                    throw new \Exception('Không thể lưu chi tiết đơn hàng');
                }
                
                // Lưu thông tin thanh toán
                $orderPayment->order_id = $model->id;
                if (!$orderPayment->save()) {
                    throw new \Exception('Không thể lưu thông tin thanh toán');
                }
                
                // Lưu các mục sản phẩm
                $orderItemsData = Yii::$app->request->post('OrderItem', []);
                foreach ($orderItemsData as $i => $orderItemData) {
                    if (!empty($orderItemData['product_id'])) {
                        $orderItem = new OrderItem();
                        $orderItem->attributes = $orderItemData;
                        $orderItem->order_id = $model->id;
                        
                        // Lấy thông tin sản phẩm
                        $product = Product::findOne($orderItem->product_id);
                        if ($product) {
                            $orderItem->product_code = $product->code;
                            $orderItem->product_name = $product->name;
                            $orderItem->barcode = $product->barcode;
                            $orderItem->brand = $product->brand;
                            
                            // Tính toán giá trị
                            $orderItem->discount_amount = ($orderItem->unit_price * $orderItem->quantity) * ($orderItem->discount_percentage / 100);
                            $orderItem->final_price = ($orderItem->unit_price * $orderItem->quantity) - $orderItem->discount_amount;
                            
                            if (!$orderItem->save()) {
                                throw new \Exception('Không thể lưu mục sản phẩm');
                            }
                            
                            // Cập nhật tồn kho
                            $product->current_stock -= $orderItem->quantity;
                            if (!$product->save()) {
                                throw new \Exception('Không thể cập nhật tồn kho');
                            }
                        }
                    }
                }
                
                // Cập nhật thông tin khách hàng nếu có
                if ($model->customer_id) {
                    $customer = Customer::findOne($model->customer_id);
                    if ($customer) {
                        $customer->total_sales += $model->final_amount;
                        $customer->last_transaction_date = time();
                        
                        // Cộng điểm tích lũy cho khách hàng (giả sử cứ 100.000đ được 1 điểm)
                        $earnedPoints = floor($model->final_amount / 100000);
                        $customer->current_points += $earnedPoints;
                        $customer->total_points += $earnedPoints;
                        
                        if (!$customer->save()) {
                            throw new \Exception('Không thể cập nhật thông tin khách hàng');
                        }
                    }
                }
                
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Đơn hàng đã được tạo thành công');
                return $this->redirect(['view', 'id' => $model->id]);
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
            'orderDetail' => $orderDetail,
            'orderPayment' => $orderPayment,
            'orderItems' => $orderItems,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $orderDetail = OrderDetail::findOne(['order_id' => $id]) ?: new OrderDetail();
        $orderPayment = OrderPayment::findOne(['order_id' => $id]) ?: new OrderPayment();
        $orderItems = OrderItem::find()->where(['order_id' => $id])->all();
        
        if (empty($orderItems)) {
            $orderItems = [new OrderItem()];
        }

        if ($model->load(Yii::$app->request->post()) && 
            $orderDetail->load(Yii::$app->request->post()) && 
            $orderPayment->load(Yii::$app->request->post())) {
            
            // Tương tự như actionCreate nhưng có thêm logic cập nhật
            // ...
            
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'orderDetail' => $orderDetail,
            'orderPayment' => $orderPayment,
            'orderItems' => $orderItems,
        ]);
    }

    public function actionDelete($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            // Xóa các mục con trước
            OrderItem::deleteAll(['order_id' => $id]);
            OrderDetail::deleteAll(['order_id' => $id]);
            OrderPayment::deleteAll(['order_id' => $id]);
            
            // Xóa đơn hàng chính
            $this->findModel($id)->delete();
            
            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Đơn hàng đã được xóa thành công');
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Không thể xóa đơn hàng: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    protected function generateOrderCode()
    {
        $prefix = 'DH';
        $date = date('ymd');
        $lastOrder = Order::find()->orderBy(['id' => SORT_DESC])->one();
        $lastId = $lastOrder ? $lastOrder->id + 1 : 1;
        return $prefix . $date . sprintf('%04d', $lastId);
    }
    
    public function actionGetProductInfo($id)
    {
        $product = Product::findOne($id);
        if ($product) {
            return Json::encode([
                'id' => $product->id,
                'code' => $product->code,
                'name' => $product->name,
                'barcode' => $product->barcode,
                'brand' => $product->brand,
                'unit' => $product->primaryUnit ? $product->primaryUnit->name : '',
                'price' => $product->selling_price,
                'stock' => $product->current_stock,
            ]);
        }
        return Json::encode(['error' => 'Sản phẩm không tồn tại']);
    }
    
    public function actionGetCustomerInfo($id)
    {
        $customer = Customer::findOne($id);
        if ($customer) {
            return Json::encode([
                'id' => $customer->id,
                'code' => $customer->code,
                'name' => $customer->full_name,
                'phone' => $customer->phone,
                'address' => $customer->address,
                'points' => $customer->current_points,
                'debt' => $customer->current_debt,
            ]);
        }
        return Json::encode(['error' => 'Khách hàng không tồn tại']);
    }
}