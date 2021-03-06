<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "product_photo".
 *
 * @property int $id
 * @property int $product_id
 * @property int $photo_order
 *
 * @property Product $product
 */
class ProductPhoto extends AbstractModel
{
    public static $entityName = 'Product photo';

    public static $entitiesName = 'Product photos';

    public $upload;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'photo_order'], 'integer'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'photo_order' => Yii::t('app', 'Photo Order'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function getPhotoPath()
    {
        return $this->uploadFolder.DIRECTORY_SEPARATOR.$this->product_id.DIRECTORY_SEPARATOR.$this->id.'.jpg';
    }

    public function eventBeforeDelete()
    {
        if (file_exists($this->photoPath)) {
            @unlink($this->photoPath);
        }
    }
}
