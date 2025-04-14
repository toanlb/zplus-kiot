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

class PosSessionController extends Controller
{
    public function init()
    {
        parent::init();
        $this->layout = 'pos';
        PosAsset::register($this->view);
    }

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

    // Hiển thị danh sách ca làm việc
    public function actionIndex()
    {
        $searchModel = new PosSessionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    // Xem chi tiết ca làm việc
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    // API để mở ca làm việc mới (từ giao diện POS)
    public function actionOpen()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        // Kiểm tra xem người dùng đã có ca làm việc đang mở chưa
        if (PosSession::hasActiveSession()) {
            return [
                'success' => false,
                'message' => 'Bạn đã có ca làm việc đang mở.'
            ];
        }
        
        $cashAmount = Yii::$app->request->post('cashAmount');
        $note = Yii::$app->request->post('note', '');
        
        if (empty($cashAmount) || !is_numeric($cashAmount)) {
            return [
                'success' => false,
                'message' => 'Vui lòng nhập số tiền đầu ca hợp lệ.'
            ];
        }
        
        // Mở ca làm việc mới
        $session = PosSession::openSession($cashAmount, $note);
        if ($session) {
            return [
                'success' => true,
                'message' => 'Đã mở ca làm việc thành công.',
                'session' => [
                    'id' => $session->id,
                    'startTime' => Yii::$app->formatter->asDatetime($session->start_time),
                    'startAmount' => PosSession::formatCurrency($session->start_amount),
                ]
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Có lỗi xảy ra khi mở ca làm việc.'
        ];
    }

    // API để đóng ca làm việc (từ giao diện POS)
    public function actionClose()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $activeSession = PosSession::getActiveSession();
        if (!$activeSession) {
            return [
                'success' => false,
                'message' => 'Không có ca làm việc đang mở.'
            ];
        }
        
        $closingAmount = Yii::$app->request->post('closingAmount');
        $note = Yii::$app->request->post('note', '');
        
        if (empty($closingAmount) || !is_numeric($closingAmount)) {
            return [
                'success' => false,
                'message' => 'Vui lòng nhập số tiền cuối ca hợp lệ.'
            ];
        }
        
        // Đóng ca làm việc
        if ($activeSession->closeSession($closingAmount, $note)) {
            // Tính chênh lệch
            $difference = $activeSession->difference;
            $diffText = $difference == 0 ? 'Không có chênh lệch' : 
                       ($difference > 0 ? 'Thừa ' . PosSession::formatCurrency($difference) : 
                                         'Thiếu ' . PosSession::formatCurrency(abs($difference)));
            
            return [
                'success' => true,
                'message' => 'Đã đóng ca làm việc thành công.',
                'session' => [
                    'id' => $activeSession->id,
                    'startTime' => Yii::$app->formatter->asDatetime($activeSession->start_time),
                    'endTime' => Yii::$app->formatter->asDatetime($activeSession->end_time),
                    'workingTime' => $activeSession->getWorkingTime(),
                    'startAmount' => PosSession::formatCurrency($activeSession->start_amount),
                    'endAmount' => PosSession::formatCurrency($activeSession->end_amount),
                    'expectedAmount' => PosSession::formatCurrency($activeSession->expected_amount),
                    'difference' => $diffText,
                    'totalSales' => PosSession::formatCurrency($activeSession->total_sales),
                ]
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Có lỗi xảy ra khi đóng ca làm việc.'
        ];
    }

    // Lấy thông tin ca làm việc hiện tại
    public function actionCurrent()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $activeSession = PosSession::getActiveSession();
        if (!$activeSession) {
            return [
                'success' => false,
                'message' => 'Không có ca làm việc đang mở.'
            ];
        }
        
        return [
            'success' => true,
            'session' => [
                'id' => $activeSession->id,
                'startTime' => Yii::$app->formatter->asDatetime($activeSession->start_time),
                'workingTime' => $activeSession->getWorkingTime(),
                'startAmount' => PosSession::formatCurrency($activeSession->start_amount),
                'currentAmount' => PosSession::formatCurrency($activeSession->current_amount),
                'cashSales' => PosSession::formatCurrency($activeSession->cash_sales),
                'cardSales' => PosSession::formatCurrency($activeSession->card_sales),
                'bankSales' => PosSession::formatCurrency($activeSession->bank_transfer_sales),
                'otherSales' => PosSession::formatCurrency($activeSession->other_sales),
                'totalSales' => PosSession::formatCurrency($activeSession->total_sales),
            ]
        ];
    }

    // Tạo mới ca làm việc (từ giao diện quản lý)
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

    // Cập nhật ca làm việc
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

    // Xóa ca làm việc
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

    // Tìm model
    protected function findModel($id)
    {
        if (($model = PosSession::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Không tìm thấy ca làm việc.');
    }
}