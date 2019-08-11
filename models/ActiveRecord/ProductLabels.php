<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "product_labels".
 *
 * @property int $id
 * @property int $product_id
 * @property int $label_id
 *
 * @property ProductLabel $label
 * @property Product $product
 */
class ProductLabels extends AbstractModel
{
    public static $entityName = 'Product label relation';

    public static $entitiesName = 'Product label relations';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'label_id'], 'integer'],
            [['label_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductLabel::className(), 'targetAttribute' => ['label_id' => 'id']],
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
            'label_id' => Yii::t('app', 'Label ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLabel()
    {
        return $this->hasOne(ProductLabel::className(), ['id' => 'label_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
