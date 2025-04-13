<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_units".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int|null $base_unit_id
 * @property float|null $conversion_rate
 * @property string|null $description
 *
 * @property ProductUnit $baseUnit
 * @property ProductUnit[] $productUnits
 * @property Products[] $products
 */
class ProductUnit extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_units';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['base_unit_id', 'description'], 'default', 'value' => null],
            [['conversion_rate'], 'default', 'value' => 1.00],
            [['code', 'name'], 'required'],
            [['base_unit_id'], 'integer'],
            [['conversion_rate'], 'number'],
            [['description'], 'string'],
            [['code'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100],
            [['code'], 'unique'],
            [['base_unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductUnit::class, 'targetAttribute' => ['base_unit_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'base_unit_id' => 'Base Unit ID',
            'conversion_rate' => 'Conversion Rate',
            'description' => 'Description',
        ];
    }

    /**
     * Gets query for [[BaseUnit]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBaseUnit()
    {
        return $this->hasOne(ProductUnit::class, ['id' => 'base_unit_id']);
    }

    /**
     * Gets query for [[ProductUnits]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductUnits()
    {
        return $this->hasMany(ProductUnit::class, ['base_unit_id' => 'id']);
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['primary_unit_id' => 'id']);
    }

}
