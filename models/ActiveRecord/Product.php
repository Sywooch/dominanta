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

    public $upload;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cat_id', 'status', 'vendor_id'], 'integer'],
            [['product_name', 'slug'], 'required', 'on' => self::SCENARIO_FORM],
            ['slug', 'match', 'pattern' => '/^[A-z0-9_-]*$/i', 'on' => self::SCENARIO_FORM],
            ['slug', 'uniqueSlugValidator', 'on' => self::SCENARIO_FORM],
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
            'cat_id' => Yii::t('app', 'Product category'),
            'status' => Yii::t('app', 'Status'),
            'product_name' => Yii::t('app', 'Product name'),
            'slug' => Yii::t('app', 'Slug'),
            'product_desc' => Yii::t('app', 'Product description'),
            'price' => Yii::t('app', 'Price'),
            'old_price' => Yii::t('app', 'Old price'),
            'quantity' => Yii::t('app', 'Quantity available'),
            'discount' => Yii::t('app', 'Discount'),
            'labels' => Yii::t('app', 'Labels'),
            'properties' => Yii::t('app', 'Properties'),
            'unit' => Yii::t('app', 'Unit'),
            'packing_quantity' => Yii::t('app', 'Packing quantity'),
            'ext_code' => Yii::t('app', 'Ext code'),
            'int_code' => Yii::t('app', 'Int code'),
            'link' => Yii::t('app', 'Link'),
            'vendor_id' => Yii::t('app', 'Vendor'),
            'title' => Yii::t('app', 'Title'),
            'meta_keywords' => Yii::t('app', 'Meta keywords'),
            'meta_description' => Yii::t('app', 'Meta description'),
            'last_update' => Yii::t('app', 'Last update'),
        ];
    }

    public function uniqueSlugValidator($attribute, $params)
    {
        $checkQuery = self::find()->where(['slug' => $this->slug])->andWhere(['cat_id' => $this->cat_id == '' ? NULL : $this->cat_id]);

        if ($this->id) {
            $checkQuery->andWhere(['!=', 'id', $this->id]);
        }

        $find = $checkQuery->one();

        if ($find) {
            $this->addError($attribute, Yii::t('app', 'This value must be unique within a subsection.'));
        }
    }

    public function eventBeforeInsert()
    {
        $checkQuery = self::find()->where(['slug' => $this->slug])->andWhere(['cat_id' => $this->cat_id == '' ? NULL : $this->cat_id]);

        $find = $checkQuery->max();

        if ($find) {
            $this->slug .= '-'.$find;
        }

        $this->eventBeforeUpdate();
    }

    public function eventBeforeUpdate()
    {
        $this->last_update = self::getDbTime();
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

    public function getAllPhotos($object = false)
    {
        $product_photos = ProductPhoto::find()->where(['product_id' => $this->id])->orderBy(['photo_order' => SORT_ASC])->all();
        $photos = [];

        foreach ($product_photos AS $photo) {
            if (file_exists($photo->photoPath)) {
                if ($object) {
                    if (is_string($object)) {
                        $idx = $object;
                    } else {
                        $idx = $photo->photoPath;
                    }

                    $photos[$photo->$idx] = $photo;
                } else {
                    $photos[] = $photo->photoPath;
                }
            }
        }

        return $photos;
    }

    public function getMainPhoto()
    {
        return isset($this->allPhotos[0]) ? $this->allPhotos[0] : false;
    }

    public function getProductLink()
    {
        return $this->cat->catLink.'/'.$this->slug;
    }

    public function getRealPrice()
    {
        return $this->price - ($this->price * ($this->discount / 100));
    }

    public function getLabels()
    {
        return ProductLabel::find()->innerJoin(ProductLabels::tableName(), ProductLabels::tableName().'.label_id='.ProductLabel::tableName().'.id')
                                   ->where(['product_id' => $this->id])
                                   ->all();
    }
}
