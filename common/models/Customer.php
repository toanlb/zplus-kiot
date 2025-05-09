<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "customers".
 *
 * @property int $id
 * @property string|null $type
 * @property string|null $branch_created
 * @property string $code
 * @property string $full_name
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $delivery_area
 * @property string|null $ward
 * @property string|null $company
 * @property string|null $tax_code
 * @property string|null $id_card
 * @property string|null $birthday
 * @property string|null $gender
 * @property string|null $email
 * @property string|null $facebook
 * @property string|null $customer_group
 * @property int|null $current_points
 * @property int|null $total_points
 * @property string|null $creator
 * @property int $created_at
 * @property int|null $last_transaction_date
 * @property float|null $current_debt
 * @property float|null $total_sales
 * @property float|null $total_sales_net
 * @property int|null $status
 * @property string|null $note
 *
 * @property Order[] $orders
 * @property ProductWarranty[] $productWarranties
 */
class Customer extends \yii\db\ActiveRecord
{

    	// Định nghĩa hằng số STATUS_ACTIVE
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'branch_created', 'phone', 'address', 'delivery_area', 'ward', 'company', 'tax_code', 'id_card', 'birthday', 'email', 'facebook', 'customer_group', 'creator', 'last_transaction_date', 'note'], 'default', 'value' => null],
            [['gender'], 'default', 'value' => 'male'],
            [['total_points'], 'default', 'value' => 0],
            [['total_sales_net'], 'default', 'value' => 0.00],
            [['status'], 'default', 'value' => 1],
            [['code', 'full_name', 'created_at'], 'required'],
            [['address', 'note'], 'string'],
            [['birthday'], 'safe'],
            [['current_points', 'total_points', 'created_at', 'last_transaction_date', 'status'], 'integer'],
            [['current_debt', 'total_sales', 'total_sales_net'], 'number'],
            [['type', 'code', 'tax_code', 'id_card'], 'string', 'max' => 50],
            [['branch_created', 'delivery_area', 'ward', 'customer_group', 'creator'], 'string', 'max' => 100],
            [['full_name', 'company', 'email', 'facebook'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
            [['gender'], 'string', 'max' => 10],
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
            'type' => 'Type',
            'branch_created' => 'Branch Created',
            'code' => 'Code',
            'full_name' => 'Full Name',
            'phone' => 'Phone',
            'address' => 'Address',
            'delivery_area' => 'Delivery Area',
            'ward' => 'Ward',
            'company' => 'Company',
            'tax_code' => 'Tax Code',
            'id_card' => 'Id Card',
            'birthday' => 'Birthday',
            'gender' => 'Gender',
            'email' => 'Email',
            'facebook' => 'Facebook',
            'customer_group' => 'Customer Group',
            'current_points' => 'Current Points',
            'total_points' => 'Total Points',
            'creator' => 'Creator',
            'created_at' => 'Created At',
            'last_transaction_date' => 'Last Transaction Date',
            'current_debt' => 'Current Debt',
            'total_sales' => 'Total Sales',
            'total_sales_net' => 'Total Sales Net',
            'status' => 'Status',
            'note' => 'Note',
        ];
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasMany(Order::class, ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[ProductWarranties]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductWarranty()
    {
        return $this->hasMany(ProductWarranty::class, ['customer_id' => 'id']);
    }

    /**
 * Tự động sinh mã khách hàng khi thêm mới
 * Format: KH + YYMMDD + số thứ tự (3 chữ số)
 * Ví dụ: KH250414001, KH250414002,...
 */
    public function generateCustomerCode()
    {
        // Lấy ngày hiện tại theo định dạng YYMMDD
        $datePart = date('ymd');
        
        // Tìm mã khách hàng có cùng ngày lớn nhất trong DB
        $lastCustomer = self::find()
            ->where(['like', 'code', 'KH' . $datePart])
            ->orderBy(['code' => SORT_DESC])
            ->one();
        
        if ($lastCustomer) {
            // Nếu tìm thấy, lấy số thứ tự và tăng lên 1
            $lastCode = $lastCustomer->code;
            $lastNumber = (int)substr($lastCode, -3); // Lấy 3 số cuối
            $newNumber = $lastNumber + 1;
        } else {
            // Nếu không tìm thấy, bắt đầu từ 1
            $newNumber = 1;
        }
        
        // Format số thứ tự thành chuỗi 3 chữ số (001, 002, ...)
        $numberPart = str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        
        // Tạo mã mới
        $newCode = 'KH' . $datePart . $numberPart;
        
        return $newCode;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Nếu là bản ghi mới và chưa có mã khách hàng
            if ($insert && empty($this->code)) {
                $this->code = $this->generateCustomerCode();
            }
            return true;
        }
        return false;
    }

}
