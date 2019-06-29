<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property int $cat_id
 * @property int $status
 * @property string $product_name
 * @property string $slug
 * @property string $product_desc
 * @property string $price
 * @property string $old_price
 * @property string $quantity
 * @property string $discount
 * @property string $labels
 * @property string $properties
 * @property string $unit
 * @property string $packing_quantity
 * @property string $ext_code
 * @property string $int_code
 * @property string $link
 * @property int $vendor_id
 * @property string $title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $last_update
 *
 * @property ProductCategory $cat
 * @property Vendor $vendor
 * @property ProductDoc[] $productDocs
 * @property ProductPhoto[] $productPhotos
 * @property ProductProperty[] $productProperties
 */
class Product extends AbstractModel
{
    public static $entityName = 'Product';

    public static $entitiesName = 'Products';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cat_id', 'status', 'vendor_id'], 'integer'],
            [['product_name', 'slug'], 'required'],
            [['product_desc', 'discount', 'labels', 'properties', 'link', 'meta_keywords', 'meta_description'], 'string'],
            [['price', 'old_price', 'quantity', 'packing_quantity'], 'number'],
            [['last_update'], 'safe'],
            [['product_name', 'slug', 'unit', 'ext_code', 'int_code', 'title'], 'string', 'max' => 255],
            [['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductCategory::className(), 'targetAttribute' => ['cat_id' => 'id']],
            [['vendor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vendor::className(), 'targetAttribute' => ['vendor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'cat_id' => Yii::t('app', 'Cat ID'),
            'status' => Yii::t('app', 'Status'),
            'product_name' => Yii::t('app', 'Product Name'),
            'slug' => Yii::t('app', 'Slug'),
            'product_desc' => Yii::t('app', 'Product Desc'),
            'price' => Yii::t('app', 'Price'),
            'old_price' => Yii::t('app', 'Old Price'),
            'quantity' => Yii::t('app', 'Quantity'),
            'discount' => Yii::t('app', 'Discount'),
            'labels' => Yii::t('app', 'Labels'),
            'properties' => Yii::t('app', 'Properties'),
            'unit' => Yii::t('app', 'Unit'),
            'packing_quantity' => Yii::t('app', 'Packing Quantity'),
            'ext_code' => Yii::t('app', 'Ext Code'),
            'int_code' => Yii::t('app', 'Int Code'),
            'link' => Yii::t('app', 'Link'),
            'vendor_id' => Yii::t('app', 'Vendor ID'),
            'title' => Yii::t('app', 'Title'),
            'meta_keywords' => Yii::t('app', 'Meta Keywords'),
            'meta_description' => Yii::t('app', 'Meta Description'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCat()
    {
        return $this->hasOne(ProductCategory::className(), ['id' => 'cat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendor()
    {
        return $this->hasOne(Vendor::className(), ['id' => 'vendor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductDocs()
    {
        return $this->hasMany(ProductDoc::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductPhotos()
    {
        return $this->hasMany(ProductPhoto::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductProperties()
    {
        return $this->hasMany(ProductProperty::className(), ['product_id' => 'id']);
    }
}
