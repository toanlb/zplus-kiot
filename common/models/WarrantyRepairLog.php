<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "warranty_repair_logs".
 *
 * @property int $id
 * @property int $warranty_id
 * @property string $repair_date
 * @property string|null $technician
 * @property string|null $repair_location
 * @property string $issue_description
 * @property string|null $repair_description
 * @property string|null $parts_replaced
 * @property float|null $repair_cost
 * @property string|null $status
 * @property string|null $next_service_recommendation
 * @property int $created_at
 *
 * @property ProductWarranties $warranty
 */
class WarrantyRepairLog extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'warranty_repair_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['technician', 'repair_location', 'repair_description', 'parts_replaced', 'next_service_recommendation'], 'default', 'value' => null],
            [['repair_cost'], 'default', 'value' => 0.00],
            [['status'], 'default', 'value' => 'pending'],
            [['warranty_id', 'repair_date', 'issue_description', 'created_at'], 'required'],
            [['warranty_id', 'created_at'], 'integer'],
            [['repair_date'], 'safe'],
            [['issue_description', 'repair_description', 'parts_replaced', 'next_service_recommendation'], 'string'],
            [['repair_cost'], 'number'],
            [['technician', 'repair_location'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 20],
            [['warranty_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductWarranties::class, 'targetAttribute' => ['warranty_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'warranty_id' => 'Warranty ID',
            'repair_date' => 'Repair Date',
            'technician' => 'Technician',
            'repair_location' => 'Repair Location',
            'issue_description' => 'Issue Description',
            'repair_description' => 'Repair Description',
            'parts_replaced' => 'Parts Replaced',
            'repair_cost' => 'Repair Cost',
            'status' => 'Status',
            'next_service_recommendation' => 'Next Service Recommendation',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Warranty]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWarranty()
    {
        return $this->hasOne(ProductWarranties::class, ['id' => 'warranty_id']);
    }

}
