<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property int $category_id
 * @property string $code
 * @property string|null $barcode
 * @property string $name
 * @property string|null $brand
 * @property float $selling_price
 * @property float $cost_price
 * @property int|null $current_stock
 * @property int|null $min_stock
 * @property int|null $max_stock
 * @property int $primary_unit_id
 * @property string|null $related_product_code
 * @property float|null $weight
 * @property int|null $is_active
 * @property int|null $is_direct_sale
 * @property string|null $description
 * @property string|null $note
 * @property string|null $location
 * @property int|null $is_component
 * @property int|null $warranty_months
 * @property int|null $maintenance_period_months
 * @property int|null $point_earn
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ProductCategories $category
 * @property OrderItems[] $orderItems
 * @property ProductUnits $primaryUnit
 * @property ProductImages[] $productImages
 * @property ProductWarranties[] $productWarranties
 */
class Product extends \yii\db\ActiveRecord
{

	// Định nghĩa hằng số STATUS_ACTIVE
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['barcode', 'brand', 'related_product_code', 'weight', 'description', 'note', 'location'], 'default', 'value' => null],
            [['point_earn'], 'default', 'value' => 0],
            [['is_direct_sale'], 'default', 'value' => 1],
            [['category_id', 'code', 'name', 'selling_price', 'cost_price', 'primary_unit_id', 'created_at', 'updated_at'], 'required'],
            [['category_id', 'current_stock', 'min_stock', 'max_stock', 'primary_unit_id', 'is_active', 'is_direct_sale', 'is_component', 'warranty_months', 'maintenance_period_months', 'point_earn', 'created_at', 'updated_at'], 'integer'],
            [['selling_price', 'cost_price', 'weight'], 'number'],
            [['description', 'note'], 'string'],
            [['code', 'related_product_code'], 'string', 'max' => 50],
            [['barcode', 'location'], 'string', 'max' => 100],
            [['name', 'brand'], 'string', 'max' => 255],
            [['code'], 'unique'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductCategories::class, 'targetAttribute' => ['category_id' => 'id']],
            [['primary_unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductUnits::class, 'targetAttribute' => ['primary_unit_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'code' => 'Code',
            'barcode' => 'Barcode',
            'name' => 'Name',
            'brand' => 'Brand',
            'selling_price' => 'Selling Price',
            'cost_price' => 'Cost Price',
            'current_stock' => 'Current Stock',
            'min_stock' => 'Min Stock',
            'max_stock' => 'Max Stock',
            'primary_unit_id' => 'Primary Unit ID',
            'related_product_code' => 'Related Product Code',
            'weight' => 'Weight',
            'is_active' => 'Is Active',
            'is_direct_sale' => 'Is Direct Sale',
            'description' => 'Description',
            'note' => 'Note',
            'location' => 'Location',
            'is_component' => 'Is Component',
            'warranty_months' => 'Warranty Months',
            'maintenance_period_months' => 'Maintenance Period Months',
            'point_earn' => 'Point Earn',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
	
	public function getImageUrl()
	{
		$primaryImage = ProductImage::find()
			->where(['product_id' => $this->id, 'is_primary' => 1])
			->one();
			
		if ($primaryImage) {
			return $primaryImage->image_url;
		}
		
		// Nếu không có ảnh, trả về ảnh mặc định
		return '/images/no-image.png';
	}

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(ProductCategory::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[PrimaryUnit]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrimaryUnit()
    {
        return $this->hasOne(ProductUnit::class, ['id' => 'primary_unit_id']);
    }

    /**
     * Gets query for [[ProductImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductImages()
    {
        return $this->hasMany(ProductImage::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[ProductWarranties]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductWarranties()
    {
        return $this->hasMany(ProductWarranty::class, ['product_id' => 'id']);
    }

}
