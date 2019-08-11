<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "delivery_address".
 *
 * @property int $id
 * @property int $user_id
 * @property string $address_name
 * @property string $address
 *
 * @property User $user
 */
class DeliveryAddress extends AbstractModel
{
    public static $entityName = 'Delivery address';

    public static $entitiesName = 'Delivery addresses';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['address'], 'string'],
            [['address_name', 'address'], 'required'],
            [['address_name'], 'string', 'max' => 255],
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
            'user_id' => Yii::t('app', 'User'),
            'address_name' => Yii::t('app', 'Address name'),
            'address' => Yii::t('app', 'Address'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
