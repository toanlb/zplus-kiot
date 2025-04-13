<?php
// File: common/models/User.php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;
	public $password; 
	
    public static function tableName()
    {
        return '{{%user}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            [['username', 'email'], 'required'],
            [['username', 'email', 'full_name'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
            [['position'], 'string', 'max' => 100],
            [['avatar'], 'string', 'max' => 255],
            [['last_login_at'], 'integer'],
            [['email'], 'email'],
            [['username'], 'unique'],
            [['email'], 'unique'],
			[['password'], 'string', 'min' => 6], // Thêm rule cho password
			[['password'], 'required', 'on' => 'create'], // Password bắt buộc khi tạo mới
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Tên đăng nhập',
            'full_name' => 'Họ và tên',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'phone' => 'Số điện thoại',
            'position' => 'Chức vụ',
            'avatar' => 'Ảnh đại diện',
            'status' => 'Trạng thái',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'last_login_at' => 'Đăng nhập lần cuối',
            'verification_token' => 'Verification Token',
			'password' => 'Mật khẩu',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public static function findByVerificationToken($token)
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    public function getProfile()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'id']);
    }
    
    public function getLoginHistory()
    {
        return $this->hasMany(UserLoginHistory::class, ['user_id' => 'id'])->orderBy(['login_time' => SORT_DESC]);
    }
    
    public function getRoleNames()
    {
        $roles = Yii::$app->authManager->getRolesByUser($this->id);
        $roleNames = [];
        foreach ($roles as $role) {
            $roleNames[] = $role->description ? $role->description : $role->name;
        }
        
        return !empty($roleNames) ? implode(', ', $roleNames) : 'Chưa gán vai trò';
    }
    
    public function recordLoginSuccess()
    {
        $this->last_login_at = time();
        $this->save(false);
        
        $history = new UserLoginHistory();
        $history->user_id = $this->id;
        $history->login_time = time();
        $history->ip_address = Yii::$app->request->userIP;
        $history->user_agent = Yii::$app->request->userAgent;
        $history->status = 1;
        $history->save();
    }
    
    public function recordLoginFailed()
    {
        $history = new UserLoginHistory();
        $history->user_id = $this->id;
        $history->login_time = time();
        $history->ip_address = Yii::$app->request->userIP;
        $history->user_agent = Yii::$app->request->userAgent;
        $history->status = 0;
        $history->save();
    }
}