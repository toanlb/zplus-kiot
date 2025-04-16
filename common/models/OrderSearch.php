<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Order;

class OrderSearch extends Model
{
    public $id;
    public $code;
    public $customer_id;
    public $customer_name;
    public $total_amount_from;
    public $total_amount_to;
    public $final_amount_from;
    public $final_amount_to;
    public $created_at_from;
    public $created_at_to;
    public $payment_method;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'customer_id'], 'integer'],
            [['code', 'customer_name', 'created_at_from', 'created_at_to', 'payment_method'], 'safe'],
            [['total_amount_from', 'total_amount_to', 'final_amount_from', 'final_amount_to'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Mã đơn hàng',
            'customer_id' => 'Khách hàng',
            'customer_name' => 'Tên khách hàng',
            'total_amount_from' => 'Tổng tiền từ',
            'total_amount_to' => 'Tổng tiền đến',
            'final_amount_from' => 'Thành tiền từ',
            'final_amount_to' => 'Thành tiền đến',
            'created_at_from' => 'Từ ngày',
            'created_at_to' => 'Đến ngày',
            'payment_method' => 'Phương thức thanh toán',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Tạo data provider instance dựa trên các điều kiện tìm kiếm
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Order::find();
        
        // Add relations needed for sorting/filtering
        $query->joinWith(['customer', 'orderPayments']);

        // Tạo data provider
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        // Set up data provider sorting attributes
        $dataProvider->sort->attributes['customer_name'] = [
            'asc' => ['customer.name' => SORT_ASC],
            'desc' => ['customer.name' => SORT_DESC],
        ];

        // Load params and validate
        $this->load($params);
        
        if (!$this->validate()) {
            // Uncomment để hiển thị tất cả lỗi
            // $query->where('0=1');
            return $dataProvider;
        }

        // Các điều kiện tìm kiếm
        $query->andFilterWhere([
            'orders.id' => $this->id,
            'orders.customer_id' => $this->customer_id,
        ]);

        // Tìm kiếm theo khoảng thời gian
        if ($this->created_at_from && $this->created_at_to) {
            $from = strtotime($this->created_at_from . ' 00:00:00');
            $to = strtotime($this->created_at_to . ' 23:59:59');
            $query->andFilterWhere(['between', 'orders.created_at', $from, $to]);
        } elseif ($this->created_at_from) {
            $from = strtotime($this->created_at_from . ' 00:00:00');
            $query->andFilterWhere(['>=', 'orders.created_at', $from]);
        } elseif ($this->created_at_to) {
            $to = strtotime($this->created_at_to . ' 23:59:59');
            $query->andFilterWhere(['<=', 'orders.created_at', $to]);
        }

        // Tìm kiếm theo khoảng tổng tiền
        if ($this->total_amount_from) {
            $query->andFilterWhere(['>=', 'orders.total_amount', $this->total_amount_from]);
        }
        
        if ($this->total_amount_to) {
            $query->andFilterWhere(['<=', 'orders.total_amount', $this->total_amount_to]);
        }
        
        // Tìm kiếm theo khoảng thành tiền
        if ($this->final_amount_from) {
            $query->andFilterWhere(['>=', 'orders.final_amount', $this->final_amount_from]);
        }
        
        if ($this->final_amount_to) {
            $query->andFilterWhere(['<=', 'orders.final_amount', $this->final_amount_to]);
        }

        // Tìm kiếm theo code (like)
        $query->andFilterWhere(['like', 'orders.code', $this->code]);
        
        // Tìm kiếm theo tên khách hàng
        if ($this->customer_name) {
            $query->andFilterWhere(['like', 'customers.name', $this->customer_name]);
        }
        
        // Tìm kiếm theo phương thức thanh toán
        if ($this->payment_method) {
            if ($this->payment_method === 'cash') {
                $query->andWhere('order_payments.cash_amount > 0');
            } elseif ($this->payment_method === 'card') {
                $query->andWhere('order_payments.card_amount > 0');
            } elseif ($this->payment_method === 'bank_transfer') {
                $query->andWhere('order_payments.bank_transfer_amount > 0');
            } elseif ($this->payment_method === 'ewallet') {
                $query->andWhere('order_payments.ewallet_amount > 0');
            }
        }

        return $dataProvider;
    }
}