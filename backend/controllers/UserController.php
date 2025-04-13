<?php
// File: backend/controllers/UserController.php
namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\UserSearch;
use common\models\UserProfile;
use common\models\UserLoginHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['viewUser'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['createUser'],
                    ],
                    [
                        'actions' => ['update', 'update-profile', 'upload-avatar'],
                        'allow' => true,
                        'roles' => ['updateUser'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['deleteUser'],
                    ],
                    [
                        'actions' => ['change-password', 'update-own-profile'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
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
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $profile = $model->profile ?: new UserProfile(['user_id' => $id]);
        
        // Lấy lịch sử đăng nhập
        $loginHistory = UserLoginHistory::find()
            ->where(['user_id' => $id])
            ->orderBy(['login_time' => SORT_DESC])
            ->limit(10)
            ->all();
        
        // Lấy vai trò của người dùng
        $auth = Yii::$app->authManager;
        $roles = $auth->getRolesByUser($id);

        return $this->render('view', [
            'model' => $model,
            'profile' => $profile,
            'loginHistory' => $loginHistory,
            'roles' => $roles,
        ]);
    }

    public function actionCreate()
    {
        $model = new User();
        $model->status = User::STATUS_ACTIVE;
        $profile = new UserProfile();

        if ($model->load(Yii::$app->request->post())) {
            $password = Yii::$app->request->post('User')['password'];
            if ($password) {
                $model->setPassword($password);
                $model->generateAuthKey();
                
                if ($model->save()) {
                    // Lưu profile
                    $profile->user_id = $model->id;
                    if ($profile->load(Yii::$app->request->post())) {
                        $profile->save();
                    }
                    
                    // Gán vai trò
                    $roleName = Yii::$app->request->post('role');
                    if ($roleName) {
                        $auth = Yii::$app->authManager;
                        $role = $auth->getRole($roleName);
                        if ($role) {
                            $auth->assign($role, $model->id);
                        }
                    }
                    
                    Yii::$app->session->setFlash('success', 'Tạo người dùng thành công.');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } else {
                $model->addError('password', 'Mật khẩu không được để trống');
            }
        }

        // Lấy danh sách vai trò
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();

        return $this->render('create', [
            'model' => $model,
            'profile' => $profile,
            'roles' => $roles,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $profile = $model->profile ?: new UserProfile(['user_id' => $id]);

        if ($model->load(Yii::$app->request->post())) {
            $password = Yii::$app->request->post('User')['password'];
            if ($password) {
                $model->setPassword($password);
            }
            
            if ($model->save()) {
                // Lưu profile
                if ($profile->load(Yii::$app->request->post())) {
                    $profile->save();
                }
                
                // Cập nhật vai trò
                $roleName = Yii::$app->request->post('role');
                if ($roleName) {
                    $auth = Yii::$app->authManager;
                    // Xóa vai trò cũ
                    $auth->revokeAll($model->id);
                    // Gán vai trò mới
                    $role = $auth->getRole($roleName);
                    if ($role) {
                        $auth->assign($role, $model->id);
                    }
                }
                
                Yii::$app->session->setFlash('success', 'Cập nhật người dùng thành công.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        // Lấy danh sách vai trò
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        
        // Lấy vai trò hiện tại
        $userRoles = $auth->getRolesByUser($id);
        $currentRole = !empty($userRoles) ? reset($userRoles)->name : null;

        return $this->render('update', [
            'model' => $model,
            'profile' => $profile,
            'roles' => $roles,
            'currentRole' => $currentRole,
        ]);
    }

    public function actionDelete($id)
    {
        // Không cho phép xóa tài khoản của chính mình
        if ($id == Yii::$app->user->id) {
            Yii::$app->session->setFlash('error', 'Không thể xóa tài khoản của bạn.');
            return $this->redirect(['index']);
        }
        
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Xóa người dùng thành công.');
        return $this->redirect(['index']);
    }

    public function actionChangePassword()
    {
        $id = Yii::$app->user->id;
        $model = $this->findModel($id);
        
        $model->scenario = 'changePassword';
        
        if ($model->load(Yii::$app->request->post())) {
            $currentPassword = Yii::$app->request->post('currentPassword');
            if ($model->validatePassword($currentPassword)) {
                $newPassword = Yii::$app->request->post('newPassword');
                $confirmPassword = Yii::$app->request->post('confirmPassword');
                
                if ($newPassword === $confirmPassword) {
                    $model->setPassword($newPassword);
                    if ($model->save(false)) {
                        Yii::$app->session->setFlash('success', 'Đổi mật khẩu thành công.');
                        return $this->redirect(['site/index']);
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Mật khẩu mới và xác nhận mật khẩu không khớp.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Mật khẩu hiện tại không đúng.');
            }
        }
        
        return $this->render('change-password', [
            'model' => $model,
        ]);
    }

    public function actionUpdateOwnProfile()
    {
        $id = Yii::$app->user->id;
        $model = $this->findModel($id);
        $profile = $model->profile ?: new UserProfile(['user_id' => $id]);
        
        if ($model->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {
            if ($model->save() && $profile->save()) {
                Yii::$app->session->setFlash('success', 'Cập nhật thông tin cá nhân thành công.');
                return $this->redirect(['update-own-profile']);
            }
        }
        
        return $this->render('update-own-profile', [
            'model' => $model,
            'profile' => $profile,
        ]);
    }

    public function actionUploadAvatar()
    {
        $model = $this->findModel(Yii::$app->request->post('id'));
        
        $model->avatar = UploadedFile::getInstance($model, 'avatar');
        if ($model->avatar) {
            $fileName = 'avatar_' . $model->id . '_' . time() . '.' . $model->avatar->extension;
            $uploadPath = Yii::getAlias('@backend/web/uploads/avatars/');
            
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            if ($model->avatar->saveAs($uploadPath . $fileName)) {
                $model->avatar = '/uploads/avatars/' . $fileName;
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'Cập nhật ảnh đại diện thành công.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Không thể lưu ảnh đại diện.');
            }
        }
        
        return $this->redirect(['view', 'id' => $model->id]);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}