<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "product_category_filter".
 *
 * @property int $id
 * @property int $category_id
 * @property int $property_id
 * @property int $filter_order
 * @property string $filter_view
 *
 * @property ProductCategory $category
 * @property Property $property
 */
class ProductCategoryFilter extends AbstractModel
{
    public static $entityName = 'Category filter';

    public static $entitiesName = 'Category filters';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'property_id', 'filter_order'], 'integer'],
            [['filter_view'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['property_id'], 'exist', 'skipOnError' => true, 'targetClass' => Property::className(), 'targetAttribute' => ['property_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'category_id' => Yii::t('app', 'Category ID'),
            'property_id' => Yii::t('app', 'Property ID'),
            'filter_order' => Yii::t('app', 'Filter Order'),
            'filter_view' => Yii::t('app', 'Filter View'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(ProductCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperty()
    {
        return $this->hasOne(Property::className(), ['id' => 'property_id']);
    }
}
