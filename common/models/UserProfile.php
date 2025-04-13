<?php
// File: common/models/UserProfile.php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class UserProfile extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%user_profile}}';
    }

    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['address', 'notes'], 'string'],
            [['birthday', 'hire_date'], 'date', 'format' => 'php:Y-m-d'],
            [['gender'], 'string', 'max' => 10],
            [['id_card'], 'string', 'max' => 30],
            [['department'], 'string', 'max' => 100],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => 'Người dùng',
            'address' => 'Địa chỉ',
            'birthday' => 'Ngày sinh',
            'gender' => 'Giới tính',
            'id_card' => 'CMND/CCCD',
            'department' => 'Phòng ban',
            'hire_date' => 'Ngày vào làm',
            'notes' => 'Ghi chú',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}