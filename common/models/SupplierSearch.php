<?php
namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class SupplierSearch extends Model
{
    public $id;
    public $code;
    public $name;
    public $email;
    public $phone;
    public $tax_code;
    public $company;
    public $status;
    public $group;

    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['code', 'name', 'email', 'phone', 'tax_code', 'company', 'group'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Supplier::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'name' => SORT_ASC,
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
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
              ->andFilterWhere(['like', 'name', $this->name])
              ->andFilterWhere(['like', 'email', $this->email])
              ->andFilterWhere(['like', 'phone', $this->phone])
              ->andFilterWhere(['like', 'tax_code', $this->tax_code])
              ->andFilterWhere(['like', 'company', $this->company])
              ->andFilterWhere(['like', 'group', $this->group]);

        return $dataProvider;
    }
}