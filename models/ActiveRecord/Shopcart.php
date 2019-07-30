<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "shopcart".
 *
 * @property int $id
 * @property int $user_id
 * @property string $hash
 * @property int $product_id
 * @property string $quantity
 *
 * @property Product $product
 * @property User $user
 */
class Shopcart extends AbstractModel
{
    public static $cookiename = 'shopcart';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'product_id'], 'integer'],
            [['quantity'], 'number'],
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
            'user_id' => Yii::t('app', 'User ID'),
            'hash' => Yii::t('app', 'Hash'),
            'product_id' => Yii::t('app', 'Product ID'),
            'quantity' => Yii::t('app', 'Quantity'),
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

    public function eventBeforeInsert()
    {
        $this->eventBeforeUpdate();
    }

    public function eventBeforeUpdate()
    {
        $this->last_update = $this->dbTime;
    }
}
