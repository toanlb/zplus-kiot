<?php
// File: common/models/UserLoginHistory.php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class UserLoginHistory extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%user_login_history}}';
    }

    public function rules()
    {
        return [
            [['user_id', 'login_time'], 'required'],
            [['user_id', 'login_time', 'status'], 'integer'],
            [['user_agent'], 'string'],
            [['ip_address'], 'string', 'max' => 50],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Người dùng',
            'login_time' => 'Thời gian đăng nhập',
            'ip_address' => 'Địa chỉ IP',
            'user_agent' => 'Trình duyệt',
            'status' => 'Trạng thái',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
    
    public function getStatusText()
    {
        return $this->status ? 'Thành công' : 'Thất bại';
    }
}