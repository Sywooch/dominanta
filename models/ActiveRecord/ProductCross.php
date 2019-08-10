<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "product_cross".
 *
 * @property int $id
 * @property int $product_id
 * @property int $cross_id
 *
 * @property Product $cross
 * @property Product $product
 */
class ProductCross extends AbstractModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'cross_id'], 'integer'],
            [['cross_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['cross_id' => 'id']],
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
            'cross_id' => Yii::t('app', 'Cross ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCross()
    {
        return $this->hasOne(Product::className(), ['id' => 'cross_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
