<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "suppliers".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $area
 * @property string|null $ward
 * @property string|null $tax_code
 * @property string|null $company
 * @property float|null $total_purchase
 * @property float|null $current_debt
 * @property string|null $group
 * @property int|null $status
 * @property float|null $total_purchase_net
 * @property string|null $creator
 * @property int $created_at
 * @property string|null $note
 */
class Supplier extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'suppliers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'phone', 'address', 'area', 'ward', 'tax_code', 'company', 'group', 'creator', 'note'], 'default', 'value' => null],
            [['total_purchase_net'], 'default', 'value' => 0.00],
            [['status'], 'default', 'value' => 1],
            [['code', 'name', 'created_at'], 'required'],
            [['address', 'note'], 'string'],
            [['total_purchase', 'current_debt', 'total_purchase_net'], 'number'],
            [['status', 'created_at'], 'integer'],
            [['code', 'tax_code'], 'string', 'max' => 50],
            [['name', 'email', 'company'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
            [['area', 'ward', 'group', 'creator'], 'string', 'max' => 100],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'address' => 'Address',
            'area' => 'Area',
            'ward' => 'Ward',
            'tax_code' => 'Tax Code',
            'company' => 'Company',
            'total_purchase' => 'Total Purchase',
            'current_debt' => 'Current Debt',
            'group' => 'Group',
            'status' => 'Status',
            'total_purchase_net' => 'Total Purchase Net',
            'creator' => 'Creator',
            'created_at' => 'Created At',
            'note' => 'Note',
        ];
    }

}
