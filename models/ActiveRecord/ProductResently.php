<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "product_resently".
 *
 * @property int $id
 * @property string $add_time
 * @property int $product_id
 * @property string $hash
 * @property int $user_id
 *
 * @property Product $product
 * @property User $user
 */
class ProductResently extends AbstractModel
{
    public static $entityName = 'Resently product';

    public static $entitiesName = 'Resently products';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['add_time'], 'safe'],
            [['product_id', 'user_id'], 'integer'],
            [['hash'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'add_time' => Yii::t('app', 'Add Time'),
            'product_id' => Yii::t('app', 'Product ID'),
            'hash' => Yii::t('app', 'Hash'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
