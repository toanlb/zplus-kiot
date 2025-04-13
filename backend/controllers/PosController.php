<?php

namespace backend\controllers;

use Yii;
use common\models\Order;
use common\models\OrderItem;
use common\models\OrderDetail;
use common\models\OrderPayment;
use common\models\Product;
use common\models\Customer;
use common\models\ProductCategory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\data\ActiveDataProvider;

class PosController extends Controller
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
        $categories = ProductCategory::find()->where(['parent_id' => null])->all();
        
        $productsQuery = Product::find()
            ->where(['is_active' => 1])
            ->andWhere(['is_direct_sale' => 1])
            ->orderBy(['name' => SORT_ASC]);
            
        if ($category_id = Yii::$app->request->get('category_id')) {
            $productsQuery->andWhere(['category_id' => $category_id]);
        }
        
        $products = $productsQuery->all();
        
        $recentOrders = Order::find()
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(10)
            ->all();
            
        return $this->render('index', [
            'categories' => $categories,
            'products' => $products,
            'recentOrders' => $recentOrders,
        ]);
    }
    
    public function actionGetProducts($category_id = null, $keyword = null)
    {
        $query = Product::find()
            ->where(['is_active' => 1])
            ->andWhere(['is_direct_sale' => 1]);
            
        if ($category_id) {
            $query->andWhere(['category_id' => $category_id]);
        }
        
        if ($keyword) {
            $query->andWhere(['or',
                ['like', 'name', $keyword],
                ['like', 'code', $keyword],
                ['like', 'barcode', $keyword]
            ]);
        }
        
        $products = $query->all();
        
        $result = [];
        foreach ($products as $product) {
            $result[] = [
                'id' => $product->id,
                'code' => $product->code,
                'name' => $product->name,
                'price' => $product->selling_price,
                'stock' => $product->current_stock,
                'unit' => $product->primaryUnit ? $product->primaryUnit->name : '',
                'image' => $product->getImageUrl(),
            ];
        }
        
        return Json::encode($result);
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
                'price' => $product->selling_price,
                'stock' => $product->current_stock,
                'unit' => $product->primaryUnit ? $product->primaryUnit->name : '',
                'image' => $product->getImageUrl(),
            ]);
        }
        return Json::encode(['error' => 'Sản phẩm không tồn tại']);
    }
    
    public function actionSearchCustomer($keyword)
    {
        $customers = Customer::find()
            ->where(['or',
                ['like', 'full_name', $keyword],
                ['like', 'phone', $keyword],
                ['like', 'code', $keyword]
            ])
            ->limit(10)
            ->all();
            
        $result = [];
        foreach ($customers as $customer) {
            $result[] = [
                'id' => $customer->id,
                'code' => $customer->code,
                'name' => $customer->full_name,
                'phone' => $customer->phone,
                'points' => $customer->current_points,
                'group' => $customer->customer_group,
            ];
        }
        
        return Json::encode($result);
    }
    
    public function actionCreateOrder()
    {
        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request->post();
            
            // Bắt đầu transaction
            $transaction = Yii::$app->db->beginTransaction();
            
            try {
                // Tạo đơn hàng mới
                $order = new Order();
                $order->code = $this->generateOrderCode();
                $order->customer_id = $request['customer_id'] ?? null;
                $order->created_at = time();
                $order->total_amount = $request['total_amount'];
                $order->discount_amount = $request['discount_amount'];
                $order->final_amount = $request['final_amount'];
                $order->paid_amount = $request['paid_amount'];
                
                if (!$order->save()) {
                    throw new \Exception('Không thể lưu đơn hàng');
                }
                
                // Tạo chi tiết đơn hàng
                $orderDetail = new OrderDetail();
                $orderDetail->order_id = $order->id;
                $orderDetail->branch = $request['branch'] ?? null;
                $orderDetail->salesperson = $request['salesperson'] ?? Yii::$app->user->identity->username;
                $orderDetail->sales_channel = 'POS';
                $orderDetail->creator = Yii::$app->user->identity->username;
                $orderDetail->status = 'Hoàn thành';
                $orderDetail->delivery_status = 'Đã giao';
                $orderDetail->order_note = $request['note'] ?? null;
                
                if (!$orderDetail->save()) {
                    throw new \Exception('Không thể lưu chi tiết đơn hàng');
                }
                
                // Tạo thông tin thanh toán
                $orderPayment = new OrderPayment();
                $orderPayment->order_id = $order->id;
                $orderPayment->cash_amount = $request['cash_amount'] ?? 0;
                $orderPayment->card_amount = $request['card_amount'] ?? 0;
                $orderPayment->bank_transfer_amount = $request['bank_transfer_amount'] ?? 0;
                $orderPayment->ewallet_amount = $request['ewallet_amount'] ?? 0;
                $orderPayment->points_used = $request['points_used'] ?? 0;
                $orderPayment->voucher_code = $request['voucher_code'] ?? null;
                $orderPayment->voucher_amount = $request['voucher_amount'] ?? 0;
                
                if (!$orderPayment->save()) {
                    throw new \Exception('Không thể lưu thông tin thanh toán');
                }
                
                // Lưu các sản phẩm trong đơn hàng
                if (isset($request['products']) && is_array($request['products'])) {
                    foreach ($request['products'] as $productData) {
                        $product = Product::findOne($productData['id']);
                        if (!$product) {
                            continue;
                        }
                        
                        $orderItem = new OrderItem();
                        $orderItem->order_id = $order->id;
                        $orderItem->product_id = $product->id;
                        $orderItem->product_code = $product->code;
                        $orderItem->product_name = $product->name;
                        $orderItem->barcode = $product->barcode;
                        $orderItem->brand = $product->brand;
                        $orderItem->unit = $product->primaryUnit ? $product->primaryUnit->name : '';
                        $orderItem->quantity = $productData['quantity'];
                        $orderItem->unit_price = $productData['price'];
                        $orderItem->discount_percentage = $productData['discount'] ?? 0;
                        $orderItem->discount_amount = $productData['discount_amount'] ?? 0;
                        $orderItem->final_price = $productData['final_price'];
                        
                        if (!$orderItem->save()) {
                            throw new \Exception('Không thể lưu sản phẩm trong đơn hàng');
                        }
                        
                        // Cập nhật tồn kho
                        $product->current_stock -= $orderItem->quantity;
                        if (!$product->save()) {
                            throw new \Exception('Không thể cập nhật tồn kho');
                        }
                    }
                }
                
                // Cập nhật thông tin khách hàng nếu có
                if ($order->customer_id) {
                    $customer = Customer::findOne($order->customer_id);
                    if ($customer) {
                        $customer->total_sales += $order->final_amount;
                        $customer->last_transaction_date = time();
                        
                        // Cộng điểm tích lũy cho khách hàng (giả sử cứ 100.000đ được 1 điểm)
                        $earnedPoints = floor($order->final_amount / 100000);
                        
                        // Trừ điểm nếu khách hàng dùng điểm để thanh toán
                        if ($orderPayment->points_used > 0) {
                            $customer->current_points -= $orderPayment->points_used;
                            if ($customer->current_points < 0) {
                                $customer->current_points = 0;
                            }
                        }
                        
                        // Cộng điểm mới
                        $customer->current_points += $earnedPoints;
                        $customer->total_points += $earnedPoints;
                        
                        if (!$customer->save()) {
                            throw new \Exception('Không thể cập nhật thông tin khách hàng');
                        }
                    }
                }
                
                $transaction->commit();
                
                return Json::encode([
                    'success' => true,
                    'order_id' => $order->id,
                    'order_code' => $order->code,
                    'message' => 'Đơn hàng đã được tạo thành công'
                ]);
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                return Json::encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
        }
        
        return Json::encode([
            'success' => false,
            'message' => 'Yêu cầu không hợp lệ'
        ]);
    }
    
    public function actionPrintReceipt($id)
    {
        $order = Order::findOne($id);
        if (!$order) {
            throw new NotFoundHttpException('Đơn hàng không tồn tại.');
        }
        
        $orderItems = OrderItem::find()->where(['order_id' => $id])->all();
        $orderDetail = OrderDetail::findOne(['order_id' => $id]);
        $orderPayment = OrderPayment::findOne(['order_id' => $id]);
        
        return $this->renderPartial('receipt', [
            'order' => $order,
            'orderItems' => $orderItems,
            'orderDetail' => $orderDetail,
            'orderPayment' => $orderPayment,
        ]);
    }
    
    protected function generateOrderCode()
    {
        $prefix = 'POS';
        $date = date('ymd');
        $lastOrder = Order::find()->orderBy(['id' => SORT_DESC])->one();
        $lastId = $lastOrder ? $lastOrder->id + 1 : 1;
        return $prefix . $date . sprintf('%04d', $lastId);
    }
}