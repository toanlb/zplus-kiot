<?php
namespace pos\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use pos\models\PosSession;

class PosSessionSearch extends Model
{
    public $id;
    public $user_id;
    public $status;
    public $date_from;
    public $date_to;

    public function rules()
    {
        return [
            [['id', 'user_id', 'status'], 'integer'],
            [['date_from', 'date_to'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = PosSession::find();
        $query->orderBy(['id' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'user_id' => $this->user_id,
            'status' => $this->status,
        ]);

        if (!empty($this->date_from) && !empty($this->date_to)) {
            $dateFrom = strtotime($this->date_from . ' 00:00:00');
            $dateTo = strtotime($this->date_to . ' 23:59:59');
            
            $query->andFilterWhere(['between', 'start_time', $dateFrom, $dateTo]);
        } elseif (!empty($this->date_from)) {
            $dateFrom = strtotime($this->date_from . ' 00:00:00');
            $query->andFilterWhere(['>=', 'start_time', $dateFrom]);
        } elseif (!empty($this->date_to)) {
            $dateTo = strtotime($this->date_to . ' 23:59:59');
            $query->andFilterWhere(['<=', 'start_time', $dateTo]);
        }

        return $dataProvider;
    }
}