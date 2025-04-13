<?php
namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class ProductWarrantySearch extends Model
{
    public $id;
    public $order_item_id;
    public $product_id;
    public $customer_id;
    public $serial_number;
    public $warranty_start_date;
    public $warranty_end_date;
    public $warranty_type;
    public $status;
    public $product_name;
    public $customer_name;

    public function rules()
    {
        return [
            [['id', 'order_item_id', 'product_id', 'customer_id'], 'integer'],
            [['serial_number', 'warranty_start_date', 'warranty_end_date', 'warranty_type', 'status', 'product_name', 'customer_name'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ProductWarranty::find();
        $query->joinWith(['product', 'customer']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'warranty_end_date' => SORT_ASC,
                ]
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        
        $dataProvider->sort->attributes['product_name'] = [
            'asc' => ['products.name' => SORT_ASC],
            'desc' => ['products.name' => SORT_DESC],
        ];
        
        $dataProvider->sort->attributes['customer_name'] = [
            'asc' => ['customers.full_name' => SORT_ASC],
            'desc' => ['customers.full_name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'product_warranties.id' => $this->id,
            'product_warranties.order_item_id' => $this->order_item_id,
            'product_warranties.product_id' => $this->product_id,
            'product_warranties.customer_id' => $this->customer_id,
            'product_warranties.warranty_type' => $this->warranty_type,
            'product_warranties.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'product_warranties.serial_number', $this->serial_number])
              ->andFilterWhere(['>=', 'product_warranties.warranty_start_date', $this->warranty_start_date])
              ->andFilterWhere(['<=', 'product_warranties.warranty_end_date', $this->warranty_end_date])
              ->andFilterWhere(['like', 'products.name', $this->product_name])
              ->andFilterWhere(['like', 'customers.full_name', $this->customer_name]);

        return $dataProvider;
    }
}