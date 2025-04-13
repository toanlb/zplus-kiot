<?php

namespace backend\controllers;

use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use common\models\User;

/**
 * Site controller
 */
class SiteController extends Controller
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
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
   public function actionIndex()
	{
		$totalProducts = \common\models\Product::find()->count();
		$totalCustomers = \common\models\Customer::find()->count();
		$totalOrders = \common\models\Order::find()->count();
		$totalSuppliers = \common\models\Supplier::find()->count();
		
		return $this->render('index', [
			'totalProducts' => $totalProducts,
			'totalCustomers' => $totalCustomers,
			'totalOrders' => $totalOrders,
			'totalSuppliers' => $totalSuppliers,
		]);
	}

    /**
     * Login action.
     *
     * @return string|Response
     */
	public function actionLogin()
	{
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			// Ghi nhận đăng nhập thành công
			$user = User::findByUsername($model->username);
			if ($user) {
				$user->recordLoginSuccess();
			}
			return $this->goBack();
		}

		$model->password = '';

		return $this->render('login', [
			'model' => $model,
		]);
	}

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
