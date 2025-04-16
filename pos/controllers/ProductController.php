<?php
namespace pos\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use common\models\Product;
use common\models\Category;

/**
 * Product controller for POS
 * Cung cấp API sản phẩm cho POS
 */
class ProductController extends Controller
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
                        'actions' => ['list', 'view', 'search', 'categories'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('accessPos');
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'list' => ['get'],
                    'view' => ['get'],
                    'search' => ['get'],
                    'categories' => ['get'],
                ],
            ],
            'contentNegotiator' => [
                'class' => 'yii\filters\ContentNegotiator',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     * Lấy danh sách sản phẩm
     */
    public function actionList()
    {
        $categoryId = Yii::$app->request->get('categoryId');
        $page = Yii::$app->request->get('page', 1);
        $perPage = Yii::$app->request->get('perPage', 24);
        
        $query = Product::find()
            ->where(['status' => Product::STATUS_ACTIVE]);
        
        if ($categoryId) {
            $query->andWhere(['category_id' => $categoryId]);
        }
        
        $totalCount = $query->count();
        
        $products = $query->orderBy(['name' => SORT_ASC])
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->all();
        
        $result = [];
        foreach ($products as $product) {
            $result[] = $this->formatProductData($product);
        }
        
        return [
            'success' => true,
            'products' => $result,
            'totalCount' => $totalCount,
            'pages' => ceil($totalCount / $perPage),
            'currentPage' => (int)$page,
        ];
    }
    
    /**
     * Lấy thông tin chi tiết sản phẩm
     * 
     * @param int $id ID sản phẩm
     * @return array
     */
    public function actionView($id)
    {
        $product = Product::findOne($id);
        if (!$product) {
            return [
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm'
            ];
        }
        
        return [
            'success' => true,
            'product' => $this->formatProductData($product, true)
        ];
    }
    
    /**
     * Tìm kiếm sản phẩm
     */
    public function actionSearch()
    {
        $search = Yii::$app->request->get('q');
        $page = Yii::$app->request->get('page', 1);
        $perPage = Yii::$app->request->get('perPage', 24);
        
        if (!$search) {
            return [
                'success' => false,
                'message' => 'Vui lòng nhập từ khóa tìm kiếm'
            ];
        }
        
        $query = Product::find()
            ->where(['status' => Product::STATUS_ACTIVE])
            ->andWhere(['or', 
                ['like', 'name', $search],
                ['like', 'code', $search],
                ['like', 'barcode', $search]
            ]);
        
        $totalCount = $query->count();
        
        $products = $query->orderBy(['name' => SORT_ASC])
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->all();
        
        $result = [];
        foreach ($products as $product) {
            $result[] = $this->formatProductData($product);
        }
        
        return [
            'success' => true,
            'products' => $result,
            'totalCount' => $totalCount,
            'pages' => ceil($totalCount / $perPage),
            'currentPage' => (int)$page,
        ];
    }
    
    /**
     * Lấy danh sách danh mục sản phẩm
     */
    public function actionCategories()
    {
        $categories = Category::find()
            ->where(['status' => Category::STATUS_ACTIVE])
            ->orderBy(['position' => SORT_ASC])
            ->all();
        
        $result = [];
        foreach ($categories as $category) {
            $result[] = [
                'id' => $category->id,
                'name' => $category->name,
                'icon' => $category->icon ?? 'fas fa-folder',
                'position' => $category->position,
                'product_count' => Product::find()
                    ->where(['category_id' => $category->id, 'status' => Product::STATUS_ACTIVE])
                    ->count(),
            ];
        }
        
        return [
            'success' => true,
            'categories' => $result,
        ];
    }
    
    /**
     * Format dữ liệu sản phẩm
     * 
     * @param Product $product Đối tượng sản phẩm
     * @param bool $detailed Có hiển thị chi tiết hay không
     * @return array Dữ liệu sản phẩm đã được format
     */
    protected function formatProductData($product, $detailed = false)
    {
        $data = [
            'id' => $product->id,
            'name' => $product->name,
            'code' => $product->code,
            'price' => (float)$product->price,
            'discount_price' => (float)$product->discount_price ?? $product->price,
            'unit' => $product->unit ? $product->unit->name : '',
            'image_url' => $this->getProductImageUrl($product),
            'in_stock' => (int)$product->stock_quantity,
        ];
        
        if ($detailed) {
            $data = array_merge($data, [
                'barcode' => $product->barcode,
                'description' => $product->description,
                'category' => $product->category ? $product->category->name : '',
                'category_id' => $product->category_id,
            ]);
        }
        
        return $data;
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