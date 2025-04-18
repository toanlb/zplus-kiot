<?php
namespace pos\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use pos\models\PosSession;
use common\models\Product;
use common\models\ProductCategory;
use common\models\Customer;
use common\models\Order;
use common\models\OrderItem;
use common\models\OrderPayment;
use common\models\TransactionHistory;
use yii\helpers\Url;

/**
 * POS controller
 */
class PosController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'get-products', 'get-product-details', 
                                     'add-to-cart', 'update-cart', 'remove-from-cart', 
                                     'get-cart', 'clear-cart', 'search-customers', 
                                     'add-customer', 'apply-discount', 'hold-order',
                                     'get-payment-url', 'open-session', 'get-session-info',
                                     'payment', 'complete-order', 'close-session', 
                                     'get-categories', 'get-held-orders', 'resume-order','select-customer',],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return self::checkPosAccess();
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'add-to-cart' => ['post'],
                    'update-cart' => ['post'],
                    'remove-from-cart' => ['post'],
                    'clear-cart' => ['post'],
                    'add-customer' => ['post'],
                    'apply-discount' => ['post'],
                    'hold-order' => ['post'],
                    'open-session' => ['post'],
                    'complete-order' => ['post'],
                    'close-session' => ['post'],
                    'resume-order' => ['post'],
                    'select-customer' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        // Nếu không có phiên POS đang mở và không phải là hành động mở phiên
        if ($action->id !== 'open-session' && $action->id !== 'index' && !PosSession::hasActiveSession()) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'success' => false,
                    'message' => 'Không có phiên làm việc đang mở. Vui lòng mở phiên mới.',
                    'needSession' => true,
                ];
            } else {
                Yii::$app->session->setFlash('error', 'Vui lòng mở phiên làm việc trước khi sử dụng POS.');
                return $this->redirect(['index']);
            }
        }
        
        return parent::beforeAction($action);
    }

    /**
     * Trang chính của POS
     */
    public function actionIndex()
    {
        $this->layout = 'pos';
        
        // Kiểm tra có phiên POS đang mở không
        $hasActiveSession = PosSession::hasActiveSession();
        $activeSession = $hasActiveSession ? PosSession::getActiveSession() : null;
        
        // Lấy danh mục sản phẩm
        $categories = ProductCategory::find()
            ->where(['status' => ProductCategory::STATUS_ACTIVE])
            ->all();
        
        // Lấy sản phẩm bán chạy (top 12)
        $topProducts = Product::find()
            ->where(['is_active' => Product::STATUS_ACTIVE])
            ->limit(12)
            ->all();
        
        return $this->render('index', [
            'hasActiveSession' => $hasActiveSession,
            'activeSession' => $activeSession,
            'categories' => $categories,
            'topProducts' => $topProducts,
        ]);
    }
    
    /**
     * Lấy danh sách sản phẩm (AJAX)
     */
    public function actionGetProducts()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $categoryId = Yii::$app->request->get('categoryId');
        $search = Yii::$app->request->get('search');
        $page = Yii::$app->request->get('page', 1);
        $perPage = 24; // Số sản phẩm mỗi trang
        
        $query = Product::find()
            ->where(['is_active' => Product::STATUS_ACTIVE]);
        
        if ($categoryId) {
            $query->andWhere(['category_id' => $categoryId]);
        }
        
        if ($search) {
            $query->andWhere(['or', 
                ['like', 'name', $search],
                ['like', 'code', $search],
                ['like', 'barcode', $search]
            ]);
        }
        
        $totalCount = $query->count();
        
        $products = $query->orderBy(['name' => SORT_ASC])
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->all();
        
        $result = [];
        foreach ($products as $product) {
            $result[] = [
                'id' => $product->id,
                'name' => $product->name,
                'code' => $product->code,
                'price' => $product->selling_price,
                'discount_price' => $product->selling_price,
                'unit' => $product->primaryUnit ? $product->primaryUnit->name : '',
                'image_url' => $this->getProductImageUrl($product),
                'in_stock' => $product->current_stock,
            ];
        }
        
        return [
            'success' => true,
            'products' => $result,
            'totalCount' => $totalCount,
            'pages' => ceil($totalCount / $perPage),
            'currentPage' => $page,
        ];
    }

        /**
     * Lưu khách hàng đã chọn vào session
     * @return \yii\web\Response
     */
    public function actionSelectCustomer()
    {
        
        $customerId = Yii::$app->request->post('customerId');
        
        if ($customerId) {
            // Tìm thông tin khách hàng từ database
            $customer = Customer::findOne($customerId);
            
            if ($customer) {
                Yii::$app->session->set('pos_customer_id', $customer->id);
                // Lưu thông tin khách hàng vào session

                return $this->asJson([
                    'success' => true,
                    'message' => 'Đã chọn khách hàng thành công'
                ]);
            }
        }
        
        return $this->asJson([
            'success' => false,
            'message' => 'Không thể chọn khách hàng'
        ]);
    }

    public function actionGetPaymentUrl()
    {
        // Lấy customer ID từ request hoặc từ session
        $customerId = Yii::$app->request->get('customerId');
        
        if (!$customerId) {
            $customer = Yii::$app->session->get('pos_selected_customer');
            $customerId = $customer ? $customer['id'] : null;
        }
        
        // Tạo URL thanh toán kèm theo thông tin khách hàng
        $url = Url::to(['pos/payment', 'customerId' => $customerId]);
        
        return $this->asJson([
            'success' => true,
            'url' => $url
        ]);
    }

    public function actionSaveOrderNote()
    {
        $note = Yii::$app->request->post('note');
        Yii::$app->session->set('pos_order_note', $note);
        
        return $this->asJson([
            'success' => true,
            'message' => 'Đã lưu ghi chú'
        ]);
    }
    
        
    /**
     * Lấy thông tin chi tiết sản phẩm
     */
    public function actionGetProductDetails($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $product = Product::findOne($id);
        if (!$product) {
            return [
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm'
            ];
        }
        
        return [
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'code' => $product->code,
                'barcode' => $product->barcode,
                'price' => $product->selling_price,
                'discount_price' => $product->selling_price,
                'unit' => $product->primaryUnit ? $product->primaryUnit->name : '',
                'image_url' => $this->getProductImageUrl($product),
                'description' => $product->description,
                'in_stock' => $product->current_stock,
                'has_variants' => false,
                'variants' => [],
                'category' => $product->category ? $product->category->name : '',
            ]
        ];
    }
    
    /**
     * Xử lý thêm sản phẩm vào giỏ hàng
     */
    public function actionAddToCart()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $productId = Yii::$app->request->post('productId');
        $quantity = Yii::$app->request->post('quantity', 1);
        $variantId = Yii::$app->request->post('variantId');
        
        // Lấy thông tin sản phẩm
        $product = Product::findOne($productId);
        if (!$product) {
            return [
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm'
            ];
        }
    
        // Check stock
        if ($product->current_stock < $quantity) {
            return [
                'success' => false,
                'message' => 'Sản phẩm không đủ số lượng trong kho'
            ];
        }
        
        // Lấy giỏ hàng từ session
        $cart = Yii::$app->session->get('pos_cart', []);
        
        // Tạo key cho sản phẩm (kết hợp id sản phẩm và id biến thể nếu có)
        $itemKey = $productId . ($variantId ? '_' . $variantId : '');
        
        // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
        if (isset($cart[$itemKey])) {
            // Cập nhật số lượng
            $cart[$itemKey]['quantity'] += $quantity;
        } else {
            $cart[$itemKey] = [
                'product_id' => $productId,
                'name' => $product->name,
                'code' => $product->code,
                'price' => $product->selling_price,
                'original_price' => $product->selling_price,
                'quantity' => $quantity,
                'unit' => $product->primaryUnit ? $product->primaryUnit->name : '',
                'image_url' => $this->getProductImageUrl($product),
                'discount' => 0,
                'tax' => 0,
            ];
        }
        
        // Lưu giỏ hàng vào session
        Yii::$app->session->set('pos_cart', $cart);
        
        return $this->calculateCartTotals($cart);
    }
    
    /**
     * Cập nhật giỏ hàng
     */
    public function actionUpdateCart()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $itemKey = Yii::$app->request->post('itemKey');
        $quantity = Yii::$app->request->post('quantity');
        $setQuantity = Yii::$app->request->post('setQuantity', 0);
        
        // Lấy giỏ hàng từ session
        $cart = Yii::$app->session->get('pos_cart', []);
        
        if (!isset($cart[$itemKey])) {
            return [
                'success' => false,
                'message' => 'Sản phẩm không tồn tại trong giỏ hàng'
            ];
        }
        
        if ($setQuantity == 1) {
            // Đặt số lượng cụ thể
            if ($quantity <= 0) {
                unset($cart[$itemKey]);
            } else {
                // Kiểm tra tồn kho
                $productId = $cart[$itemKey]['product_id'];
                $product = Product::findOne($productId);
                
                if ($product && $product->current_stock < $quantity) {
                    return [
                        'success' => false,
                        'message' => 'Sản phẩm không đủ số lượng trong kho'
                    ];
                }
                
                // Cập nhật số lượng
                $cart[$itemKey]['quantity'] = $quantity;
            }
        } else {
            // Tăng/giảm số lượng
            $newQuantity = $cart[$itemKey]['quantity'] + $quantity;
            
            if ($newQuantity <= 0) {
                unset($cart[$itemKey]);
            } else {
                // Kiểm tra tồn kho
                $productId = $cart[$itemKey]['product_id'];
                $product = Product::findOne($productId);
                
                if ($product && $product->current_stock < $newQuantity) {
                    return [
                        'success' => false,
                        'message' => 'Sản phẩm không đủ số lượng trong kho'
                    ];
                }
                
                // Cập nhật số lượng
                $cart[$itemKey]['quantity'] = $newQuantity;
            }
        }
        
        // Lưu giỏ hàng vào session
        Yii::$app->session->set('pos_cart', $cart);
        
        return $this->calculateCartTotals($cart);
    }
    
    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function actionRemoveFromCart()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $itemKey = Yii::$app->request->post('itemKey');
        
        // Lấy giỏ hàng từ session
        $cart = Yii::$app->session->get('pos_cart', []);
        
        if (!isset($cart[$itemKey])) {
            return [
                'success' => false,
                'message' => 'Sản phẩm không tồn tại trong giỏ hàng'
            ];
        }
        
        // Xóa sản phẩm khỏi giỏ hàng
        unset($cart[$itemKey]);
        
        // Lưu giỏ hàng vào session
        Yii::$app->session->set('pos_cart', $cart);
        
        return $this->calculateCartTotals($cart);
    }
    
    
    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function actionClearCart()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        // Xóa giỏ hàng khỏi session
        Yii::$app->session->remove('pos_cart');
        Yii::$app->session->remove('pos_customer_id');
        Yii::$app->session->remove('pos_order_note');
        Yii::$app->session->remove('pos_discount');
        
        return [
            'success' => true,
            'message' => 'Đã xóa toàn bộ giỏ hàng',
        ];
    }
    
    /**
     * Tìm kiếm khách hàng
     */
    public function actionSearchCustomers()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $search = Yii::$app->request->get('search');
        
        $query = Customer::find()
            ->where(['status' => Customer::STATUS_ACTIVE]);
        
        if ($search) {
            $query->andWhere(['or', 
                ['like', 'full_name', $search],
                ['like', 'phone', $search],
                ['like', 'email', $search]
            ]);
        }
        
        $customers = $query->orderBy(['full_name' => SORT_ASC])
            ->limit(10)
            ->all();
        
        $result = [];
        foreach ($customers as $customer) {
            $result[] = [
                'id' => $customer->id,
                'name' => $customer->full_name,
                'phone' => $customer->phone,
                'email' => $customer->email,
                'address' => $customer->address,
                'points' => $customer->total_points,
                'debt' => $customer->current_debt,
            ];
        }
        
        return [
            'success' => true,
            'customers' => $result,
        ];
    }
    
    /**
     * Thêm khách hàng mới
     */
    public function actionAddCustomer()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $name = Yii::$app->request->post('name');
        $phone = Yii::$app->request->post('phone');
        $email = Yii::$app->request->post('email');
        $address = Yii::$app->request->post('address');
        
        // Kiểm tra số điện thoại đã tồn tại chưa
        $existingCustomer = Customer::findOne(['phone' => $phone]);
        if ($existingCustomer) {
            return [
                'success' => false,
                'message' => 'Số điện thoại đã tồn tại trong hệ thống'
            ];
        }
        
        // Tạo khách hàng mới
        $customer = new Customer();
        $customer->full_name = $name;
        $customer->phone = $phone;
        $customer->email = $email;
        $customer->address = $address;
        $customer->status = Customer::STATUS_ACTIVE;
        $customer->created_at = time();
        $customer->beforeSave(true);
        
        if ($customer->save()) {
            // Lưu id khách hàng vào session
            Yii::$app->session->set('pos_customer_id', $customer->id);
            
            return [
                'success' => true,
                'message' => 'Đã thêm khách hàng mới',
                'customer' => [
                    'id' => $customer->id,
                    'name' => $customer->full_name,
                    'phone' => $customer->phone,
                    'email' => $customer->email,
                    'address' => $customer->address,
                ]
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Không thể thêm khách hàng: ' . implode(', ', $customer->getErrorSummary(true))
            ];
        }
    }
    
    /**
     * Trang thanh toán
     */
    public function actionPayment()
    {
        $this->layout = 'pos';
        
        // Lấy giỏ hàng từ session
        $cart = Yii::$app->session->get('pos_cart', []);
        
        if (empty($cart)) {
            Yii::$app->session->setFlash('error', 'Giỏ hàng trống, không thể thanh toán');
            return $this->redirect(['index']);
        }
        
        // Tính toán tổng giỏ hàng
        $totalQuantity = 0;
        $subtotal = 0;
        $discount = 0;
        $tax = 0;
        
        foreach ($cart as $item) {
            $totalQuantity += $item['quantity'];
            $itemTotal = $item['price'] * $item['quantity'];
            $subtotal += $itemTotal;
            $discount += $item['discount'] * $item['quantity'];
            $tax += $item['tax'] * $item['quantity'];
        }
        
        // Áp dụng giảm giá tổng nếu có
        $discountInfo = Yii::$app->session->get('pos_discount');
        if ($discountInfo) {
            if ($discountInfo['type'] == 'percent') {
                $discount += $subtotal * ($discountInfo['value'] / 100);
            } else {
                $discount += $discountInfo['value'];
            }
            
            // Giới hạn giảm giá không vượt quá tổng tiền
            if ($discount > $subtotal) {
                $discount = $subtotal;
            }
        }
        
        $grandTotal = $subtotal - $discount + $tax;
        
        // Lấy thông tin khách hàng đã chọn nếu có
        $customerId = Yii::$app->session->get('pos_customer_id');
        $customer = null;
        
        if ($customerId) {
            $customer = Customer::findOne($customerId);
        }
        // Lấy phương thức thanh toán
        $paymentMethods = [
            'cash' => 'Tiền mặt',
            'bank_transfer' => 'Chuyển khoản',
            'card' => 'Thẻ',
            'momo' => 'Ví MoMo',
            'vnpay' => 'VNPay',
            'credit' => 'Công nợ',
        ];
        
        return $this->render('payment', [
            'cart' => $cart,
            'totalQuantity' => $totalQuantity,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'grandTotal' => $grandTotal,
            'customer' => $customer,
            'paymentMethods' => $paymentMethods,
        ]);
    }

     /**
     * Lấy thông tin giỏ hàng
     */
    public function actionGetCart()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        // Lấy giỏ hàng từ session
        $cart = Yii::$app->session->get('pos_cart', []);
        
        $response = $this->calculateCartTotals($cart);
        
        // Lấy thông tin khách hàng đã chọn nếu có
        $customerId = Yii::$app->session->get('pos_customer_id');
        $customerInfo = null;
        
        if ($customerId) {
            $customer = Customer::findOne($customerId);
            if ($customer) {
                $customerInfo = [
                    'id' => $customer->id,
                    'name' => $customer->full_name,
                    'phone' => $customer->phone,
                    'email' => $customer->email,
                    'points' => $customer->total_points,
                ];
                $response['customer'] = $customerInfo;
            }
        }
        
        return $response;
    }
    
    /**
     * Áp dụng giảm giá
     */
    public function actionApplyDiscount()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $type = Yii::$app->request->post('type'); // percent hoặc amount
        $value = Yii::$app->request->post('value');
        
        if (!in_array($type, ['percent', 'amount']) || $value <= 0) {
            return [
                'success' => false,
                'message' => 'Thông tin giảm giá không hợp lệ'
            ];
        }
        
        // Lưu thông tin giảm giá vào session
        Yii::$app->session->set('pos_discount', [
            'type' => $type,
            'value' => $value,
        ]);
        
        // Lấy giỏ hàng từ session và tính toán lại tổng
        $cart = Yii::$app->session->get('pos_cart', []);
        return $this->calculateCartTotals($cart);
    }
    
    /**
     * Hoàn thành đơn hàng
     */
    public function actionCompleteOrder()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $paymentMethod = Yii::$app->request->post('paymentMethod');
        $amountTendered = Yii::$app->request->post('amountTendered');
        $note = Yii::$app->request->post('note');
        
        // Lấy giỏ hàng từ session
        $cart = Yii::$app->session->get('pos_cart', []);
        
        if (empty($cart)) {
            return [
                'success' => false,
                'message' => 'Giỏ hàng trống, không thể thanh toán'
            ];
        }
        
        // Lấy thông tin khách hàng
        $customerId = Yii::$app->session->get('pos_customer_id');
        
        // Lấy thông tin giảm giá
        $discount = Yii::$app->session->get('pos_discount');
        
        // Tính toán tổng giỏ hàng
        $subtotal = 0;
        $tax = 0;
        
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            $tax += $item['tax'] * $item['quantity'];
        }
        
        // Tính giảm giá
        $discountAmount = 0;
        if ($discount) {
            if ($discount['type'] == 'percent') {
                $discountAmount = $subtotal * ($discount['value'] / 100);
            } else {
                $discountAmount = $discount['value'];
            }
            
            // Giới hạn giảm giá không vượt quá tổng tiền
            if ($discountAmount > $subtotal) {
                $discountAmount = $subtotal;
            }
        }
        
        $grandTotal = $subtotal - $discountAmount + $tax;
        
        // Bắt đầu transaction
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            // Tạo đơn hàng mới
            $order = new Order();
            $order->code = 'HD' . time() . rand(1000, 9999);
            $order->customer_id = $customerId;
            $order->total_amount = $subtotal;
            $order->discount_amount = $discountAmount;
            $order->final_amount = $grandTotal;
            $order->created_at = time();
            $order->pos_session_id = PosSession::getActiveSession()->id;
            if (!$order->save()) {
                throw new \Exception('Không thể tạo đơn hàng: ' . implode(', ', $order->getErrorSummary(true)));
            }
            
            // Thêm chi tiết đơn hàng
            foreach ($cart as $itemKey => $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item['product_id'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->unit_price = $item['price'];
                $orderItem->discount_amount = $item['discount'];
                $orderItem->final_price = $item['price'] * $item['quantity'] - ($item['discount'] * $item['quantity']) + ($item['tax'] * $item['quantity']);

                 // Cập nhật tồn kho
                 $product = Product::findOne($item['product_id']);
                 $orderItem->product_code = $product->code;
                 $orderItem->product_name = $product->name;
                 if ($product) {
                     $product->current_stock -= $item['quantity'];
                     
                     if (!$product->save()) {
                         throw new \Exception('Không thể cập nhật tồn kho: ' . implode(', ', $product->getErrorSummary(true)));
                     }
                 }
                
                if (!$orderItem->save()) {
                    throw new \Exception('Không thể thêm chi tiết đơn hàng: ' . implode(', ', $orderItem->getErrorSummary(true)));
                }

               
            }
    
            // Thêm thanh toán
            $payment = new OrderPayment();
            $payment->order_id = $order->id;
            switch ($paymentMethod) {
                case 'cash':
                    $payment->cash_amount = $grandTotal;
                    break;
                case 'bank_transfer':
                    $payment->bank_transfer_amount = $grandTotal;
                    break;
                case 'card':
                    $payment->ewallet_amount= $grandTotal;
                    break;
                case 'momo':
                    $payment->card_amount = $grandTotal;
                    break;
                default:
                    throw new \Exception('Phương thức thanh toán không hợp lệ');
            }
        
            
            if (!$payment->save()) {
                throw new \Exception('Không thể thêm thanh toán: ' . implode(', ', $payment->getErrorSummary(true)));
            }

            try {
                // Ghi lại lịch sử giao dịch
                $transactionHistory = new \common\models\TransactionHistory();
                $transactionHistory->transaction_code = 'TRX' . time() . rand(1000, 9999);
                $transactionHistory->order_id = $order->id;
                $transactionHistory->user_id = Yii::$app->user->id;
                $transactionHistory->pos_session_id = $order->pos_session_id;
                $transactionHistory->customer_id = $order->customer_id;
                $transactionHistory->total_amount = $order->total_amount;
                $transactionHistory->discount_amount = $order->discount;
                $transactionHistory->final_amount = $order->final_amount;
                
                // Thiết lập thông tin thanh toán
                if ($payment->payment_method == 'cash') {
                    $transactionHistory->cash_amount = $payment->amount;
                } elseif ($payment->payment_method == 'card') {
                    $transactionHistory->card_amount = $payment->amount;
                } elseif ($payment->payment_method == 'bank_transfer') {
                    $transactionHistory->bank_transfer_amount = $payment->amount;
                } elseif (in_array($payment->payment_method, ['momo', 'vnpay'])) {
                    $transactionHistory->ewallet_amount = $payment->amount;
                }
                
                $transactionHistory->paid_amount = $payment->amount;
                
                // Thiết lập trạng thái thanh toán
                if ($payment->payment_method == 'credit') {
                    $transactionHistory->payment_status = \common\models\TransactionHistory::STATUS_PENDING;
                    $transactionHistory->transaction_type = \common\models\TransactionHistory::TYPE_CREDIT;
                } else {
                    $transactionHistory->payment_status = \common\models\TransactionHistory::STATUS_PAID;
                    $transactionHistory->transaction_type = \common\models\TransactionHistory::TYPE_SALE;
                }
                
                $transactionHistory->notes = $note;
                $transactionHistory->save();
                
            } catch (\Exception $e) {
                // Ghi log lỗi nhưng không cần rollback transaction
                Yii::error('Không thể lưu lịch sử giao dịch: ' . $e->getMessage());
            }
            
            // Commit transaction
            $transaction->commit();
            
            // Xóa giỏ hàng, khách hàng và giảm giá khỏi session
            Yii::$app->session->remove('pos_cart');
            Yii::$app->session->remove('pos_customer_id');
            Yii::$app->session->remove('pos_discount');
            Yii::$app->session->remove('pos_order_note');
            
            return [
                'success' => true,
                'message' => 'Đã hoàn thành đơn hàng',
                'order' => [
                    'id' => $order->id,
                    'code' => $this->getOrderCode($order),
                    'total' => $order->total_amount,
                    'change' => ($paymentMethod == 'cash' && $amountTendered > 0) ? ($amountTendered - $order->total_amount) : 0,
                ]
            ];
            
        } catch (\Exception $e) {
            // Rollback transaction nếu có lỗi
            $transaction->rollBack();
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Lưu đơn hàng tạm
     */
    public function actionHoldOrder()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $note = Yii::$app->request->post('note', '');
        
        // Lấy giỏ hàng từ session
        $cart = Yii::$app->session->get('pos_cart', []);
        
        if (empty($cart)) {
            return [
                'success' => false,
                'message' => 'Giỏ hàng trống, không thể lưu tạm'
            ];
        }
        
        // Lấy thông tin khách hàng
        $customerId = Yii::$app->session->get('pos_customer_id');
        
        // Lưu đơn hàng tạm vào cơ sở dữ liệu
        $order = new Order();
        $order->customer_id = $customerId;
        $order->status = Order::STATUS_PENDING;
        $order->note = $note;
        $order->created_by = Yii::$app->user->id;
        $order->created_at = time();
        $order->pos_session_id = PosSession::getActiveSession()->id;
        
        // Tính toán tổng giỏ hàng
        $cartTotals = $this->calculateCartTotals($cart);
        
        $order->subtotal = $cartTotals['subtotal'];
        $order->discount = $cartTotals['discount'];
        $order->tax = $cartTotals['tax'];
        $order->total = $cartTotals['grandTotal'];
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            if (!$order->save()) {
                throw new \Exception('Không thể tạo đơn hàng tạm: ' . implode(', ', $order->getErrorSummary(true)));
            }
            
            // Thêm chi tiết đơn hàng
            foreach ($cart as $itemKey => $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item['product_id'];
                $orderItem->variant_id = isset($item['variant_id']) ? $item['variant_id'] : null;
                $orderItem->name = $item['name'];
                $orderItem->code = $item['code'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->price = $item['price'];
                $orderItem->original_price = $item['original_price'];
                $orderItem->discount = $item['discount'];
                $orderItem->tax = $item['tax'];
                $orderItem->total = $item['price'] * $item['quantity'] - ($item['discount'] * $item['quantity']) + ($item['tax'] * $item['quantity']);
                
                if (!$orderItem->save()) {
                    throw new \Exception('Không thể thêm chi tiết đơn hàng: ' . implode(', ', $orderItem->getErrorSummary(true)));
                }
            }
            
            $transaction->commit();
            
            // Xóa giỏ hàng khỏi session
            Yii::$app->session->remove('pos_cart');
            Yii::$app->session->remove('pos_customer_id');
            Yii::$app->session->remove('pos_discount');
            Yii::$app->session->remove('pos_order_note');
            
            return [
                'success' => true,
                'message' => 'Đã lưu đơn hàng tạm',
                'order' => [
                    'id' => $order->id,
                    'code' => $this->getOrderCode($order),
                ]
            ];
            
        } catch (\Exception $e) {
            $transaction->rollBack();
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    
    /**
     * Mở phiên làm việc POS
     */
    public function actionOpenSession()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $cashAmount = Yii::$app->request->post('cashAmount', 0);
        $note = Yii::$app->request->post('note', '');
        
        // Kiểm tra có phiên đang mở không
        if (PosSession::hasActiveSession()) {
            return [
                'success' => false,
                'message' => 'Đã có phiên POS đang mở, vui lòng đóng phiên hiện tại trước khi mở phiên mới'
            ];
        }
        
        // Tạo phiên POS mới
        $session = new PosSession();
        $session->user_id = Yii::$app->user->id;
        $session->start_amount = $cashAmount;
        $session->current_amount = $cashAmount;
        $session->start_time = time();
        $session->note = $note;
        $session->status = PosSession::STATUS_ACTIVE;
        
        if ($session->save()) {
            return [
                'success' => true,
                'message' => 'Đã mở phiên làm việc mới',
                'session' => [
                    'id' => $session->id,
                    'start_time' => Yii::$app->formatter->asDatetime($session->start_time),
                    'start_amount' => $session->start_amount,
                    'current_amount' => $session->current_amount,
                ]
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Không thể mở phiên làm việc: ' . implode(', ', $session->getErrorSummary(true))
            ];
        }
    }
    
    /**
     * Đóng phiên làm việc POS
     */
    public function actionCloseSession()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        try {
            if (!PosSession::hasActiveSession()) {
                return [
                    'success' => false,
                    'message' => 'Không có phiên POS đang mở',
                ];
            }
            
            $session = PosSession::getActiveSession();
            $cashAmount = Yii::$app->request->post('cashAmount');
            $note = Yii::$app->request->post('note', '');
            
            if (empty($cashAmount) || !is_numeric($cashAmount)) {
                return [
                    'success' => false,
                    'message' => 'Vui lòng nhập số tiền cuối ca hợp lệ',
                ];
            }
            
            // Cập nhật thống kê trước khi đóng ca
            try {
                $session->updateStats();
            } catch (\Exception $e) {
                Yii::error('Error updating stats before closing: ' . $e->getMessage());
                // Tiếp tục mặc dù có lỗi khi cập nhật thống kê
            }
            
            // Đóng phiên
            $session->end_time = time();
            $session->end_amount = $cashAmount;
            $session->close_note = $note;
            $session->expected_amount = $session->start_amount + $session->cash_sales;
            $session->difference = $cashAmount - $session->expected_amount;
            $session->status = PosSession::STATUS_CLOSED;
            
            if ($session->save()) {
                return [
                    'success' => true,
                    'message' => 'Đã đóng ca làm việc thành công',
                    'session' => [
                        'id' => $session->id,
                        'startTime' => Yii::$app->formatter->asDatetime($session->start_time),
                        'endTime' => Yii::$app->formatter->asDatetime($session->end_time),
                        'cashSales' => PosSession::formatCurrency($session->cash_sales),
                        'cardSales' => PosSession::formatCurrency($session->card_sales),
                        'totalSales' => PosSession::formatCurrency($session->total_sales),
                        'difference' => PosSession::formatCurrency($session->difference),
                    ]
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi đóng ca làm việc',
                    'errors' => $session->errors,
                ];
            }
        } catch (\Exception $e) {
            Yii::error('Error in closeSession: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi đóng ca làm việc',
            ];
        }
    }
    
    /**
     * Lấy thông tin phiên làm việc
     */
    public function actionGetSessionInfo()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (!PosSession::hasActiveSession()) {
            return [
                'success' => false,
                'message' => 'Không có phiên POS đang mở',
                'hasActiveSession' => false,
            ];
        }
        
        $session = PosSession::getActiveSession();
        
        // Tính toán doanh số, tiền mặt và các khoản thanh toán khác trong phiên
        $stats = PosSession::calculateSessionStats($session->id);
        
        return [
            'success' => true,
            'hasActiveSession' => true,
            'session' => [
                'id' => $session->id,
                'start_time' => Yii::$app->formatter->asDatetime($session->start_time),
                'duration' => Yii::$app->formatter->asRelativeTime($session->start_time),
                'start_amount' => $session->start_amount,
                'current_amount' => $session->start_amount + $stats['cash_sales'],
                'total_sales' => $stats['total_sales'],
                'cash_sales' => $stats['cash_sales'],
                'card_sales' => $stats['card_sales'],
                'bank_transfer_sales' => $stats['bank_transfer_sales'],
                'other_sales' => $stats['other_sales'],
                'orders_count' => $stats['orders_count'],
            ]
        ];
    }
    
    /**
     * Lấy danh sách đơn hàng tạm
     */
    public function actionGetHeldOrders()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $orders = Order::find()
            ->where(['status' => Order::STATUS_PENDING])
            ->andWhere(['pos_session_id' => PosSession::getActiveSession()->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(20)
            ->all();
        
        $result = [];
        foreach ($orders as $order) {
            $customerName = 'Khách lẻ';
            if ($order->customer_id) {
                $customer = Customer::findOne($order->customer_id);
                if ($customer) {
                    $customerName = $customer->full_name;
                }
            }
            
            $result[] = [
                'id' => $order->id,
                'code' => $this->getOrderCode($order),
                'total' => $order->total,
                'customer_name' => $customerName,
                'items_count' => $order->getOrderItems()->count(),
                'created_at' => Yii::$app->formatter->asRelativeTime($order->created_at),
                'note' => $order->note,
            ];
        }
        
        return [
            'success' => true,
            'orders' => $result,
        ];
    }
    
    /**
     * Khôi phục đơn hàng tạm
     */
    public function actionResumeOrder($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $order = Order::findOne([
            'id' => $id,
            'status' => Order::STATUS_PENDING,
        ]);
        
        if (!$order) {
            return [
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng tạm'
            ];
        }
        
        // Xóa giỏ hàng hiện tại
        Yii::$app->session->remove('pos_cart');
        Yii::$app->session->remove('pos_customer_id');
        Yii::$app->session->remove('pos_discount');
        Yii::$app->session->remove('pos_order_note');
        
        // Lấy chi tiết đơn hàng
        $orderItems = $order->orderItems;
        
        // Tạo giỏ hàng mới từ đơn hàng tạm
        $cart = [];
        foreach ($orderItems as $item) {
            $product = Product::findOne($item->product_id);
            if (!$product) continue;
            
            $itemKey = $item->product_id . ($item->variant_id ? '_' . $item->variant_id : '');
            
            $cart[$itemKey] = [
                'product_id' => $item->product_id,
                'variant_id' => $item->variant_id,
                'name' => $item->name,
                'code' => $item->code,
                'price' => $item->price,
                'original_price' => $item->original_price,
                'quantity' => $item->quantity,
                'unit' => $product->primaryUnit ? $product->primaryUnit->name : '',
                'image_url' => $this->getProductImageUrl($product),
                'discount' => $item->discount,
                'tax' => $item->tax,
            ];
        }
        
        // Lưu giỏ hàng vào session
        Yii::$app->session->set('pos_cart', $cart);
        
        // Lưu khách hàng vào session nếu có
        if ($order->customer_id) {
            Yii::$app->session->set('pos_customer_id', $order->customer_id);
        }
        
        // Lưu ghi chú vào session
        Yii::$app->session->set('pos_order_note', $order->note);
        
        // Nếu có giảm giá thì lưu vào session
        if ($order->discount > 0) {
            Yii::$app->session->set('pos_discount', [
                'type' => 'amount',
                'value' => $order->discount,
            ]);
        }
        
        // Xóa đơn hàng tạm
        $order->delete();
        
        return [
            'success' => true,
            'message' => 'Đã khôi phục đơn hàng tạm',
            'cart' => $cart,
        ];
    }
    
    /**
     * Tính toán tổng giỏ hàng
     * 
     * @param array $cart Giỏ hàng
     * @return array Kết quả tính toán
     */
    protected function calculateCartTotals($cart)
    {
        $totalQuantity = 0;
        $subtotal = 0;
        $discount = 0;
        $tax = 0;
        
        foreach ($cart as $item) {
            $totalQuantity += $item['quantity'];
            $itemTotal = $item['price'] * $item['quantity'];
            $subtotal += $itemTotal;
            $discount += $item['discount'] * $item['quantity'];
            $tax += $item['tax'] * $item['quantity'];
        }
        
        // Áp dụng giảm giá tổng nếu có
        $discountInfo = Yii::$app->session->get('pos_discount');
        if ($discountInfo) {
            if ($discountInfo['type'] == 'percent') {
                $discount += $subtotal * ($discountInfo['value'] / 100);
            } else {
                $discount += $discountInfo['value'];
            }
            
            // Giới hạn giảm giá không vượt quá tổng tiền
            if ($discount > $subtotal) {
                $discount = $subtotal;
            }
        }
        
        $grandTotal = $subtotal - $discount + $tax;
        
        return [
            'success' => true,
            'message' => 'Đã cập nhật giỏ hàng',
            'cart' => $cart,
            'totalQuantity' => $totalQuantity,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'grandTotal' => $grandTotal,
        ];
    }
    
    /**
     * Kiểm tra quyền truy cập POS cho người dùng hiện tại
     * 
     * @return bool true nếu có quyền, false nếu không
     */
    protected static function checkPosAccess()
    {
        // Nếu người dùng chưa đăng nhập
        if (Yii::$app->user->isGuest) {
            return false;
        }
        
        $user = Yii::$app->user->identity;
        
        // Nếu đã cài đặt RBAC và có quyền accessPos
        if (Yii::$app->has('authManager')) {
            return Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'accessPos');
        }
        
        // Nếu user có phương thức canAccessPos riêng
        if (method_exists($user, 'canAccessPos')) {
            return $user->canAccessPos();
        }
        
        // Mặc định cho phép đăng nhập
        return true;
    }

    /**
     * Tạo mã đơn hàng từ ID
     * 
     * @param Order $order
     * @return string
     */
    protected function getOrderCode($order)
    {
        return 'ORD' . str_pad($order->id, 6, '0', STR_PAD_LEFT);
    }
    
    /**
     * Lấy đường dẫn hình ảnh sản phẩm
     * 
     * @param Product $product
     * @return string
     */
    protected function getProductImageUrl($product)
    {
        // If product has getImageUrl method
        if (method_exists($product, 'getImageUrl')) {
            $url = $product->getImageUrl();
            if ($url) {
                return $url;
            }
        }
        
        // Default image if none exists
        return Yii::$app->request->baseUrl . '/images/product-default.png';
    }
}