<?php
namespace pos\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use pos\models\LoginForm;
use pos\models\PosSession;
use yii\web\ErrorAction;
use yii\web\NotFoundHttpException;

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
                'only' => ['logout', 'index'],
                'rules' => [
                    [
                        'actions' => ['logout', 'index', 'error'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                        'roles' => ['?'],
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
                'class' => 'yii\web\ErrorAction',
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
        // Kiểm tra người dùng có quyền truy cập POS không
        if (!$this->checkPosAccess()) {
            Yii::$app->session->setFlash('error', 'Bạn không có quyền truy cập vào hệ thống POS.');
            return $this->goHome();
        }
        
        // Kiểm tra có phiên làm việc nào đang mở không
        $hasActiveSession = PosSession::hasActiveSession();
        
        // Nếu có phiên đang mở, chuyển trực tiếp đến trang POS
        if ($hasActiveSession) {
            return $this->redirect(['pos/index']);
        }
        
        // Hiển thị trang chủ cho POS - trang chọn mở phiên làm việc
        return $this->render('index', [
            'hasActiveSession' => $hasActiveSession
        ]);
    }

    /**
     * Login action.
     *
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'main-login';
        $model = new LoginForm();
        
        // Xác định chế độ đăng nhập (standard hoặc pin)
        $loginMode = Yii::$app->request->post('LoginForm')['loginMode'] ?? 'standard';
        $model->loginMode = $loginMode;
        
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // Kiểm tra người dùng có quyền truy cập POS không
            if (!$this->checkPosAccess()) {
                Yii::$app->user->logout();
                Yii::$app->session->setFlash('error', 'Bạn không có quyền truy cập vào hệ thống POS.');
                return $this->goHome();
            }
            
            // Ghi nhận lịch sử đăng nhập
            $this->logUserLogin();
            
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
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    /**
     * Kiểm tra quyền truy cập POS cho người dùng hiện tại
     *
     * @return bool true nếu có quyền, false nếu không
     */
    protected function checkPosAccess()
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
     * Ghi nhận lịch sử đăng nhập của người dùng
     */
    private function logUserLogin()
    {
        // Lưu lịch sử đăng nhập nếu có bảng user_login_history
        try {
            Yii::$app->db->createCommand()->insert('{{%user_login_history}}', [
                'user_id' => Yii::$app->user->id,
                'login_time' => time(),
                'ip_address' => Yii::$app->request->userIP,
                'user_agent' => Yii::$app->request->userAgent,
                'platform' => 'pos',
            ])->execute();
        } catch (\Exception $e) {
            Yii::error('Không thể ghi nhận lịch sử đăng nhập: ' . $e->getMessage());
        }
    }
}