<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "product_category".
 *
 * @property int $id
 * @property int $status
 * @property int $pid
 * @property string $category_name
 * @property string $slug
 * @property string $category_description
 * @property string $title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $last_update
 * @property string $link
 *
 * @property Product[] $products
 * @property ProductCategory $p
 * @property ProductCategory[] $productCategories
 */
class ProductCategory extends AbstractModel
{
    public static $entityName = 'Product category';

    public static $entitiesName = 'Product categories';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'pid'], 'integer'],
            [['category_name', 'slug'], 'required'],
            [['category_description', 'title', 'meta_keywords', 'meta_description'], 'string'],
            [['last_update'], 'safe'],
            [['category_name', 'slug', 'link'], 'string', 'max' => 255],
            [['pid'], 'exist', 'skipOnError' => true, 'targetClass' => ProductCategory::className(), 'targetAttribute' => ['pid' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'status' => Yii::t('app', 'Status'),
            'pid' => Yii::t('app', 'Pid'),
            'category_name' => Yii::t('app', 'Category name'),
            'slug' => Yii::t('app', 'Slug'),
            'category_description' => Yii::t('app', 'Category description'),
            'title' => Yii::t('app', 'Title'),
            'meta_keywords' => Yii::t('app', 'Meta keywords'),
            'meta_description' => Yii::t('app', 'Meta description'),
            'last_update' => Yii::t('app', 'Last update'),
            'link' => Yii::t('app', 'Ext link'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['cat_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getP()
    {
        return $this->hasOne(ProductCategory::className(), ['id' => 'pid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCategories()
    {
        return $this->hasMany(ProductCategory::className(), ['pid' => 'id']);
    }

    public function getCountSubcats()
    {
        return self::find()->where(['>=', 'status', self::STATUS_INACTIVE])->andWhere(['pid' => $this->id])->count();
    }

    public function getCountProducts()
    {
        return Product::find()->where(['>=', 'status', self::STATUS_INACTIVE])->andWhere(['cat_id' => $this->id])->count();
    }
}
