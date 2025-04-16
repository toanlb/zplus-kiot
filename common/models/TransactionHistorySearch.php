<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TransactionHistory;
use Yii;

/**
 * TransactionHistorySearch represents the model behind the search form for TransactionHistory.
 */
class TransactionHistorySearch extends TransactionHistory
{
    /**
     * Thuộc tính bổ sung cho tìm kiếm
     */
    public $date_from;
    public $date_to;
    public $user_name;
    public $customer_name;
    public $order_code;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'order_id', 'user_id', 'pos_session_id', 'customer_id'], 'integer'],
            [['transaction_code', 'payment_status', 'transaction_type', 'notes', 'date_from', 'date_to', 'user_name', 'customer_name', 'order_code'], 'safe'],
            [['total_amount', 'discount_amount', 'final_amount', 'paid_amount', 'cash_amount', 'card_amount', 'ewallet_amount', 'bank_transfer_amount'], 'number'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TransactionHistory::find();
        
        // Thêm join với bảng user, customer và order để tìm kiếm theo tên
        $query->joinWith([
            'user', 
            'customer', 
            'order'
        ]);

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
                'attributes' => [
                    'id',
                    'transaction_code',
                    'order_id',
                    'user_id',
                    'customer_id',
                    'total_amount',
                    'final_amount',
                    'paid_amount',
                    'payment_status',
                    'transaction_type',
                    'created_at',
                    'user_name' => [
                        'asc' => ['{{%user}}.username' => SORT_ASC],
                        'desc' => ['{{%user}}.username' => SORT_DESC],
                    ],
                    'customer_name' => [
                        'asc' => ['{{%customer}}.full_name' => SORT_ASC],
                        'desc' => ['{{%customer}}.full_name' => SORT_DESC],
                    ],
                    'order_code' => [
                        'asc' => ['{{%orders}}.code' => SORT_ASC],
                        'desc' => ['{{%orders}}.code' => SORT_DESC],
                    ],
                ],
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'order_id' => $this->order_id,
            'user_id' => $this->user_id,
            'pos_session_id' => $this->pos_session_id,
            'customer_id' => $this->customer_id,
            'total_amount' => $this->total_amount,
            'discount_amount' => $this->discount_amount,
            'final_amount' => $this->final_amount,
            'paid_amount' => $this->paid_amount,
            'cash_amount' => $this->cash_amount,
            'card_amount' => $this->card_amount,
            'ewallet_amount' => $this->ewallet_amount,
            'bank_transfer_amount' => $this->bank_transfer_amount,
        ]);

        $query->andFilterWhere(['like', 'transaction_history.transaction_code', $this->transaction_code])
            ->andFilterWhere(['like', 'transaction_history.payment_status', $this->payment_status])
            ->andFilterWhere(['like', 'transaction_history.transaction_type', $this->transaction_type])
            ->andFilterWhere(['like', 'transaction_history.notes', $this->notes]);
            
        // Tìm kiếm theo tên người dùng
        if (!empty($this->user_name)) {
            $query->andFilterWhere(['like', '{{%user}}.username', $this->user_name]);
        }
        
        // Tìm kiếm theo tên khách hàng
        if (!empty($this->customer_name)) {
            $query->andFilterWhere(['like', '{{%customer}}.full_name', $this->customer_name]);
        }
        
        // Tìm kiếm theo mã đơn hàng
        if (!empty($this->order_code)) {
            $query->andFilterWhere(['like', '{{%orders}}.code', $this->order_code]);
        }
        
        // Tìm kiếm theo khoảng thời gian
        if (!empty($this->date_from)) {
            $dateFrom = strtotime($this->date_from . ' 00:00:00');
            $query->andFilterWhere(['>=', 'transaction_history.created_at', $dateFrom]);
        }
        
        if (!empty($this->date_to)) {
            $dateTo = strtotime($this->date_to . ' 23:59:59');
            $query->andFilterWhere(['<=', 'transaction_history.created_at', $dateTo]);
        }

        return $dataProvider;
    }
}