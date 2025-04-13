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
    public $total_amount;
    public $final_amount;
    public $created_at_from;
    public $created_at_to;

    public function rules()
    {
        return [
            [['id', 'customer_id'], 'integer'],
            [['code', 'created_at_from', 'created_at_to'], 'safe'],
            [['total_amount', 'final_amount'], 'number'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Order::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'customer_id' => $this->customer_id,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code]);

        if ($this->total_amount) {
            $query->andFilterWhere(['>=', 'total_amount', $this->total_amount]);
        }

        if ($this->final_amount) {
            $query->andFilterWhere(['>=', 'final_amount', $this->final_amount]);
        }

        if ($this->created_at_from) {
            $query->andFilterWhere(['>=', 'created_at', strtotime($this->created_at_from . ' 00:00:00')]);
        }

        if ($this->created_at_to) {
            $query->andFilterWhere(['<=', 'created_at', strtotime($this->created_at_to . ' 23:59:59')]);
        }

        return $dataProvider;
    }
}