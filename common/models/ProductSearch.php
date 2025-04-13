<?php
namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Product;

class ProductSearch extends Model
{
    public $id;
    public $category_id;
    public $supplier_id;
    public $code;
    public $barcode;
    public $name;
    public $brand;
    public $selling_price;
    public $cost_price;
    public $current_stock;
    public $min_stock;
    public $max_stock;
    public $primary_unit_id;
    public $related_product_code;
    public $weight;
    public $is_active;
    public $is_direct_sale;
    public $description;
    public $note;
    public $location;
    public $is_component;
    public $warranty_months;
    public $maintenance_period_months;
    public $point_earn;
    public $created_at;
    public $updated_at;

    public function rules()
    {
        return [
            [['id', 'category_id', 'supplier_id', 'primary_unit_id', 'current_stock', 'min_stock', 'max_stock', 'is_active', 'is_direct_sale', 'is_component', 'warranty_months', 'maintenance_period_months', 'point_earn', 'created_at', 'updated_at'], 'integer'],
            [['code', 'barcode', 'name', 'brand', 'related_product_code', 'description', 'note', 'location'], 'safe'],
            [['selling_price', 'cost_price', 'weight'], 'number'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Product::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'name' => SORT_ASC,
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

        // Thêm các điều kiện lọc
        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'supplier_id' => $this->supplier_id,
            'primary_unit_id' => $this->primary_unit_id,
            'selling_price' => $this->selling_price,
            'cost_price' => $this->cost_price,
            'current_stock' => $this->current_stock,
            'min_stock' => $this->min_stock,
            'max_stock' => $this->max_stock,
            'weight' => $this->weight,
            'is_active' => $this->is_active,
            'is_direct_sale' => $this->is_direct_sale,
            'is_component' => $this->is_component,
            'warranty_months' => $this->warranty_months,
            'maintenance_period_months' => $this->maintenance_period_months,
            'point_earn' => $this->point_earn,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'barcode', $this->barcode])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'brand', $this->brand])
            ->andFilterWhere(['like', 'related_product_code', $this->related_product_code])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'location', $this->location]);

        return $dataProvider;
    }
}