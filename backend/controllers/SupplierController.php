<?php
namespace backend\controllers;

use Yii;
use common\models\Supplier;
use common\models\SupplierSearch;
use common\models\Product;
use common\models\ProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class SupplierController extends Controller
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
        $searchModel = new SupplierSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        // Get supplier's products
        $productSearchModel = new ProductSearch();
        $productSearchModel->supplier_id = $id;
        $productDataProvider = $productSearchModel->search([]);
        
        return $this->render('view', [
            'model' => $model,
            'productSearchModel' => $productSearchModel,
            'productDataProvider' => $productDataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Supplier();
        $model->status = 1;
        $model->created_at = time();
        $model->creator = Yii::$app->user->identity->username;
        $model->total_purchase = 0;
        $model->total_purchase_net = 0;
        $model->current_debt = 0;

        // Generate supplier code
        $lastSupplier = Supplier::find()->orderBy(['id' => SORT_DESC])->one();
        $nextId = $lastSupplier ? $lastSupplier->id + 1 : 1;
        $model->code = 'SUP' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
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

    public function actionUpdateStatus($id, $status)
    {
        $model = $this->findModel($id);
        $model->status = $status;
        $model->save();
        
        return $this->redirect(['view', 'id' => $id]);
    }

    protected function findModel($id)
    {
        if (($model = Supplier::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Không tìm thấy nhà cung cấp.');
    }
}