<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\rbac\Role;
use yii\rbac\Permission;
use yii\helpers\ArrayHelper;

class AuthManagerController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['manageRbac'],
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
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $permissions = $auth->getPermissions();

        return $this->render('index', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    public function actionViewRole($name)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($name);
        
        if (!$role) {
            throw new \yii\web\NotFoundHttpException('Vai trò không tồn tại.');
        }
        
        $permissions = $auth->getPermissionsByRole($name);
        $allPermissions = $auth->getPermissions();
        
        $users = Yii::$app->authManager->getUserIdsByRole($name);
        $userModels = \common\models\User::find()->where(['id' => $users])->all();

        return $this->render('view-role', [
            'role' => $role,
            'permissions' => $permissions,
            'allPermissions' => $allPermissions,
            'users' => $userModels,
        ]);
    }

    public function actionUpdateRolePermissions($name)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($name);
        
        if (!$role) {
            throw new \yii\web\NotFoundHttpException('Vai trò không tồn tại.');
        }
        
        if (Yii::$app->request->isPost) {
            $selectedPermissions = Yii::$app->request->post('permissions', []);
            
            // Xóa tất cả quyền hiện tại của vai trò
            $auth->removeChildren($role);
            
            // Thêm các quyền đã chọn vào vai trò
            foreach ($selectedPermissions as $permissionName) {
                $permission = $auth->getPermission($permissionName);
                if ($permission) {
                    $auth->addChild($role, $permission);
                }
            }
            
            Yii::$app->session->setFlash('success', 'Cập nhật quyền thành công.');
            return $this->redirect(['view-role', 'name' => $name]);
        }
        
        $permissions = $auth->getPermissionsByRole($name);
        $allPermissions = $auth->getPermissions();
        
        return $this->render('update-role-permissions', [
            'role' => $role,
            'permissions' => $permissions,
            'allPermissions' => $allPermissions,
        ]);
    }

    public function actionAssignRole()
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $userModel = new \common\models\User();
        
        if (Yii::$app->request->isPost) {
            $userId = Yii::$app->request->post('user_id');
            $roleName = Yii::$app->request->post('role_name');
            
            if ($userId && $roleName) {
                // Xóa vai trò hiện tại của người dùng
                $auth->revokeAll($userId);
                
                // Gán vai trò mới
                $role = $auth->getRole($roleName);
                $auth->assign($role, $userId);
                
                Yii::$app->session->setFlash('success', 'Gán vai trò thành công.');
                return $this->redirect(['index']);
            }
        }
        
        $users = \common\models\User::find()->all();
        
        return $this->render('assign-role', [
            'roles' => $roles,
            'users' => $users,
        ]);
    }
}