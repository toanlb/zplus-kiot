<?php
namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class WarrantyRepairLogSearch extends Model
{
    public $id;
    public $warranty_id;
    public $repair_date;
    public $technician;
    public $status;

    public function rules()
    {
        return [
            [['id', 'warranty_id'], 'integer'],
            [['repair_date', 'technician', 'status'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = WarrantyRepairLog::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'repair_date' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'warranty_id' => $this->warranty_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'technician', $this->technician])
              ->andFilterWhere(['>=', 'repair_date', $this->repair_date]);

        return $dataProvider;
    }
}