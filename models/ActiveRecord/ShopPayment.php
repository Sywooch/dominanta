<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "shop_payment".
 *
 * @property int $id
 * @property int $order_id
 * @property int $status
 * @property string $amount
 * @property string $payed
 * @property string $hash
 *
 * @property ShopOrder $order
 */
class ShopPayment extends AbstractModel
{
    public static $entityName = 'Shop payment';

    public static $entitiesName = 'Shop payments';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'status'], 'integer'],
            [['amount', 'payed'], 'number'],
            [['hash'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopOrder::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'status' => Yii::t('app', 'Status'),
            'amount' => Yii::t('app', 'Amount'),
            'payed' => Yii::t('app', 'Payed'),
            'hash' => Yii::t('app', 'Hash'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(ShopOrder::className(), ['id' => 'order_id']);
    }
}
