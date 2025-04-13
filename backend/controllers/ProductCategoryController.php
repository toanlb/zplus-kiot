<?php

namespace backend\controllers;

use Yii;
use common\models\ProductCategory;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ProductCategoryController extends Controller
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
        $dataProvider = new ActiveDataProvider([
            'query' => ProductCategory::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
	{
		$model = new ProductCategory();
		$model->created_at = time(); // Đảm bảo gán giá trị cho created_at
		$model->status = 1; // Mặc định kích hoạt

		if ($model->load(Yii::$app->request->post())) {
			// Thêm dòng debug này để kiểm tra dữ liệu
			echo '<pre>';
			print_r($model->attributes);
			echo '</pre>';
			echo '<pre>';
			print_r($model->errors);
			echo '</pre>';
			
			if ($model->save()) {
				Yii::$app->session->setFlash('success', 'Danh mục đã được tạo thành công');
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
				Yii::$app->session->setFlash('error', 'Không thể lưu danh mục');
			}
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

    protected function findModel($id)
    {
        if (($model = ProductCategory::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}