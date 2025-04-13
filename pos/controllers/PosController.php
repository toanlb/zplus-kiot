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
                        'actions' => ['index', 'get-products', 'get-categories', 'add-to-cart', 
                                     'update-cart', 'remove-from-cart', 'get-cart', 'clear-cart',
                                     'search-customers', 'add-customer', 'payment', 'complete-order',
                                     'get-product-details', 'apply-discount', 'save-order',
                                     'open-session', 'close-session', 'get-session-info',
                                     'hold-order', 'get-held-orders', 'resume-order'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            // Sử dụng phương thức checkPosAccess tương tự như trong SiteController
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
                    'complete-order' => ['post'],
                    'apply-discount' => ['post'],
                    'save-order' => ['post'],
                    'open-session' => ['post'],
                    'close-session' => ['post'],
                    'hold-order' => ['post'],
                    'resume-order' => ['post'],
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
            ->where(['status' => Product::STATUS_ACTIVE]);
        
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
                'price' => $product->price,
                'discount_price' => $product->discount_price,
                'unit' => $product->unit ? $product->unit->name : '',
                'image_url' => $this->getProductImageUrl($product),
                'in_stock' => $product->stock_quantity,
                'has_variants' => $product->has_variants,
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
        
        // Lấy thông tin biến thể nếu có
        $variants = [];
        if ($product->has_variants) {
            // Lấy thông tin biến thể từ model phù hợp
            // Ví dụ: $variants = $product->getVariants();
        }
        
        return [
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'code' => $product->code,
                'barcode' => $product->barcode,
                'price' => $product->price,
                'discount_price' => $product->discount_price,
                'unit' => $product->unit ? $product->unit->name : '',
                'image_url' => $this->getProductImageUrl($product),
                'description' => $product->description,
                'in_stock' => $product->stock_quantity,
                'has_variants' => $product->has_variants,
                'variants' => $variants,
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
        
        // Kiểm tra tồn kho
        if ($product->stock_quantity < $quantity) {
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
            // Thêm mới vào giỏ hàng
            $cart[$itemKey] = [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'name' => $product->name,
                'code' => $product->code,
                'price' => $product->discount_price > 0 ? $product->discount_price : $product->price,
                'original_price' => $product->price,
                'quantity' => $quantity,
                'unit' => $product->unit ? $product->unit->name : '',
                'image_url' => $this->getProductImageUrl($product),
                'discount' => 0,
                'tax' => 0, // Có thể bổ sung tính thuế nếu cần
            ];
        }
        
        // Lưu giỏ hàng vào session
        Yii::$app->session->set('pos_cart', $cart);
        
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
        
        $grandTotal = $subtotal - $discount + $tax;
        
        return [
            'success' => true,
            'message' => 'Đã thêm sản phẩm vào giỏ hàng',
            'cart' => $cart,
            'totalQuantity' => $totalQuantity,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'grandTotal' => $grandTotal,
        ];
    }
    
    /**
     * Cập nhật giỏ hàng
     */
    public function actionUpdateCart()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $itemKey = Yii::$app->request->post('itemKey');
        $quantity = Yii::$app->request->post('quantity');
        
        // Lấy giỏ hàng từ session
        $cart = Yii::$app->session->get('pos_cart', []);
        
        if (!isset($cart[$itemKey])) {
            return [
                'success' => false,
                'message' => 'Sản phẩm không tồn tại trong giỏ hàng'
            ];
        }
        
        // Nếu số lượng = 0, xóa sản phẩm khỏi giỏ hàng
        if ($quantity <= 0) {
            unset($cart[$itemKey]);
        } else {
            // Kiểm tra tồn kho
            $productId = $cart[$itemKey]['product_id'];
            $product = Product::findOne($productId);
            
            if ($product && $product->stock_quantity < $quantity) {
                return [
                    'success' => false,
                    'message' => 'Sản phẩm không đủ số lượng trong kho'
                ];
            }
            
            // Cập nhật số lượng
            $cart[$itemKey]['quantity'] = $quantity;
        }
        
        // Lưu giỏ hàng vào session
        Yii::$app->session->set('pos_cart', $cart);
        
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
        
        $grandTotal = $subtotal - $discount + $tax;
        
        return [
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng',
            'cart' => $cart,
            'totalQuantity' => $totalQuantity,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'grandTotal' => $grandTotal,
        ];
    }
    
    /**
     * Lấy thông tin giỏ hàng
     */
    public function actionGetCart()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        // Lấy giỏ hàng từ session
        $cart = Yii::$app->session->get('pos_cart', []);
        
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
        
        $grandTotal = $subtotal - $discount + $tax;
        
        // Lấy thông tin khách hàng đã chọn nếu có
        $customerId = Yii::$app->session->get('pos_customer_id');
        $customerInfo = null;
        
        if ($customerId) {
            $customer = Customer::findOne($customerId);
            if ($customer) {
                $customerInfo = [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'email' => $customer->email,
                    'points' => $customer->points,
                    'debt' => $customer->debt,
                ];
            }
        }
        
        return [
            'success' => true,
            'cart' => $cart,
            'totalQuantity' => $totalQuantity,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'grandTotal' => $grandTotal,
            'customer' => $customerInfo,
        ];
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
                ['like', 'name', $search],
                ['like', 'phone', $search],
                ['like', 'email', $search]
            ]);
        }
        
        $customers = $query->orderBy(['name' => SORT_ASC])
            ->limit(10)
            ->all();
        
        $result = [];
        foreach ($customers as $customer) {
            $result[] = [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'email' => $customer->email,
                'address' => $customer->address,
                'points' => $customer->points,
                'debt' => $customer->debt,
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
        $customer->name = $name;
        $customer->phone = $phone;
        $customer->email = $email;
        $customer->address = $address;
        $customer->status = Customer::STATUS_ACTIVE;
        $customer->created_at = time();
        $customer->created_by = Yii::$app->user->id;
        
        if ($customer->save()) {
            // Lưu id khách hàng vào session
            Yii::$app->session->set('pos_customer_id', $customer->id);
            
            return [
                'success' => true,
                'message' => 'Đã thêm khách hàng mới',
                'customer' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'email' => $customer->email,
                    'address' => $customer->address,
                    'points' => 0,
                    'debt' => 0,
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
            'zalopay' => 'ZaloPay',
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
        
        // Lấy giỏ hàng từ session
        $cart = Yii::$app->session->get('pos_cart', []);
        
        // Tính toán tổng giỏ hàng
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        // Tính giảm giá
        $discountAmount = 0;
        if ($type == 'percent') {
            $discountAmount = $subtotal * ($value / 100);
        } else {
            $discountAmount = $value;
        }
        
        // Giới hạn giảm giá không vượt quá tổng tiền
        if ($discountAmount > $subtotal) {
            $discountAmount = $subtotal;
        }
        
        $grandTotal = $subtotal - $discountAmount;
        
        return [
            'success' => true,
            'message' => 'Đã áp dụng giảm giá',
            'subtotal' => $subtotal,
            'discount' => $discountAmount,
            'grandTotal' => $grandTotal,
        ];
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
            $order->customer_id = $customerId;
            $order->status = Order::STATUS_COMPLETED;
            $order->subtotal = $subtotal;
            $order->discount = $discountAmount;
            $order->tax = $tax;
            $order->total = $grandTotal;
            $order->note = $note;
            $order->created_by = Yii::$app->user->id;
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
                $orderItem->variant_id = $item['variant_id'];
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
                
                // Cập nhật tồn kho
                $product = Product::findOne($item['product_id']);
                if ($product) {
                    $product->stock_quantity -= $item['quantity'];
                    $product->sales_count += $item['quantity'];
                    
                    if (!$product->save()) {
                        throw new \Exception('Không thể cập nhật tồn kho: ' . implode(', ', $product->getErrorSummary(true)));
                    }
                }
            }
            
            // Thêm thanh toán
            $payment = new OrderPayment();
            $payment->order_id = $order->id;
            $payment->payment_method = $paymentMethod;
            $payment->amount = $grandTotal;
            $payment->status = OrderPayment::STATUS_COMPLETED;
            $payment->transaction_id = time() . rand(1000, 9999);
            $payment->created_by = Yii::$app->user->id;
            $payment->created_at = time();
            
            // Nếu là công nợ thì đánh dấu chưa thanh toán
            if ($paymentMethod == 'credit') {
                $payment->status = OrderPayment::STATUS_PENDING;
                
                // Cập nhật công nợ cho khách hàng
                if ($customerId) {
                    $customer = Customer::findOne($customerId);
                    if ($customer) {
                        $customer->debt += $grandTotal;
                        if (!$customer->save()) {
                            throw new \Exception('Không thể cập nhật công nợ khách hàng: ' . implode(', ', $customer->getErrorSummary(true)));
                        }
                    }
                }
            }
            
            if (!$payment->save()) {
                throw new \Exception('Không thể thêm thanh toán: ' . implode(', ', $payment->getErrorSummary(true)));
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
                    'total' => $order->total,
                    'change' => ($paymentMethod == 'cash' && $amountTendered > 0) ? ($amountTendered - $order->total) : 0,
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
        
        $cashAmount = Yii::$app->request->post('cashAmount', 0);
        $note = Yii::$app->request->post('note', '');
        
        // Kiểm tra có phiên đang mở không
        if (!PosSession::hasActiveSession()) {
            return [
                'success' => false,
                'message' => 'Không có phiên POS đang mở'
            ];
        }
        
        $session = PosSession::getActiveSession();
        
        // Tính toán doanh số, tiền mặt và các khoản thanh toán khác trong phiên
        $stats = PosSession::calculateSessionStats($session->id);
        
        // Cập nhật thông tin đóng phiên
        $session->end_time = time();
        $session->end_amount = $cashAmount;
        $session->cash_sales = $stats['cash_sales'];
        $session->card_sales = $stats['card_sales'];
        $session->bank_transfer_sales = $stats['bank_transfer_sales'];
        $session->other_sales = $stats['other_sales'];
        $session->total_sales = $stats['total_sales'];
        $session->expected_amount = $session->start_amount + $stats['cash_sales'];
        $session->difference = $cashAmount - $session->expected_amount;
        $session->close_note = $note;
        $session->status = PosSession::STATUS_CLOSED;
        
        if ($session->save()) {
            return [
                'success' => true,
                'message' => 'Đã đóng phiên làm việc',
                'session' => [
                    'id' => $session->id,
                    'start_time' => Yii::$app->formatter->asDatetime($session->start_time),
                    'end_time' => Yii::$app->formatter->asDatetime($session->end_time),
                    'start_amount' => $session->start_amount,
                    'end_amount' => $session->end_amount,
                    'expected_amount' => $session->expected_amount,
                    'difference' => $session->difference,
                    'total_sales' => $session->total_sales,
                    'cash_sales' => $session->cash_sales,
                    'card_sales' => $session->card_sales,
                    'bank_transfer_sales' => $session->bank_transfer_sales,
                    'other_sales' => $session->other_sales,
                ]
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Không thể đóng phiên làm việc: ' . implode(', ', $session->getErrorSummary(true))
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
        $subtotal = 0;
        $discount = 0;
        $tax = 0;
        
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            $discount += $item['discount'] * $item['quantity'];
            $tax += $item['tax'] * $item['quantity'];
        }
        
        $order->subtotal = $subtotal;
        $order->discount = $discount;
        $order->tax = $tax;
        $order->total = $subtotal - $discount + $tax;
        
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
                $orderItem->variant_id = $item['variant_id'];
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
                    $customerName = $customer->name;
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
                'unit' => $product->unit ? $product->unit->name : '',
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
        
        // Mặc định cho phép đăng nhập (có thể thay đổi tùy theo logic)
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
        // Nếu sản phẩm có hình ảnh
        if (method_exists($product, 'getImageUrl')) {
            $url = $product->getImageUrl();
            if ($url) {
                return $url;
            }
        }
        
        // Hình mặc định nếu không có
        return Yii::$app->request->baseUrl . '/images/product-default.png';
    }
}