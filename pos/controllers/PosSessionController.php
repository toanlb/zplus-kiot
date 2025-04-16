<?php
namespace pos\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use pos\models\PosSession;
use pos\models\PosSessionSearch;
use yii\web\NotFoundHttpException;
use pos\assets\PosAsset;

/**
 * Controller quản lý phiên làm việc POS
 */
class PosSessionController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->layout = 'main';
        PosAsset::register($this->view);
    }

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
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->canAccessPos();
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'open' => ['post'],
                    'close' => ['post'],
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Hiển thị danh sách ca làm việc
     * 
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PosSessionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Xem chi tiết ca làm việc
     * 
     * @param int $id ID của phiên làm việc
     * @return string
     * @throws NotFoundHttpException nếu không tìm thấy phiên
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Tạo mới ca làm việc (từ giao diện quản lý)
     * 
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PosSession();

        if ($model->load(Yii::$app->request->post())) {
            $model->start_time = time();
            $model->current_amount = $model->start_amount;
            $model->status = PosSession::STATUS_ACTIVE;
            
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Ca làm việc đã được tạo thành công.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Cập nhật ca làm việc
     * 
     * @param int $id ID của phiên làm việc
     * @return mixed
     * @throws NotFoundHttpException nếu không tìm thấy phiên
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // Không cho phép cập nhật ca đang mở
        if ($model->status == PosSession::STATUS_ACTIVE && $model->end_time === null) {
            Yii::$app->session->setFlash('error', 'Không thể chỉnh sửa ca làm việc đang mở.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Ca làm việc đã được cập nhật.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Xóa ca làm việc
     * 
     * @param int $id ID của phiên làm việc
     * @return mixed
     * @throws NotFoundHttpException nếu không tìm thấy phiên
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        // Không cho phép xóa ca đang mở
        if ($model->status == PosSession::STATUS_ACTIVE && $model->end_time === null) {
            Yii::$app->session->setFlash('error', 'Không thể xóa ca làm việc đang mở.');
        } else {
            // Kiểm tra xem có đơn hàng liên kết không
            if ($model->getOrders()->count() > 0) {
                Yii::$app->session->setFlash('error', 'Không thể xóa ca làm việc đã có giao dịch.');
            } else {
                $model->delete();
                Yii::$app->session->setFlash('success', 'Ca làm việc đã được xóa.');
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Tìm model phiên làm việc theo ID
     * 
     * @param int $id ID của phiên làm việc
     * @return PosSession model phiên làm việc
     * @throws NotFoundHttpException nếu không tìm thấy phiên
     */
    protected function findModel($id)
    {
        if (($model = PosSession::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Không tìm thấy ca làm việc.');
    }
}