<?php
// File: common/models/UserSearch.php
namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

class UserSearch extends Model
{
    public $id;
    public $username;
    public $full_name;
    public $email;
    public $phone;
    public $position;
    public $status;
    public $created_at;
    public $last_login_at;

    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['username', 'full_name', 'email', 'phone', 'position'], 'safe'],
            [['created_at', 'last_login_at'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
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

        if ($this->created_at) {
            $start = strtotime($this->created_at);
            $end = strtotime($this->created_at . ' 23:59:59');
            $query->andFilterWhere(['between', 'created_at', $start, $end]);
        }

        if ($this->last_login_at) {
            $start = strtotime($this->last_login_at);
            $end = strtotime($this->last_login_at . ' 23:59:59');
            $query->andFilterWhere(['between', 'last_login_at', $start, $end]);
        }

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'full_name', $this->full_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'position', $this->position]);

        return $dataProvider;
    }
}