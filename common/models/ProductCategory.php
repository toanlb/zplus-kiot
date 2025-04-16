<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_categories".
 *
 * @property int $id
 * @property string $name
 * @property int|null $parent_id
 * @property int|null $level
 * @property string|null $description
 * @property int|null $status
 * @property int $created_at
 *
 * @property ProductCategory $parent
 * @property ProductCategory[] $productCategory
 * @property Product[] $products
 */
class ProductCategory extends \yii\db\ActiveRecord
{

	// Định nghĩa hằng số STATUS_ACTIVE
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'description'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 1],
            [['name', 'created_at'], 'required'],
            [['parent_id', 'level', 'status', 'created_at'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductCategory::class, 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'parent_id' => 'Parent ID',
            'level' => 'Level',
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(ProductCategory::class, ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[ProductCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductCategory()
    {
        return $this->hasMany(ProductCategory::class, ['parent_id' => 'id']);
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasMany(Product::class, ['category_id' => 'id']);
    }

}
