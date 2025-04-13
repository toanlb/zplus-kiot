<?php
namespace pos\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $pin;
    public $loginMode = 'standard'; // 'standard' hoặc 'pin'

    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // Chế độ đăng nhập thông thường
            [['username', 'password'], 'required', 'when' => function($model) {
                return $model->loginMode === 'standard';
            }, 'whenClient' => "function (attribute, value) {
                return $('#loginform-loginmode').val() === 'standard';
            }"],
            // Chế độ đăng nhập bằng PIN
            [['pin'], 'required', 'when' => function($model) {
                return $model->loginMode === 'pin';
            }, 'whenClient' => "function (attribute, value) {
                return $('#loginform-loginmode').val() === 'pin';
            }"],
            ['pin', 'string', 'min' => 4, 'max' => 6],
            
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
            ['pin', 'validatePin'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Tên đăng nhập',
            'password' => 'Mật khẩu',
            'rememberMe' => 'Ghi nhớ đăng nhập',
            'pin' => 'Mã PIN',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors() && $this->loginMode === 'standard') {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Tên đăng nhập hoặc mật khẩu không đúng.');
            } elseif (!$this->checkPosAccess($user)) {
                $this->addError($attribute, 'Tài khoản của bạn không có quyền truy cập vào POS.');
            }
        }
    }

    /**
     * Validates the PIN.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePin($attribute, $params)
    {
        if (!$this->hasErrors() && $this->loginMode === 'pin') {
            $user = User::findByPin($this->pin);
            if (!$user) {
                $this->addError($attribute, 'Mã PIN không đúng.');
            } elseif (!$this->checkPosAccess($user)) {
                $this->addError($attribute, 'Tài khoản của bạn không có quyền truy cập vào POS.');
            }
            $this->_user = $user;
        }
    }

    /**
     * Kiểm tra quyền truy cập POS
     * 
     * @param User $user Người dùng cần kiểm tra
     * @return bool true nếu có quyền, false nếu không
     */
    protected function checkPosAccess($user)
    {
        // Nếu đã cài đặt RBAC và có quyền accessPos
        if (Yii::$app->has('authManager')) {
            return Yii::$app->authManager->checkAccess($user->getId(), 'accessPos');
        }
        
        // Nếu user có phương thức canAccessPos riêng
        if (method_exists($user, 'canAccessPos')) {
            return $user->canAccessPos();
        }
        
        // Mặc định cho phép đăng nhập (có thể thay đổi tùy theo logic)
        return true;
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            if ($this->loginMode === 'standard') {
                $this->_user = User::findByUsername($this->username);
            }
        }

        return $this->_user;
    }
}